<?php
/**
 * AuthController.php - AuthController
 * Controlador de autenticação
 * 
 * @package controllers
 * @author Mauro Ribeiro
 * @since 2011-06
 */
class AuthController extends Zend_Controller_Action
{

    /**
     * Método chamado antes de qualquer e toda action
     */
    public function init()
    {
        // não deixa o cara passar se ele foi bloqueado
        $this->_helper->BotBlocker->filter();
    }

    public function indexAction()
    {
                return $this->_redirect('autenticar');
    }
    
    /**
     * Rotina de login automático do usuário
     * @param int $id           id do usuário
     * @param string $hash      hash de segurança
     * @return boolean
     */
    public function autoLoginAction() {
        $url = urldecode($this->_getParam('url'));
        $id = urldecode($this->_getParam('user_id'));
        $hash = urldecode($this->_getParam('hash'));
        
        $url = str_replace('__', '/', $url);
                
        if ($id && $hash) {

            $user_mapper = new Ee_Model_Users();
            $user = $user_mapper->find($id);
            if ($user) {
                // gera hash correto
                $correct_hash = sha1($user->login.'eeAutoLogin'.date('YW'));
                $correct_hash .= sha1($user->password.'eeAutoLogin'.date('YW'));
                $this->_helper->Tracker->userEvent('auto login attempt');
                // os hashes verificam
                if ($hash == $correct_hash) {

                    $dbAdapter = Zend_Db_Table::getDefaultAdapter();
                    // Inicia o adaptador Zend_Auth para banco de dados
                    $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
                    $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production','production');
                    $authAdapter->setTableName('users')
                                ->setIdentityColumn('login')
                                ->setCredentialColumn('password');
                    // Define os dados para processar o login
                    $authAdapter->setIdentity($user->login)
                                ->setCredential($user->password);
                    // Efetua o login
                    $auth = Zend_Auth::getInstance();
                    $result = $auth->authenticate($authAdapter);
                    // Verifica se o login foi efetuado com sucesso
                    if ( $result->isValid() ) {
                        // Armazena os dados do usuário em sessão, apenas desconsiderando
                        // a senha do usuário
                        $info = $authAdapter->getResultRowObject(null, 'password');
                        $storage = $auth->getStorage();
                        $storage->write($info);

                        $user = $auth->getIdentity();

                        // se usuário ainda não está confirmado
                        // mas logou, entao confirma!
                        $user_mapper = new Ee_Model_Users();
                        if ($user->group == 'unconfirmed') {
                            $user->group = 'user';
                        }
                        $user->date_updated = date('Y-m-d H:i:s');
                        $user_mapper->save($user);

                        // Bota os dados do usuário na sessão.
                        $userData = new Zend_Session_Namespace('UserData');
                        $userData->user = $user;
                        $company_mapper = new Ee_Model_Companies();
                        $userData->user->company = $company_mapper->find($userData->user->company_id);
                        $this->_helper->Tracker->userEvent('logged in');
                        $this->_helper->Tracker->userEvent('auto login');
                    } else {
                        // Dados inválidos
                        $this->_helper->Tracker->userEvent('ERROR auto login');
                        $this->getResponse()->setHttpResponseCode(403); // forbidden
                    }
                }
            }
        }
                
        $this->_redirect($url);
        
    }
    
    
    /**
     * Loga o usuário pelo facebook
     */
    public function facebookLoginAction() {
        
        $fb_id = $this->_getParam('fbid');
        $fb_token = $this->_getParam('fbtoken'); 
        $redirect = $_GET['redirect'];
               
        // se o cara já está logado
        if (Zend_Auth::getInstance()->hasIdentity() == true) {
            $this->_helper->FlashMessenger(array(
                'message'=>'Você já está logado no sistema',
                'status'=>'alert')
            );
            return $this->_redirect('passo-a-passo');
        }
        
        $fp = fopen('https://graph.facebook.com/'.$fb_id.'?access_token='.$fb_token, 'r');
        $response = stream_get_contents($fp);
        $fbuser = Zend_Json::decode($response);
                
        
        if ($fbuser && isset($fbuser['email'])) {
            $email = $fbuser['email'];

            $dbAdapter = Zend_Db_Table::getDefaultAdapter();
            // Inicia o adaptador Zend_Auth para banco de dados
            $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
            $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production','production');
            $authAdapter->setTableName('users')
                        ->setIdentityColumn('login')
                        ->setCredentialColumn('login');
            // Define os dados para processar o login
            $authAdapter->setIdentity($email)
                        ->setCredential($email);
            // Efetua o login
            $auth = Zend_Auth::getInstance();
            $result = $auth->authenticate($authAdapter);
            // Verifica se o login foi efetuado com sucesso
            if ( $result->isValid() ) {
                // Armazena os dados do usuário em sessão, apenas desconsiderando
                // a senha do usuário
                $info = $authAdapter->getResultRowObject(null, 'password');
                $storage = $auth->getStorage();
                $storage->write($info);

                $user = $auth->getIdentity();

                // se usuário ainda não está confirmado
                // mas logou, entao confirma!
                $user_mapper = new Ee_Model_Users();
                if ($user->group == 'unconfirmed') {
                    $user->group = 'user';
                }
                $date_updated_old = $user->date_updated;
                $user->date_updated = date('Y-m-d H:i:s');
                $user_mapper->save($user);

                // Bota os dados do usuário na sessão.
                $user->date_updated_old = $date_updated_old;
                $userData = new Zend_Session_Namespace('UserData');
                $userData->user = $user;
                $company_mapper = new Ee_Model_Companies();
                $userData->user->company = $company_mapper->find($userData->user->company_id);
                $this->_helper->Tracker->userEvent('logged in');
                $this->_helper->Tracker->userEvent('facebook login');

                unset($user->date_updated_old);
                $this->_redirect($redirect);
                
            } else {
                // Email não cadastrado
                $this->_helper->FlashMessenger(array(
                    'message'=>'O email '.$fbuser['email'].' não está cadastrado',
                    'status'=>'error'
                ));
                $this->_helper->Tracker->userEvent('ERROR facebook login');
                $this->_redirect('login');
            }
        }
        else {
            // Não achou o email do cara
            $this->_helper->FlashMessenger(array(
                'message'=>'Erro ao tentar logar pelo Facebook',
                'status'=>'error'
            ));
            $this->_helper->Tracker->userEvent('ERROR facebook login');
            $this->_redirect('login');
        }
    }

    /**
     * Rotina de login do usuário
     * @param string $login         email do usuário
     * @param string $password      senha do usuário
     * @return boolean
     */
    public function login($login, $password , $first_login = false)
    {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        // Inicia o adaptador Zend_Auth para banco de dados
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production','production');
        $authAdapter->setTableName('users')
                    ->setIdentityColumn('login')
                    ->setCredentialColumn('password')
                    ->setCredentialTreatment("SHA1(CONCAT('".$config->security->pwdsalt."',?))");
        // Define os dados para processar o login
        $authAdapter->setIdentity($login)
                    ->setCredential($password);
        // Efetua o login
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);
        $userData = new Zend_Session_Namespace('UserData');

        // Verifica se o login foi efetuado com sucesso
        if ( $result->isValid() ) {
            // Armazena os dados do usuário em sessão, apenas desconsiderando
            // a senha do usuário
            $info = $authAdapter->getResultRowObject(null, 'password');
            $storage = $auth->getStorage();
            $storage->write($info);

            $user = $auth->getIdentity();

            // se usuário ainda não está confirmado
            // mas logou, entao confirma!
            $user_mapper = new Ee_Model_Users();
            if ($user->group == 'unconfirmed') {
                $user->group = 'user';
            }
            $date_updated_old = $user->date_updated;
            $user->date_updated = date('Y-m-d H:i:s');
            $user_mapper->save($user);

			//Pegar compania do usuario e atualizar o dateupdated
			$company_mapper = new Ee_Model_Companies();
			$company = $company_mapper->find($user->company_id);
			$company->date_updated = date('Y-m-d H:i:s');
			$company_mapper->save($company);

            // Bota os dados do usuário na sessão.
            $user->date_updated_old = $date_updated_old;
            $userData = new Zend_Session_Namespace('UserData');
            $userData->user = $user;
            $company_mapper = new Ee_Model_Companies();
            $userData->user->company = $company_mapper->find($userData->user->company_id);
            $this->_helper->Tracker->userEvent('logged in');
            
            if (!$first_login) 
                $this->_helper->Tracker->login($user);
            
            unset($user->date_updated_old);

            return true;
        }else {
            // Dados inválidos
            $this->_helper->Tracker->userEvent('ERROR log in');
            return false;
        }
    }

    /**
     * Login do usuário
     */
    public function loginAction()
    {
        // se o cara já está logado
        if (Zend_Auth::getInstance()->hasIdentity() == true) {
            $this->_helper->FlashMessenger(array(
                'message'=>'Você já está logado no sistema',
                'status'=>'alert')
            );
            return $this->_redirect('passo-a-passo');
        }
        $form = new Ee_Form_Login();
        $this->view->form = $form;
        // Verifica se existem dados de POST
        
        if ( $this->getRequest()->isPost() ) {
            $data = $this->getRequest()->getPost();
            // Formulário corretamente preenchido?
            if ( $form->isValid($data) ) {
                $login = $form->getValue('login');
                $password = $form->getValue('password');
                
                $auth = $this->login($login, $password);

                // usuário logou com sucesso
                if ( $auth ) {
                    $this->_helper->Tracker->track('form send', array('form_name'=>'login','form_status'=>'success'));
                    $redirect = $form->getValue('return');
                    if ($redirect == "") $redirect = "passo-a-passo";
                    $this->_redirect($redirect);
                } 
                // usuário digitou dados incorretos
                else {
                    $userData = new Zend_Session_Namespace('UserData');
                    /* Se errou pela primeira vez, marca. */
                    if ($userData->errorPassword != true){
                        $userData->errorPassword = true;
                        $this->_helper->FlashMessenger(array(
                            'message'=>'Usuário ou senha inválidos!',
                            'status'=>'error')
                        );
                    }
                    /* Senão, exibe mensagem e envia e-mail de recuperação de senha */
                    else{
                        $this->_helper->FlashMessenger(array(
                                'message'=>'Senha inválida. Uma nova senha foi enviada para seu email.',
                                'status'=>'error')
                        );
                        $this->forgotPassword($login);
                    }
                    $form->populate($data);
                    $this->_helper->Tracker->track('form send', array('form_name'=>'login','form_status'=>'error'));
                    //$this->getResponse()->setHttpResponseCode(403); // forbidden
                }
            } else {
                // Formulário preenchido de forma incorreta
                $form->populate($data);
                
                $this->_helper->Tracker->track('form send', array('form_name'=>'login','form_status'=>'error'));
            }
        }
    }

    /**
     * Usuário desloga do sistema
     */
    public function logoutAction()
    {
                $this->_helper->Tracker->userEvent('logged out');
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $userdata = new Zend_Session_Namespace('UserData');
        $this->view->user = $userdata->user;
        $this->_helper->Tracker->track('logout', array('url' => $userdata->Navigation->last));
        unset($userdata->user);

	$this->view->deleteaccount = (isset($_GET['action']) && $_GET['action'] == 'deleteaccount') ? true : false;
        
        $this->_helper->FlashMessenger(array(
            'message'=>'Você deslogou do sistema',
            'status'=>'alert')
        );
        
    }

    /**
     * Usuário se cadastra no sistema
     */
    public function signUpAction()
    {  
                // se o camarada está logado
        if (Zend_Auth::getInstance()->hasIdentity() == true) {
            $this->_helper->FlashMessenger(array(
                'message'=>'Você já está cadastrado no sistema',
                'status'=>'alert')
            );
            return $this->_redirect('passo-a-passo');
        }

        // navegação interrompida
        $interruption = $this->_getParam('interruption');
        // veio por facebook
        $facebook = $this->_getParam('facebook');
        
        $this->view->interruption = $interruption;
        if (!$interruption) {
            $this->_helper->Tracker->userEvent('started sign up');
        }
        $request = $this->getRequest();
        $form =  new Ee_Form_SignUp();

        if ($request->isPost()) {
     
            // se ta tudo preenchido bonitinhamente
            if ($form->isValid($request->getPost())) {
                // segurança contra bot
                $this->_helper->BotBlocker->timeFinish();
                if ($this->_helper->BotBlocker->timeCheck() == false || $form->honeyPotsCheck() == false)
                    $this->_helper->BotBlocker->block();
                
                $values = $form->getModels();
                $user_mapper = new Ee_Model_Users();
                $company_mapper = new Ee_Model_Companies();
                $user = new Ee_Model_Data_User($values->user);
                
                // salva os dados da empresa
                $values->company->date_created = date('Y-m-d H:i:s');
                $values->company->date_updated = $user->date_created;
                $company = new Ee_Model_Data_Company($values->company);
                $company_mapper->save($company);

                // salva os dados do usuário
                $password = $user->password;
                $user->group = 'user';
                $user->email = $user->login;
                $user->company_id = $company->id;
                $user->date_created = date('Y-m-d');
                $user->date_updated = $user->date_created;
                $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');
                $user->password = sha1($config->security->pwdsalt.$password);
                $user_mapper->save($user);
                
                // loga o cara
                $this->login($user->login, $password, true);
                
                $this->_helper->Tracker->userEvent('finished sign up');
                
                $userdata = new Zend_Session_Namespace('UserData');
                $this->_helper->Tracker->track('form send', array('form_name'=>'signup','form_status'=>'success'));
                $this->_helper->Tracker->signup($userdata->user);

                // email de boas vindas
                $this->_helper->EeMsg->welcomeEmail($user);
                
                $redirect = $form->getValue('return');
                $this->_redirect($redirect);
            }
            // se algum dado foi preenchido incorretamente
            else {
                $this->_helper->Tracker->userEvent('ERROR sign up');
                // inicia timer contra bot
                $this->_helper->BotBlocker->timeStart();
                $values = $form->getModels();
                $user_login_errors = $form->getElement('user_login')->getErrors();
                
                // se o cara tentou cadastrar um email já cadastrado
                if (isset($user_login_errors[0]) && $user_login_errors[0] == 'uniqueLoginNotMatch') {
                    $this->_helper->FlashMessenger(array(
                        'message'=>'O email <strong>'.$values->user->login.'</strong> já está cadastrado no sistema',
                        'status'=>'error')
                    );
                }
                $this->_helper->FlashMessenger(array(
                    'message'=>'Dados preenchidos incorretamente',
                    'status'=>'error')
                );
                
                $this->_helper->Tracker->track('form send', array('form_name'=>'sign-up','form_status'=>'error'));
            }
        }
        // se o cara acabou de entrar na página
        else {
            // inicia timer contra o bot
            $this->_helper->BotBlocker->timeStart();
            
            $userdata = new Zend_Session_Namespace('UserData');
            
            if (!$interruption) {
                $this->_helper->Tracker->register_once(array('signup_type'=>'organic','signup_url'=> $userdata->Navigation->last));
                
            }
            else {
                $this->_helper->Tracker->register_once(array('signup_type'=>'interruption','signup_url'=> $userdata->Navigation->last));
            }
            
            if ($facebook) {
                $fb_id = $this->_getParam('fbid');
                $fb_token = $this->_getParam('fbtoken'); 
                $fp = fopen('https://graph.facebook.com/'.$fb_id.'?access_token='.$fb_token, 'r');
                $response = stream_get_contents($fp);
                $fbuser = Zend_Json::decode($response);

                $nameexp = explode(' ',$fbuser['name']);
                $form->user_name->setValue($nameexp[0]);
                $form->user_family_name->setValue($nameexp[count($nameexp)-1]);
                $form->user_login->setValue($fbuser['email']);
                $form->user_login_confirmation->setValue($fbuser['email']);
                if (isset($fbuser['work']) && isset($fbuser['work'][0])) {
                    $form->company_name->setValue($fbuser['work'][0]['employer']['name']);
                }

            }

            $this->_helper->Tracker->track('signup start', array('url'=> $userdata->Navigation->last));
            
        }

        $this->view->form = $form;
    }

    /**
     * Se o usuário esqueceu a senha, gera uma nova senha e envia por email
     */
    public function forgotPassword($login)
    {
                $login = $this->_getParam('login');
        
        // aceita emails tipo joaozinho+teste@teste.com
        $login = str_replace(' ', '+', $login);
        
        $user_mapper = new Ee_Model_Users();
        $user = $user_mapper->findByLogin($login);

        $this->view->request = false;

        // se o email não está cadastrado
        if ($user == null) {
            $this->_helper->FlashMessenger(array(
                'message'=>'O e-mail '.$login.' não está cadastrado no nosso sistema. Será que você não usou outro e-mail no seu cadastro?',
                'status'=>'error')
            );
            $this->_forward('login');
        }
        // se o email está cadastrado, envia nova senha por email
        else  {
            // nova senha, só vale naquele dia
            $new_password = substr(sha1($user->id.$user->password.date('Y-m-d').'w0wnewPwD'),0,6);
            // hash de autenticação do email, só vale naquele dia
            $correct_hash = sha1($user->id.$user->login.date('Y-m-d').'0opsiforg0tit');
            // envia o email para o sujeito
            $this->_helper->EeMsg->forgotPasswordEmail($user, $new_password, $correct_hash);
            $this->view->user = $user;
        }
    }
    
    /**
     * Rota que gera uma nova senha e envia por email
     */
    public function forgotPasswordAction()
    {
        $login = $this->_getParam('login');
        
        $this->forgotPassword($login);
    }

    /**
     * Quando o cara que esqueceu a senha clicou no email para gerar uma nova
     * senha
     */
    public function generatePasswordAction()
    {
                $login = $this->_getParam('login');
        $hash = $this->_getParam('hash');

        // aceita emails tipo joaozinho+teste@gmail.com
        $login = str_replace(' ', '+', $login);
        
        // procura pelo broder
        $user_mapper = new Ee_Model_Users();
        $user = $user_mapper->findByLogin($login);

        // qual deve ser a nova senha, só vale naquele dia
        $new_password = substr(sha1($user->id.$user->password.date('Y-m-d').'w0wnewPwD'),0,6);
        // qual deve ser a hash de segurança, só vale naquele dia
        $correct_hash = sha1($user->id.$user->login.date('Y-m-d').'0opsiforg0tit');

        // se os hashes conferem
        if ($correct_hash == $hash) {
            $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');
            // salva a nova senha
            $user->password = sha1($config->security->pwdsalt.$new_password);
            $user_mapper->save($user);
            // loga o cara
            $this->login($login, $new_password);
            $this->view->user = $user;
        }
        // o link não é autêntico
        else {
            $this->_helper->Access->error('Não permitido', 403);
        }
    }

    /**
     * Usuário aceita o convite enviado por alguém
     */
    public function inviteAction()
    {
                // se o cara já estiver logado
        if (Zend_Auth::getInstance()->hasIdentity() == true) {
            $this->_helper->FlashMessenger(array(
                'message'=>'Você já está cadastrado no sistema',
                'status'=>'alert')
            );
            return $this->_redirect('passo-a-passo');
        }

        $email = $this->_getParam('email');
        $invite_id = $this->_getParam('invite_id');
        $date = $this->_getParam('date');

        $email = str_replace(' ', '+', $email);
        
        // verifica se o convite realmente existe
        $invite_mapper = new Ee_Model_Invites();
        $invite = $invite_mapper->check($invite_id, $email, $date);

        // se o convite não existe, direciona para o cadastro
        if (!$invite) {
            return $this->_redirect('cadastre-se');
        }

        $user_mapper = new Ee_Model_Users();

        // procura o convite
        $inviter = $user_mapper->find($invite->user_id);
        $this->view->inviter = $inviter;

        $request = $this->getRequest();
        $form =  new Ee_Form_SignUp();

        // se usuário preencheu o formulário
        if ($request->isPost()) {
            // se os dados foram preenchidos corretamente
            if ($form->isValid($request->getPost())) {
                // segurança contra bot
                $this->_helper->BotBlocker->timeFinish();
                if ($this->_helper->BotBlocker->timeCheck() == false || $form->honeyPotsCheck() == false)
                    $this->_helper->BotBlocker->block();

                $values = $form->getModels();

                $company_mapper = new Ee_Model_Companies();
                $user = new Ee_Model_Data_User($values->user);
                
                // salva os dados da empresa
                $values->company->date_created = date('Y-m-d H:i:s');
                $values->company->date_updated = $user->date_created;
                $company = new Ee_Model_Data_Company($values->company);
                $company_mapper->save($company);

                // salva os dados do usuário
                $password = $user->password;
                $user->group = 'user';
                $user->email = $user->login;
                $user->company_id = $company->id;
                $user->date_created = date('Y-m-d H:i:s');
                $user->date_updated = $user->date_created;
                $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');
                $user->password = sha1($config->security->pwdsalt.$password);
                $user_mapper->save($user);

                // salva o novo estado do convite
                $invite->invited_id = $user->id;
                $invite_mapper->save($invite);

                // trocam cartões automaticamente
                $contact->contact_id = $user->id;
                $contact->user_id = $inviter->id;
                $contact->date = date('Y-m-d H:i:s');
                $contact_mapper = new Ee_Model_Contacts();
                $contact_mapper->save($contact);
                $contact2->date = $contact->date;
                $contact2->contact_id = $inviter->id;
                $contact2->user_id = $user->id;
                $contact_mapper->save($contact2);

                // envia notificação para o convidado e o convidante
                $this->_helper->EeMsg->userNotify($user->id, $inviter->id, 'Vocês trocaram seus cartões.');
                $this->_helper->EeMsg->userNotify($inviter->id, $user->id, 'Aceitou o seu convite.');

                // login ostomático
                $this->login($user->login, $password);

                $this->_helper->Tracker->userEvent('finished sign up');

                $this->_redirect('e/'.$inviter->company->slug.'/avaliar?action=convite');
            }
            // dados preenchidos inválidos
            else {
                $this->_helper->Tracker->userEvent('ERROR sign up');
                // inicia timer contra bot
                $this->_helper->BotBlocker->timeStart();
                $values = $form->getModels();
                $user_login_errors = $form->getElement('user_login')->getErrors();
                // se usuário tentou cadastrar email já cadastrado
                if (isset($user_login_errors[0]) && $user_login_errors[0] == 'uniqueLoginNotMatch') {
                    $this->_helper->FlashMessenger(array(
                        'message'=>'O email <strong>'.$values->user->login.'</strong> já está cadastrado no sistema',
                        'status'=>'error')
                    );
                }
                $this->_helper->FlashMessenger(array(
                    'message'=>'Dados preenchidos incorretamente',
                    'status'=>'error')
                );
            }
        }
        // usuário ainda não preencheu o formulário
        else {
            // inicia o timer contra bots
            $this->_helper->BotBlocker->timeStart();
            // preenche formulário de cadastro com valores do convite
            $form->user_login->setValue($invite->email);
            $form->user_login_confirmation->setValue($invite->email);
            $form->company_region_id_select->setValue($inviter->company->city->region_id);
            $form->company_city_id->setValue($inviter->company->city_id);
            $name_explode = explode(' ', $invite->name);
            if (isset($name_explode[0])) $form->user_name->setValue($name_explode[0]);
            if (isset($name_explode[1])) $form->user_family_name->setValue($name_explode[1]);
        }

        $this->view->form = $form;
    }
}

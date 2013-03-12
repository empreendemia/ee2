<?php
/**
 * EeMsg.php - Ee_Controller_Helper_EeMsg
 * Helper para envio de mensagens e emails
 * 
 * @package controllers
 * @subpackage helpers
 * @author Mauro Ribeiro
 * @since 2011-08
 */
class Ee_Controller_Helper_EeMsg extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Envia email usando smtp do sendgrid
     * @param Zend_Mail $mail               email a ser enviado
     * @param string|boolean $category      categoria para atrelar no Sendgrid
     * @return boolean 
     * @author Mauro Ribeiro
     */
    private function sendEmail($mail, $category = false) {
        if($config->login->sendgrid->user && $config->login->sendgrid->user != '' && $config->login->sendgrid->password && $config->login->sendgrid->password != '') {
            $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');
            $login = array(
                'auth' => 'login',
                'username' => $config->login->sendgrid->user,
                'password' => $config->login->sendgrid->password
            );
            $transport = new Zend_Mail_Transport_Smtp('smtp.sendgrid.net', $login);
            if ($category) {
                if (is_array($category)) {
                    $categories_str = implode('","',$category);
                    $mail->addHeader('X-SMTPAPI','{"category":["'.$categories_str.'"]}');   
                }
                else {
                    $mail->addHeader('X-SMTPAPI','{"category":"'.$category.'"}');
                }
            }
            return $mail->send($transport);
        }
        else {
            return $mail->send();
        }
    }
    
    /**
     * Pega o objeto usuário caso tenha sido passado o id
     * @param int|Ee_Model_User_Data $user
     * @return Ee_Model_User_Data 
     */
    private function _user($user) {
        if (is_numeric($user)) {
            $user_mapper = new Ee_Model_Users();
            return $user_mapper->find($user);
        }
        else {
             return $user;
        }
    }

    /**
     * Renderiza a view do email
     * @param string $view_name     nome do arquivo da view
     * @param object $vars          variáveis para passar para a view
     * @return string               código html
     */
    private function _render($view_name, $vars) {
        // cria uma view
        $html = new Zend_View();
        $html->setScriptPath(APPLICATION_PATH . '/views/emails/');

        // atribui os dados
        foreach ($vars as $id => $value) {
            $html->assign($id, $value);
        }

        // render view
        $bodyText = $html->render($view_name.'.phtml');

        return $bodyText;
    }

    /**
     * Envia uma notificação de um usuário para outro
     * @param object|int $user          usuário recebendo a notificação
     * @param object|int $from_user     usuário enviando a notificação
     * @param string $message           mensagem a ser enviada 
     */
    public function userNotify($user, $from_user, $message) {
        if (is_numeric($user)) $user_id = $user;
        else $user_id = $user->id;
        if (is_numeric($from_user)) $from_user_id = $from_user;
        else $from_user_id = $from_user->id;

        // cria objeto com os valores
        $notify->user_id = $user_id;
        $notify->from_user_id = $from_user_id;
        $notify->type = 'simple';
        $notify->message = $message;
        $notify->date = date('Y-m-d H:i:s');

        // salva objeto no banco de dados
        $notify_mapper = new Ee_Model_Notifies();
        return $notify_mapper->save($notify);
    }


    /**
     * Email de troca de mensagens
     * @param int|Ee_Model_Data_User $user          usuário que recebeu
     * @param int|Ee_Model_Data_User $from_user     usuário que enviou
     * @param string $subject                       título da mensagem
     * @param type $body                            corpo da mensagem
     */
    public function contactEmail($user, $from_user, $subject, $body) {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');

        $user = $this->_user($user);
        $from_user = $this->_user($from_user);
        
        // passa os valores para a view e renderiza o html
        $view->user = $user;
        $view->from_user = $from_user;
        $view->message->body = $body;
        $view->message->title = $subject;
        $render = $this->_render('contact', $view);
        
        // monta o email
        $mail = new Zend_Mail('utf-8');
        $mail->setBodyHtml($render);
        $mail->setFrom($config->email->noreply->address, $from_user->fullName());
        $mail->setReplyTo($config->email->customer->address, $config->email->customer->name);
        $mail->addTo($user->login, $user->fullName());
        $mail->setSubject($subject);

        // envia o email
        return $this->sendEmail($mail, 'mensagem logado');
    }
    
/**
     * Email de troca de mensagens (usuário deslogado)
     * @param int|Ee_Model_Data_User $user          usuário que recebeu
     * @param int|Ee_Model_Data_User $from_user     usuário que enviou
     * @param int|Ee_Model_Data_User $from_email    e-mail do usuário que enviou
     * @param string $subject                       título da mensagem
     * @param type $body                            corpo da mensagem
     */
    public function loggedOutContactEmail($user, $from_user, $from_email, $subject, $body) {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');

        $user = $this->_user($user);
        $from_user = $this->_user($from_user);
        
        // passa os valores para a view e renderiza o html
        $view->user = $user;
        $view->from_user = $from_user;
        $view->message->title = $subject;
        $view->message->body = $body;
        $render = $this->_render('contactLoggedOut', $view);

        // monta o email
        $mail = new Zend_Mail('utf-8');
        $mail->setBodyHtml($render);
        $mail->setFrom($from_email, $from_user);
        $mail->setReplyTo($from_email, $from_user);
        $mail->addTo($user->login, $user->fullName());
        $mail->setSubject($subject);

        // envia o email
        return $this->sendEmail($mail, 'mensagem deslogado');
    }
    
    /**
     * Email para contato entre vendedor e comprador gerado pelo Mural de Serviços
     * Obs.: controle está em controllers/DemandsController.php
     * @param type $user_name                       nome do usuário que recebeu
     * @param type $user_email                      email do usuário que recebeu
     * @param int|Ee_Model_Data_User $from_user     usuário que enviou
     * @param type $body                            corpo da mensagem
     */
    public function demandContactEmail($user_name, $user_email, $from_user, $body) {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');
        
        $from_user = $this->_user($from_user);
        
        // passa os valores para a view e renderiza o html
        $view->body = $body;
        $render = $this->_render('contactDemand', $view);
        
        // monta o email
        $mail = new Zend_Mail('utf-8');
        $mail->setBodyHtml($render);
        $mail->setFrom($config->email->noreply->address, $user_name);
        $mail->setReplyTo($user_email, $user_name);
        $mail->addTo($user_email, $user_name);
        $mail->setSubject('Contato referente ao seu pedido no Empreendemia');

        // envia o email
        return $this->sendEmail($mail, 'mensagem demanda');
    }
    
    /**
     * Email de convite para rede
     * @param Ee_Model_Data_Invite $invite      convite que usuário mandou
     * @param Ee_Model_Data_User $user          usuário que convidou
     */
    public function inviteEmail($invite, $user) {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');

        $user = $this->_user($user);
        
        // passa os valores para view e renderiza o html
        $view->user = $user;
        $view->invite = $invite;
        $render = $this->_render('invite', $view);

        // monta o email
        $mail = new Zend_Mail('utf-8');
        $mail->setBodyHtml($render);
        $mail->setFrom($config->email->noreply->address, $user->fullName());
        $mail->setReplyTo($config->email->customer->address, $config->email->customer->name);
        $mail->addTo($invite->email, $invite->name);
        $mail->setSubject('[Empreendemia] Vamos trocar cartões?');

        // envia o email
        return $mail->send();
    }

    /**
     * Convida membro da empresa para participar da rede
     * @param Ee_Model_Data_Company $company    empresa que o cara é
     * @param int|Ee_Model_Data_User $user      usuário que convidou
     * @param string $password                  password gerado para o convidado
     * @return type 
     */
    public function addMember($company, $user, $password) {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');

        $user = $this->_user($user);
        
        // passa os valores para a view e renderiza o html
        $view->company = $company;
        $view->user = $user;
        $view->password = $password;
        $render = $this->_render('new_member', $view);

        // monta o email
        $mail = new Zend_Mail('utf-8');
        $mail->setBodyHtml($render);
        $mail->setFrom($config->email->noreply->address, $config->email->noreply->name);
        $mail->setReplyTo($config->email->customer->address, $config->email->customer->name);
        $mail->addTo($user->login, $user->name.' '.$user->family_name);
        $mail->setSubject('[Empreendemia] Você foi adicionado na empresa '.$company->name);
        
        // envia o email
        return $mail->send();
    }

    /**
     * Email de troca de cartões
     * @param int|Ee_Model_User_Data $user          cara que recebeu o pedido
     * @param int|Ee_Model_User_Data $from_user     cara que fez o pedido
     * @param string $message                       mensagem que o cara escreveu
     */
    public function cardEmail($user, $from_user, $message) {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');

        $user = $this->_user($user);
        $from_user = $this->_user($from_user);
        
        // atribuindo valores para a view e renderizando o html
        $view->user = $user;
        $view->from_user = $from_user;
        $view->message = $message;
        $render = $this->_render('card', $view);

        // cria o email
        $mail = new Zend_Mail('utf-8');
        $mail->setBodyHtml($render);
        $mail->setFrom($config->email->noreply->address, $config->email->noreply->name);
        $mail->setReplyTo($config->email->customer->address, $config->email->customer->name);
        $mail->addTo($user->login, $user->fullName());
        $mail->setSubject('[Empreendemia] '.$from_user->fullName().' quer trocar cartões');

        // envia o email
        return $this->sendEmail($mail, 'troca de cartao');
    }

    /**
     * Email de boas vindas
     * @param int|Ee_Model_Data_User $user      usuário que acabou de se cadastrar
     */
    public function welcomeEmail($user) {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');

        $user = $this->_user($user);
        
        // atribui os dados do usuário para a view e renderiza o html
        $view->user = $user;
        $render = $this->_render('welcome', $view);

        // cria o email
        $mail = new Zend_Mail('utf-8');
        $mail->setBodyHtml($render);
        $mail->setFrom('millor@empreendemia.com.br', 'Millor Machado');
        $mail->setReplyTo('millor@empreendemia.com.br', 'Millor Machado');
        $mail->addTo($user->login, $user->fullName());
        $mail->setSubject('Dicas de como conseguir mais negócios pelo Empreendemia');

        // envia o email
        return $this->sendEmail($mail, 'welcome');
    }

    /**
     * Envia email para admins da Empreendemia
     * @param string $subject       título do email
     * @param string $body          corpo da mensagem
     */
    public function adminsEmail($subject, $body) {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');

        $mail = new Zend_Mail('utf-8');
        $mail->setBodyHtml($body);
        $mail->setFrom($config->email->noreply->address, $config->email->noreply->name);
        $mail->setReplyTo($config->email->sysadmins->address, $config->email->sysadmins->address);
        $mail->addTo($config->email->sysadmins->address, $config->email->sysadmins->name);
        $mail->setSubject('[Empreendemia] '.$subject);
        return $mail->send();
    }

    /**
     * Email de feedback do usuário
     * @param int|Ee_Model_User_Data $user      usuário que enviou o feedback
     * @param string $feedback                  texto que o usuário enviou
     */
    public function feedbackEmail($user, $feedback) {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');

        $user = $this->_user($user);
        
        // atribui os dados para a view e renderiza o html
        $view->user = $user;
        $view->feedback = $feedback;
        $render = $this->_render('admins/feedback', $view);

        // monta o email
        $mail = new Zend_Mail('utf-8');
        $mail->setBodyHtml($render);
        $mail->setFrom($config->email->noreply->address, $config->email->noreply->name);
        $mail->addTo($config->email->customer->address, $config->email->customer->name);
        $mail->setReplyTo($user->login, $user->fullName());
        $mail->setSubject('[Empreendemia] Novo feedback de '.$user->fullName());

        // envia o email
        return $mail->send();
    }

    /**
     * Envia email de orçamento para membros da empresa
     * @param Ee_Model_Data_Budget $budget      dados do pedido de orçamento
     * @param Ee_Model_Data_Company $company    empresa que recebeu pedido
     * @param object $user                      usuário que pediu o orçamento
     */
    public function budgetEmail($budget, $company, $user) {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');

        $product_mapper = new Ee_Model_Products();
        $city_mapper = new Ee_Model_Cities();
        $region_mapper = new Ee_Model_Regions();

        // atribui os valores para a view e renderiza o html
        $view->budget = $budget;
        $view->company = $company;
        $view->user = $user;
        $view->products = $product_mapper->findByCompany($company);
        $view->city = $city_mapper->find($budget->company->city_id);
        if ($view->city) $view->city->region = $region_mapper->find($view->city->region_id);
        $render = $this->_render('budget', $view);

        // monta o email
        $mail = new Zend_Mail('utf-8');
        $mail->setBodyHtml($render);
        $mail->setFrom($config->email->noreply->address, $config->email->noreply->name);
        $mail->setReplyTo($budget->user->email, $budget->user->name);
        
        // procura os membros da empresa que recebeu o pedido
        $user_mapper = new Ee_Model_Users();
        $members = $user_mapper->findByCompany($company);
        foreach ($members as $member) {
            $mail->addTo($member->login, $member->fullName());
        }
        $mail->setSubject('[Empreendemia] Pedido de orçamento por '.$budget->user->name);
        
        // envia o email
        return $this->sendEmail($mail, 'budget request');
    }

    /**
     * Envia email para os membros da empresa que recebeu avaliação
     * @param Ee_Model_Data_Company $company        empresa que foi avaliada
     * @param int|Ee_Model_Data_User $from_user     usuário que avaliou
     * @param Ee_Model_Data_Business $business      avaliação
     */
    public function businessEmail($member, $company, $from_user, $business) {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');

        // envia notificação para membro da empresa avaliada
        $this->userNotify($member, $from_user, 'Avaliou sua empresa');
        
        // atribui os valores para view e renderiza o html
        $from_user = $this->_user($from_user);
        $view->user = $member;
        $view->company = $company;
        $view->from_user = $from_user;
        $view->business = $business;
        $render = $this->_render('business', $view);

        // monta o email
        $mail = new Zend_Mail('utf-8');
        $mail->setBodyHtml($render);
        $mail->setFrom($config->email->noreply->address, $config->email->noreply->name);
        $mail->addTo($member->login, $member->fullName());
        $mail->setReplyTo($config->email->customer->address, $config->email->customer->name);
        $mail->setSubject('[Empreendemia] '.$from_user->fullName().' avaliou sua empresa');
        
        // envia o email
        return $mail->send();
    }

    /**
     * Email com a nova senha para usuário que esqueceu a senha antiga
     * @param int|Ee_Model_Data_User $user      usuário que esqueceu a senha
     * @param string $new_password              nova senha
     * @param string $hash                      código de verificação
     */
    public function forgotPasswordEmail($user, $new_password, $hash) {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');

        $user = $this->_user($user);

        // atribui os valores para view e renderiza o html
        $view->user = $user;
        $view->new_password = $new_password;
        $view->hash = $hash;
        $render = $this->_render('forgot_password', $view);

        // cria o email
        $mail = new Zend_Mail('utf-8');
        $mail->setBodyHtml($render);
        $mail->setFrom($config->email->noreply->address, $config->email->noreply->name);
        $mail->setReplyTo($config->email->customer->address, $config->email->customer->name);
        $mail->addTo($user->login, $user->fullName());
        $mail->setSubject('[Empreendemia] Gerar nova senha');

        // envia o email
        return $mail->send();
    }

    /**
     * Email notificando usuário sobre o anúncio que criou e notifica os
     * sysadmins sobre o novo anúncio
     * @param int|Ee_Model_Data_User $user      usuário que criou anúncio
     * @param Ee_Model_Data_Ad $ad              anúncio criado
     * @param int $companies_count              número de empresas que vai atingir
     * @param int $months                       prazo em meses 
     * @param int $price                        preço que saiu
     */
    public function newAd($user, $ad, $companies_count, $months, $price) {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');

        $user = $this->_user($user);

        // atribui valores para a view e renderiza o html para os sysadmins
        $view->user = $user;
        $view->ad = $ad;
        $view->companies_count = $companies_count;
        $view->months = $months;
        $view->price = $price;
        $render = $this->_render('admins/new_ad', $view);
        $subject = 'Nova campanha';

        // envia notificação para sysadmins
        $this->adminsEmail($subject, $render);

        // renderiza email para o usuário
        $render = $this->_render('ad_payment', $view);

        // monta o email
        $mail = new Zend_Mail('utf-8');
        $mail->setBodyHtml($render);
        $mail->setFrom($config->email->noreply->address, $config->email->noreply->name);
        $mail->setReplyTo($config->email->customer->address, $config->email->customer->name);
        $mail->addTo($user->login, $user->fullName());
        $mail->setSubject('[Empreendemia] Sua campanha foi configurada!');

        // envia o email
        return $mail->send();
    }

    /**
     * Notifica os sysadmins de uma nova oferta cadastrada
     * @param int|Ee_Model_Data_User $user      usuário que cadastrou oferta
     * @param Ee_Model_Data_Product $product    produto da oferta
     */
    public function newOffer($user, $product) {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');

        $user = $this->_user($user);

        // atribui os valores para view e renderiza o html
        $view->user = $user;
        $view->product = $product;
        $render = $this->_render('admins/new_offer', $view);
        $subject = 'Nova oferta cadastrada';

        // envia o email para os sysadmins
        return $this->adminsEmail($subject, $render);
    }


    /**
     * Notifica os sysadmins de uma nova requisição de serviço
     * @param Ee_Model_Data_Deman $demand       demanda
     * @param int|Ee_Model_Data_User $user      usuário que cadastrou
     */
    public function newDemand($demand, $user) {
        $user = $this->_user($user);

        // monta e renderiza o html
        $view->user = $user;
        $view->demand = $demand;
        $render = $this->_render('admins/new_demand', $view);
        $subject = 'Novo serviço cadastrado';

        // envia o email para os sysadmins
        return $this->adminsEmail($subject, $render);
    }

    /**
     * Notifica os sysadmins de uma nova requisição de serviço de usuário deslogado
     * @param Ee_Model_Data_Deman $demand       demanda
     */
    public function newDemandLoggedOut($demand) {

        // monta e renderiza o html
        $view->demand = $demand;
        $render = $this->_render('admins/new_demand_logged_out', $view);
        $subject = 'Novo serviço cadastrado (usuário deslogado)';

        // envia o email para os sysadmins
        return $this->adminsEmail($subject, $render);
    }
    
    /**
     * Notifica sysadmins sobre novo premium
     * @param type $user
     * @param type $expirations
     * @param type $period
     * @return type 
     */
    public function startPremiumEmail($user, $expirations, $period) {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');

        $user = $this->_user($user);

        // atribui os valores para a view e renderiza o html
        $view->user = $user;
        $view->expirations = $expirations;
        $view->period = $period;
        $render = $this->_render('admins/start_premium', $view);
        if ($period == 'test-drive') $subject = 'Novo test drive Premium - '.$user->company->name;
        else if ($user->company->plan == null) $subject = 'Novo Premium - '.$user->company->name;
        else  $subject = 'Renovação Premium - '.$user->company->name;
        
        // envia o email
        return $this->adminsEmail($subject, $render);
    }


}

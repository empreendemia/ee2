<?php
/**
 * PanelController.php - PanelController
 * Painel de controle do usuário e da empresa
 * 
 * @package controllers
 * @author Mauro Ribeiro
 * @since 2011-06-22
 */
class PanelController extends Zend_Controller_Action
{
    /**
     * Método chamado antes de qualquer e toda action
     */
    public function init()
    {
        // precisa de autenticação do usuário
        $this->_helper->Access->passAuth();
    }

    public function indexAction()
    {
        $this->_redirect('painel/empresa');
    }

    public function userAction()
    {
        // action body
    }

    /**
     * Painel principal da empresa
     */
    public function companyAction()
    {
        $userdata = new Zend_Session_Namespace('UserData');
        $company_id = $userdata->user->company_id;
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($company_id);
        $this->view->company = $company;
    }

    /**
     * Notificações que o usuário recebeu
     */
    public function notifiesAction()
    {
        $userData = new Zend_Session_Namespace('UserData');
        $user = new Ee_Model_Data_User($userData->user);

        // pessoas que pediram troca de cartão
        $contact_mapper = new Ee_Model_Contacts();
        $requests = $contact_mapper->findRequests($user->id);

        // notificações da rede
        $notify_mapper = new Ee_Model_Notifies();
        $notifies = $notify_mapper->findByUser($user);

        $this->view->requests = $requests;
        $this->view->notifies = $notifies;
    }

    /**
     * Dados do perfil do usuário
     */
    public function userProfileAction()
    {
        $userdata = new Zend_Session_Namespace('UserData');

        $request = $this->getRequest();
        $form =  new Ee_Form_UserProfile();

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $values = $form->getModels();
                $user = (object) array_merge((array) $userdata->user, (array) $values->user);
                $user->date_updated = date('Y-m-d H:i:s');
                $userdata->user = $user;
                $user_mapper = new Ee_Model_Users();
                $user_mapper->save($user);
                $this->_helper->FlashMessenger(array('message'=>'Dados pessoais alterados com sucesso!','status'=>'success'));
            }
            else {
                $this->_helper->FlashMessenger(array('message'=>'Erro: alguns dados foram preenchidos incorretamente. <br />Por favor, verifique abaixo.','status'=>'error'));
            }
        }
        else {
            $form->populate(get_object_vars($userdata->user));
        }

        $this->view->form = $form;
    }

    /**
     * Dados do perfil da empresa
     */
    public function companyProfileAction()
    {
        $userdata = new Zend_Session_Namespace('UserData');
        $company_id = $userdata->user->company_id;
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($company_id);

        $request = $this->getRequest();
        $form =  new Ee_Form_CompanyProfile();
        $form->region_id_select->setValue($company->city->region_id);

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $values = $form->getModels();
                $company = (object) array_merge((array) $company, (array) $values->company);
                $company->date_updated = date('Y-m-d H:i:s');
                $userdata->user->company = $company;
                $company_mapper->save($company);
                $userdata->user->company = $company_mapper->find($company->id);
                $this->_helper->FlashMessenger(array('message'=>'Dados para contato da empresa alterados com sucesso!','status'=>'success'));
            }
            else {
                $this->_helper->FlashMessenger(array('message'=>'Erro: alguns dados foram preenchidos incorretamente. <br />Por favor, verifique abaixo.','status'=>'error'));
            }
        }
        else {
            $form->populate(get_object_vars($company));
        }

        $this->view->form = $form;

    }

    /**
     * Informação sobre a empresa
     */
    public function companyAboutAction()
    {
        $userdata = new Zend_Session_Namespace('UserData');
        $company_id = $userdata->user->company_id;
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($company_id);

        $is_premium = $company->isPlan('premium');

        $request = $this->getRequest();
        $form =  new Ee_Form_CompanyAbout(array('premium'=>$is_premium));

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $values = $form->getModels();
                $company = (object) array_merge((array) $company, (array) $values->company);
                $company->date_updated = date('Y-m-d H:i:s');
                $userdata->user->company = $company;
                /* Criacao do embed do Slideshare */
                if (isset($values->company->slides_url) && $values->company->slides_url != ""){ 
                    $call_url = 'http://www.slideshare.net/api/oembed/2?url=' . $values->company->slides_url . '&format=xml';
                    $response = file_get_contents($call_url);
                    $slides_embed = substr($response, strpos($response, '<html>') + strlen('<html>'), strpos($response, '</html>') - strpos($response, '<html>') - strlen('<html>'));
                    $company->slides_embed = $slides_embed;
                }
                else{
                    $company->slides_embed = new Zend_Db_Expr('NULL');
                    $company->slides_url = new Zend_Db_Expr('NULL');
                }
                /*--------------------------------*/
                $company_mapper->save($company);
                if (isset($company->about) && $company->about != '') $this->_helper->Tracker->userEvent('premium: edited my premium about');
                $this->_helper->FlashMessenger(array('message'=>'Dados sobre a empresa alterados com sucesso!','status'=>'success'));
            }
            else {
                $this->_helper->FlashMessenger(array('message'=>'Erro: alguns dados foram preenchidos incorretamente. <br />Por favor, verifique abaixo.','status'=>'error'));
            }
        }
        else {
            if ($is_premium) $this->_helper->Tracker->userEvent('premium: edit my premium about');
            $form->populate(get_object_vars($company));
        }

        $this->view->form = $form;
    }

    /**
     * Alterar logotipo da empresa
     */
    public function companyImageAction()
    {
        $userdata = new Zend_Session_Namespace('UserData');
        $this->view->company = new Ee_Model_Data_Company($userdata->user->company);
    }

    /**
     * Alterar foto do usuário
     */
    public function userImageAction()
    {
        $userdata = new Zend_Session_Namespace('UserData');
        $this->view->user = new Ee_Model_Data_User($userdata->user);
    }

    /**
     * Delete conta do usuario
     */
    public function deleteAccountAction()
    {
        $userdata = new Zend_Session_Namespace('UserData');
        $this->view->user = new Ee_Model_Data_User($userdata->user);
	if(isset($_GET['confirm']) && $_GET['confirm'] == 'true')
	{
	   $company_mapper = new Ee_Model_Companies();
	   $company = $company_mapper->find($userdata->user->company_id);

	   $company->status = "deleted";
	   $company_mapper->save($company);

	   die('<script>top.location.href = "../../logout?action=deleteaccount";</script>');
	}
    }

    /**
     * Remove notificação
     */
    public function removeNotifyAction()
    {
        $notify_id = $_POST['notify_id'];
        $notify_mapper = new Ee_Model_Notifies();
        $notify_mapper->delete($notify_id);
        die('Yeyeah!');
    }

    /**
     * Customiza layout da empresa Premium
     */
    public function companyPremiumCustomAction()
    {
        $this->_helper->Access->passAuth();

        $userdata = new Zend_Session_Namespace('UserData');
        $user = new Ee_Model_Data_User($userdata->user);
        $company = new Ee_Model_Data_Company($userdata->user->company);

        $request = $this->getRequest();
        $form = new Ee_Form_CustomPremium();

        $this->view->sent = false;

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $values = $form->getValues();
                if($form->card_image->receive() && $form->card_image->isUploaded()) {
                    $source = $form->card_image->getFileName();
                    $company_mapper = new Ee_Model_Companies();
                    $company_mapper->cardImageUpdate($company, $source);
                    $userdata->user->company->card_image = $company->card_image;
                    $this->_helper->Tracker->userEvent('premium: added card image');
                }
                if($form->side_image->receive() && $form->side_image->isUploaded()) {
                    $source = $form->side_image->getFileName();
                    $company_mapper = new Ee_Model_Companies();
                    $company_mapper->sideImageUpdate($company, $source);
                    $userdata->user->company->side_image = $company->side_image;
                    $this->_helper->Tracker->userEvent('premium: added side image');
                }
                $this->_helper->FlashMessenger(array(
                    'message'=>'Imagem(ns) enviada(s) com sucesso!',
                    'status'=>'success')
                );
                $this->view->sent = true;
            }
            else {
                $this->_helper->FlashMessenger(array('message'=>'Erro ao atualizar imagem','status'=>'error'));
            }
        }
        else {
            $this->_helper->Tracker->userEvent('premium: custom template');
        }

        $this->view->form = $form;
        $this->view->company = $company;
    }

    /**
     * Adiciona produto à empresa
     */
    public function companyAddProductAction()
    {
        $userdata = new Zend_Session_Namespace('UserData');
        $user = new Ee_Model_Data_User($userdata->user);
        $company = new Ee_Model_Data_Company($userdata->user->company);
        
	$this->view->template = isset($_GET['action']) && $_GET['action'] == 'campanha' ? 'modal_iframe' : 'panel_company';

        $request = $this->getRequest();
        $form = new Ee_Form_Product();

        $this->view->sent = false;

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $values = $form->getModels();
                $product = $values->product;
                $product->company_id = $company->id;
                $product->company = $company;
                $product->date_created = date('Y-m-d H:i:s');
                $product->date_updated = $product->date_created;
                $product->offer_status = "inactive";

                $product_mapper = new Ee_Model_Products();
                $product_mapper->save($product);

                if($form->image->receive() && $form->image->isUploaded()) {
                    $source = $form->image->getFileName();
                    $product_image = $product_mapper->imageUpdate($product, $source);+
                    $product_mapper->save($product);
                }

                $this->_helper->FlashMessenger(array(
                    'message'=>'Produto adicionado com sucesso!',
                    'status'=>'success')
                );

                if(isset($_GET['action']) && $_GET['action'] == 'campanha')
		{
		    die("<script>window.open('../../../publicidade/configurar-campanha', '_top')</script>");
		}
		else
		{
                    $this->_redirect('painel/empresa/produtos?pid='.$product->id);
		}
            }
            else {
                $this->_helper->FlashMessenger(array('message'=>'Erro ao adicionar produto','status'=>'error'));
            }
        }

        $this->view->form = $form;
    }

    /**
     * Lista produtos da empresa
     */
    public function companyProductsAction()
    {
        $this->view->added_product = isset($_GET['pid'])? $_GET['pid'] : false;
        $userdata = new Zend_Session_Namespace('UserData');
        $user = new Ee_Model_Data_User($userdata->user);
        $company = new Ee_Model_Data_Company($userdata->user->company);

        $is_premium = $company->isPlan('premium');
        if ($is_premium) $this->_helper->Tracker->userEvent('premium: list my products');

        $product_mapper = new Ee_Model_Products();
        $products = $product_mapper->findByCompany($company);
        $this->view->products = $products;

    }

    /**
     * Troca senha do usuário
     */
    public function userChangePasswordAction()
    {
        $userData = new Zend_Session_Namespace('UserData');
        $user_mapper = new Ee_Model_Users();
        $user = $user_mapper->find($userData->user->id);

        $request = $this->getRequest();
        $form =  new Ee_Form_ChangePassword();

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $values = $form->getValues();
                $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');
                if (sha1($config->security->pwdsalt.$values['password']) == $user->password) {
                    $user->date_updated = date('Y-m-d H:i:s');
                    $user->password = sha1($config->security->pwdsalt.$values['new_password']);
                    $userdata->user = $user;
                    $user_mapper->save($user);
                    $this->_helper->FlashMessenger(array('message'=>'Senha alterada com sucesso!','status'=>'success'));
                }
                else {
                    $this->_helper->FlashMessenger(array('message'=>'Senha incorreta','status'=>'error'));
                }
            }
            else {
                $this->_helper->FlashMessenger(array('message'=>'Erro: alguns dados foram preenchidos incorretamente. <br />Por favor, verifique abaixo.','status'=>'error'));
            }
        }
        else {
            
        }

        $this->view->form = $form;
    }

    /**
     * Lista membros da empresa
     */
    public function companyMembersAction()
    {
        $userdata = new Zend_Session_Namespace('UserData');
        $company_id = $userdata->user->company_id;
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($company_id);

        $user_mapper = new Ee_Model_Users();

        $request = $this->getRequest();
        $form =  new Ee_Form_AddCompanyMember();

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $user = $form->getModels()->user;
                $password = substr(sha1($company->id.date('Y-m-d h:i:s').'n3wm3mbER'),0,6);
                $user->company_id = $company_id;
                $user->group = 'unconfirmed';
                $this->_helper->EeMsg->addMember($company, $user, $password);
                
                $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');
                $user->password = sha1($config->security->pwdsalt.$password);
                $user_mapper->save($user);
                $this->_helper->FlashMessenger(array('message'=>'Novo membro adicionado com sucesso!','status'=>'success'));
                $this->_redirect('painel/empresa/pessoas?user_id='.$user->id);
            }
            else {
                $this->_helper->FlashMessenger(array('message'=>'Erro: alguns dados foram preenchidos incorretamente. <br />Por favor, verifique abaixo.','status'=>'error'));
            }
        }
        else {

        }

        $this->view->form = $form;
        $users = $user_mapper->findByCompany($company, array('where' => '1 = 1'));
        $this->view->users = $users;
        $this->view->user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
    }

    /**
     * Convida alguém de fora para se cadastrar na rede
     */
    public function userInviteAction()
    {
        $user_mapper = new Ee_Model_Users();
        $from_user = $user_mapper->find($this->_helper->Access->getAuth()->id);
        $invite_mapper = new Ee_Model_Invites();
        $this->view->sent = false;
        $request = $this->getRequest();


        if ($request->isPost()) {
            $this->view->sent = true;
            
            $invs = array();
            foreach ($_POST as $id => $post) {
                $explode_id = explode('_', $id);
                if ($explode_id[0] == 'name') $invs[$explode_id[1]]->name = $post;
                else if ($explode_id[0] == 'email') $invs[$explode_id[1]]->email = $post;
            }

            $invites = array();
            $users = array();
            $errors = array();
            foreach ($invs as $inv) {

                $error = false;
                $email_validator = new Zend_Validate_EmailAddress();
                if (!isset($inv->name) || !$inv->name) $error = true;
                else if (!isset($inv->email) || !$inv->email) $error = true;
                else if (!$email_validator->isValid($inv->email)) $error = true;
          
                if ($error) {
                    $errors[] = $inv;
                }
                else {
                    $user = $user_mapper->findByLogin($inv->email);
                    if ($user) {
                        $users[] = $user;
                    }
                    else {
                        $invites[] = $inv;
                        $inv->user_id = $from_user->id;
                        $invite_mapper->save($inv);
                        $this->_helper->EeMsg->inviteEmail($inv, $from_user);
                    }
                }
            }
            $this->view->invites = $invites;
            $this->view->users = $users;
            $this->view->errors = $errors;
        }
    }


}


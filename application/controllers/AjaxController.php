<?php
/**
 * AjaxController.php - AjaxController
 * Controlador de chamadas ajax
 * 
 * @package controllers
 * @author Mauro Ribeiro
 * @since 2011-06
 */
class AjaxController extends Zend_Controller_Action
{
    

    
    /**
     * Pega os dados do usuário em json
     * @author Mauro Ribeiro
     * @since 2012-03-21
     */
    
    public function getAuthAction() {
        $auth = Zend_Auth::getInstance()->getIdentity();
        
        if ($auth) {
            $user->id = $auth->id;
            $user->login = $auth->login;
            $user->name = $auth->name;
            $user->family_name = $auth->family_name;

            $user->company->id = $auth->company->id;
            $user->company->name = $auth->company->name;
            $user->company->slug = $auth->company->slug;
        }
        else {
            $user->ip = $_SERVER['REMOTE_ADDR'];
        }
        
        $json = Zend_Json::encode($user);
        $this->view->content = $json;
        $this->renderScript("response/ajax.phtml");
    }
    
    /**
     * Pega todos os eventos na sessão do usuário para o Google Analytics
     * @author Mauro Ribeiro
     * @since 2012-03-21
     */
    public function getEventsAction() {
        $userdata = new Zend_Session_Namespace('UserData');
        if (isset($userdata->tracker) && isset($userdata->tracker->events)) {
            $json = Zend_Json::encode($userdata->tracker->events);
        }
        else {
            $json = "{}";
        }
        unset($userdata->tracker->events);
        $this->view->content = $json;
        $this->renderScript("response/ajax.phtml");
    }
    
    
    /**
     * Pega todos os eventos na sessão do usuário para o Mixpanel
     * @author Mauro Ribeiro
     * @since 2012-03-21
     */
    public function getTracksAction() {
        $userdata = new Zend_Session_Namespace('UserData');
        if (isset($userdata->tracker) && isset($userdata->tracker->tracks)) {
            $json = Zend_Json::encode($userdata->tracker->tracks);
        }
        else {
            $json = "{}";
        }
        unset($userdata->tracker->tracks);
        $this->view->content = $json;
        $this->renderScript("response/ajax.phtml");
    }
    
    /**
     * Pega todos as mensagens flashes do usuário
     * @author Mauro Ribeiro
     * @since 2012-03-21
     */
    public function getFlashMessagesAction() {
        $messages = array_merge(
           Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages(),
           Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getCurrentMessages()
        );
        
        $json = Zend_Json::encode($messages);
        $this->view->content = $json;
        $this->renderScript("response/ajax.phtml");
    }

    public function getUserAction() {
        $user_id = $_POST['user_id'];
        $user_mapper = new Ee_Model_Users();
        $user = $user_mapper->find($user_id);
        
        $json = Zend_Json::encode($user);
        $this->view->content = $json;
        $this->renderScript("response/ajax.phtml");
    }

    public function getCompanyAction() {
        $company_id = $_POST['company_id'];
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($company_id);
        
        $json = Zend_Json::encode($company);
        $this->view->content = $json;
        $this->renderScript("response/ajax.phtml");
    }


}
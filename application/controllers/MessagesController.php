<?php
/**
 * MessagesController.php - MessagesController
 * Controlador de mensagens
 * 
 * @package controllers
 * @author Mauro Ribeiro
 * @since 2011-07-18
 */
class MessagesController extends Zend_Controller_Action
{
    /**
     * Método chamado antes de qualquer e toda action
     */
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
                // action body
    }

    /**
     * Lista mensagens de uma thread
     */
    public function threadAction()
    {
        if ($this->_helper->Access->notAjax(true)) return;
        $thread_id = $this->_getParam('thread_id');

        $userData = new Zend_Session_Namespace('UserData');
        $user = new Ee_Model_Data_User($userData->user);
        $this->view->user = $user;

        // lista as mensagens
        $message_mapper = new Ee_Model_Messages();
        $this->view->messages = $message_mapper->findMessagesByThread($thread_id);

        if ($this->view->messages[0]->user_id == $user->id)
            $user_2_id = $this->view->messages[0]->to_user_id;
        else
            $user_2_id = $this->view->messages[0]->user_id;

        $user_mapper = new Ee_Model_Users();
        $this->view->user_2 = $user_mapper->find($user_2_id);
    }

    /**
     * Envia nova mensagem
     */
    public function writeAction()
    {
        if ($this->_helper->Access->notAjax(true)) return;
        $request = $this->getRequest();
        $message_mapper = new Ee_Model_Messages();
        $parent = null;
        if (isset($_POST['parent_id'])) {
            $form =  new Ee_Form_MessageReply();
            $parent = $message_mapper->find($_POST['parent_id']);
        }
        else {
            $form =  new Ee_Form_Message();
        }
        
        // se os dados foram enviados
        if ($request->isPost()) {
            // se os dados são válidos
            if ($form->isValid($request->getPost())) {
                // segurança contra bot
                $this->_helper->BotBlocker->timeFinish();
                
                $values = $form->getModels();
                $message = $values->message;
                
                if ($this->_helper->BotBlocker->timeCheck() == false || $this->_helper->BotBlocker->sameMessage($message->body) || $form->honeyPotsCheck() == false)
                    $this->_helper->BotBlocker->block();
                
                $message->user_id = $this->_helper->Access->getAuth()->id;
                if ($parent) $message->title = $parent->title;
                $message_mapper->toUser($message);

                $user_mapper = new Ee_Model_Users();
                $user = $user_mapper->find($message->user_id);
                $to_user = $user_mapper->find($message->to_user_id);

                $this->_helper->EeMsg->contactEmail($to_user, $message->user_id, $message->title, $message->body);

                if (isset($message->parent_id)) {
                    $this->_helper->Tracker->userEvent('interaction: replied message');
                    $this->_helper->Tracker->event('User','interaction: received reply',$to_user->login);
                    // se o cara nao esta respondendo a propria mensagem
                    if ($parent->user_id != $message->user_id) {
                        $this->_helper->Tracker->track('contact reply', array('company_slug'=>$to_user->company->slug,'company_city'=>$to_user->company->city->slug,'company_region'=>$to_user->company->city->region->slug,'company_sector'=>$to_user->company->sector->slug,'contact_type'=>'message'));
                        $this->_helper->Tracker->trackAnotherUser('contact receive reply', array('company_slug'=>$user->company->slug,'company_city'=>$user->company->city->slug,'company_region'=>$user->company->city->region->slug,'company_sector'=>$user->company->sector->slug,'contact_type'=>'message'), $to_user);
                    }
                }
                else {
                    $this->_helper->Tracker->userEvent('interaction: sent message');
                    $this->_helper->Tracker->event('User','interaction: received message',$to_user->login);
                    $this->_helper->Tracker->track('contact send', array('company_slug'=>$to_user->company->slug,'company_city'=>$to_user->company->city->slug,'company_region'=>$to_user->company->city->region->slug,'company_sector'=>$to_user->company->sector->slug,'contact_type'=>'message'));
                }

                die('<div class="message_status">Mensagem enviada com sucesso!</div>');
            }
            // dados inválidos
            else {
                if (isset($message->parent_id)) {
                    $this->_helper->Tracker->userEvent('ERROR interaction: reply message');
                }
                else {
                    $this->_helper->Tracker->userEvent('ERROR interaction: send message');
                    $this->_helper->Tracker->track('form send', array('form_name'=>'sendmessage','form_status'=>'error','company_slug'=>$to_user->company->slug,'company_city'=>$to_user->company->city->slug,'company_region'=>$to_user->company->city->region->slug,'company_sector'=>$to_user->company->sector->slug,'contact_type'=>'message'));
                }
                $this->getResponse()->setHttpResponseCode(400);
                $this->renderScript('response/ajax.phtml');
            }
        }
        // se não enviou os dados
        else {
            $this->getResponse()->setHttpResponseCode(400);
            $this->renderScript('response/ajax.phtml');
        }
    }

    /**
     * Envia nova mensagem (usuário deslogado)
     */
    public function loggedOutWriteAction()
    {
        if ($this->_helper->Access->notAjax()) return;
        $request = $this->getRequest();
        $form =  new Ee_Form_MessageLoggedOut();
        
        // se os dados foram enviados
        if ($request->isPost()) {
            // se os dados são válidos
            if ($form->isValid($request->getPost())) {
                // segurança contra bot
                $this->_helper->BotBlocker->timeFinish();
                $values = $form->getModels();
                $message = $values->message;
                
                if ($this->_helper->BotBlocker->timeCheck() == false || $this->_helper->BotBlocker->sameMessage($message->body) || $form->honeyPotsCheck() == false)
                    $this->_helper->BotBlocker->block();
                
                $user_mapper = new Ee_Model_Users();
                $to_user = $user_mapper->find($message->to_user_id);

                $this->_helper->EeMsg->loggedOutContactEmail($to_user, $message->name, $message->email, $message->title, $message->body);

                $this->_helper->Tracker->userEvent('interaction: sent message');
                $this->_helper->Tracker->event('User','interaction: received message', $to_user->login);
                $this->_helper->Tracker->track('contact send', array('company_slug'=>$to_user->company->slug,'company_city'=>$to_user->company->city->slug,'company_region'=>$to_user->company->city->region->slug,'company_sector'=>$to_user->company->sector->slug,'contact_type'=>'message'));
                die('<div class="message_status">Mensagem enviada com sucesso!</div>');
            }
            // dados inválidos
            else {
                $this->_helper->Tracker->track('form send', array('form_name'=>'sendmessage','form_status'=>'error','company_slug'=>$to_user->company->slug,'company_city'=>$to_user->company->city->slug,'company_region'=>$to_user->company->city->region->slug,'company_sector'=>$to_user->company->sector->slug,'contact_type'=>'message'));
                $this->_helper->Tracker->userEvent('ERROR interaction: send message');
                $this->getResponse()->setHttpResponseCode(400);
                $this->renderScript('response/ajax.phtml');
            }
        }
        // se não enviou os dados
        else {
            $this->getResponse()->setHttpResponseCode(400);
            $this->renderScript('response/ajax.phtml');
        }
    }
    
    /**
     * Envia novo feedback
     */
    public function feedbackAction()
    {
        if ($this->_helper->Access->notAjax(true)) return;
        $request = $this->getRequest();
        $form =  new Ee_Form_Feedback();

        $this->view->sent = false;

        // se usuário enviou os dados
        if ($request->isPost()) {
            // se os dados são válidos
            if ($form->isValid($request->getPost())) {
                $values = $form->getModels();
                $feedback = $values->feedback;
                $userdata = new Zend_Session_Namespace('UserData');
                $this->_helper->EeMsg->feedbackEmail($userdata->user->id, $feedback);
                $this->view->sent = true;
            }
            // dados inválidos
            else {
                $this->getResponse()->setHttpResponseCode(400);
                $this->renderScript('response/ajax.phtml');
            }
        }
        // dados não foram enviados
        else {
            $this->view->form = $form;
        }

    }
}

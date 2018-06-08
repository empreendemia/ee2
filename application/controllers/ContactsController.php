<?php
/**
 * ContactsController.php - ContactsController
 * Controlador de contatos do usuário
 * 
 * @package controllers
 * @author Mauro Ribeiro
 * @since 2011-07-18
 */
class ContactsController extends Zend_Controller_Action
{
    /**
     * Método chamado antes de qualquer e toda action
     */
    public function init()
    {
        if ( !$this->_helper->Access->checkAuth() ) {
            return $this->_redirect('login');
        }
    }

    /**
     * Lista de contatos.
     * Se usuário escolheu algum contato, mostra o contato e exibe as threads
     */
    public function indexAction()
    {
                // precisa estar logado
        $this->_helper->Access->passAuth();
        
        $userData = new Zend_Session_Namespace('UserData');
        $user = new Ee_Model_Data_User($userData->user);

        $user_mapper = new Ee_Model_Users();
        
        // contatos do usuário
        $contacts = $user_mapper->contacts($user);
        $this->view->contacts = $contacts;
        
        // quem mandou mensagem pro usuário mas não é contato
        $message_contacts = $user_mapper->messageContacts($user);
        $this->view->message_contacts = $message_contacts;
        
        $user_id = $this->_getParam('user_id');

        $messages_mapper = new Ee_Model_Messages();

        // se tiver algum contato selecionado
        if ($user_id) {
            $this->_helper->BotBlocker->timeStart();
            $contact = $user_mapper->find($user_id);
            $this->view->contact = $contact;
            // threads do usuário
            $this->view->threads = $messages_mapper->findThreadsByUsers($user, $contact);
        }
        // se não tiver nenhum contato selecionado
        else {
            // mostra todas as mensagens não lidas
            $this->view->unread = $messages_mapper->unreadThreadsByUser($user);
        }
        
    }

    /**
     * Pedido troca de cartão
     */
    public function requestAction()
    {
                $this->_helper->Access->passAuth();
        $user_id = $this->_getParam('user_id');
        $user_mapper = new Ee_Model_Users();
        $user = $user_mapper->find($user_id);

        if ($this->_helper->Access->getAuth()->id == $user_id) {
            $this->_helper->Access->error('Você não pode trocar cartões contigo mesmo!', 403);
        }
        if (!$user) {
            $this->_helper->Access->error('Pessoa não encontrada');
        }

        $request = $this->getRequest();
        $form =  new Ee_Form_RequestContact(array('contact_id'=>$user_id));

        $this->view->user = $user;

        $this->view->sent = false;

        // se submeteu os dados
        if ($request->isPost()) {
            // se os dados são válidos
            if ($form->isValid($request->getPost())) {
                $values = $form->getModels();
                $contact = $values->contact;
                $contact->user_id = $this->_helper->Access->getAuth()->id;
                $contact->date = date('Y-m-d H:i:s');
                $contact_mapper = new Ee_Model_Contacts();
                $contact_mapper->save($contact);
                $this->view->sent = true;
                //$this->_helper->FlashMessenger(array('message'=>'Contato pedido para '.$user->fullName(),'status'=>'success'));
                $this->_helper->EeMsg->cardEmail($user, $contact->user_id, $values->message);

                $this->_helper->Tracker->userEvent('interaction: sent card');
                $this->_helper->Tracker->event('User','interaction: received card',$user->login);
                $this->_helper->Tracker->track('contact send', array('company_slug'=>$user->company->slug,'company_city'=>$user->company->city->slug,'company_region'=>$user->company->city->region->slug,'company_sector'=>$user->company->sector->slug,'contact_type'=>'card'));
            }
            // se os dados são inválidos
            else {
                $this->_helper->Tracker->userEvent('ERROR interaction: sent card');
                $this->_helper->Tracker->track('form send', array('form_name'=>'requestcard','form_status'=>'error','company_slug'=>$user->company->slug,'company_city'=>$user->company->city->slug,'company_region'=>$user->company->city->region->slug,'company_sector'=>$user->company->sector->slug,'contact_type'=>'card'));
                $this->_helper->FlashMessenger(array('message'=>'Erro ao pedir contato de '.$user->fullName(),'status'=>'error'));
                $this->view->form = $form;
            }
        }
        // se usuário ainda não submeteu os dados
        else {
            $this->_helper->Tracker->userEvent('interaction: send card');
            $this->view->form = $form;
            $this->_helper->Tracker->track('contact start', array('company_slug'=>$user->company->slug,'company_city'=>$user->company->city->slug,'company_region'=>$user->company->city->region->slug,'company_sector'=>$user->company->sector->slug,'contact_type'=>'card'));
        }
        
    }

    /**
     * Aceita uma troca de cartão
     */
    public function acceptAction()
    {
                // cara tem que estar logado
        $this->_helper->Access->passAuth();
        
        $user_id = $this->_getParam('user_id');
        $user_mapper = new Ee_Model_Users();
        $user = $user_mapper->find($user_id);

        $request = $this->getRequest();   

        $this->view->user = $user;

        $this->view->sent = false;

        // se submeteu
        if ($request->isPost()) {
            $contact->contact_id = $user_id;
            $contact->user_id = $this->_helper->Access->getAuth()->id;
            $contact->date = date('Y-m-d H:i:s');
            $contact_mapper = new Ee_Model_Contacts();
            $contact_mapper->save($contact);
            $this->view->sent = true;
            //$this->_helper->FlashMessenger(array('message'=>'Cartões trocados com sucesso!','status'=>'success'));

            $this->_helper->EeMsg->userNotify($user_id, $contact->user_id, 'Vocês trocaram seus cartões.');

            $this->_helper->Tracker->userEvent('interaction: accept card');
            $this->_helper->Tracker->event('User','interaction: got card accepted',$user->login);
            
            $sender = $user_mapper->find($this->_helper->Access->getAuth()->id);
            $this->_helper->Tracker->track('contact reply', array('company_slug'=>$user->company->slug,'company_city'=>$user->company->city->slug,'company_region'=>$user->company->city->region->slug,'company_sector'=>$user->company->sector->slug,'contact_type'=>'card'));
            $this->_helper->Tracker->trackAnotherUser('contact receive reply', array('company_slug'=>$sender->company->slug,'company_city'=>$sender->company->city->slug,'company_region'=>$sender->company->city->region->slug,'company_sector'=>$sender->company->sector->slug,'contact_type'=>'card'), $user);
        }
    }

    /**
     * Recusa troca de cartão
     */
    public function refuseAction()
    {
                // apenas usuários logados
        $this->_helper->Access->passAuth();
        
        $user_id = $this->_getParam('user_id');
        $user_mapper = new Ee_Model_Users();
        $user = $user_mapper->find($user_id);

        $request = $this->getRequest();

        $this->view->user = $user;

        $this->view->sent = false;

        // se enviou dados
        if ($request->isPost()) {
            $contact_mapper = new Ee_Model_Contacts();
            $contact_mapper->refuse($user_id, $this->_helper->Access->getAuth()->id);
            $this->view->sent = true;
            //$this->_helper->FlashMessenger(array('message'=>'Cartão recusado com sucesso','status'=>'success'));

            $this->_helper->Tracker->userEvent('interaction: refuse card');
            $this->_helper->Tracker->event('User','interaction: got card refused',$user->login);
            
            $sender = $user_mapper->find($this->_helper->Access->getAuth()->id);
            $this->_helper->Tracker->track('contact reject', array('company_slug'=>$user->company->slug,'company_city'=>$user->company->city->slug,'company_region'=>$user->company->city->region->slug,'company_sector'=>$user->company->sector->slug,'contact_type'=>'card'));
            $this->_helper->Tracker->trackAnotherUser('contact receive rejection', array('company_slug'=>$sender->company->slug,'company_city'=>$sender->company->city->slug,'company_region'=>$sender->company->city->region->slug,'company_sector'=>$sender->company->sector->slug,'contact_type'=>'card'), $user);
        }
    }


}

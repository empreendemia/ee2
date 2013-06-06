<?php
/**
 * DemandsController.php - DemandsController
 * Controlador de requisição de serviços
 * 
 * @package controllers
 * @author Mauro Ribeiro, Lucas Gaspar, Rafael Erthal
 * @since 2011-07-29
 */
class DemandsController extends Zend_Controller_Action
{
    /**
     * Método chamado antes de qualquer e toda action
     */
    public function init()
    {
        /* Initialize action controller here */
    }

    /**
     * Mural de pedido de serviços
     */
    public function indexAction()
    {
        $demand_mapper = new Ee_Model_Demands();
        $this->view->demands = $demand_mapper->findActive();
    }

    /**
     * Fazer um pedido de serviço deslogado
     */
    public function logedoutrequestAction()
    {
        $request = $this->getRequest();
        $form =  new Ee_Form_Unloged();

        $this->view->sent = false;

        // se usuário enviou os dados
        if ($request->isPost()) {
            // se os dados são válidos
            if ($form->isValid($request->getPost())) {
                $values = $form->getModels();
                $demand = $values->demand;
                $demand->date_deadline = date("Y/m/d", $demand->date_deadline);
                $demand_mapper = new Ee_Model_Demands();
                $demand_mapper->save($demand);
                $this->view->sent = true;
                $this->view->demand = $demand;
                $this->_helper->FlashMessenger(array(
                    'message'=>'Pedido de serviço enviado!',
                    'status'=>'success')
                );
                $this->_helper->EeMsg->newDemandLoggedOut($values->demand);
            }
            // dados inválidos
            else {
                $this->_helper->FlashMessenger(array(
                    'message'=>'Alguns dados não foram preenchidos corretamente',
                    'status'=>'error')
                );
                $this->view->form = $form;
            }
        }
        // os dados ainda não foram enviados
        else {
            $this->view->form = $form;
        }

    }


    /**
     * Fazer um pedido de serviço
     */
    public function requestAction()
    {
        $request = $this->getRequest();
        $form =  new Ee_Form_Demand();

        $this->view->sent = false;

        // se usuário enviou os dados
        if ($request->isPost()) {
            // se os dados são válidos
            if ($form->isValid($request->getPost())) {
                $values = $form->getModels();
                $demand = $values->demand;
                $demand->user_id = $this->_helper->Access->getAuth()->id;
                $demand_mapper = new Ee_Model_Demands();
                $demand_mapper->save($demand);
                $this->view->sent = true;
                $this->view->demand = $demand;
                $this->_helper->FlashMessenger(array(
                    'message'=>'Pedido de serviço enviado!',
                    'status'=>'success')
                );
                $this->_helper->EeMsg->newDemand($values->demand, $demand->user_id);
                $this->_helper->Tracker->userEvent('buyer: requested demand');
            }
            // dados inválidos
            else {
                $this->_helper->FlashMessenger(array(
                    'message'=>'Alguns dados não foram preenchidos corretamente',
                    'status'=>'error')
                );
                $this->view->form = $form;
                $this->_helper->Tracker->userEvent('ERROR buyer: request demand');
            }
        }
        // os dados ainda não foram enviados
        else {
            $this->_helper->Tracker->userEvent('buyer: request demand');
            $this->view->form = $form;
        }

    }

    /**
     * Ver um pedido de serviço
     */
    public function viewAction(){
        
        $demand_id = $this->_getParam('demand_id');
        $demand_mapper = new Ee_Model_Demands();
        $demand = $demand_mapper->find($demand_id);
        
        $request = $this->getRequest();
        $form =  new Ee_Form_MessageDemand(array('demand'=>$demand));
                
        // se os dados foram enviados
        if ($request->isPost()) {
            
            // se os dados são válidos
            if ($form->isValid($request->getPost())) {
                $values = $form->getModels();
                $body = $values->message->body;

                // segurança contra bot
                $this->_helper->BotBlocker->timeFinish();
                if ($this->_helper->BotBlocker->timeCheck(1) == false || $form->honeyPotsCheck() == false)
                    $this->_helper->BotBlocker->block();
                
                $from_user = $this->_helper->Access->getAuth()->id;
                $to_user = $demand->user_id;
                
                // chama controller de envio de e-mail
                $this->_helper->EeMsg->demandContactEmail($from_user, $to_user, $body);

                $this->_helper->Tracker->userEvent('interaction: sent message');
                die('<div class="message_status">Mensagem enviada com sucesso!</div>');
            }
            // dados inválidos
            else {
                $this->_helper->Tracker->userEvent('ERROR interaction: send message');
                die ('Não conseguimos pegar sua mensagem :-(');

                //$this->getResponse()->setHttpResponseCode(400);
                //$this->renderScript('response/ajax.phtml');
            }
        }
        // se não enviou os dados
        else {            
            $demand_id = $this->_getParam('demand_id');
            $demand_mapper = new Ee_Model_Demands();
            $this->view->demand = $demand_mapper->find($demand_id);
	    if(isset($this->_helper->Access->getAuth()->id)){
                $this->view->user_id = $this->_helper->Access->getAuth()->id;
	    }
        }
    }
}

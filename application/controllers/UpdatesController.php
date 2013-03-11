<?php
/**
 * UpdatesController.php - UpdatesController
 * Controlador de status updates
 * 
 * @package controllers
 * @author Mauro Ribeiro
 * @since 2011-07-15
 */
class UpdatesController extends Zend_Controller_Action
{

    /**
     * Função chamada antes de qualquer action
     */
    public function init()
    {
        /* Initialize action controller here */
    }

    /**
     * Usuário lista de updates de seus contatos
     */
    public function indexAction()
    {
                //if ($this->_helper->Access->notAjax(true)) return;
        if (isset($_GET['page'])) $page = $_GET['page'];
        else $page = 1;

        $user_id = $this->_helper->Access->getAuth()->id;

        $updates_mapper = new Ee_Model_Updates();
        $this->view->updates = $updates_mapper->subscribedUsers($user_id, $page);

        $this->view->next_page = $page + 1;
    }

    /**
     * Usuário publica novo update
     */
    public function writeAction()
    {
                if ($this->_helper->Access->notAjax(true)) return;
        if (isset($_POST['text'])) {
            $update->text = $_POST['text'];
            $update->user_id = $this->_helper->Access->getAuth()->id;
            $update->type = 'message';
            $update->date = date('Y-m-d H:i:s');

            $update_mapper = new Ee_Model_Updates();
            $update_mapper->save($update);
            $this->_helper->Tracker->userEvent('interaction: sent update');
        }
        die('Yeyeah!');

    }

    /**
     * Usuário lista updates apenas de empresas
     * @deprecated
     */
    public function companiesAction()
    {
                if (isset($_GET['page'])) $page = $_GET['page'];
        else $page = 1;

        $user_id = $this->_helper->Access->getAuth()->id;

        $updates_mapper = new Ee_Model_Updates();
        $this->view->updates = $updates_mapper->subscribedCompanies($user_id, $page);

        $this->view->next_page = $page + 1;
    }


}






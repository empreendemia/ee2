<?php
/**
 * UsersController.php - UsersController
 * Controlador de usuários
 * 
 * @package models
 * @author Mauro Ribeiro
 * @since 2011-07-05
 */
class UsersController extends Zend_Controller_Action
{

    /**
     * Função chamada antes de qualquer action
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
     * Atualiza a foto do usuário
     */
    public function imageUpdateAction()
    {
        // precisa estar logado
        $this->_helper->Access->passAuth();
        // pega os dados do usuário na sessão
        $userData = new Zend_Session_Namespace('UserData');
        $user = new Ee_Model_Data_User($userData->user);

        $request = $this->getRequest();
        $form = new Ee_Form_Image();
        $form->setAttrib('action', 'usuario/atualizar-imagem');

        $this->view->sent = false;

        // se o usuário preencheu e enviou o formulário
        if ($request->isPost()) {
            // se tiver tudo certinho
            if ($form->isValid($request->getPost()) && $form->image->receive()) {
                // se fez a imagem foi enviada com sucesso
                if($form->image->isUploaded()) {
                    $values = $form->getValues();
                    $source = $form->image->getFileName();
                    $user_mapper = new Ee_Model_Users();
                    $user_mapper->imageUpdate($user, $source);
                    $userData->user->image = $user->image;
                    $this->view->sent = true;
                }
            }
        }

        $this->view->form = $form;
        $this->view->user = $user;
    }


}






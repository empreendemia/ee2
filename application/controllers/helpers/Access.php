<?php
/**
 * Access.php - Ee_Controller_Helper_Access
 * Helper para controle de acesso
 * 
 * @package controllers
 * @subpackage helpers
 * @author Mauro Ribeiro
 * @since 2011-08
 */
class Ee_Controller_Helper_Access extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * Pega os dados do usuário
     * @return object       objeto com dados do usuário
     * @author Mauro Ribeiro
     * @since 2011-08
     */
    function getAuth() {
        return Zend_Auth::getInstance()->getIdentity();
    }

    /**
     * Verifica se usuário está logado
     * @return boolean
     * @author Mauro Ribeiro
     * @since 2011-08
     */
    function checkAuth() {
        return Zend_Auth::getInstance()->hasIdentity();
    }

    /**
     * Se o cara está logado, passa.
     * Se não, bloqueia e redireciona para tela de login.
     * @author Mauro Ribeiro
     * @since 2011-08
     */
    function passAuth() {
        $redirector = $this->_actionController->getHelper('Redirector');
        if (!Zend_Auth::getInstance()->hasIdentity() ) return $redirector->gotoUrl('autenticar');
    }

    /**
     * Verifica se não é ajax. Se for, retorna false, se não for, true.
     * @param boolean $require_login        se precisa estar logado
     * @return type 
     * @author Mauro Ribeiro
     * @since 2011-08
     */
    function notAjax($require_login = false) {
        $request = $this->_actionController->getRequest();
        $redirector = $this->_actionController->getHelper('Redirector');
        $response = $this->_actionController->getResponse();

        // se precisa de login, verifica
        if ($require_login) {
           if (!$this->checkAuth()) {
                $response->setHttpResponseCode(403);
                $this->_actionController->render('/error/error', null, true);
                return true;
           }
        }

        // se não for ajax
        if ($request->isXmlHttpRequest() == false) {
            $response->setHttpResponseCode(400);
            $this->_actionController->render('/error/error', null, true);
            return true;
        }

        return false;
    }

    /**
     * Redireciona para página de erro
     * @param string $mensagem      mensagem para retornar para o usuário
     * @param int $error            erro http
     * @author Mauro Ribeiro
     * @since 2011-08 
     */
    function error($mensagem = 'Página não encontrada', $error = 404) {
        throw new Zend_Controller_Action_Exception($mensagem, $error);
    }

}
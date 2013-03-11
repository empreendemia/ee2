<?php
/**
 * ErrosController.php - ErrosController
 * Controlador de erros
 * 
 * @package controllers
 * @author Mauro Ribeiro
 * @since 2011-06-09
 */
class ErrorController extends Zend_Controller_Action
{
    /**
     * Manipulação de erros
     */
    public function errorAction()
    {
                $errors = $this->_getParam('error_handler');
        
        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'Página de Erro';
            return;
        }
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Página não encontrada';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;
                $this->view->message = 'Application error';
                break;
        }
        
        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->log($this->view->message, $priority, $errors->exception);
            $log->log('Request Parameters', $priority, $errors->request->getParams());
        }
        
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }
        
        $this->view->request   = $errors->request;
    }

    /**
     * Pega o log
     */
    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }

    /**
     * Redirecionador
     */
    public function redirectAction()
    {
                $params = $this->_getAllParams();

        $redirect_params = explode('/', $params['redirect']);

        $n_params = count($redirect_params);

        for ($i = 0; $i < $n_params; $i++) {
            if ($redirect_params[$i][0] == ':') {
                $redirect_params[$i] = $params[substr($redirect_params[$i], 1)];
            }
        }

        $redirect = implode('/', $redirect_params);

        $this->_redirect($redirect, array('code'=>301));
    }


}






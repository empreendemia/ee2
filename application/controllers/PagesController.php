<?php
/**
 * PagesController.php - PagesController
 * Controlador de páginas estáticas
 * 
 * @package controllers
 * @author Mauro Ribeiro
 * @since 2011-06-21
 */
class PagesController extends Zend_Controller_Action
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
     * Exibe página estática
     */
    public function displayAction()
    {
                $pg[0] = $this->_getParam('pg0');
        $pg[1] = $this->_getParam('pg1');
        $pg[2] = $this->_getParam('pg2');
        $pg[3] = $this->_getParam('pg3');
        $pg[4] = $this->_getParam('pg4');

        $page = $pg[0];

        for ($i = 1; $i <= 4; $i++) {
            if ($pg[$i]) $page .= '/'.$pg[$i];
            else break;
        }

        if (file_exists($this->view->getScriptPath(null) . '/pages/'.$page.'.phtml')) {
            $this->render($page);
        }
        else if (file_exists($this->view->getScriptPath(null) . '/pages/'.$page.'/index.phtml')) {
            $this->render($page.'/index');
        }
        else {
            $this->_helper->Access->error();
        }

    }


}




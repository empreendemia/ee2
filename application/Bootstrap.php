<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initAutoload() {

        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
            'basePath'  => APPLICATION_PATH,
            'namespace' => 'Ee',
        ));

        $resourceLoader->addResourceTypes(array(
           'model' => array(
               'path' => 'models/data/',
               'namespace' => 'Model_Data'
           )
        ));

        Zend_Controller_Action_HelperBroker::addPath(
            APPLICATION_PATH . '/controllers/helpers',
            'Ee_Controller_Helper_');

        return $resourceLoader;
    }
    

    protected function _initRewrite() {
        $front = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes/actions.ini', 'production');
        $router->addConfig($config,'routes');

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes/pages.ini', 'production');
        $router->addConfig($config,'routes');

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes/redirections.ini', 'production');
        $router->addConfig($config,'routes');

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes/vip.ini', 'production');
        $router->addConfig($config,'routes');

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes/api.ini', 'production');
        $router->addConfig($config,'routes');
    }

    protected function _initDoctype() {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }

    protected function _initRedirections() {
        $ajax = false;
        $redirect = false;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') $ajax = true;

        // se nao for ajax
        if ($ajax == false) {
            // se nao for página de login ou de autenticar
            if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'login') === false && strpos($_SERVER['REQUEST_URI'], 'autenticar') === false && strpos($_SERVER['REQUEST_URI'], 'logout') === false && strpos($_SERVER['REQUEST_URI'], 'cadastre-se') === false) {
                $redirect = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            }
        }
        if ($redirect) {
            $base = substr($_SERVER['PHP_SELF'], 0, -9);
            $userdata = new Zend_Session_Namespace('UserData');
            if (strlen($base) > 1) {
                $explode = explode($base, $redirect);
                $userdata->Navigation->last = $explode[count($explode) - 1];
            }
            else {
                $userdata->Navigation->last = $redirect;
            }
            $userdata->Navigation->last = '/'.$userdata->Navigation->last;
        }
        else {
            $userdata = new Zend_Session_Namespace('UserData');
            if (!isset($userdata->Navigation) || !isset($userdata->Navigation->last)) {
                $userdata->Navigation->last = '/';
            }
        }
    }
}


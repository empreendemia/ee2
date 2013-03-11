<?php
/**
 * MainController.php - MainController
 * Controlador das páginas principais do usuário
 * 
 * @package controllers
 * @author Mauro Ribeiro
 * @since 2011-07-05
 */
class MainController extends Zend_Controller_Action
{
    /**
     * Método chamado antes de qualquer e toda action
     */
    public function init()
    {
        $this->_helper->Access->passAuth();
    }

    public function indexAction()
    {
                // action body
    }

    /**
     * Página principal de usuário logado
     */
    public function getStartedAction()
    {
        $userData = new Zend_Session_Namespace('UserData');
        $user = new Ee_Model_Data_User($userData->user);
        $this->view->user = $user;
        $company_mapper = new Ee_Model_Companies();
        $this->view->company = $company_mapper->find($user->company_id);
    }

    /**
     * Página de updates da rede do usuário
     */
    public function updatesAction()
    {
                $userData = new Zend_Session_Namespace('UserData');
        $user = new Ee_Model_Data_User($userData->user);
        $this->view->user = $user;
        $this->_helper->Tracker->userEvent('interaction: view updates');

        // número de contatos do usuário
        $user_mapper = new Ee_Model_Users();
        $contacts = $user_mapper->countContacts($user);
        $this->view->contacts = $contacts;

        // empresas que usuário tem contatos
        $company_mapper = new Ee_Model_Companies();
        $companies_ids = $company_mapper->contactsIds($user->id);

        // 5 últimas avaliações da rede do usuário
        $business_mapper = new Ee_Model_Businesses();
        $this->view->businesses = $business_mapper->findBySubscriber($user->id, array('limit'=>5,'companies_ids'=>$companies_ids));

        // 5 últimos produtos cadastrados na rede do usuário
        $product_mapper = new Ee_Model_Products();
        $this->view->products = $product_mapper->findBySubscriber($user->id, array('limit'=>5,'companies_ids'=>$companies_ids));
    }

    /**
     * Página de usuário que quer comprar
     */
    public function buyAction()
    {
                $userData = new Zend_Session_Namespace('UserData');
        $this->view->user = new Ee_Model_Data_User($userData->user);

        // lista ofertas em destaque
        $product_mapper = new Ee_Model_Products();
        $this->view->offers = $product_mapper->featuredOffers();

        $this->_helper->Tracker->userEvent('buyer: view buyer page');
    }

    /**
     * Página de usuário que quer vender
     */
    public function sellAction()
    {
                $userdata = new Zend_Session_Namespace('UserData');
        $this->view->user = new Ee_Model_Data_User($userdata->user);

        // lista de empresas por setor dentro da cidade do usuário
        $company_mapper = new Ee_Model_Companies();
        $this->view->city_count = $company_mapper->countByCity($userdata->user->company->city_id);

        // lista de setores na cidade do usuário
        $sector_mapper = new Ee_Model_Sectors();
        $this->view->sectors = $sector_mapper->findByCity($userdata->user->company->city_id);

        $this->_helper->Tracker->userEvent('seller: view seller page');
    }


}





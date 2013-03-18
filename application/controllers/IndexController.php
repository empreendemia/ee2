<?php
/**
 * IndexController.php - IndexController
 * Controlador de índices
 * 
 * @package controllers
 * @author Mauro Ribeiro
 * @since 2011-06-29
 */
class IndexController extends Zend_Controller_Action
{
    /**
     * Método chamado antes de qualquer e toda action
     */
    public function init()
    {
        /* Initialize action controller here */
    }

    /**
     * Home
     */
    public function indexAction()
    {

                // últimas 15 empresas com logotipo cadastradas
        $company_mapper = new Ee_Model_Companies();
        $this->view->companies = $company_mapper->last(15);
        // número de empresas cadastradas
        $this->view->companies_count = $company_mapper->count();

        // últimas 8 avaliações feitas 
        $business_mapper = new Ee_Model_Businesses();
        $this->view->businesses = $business_mapper->last(8);
        $this->view->businesses_count = $business_mapper->count();

        // últimas 8 ofertas
        $product_mapper = new Ee_Model_Products();
        $this->view->products = $product_mapper->lastOffers(8);

        // número de orçamentos
        $budget_mapper = new Ee_Model_Budgets();
        $this->view->budgets_count = $budget_mapper->count();

        // número de troca de cartões feitas
        $contact_mapper = new Ee_Model_Contacts();
        $this->view->contacts_count = $contact_mapper->count();


    }

    /**
     * Gerador de sitemap
     */
    public function sitemapAction()
    {
                $this->_helper->layout->disableLayout();

        $city_mapper = new Ee_Model_Cities();
        $region_mapper = new Ee_Model_Regions();
        $sector_mapper = new Ee_Model_Sectors();

        $this->view->sectors = $sector_mapper->findAll();
        $this->view->cities = $city_mapper->mostPopular(400);
        $this->view->regions = $region_mapper->findAll();

    }
    
    /**
     * Ping 
     */
    public function pingAction() {
        die('pong');
    }


}




<?php

class Vip_BoaVistaController extends Zend_Controller_Action
{
    private $company_id = 'boa-vista';

    public function init()
    {
        $this->_helper->layout()->setLayout('vip/'.$this->company_id);
    }

    public function indexAction()
    {
    }

    public function viewAction() {
        $company_id = $this->company_id;

        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($company_id);
        if (!$company) $this->_helper->Access->error('Empresa não encontrada');
        $this->view->company = $company;

        $this->_helper->Tracker->userEvent('view page: main', $company->id);
    }

    public function productAction() {
        $company_id = $this->company_id;
        $product_id = $this->_getParam('product_id');  
        
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($company_id);
        
        if (!$company) $this->_helper->Access->error('Empresa não encontrada');
        if ($product_id != 'servicos' && $product_id != 'fixo-ilimitado' && $product_id != 'comercio') $this->_helper->Access->error('Produto não encontrado');
        $this->view->company = $company;
        $this->view->product_id = $product_id;

        $this->_helper->Tracker->userEvent('view page: products', $company->id);
        $this->_helper->Tracker->userEvent('view product: '.$product_id, $company->id);
    }


}


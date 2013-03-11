<?php

class Vip_AleloController extends Zend_Controller_Action
{
    private $company_id = 'alelo';

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
        
        $updates_mapper = new Ee_Model_Updates();
        $this->view->updates = $updates_mapper->findByCompany($company, array('limit'=>3));

        $this->_helper->Tracker->userEvent('view page: main', $company->id);
    }
}


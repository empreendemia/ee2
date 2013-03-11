<?php

class Vip_LocawebController extends Zend_Controller_Action
{
    private $company_id = 'locaweb';

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

    public function benefitsAction() {
        $company_id = $this->company_id;
        
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($company_id);
        if (!$company) $this->_helper->Access->error('Empresa não encontrada');
        $this->view->company = $company;
    }


    public function articlesAction() {
        $company_id = $this->company_id;
        $post_title = $this->_getParam('post_title');
        
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($company_id);
        if (!$company) $this->_helper->Access->error('Empresa não encontrada');
        $this->view->company = $company;
        $this->view->rss_locaweb = new Zend_Feed_Rss('http://feeds.feedburner.com/BlogOficialDaLocawebPme');
        $this->view->rss_sdl = new Zend_Feed_Rss('http://www.saiadolugar.com.br/tag/locaweb/feed');
        
        $this->view->post_title = $post_title;
    }

    public function productsAction() {
        $company_id = $this->company_id;
        $product_id = $this->_getParam('product_id');

        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($company_id);
        if (!$company) $this->_helper->Access->error('Empresa não encontrada');
        $this->view->company = $company;
        $product_mapper = new Ee_Model_Products();
        $products = $product_mapper->findByCompany($company);
        $this->view->products = $products;

        if ($product_id) {
            $product = $product_mapper->find($product_id, $company->id);
            if (!$product) $this->_helper->Access->error('Produto não encontrado');
            $this->view->product = $product;
            $this->_helper->Tracker->companyEvent('view product: '.$product->id, $company->id);
        }
        else {
            if (count($products) > 0) {
                $product = $product_mapper->find($products[0]->id);
                $this->view->product = $product;
            }
            $this->_helper->Tracker->userEvent('view page: products', $company->id);
        }

    }
    
    
    public function subscribeAction() {
        if ($this->_helper->Access->checkAuth() == false) {
            $this->_helper->FlashMessenger(array('message'=>'Você precisa estar logado para seguir a Locaweb','status'=>'alert'));
        }
        else {
            $company_id = $this->company_id;

            $company_mapper = new Ee_Model_Companies();
            $company = $company_mapper->find($company_id);
            if (!$company) $this->_helper->Access->error('Empresa não encontrada');
            $this->view->company = $company;

            $subscription_mapper = new Ee_Model_Subscriptions();
            $subscription->user_id = $this->_helper->Access->getAuth()->id;
            $subscription->company_id = $company->id;
            if ($subscription_mapper->save($subscription)) {
                $this->_helper->FlashMessenger(array('message'=>'Você está seguindo a Locaweb e receberá novidades'));
                $this->_helper->Tracker->userEvent('interaction: subscribe', $company->id);
            }
            else {
                $this->_helper->FlashMessenger(array('message'=>'Você já segue a Locaweb','status'=>'alert'));
            }
        }
        
        $this->_redirect('e/locaweb');
    }


}


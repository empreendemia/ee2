<?php
/**
 * AdsController.php - AdsController
 * Controlador de anúncios
 * 
 * @package controllers
 * @author Mauro Ribeiro
 * @since 2011-06
 */
class AdsController extends Zend_Controller_Action
{

    /**
     * Método chamado antes de qualquer e toda action
     */
    public function init()
    {
    }

    public function indexAction()
    {
        // action body
    }

    /**
     * Lista anúncios
     * @param int $number                       número máximo de anúncios para listar
     * @return array(Ee_Model_Data_Ads) $ads    lista de anúncios
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function listAction()
    {
                $number = $this->_getParam('number');
        if (!$number) $number = 4;

        $ad_mapper = new Ee_Model_Ads();
        
        $userdata = new Zend_Session_Namespace('UserData');
        // usuário está logado
        if ($userdata->user) {
            $sector_id = $userdata->user->company->sector_id;
            $city_id = $userdata->user->company->city_id;
        }
        // usuário deslogado
        else {
            $sector_id = 0;
            $city_id = 0;
        }
                
        $ads = $ad_mapper->advertises($sector_id, $city_id, $number);
        
        foreach($ads as $ad) {
            $this->_helper->Tracker->userEvent('viewed ad: '.$ad->id, null);
        }
        
        $this->view->ads = $ads;
    }


    /**
     * Contabiliza um clique para o anúncio e redireciona para o produto
     * @param int $ad_id            id do anúncio
     * @param string $product_id    slug do produto do anúncio
     * @param string $company_id    slug da empresa do anúncio
     * @return redirect             página do produto
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function viewAction()
    {
        
        $ad_id = $this->_getParam('ad_id');
        $product_id = $this->_getParam('product_id');
        $company_id = $this->_getParam('company_id');
     
        // se usuário está logado
        if ($this->_helper->Access->checkAuth()) {
            $userdata = new Zend_Session_Namespace('UserData');
            $sector_id = $userdata->user->company->sector_id;
            $city_id = $userdata->user->company->city_id;
        }
        // se usuário não está logado
        else {
            $sector_id = 0;
            $city_id = 0;
        }

        $ad_mapper = new Ee_Model_Ads();
        $ad_mapper->addClickCount($ad_id, $sector_id, $city_id);
        
        // redireciona para a página da empresa
        return $this->_redirect('e/'.$company_id.'/produtos/'.$product_id);
    }

    /**
     * Painel para usuário criar um anúncio
     * @return Ee_Model_Data_User $user                 dados do usuário
     * @return array(Ee_Model_Data_Regions) $regions    lista de regiões
     * @return array(Ee_Model_Data_Cities) $cities      lista de cidades
     * @return array(Ee_Model_Data_Sectors) $sectors    lista de setores
     */
    public function createAction()
    {
        $this->view->layout()->setLayout('ads');
        
        $this->_helper->Access->passAuth();
        
        $userdata = new Zend_Session_Namespace('UserData');
        $user = $userdata->user;
        $this->view->user = $user;
        
        $region_mapper = new Ee_Model_Regions();
        $this->view->regions = $region_mapper->countCompanies();

        $city_mapper = new Ee_Model_Cities();
        $this->view->cities = $city_mapper->countCompaniesByRegions(array($user->company->city->region->id));

        $sector_mapper = new Ee_Model_Sectors();
        $this->view->sectors = $sector_mapper->countCompaniesByCities(array($user->company->city->id));

        $products_mapper = new Ee_Model_Products();
        $this->view->products = $products_mapper->findByCompany($user->company);
    }

    /**
     * Quando usuário escolhe uma região (estado), atualiza a seleção de cidades
     * @param array($region_id)     lista de ids de regiões
     */
    public function selectCitiesAction()
    {
        // aceita apenas ajax
        if ($this->_helper->Access->notAjax(true)) return;
        
        // pega os region_ids via post
        $regions_ids = explode(',',$_POST['regions']);

        // atualiza cidades das regiões
        $city_mapper = new Ee_Model_Cities();
        $this->view->cities = $city_mapper->countCompaniesByRegions($regions_ids);
    }


    /**
     * Quando usuário escolhe uma cidade, atualiza a seleção de setores
     * @param array($city_id)     lista de ids de cidades
     */
    public function selectSectorsAction()
    {
                // aceita apenas chamadas ajax
        if ($this->_helper->Access->notAjax(true)) return;
        
        // pega as city_ids do post
        $cities_ids = explode(',',$_POST['cities']);
        
        // atualiza setores que tem empresas nas cidades escolhidas
        $sector_mapper = new Ee_Model_Sectors();
        $this->view->sectors = $sector_mapper->countCompaniesByCities($cities_ids);
    }

    /**
     * Salva o anúncio do usuário no banco de dados
     * @param array(regions_id) $_POST[]     regiões que o usuário escolheu
     * @param array(cities_id) $_POST[]      cidades que o usuário escolheu
     * @param array(sectors_id) $_POST[]     setores que o usuário escolheu
     * @param int $_POST['total_companies_count']   número total de empresas que deu
     * @param int $_POST['product_id']              id do produto para anunciar
     * @param int $_POST['months']                  quantidade de meses para anunciar
     */
    public function finishAction()
    {
        // aceita apenas ajax
        if ($this->_helper->Access->notAjax(true)) return;
        
        // pega os parâmetros regions, cities e sectors do usuário
        $regions_ids = explode(',',$_POST['regions']);
        $cities_ids = explode(',',$_POST['cities']);
        $sectors_ids = explode(',',$_POST['sectors']);

        // total de empresas
        $companies_count = isset($_POST['total_companies_count']) ? $_POST['total_companies_count'] : 0;
        // id do produto
        $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
        // quantidade de meses para anunciar
        $months = isset($_POST['months']) ? $_POST['months'] : null;

        // se o usuário selecionou tudo que precisa
        if (count($cities_ids) && count($regions_ids) && count($sectors_ids) && $companies_count && $product_id && $months) {
            $userdata = new Zend_Session_Namespace('UserData');
            $user = $userdata->user;

            $ad->cities = $cities_ids;
            $ad->sectors = $sectors_ids;
            $ad->product_id = $product_id;
            $ad->date_deadline = date('Y-m-d', strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " +".$months." month"));

            $ad_mapper = new Ee_Model_Ads();
            $ad_mapper->create($ad);
            $this->view->months = $months;
            $this->view->companies_count = $companies_count;

            $month_price = 0;
            if ($companies_count <= 2000) $month_price = 70;
            else if ($companies_count <= 5000) $month_price = 150;
            else if ($companies_count <= 10000) $month_price = 250;
            else $month_price = 450;

            $price = $months * $month_price;
            $this->view->price = $price;

            $this->_helper->EeMsg->newAd($user->id, $ad, $companies_count, $months, $price);
        }
        // se ele nào selecionou alguma coisa, FAIL
        else {
            var_dump($cities_ids);
            var_dump($regions_ids);
            var_dump($sectors_ids);
            $this->getResponse()->setHttpResponseCode(400);
            $this->renderScript('response/ajax.phtml');
        }
    }
    

    /**
     * Painel para usuário criar um anúncio
     * @return Ee_Model_Data_User $user                 dados do usuário
     * @return array(Ee_Model_Data_Regions) $regions    lista de regiões
     * @return array(Ee_Model_Data_Cities) $cities      lista de cidades
     * @return array(Ee_Model_Data_Sectors) $sectors    lista de setores
     */
    public function saleCreateAction()
    {
        $this->view->layout()->setLayout('ads');
        
        $this->_helper->Access->passAuth();
        
        $userdata = new Zend_Session_Namespace('UserData');
        $user = $userdata->user;
        $this->view->user = $user;
        
        $region_mapper = new Ee_Model_Regions();
        $this->view->regions = $region_mapper->countCompanies();

        $city_mapper = new Ee_Model_Cities();
        $this->view->cities = $city_mapper->countCompaniesByRegions(array($user->company->city->region->id));

        $sector_mapper = new Ee_Model_Sectors();
        $this->view->sectors = $sector_mapper->countCompaniesByCities(array($user->company->city->id));

        $products_mapper = new Ee_Model_Products();
        $this->view->products = $products_mapper->findByCompany($user->company);
    }
    

    /**
     * Salva o anúncio do usuário no banco de dados
     * @param array(regions_id) $_POST[]     regiões que o usuário escolheu
     * @param array(cities_id) $_POST[]      cidades que o usuário escolheu
     * @param array(sectors_id) $_POST[]     setores que o usuário escolheu
     * @param int $_POST['total_companies_count']   número total de empresas que deu
     * @param int $_POST['product_id']              id do produto para anunciar
     * @param int $_POST['months']                  quantidade de meses para anunciar
     */
    public function saleFinishAction()
    {
                // aceita apenas ajax
        if ($this->_helper->Access->notAjax(true)) return;
        
        // pega os parâmetros regions, cities e sectors do usuário
        $regions_ids = explode(',',$_POST['regions']);
        $cities_ids = explode(',',$_POST['cities']);
        $sectors_ids = explode(',',$_POST['sectors']);

        // total de empresas
        $companies_count = isset($_POST['total_companies_count']) ? $_POST['total_companies_count'] : 0;
        // id do produto
        $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
        // quantidade de meses para anunciar
        $months = 3;

        // se o usuário selecionou tudo que precisa
        if (count($cities_ids) && count($regions_ids)&& count($sectors_ids) && $companies_count && $product_id && $months) {
            $userdata = new Zend_Session_Namespace('UserData');
            $user = $userdata->user;

            $ad->cities = $cities_ids;
            $ad->sectors = $sectors_ids;
            $ad->product_id = $product_id;
            $ad->date_deadline = date('Y-m-d', strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " +".$months." month"));

            $ad_mapper = new Ee_Model_Ads();
            $ad_mapper->create($ad);
            $this->view->months = $months;
            $this->view->companies_count = $companies_count;

            if ($companies_count <= 5000) $price = 300;
            else $price = 400;

            $this->view->price = $price;

            $this->_helper->EeMsg->newAd($user->id, $ad, $companies_count, 3, $price);
            
            /*
             * PREMIUM
             */
                if ($user->company->plan != 'premium') {
                    $expiration = date('Y-m-d', strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " +15 days"));
                    $prov_expiration = $expiration;
                    $this->_helper->Tracker->userEvent('premium: started test drive');
                    
                    $prov_premium->id = $user->company->id;
                    $prov_premium->plan = 'premium';
                    $prov_premium->plan_expiration = $prov_expiration;

                    $company_mapper = new Ee_Model_Companies();
                    $company_mapper->save($prov_premium);

                    $userdata = new Zend_Session_Namespace('UserData');
                    $userdata->user->company->plan = 'premium';
                    $userdata->user->company->plan_expiration = $prov_expiration;

                    $old_expiration = $user->company->plan_expiration;

                    $userdata->user->company->plan_expiration = $prov_expiration;
                    $userdata->user->company->plan = 'premium';
                
                    $expirations['old'] = $old_expiration;
                    $expirations['prov'] = $prov_expiration;
                    $expirations['new'] = $expiration;

                    $this->_helper->EeMsg->startPremiumEmail($user, $expirations, 'half');
                }
                /*
                * /premium
                */
        }
        // se ele nào selecionou alguma coisa, FAIL
        else {
            $this->getResponse()->setHttpResponseCode(400);
            $this->renderScript('response/ajax.phtml');
        }
    }


}
















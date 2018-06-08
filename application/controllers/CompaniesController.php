<?php
/**
 * CompaniesController.php - CompaniesController
 * Controlador de empresas
 * 
 * @package controllers
 * @author Mauro Ribeiro
 * @since 2011-06-15
 */
class CompaniesController extends Zend_Controller_Action
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
                $this->viewAction();
    }

    /**
     * Página principal do perfil de uma empresa
     */
    public function viewAction()
    {
        $company_id = $this->_getParam('company_id');        
        
        // procura a empresa
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($company_id);
        if (!$company) $this->_helper->Access->error('Empresa não encontrada');
        $this->view->company = $company;

        // conta quantas avaliações ela já recebeu
        $business_mapper = new Ee_Model_Businesses();
        $this->view->count_businesses = $business_mapper->countByCompany($company->id);

        // lista os produtos no topo da vitrine
        $product_mapper = new Ee_Model_Products();
        $products = $product_mapper->findTopByCompany($company);
        $this->view->products = $products;

        $this->_helper->Tracker->userEvent('view page: main', $company->id);
        $this->_helper->Tracker->track('view company', array('company_slug'=>$company->slug,'company_city'=>$company->city->slug,'company_region'=>$company->city->region->slug,'company_page'=>'main'));
    }

    /**
     * Página para visualizar o video da empresa
     */
    public function videoAction()
    {
        $company_id = $this->_getParam('company_id');        
        
        // procura a empresa
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($company_id);
        if (!$company) $this->_helper->Access->error('Empresa não encontrada');
        $this->view->company = $company;
    }

    /**
     * Página para visualizar os slides da empresa
     */
    public function slidesAction()
    {
        $company_id = $this->_getParam('company_id');        
        
        // procura a empresa
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($company_id);
        if (!$company) $this->_helper->Access->error('Empresa não encontrada');
        $this->view->company = $company;
    }

    /**
     * Página para visualizar a descrição da empresa
     */
    public function aboutAction()
    {
        $company_id = $this->_getParam('company_id');        
        
        // procura a empresa
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($company_id);
        if (!$company) $this->_helper->Access->error('Empresa não encontrada');
        $this->view->company = $company;
    }

    /**
     * Página dos produtos de uma empresa
     * Se um produto foi selecionado, mostra os dados deste produto
     */
    public function productsAction()
    {
                $company_id = $this->_getParam('company_id');
        $product_id = $this->_getParam('product_id');        

        // procura a empresa
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($company_id);
        if (!$company) $this->_helper->Access->error('Empresa não encontrada');
        $this->view->company = $company;

        // conta quantas avaliações ela já recebeu
        $business_mapper = new Ee_Model_Businesses();
        $this->view->count_businesses = $business_mapper->countByCompany($company->id);

        // lista de produtos
        $product_mapper = new Ee_Model_Products();
        $products = $product_mapper->findByCompany($company);
        $this->view->products = $products;

        // se estiver visualizando um produto específico
        if ($product_id) {
            $product = $product_mapper->find($product_id, $company->id);
            if (!$product) $this->_helper->Access->error('Produto não encontrado');
            $this->view->product = $product;
            $this->_helper->Tracker->companyEvent('view product: '.$product->id, $company->id);
            $this->_helper->Tracker->track('view company', array('company_slug'=>$company->slug,'company_city'=>$company->city->slug,'company_region'=>$company->city->region->slug,'company_page'=>'products','company_product'=>$product->slug));
        }
        // se nenhum produto foi escolhido
        else {
            if (count($products) > 0) {
                $product = $product_mapper->find($products[0]->id);
                $this->view->product = $product;
            }
            $this->_helper->Tracker->companyEvent('view page: products', $company->id);
            $this->_helper->Tracker->track('view company', array('company_slug'=>$company->slug,'company_city'=>$company->city->slug,'company_region'=>$company->city->region->slug,'company_page'=>'products'));
        }

    }

    /**
     * Página de membros de uma empresa
     * Se um membro foi selecionado, mostra os dados desse membro
     */
    public function membersAction()
    {
                $company_id = $this->_getParam('company_id');
        $user_id = $this->_getParam('user_id');
        
        // procura a empresa
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($company_id);
        if (!$company) $this->_helper->Access->error('Empresa não encontrada');
        $this->view->company = $company;

        // conta quantas avaliações ela já recebeu
        $business_mapper = new Ee_Model_Businesses();
        $this->view->count_businesses = $business_mapper->countByCompany($company->id);

        // lista os membros
        $user_mapper = new Ee_Model_Users();
        $users = $user_mapper->findByCompany($company);
        $this->view->users = $users;
        
        // se um membro foi selecionado
        if ($user_id) {
            $this->_helper->BotBlocker->timeStart();
            $member = $user_mapper->find($user_id);
            if (!$member) $this->_helper->Access->error('Membro não encontrado');
            $this->view->user = $member;
            $this->_helper->Tracker->companyEvent('view member: '.$member->login, $company->id);
            $this->_helper->Tracker->track('view company', array('company_slug'=>$company->slug,'company_city'=>$company->city->slug,'company_region'=>$company->city->region->slug,'company_page'=>'members','company_user'=>$member->id));
        }
        // se nenhum membro foi selecioando
        else {
            $this->view->user = $user_mapper->find($users[0]->id);
            $this->_helper->Tracker->companyEvent('view page: members', $company->id);
            $this->_helper->Tracker->track('view company', array('company_slug'=>$company->slug,'company_city'=>$company->city->slug,'company_region'=>$company->city->region->slug,'company_page'=>'members'));
        }

    }

    /**
     * Página de depoimentos de uma empresa
     */
    public function businessesAction()
    {
                $company_id = $this->_getParam('company_id');
        
        // procura a empresa
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($company_id);
        if (!$company) $this->_helper->Access->error('Empresa não encontrada');
        $this->view->company = $company;

        // lista as avaliações
        $business_mapper = new Ee_Model_Businesses();
        $businesses = $business_mapper->findByCompany($company->id);
        $this->view->businesses = $businesses;

        $this->view->count_businesses = count($businesses);

        $this->_helper->Tracker->userEvent('view page: businesses', $company->id);
            $this->_helper->Tracker->track('view company', array('company_slug'=>$company->slug,'company_city'=>$company->city->slug,'company_region'=>$company->city->region->slug,'company_page'=>'businesses'));
    }

    /**
     * Atualiza logotipo de uma empresa
     */
    public function imageUpdateAction()
    {
                // tem que estar logado
        $this->_helper->Access->passAuth();
        
        $userData = new Zend_Session_Namespace('UserData');
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($userData->user->company_id);

        $request = $this->getRequest();
        $form = new Ee_Form_Image();
        $form->setAttrib('action', 'minha-empresa/atualizar-imagem');

        $this->view->sent = false;

        // se os dados foram submetidos
        if ($request->isPost()) {
            // se os dados são válidos
            if ($form->isValid($request->getPost()) && $form->image->receive()) {
                // se foi enviado a imagem
                if($form->image->isUploaded()) {
                    $values = $form->getValues();
                    $source = $form->image->getFileName();
                    $company_mapper->imageUpdate($company, $source);
                    $userData->user->company->image = $company->image;
                    $this->_helper->FlashMessenger(array('message'=>'Imagem enviada com sucesso!','status'=>'success'));
                    $this->view->sent = true;
                }
                // erro no envio da imagem
                else {
                    $this->_helper->FlashMessenger(array('message'=>'Erro ao atualizar imagem','status'=>'error'));
                }
            }
            // dados inválidos
            else {
                $this->_helper->FlashMessenger(array('message'=>'Erro ao atualizar imagem','status'=>'error'));
            }
        }

        $this->view->form = $form;
        $this->view->company = $company;
    }

    /**
     * Avalia a empresa
     */
    public function rateAction()
    {
        // tem que estar logado
        if ($this->_helper->Access->checkAuth() == false) {
            die('Você precisa estar logado para avaliar esta empresa.');
        }
        $company_id = $this->_getParam('company_id');
        $testimonial = $this->_getParam('testimonial');
        
        $this->view->testimonial = $testimonial;
        $this->view->sent = false;
        $this->view->template = isset($_GET['action']) && $_GET['action'] == 'convite' ? '2_cols_large_small' : 'modal_iframe';

        // procura a empresa
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($company_id);
        if (!$company) $this->_helper->Access->error('Empresa não encontrada');
        $this->view->company = $company;

        $userdata = new Zend_Session_Namespace('UserData');

        $contact_status = $company->contactStatus($userdata->user->id);
        
        // se é a própria empresa
        if ($userdata->user->company_id == $company->id) {
            // Forbidden error
            $this->getResponse()->setHttpResponseCode(403);
            die('Você não pode avaliar sua própria empresa!');
            
        }
        // se não tem contato da empresa
        if ($contact_status == false || $contact_status == 'sender') {
            die('Você só pode enviar avaliações para empresas que você tenha contato.');
        }

        if (!$testimonial) {

        }
        // se avaliou a empresa
        else {
            $request = $this->getRequest();
            if ($testimonial == 'up') $rate = '+';
            else $rate = '-';
            $business->user_id = $userdata->user->id;
            $business->company_id = $userdata->user->company_id;
            $business->to_company_id = $company->id;
            $business->rate = $rate;
            $business_mapper = new Ee_Model_Businesses();
            $form = new Ee_Form_Rate(array('to_company_id'=>$company_id,'rate'=>$rate));
            // se não teve depoimento (não deu submit em formulário)
            if (!$request->isPost()) {
                $this->view->form = $form;
                if ($rate == '+') {
                    $this->_helper->Tracker->userEvent('interaction: rate up company');
                    $this->_helper->Tracker->companyEvent('rated up', $company->id);
                }
                if ($rate == '-') {
                    $this->_helper->Tracker->userEvent('interaction: rate down company');
                    $this->_helper->Tracker->companyEvent('rated down', $company->id);
                }
            }
            // se teve depoimento
            else {
                $form->isValid($request->getPost());
                $values = $form->getModels();
                $business->testimonial = $values->business->testimonial;
                $this->view->sent = true;
                
                // procura todos os membros da empresa
                $user_mapper = new Ee_Model_Users();
                $members = $user_mapper->findByCompany($company);
                
                // envia email para cada membro da empresa
                foreach ($members as $member) {
                    $this->_helper->EeMsg->businessEmail($member, $company, $userdata->user->id, $business);
                }
                $this->_helper->Tracker->userEvent('interaction: sent testimonial company');
                $this->_helper->Tracker->companyEvent('received testimonial', $company->id);
            }
            $business_mapper->rate($business);
        }
    }

    /**
     * Fazer pedido de orçamento
     */
    public function budgetAction()
    {
                $company_id = $this->_getParam('company_id');
        $product_id = $this->_getParam('product_id');
        
        // procura a  empresa
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->find($company_id);
        if (!$company) $this->_helper->Access->error('Empresa não encontrada');

        // lista os produtos
        $product_mapper = new Ee_Model_Products();
        $products = $product_mapper->findByCompany($company);

        // verifica se usuário está logado
        $userdata = new Zend_Session_Namespace('UserData');
        $user = null;
        if (isset($userdata->user)) {
            $user = new Ee_Model_Data_User($userdata->user);
            $this->view->user = $user;
        }

        $this->view->sent = false;

        $form = new Ee_Form_Budget(array('user'=>$user,'products'=>$products,'company'=>$company,'product_id'=>$product_id));

        $request = $this->getRequest();

        // se dados foram submetidos
        if ($request->isPost()) {
            // se os dados são válidos
            if ($form->isValid($request->getPost())) {
                // segurança contra bot
                $this->_helper->BotBlocker->timeFinish();
                
                if ($this->_helper->BotBlocker->timeCheck(2) == false || $form->honeyPotsCheck() == false)
                    $this->_helper->BotBlocker->block();
                
                $values = $form->getModels();
                
                $this->view->budget = $values;
                $this->view->company = $company;
                $budget_mapper = new Ee_Model_Budgets();
                $budget = $values->budget;
                $budget->company_id = $company->id;
                $budget_mapper->save($values->budget);
                $this->view->sent = true;

                $this->_helper->EeMsg->budgetEmail($values, $company, $user);
                $this->_helper->Tracker->userEvent('buyer: requested budget');
                $this->_helper->Tracker->companyEvent('requested budget', $company->id);
                $this->_helper->Tracker->track('contact send', array('company_slug'=>$company->slug,'company_city'=>$company->city->slug,'company_region'=>$company->city->region->slug,'company_sector'=>$company->sector->slug,'contact_type'=>'budget'));
            }
            // se os dados forem inválidos
            else {
                $this->_helper->Tracker->userEvent('ERROR buyer: request budget');
                $this->_helper->Tracker->companyEvent('ERROR request budget', $company->id);
                $this->_helper->Tracker->track('form send', array('form_name'=>'budget','form_status'=>'error','company_slug'=>$company->slug,'company_city'=>$company->city->slug,'company_region'=>$company->city->region->slug,'company_sector'=>$company->sector->slug,'contact_type'=>'budget'));
                $this->_helper->BotBlocker->timeStart(20);
                $this->_helper->FlashMessenger(array('message'=>'Alguns dados não foram preenchidos corretamente','status'=>'error'));
                $this->view->form = $form;
            }
        }
        // se não tiveram dados submetidos
        else {
            $this->_helper->Tracker->userEvent('buyer: request budget');
            $this->_helper->Tracker->companyEvent('request budget', $company->id);
            $this->_helper->BotBlocker->timeStart();
            $this->view->form = $form;
            $this->_helper->Tracker->track('contact start', array('company_slug'=>$company->slug,'company_city'=>$company->city->slug,'company_region'=>$company->city->region->slug,'company_sector'=>$company->sector->slug,'contact_type'=>'budget'));
        }

    }

    /**
     * Lista de empresas
     */
    public function listAction()
    {                
        $options['sector_id'] = $this->_getParam('sector_id');
        $options['region_id'] = $this->_getParam('region_id');
        $options['city_id'] = $this->_getParam('city_id');
        $options['page'] = isset($_GET['p']) ? $_GET['p'] : 1;
        $options['search'] = null;

        // faz validação da busca
        $form_search = new Ee_Form_Search();
        if ($form_search->isValid($this->getRequest()->getPost())) $options['search'] = $this->_request->getParam('s');
        
        $region_id = null;
        $city_id = null;
        $sector_id = null;
        $search = null;
        if (isset($_POST['region_id']) && $_POST['region_id'] != '') $region_id = $_POST['region_id'];
        if (isset($_POST['city_id']) && $_POST['city_id'] != '') $city_id = $_POST['city_id'];
        if (isset($_POST['sector_id']) && $_POST['sector_id'] != '') $sector_id = $_POST['sector_id'];
        if (isset($_POST['search']) && $_POST['search'] != '') $search = $_POST['search'];

        // se usuário escolheu alguma cidade ou região
        if ($region_id || $city_id) {
            // constrói a url
            $url = '';
            if ($region_id && $city_id) {
                $url = $region_id.'/'.$city_id;
                if ($sector_id) $url = $url.'/'.$sector_id;
            }
            else if ($region_id) {
                $url = $region_id;
                if ($sector_id) $url = $url.'/todas-cidades/'.$sector_id;
            }
            if ($search) $url .= '?s='.$search;
            // redireciona
            $this->_redirect('lista-de-empresas/'.$url);
        }
        
        $company_mapper = new Ee_Model_Companies();
        $directory = $company_mapper->directory($options);
        $this->view->directory = $directory;
        
        $region_mapper = new Ee_Model_Regions();
        $this->view->regions = $region_mapper->formArray('slug');

        $sector_mapper = new Ee_Model_Sectors();
        // se não fez busca
        if ($options['search'] == null) {
            // se filtrou por cidade, mostra apenas os setores com empresa
            // nessas cidades
            if (count($directory->cities_ids) > 0) {
                $this->view->sectors = $sector_mapper->countCompaniesByCities($directory->cities_ids);
            }
            // se não, mostra todos os setores que tem empresa em qualquer lugar
            else {
                $this->view->sectors = $sector_mapper->countCompanies();
            }
        }
        // se fez busca, registra a busca
        // não mostra os setores
        else {
            $this->_helper->Tracker->userEvent('search: '.$options['search']);
        }

        $this->view->region_id = $options['region_id'];
        $this->view->city_id = $options['city_id'];
        $this->view->sector_id = $options['sector_id'];
        $this->view->search = $options['search'];
    }

    /**
     * Inicia um testdrive Premium
     */
    public function startPremiumAction()
    {
                $period = $this->_getParam('period');
        $confirmation = $this->_getParam('confirm');

        // hashes falsas
        $hash['test-drive'] = sha1('oi eu to errado!');
        $hash['month'] = sha1('oi eu também to errado!');
        $hash['half'] = sha1('hehe eu também!');
        $hash['year'] = sha1('e eu!');
            
        // se o cara tiver logado
        if ($this->_helper->Access->checkAuth()) {
            $user_mapper = new Ee_Model_Users();
            $user = $user_mapper->find($this->_helper->Access->getAuth()->id);

            // gera as hashes de segurança verdadeiras
            $hash['test-drive'] = sha1('test-drive'.$user->company->id.$user->id);
            $hash['month'] = sha1('month'.$user->company->id.$user->id);
            $hash['half'] = sha1('half'.$user->company->id.$user->id);
            $hash['year'] = sha1('year'.$user->company->id.$user->id);
        }

        $this->view->updated = false;
        $save = true;

        // se nenhuma opção foi escolhida ainda
        if (!$period) {
            $this->view->confirmation = $hash;
            $this->render('/pages/plans/premium', null, true);
        }
        // se usuário escolheu alguma opção
        else {
            // precisa estar logado
            $this->_helper->Access->passAuth();
            // se a hash não confere
            if ($confirmation != $hash[$period]) {
                $this->view->confirmation = $hash;
                $this->render('/pages/plans/premium', null, true);
                //$this->getResponse()->setHttpResponseCode(400);
                //$this->renderScript('response/ajax.phtml');
            }
            // se a hash confere
            else {
                // se o cara escolheu testdrive
                if ($period == 'test-drive') {
                    // se o cara já fez o testdrive
                    if ($user->company->plan_expiration != null) {
                        $save = false;
                        $expiration = $user->company->plan_expiration;
                        $prov_expiration = $user->company->plan_expiration;
                    }
                    // se é a primeira vez que tenta fazer testdrive
                    else {
                        $expiration = date('Y-m-d', strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " +15 days"));
                        $prov_expiration = $expiration;
                        $this->_helper->Tracker->userEvent('premium: started test drive');
                    }
                }
                // se o cara pediu por um período
                else {
                    if ($period == 'month') $months = 1;
                    else if ($period == 'half') $months = 6;
                    else if ($period == 'year') $months = 12;

                    // se o cara tiver dentro do plano ainda
                    if (strtotime($user->company->plan_expiration) > time()) {
                        $new_time = strtotime($user->company->plan_expiration) + $months*32*24*60*60;
                        $expiration = date('Y-m-d', $new_time);
                        $prov_expiration = $user->company->plan_expiration;
                        $this->view->updated = true;
                    }
                    else {
                        // se for a primeira vez do broder, dá o testdrive
                        if ($user->company->plan_expiration != null) {
                            $prov_expiration = date('Y-m-d', strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " +16 days"));
                        }
                        // se nao dá só um dia
                        else {
                            $prov_expiration = date('Y-m-d', strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " +1 day"));
                        }
                        $expiration = date('Y-m-d', strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " +".$months." month"));
                    }
                }

                $prov_premium->id = $user->company->id;
                $prov_premium->plan = 'premium';
                $prov_premium->plan_expiration = $prov_expiration;

                if ($save) {
                    $company_mapper = new Ee_Model_Companies();
                    $company_mapper->save($prov_premium);
                }

                $userdata = new Zend_Session_Namespace('UserData');
                $userdata->user->company->plan = 'premium';
                $userdata->user->company->plan_expiration = $prov_expiration;


                $old_expiration = $user->company->plan_expiration;

                $userdata->user->company->plan_expiration = $prov_expiration;
                $userdata->user->company->plan = 'premium';
                $this->view->user = $user;
                $this->view->period = $period;
                $this->view->save = $save;
                
                $expirations['old'] = $old_expiration;
                $expirations['prov'] = $prov_expiration;
                $expirations['new'] = $expiration;

                $this->view->expirations = $expirations;

                $this->_helper->EeMsg->startPremiumEmail($user, $expirations, $period);
            }
        }
    }


}


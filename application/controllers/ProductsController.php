<?php
/**
 * ProductsController.php - ProductsController
 * Controlador de produtos
 * 
 * @package controllers
 * @author Mauro Ribeiro
 * @since 2011-08-17
 */
class ProductsController extends Zend_Controller_Action
{

    /**
     * Função chamada antes de qualquer action
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
     * Mural de Ofertas
     */
    public function offersAction()
    {
        $options['sector_id'] = $this->_getParam('sector_id');
        $options['region_id'] = $this->_getParam('region_id');
        $options['city_id'] = $this->_getParam('city_id');
        $options['page'] = isset($_GET['p']) ? $_GET['p'] : 1;

        $region_id = null;
        $city_id = null;
        $sector_id = null;
        $search = null;
        if (isset($_POST['region_id']) && $_POST['region_id'] != '') $region_id = $_POST['region_id'];
        if (isset($_POST['city_id']) && $_POST['city_id'] != '') $city_id = $_POST['city_id'];
        if (isset($_POST['sector_id']) && $_POST['sector_id'] != '') $sector_id = $_POST['sector_id'];
        if (isset($_POST['search']) && $_POST['search'] != '') $search = $_POST['search'];

        // se usuário escolheu uma região ou cidade
        if ($region_id || $city_id) {
            // monta a url
            $url = '';
            if ($region_id && $city_id) {
                $url = $region_id.'/'.$city_id;
                if ($sector_id) $url = $url.'/'.$sector_id;
            }
            else if ($region_id) {
                $url = $region_id;
                if ($sector_id) $url = $url.'/todas-cidades/'.$sector_id;
            }
            // redireciona para o mural de ofertas daquela cidade ou região
            $this->_redirect('quero-comprar/ofertas/'.$url);
        }

        $product_mapper = new Ee_Model_Products();
        $offers = $product_mapper->offers($options);
        $this->view->offers = $offers;

        $region_mapper = new Ee_Model_Regions();
        $this->view->regions = $region_mapper->formArray('slug');

        $sector_mapper = new Ee_Model_Sectors();
        
        // se estiver filtrado por região ou cidade, mostra apenas os setores 
        // que tenham empresas com ofertas nessa região ou cidade
        if (count($offers->cities_ids) > 0) {
            $this->view->sectors = $sector_mapper->countOffersByCities($offers->cities_ids);
        }
        // se não, mostra todos os setores com ofertas
        else {
            $this->view->sectors = $sector_mapper->countOffers();
        }

        $this->view->region_id = $options['region_id'];
        $this->view->city_id = $options['city_id'];
        $this->view->sector_id = $options['sector_id'];

        $this->_helper->Tracker->userEvent('buyer: view offers');
    }

    /**
     * Edita os dados de um produto
     */
    public function editAction()
    {
        // precisa estar logado
        $this->_helper->Access->passAuth();
        
        $product_id = $this->_getParam('product_id');
        $userdata = new Zend_Session_Namespace('UserData');
        
        $user = new Ee_Model_Data_User($userdata->user);
        $company = new Ee_Model_Data_Company($userdata->user->company);

        $request = $this->getRequest();
        $form = new Ee_Form_Product();

        $product_mapper = new Ee_Model_Products();
        $product = $product_mapper->find($product_id);
        $this->view->product = $product;

        $this->view->sent = false;

        // se usuário submeteu o formulário
        if ($request->isPost()) {
            // se estiver tudo certinho
            if ($form->isValid($request->getPost())) {
                $values = $form->getModels();
                $product->set($values->product);
                $product->id = $product->id;
                $product->date_updated = date('Y-m-d H:i:s');
                $product_mapper->save($product);
                $product->company = $company;

                // se enviou imagem
                if($form->image->receive() && $form->image->isUploaded()) {
                    $source = $form->image->getFileName();
                    $product_image = $product_mapper->imageUpdate($product, $source);
                    $product_mapper->save($product);
                }
                $this->view->product = $product;
                $this->view->sent = true;
            }
            // se algum dado foi preenchido incorreto
            else {
                $this->_helper->FlashMessenger(array('message'=>'Erro ao editar produto','status'=>'error'));
            }
        }
        // usuário ainda não submeteu nada
        else {
            $form->populate(get_object_vars($product));
        }

        $this->view->form = $form;
    }

    /**
     * Usuário cria uma nova oferta
     */
    public function setOfferAction()
    {
        // precisa estar logado para acessar
        $this->_helper->Access->passAuth();
        
        $product_id = $this->_getParam('product_id');
        $userdata = new Zend_Session_Namespace('UserData');
        
        $user = new Ee_Model_Data_User($userdata->user);
        $company = new Ee_Model_Data_Company($userdata->user->company);

        $request = $this->getRequest();
        $form = new Ee_Form_Offer();

        $product_mapper = new Ee_Model_Products();
        $product = $product_mapper->find($product_id);
        $this->view->product = $product;

        $this->view->sent = false;

        // se usuário submeteu formulário
        if ($request->isPost()) {
            // se os dados são válidos
            if ($form->isValid($request->getPost())) {
                $values = $form->getModels();
                $product->set($values->product);
                $product->date_updated = date('Y-m-d H:i:s');
                $product->offer_date_created = $product->date_updated;
                $product->offer_status = 'inactive';
                $product_mapper->save($product);

                $this->_helper->EeMsg->newOffer($user, $product);
                $this->view->product = $product;
                $this->view->sent = true;
            }
            // se os dados forem inválidos
            else {
                $this->_helper->FlashMessenger(array('message'=>'Erro ao salvar oferta','status'=>'error'));
            }
        }
        // se ainda não submeteu o formulário
        else {
            $popvars = get_object_vars($product);
            if ($popvars['offer_date_deadline']) {
                $explode = explode('-', $popvars['offer_date_deadline']);
                $popvars['offer_date_deadline'] = $explode[2].'/'.$explode[1].'/'.$explode[0];
            }
            $form->populate($popvars);
        }

        $this->view->form = $form;
    }

    /**
     * Adiciona imagens à galeria de imagens do produto
     */
    public function setImagesAction()
    {
        // precisa estar logado para acessar
        $this->_helper->Access->passAuth();
        
        $product_id = $this->_getParam('product_id');
        $index = $this->_getParam('index');
        
        $product_mapper = new Ee_Model_Products();
        $product = $product_mapper->find($product_id);
        
        $this->view->product = $product;
        $this->view->index = false;
        $this->view->sent = false;

        // se for galeria de imagens
        if ($index) {
            $this->view->index = $index;

            $request = $this->getRequest();
            $form = new Ee_Form_Image();

            // se usuário submeteu os dados
            if ($request->isPost()) {
                // se os dados são válidos
                if ($form->isValid($request->getPost()) && $form->image->receive()) {
                    // se a imagem foi enviada com sucesso
                    if($form->image->isUploaded()) {
                        $values = $form->getValues();
                        $source = $form->image->getFileName();
                        if ($index == 'showcase') $product_mapper->imageUpdate($product,  $source);
                        else $product_mapper->imageUpdate($product, $source, $index);
                        $this->view->sent = true;
                        $this->_helper->Tracker->userEvent('premium: added product image');
                    }
                    // erro ao enviar imagem
                    else {
                    $this->_helper->Tracker->userEvent('ERROR premium: add product image');
                        $this->_helper->FlashMessenger(array('message'=>'Erro ao atualizar imagem','status'=>'error'));
                    }
                }
                // dados inválidos
                else {
                    $this->_helper->Tracker->userEvent('ERROR premium: add product image');
                    $this->_helper->FlashMessenger(array('message'=>'Erro ao atualizar imagem','status'=>'error'));
                }
            }
            // usuário ainda não submeteu os dados
            else {
                $this->_helper->Tracker->userEvent('premium: add product image');
            }


            $this->view->form = $form;
        }
    }

    /**
     * Adiciona descrição premium a um produtos
     */
    public function setDescriptionAction()
    {
        // precisa estar logado
        $this->_helper->Access->passAuth();
        
        $product_id = $this->_getParam('product_id');
        $userdata = new Zend_Session_Namespace('UserData');
        
        $user = new Ee_Model_Data_User($userdata->user);
        $company = new Ee_Model_Data_Company($userdata->user->company);

        $request = $this->getRequest();
        $form = new Ee_Form_PremiumProduct();

        $product_mapper = new Ee_Model_Products();
        $product = $product_mapper->find($product_id);
        $this->view->product = $product;

        $this->view->sent = false;

        // se usuário submeteu os dados
        if ($request->isPost()) {
            // se os dados são válidos
            if ($form->isValid($request->getPost())) {
                $values = $form->getModels();
                $product->set($values->product);
                $product->date_updated = date('Y-m-d H:i:s');
                $product_mapper->save($product);
                $this->view->product = $product;
                $this->view->sent = true;
            $this->_helper->Tracker->userEvent('premium: added product description');
            }
            // se os dados são inválidos
            else {
                $this->_helper->FlashMessenger(array('message'=>'Erro ao editar produto','status'=>'error'));
                $this->_helper->Tracker->userEvent('ERROR premium: add product description');
            }
        }
        // se usuário ainda não submeteu os dados
        else {
            $this->_helper->Tracker->userEvent('premium: add product description');
            $form->populate(get_object_vars($product));
        }

        $this->view->form = $form;
    }

    /**
     * Apaga um produto
     */
    public function removeAction()
    {
        // precisa estar logado
        $this->_helper->Access->passAuth();
        
        $product_id = $this->_getParam('product_id');
        $confirmation = isset($_GET['confirm']) ? $_GET['confirm'] : null;

        $userdata = new Zend_Session_Namespace('UserData');
        $user = new Ee_Model_Data_User($userdata->user);

        $product_mapper = new Ee_Model_Products();
        $product = $product_mapper->find($product_id);
        $this->view->product = $product;

        // hash de segurança
        $correct_hash = sha1($product_id.$product->company_id.$user->id);

        // se o usuário pode apagar o produto
        if ($confirmation == $correct_hash && $user->company_id == $product->company_id) {
            $product_mapper->delete($product);
            $this->view->removed = true;
        }
        // se não pode
        else {
            $this->view->removed = false;
            $this->view->confirmation = sha1($product_id.$user->company_id.$user->id);
        }
    }

    /**
     * Salva a ordem dos produtos na vitrine
     */
    public function saveOrderAction()
    {
        // aceita apenas chamada ajax
        if ($this->_helper->Access->notAjax(true)) return;
        
        $userdata = new Zend_Session_Namespace('UserData');
        $company = new Ee_Model_Data_Company($userdata->user->company);
        $product_mapper = new Ee_Model_Products();
        $products = $product_mapper->findByCompany($company);

        foreach ($products as $product) {
            if (isset($_POST['products_ids'][$product->id])) {
                $product->sort = $_POST['products_ids'][$product->id];
                $product_mapper->save($product);
            }
        }

        die('yeyeah');
    }


}


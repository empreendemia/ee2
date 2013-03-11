<?php
/**
 * Budget.php - Ee_Form_Budget
 * Formulário de pedido de orçamento
 * 
 * @author Mauro Ribeiro
 * @since 2011-10-09
 */
class Ee_Form_Budget extends Ee_Form_Form
{
    /**
     * Usuário que está pedindo o orçamento
     * @var Ee_Model_Data_User
     */
    private $user;
    /**
     * Empresa que está sendo solicitada
     * @var Ee_Model_Data_Company
     */
    private $company;
    /**
     * Todos os produtos da empresa
     * @var array(Ee_Model_Data_Product)
     */
    private $products;
    /**
     * ID do produto se o usuário escolheu algum
     * @var int
     */
    private $product_id;

    /**
     * Construtor da cacetada toda
     */
    public function __construct($options = null) {
        $this->user = isset($options['user']) ? $options['user'] : null;
        $this->company = isset($options['company']) ? $options['company'] : null;
        $this->products = isset($options['products']) ? $options['products'] : null;
        $this->product_id = isset($options['product_id']) ? $options['product_id'] : null;

        parent::__construct();
    }

    /**
     * Constrói o formulário
     */
    public function init()
    {

        $this->setName('budget');
        $this->setMethod('POST');

        // importa os filtros da empreendemia
        $this->addElementPrefixPath(
                'Ee_Filter',
                APPLICATION_PATH.'/../library/filters/',
                'filter'
        );
        
        // cria honeypots contra bots
        $hps = $this->honeyPots();
        
        /*
         * ------------------------------------------------------------
         * Quantidades
         * ------------------------------------------------------------
         */        

        $title_qtts = $this->htmlElement('Quantidades', 'h3', 'title');
        $this->addElement($title_qtts);

        $form_ids = array();

        $form_ids[] = $title_qtts;

        if ($this->products) {
            // lista de produtos
            foreach ($this->products as $product) {
                $form_ids[] = 'product_'.$product->id;
                // quantidade do produto que o usuário deseja cotar
                $product_qtt = new Zend_Form_Element_Text('product_'.$product->id);
                $product_qtt->setLabel($product->image(32).$product->name)
                      ->addDecorator('Label', array('escape'=>false))
                      ->setRequired(true)
                      ->addFilter('StripTags')
                      ->addFilter('StringTrim')
                      ->addValidator('NotEmpty')
                      ->setAttrib('class', array('tip_tool_form'))
                      ->setAttrib('title', 'Qual a quantidade de '.$product->name.'?')
                      ->setAttrib('size', 1)
                      ->setValue(0)
                      ->addErrorMessage('escolha uma quantidade');
                // se o usuário escolheu este produto, adiciona quantidade  = 1 ostomaticamente
                if ($product->slug == $this->product_id || $product->id == $this->product_id) {
                    $product_qtt->setValue(1);
                }
                else {
                    $product_qtt->setValue(0);
                }
                $this->addElement($product_qtt);
                
            }
        }

        $group = $this->addDisplayGroup($form_ids, 'quantities_group');

        /*
         * ------------------------------------------------------------
         * Dados do usuário
         * ------------------------------------------------------------
         */

        $title_contact = $this->htmlElement('Seus dados de contato', 'h3', 'title');
        $this->addElement($title_contact);
        
        // nome completo do camarada
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('* Nome completo')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('name', false))
              ->addValidator('NotEmpty')
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Exemplo: João da Silva')
              ->setAttrib('maxlength', 100)
              ->addErrorMessage('digite seu nome completo');
        // se atribuiu um usuário, já preenche ostomaticamente
        if (isset($this->user)) {
            $name->setValue($this->user->fullName());
        }
        $this->addElement($name);

        // adiciona um honeypot
        $this->addElement($hps[0]);

        // email do comarade
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('* Email')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('StringToLower')
              ->addValidator('NotEmpty')
              ->addValidator('EmailAddress')
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Exemplo: nome@empresa.com.br')
              ->setAttrib('maxlength', 50)
              ->addErrorMessage('digite um email válido');
        // se atribuiu um usuário, adiciona os dados tomáticamente
        if (isset($this->user)) {
            $email->setValue($this->user->email);
        }
        $this->addElement($email);

        // adiciona mais dois honeyhoneys
        $this->addElement($hps[1]);
        $this->addElement($hps[2]);

        // nome da empresa que está pedindo o orçamento
        $company_name = new Zend_Form_Element_Text('company_name');
        $company_name->setLabel('Nome da empresa')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('title'))
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Nome da sua empresa');
        // se atribuiu um usuario, ja preenche la pora toda
        if (isset($this->user)) {
            $company_name->setValue($this->user->company->name);
        }
        $this->addElement($company_name);

        // região (estado) do usuário querendo pedir orçamento
        $region_mapper = new Ee_Model_Regions();
        $regions_data = $region_mapper->formArray();
        $options = array(''=>'- escolha um estado') + $regions_data;
        $region_id_select = new Zend_Form_Element_Select('region_id_select');
        $region_id_select->setLabel('Estado')
              ->setAttrib('class', array('select_region', 'tip_tool_form'))
              ->setAttrib('title', 'Escolha o estado em que sua empresa se localiza')
              ->addMultiOptions($options);
        // se atribuiu um usuário preenche bla bla bla bla bla
        if (isset($this->user)) {
            $region_id_select->setValue($this->user->company->city->region_id);
        }
        $this->addElement($region_id_select);

        // cidade do brother querendo pedir o orçamento
        $city_id_select = new Zend_Form_Element_Select('city_id_select');
        $city_id_select->setLabel('Cidade')
              ->setAttrib('disabled', '')
              ->setAttrib('class', 'select_city')
              ->setRegisterInArrayValidator(false)
              ->addMultiOptions(array(''=>'- escolha um estado'))
              ->setAttrib('class', array('select_city', 'tip_tool_form'))
              ->setAttrib('title', 'Escolha a cidade em que sua empresa se localiza');
        // se atribuiu usuário, seta la porita todita
        if (isset($this->user)) {
            $city_id_select->setValue($this->user->company->city_id);
        }
        $this->addElement($city_id_select);

        // campo que vai guardar o id da cidade que o usuário escolheu
        $city_id = new Zend_Form_Element_Hidden('city_id');
        $city_id
            ->removeDecorator('label')
            ->setAttrib('class', 'city_id_hidden');
        // se atribuiu usuário, ja seta ostomatico
        if (isset($this->user)) {
            $city_id->setValue($this->user->company->city_id);
        }
        $this->addElement($city_id);

        // telefone do brother pedindo o orçamento
        $phone = new Zend_Form_Element_Text('phone');
        $phone->setLabel('Telefone')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('class', array('tip_tool_form'))
              ->addValidator('regex',false,array('/^\d{1,3} \d{1,3} \d{5,10}$/'))
              ->setAttrib('maxlength', 20)
              ->setAttrib('title', 'Exemplo: 55 11 99999999')
              ->addErrorMessage('o telefone deve estar no formato "XX XX XXXXXXXX"');
        // se atribuiu usuário, ja seta ostomatico
        if (isset($this->user)) {
            $phone->setValue($this->user->company->phone);
        }
        $this->addElement($phone);


        $obs = $this->htmlElement('* campos obrigatórios', 'div', 'observation');
        $this->addElement($obs);

        $this->addDisplayGroup(array(
            $title_contact,
            $name,
            $email,
            $company_name,
            $region_id_select,
            $city_id_select,
            $city_id,
            $phone,
            $obs,
            $hps[0],
            $hps[1],
            $hps[2]
        ), 'contact_group');

        /*
         * ------------------------------------------------------------
         * Mensagem
         * ------------------------------------------------------------
         */

        $title_message = $this->htmlElement('Mensagem', 'h3', 'title');
        $this->addElement($title_message);

        // mensagem que o usuário quer complementar o orçamento
        $message = new Zend_Form_Element_Textarea('message');
        $message->setLabel('Mensagem')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('text'))
              ->addValidator('NotEmpty')
              ->setAttrib('class', array('tip_tool_form wordcount wc250'))
              ->setAttrib('title', 'Escreva uma mensagem para a empresa')
              ->addErrorMessage('escreva uma mensagem');
        $message_txt = "Olá, ".$this->company->name."!\n\nGostaria de fazer um pedido de orçamento dos produtos listados acima.\n\nAtenciosamente";
        
        // se o usuário está setado, já adiciona uma assinaturinha bacanuda.
        if (isset($this->user)) {
            $message_txt .= ",\n".$this->user->fullName();
        }
        $message->setValue($message_txt);
        $this->addElement($message);

        $this->addDisplayGroup(array(
            $title_message,
            $message
        ), 'message_group');

        // bostão de submit
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Pedir orçamento')
              ->setAttrib('class', array('tip_tool_right'))
              ->setAttrib('title', 'Enviar pedido de orçamento');
        $this->addElement($submit);
    }
    
    /**
     * Pega os valores do formulário
     */
    public function getModels() {
        $values = $this->getValues();

        // nome completo do brother
        $models->user->name = $values['name'];
        // email do brother
        $models->user->email = $values['email'];
        // nome da empresa do brother
        $models->company->name = $values['company_name'];
        // id da cidade do brother
        $models->company->city_id = $values['city_id'];
        // telefone do brother
        $models->company->phone = $values['phone'];
        // mensagem do brother
        $models->budget->message = $values['message'];
        // esse é brother mesmo.
        
        $products = '';
        $products_list = array();
        
        // quantidade de cada producto
        foreach ($values as $id => $value) {
            $explode = explode('_', $id);
            if ($explode[0] == 'product') {
                $products .= $explode[1].','.$value.';';
                $products_list[$explode[1]] = $value;
            }
        }
        $models->budget->products = $products;
        $models->products_list = $products_list;

        
        return $models;
    }

}


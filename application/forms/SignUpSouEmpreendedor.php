<?php
/**
 * SignUp.php - Ee_Form_SignUp
 * Formulário de cadastro de novo usuário
 * 
 * @author Mauro Ribeiro
 * @since 2011-07-20
 */
class Ee_Form_SignUpSouEmpreendedor extends Ee_Form_Form
{

    /**
     * Pega o redirecionamento caso seja navegação interrompida
     * @return string       url de redirecionamento 
     */
    public function getRedirection() {
        $userdata = new Zend_Session_Namespace('UserData');

        if (isset($userdata->Navigation->last) && strpos($userdata->Navigation->last, 'cadastre-se') === false) $return = $userdata->Navigation->last;
        else $return = 'passo-a-passo';

        if (strpos($return, '?') !== false) $return .= '&ustatus=registered';
        else $return .= '?ustatus=registered';

        return $return;
    }

    /**
     * Constrói o formulário
     */
    public function init()
    {

        $this->setName('sign-up');

        // importa os validators da empreendemia
        $this->addElementPrefixPath(
                'Ee_Validate',
                APPLICATION_PATH.'/../library/validators/',
                'validate'
        );

        // importa os filtros da empreendemia
        $this->addElementPrefixPath(
                'Ee_Filter',
                APPLICATION_PATH.'/../library/filters/',
                'filter'
        );

        // cria honeypots
        $hps = $this->honeyPots();
        
        /*
         * ------------------------------------------------------------
         * Dados do usuário
         * ------------------------------------------------------------
         */

        $user_title = $this->htmlElement('Dados do usuário', 'div', 'title');

        // nome do usuário
        $user->name = new Zend_Form_Element_Text('user_name');
        $user->name->setLabel('Nome')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('name', false))
              ->addValidator('NotEmpty')
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Exemplo: João')
              ->addErrorMessage('digite seu nome');

        // sobrenome do usuário
        $user->family_name = new Zend_Form_Element_Text('user_family_name');
        $user->family_name->setLabel('Sobrenome')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('name', false))
              ->addValidator('NotEmpty')
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Exemplo: da Silva')
              ->addErrorMessage('digite seu sobrenome');

        // senha do usuário
        $user->password = new Zend_Form_Element_Password('user_password');
        $user->password->setLabel('Senha')
              ->setRequired(true)
              ->addValidator('NotEmpty')
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Senha para logar na rede')
              ->addErrorMessage('digite uma senha');

        // confirmação (repetição) da senha do usuário
        $user->password_confirmation = new Zend_Form_Element_Password('user_password_confirmation');
        $user->password_confirmation->setLabel('Confirmação da senha')
              ->setRequired(true)
              ->addValidator('NotEmpty')
              ->addValidator('PasswordConfirmation', false, array('user_password'))
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Reescreva a mesma senha digitada acima')
              ->addErrorMessage('as senhas não conferem');
        
        // email de login do usuário
        $user->login = new Zend_Form_Element_Text('user_login');
        $user->login->setLabel('Email')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('StringToLower')
              ->addValidator('NotEmpty', true, array('messages'=>'digite um email'))
              ->addValidator('regex', false, array(
                  'pattern'=>'/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                  'messages'=>'digite um email válido'
              ))
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form', 'signup_email'))
              ->setAttrib('title', 'Seu email que será usado para login. Exemplo: nome@empresa.com.br')
              ->addValidator('UniqueLogin', true, array('messages'=>'este email já está cadastrado'));
        
        // confirmação (repetição) do email de login do usuário
        $user->login_confirmation = new Zend_Form_Element_Text('user_login_confirmation');
        $user->login_confirmation->setLabel('Confirmação do email')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('StringToLower')
              ->addValidator('NotEmpty')
              ->addValidator('EmailConfirmation', false, array('user_login'))
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form', 'signup_email_confirmation'))
              ->setAttrib('title', 'Digite novamente seu email')
              ->addErrorMessage('os emails não conferem');

        /*
         * ------------------------------------------------------------
         * Dados da empresa
         * ------------------------------------------------------------
         */
        $company_title = $this->htmlElement('Dados da empresa', 'div', 'title');

        // nome da empresa
        $company->name = new Zend_Form_Element_Text('company_name');
        $company->name->setLabel('Nome da Empresa')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('title'))
              ->addValidator('NotEmpty')
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Nome da sua empresa')
              ->addErrorMessage('digite o nome da sua empresa');

        // cnpj da empresa
        $company->cnpj = new Zend_Form_Element_Text('cnpj');
        $company->cnpj->setLabel('CNPJ')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', true, array('messages'=>'digite o CNPJ'))
              ->addValidator('regex', false, array(
                  'pattern'=>'/^[0-9]{2}\.?[0-9]{3}\.?[0-9]{3}\/?[0-9]{4}\-?[0-9]{2}$/',
                  'messages'=>'digite um CNPJ válido'
              ))
              ->setAttrib('class', array('tip_tool_form', 'signup_cnpj'))
              ->setAttrib('title', 'O CNPJ de sua empresa. Exemplo: 00.000.000/0000-00')
              ->addValidator('UniqueCNPJ', true, array('messages'=>'este CNPJ já está cadastrado'));


        // perfil (comprador ou fornecedor) da empresa
        $company->profile = new Zend_Form_Element_Radio('company_profile');
        $company->profile->setLabel('O que você procura?')
              ->setRequired(true)
              ->setSeparator('')
              ->addMultiOptions(array(
                  'buyer' => 'fornecedores',
                  'seller' => 'clientes',
                  'all' => 'ambos'
              ))
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'O que você mais procura em nossa rede?')
              ->addErrorMessage('escolha um objetivo');

        // tipo da empresa
        $company->type = new Zend_Form_Element_Radio('company_type');
        $company->type->setLabel('Tipo')
              ->setRequired(true)
              ->setSeparator('')
              ->addMultiOptions(array(
                  'company' => 'empresa',
                  'freelancer' => 'autônomo'
              ))
              ->setValue('company')
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Você tem uma empresa ou é profissional autônomo?')
              ->addErrorMessage('escolha um tipo de cadastro');

        // setor da empresa
        $sector_mapper = new Ee_Model_Sectors();
        $sectors_data = $sector_mapper->formArray(array('index'=>'id'));
        $options = array(''=>'- escolha um setor') + $sectors_data;
        $company->sector = new Zend_Form_Element_Select('company_sector_id');
        $company->sector->setLabel('Setor')
              ->setRequired(true)
              ->addMultiOptions($options)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Setor de atividade que sua empresa exerce')
              ->addErrorMessage('escolha um setor');

        // região (estado) da empresa
        $region_mapper = new Ee_Model_Regions();
        $regions_data = $region_mapper->formArray();
        $options = array(''=>'- escolha um estado') + $regions_data;
        $company->region = new Zend_Form_Element_Select('company_region_id_select');
        $company->region->setLabel('Estado')
              ->setRequired(true)
              ->setAttrib('class', array('select_region', 'tip_tool_form'))
              ->setAttrib('title', 'Escolha o estado em que sua empresa se localiza')
              ->addMultiOptions($options)
              ->addErrorMessage('escolha seu estado');

        // cidade da empresa
        $company->city = new Zend_Form_Element_Select('company_city_id_select');
        $company->city->setLabel('Cidade')
              ->setRequired(true)
              ->setAttrib('disabled', '')
              ->setAttrib('class', 'select_city')
              ->setRegisterInArrayValidator(false)
              ->addMultiOptions(array(''=>'- escolha um estado'))
              ->setAttrib('class', array('select_city', 'tip_tool_form'))
              ->setAttrib('title', 'Escolha a cidade em que sua empresa se localiza')
              ->addErrorMessage('escolha sua cidade');

        // campo que guarda o verdadeiro valor da cidade da empresa
        $company_city_id = new Zend_Form_Element_Hidden('company_city_id');
        $company_city_id
            ->removeDecorator('label')
            ->setAttrib('class', 'city_id_hidden');

        // optin para aceitar os termos de compromisso da empresa
        $terms = new Zend_Form_Element_Checkbox('terms_of_service');
        $terms->getDecorator('Label')
              ->setOption('escape', false);
        $terms->setLabel('Eu aceito os <a href="termos/uso" class="modal" title="Termos de Uso">termos de uso</a>')
            ->addValidator(new Zend_Validate_InArray(array(1)))
            ->addErrorMessage('você deve concordar com os termos de uso');

        
        $spacer = $this->htmlElement('', 'div', 'title');

        // optin para se cadastrar na newsletter
        $newsletter = new Zend_Form_Element_Checkbox('newsletter');
        $newsletter->setLabel('Receber descontos exclusivos');
        $newsletter->getDecorator('Label')->setOption('escape', false);
        $newsletter
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Você receberá descontos exclusivos para a sua empresa');

       

        // redirecionador
        $return = new Zend_Form_Element_Hidden('return');
        $return->removeDecorator('label')
                ->setValue($this->getRedirection());

        // botão de submit
        $submit = new Zend_Form_Element_Submit('Cadastrar');
        $submit
              ->setAttrib('class', array('tip_tool_right'))
              ->setAttrib('title', 'Finalizar seu cadastro');

        $this->addElements(array(
            $user_title,
            $hps[0],
            $user->name,
            $user->family_name,
            $user->password,
            $user->password_confirmation,
            $user->login,
            $user->login_confirmation,
            $hps[1],
            $company_title,
            $company->name,
            $company->cnpj,
            $company->type,
            $company->sector,
            $company->region,
            $company->city,
            $company_city_id,
            $hps[2],
            $spacer,
            $company->profile,
            $terms,
            $newsletter,
            $submit,
            $return
        ));
    }
    
    /**
     * Pega os valores do formulário
     * @return object
     */
    public function getModels() {
        $values = $this->getValues();
        
        $models->user->name = $values['user_name'];
        $models->user->family_name = $values['user_family_name'];
        $models->user->password = $values['user_password'];
        $models->user->login = $values['user_login'];
        $models->user->mails = $values['newsletter'] . $values['newsletter']; // newsletter and discounts

        $models->company->name = $values['company_name'];
        $models->company->cnpj = preg_replace('/[^0-9]/', '', $values['cnpj']);
        $models->company->profile  = $values['company_profile'];
        $models->company->type  = $values['company_type'];
        $models->company->sector_id = $values['company_sector_id'];
        $models->company->city_id = $values['company_city_id'];
        
        return $models;
    }

}


<?php
/**
 * CompanyProfile.php - Ee_Form_CompanyProfile
 * Formulário do perfil da empresa e dados de contato
 * 
 * @author Mauro Ribeiro
 * @since 2011-08-03
 */
class Ee_Form_CompanyProfile extends Ee_Form_Form
{
    /**
     * Constroi formulário
     */
    public function init()
    {

        $this->setName('company-profile');
        $this->setAction('painel/empresa/dados-para-contato');
        $this->setMethod('POST');

        // importa filtros da empreendemia
        $this->addElementPrefixPath(
                'Ee_Filter',
                APPLICATION_PATH.'/../library/filters/',
                'filter'
        );

        /*
         * ------------------------------------------------------------
         * Dados gerais
         * ------------------------------------------------------------
         */
        $title = $this->htmlElement('Dados para contato', 'div', 'title');
        $this->addElement($title);

        // primeiro telefone da empresa
        $phone = new Zend_Form_Element_Text('phone');
        $phone->setLabel('Telefone')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('class', array('tip_tool_form'))
              ->addValidator('regex',false,array('/^\d{1,3} \d{1,3} \d{5,10}$/'))
              ->setAttrib('maxlength', 20)
              ->setAttrib('title', 'Telefone que ficará disponível para seus contatos. Exemplo: 55 11 99999999')
              ->addErrorMessage('o telefone deve estar no formato "XX XX XXXXXXXX"');
        $this->addElement($phone);

        // segundo telefone da empresa
        $phone2 = new Zend_Form_Element_Text('phone2');
        $phone2->setLabel('Telefone 2')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('class', array('tip_tool_form'))
              ->addValidator('regex',false,array('/^\d{1,3} \d{1,3} \d{5,10}$/'))
              ->setAttrib('maxlength', 20)
              ->setAttrib('title', 'Telefone que ficará disponível para seus contatos. Exemplo: 55 11 99999999')
              ->addErrorMessage('o telefone deve estar no formato "XX XX XXXXXXXX"');
        $this->addElement($phone2);
        
        // email de contato da empresa
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('StringToLower')
              ->setAttrib('class', array('tip_tool_form'))
              ->addValidator('EmailAddress')
              ->setAttrib('maxlength', 50)
              ->setAttrib('title', 'Email de contato da sua empresa')
              ->addErrorMessage('digite um email válido');
        $this->addElement($email);

        // site da empersa
        $website = new Zend_Form_Element_Text('website');
        $website->setLabel('Site')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('maxlength', 255)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Endereço do site da sua empresa.');
        $this->addElement($website);


        /*
         * ------------------------------------------------------------
         * Endereço
         * ------------------------------------------------------------
         */
        $title = $this->htmlElement('Endereço', 'div', 'title');
        $this->addElement($title);

        // região (estado) da empresa
        $region_mapper = new Ee_Model_Regions();
        $regions_data = $region_mapper->formArray();
        $options = array(''=>'- escolha um estado') + $regions_data;
        $region = new Zend_Form_Element_Select('region_id_select');
        $region->setLabel('Estado')
              ->setRequired(true)
              ->setAttrib('class', array('select_region', 'tip_tool_form'))
              ->setAttrib('title', 'Escolha o estado em que sua empresa se localiza')
              ->addMultiOptions($options)
              ->addErrorMessage('escolha seu estado');
        $this->addElement($region);

        // cidade da empresa
        $city = new Zend_Form_Element_Select('city_id_select');
        $city->setLabel('Cidade')
              ->setRequired(true)
              ->setAttrib('disabled', '')
              ->setAttrib('class', 'select_city')
              ->setRegisterInArrayValidator(false)
              ->addMultiOptions(array(''=>'- escolha um estado'))
              ->setAttrib('class', array('select_city', 'tip_tool_form'))
              ->setAttrib('title', 'Escolha a cidade em que sua empresa se localiza')
              ->addErrorMessage('escolha sua cidade');
        $this->addElement($city);

        // campo onde realmente guarda o id da cidade da empresa
        $company_city_id = new Zend_Form_Element_Hidden('city_id');
        $company_city_id
            ->removeDecorator('label')
            ->setAttrib('class', 'city_id_hidden');
        $this->addElement($company_city_id);
        
        // nome da rua onde esstá a empresa
        $address_street = new Zend_Form_Element_Text('address_street');
        $address_street->setLabel('Rua')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('title'))
              ->setAttrib('maxlength', 100)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Nome da rua da sua empresa');
        $this->addElement($address_street);

        // número do edifício da empresa
        $address_number = new Zend_Form_Element_Text('address_number');
        $address_number->setLabel('Número')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('Alnum')
              ->setAttrib('maxlength', 10)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Número do edifício');
        $this->addElement($address_number);

        // complemento do endereço
        $address_complement = new Zend_Form_Element_Text('address_complement');
        $address_complement->setLabel('Complemento')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('title'))
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Complemento do endereço');
        $this->addElement($address_complement);


        /*
         * ------------------------------------------------------------
         * Contatos voip e im
         * ------------------------------------------------------------
         */
        $title = $this->htmlElement('Messengers e VOIP', 'div', 'title');
        $this->addElement($title);

        // nome do skype da empresa
        $contact_skype = new Zend_Form_Element_Text('contact_skype');
        $contact_skype->setLabel('Skype')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Nome da sua empresa no Skype');
        $this->addElement($contact_skype);

        // email do msn da empresa
        $contact_msn = new Zend_Form_Element_Text('contact_msn');
        $contact_msn->setLabel('MSN')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Nome da sua empresa no MSN')
              ->setAttrib('maxlength', 50)
              ->addValidator('EmailAddress')
              ->addErrorMessage('digite um endereço válido (xxx@xxxx.xxx.xx)');
        $this->addElement($contact_msn);

        // email do gtalk da empresa
        $contact_gtalk = new Zend_Form_Element_Text('contact_gtalk');
        $contact_gtalk->setLabel('Gtalk')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Nome da sua empresa no Gtalk')
              ->setAttrib('maxlength', 50)
              ->addValidator('EmailAddress')
              ->addErrorMessage('digite um endereço válido (xxx@xxxx.xxx.xx)');
        $this->addElement($contact_gtalk);

        /*
         * ------------------------------------------------------------
         * Links externos
         * ------------------------------------------------------------
         */
        $title = $this->htmlElement('Links externos', 'div', 'title');
        $this->addElement($title);

        // link para o blog da empresa
        $link_blog = new Zend_Form_Element_Text('link_blog');
        $link_blog->setLabel('Blog')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('maxlength', 250)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Link para blog da empresa');
        $this->addElement($link_blog);

        // link para o canal do youtube da empresa
        $link_youtube = new Zend_Form_Element_Text('link_youtube');
        $link_youtube->setLabel('Canal do Youtube')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('maxlength', 250)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Link para canal do Youtube da empresa');
        $this->addElement($link_youtube);

        // link para o canal no vimeo da empresa
        $link_vimeo = new Zend_Form_Element_Text('link_vimeo');
        $link_vimeo->setLabel('Canal do Vimeo')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('maxlength', 250)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Link para canal do Vimeo da empresa');
        $this->addElement($link_vimeo);

        // link para o slideshare da empresa
        $link_slideshare = new Zend_Form_Element_Text('link_slideshare');
        $link_slideshare->setLabel('Slideshare')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('maxlength', 250)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Link para o perfil da empresa no Slideshare');
        $this->addElement($link_slideshare);

        // link para o facebook da empresa
        $link_facebook = new Zend_Form_Element_Text('link_facebook');
        $link_facebook->setLabel('Facebook')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('maxlength', 250)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Link para o perfil da empresa no Facebook');
        $this->addElement($link_facebook);

        // twitter da empresa
        $contact_twitter = new Zend_Form_Element_Text('contact_twitter');
        $contact_twitter->setLabel('Twitter')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('maxlength', 250)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Username do twitter da empresa. Exemplo: @empresa')
              ->addValidator('regex',false,array('/^@[A-Za-z0-9_]+$/'))
              ->addErrorMessage('digite um twitter válido');
        $this->addElement($contact_twitter);


        // botão de submit
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Salvar dados')
              ->setAttrib('class', array('tip_tool_right'))
              ->setAttrib('title', 'Atualizar meus dados alterados');
        $this->addElement($submit);
    }
    
    /**
     * Pega os valores do formulário
     * @return object
     * @return string $object->company->phone       primeiro telefone da empresa
     * @return string $object->company->phone2      segundo telefone da empresa
     * @return string $object->company->email       email de contato da empresa
     * @return string $object->company->website     link para o site da empresa
     * @return int $object->company->city_id        id da cidade da empresa
     * @return string $object->company->address_street  nome da rua da empresa
     * @return string $object->company->address_number  número do edifício da empresa
     * @return string $object->company->address_complement  complemento do endereço da emrpesa
     * @return string $object->company->contact_skype   skype da empresa
     * @return string $object->company->contact_twitter twitter da empresa
     * @return string $object->company->contact_msn     email do msn da empresa
     * @return string $object->company->contact_gtalk   email do gtalk da empresa
     * @return string $object->company->link_blog       link para o blog da empresa
     * @return string $object->company->link_youtube    link para o canal no youtube da empresa
     * @return string $object->company->link_vimeo      link para o vimeo
     * @return string $object->company->link_slideshare link para o slideshare
     * @return string $object->company->link_facebook   link para o facebook
     */
    public function getModels() {
        $values = $this->getValues();

        $models->company->phone = (strlen($values['phone']) > 0) ? $values['phone'] : new Zend_Db_Expr('NULL');
        $models->company->phone2 = (strlen($values['phone2']) > 0) ? $values['phone2'] : new Zend_Db_Expr('NULL');
        $models->company->email = (strlen($values['email']) > 0) ? $values['email'] : new Zend_Db_Expr('NULL');
        $models->company->website = (strlen($values['website']) > 0) ? $values['website'] : new Zend_Db_Expr('NULL');
        $models->company->city_id = $values['city_id'];
        $models->company->address_street = $values['address_street'];
        $models->company->address_number = $values['address_number'];
        $models->company->address_complement = $values['address_complement'];
        $models->company->contact_skype = $values['contact_skype'];
        $models->company->contact_twitter = $values['contact_twitter'];
        $models->company->contact_msn = $values['contact_msn'];
        $models->company->contact_gtalk = $values['contact_gtalk'];
        $models->company->link_blog = $values['link_blog'];
        $models->company->link_youtube = $values['link_youtube'];
        $models->company->link_vimeo = $values['link_vimeo'];
        $models->company->link_slideshare = $values['link_slideshare'];
        $models->company->link_facebook = $values['link_facebook'];
        
        return $models;
    }

}


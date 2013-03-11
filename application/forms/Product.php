<?php
/**
 * Product.php - Ee_Form_Product
 * Formulário de produto
 * 
 * @author Mauro Ribeiro
 * @since 2011-08-23
 */
class Ee_Form_Product extends Ee_Form_Form
{

    /**
     * Cria o formulário
     */
    public function init()
    {
        $this->setName('product');
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        // importa os filtros da empreendemia
        $this->addElementPrefixPath(
                'Ee_Filter',
                APPLICATION_PATH.'/../library/filters/',
                'filter'
        );
        
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');

        // nome do produto
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Nome')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('title'))
              ->addValidator('NotEmpty')
              ->setAttrib('maxlength', 30)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Nome do produto ou serviço')
              ->addErrorMessage('digite o nome do produto ou serviço');
        $this->addElement($name);

        // descrição do produto
        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Descrição')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('text'))
              ->addValidator('NotEmpty')
              ->setAttrib('maxlength', 100)
              ->setAttrib('class', array('tip_tool_form wordcount wc100'))
              ->setAttrib('title', 'Descreva o seu produto')
              ->addErrorMessage('digite uma descrição');
        $this->addElement($description);

        // site do produto
        $website = new Zend_Form_Element_Text('website');
        $website->setLabel('Site')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('maxlength', 250)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Endereço desse produto no seu site');
        $this->addElement($website);

        // imagem do produto
        $image = new Zend_Form_Element_File('image');
        $image->setLabel('Imagem do produto')
                ->setDestination($config->files->temp)
                ->setMaxFileSize(2097152)
                ->addValidator('Count', false, 1)
                ->addValidator('Size', false, 2097152)
                ->addValidator('Extension', false, 'jpg,jpeg,png,gif')
                ->setAttrib('class', array('tip_tool_top'))
                ->setAttrib('title', 'Escolha uma imagem de seu computador')
                ->addErrorMessage('erro ao enviar imagem');
        $this->addElement($image);

        // botão de submit
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Adicionar produto')
              ->setAttrib('class', array('tip_tool_top'))
              ->setAttrib('title', 'Adicionar produto à minha vitrine');
        $this->addElement($submit);

    }

    /**
     * Retorna os valores do formulário
     * @return object
     */
    public function getModels() {
        $values = $this->getValues();

        $models->product->name = $values['name'];
        $models->product->description = $values['description'];
        $models->product->website = $values['website'];

        return $models;
    }


}


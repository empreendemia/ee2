<?php
/**
 * PremiumProduct.php - Ee_Form_PremiumProduct
 * Formulário de produto premium
 * 
 * @author Mauro Ribeiro
 * @since 2011-08-23
 */
class Ee_Form_PremiumProduct extends Ee_Form_Form
{

    /**
     * Constrói o formulário
     */
    public function init()
    {
        $this->setName('product');

        // importa os filtros da empreendemia
        $this->addElementPrefixPath(
                'Ee_Filter',
                APPLICATION_PATH.'/../library/filters/',
                'filter'
        );
        
        // texto informativo sobre o produto
        $about = new Zend_Form_Element_Textarea('about');
        $about->setLabel('Sobre')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('text'))
              ->setAttrib('class', array('tip_tool_form', 'ckeditor'))
              ->setAttrib('title', 'Fale mais sobre a sua empresa');
        $this->addElement($about);

        // botão de submit
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Salvar descrição')
              ->setAttrib('class', array('tip_tool_top'))
              ->setAttrib('title', 'Salvar descrição do produto');
        $this->addElement($submit);

    }

    /**
     * Pega os valores do formulário
     * @return object
     */
    public function getModels() {
        $values = $this->getValues();

        $models->product->about = stripslashes($values['about']);

        return $models;
    }


}


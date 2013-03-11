<?php
/**
 * Search.php - Ee_Form_Search
 * Formulário de busca
 * 
 * @author Mauro Ribeiro
 * @since 2011-07-25
 */
class Ee_Form_Search extends Ee_Form_Form
{

    /**
     * Constrói o formulário
     */
    public function init()
    {

        $this->setName('sign-up');
        $this->setAction('lista-de-empresas');
        $this->setMethod('GET');

        // importa filtros da empreendemia
        $this->addElementPrefixPath(
                'Ee_Filter',
                APPLICATION_PATH.'/../library/filters/',
                'filter'
        );
        
        // campo de busca
        $search = new Zend_Form_Element_Text('s');
        $search
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('search_field'))
              ->setDecorators(array('ViewHelper'));

        // botão de submit
        $submit = new Zend_Form_Element_Submit('buscar');
        $submit
              ->setAttrib('class', array('search_submit'))
              ->setDecorators(array('ViewHelper'));

        $this->addElements(array(
            $search,
            $submit
        ));
    }

}


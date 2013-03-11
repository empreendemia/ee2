<?php
/**
 * Offer.php - Ee_Form_Offer
 * Formulário para cadastro de oferta
 * 
 * @author Mauro Ribeiro
 * @since 2011-08-23
 */
class Ee_Form_Offer extends Ee_Form_Form
{

    /**
     * Constrói o formulário
     */
    public function init()
    {
        $this->setName('offer');

        // importa os filtros da empreendemia
        $this->addElementPrefixPath(
                'Ee_Filter',
                APPLICATION_PATH.'/../library/filters/',
                'filter'
        );
        
        // descrição da oferta
        $offer_description = new Zend_Form_Element_Textarea('offer_description');
        $offer_description->setLabel('Benefícios')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('text'))
              ->setAttrib('class', array('tip_tool_form wordcount wc250'))
              ->setAttrib('title', 'Descreva os benefícios da oferta');
        $this->addElement($offer_description);

        // até quando fica a publicação da oferta
        $offer_date_deadline = new Zend_Form_Element_Text('offer_date_deadline');
        $offer_date_deadline->setLabel('Válido até')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('class', array('tip_tool_form datepicker'))
              ->setAttrib('title', 'Até que dia você deseja publicar esta oferta?')
              ->setValue(date('d/m/Y'))
              ->setAttrib('size', 10)
              ->setAttrib('maxlength', 10)
              ->addErrorMessage('escolha uma data');
        $this->addElement($offer_date_deadline);
        
        // botão de submit
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Salvar oferta')
              ->setAttrib('class', array('tip_tool_top'))
              ->setAttrib('title', 'Salvar oferta deste produto');
        $this->addElement($submit);

    }

    /**
     * Pega os valores
     * @return object
     */
    public function getModels() {
        $values = $this->getValues();

        $models->product->offer_description = $values['offer_description'];
        
        $explode = explode('/', $values['offer_date_deadline']);
        $models->product->offer_date_deadline = $explode[2].'-'.$explode[1].'-'.$explode[0];
  
        return $models;
    }


}


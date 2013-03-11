<?php
/**
 * Rate.php - Ee_Form_Rate
 * Formulário de avaliação de empresa
 * 
 * @author Mauro Ribeiro
 * @since 2011-08-03
 */
class Ee_Form_Rate extends Ee_Form_Form
{

    /**
     * ID da empresa recebendo a avaliação
     * @var int
     */
    private $to_company_id;
    /**
     * Avaliação positiva ou negativa
     * @var char
     * @example '+' ou '-'
     */
    private $rate;

    /**
     * Construtor
     * @param array $options
     * @param int $options['to_company_id']     id da empresa recebendo avaliação
     * @param char $options['rate']             avaliação positiva ou negativa
     */
    public function __construct($options = null) {
        $this->to_company_id = isset($options['to_company_id']) ? $options['to_company_id'] : null;
        $this->rate = isset($options['rate']) ? $options['rate'] : null;
        parent::__construct();
    }

    /**
     * Constrói o formulário
     */
    public function init()
    {

        $this->setName('rate');

        // importa os filtros da empreendemia
        $this->addElementPrefixPath(
                'Ee_Filter',
                APPLICATION_PATH.'/../library/filters/',
                'filter'
        );
        
        // para que empresa é a avaliação
        $to_company_id = new Zend_Form_Element_Hidden('to_company_id');
        $to_company_id
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setDecorators(array('ViewHelper'));
        if ($this->to_company_id) $to_company_id->setValue($this->to_company_id);
        $this->addElement($to_company_id);

        // depoimento para a empresa
        $testimonial = new Zend_Form_Element_Textarea('testimonial');
        $testimonial->setLabel('Depoimento')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('text'))
              ->setAttrib('class', array('wordcount wc250'));
        $this->addElement($testimonial);

        // botão de submit
        $submit = new Zend_Form_Element_Submit('Enviar depoimento');
        $submit
            ->setLabel('Enviar depoimento');
        $this->addElement($submit);
    }
    
    /**
     * Pega os valores do formulário
     * @return object
     */
    public function getModels() {
        $values = $this->getValues();
        $models->business->to_company_id = $values['to_company_id'];
        $models->business->testimonial = $values['testimonial'];
        return $models;
    }
    

}


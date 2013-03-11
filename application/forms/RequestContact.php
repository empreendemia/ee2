<?php
/**
 * RequestContact.php - Ee_Form_RequestContact
 * Formulário para pedir troca de cartões para um usuário
 * 
 * @author Mauro Ribeiro
 * @since 2011-08-02
 */
class Ee_Form_RequestContact extends Ee_Form_Form
{

    /**
     * ID do usuário que está recebendo o pedido de troca de cartão
     * @var int
     */
    private $contact_id;

    /**
     * Construtor
     * @param array $options 
     * @param int $options['contact_id']    ID do cara recebendo o pedido
     */
    public function __construct($options = null) {
        $this->contact_id = isset($options['contact_id']) ? $options['contact_id'] : null;
        parent::__construct();
    }

    /**
     * Constrói o formulário
     */
    public function init()
    {

        $this->setName('request-card');

        // importa filtros da empreendemia
        $this->addElementPrefixPath(
                'Ee_Filter',
                APPLICATION_PATH.'/../library/filters/',
                'filter'
        );
        
        // id da pessoa recebendo a requisição
        $contact_id = new Zend_Form_Element_Hidden('contact_id');
        $contact_id
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setDecorators(array('ViewHelper'));
        if ($this->contact_id) $contact_id->setValue($this->contact_id);
        $this->addElement($contact_id);

        // mensagem para ser enviada junto com a requisição
        $message = new Zend_Form_Element_Textarea('message');
        $message->setLabel('Mensagem')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('text'))
              ->addValidator('NotEmpty')
              ->setAttrib('class', array('tip_tool_form', 'wordcount wc250'))
              ->setAttrib('title', 'Escreva uma mensagem explicando o motivo do contato')
              ->addErrorMessage('excreva uma mensagem explicando o motivo do contato');
        $this->addElement($message);

        // botão de submit
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Trocar cartões!')
              ->setAttrib('class', array('tip_tool_right'))
              ->setAttrib('title', 'Pedir o cartão!');
        $this->addElement($submit);

    }

    /**
     * Pega os valores do formulário
     * @return object 
     */
    public function getModels() {
        $values = $this->getValues();

        $models->contact->contact_id = $values['contact_id'];
        $models->message = $values['message'];
     
        return $models;
    }

}


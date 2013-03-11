<?php
/**
 * MessageDemand.php - Ee_Form_MessageDemand
 * Formulário de mensagem para um usuário que escreveu no Mural de Serviços
 * 
 * @author Lucas Gaspar (via Mauro Ribeiro)
 * @since 2012-06-04
 */
class Ee_Form_MessageDemand extends Ee_Form_Form
{
    /**
     * Usuário que está recebendo a mensagem
     * @var Ee_Model_Data_User 
     */
    private $demand;

    /**
     * Construtor da porra toda
     * @param array $options 
     * @param Ee_Model_Data_User $options['demand']    usuário que está recebendo a mensagem
     */
    public function __construct($options = null) {
        $this->demand = isset($options['demand']) ? $options['demand'] : null;
        parent::__construct();
    }

    /**
     * Constrói o formulário
     */
    public function init()
    {

        $this->setName('send-message');

        // importa os filtros da empreendemia
        $this->addElementPrefixPath(
                'Ee_Filter',
                APPLICATION_PATH.'/../library/filters/',
                'filter'
        );
        
        // cria honeypots
        $hps = $this->honeyPots();

        // adiciona dois honeypots
        $this->addElement($hps[0]);
        $this->addElement($hps[1]);

        // mensagem
        $body = new Zend_Form_Element_Textarea('body');
        $body->setLabel('Entre em contato e mande sua proposta')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('text'))
              ->addValidator('NotEmpty')
              ->setAttrib('class', array('wordcount'))
              ->setAttrib('onclick', 'Tracker.ga.userEvent("demmand contact start: ' . $this->demand->slug . '");')
              ->addErrorMessage('mensagem não pode ficar em branco');
        $this->addElement($body);

        // adiciona um honeypot
        $this->addElement($hps[2]);

        // botão de submit
        $submit = new Zend_Form_Element_Submit('Enviar mensagem');
        $submit->setLabel('Enviar mensagem');
        $this->addElement($submit);
    }
    
    /**
     * Pega os valores do formulário
     * @return string $object->message->body    mensagem
     */
    public function getModels() {
        $values = $this->getValues();

        $models->message->body = $values['body'];

        return $models;
    }    
}


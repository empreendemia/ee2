<?php
/**
 * Message.php - Ee_Form_Message
 * Formulário de nova mensagem para um usuário
 * 
 * @author Mauro Ribeiro
 * @since 2011-07-29
 */
class Ee_Form_Message extends Ee_Form_Form
{
    /**
     * Usuário que está recebendo a mensagem
     * @var Ee_Model_Data_User 
     */
    private $to_user;

    /**
     * Construtor da porra toda
     * @param array $options 
     * @param Ee_Model_Data_User $options['to_user']    usuário que está recebendo a mensagem
     */
    public function __construct($options = null) {
        $this->to_user = isset($options['to_user']) ? $options['to_user'] : null;
        parent::__construct();
    }

    /**
     * Constrói o formulário
     */
    public function init()
    {

        $this->setName('send-message');
        $this->setAction('messages/write');

        // importa os filtros da empreendemia
        $this->addElementPrefixPath(
                'Ee_Filter',
                APPLICATION_PATH.'/../library/filters/',
                'filter'
        );
        
        // cria honeypots
        $hps = $this->honeyPots();
        
        // para quem a mensagem vai ser mandada
        $to_user_id = new Zend_Form_Element_Hidden('to_user_id');
        $to_user_id
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setDecorators(array('ViewHelper'));
        if ($this->to_user) $to_user_id->setValue($this->to_user->id);
        $this->addElement($to_user_id);

        // adiciona dois honeypots
        $this->addElement($hps[0]);
        $this->addElement($hps[1]);

        // título da mensagem
        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Assunto')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('title'))
              ->addValidator('NotEmpty')
              ->setAttrib('maxlength', 250)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Sobre o que é esta mensagem?')
              ->addErrorMessage('escreva um assunto');
        $this->addElement($title);

        // corpo da mensagem
        $body = new Zend_Form_Element_Textarea('body');
        $body->setLabel('Mensagem')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('text'))
              ->addValidator('NotEmpty')
              ->setAttrib('class', array('wordcount'))
              ->addErrorMessage('mensagem não pode ficar em branco');
        $this->addElement($body);

        // adiciona um honeypot
        $this->addElement($hps[2]);

        // botão de submit
        $submit = new Zend_Form_Element_Submit('Enviar mensagem');
        $submit
            ->setLabel('Enviar mensagem');
        $this->addElement($submit);
    }
    
    /**
     * Pega os valores do formulário
     * @return object
     * @return int $object->message->to_user_id     id do usuário recebendo a mensagem
     * @return string $object->message->body        copor da mensagem
     * @return string $object->message->title       título da mensagem
     */
    public function getModels() {
        $values = $this->getValues();

        $models->message->to_user_id = $values['to_user_id'];
        $models->message->body = $values['body'];
        $models->message->title = $values['title'];

        return $models;
    }
    

}


<?php
/**
 * MessageReply.php - Ee_Form_MessageReply
 * Formulário de responder mensagem
 * 
 * @author Mauro Ribeiro
 * @since 2011-07-29
 */
class Ee_Form_MessageReply extends Ee_Form_Form
{

    /**
     * Para quem a mensagem será respondida
     * @var Ee_Model_Data_User 
     */
    private $to_user;
    /**
     * ID da mensagem pai
     * @var int
     */
    private $parent_id;

    /**
     * Construtor
     * @param array $options 
     * @param Ee_Model_Data_user $options['to_user']    usuário recebendo a resposta
     * @param int $options['parent_id']                 id da mensagem pai
     */
    public function __construct($options = null) {
        $this->to_user = isset($options['to_user']) ? $options['to_user'] : null;
        $this->parent_id = isset($options['parent_id']) ? $options['parent_id'] : null;
        parent::__construct();
    }

    /**
     * Constrói o formulário
     */
    public function init()
    {

        $this->setName('send-message-reply');
        $this->setAction('messages/write');

        // importa os filtros da empreendemia
        $this->addElementPrefixPath(
                'Ee_Filter',
                APPLICATION_PATH.'/../library/filters/',
                'filter'
        );
        
        // quem vai receber a mensagem
        $to_user_id = new Zend_Form_Element_Hidden('to_user_id');
        $to_user_id
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setDecorators(array('ViewHelper'));
        if ($this->to_user) $to_user_id->setValue($this->to_user->id);
        $this->addElement($to_user_id);

        // quem é a mensagem pai
        $parent_id = new Zend_Form_Element_Hidden('parent_id');
        $parent_id->setDecorators(array('ViewHelper'));
        if ($this->parent_id) $parent_id->setValue($this->parent_id);
        $this->addElement($parent_id);

        // corpo da mensagem
        $body = new Zend_Form_Element_Textarea('body');
        $body->setLabel('Mensagem')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('text'))
              ->addValidator('NotEmpty')
              ->addErrorMessage('mensagem não pode ficar em branco');
        $this->addElement($body);

        // botão de submit
        $submit = new Zend_Form_Element_Submit('Enviar mensagem');
        $submit
            ->setLabel('Enviar mensagem');
        $this->addElement($submit);
    }
    
    /**
     * Pega os valores do formulário
     * @return object
     * @return int $object->message->to_user_id     id para quem é a resposta
     * @return string $object->message->body        corpo da mensagem
     * @return string $object->message->parent_id   id da mensagem pai
     */
    public function getModels() {
        $values = $this->getValues();

        $models->message->to_user_id = $values['to_user_id'];
        $models->message->body = $values['body'];
        $models->message->parent_id = $values['parent_id'];

        return $models;
    }
    

}


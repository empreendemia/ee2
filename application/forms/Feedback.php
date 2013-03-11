<?php
/**
 * Feedback.php - Ee_Form_Feedback
 * Formulário de feedback do sistema
 * 
 * @author Mauro Ribeiro
 * @since 2011-08-26
 */
class Ee_Form_Feedback extends Ee_Form_Form
{

    /**
     * Usuário que está enviando o feedback
     * @var Ee_Model_Data_User
     */
    private $user;
    /**
     * URL que o usuário estava quando enviou o feedback
     * @var string
     */
    private $url;

    /**
     * Construtor
     * @param array $options 
     * @param Ee_Model_Data_User $options['user']   usuário que enviou o feedback
     * @param string $options['url']                url que o usuário estava quando enviou o feedback
     */
    public function __construct($options = null) {
        $this->user = isset($options['user']) ? $options['user'] : null;
        $this->url = isset($options['url']) ? $options['url'] : null;
        parent::__construct();
    }

    /**
     * Constrói o formulário
     */
    public function init()
    {

        $this->setName('send-feedback');
        
        // importa os filtros da empreendemia
        $this->addElementPrefixPath(
                'Ee_Filter',
                APPLICATION_PATH.'/../library/filters/',
                'filter'
        );
        
        // url em que o usuário estava quando enviou o feedback
        $url = new Zend_Form_Element_Hidden('url');
        $url
              ->setRequired(true)
              ->addValidator('NotEmpty')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setDecorators(array('ViewHelper'));
        if ($this->url) $url->setValue($url);
        $this->addElement($url);


        // mensagem que o usuário enviou
        $body = new Zend_Form_Element_Textarea('body');
        $body->setLabel('Mensagem')
              ->setRequired(true)
              ->addFilter('MrWilsonTracesKiller', array('text'))
              ->addValidator('NotEmpty')
              ->addFilter('StripTags')
              ->addFilter('StringTrim');
        $this->addElement($body);

        $submit = new Zend_Form_Element_Submit('Feedbackar!');
        $submit
            ->setLabel('Feedbackar!');
        $this->addElement($submit);
    }
    
    /**
     * Pega os valores do formulário
     * @return object
     * @return string $object->feedback->user_name  nome do usuário
     * @return string $object->feedback->user_email email do usuário
     * @return string $object->feedback->url        url que o usuário estava
     * @return string $object->feedback->body       mensagem do usuário
     */
    public function getModels() {
        $values = $this->getValues();

        $models->feedback->user_name = isset($values['user_name']) ? $values['user_name'] : null;
        $models->feedback->user_email = isset($values['user_email']) ? $values['user_email'] : null;
        $models->feedback->url = isset($values['url']) ? $values['url'] : null;
        $models->feedback->body = $values['body'];

        return $models;
    }
    

}


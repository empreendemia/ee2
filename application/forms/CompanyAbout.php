<?php
/**
 * CompanyAbout.php - Ee_Form_CompanyAbout
 * Informações sobre a empresa
 */
class Ee_Form_CompanyAbout extends Ee_Form_Form
{
    /**
     * Se a empresa é premium
     * @var boolean
     */
    private $is_premium;

    /**
     * Construtor da porra toda
     * @param array $options
     * @param boolean $options['premium']       se a empresa é premium ou não
     */
    public function __construct($options = null) {
        $this->is_premium = isset($options['premium']) ? $options['premium'] : false;
        parent::__construct();
    }

    /**
     * Constrói o formulário
     */
    public function init()
    {

        $this->setName('company-about');
        $this->setAction('painel/empresa/sobre');
        $this->setMethod('POST');

        // importa os filtros da emrpeendemia
        $this->addElementPrefixPath(
                'Ee_Filter',
                APPLICATION_PATH.'/../library/filters/',
                'filter'
        );

        // nome da empresa
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Nome')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('title'))
              ->addValidator('NotEmpty')
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Nome da sua empresa')
              ->addErrorMessage('digite o nome da sua empresa');
        $this->addElement($name);

        // tipo da empersa (empresa ou auônomo)
        $type = new Zend_Form_Element_Radio('type');
        $type->setLabel('Tipo')
              ->setRequired(true)
              ->setSeparator('')
              ->addMultiOptions(array(
                  'company' => 'empresa',
                  'freelancer' => 'autônomo'
              ))
              ->setValue('company')
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Você tem uma empresa ou é profissional autônomo?')
              ->addErrorMessage('escolha um tipo de cadastro');
        $this->addElement($type);

        // setor da empresa
        $sector_mapper = new Ee_Model_Sectors();
        $sectors_data = $sector_mapper->formArray(array('index'=>'id'));
        $options = array(''=>'- escolha um setor') + $sectors_data;
        $sector_id = new Zend_Form_Element_Select('sector_id');
        $sector_id->setLabel('Setor')
              ->setRequired(true)
              ->addMultiOptions($options)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Setor de atividade que sua empresa exerce')
              ->addErrorMessage('escolha um setor');
        $this->addElement($sector_id);

        // atividade da empresa
        $activity = new Zend_Form_Element_Text('activity');
        $activity->setLabel('Atividade')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('title'))
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Descrição sucinta da atividade de sua empresa');
        $this->addElement($activity);

        // descricao da empresa
        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Descrição')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('text'))
              ->setAttrib('class', array('tip_tool_form','wordcount','wc250'))
              ->setAttrib('title', 'Descrição resumida de sua empresa');
        $this->addElement($description);

        // se for premium
        if ($this->is_premium) {
            // texto informativo sobre a empresa
            $about = new Zend_Form_Element_Textarea('about');
            $about->setLabel('Sobre')
                  ->addFilter('StringTrim')
                  ->addFilter('MrWilsonTracesKiller', array('text'))
                  ->setAttrib('class', array('tip_tool_form', 'ckeditor'))
                  ->setAttrib('title', 'Fale mais sobre a sua empresa');
            $this->addElement($about);
            
            // url do video da empresa
            $video_url = new Zend_Form_Element_Text('video_url');
            $video_url->setLabel('Vídeo de apresentação')
                  ->addFilter('StripTags')
                  ->addFilter('StringTrim')
                  ->addFilter('MrWilsonTracesKiller', array('title'))
                  ->setAttrib('maxlength', 120)
                  ->setAttrib('class', array('tip_tool_form'))
                  ->setAttrib('title', 'Endereço (URL) do seu vídeo (YouTube, Vimeo ou Videolog)');
            $this->addElement($video_url);
            
            // url dos slides da empresa
            $slides_url = new Zend_Form_Element_Text('slides_url');
            $slides_url->setLabel('Apresentação em slides')
                  ->addFilter('StripTags')
                  ->addFilter('StringTrim')
                  ->addFilter('MrWilsonTracesKiller', array('title'))
                  ->setAttrib('maxlength', 120)
                  ->setAttrib('class', array('tip_tool_form'))
                  ->setAttrib('title', 'Endereço (URL) da sua apresentação do Slideshare.net');
            $this->addElement($slides_url);
            
            }

        // botão de submiter
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Salvar dados')
              ->setAttrib('class', array('tip_tool_right'))
              ->setAttrib('title', 'Atualizar meus dados alterados');
        $this->addElement($submit);
    }
    
    /**
     * Pega os valores do formulário
     * @return object
     */
    public function getModels() {
        $values = $this->getValues();

        $models->company->name = $values['name'];
        $models->company->type = $values['type'];
        $models->company->sector_id = $values['sector_id'];
        $models->company->activity = $values['activity'];
        $models->company->description = $values['description'];
        if (isset($values['about'])) $models->company->about = stripslashes($values['about']);
        if (isset($values['video_url'])) $models->company->video_url =  $values['video_url'];
        if (isset($values['slides_url'])) $models->company->slides_url = $values['slides_url'];
            
        return $models;
        
    }

}


<?php
/**
 * UserProfile.php - Ee_Form_UserProfile
 * Formulário de dados do perfil de um usuário
 */
class Ee_Form_UserProfile extends Ee_Form_Form
{

    /**
     * Constrói o formulário
     */
    public function init()
    {

        $this->setName('user-profile');
        $this->setAction('painel/usuario/dados-pessoais');
        $this->setMethod('POST');

        // importa os filtros da empreendemia
        $this->addElementPrefixPath(
                'Ee_Filter',
                APPLICATION_PATH.'/../library/filters/',
                'filter'
        );

        // nome do usuário
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Nome')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('name', false))
              ->addValidator('NotEmpty')
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Exemplo: João')
              ->addErrorMessage('digite seu nome');
        $this->addElement($name);

        // sobrenome do usuário
        $family_name = new Zend_Form_Element_Text('family_name');
        $family_name->setLabel('Sobrenome')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('name', false))
              ->addValidator('NotEmpty')
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Exemplo: da Silva')
              ->addErrorMessage('digite seu sobrenome');
        $this->addElement($family_name);

        // descricao do usuario
        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Descrição')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('type'=>'text'))
              ->setAttrib('class', array('tip_tool_form wordcount wc250'))
              ->setAttrib('title', 'Fale um pouco sobre você');
        $this->addElement($description);

        // cargo do usuário
        $job = new Zend_Form_Element_Text('job');
        $job->setLabel('Cargo')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('type'=>'text'))
              ->setAttrib('maxlength', 40)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Qual seu cargo de atuação na sua empresa?');
        $this->addElement($job);

        // telefone do usuário
        $phone = new Zend_Form_Element_Text('phone');
        $phone->setLabel('Telefone')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('maxlength', 20)
              ->setAttrib('class', array('tip_tool_form'))
              ->addValidator('regex',false,array('/^\d{1,3} \d{1,3} \d{5,10}$/'))
              ->setAttrib('title', 'Telefone que ficará disponível para seus contatos. Exemplo: 55 11 99999999')
              ->addErrorMessage('o telefone deve estar no formato "XX XX XXXXXXXX"');
        $this->addElement($phone);

        // celular do usuário
        $cell_phone = new Zend_Form_Element_Text('cell_phone');
        $cell_phone->setLabel('Celular')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('maxlength', 20)
              ->addValidator('regex',false,array('/^\d{1,3} \d{1,3} \d{5,10}$/'))
              ->setAttrib('title', 'Celular que ficará disponível para seus contatos. Exemplo: 55 11 99999999')
              ->addErrorMessage('o telefone deve estar no formato "XX XX XXXXXXXX"');
        $this->addElement($cell_phone);

        // email de contato do usuário
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('StringToLower')
              ->addValidator('EmailAddress')
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Email que ficará disponível para seus contatos')
              ->addErrorMessage('digite um email válido');
        $this->addElement($email);


        // botão de submit
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Salvar dados')
              ->setAttrib('class', array('tip_tool_right'))
              ->setAttrib('title', 'Atualizar meus dados alterados');
        $this->addElement($submit);
    }
    
    /**
     * Retorna os valores do formulário
     * @return object
     */
    public function getModels() {
        $values = $this->getValues();
        
        $models->user->name = $values['name'];
        $models->user->family_name = $values['family_name'];
        $models->user->description = $values['description'];
        $models->user->job = $values['job'];
        $models->user->email = (strlen($values['email']) > 0) ? $values['email'] : new Zend_Db_Expr('NULL');
        $models->user->phone = (strlen($values['phone']) > 0) ? $values['phone'] : new Zend_Db_Expr('NULL');
        $models->user->cell_phone = (strlen($values['cell_phone']) > 0) ? $values['cell_phone'] : new Zend_Db_Expr('NULL');
        
       
        return $models;
    }

}


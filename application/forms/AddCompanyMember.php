<?php
/**
 * AddCompanyMember.php - Ee_Form_AddCompanyMember
 * Formulário para adicionar novo membro numa empresa
 * 
 * @author Mauro Ribeiro
 * @since 2011-09-19 
 */
class Ee_Form_AddCompanyMember extends Ee_Form_Form
{

    /**
     * Inicia o formulário
     */
    public function init()
    {

        $this->setName('add-company-member');
        $this->setMethod('POST');

        // importa validators da Empreendemia
        $this->addElementPrefixPath(
                'Ee_Validate',
                APPLICATION_PATH.'/../library/validators/',
                'validate'
        );
        
        // importa filtros da Empreendemia
        $this->addElementPrefixPath(
                'Ee_Filter',
                APPLICATION_PATH.'/../library/filters/',
                'filter'
        );

        // nome do camarada
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

        // sobrenome do camarada
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

        // cargo do camarada
        $job = new Zend_Form_Element_Text('job');
        $job->setLabel('Cargo')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('type'=>'text'))
              ->setAttrib('maxlength', 40)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Qual seu cargo de atuação na sua empresa?')
              ->addErrorMessage('escreva o cargo do novo membro');
        $this->addElement($job);

        // email do camarada
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('StringToLower')
              ->addValidator('EmailAddress', true, array('messages'=>array(
                  'emailAddressInvalid'=>'digite um email válido'
               )))
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Email que servirá como login para o novo membro')
              ->addValidator('UniqueLogin', true, array('messages'=>'já existe um usuário com este email'));
        $this->addElement($email);
        
        // confirmação do email
        $email_confirmation = new Zend_Form_Element_Text('email_confirmation');
        $email_confirmation->setLabel('Confirmação do email')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('StringToLower')
              ->addValidator('NotEmpty')
              ->addValidator('EmailConfirmation', false, array('email'))
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Digite novamente o email')
              ->addErrorMessage('os emails não conferem');
        $this->addElement($email_confirmation);

        // botão de submeter
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Adicionar membro')
              ->setAttrib('class', array('tip_tool_right'))
              ->setAttrib('title', 'Atualizar meus dados alterados');
        $this->addElement($submit);
    }
    
    /**
     * Pega os valores do formulário em um objeto
     */
    public function getModels() {
        $values = $this->getValues();
        
        // nome do camarada
        $models->user->name = $values['name'];
        // sobrenome do camarada
        $models->user->family_name = $values['family_name'];
        // cargo do camarada
        $models->user->job = $values['job'];
        // email do camarada
        $models->user->email = $values['email'];
        // email de novo do camarada
        $models->user->login = $values['email'];

        return $models;
    }

}


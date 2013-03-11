<?php
/**
 * ChangePassword.php - Ee_Form_ChangePassword 
 * Formulário de troca de senha
 * 
 * @author Mauro Ribeiro
 * @since 2011-09-09
 */
class Ee_Form_ChangePassword extends Ee_Form_Form
{

    /**
     * Preenche formulário
     */
    public function init()
    {

        $this->setName('change-password');

        // importa validators da empreendemia
        $this->addElementPrefixPath(
                'Ee_Validate',
                APPLICATION_PATH.'/../library/validators/',
                'validate'
        );

        // senha atual do usuário
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Senha atual')
              ->setRequired(true)
              ->addValidator('NotEmpty')
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Sua senha atual')
              ->addErrorMessage('digite uma senha');
        $this->addElement($password);

        // nova senha
        $new_password = new Zend_Form_Element_Password('new_password');
        $new_password->setLabel('Nova senha')
              ->setRequired(true)
              ->addValidator('NotEmpty')
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Senha nova')
              ->addErrorMessage('digite uma senha');
        $this->addElement($new_password);

        // confirmação da nova senha
        $new_password_confirmation = new Zend_Form_Element_Password('new_password_confirmation');
        $new_password_confirmation->setLabel('Confirmação nova senha')
              ->setRequired(true)
              ->addValidator('NotEmpty')
              ->addValidator('PasswordConfirmation', false, array('new_password'))
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Reescreva a mesma senha digitada acima')
              ->addErrorMessage('as senhas não conferem');
        $this->addElement($new_password_confirmation);

        // botão de submit
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Alterar senha')
              ->setAttrib('class', array('tip_tool_top'))
              ->setAttrib('title', 'Salvar nova senha!');
        $this->addElement($submit);
    }
    

}


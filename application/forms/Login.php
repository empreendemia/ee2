<?php
/**
 * Login.php - Ee_Form_Login
 * Formulário de login e senha para autenticação
 * 
 * @author Mauro Ribeiro
 * @since 2011-06-14
 */
class Ee_Form_Login extends Zend_Form
{

    /**
     * Pega a url de redirecionamento no caso de navegação interrompida
     * @return string       url de redirecionamento
     */
    public function getRedirection() {
        $userdata = new Zend_Session_Namespace('UserData');
        if (isset($userdata->Navigation->last)) return $userdata->Navigation->last;
        else return 'passo-a-passo';
    }

    /**
     * Constrói o formulario
     */
    public function init()
    {

        $this->setName('login');

        // campo de login do usuário
        $login = new Zend_Form_Element_Text('login');
        $login->setLabel('Email')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('maxlength', 50)
              ->addValidator('NotEmpty')
              ->addErrorMessage('login não pode ser vazio');

        // campo de senha do usuário
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Senha')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setAttrib('maxlength', 50)
              ->addValidator('NotEmpty')
              ->addErrorMessage('senha não pode ser vazia');

        // campo oculto com url de retorno
        $return = new Zend_Form_Element_Hidden('return');
        $return->removeDecorator('label')
                ->setValue($this->getRedirection());

        // id da cidade da empresa
        $company_city_id = new Zend_Form_Element_Hidden('company_city_id');
        $company_city_id
            ->removeDecorator('label')
            ->setAttrib('class', 'city_id_hidden');

        // botão de submit
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('entrar')
               ->setAttrib('id', 'submitbutton');

        $this->addElements(array($login, $password, $submit, $return));
    }


}


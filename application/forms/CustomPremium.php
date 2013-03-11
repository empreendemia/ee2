<?php
/**
 * CustomPremium.php - Ee_Form_CustomPremium
 * Customização da arte da empresa premium
 * 
 * @author Mauro Ribeiro
 * @since 2011-17-08
 */
class Ee_Form_CustomPremium extends Zend_Form
{

    public function init()
    {
        $this->setName('custom-premium');
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');

        // imagem do cartão da empresa
        $card_image = new Zend_Form_Element_File('card_image');
        $card_image->setLabel('Imagem do cartão')
                ->setDestination($config->files->temp)
                ->setMaxFileSize(2097152)
                ->addValidator('Count', false, 1)
                ->addValidator('Size', false, 2097152)
                ->addValidator('Extension', false, 'jpg,jpeg,png,gif')
                ->setAttrib('class', array('tip_tool_top'))
                ->setAttrib('title', 'Escolha uma imagem de seu computador')
                ->addErrorMessage('erro ao enviar imagem');
        $this->addElement($card_image);

        // imagem lateral da empresa
        $side_image = new Zend_Form_Element_File('side_image');
        $side_image->setLabel('Imagem lateral')
                ->setDestination($config->files->temp)
                ->setMaxFileSize(2097152)
                ->addValidator('Count', false, 1)
                ->addValidator('Size', false, 2097152)
                ->addValidator('Extension', false, 'jpg,jpeg,png,gif')
                ->setAttrib('class', array('tip_tool_top'))
                ->setAttrib('title', 'Escolha uma imagem de seu computador')
                ->addErrorMessage('erro ao enviar imagem');
        $this->addElement($side_image);

        // botao de submit
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Atualizar perfil da minha empresa')
              ->setAttrib('class', array('tip_tool_top'))
              ->setAttrib('title', 'Enviar a(s) imagem(ns) selecionada(s)');
        $this->addElement($submit);

    }


}


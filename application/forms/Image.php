<?php
/**
 * Image.php - Ee_Form_Image
 * Formulário para upload de imagem
 * 
 * @author Mauro Ribeiro
 * @since 2011-08-01
 */
class Ee_Form_Image extends Zend_Form
{

    /**
     * Constrói o formulário
     */
    public function init()
    {
        $this->setName('user-image');
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');

        // campo para escolher a imagem
        $image = new Zend_Form_Element_File('image');
        $image->setLabel('Imagem')
                ->setDestination($config->files->temp)
                ->setRequired(true)
                ->setMaxFileSize(2097152)
                ->addValidator('Count', false, 1)
                ->addValidator('Size', false, 2097152)
                ->addValidator('Extension', false, 'jpg,jpeg,png,gif')
                ->setAttrib('class', array('tip_tool_top'))
                ->setAttrib('title', 'Escolha uma imagem de seu computador')
                ->addErrorMessage('erro ao enviar imagem');
        $this->addElement($image);

        // botão de submit
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Atualizar minha imagem')
              ->setAttrib('class', array('tip_tool_top'))
              ->setAttrib('title', 'Enviar imagem');
        $this->addElement($submit);



    }


}


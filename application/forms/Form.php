<?php
/**
 * Form.php - Ee_Form_Form
 * Pai de todos os forms
 * 
 * @author Mauro Ribeiro
 * @since 2011-07-20
 */
class Ee_Form_Form extends Zend_Form
{

    /**
     * Conta quantos elementos html foram criados
     * @var int
     */
    private $_html_elements = 0;

    /**
     * Adiciona um elemento html no formulário (para títulos por exemplo)
     * @param string $content       conteúdo dentro do elemento
     * @param string $tag           em qual tag html?
     * @param string $class         classe para adicionar dentro da tag
     * @return Zend_Form_Element_Hidden 
     */
    public function htmlElement($content, $tag = 'div', $class = null) {

        $this->_html_elements++;

        $element = new Zend_Form_Element_Hidden('html_element_'.$this->_html_elements);
        $element->setLabel($content)
                ->setRequired(false)
                ->setIgnore(true)
                ->setAutoInsertNotEmptyValidator(false)
                ->setDecorators(
                    array(
                        array(
                            'Label'
                        ),
                        array(
                            array('labelDivClose' => 'HtmlTag'),
                            array(
                                'tag' => $tag,
                                'class'=>'html_element '.$class
                            )
                        )
                    )
                )
                ->clearValidators();

        return $element;
    }

    /**
     * Adiciona honeypots no formulário
     * @return array(Zend_Form_Element_Text) 
     */
    public function honeyPots() {
        $captcha = new Zend_Form_Element_Text('captcha');
        $captcha->setLabel('Captcha');

        $hp_name = new Zend_Form_Element_Text('hp_name');
        $hp_name->setLabel('Name');

        $id_hp = new Zend_Form_Element_Text('id_hp');
        $id_hp->setLabel('ID');

        $elements = array($captcha, $hp_name, $id_hp);
        return $elements;
    }

    /**
     * Verifica se os honeypots foram preenchidos
     * @return boolean      true se não foi preenchido, false se foi 
     */
    public function honeyPotsCheck() {
        $values = $this->getValues();
        if (isset($values['captcha']) && ($values['captcha'] != null && $values['captcha'] != '')) {
            return false;
        }
        if (isset($values['hp_name']) && ($values['hp_name'] != null && $values['hp_name'] != '')) {
            return false;
        }
        if (isset($values['id_hp']) && ($values['id_hp'] != null && $values['id_hp'] != '')) {
            return false;
        }
        return true;
    }

}


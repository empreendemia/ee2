<?php
/**
 * Demand.php - Ee_Form_Demand
 * Formulário para cadastro de uma nova requisição de serviço
 * 
 * @author Mauro Ribeiro
 * @since 2011-07-09
 */
class Ee_Form_Demand extends Ee_Form_Form
{

    /**
     * Constrói o formulário
     */
    public function init()
    {

        $this->setName('demand');

        // importa os filtros da empreendemia
        $this->addElementPrefixPath(
                'Ee_Filter',
                APPLICATION_PATH.'/../library/filters/',
                'filter'
        );
        
	// título da requisição
        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Titulo')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('title'))
              ->addValidator('NotEmpty')
              ->setAttrib('maxlength', 250)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Resumidamente, o que você precisa?')
              ->addErrorMessage('digite um título');
        $this->addElement($title);

        // descrição da requisição
        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Descrição')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('text'))
              ->addValidator('NotEmpty')
              ->setAttrib('class', array('tip_tool_form', 'wordcount'))
              ->setAttrib('title', 'Descreva detalhes do que você precisa')
              ->addErrorMessage('digite detalhadamente o que você precisa');
        $this->addElement($description);

        // setor da requisição
        $sector_mapper = new Ee_Model_Sectors();
        $sectors_data = $sector_mapper->formArray(array('index'=>'id'));
        $options = array(''=>'- escolha um setor') + $sectors_data;
        $sector_id = new Zend_Form_Element_Select('sector_id');
        $sector_id->setLabel('Categoria')
              ->setRequired(true)
              ->addMultiOptions($options)
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Qual a área do serviço que você precisa?')
              ->addErrorMessage('escolha um setor');
        $this->addElement($sector_id);

        // preço da requisição
        $price = new Zend_Form_Element_Select('price');
        $price->setLabel('Preço desejado')
              ->setRequired(true)
              ->addMultiOptions(array(
                  '' => '- escolha o preço desejado',
                  '500' => 'Até R$ 500,00',
                  '1k' => 'Até R$ 1.000,00',
                  '5k' => 'Até R$ 5.000,00',
                  '10k' => 'Até R$ 10.000,00',
                  '10k+' => 'Acima de R$ 10.000,00',
                  '0' => 'Qualquer preço / não sei avaliar'
              ))
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Qual seu orçamento?')
              ->addErrorMessage('escolha um preço');
        $this->addElement($price);

        // prazo do anúncio
        $date_deadline = new Zend_Form_Element_Text('date_deadline');
        $date_deadline->setLabel('Validade do pedido')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty')
              ->setAttrib('class', array('tip_tool_form datepicker'))
              ->setAttrib('title', 'Até que dia você deseja publicar este pedido?')
              ->setValue(date('d/m/Y', time() + 30*24*60*60))
              ->setAttrib('size', 10)
              ->setAttrib('maxlength', 10)
              ->addErrorMessage('escolha uma data');
        $this->addElement($date_deadline);

        // botão de submissão
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Enviar pedido')
              ->setAttrib('class', array('tip_tool_right'))
              ->setAttrib('title', 'Finalizar pedido');
        $this->addElement($submit);
       
    }
    
    /**
     * Pega os valores do formulário
     * @return object
     * @return string $object->demand->title        título da demanda
     * @return string $object->demand->description  descrição da requisição
     * @return int $object->demand->sector_id       setor da requisição
     * @return string $object->demand->price        preço da requisição
     */
    public function getModels() {
        $values = $this->getValues();
        
        $models->demand->title = $values['title'];
        $models->demand->description = $values['description'];
        $models->demand->sector_id = $values['sector_id'];
        $models->demand->price = $values['price'];
        $models->demand->name = $values['name'];
        $models->demand->email = $values['email'];

        $explode = explode('/', $values['date_deadline']);
        $models->demand->date_deadline = $explode[2].'-'.$explode[1].'-'.$explode[0];
        
        return $models;
    }

}


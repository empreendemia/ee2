<?php
/**
 * Demand.php - Ee_Form_Demand
 * Formulário para cadastro de uma nova requisição de serviço
 * 
 * @author Mauro Ribeiro
 * @since 2011-07-09
 */
class Ee_Form_Unloged extends Ee_Form_Form
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
       // nome do requisitor
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Seu nome')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('title'))
              ->addValidator('NotEmpty')
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form','input_example'))
              ->setAttrib('title', 'Digite seu nome e sobrenome')
              ->setValue('Ex.: João Silva')
              ->addErrorMessage('digite seu nome');
        $this->addElement($name);

        // email do requisitor
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Seu email')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('title'))
              ->addValidator('NotEmpty')
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form','input_example'))
              ->setAttrib('title', 'Digite seu e-mail')
              ->setValue('Ex.: joaosilva@empresadojoao.com.br')
              ->addErrorMessage('digite seu e-mail');
        $this->addElement($email);
        
        // cidade e estado do requisitor
        $city_state = new Zend_Form_Element_Text('city_state');
        $city_state->setLabel('Sua cidade e estado')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('title'))
              ->addValidator('NotEmpty')
              ->setAttrib('maxlength', 50)
              ->setAttrib('class', array('tip_tool_form','input_example'))
              ->setAttrib('title', 'Digite sua cidade e seu estado')
              ->setValue('Ex.: Campinas/SP')
              ->addErrorMessage('digite sua cidade e seu estado');
        $this->addElement($city_state);
        
        // título da requisição
        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('O que você precisa?')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('title'))
              ->addValidator('NotEmpty')
              ->setAttrib('maxlength', 250)
              ->setAttrib('class', array('tip_tool_form','input_example'))
              ->setAttrib('title', 'Resumidamente, o que você precisa?')
              ->setValue('Ex.: Preciso de um site')
              ->addErrorMessage('digite um título');
        $this->addElement($title);

        // descrição da requisição
        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Descrição do pedido')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addFilter('MrWilsonTracesKiller', array('text'))
              ->addValidator('NotEmpty')
              ->setAttrib('class', array('tip_tool_form', 'wordcount','input_example'))
              ->setAttrib('title', 'Descreva detalhes do que você precisa')
              ->setValue('Ex.: Quero melhorar minha presença na internet e preciso de um profissional que além de fazem um bom site, também saiba sobre marketing digital para me ajudar a vender mais pela internet.')
              ->addErrorMessage('digite detalhadamente o que você precisa');
        $this->addElement($description);

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
                  '0' => 'não sei avaliar'
              ))
              ->setAttrib('class', array('tip_tool_form'))
              ->setAttrib('title', 'Qual seu orçamento?')
              ->addErrorMessage('escolha um preço');
        $this->addElement($price);

        // botão de submissão
        $submit = new Zend_Form_Element_Image('submit');
        $submit->setLabel('')
              ->setAttrib('class', array('tip_tool_right'))
              ->setAttrib('src', 'images/pages/demands/demands_send_request.png')
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
        
        $models->demand->name = $values['name'];
        $models->demand->email = $values['email'];
        $models->demand->city_state = $values['city_state'];
        $models->demand->title = $values['title'];
        $models->demand->description = $values['description'];
        $models->demand->price = $values['price'];
        $models->demand->date_deadline = mktime(0,0,0,date("m"),date("d")+14,date("Y"));
        
        return $models;
    }

}


<?php

/**
 * Mapper.php - Ee_Model_Mapper
 * Mapper é a classe que serve para procurar dados no banco de dados
 * 
 * @package models
 * @author Mauro Ribeiro
 * @since 2011-06
 */

class Ee_Model_Mapper
{
    
    /**
     * Banco de dados padrão de um Mapper
     */
    protected $_dbTable;
    
    /**
     * Nome do Model (usado para quando o nome do ModelData é diferente do
     * ModelMapper ou o singular/plural é meio complicado)
     */
    protected $_modelDataName = null;

    /**
     * @param $data                 Quando você quer que retorne um ModelData
     * @author  Mauro Ribeiro
     * @since   2011-06
     */
    public function __construct($data = null) {
        $dbtablename = 'Ee_Model_DbTable_'.$this->getModelMapperName();
        $this->_dbTable = new $dbtablename();

        if ($data) {
            $model_name = 'Ee_Model_Data_'.$this->getModelMapperName();
            $return = new $model_name($data);
        }
        else {
            $return = true;
        }

        return $return;
    }


    /**
     * Pega o nome do ModelMapper sem prefixos e sufixos
     * 
     * @return string   nome do ModelMapper 
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function getModelMapperName() {
        $explode = explode('_',get_class($this));
        $name = $explode[2];
        return $name;
    }
    
    
    /**
     * Pega o nome do ModelData sem prefixos e sufixos
     * 
     * @return string   nome do ModelData
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function getModelDataName() {
        if ($this->_modelDataName != null) $name = $this->_modelDataName;
        else {
            $explode = explode('_',get_class($this));
            $name = substr($explode[2], 0, strlen($explode[2]) - 1);
        }
        return $name;
    }
    
    /**
     * Seta um dado no seu respectivo ModelData
     * 
     * @param object
     * @return ModelData
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function set($data = null) {
        $model_name = 'Ee_Model_Data_'.$this->getModelMapperName();
        $return = new $model_name($data);

        return $return;
    }


    /**
     * Transforma um objeto em um array de dados
     * 
     * @param $object   objeto a ser transformado
     * @return array    objeto transformado em array
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    private function _dataArray($object) {
        $data = array();
        foreach ($object as $index => $value) {
            // não pega objetos nem valores nulos
            // aceita expressões mysql
            if ((is_object($value) == false && $value != null) || $value instanceof Zend_Db_Expr) {
                $data[$index] = $value;
            }
        }
        return $data;
    }

    /**
     * Método padrão de procura por uma tupla no banco de dados
     * 
     * @param int $id           id do objeto a ser procurado
     * @return Ee_Model_Data
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function find($id = null) {

        $result = $this->_dbTable->find($id);

        if (0 == count($result)) {
            return;
        }

        $model_name = 'Ee_Model_Data_'.$this->getModelDataName();
        $model = new $model_name();
        $model->set($result->current());

        return $model;
    }


    /**
     * Método padrão de salvar uma tupla no banco de dados
     * 
     * @param Ee_Model_Data $model     objeto a ser salvo
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    function save($object) {
        // se o objeto tiver um id, atualiza
        if (isset($object->id)) {
            $save = $this->_dbTable->update($this->_dataArray($object), array('id = ?' => $object->id));
        }
        // se não, insere nova linha
        else {
            $save = $this->_dbTable->insert($this->_dataArray($object));
            // retorna o id da nova linha adicionada
            $object->id = $this->_dbTable->getAdapter()->lastInsertId();
        }
        return $save;
    }


    /**
     * Método padrão de remoção de uma tupla no banco de dados
     * 
     * @param Ee_Model_Model $model     objeto a ser removido
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    function delete($object) {
        // aceita objeto
        if (is_object($object)) {
            $where = $this->_dbTable->getAdapter()->quoteInto('id = ?', $object->id);
        }
        // aceita id numérico também
        else {
            $where = $this->_dbTable->getAdapter()->quoteInto('id = ?', $object);
        }
        $this->_dbTable->delete($where);
    }


    /**
     * Retorna todos os ids de uma lista de ModelData
     * 
     * @param array(Ee_Model_Data) $data     array de ModelData
     * @return array(int)                    lista de $data[]->id
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function getIds($data) {
        $ids = array();
        foreach ($data as $obj) {
            $ids[] = $obj->id;
        }
        return $ids;
    }

    /**
     * Gerador de slug (slugify)
     * 
     * @param object $model                 objeto ModelData
     * @param string $slugify_column        nome da coluna a se gerar o slug
     * @param string $slug_column           nome da coluna onde salvar o slug
     * @return string                       slug gerado
     * @return false                        caso não encontrou nada
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function slug($model, $slugify_column = 'name', $slug_column = 'slug')
    {
        $result = false;
        // pega o texto a se transformar em slug
        $text = $model->$slugify_column;
        // substitui dígitos ou outras coisas que não são letras por '-'
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        // tira os '-' em excesso
        $text = trim($text, '-');
        // reduz tudo para lowercase
        $text = strtolower($text);
        
        // arranca fora os acentos
        $search = explode(",","À,Á,Ã,Â,É,Ê,Í,Ó,Õ,Ô,Ú,Ü,Ç,à,á,ã,â,é,ê,í,ó,õ,ô,ú,ü,ç");
        $replace = explode(",","A,A,A,A,E,E,I,O,O,O,U,U,C,a,a,a,a,e,e,i,o,o,o,u,u,c");
        $text = str_replace($search, $replace, $text);
        
        // remove os caracteres bizarros que sobraram
        $text = preg_replace('~[^-\w]+~', '', $text);
        
        // se não sobrou nada, retorna o id
        if (empty($text)) $result = $model->id;
        // se sobrou qualquer coisa
        else {

            // verifica no banco de dados se o slug já existe
            $select = $this->_dbTable->select()
                    ->where('`'.$slug_column.'` = ?', $text)
                    ->where('`id` != ?', $model->id)
                    ->limit(1);

            $rows = $this->_dbTable->fetchAll($select);

            // se já existe, retorna o slug
            if (count($rows) == 0) $result = $text;
            // se não existe, concatena com o id
            else $result = $text.'-'.$model->id;
        }

        return $result;
    }

}


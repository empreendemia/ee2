<?php
/**
 * Data.php - Ee_Model_Data
 * Representação dos Model Data em geral
 * 
 * @package models
 * @subpackage data
 * @author Mauro Ribeiro
 * @since 2011-06
 */
class Ee_Model_Data
{
    
    /**
     * Construtor do Model Data
     * @param $data     dados de inicialização
     * @return Ee_Model_Data
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function  __construct($data = null) {
        if ($data) $this->set($data);
    }


    /**
     * Nome do Model Data
     * @return string           nome do Model Data
     * @example Para "Ee_Model_Data_User" retornaria "User"
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function getModelDataName() {
        $explode = explode('_',get_class($this));
        return $explode[2];
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }


    /**
     * Seta os valores para o objeto
     * @param $data     dados a serem setados
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function set($data) {
        foreach($data as $id => $value) {
            if (property_exists($this,$id)) {
                $this->$id = $value;
            }
        }
    }

}


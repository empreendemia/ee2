<?php
/**
 * City.php - Ee_Model_Data_City
 * Representação dos dados de uma cidade
 * 
 * @package models
 * @subpackage data
 * @author Mauro Ribeiro
 * @since 2011-06
 */
require_once 'Data.php';

class Ee_Model_Data_City extends Ee_Model_Data
{
    /**
     * ID da cidade
     * @var int
     */
    public $id;
    /**
     * ID da região (estado) da cidade
     * @var int
     */
    public $region_id;
    /**
     * Região (estado) da cidade
     * @var Ee_Model_Data_Region
     */
    public $region;
    /**
     * Nome da região (estado)
     * @var string
     * @example "Sao Paulo"
     */
    public $name;
    /**
     * Slug da cidade
     * @var string
     * @example "sao-paulo"
     */
    public $slug;


}


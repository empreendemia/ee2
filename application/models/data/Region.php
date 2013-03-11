<?php
/**
 * Region.php - Ee_Model_Data_Region
 * Representação dos dados da região (estado)
 * 
 * @package models
 * @subpackage data
 * @author Mauro Ribeiro
 * @since 2011-06
 */
require_once 'Data.php';

class Ee_Model_Data_Region extends Ee_Model_Data
{
    /**
     * ID da região (estado)
     * @var int
     */
    public $id;
    /**
     * ID do país em que a região se encontra
     * @var int
     */
    public $country_id;
    /**
     * País em que a região se encontra
     * @var int
     */
    public $country;
    /**
     * Sigla do estado
     * @var string
     * @example "SP"
     */
    public $symbol;
    /**
     * Nome do estado
     * @var string
     * @example "São Paulo"
     */
    public $name;
    /**
     * Slug do estado
     * @var string
     * @example "sao-paulo"
     */
    public $slug;


}


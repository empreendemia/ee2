<?php
/**
 * Sector.php - Ee_Model_Data_Sector
 * Representação dos dados do setor
 * 
 * @package models
 * @subpackage data
 * @author Mauro Ribeiro
 * @since 2011-06
 */
require_once 'Data.php';

class Ee_Model_Data_Sector extends Ee_Model_Data
{
    /**
     * ID do setor
     * @var int 
     */
    public $id;
    /**
     * Nome do setor
     * @var string 
     */
    public $name;
    /**
     * Slug do setor
     * @var string
     */
    public $slug;

}


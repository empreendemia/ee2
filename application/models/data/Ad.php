<?php
/**
 * Ad.php - Ee_Model_Data_Ad
 * Representação dos dados de um anúncio
 * 
 * @package models
 * @subpackage data
 * @author Mauro Ribeiro
 * @since 2011
 */
require_once 'Data.php';

class Ee_Model_Data_Ad extends Ee_Model_Data
{
    /**
     * ID do anúncio
     * @var int
     */
    public $id;
    /**
     * ID do produto do anúncio
     * @var int
     */
    public $product_id;
    /**
     * Produto
     * @var Ee_Model_Data_Product 
     */
    public $product;
    /**
     * Estado do anúncio, pode ser "active" ou "inactive"
     * @var string 
     */
    public $status;
    /**
     * Data de criação do anúncio, no formato
     * @var datetime
     */
    public $date_created;
    /**
     * Prazo de exibição do anúncio
     * @var date
     */
    public $date_deadline;
}


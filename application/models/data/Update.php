<?php
/**
 * Update.php - Ee_Model_Data_Update
 * Representação dos dados do status update
 * 
 * @package models
 * @subpackage data
 * @author Mauro Ribeiro
 * @since 2011-06
 */
require_once 'Data.php';

class Ee_Model_Data_Update extends Ee_Model_Data
{
    /**
     * ID do update
     * @var int
     */
    public $id;
    /**
     * ID do usuário que escreveu o update
     * @var int 
     */
    public $user_id;
    /**
     * Usuário que escreveu o update
     * @var Ee_Model_Data_User 
     */
    public $user;
    /**
     * ID da empresa que escreveu o update
     * @var int
     */
    public $company_id;
    /**
     * Empresa que escreveu o update
     * @var Ee_Model_Data_Company
     */
    public $company;
    /**
     * ID do produto relacionado ao update
     * @var int
     */
    public $product_id;
    /**
     * Produto relacionado ao update
     * @var Ee_Model_Data_Product
     */
    public $product;
    /**
     * Tipo do update
     * @var string
     * @example "message", "companyMessage", "newProduct", "companyDataUpdate", "userDataUpdate", "sentPositiveTestimonial", "sentNegativeTestimonial", "sentRecommendation"
     */
    public $type;
    /**
     * Texto do update
     * @var string
     */
    public $text;
    /**
     * Data de envio do update
     * @var datetime
     * @example "2010-01-01 00:00"
     */
    public $date;

}


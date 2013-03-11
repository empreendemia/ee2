<?php
/**
 * Business.php - Ee_Model_Data_Business
 * Representação dos dados de um negócio
 * 
 * @package models
 * @subpackage data
 * @author Mauro Ribeiro
 * @since 2011
 */
require_once 'Data.php';

class Ee_Model_Data_Business extends Ee_Model_Data
{
    /**
     * ID do negócio
     * @var int
     */
    public $id;
    /**
     * ID do usuário que fez o negócio
     * @var int
     */
    public $user_id;
    /**
     * Usuário que fez o negócio
     * @var Ee_Model_Data_User 
     */
    public $user;
    /**
     * ID da empresa que fez o negócio
     * @var int
     */
    public $company_id;
    /**
     * Empresa que fez o negócio
     * @var Ee_Model_Data_Company
     */
    public $company;
    /**
     * ID da empresa que recebeu o negócio
     * @var int
     */
    public $to_company_id;
    /**
     * ID da empresa que recebeu o negócio
     * @var Ee_Model_Data_Company
     */
    public $to_company;
    /**
     * Avaliação que a empresa recebeu, pode ser '+' ou '-'
     * @var char
     */
    public $rate;
    /**
     * Depoimento que a empresa recebeu
     * @var string 
     */
    public $testimonial;
    /**
     * Data do negócio
     * @var datetime
     */
    public $date;


}


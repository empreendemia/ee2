<?php
/**
 * Notify.php - Ee_Model_Data_Notify
 * Representação dos dados das notificações
 * 
 * @package models
 * @subpackage data
 * @author Mauro Ribeiro
 * @since 2011-06
 */
require_once 'Data.php';

class Ee_Model_Data_Notify extends Ee_Model_Data
{
    /**
     * ID da notificação
     * @var int
     */
    public $id;
    /**
     * ID do usuário que recebeu a notificação
     * @var int 
     */
    public $user_id;
    /**
     * Usuário que recebeu a notificação
     * @var Ee_Model_Data_User
     */
    public $user;
    /**
     * ID do usuário que gerou a notificação
     * @var int
     */
    public $from_user_id;
    /**
     * Usuário que gerou a notificação
     * @var Ee_Model_Data_User
     */
    public $from_user;
    /**
     * ID da empresa que gerou a notificação
     * @var int
     */
    public $from_company_id;
    /**
     * Empresa que gerou a notificação
     * @var Ee_Model_Data_Company
     */
    public $from_company;
    /**
     * Tipo da notificação
     * @var string
     * @example "simple" apenas
     */
    public $type;
    /**
     * Mesagem
     * @var string 
     */
    public $message;
    /**
     * Data em que foi gerada a notificação
     * @var datetime
     * @example "2010-01-01 00:00"
     */
    public $date;

}


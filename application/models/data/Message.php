<?php
/**
 * Message.php - Ee_Model_Data_Message
 * Representação das mensagens
 * 
 * @package models
 * @subpackage data
 * @author Mauro Ribeiro
 * @since 2011-06
 */
require_once 'Data.php';

class Ee_Model_Data_Message extends Ee_Model_Data
{
    /**
     * ID da mensagem
     * @var int 
     */
    public $id;
    /**
     * ID do usuário que mandou a mensagem
     * @var int
     */
    public $user_id;
    /**
     * ID do usuário que recebeu a mensagem
     * @var int
     */
    public $to_user_id;
    /**
     * ID da empresa que recebeu a mensagem
     * @var int
     */
    public $to_company_id;
    /**
     * Tipo da mensagem
     * @var string
     * @example "user" para quando for para um usuário
     * @example "company" para quando for para uma empresa
     */
    public $type;
    /**
     * ID da mensagem pai
     * @var int 
     */
    public $parent_id;
    /**
     * Título da mensagem
     * @var string
     */
    public $title;
    /**
     * Corpo da mensagem
     * @var string
     */
    public $body;
    /**
     * Data de envio da mensagem
     * @var datetime
     * @example "2010-01-01 00:00"
     */
    public $date;
    /**
     * Status do enviador
     * @var string
     * @example "sent" ou "deleted"
     */
    public $status_sender;
    /**
     * Status do recebedor
     * @var string
     * @example "unread", "read" ou "deleted"
     */
    public $status_reader;


}


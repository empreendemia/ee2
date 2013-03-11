<?php
/**
 * Demand.php - Ee_Model_Data_Demand
 * Representação das requisições de serviços
 * 
 * @package models
 * @subpackage data
 * @author Mauro Ribeiro
 * @since 2011-06
 */
require_once 'Data.php';

class Ee_Model_Data_Demand extends Ee_Model_Data
{
    /**
     * ID do usuário que solicitou o serviço
     * @var int
     */
    public $user_id;
    /**
     * nome do usuario que solicitou o serviço
     * @var int
     */
    public $name;
    /**
     * email do usuário que solicitou o serviço
     * @var int
     */
    public $email;
    /**
     * Usuário que solicitou o serviço
     * @var Ee_Model_Data_User
     */
    public $city_state;
    /**
     * Cidade e estado do usuário solicitante do serviço
     * @var Ee_Model_Data_User
     */
    public $user;
    /**
     * ID do setor do serviço
     * @var int
     */
    public $sector_id;
    /**
     * Setor do serviço
     * @var Ee_Model_Data_Sector
     */
    public $sector;
    /**
     * Título do serviço
     * @var string
     * @example "Site para minha empresa"
     */
    public $title;
    /**
     * Slug do serviço
     * @var string 
     * @example "site-para-minha-empresa"
     */
    public $slug;
    /**
     * Faixa de preço do orçamento
     * @var string
     * @example "0" -> não sei avaliar
     * @example "500" -> até 500 dinheiros
     * @example "1k" -> até 1000 dinheiros
     * @example "5k" -> até 5000 dinheiros
     * @example "10k" -> até 10000 dinheiros
     * @example "10k+" -> mais de 10000 dinheiros
     */
    public $price;
    /**
     * Descrição do serviço
     * @var string
     */
    public $description;
    /**
     * Status do pedido de serviço
     * @var string
     * @example "active" ou "inactive"
     */
    public $status;
    /**
     * Data de criação do pedido
     * @var datetime
     * @example "2010-01-01 00:00"
     */
    public $date_created;
    /**
     * Data do prazo do pedido
     * @var date
     * @example "2010-01-01"
     */
    public $date_deadline;

    /**
     * Converte o dado do preço para uma string mais entendível
     * @return string
     */
    public function priceStr() {
        if ($this->price == '500') return "Até R$ 500,00";
        else if ($this->price == '1k') return "Até R$ 1.000,00";
        else if ($this->price == '5k') return "Até R$ 5.000,00";
        else if ($this->price == '10k') return "Até R$ 10.000,00";
        else if ($this->price == '10k+') return "Acima de R$ 10.000,00";
        else return "Qualquer preço / não sei avaliar";
    }
}


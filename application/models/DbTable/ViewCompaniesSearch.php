<?php
/**
 * ViewCompaniesSearch.php - Ee_Model_DbTable_ViewCompaniesSearch
 * Representação no banco de dados da tabela view de procura de empresas
 * 
 * @package models
 * @subpackage DbTable
 * @author Mauro Ribeiro
 * @since 2011-08
 */
class Ee_Model_DbTable_ViewCompaniesSearch extends Zend_Db_Table_Abstract
{

    /**
     * Nome da tabela no banco de dados
     * @var string
     */
    protected $_name = 'view_companies_search';
    
    /**
     * Nome da coluna da chave primária
     * @var string
     */
    protected $_primary = 'company_id';


}


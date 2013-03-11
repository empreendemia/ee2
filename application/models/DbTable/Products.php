<?php
/**
 * Products.php - Ee_Model_DbTable_Products
 * Representação no banco de dados dos produtos
 * 
 * @package models
 * @subpackage DbTable
 * @author Mauro Ribeiro
 * @since 2011-06
 */
class Ee_Model_DbTable_Products extends Zend_Db_Table_Abstract
{

    /**
     * Nome da tabela no banco de dados
     * @var string
     */
    protected $_name = 'products';

    /**
     * Procura produto pelo slug
     * @param string $slug
     */
    public function findBySlug($slug)
    {
        $where = $this->getAdapter()->quoteInto('slug = ?', $slug);
        return $this->fetchAll($where, 'slug');
    }
    
}


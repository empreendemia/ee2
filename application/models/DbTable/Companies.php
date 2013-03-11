<?php
/**
 * Companies.php - Ee_Model_DbTable_Companies
 * Representação no banco de dados das empresas
 * 
 * @package models
 * @subpackage DbTable
 * @author Mauro Ribeiro
 * @since 2011-06
 */
class Ee_Model_DbTable_Companies extends Zend_Db_Table_Abstract
{

    /**
     * Nome da tabela no banco de dados
     * @var string
     */
    protected $_name = 'companies';
    
    /**
     * Associa uma empresa a um setor
     * @var array
     */
    protected $_referenceMap    = array(
        'Sector' => array(
            'columns'           => 'sector_id',
            'refTableClass'     => 'Ee_Model_DbTable_Sector',
            'refColumns'        => 'id'
        )
    );

    /**
     * Procura empresa pelo slug
     * @param string $slug
     */
    public function findBySlug($slug)
    {
        $where = $this->getAdapter()->quoteInto('slug = ?', $slug);
        return $this->fetchAll($where, 'slug');
    }

}


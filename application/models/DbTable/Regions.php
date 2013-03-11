<?php
/**
 * Regions.php - Ee_Model_DbTable_Regions
 * Representação no banco de dados das regiões
 * 
 * @package models
 * @subpackage DbTable
 * @author Mauro Ribeiro
 * @since 2011-06
 */
class Ee_Model_DbTable_Regions extends Zend_Db_Table_Abstract
{

    /**
     * Nome da tabela no banco de dados
     * @var string
     */
    protected $_name = 'regions';

    /**
     * Procura região pelo slug
     * @param string $slug
     */
    public function findBySlug($slug)
    {
        $where = $this->getAdapter()->quoteInto('slug = ?', $slug);
        return $this->fetchAll($where, 'slug');
    }

}


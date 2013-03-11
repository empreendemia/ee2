<?php

/**
 * Budgets.php - Ee_Model_Budgets
 * Mapper de orcamentos
 * 
 * @package models
 * @author Mauro Ribeiro
 * @since 2011-06
 */

class Ee_Model_Budgets extends Ee_Model_Mapper
{

    /**
     * Salva novo orçamento
     * 
     * @param object $budget        dados do orcamento
     * @return boolean
     * @author                      Mauro Ribeiro
     * @since                       2011-07
     */
    public function save($budget) {
        $budget->status = 'notAnswered';
        $budget->date = date('Y-m-d H:i:s');
        $save = parent::save($budget);
        return $save;
    }

    /**
     * Conta todos os orçamentos já feitos
     * 
     * @return int $num             número total de orçamentos feitos
     * @author Mauro Ribeiro
     * @since 2011-08
     */
    public function count() {
        $select = $this->_dbTable->select()
            ->from($this->_dbTable, array('COUNT(*) as num'));
        return $this->_dbTable->fetchRow($select)->num;
    }

}


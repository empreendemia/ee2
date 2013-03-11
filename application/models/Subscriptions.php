<?php
/**
 * Subscriptions.php - Ee_Model_Subscriptions
 * Mapper de seguidores de empresas
 * 
 * @package models
 * @author Mauro Ribeiro
 * @since 2011-08
 */
class Ee_Model_Subscriptions extends Ee_Model_Mapper
{

    /**
     * Salva uma nova perseguição
     * 
     * @param int $subscription->user_id        id do cara que segue
     * @param int $subscription->company_id     id da empresa seguida
     * @author Mauro Ribeiro
     */
    public function save($subscription) {

        $select = $this->_dbTable
                ->select()
                ->where('user_id = ?', $subscription->user_id)
                ->where('company_id = ?', $subscription->company_id);

        $rows = $this->_dbTable->fetchAll($select);

        if (count($rows) > 0) return false;
        
        $subscription->date = date('Y-m-d H:i:s');
        
        return parent::save($subscription);
    }

}


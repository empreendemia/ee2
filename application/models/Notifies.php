<?php

/**
 * Notifies.php - Ee_Model_Notifies
 * Mapper de notificações do sistema
 * 
 * @package models
 * @author Mauro Ribeiro
 * @since 2011-06
 */

class Ee_Model_Notifies extends Ee_Model_Mapper
{
    
    /**
     * Lista de notificações para um usuário
     * 
     * @param $user                         o tal usuário
     * @return array(Ee_Model_Data_Notify)  lista de notifies
     * @return null                         caso não encontrou nada
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function findByUser($user) {
        // pode ser objeto ou id
        if (is_object($user)) $user_id = $user->id;
        else $user_id = $user;

        $select = $this->_dbTable
                ->select()
                ->where('user_id = ?', $user_id)
                ->order('id DESC');

        $rows = $this->_dbTable->fetchAll($select);

        // se não encontrou nada
        if (count($rows) == 0) {
            return;
        }

        $notifies = array();

        foreach ($rows as $row) {
            $notify = new Ee_Model_Data_Notify($row);

            // se a notificação vem de algum usuário
            if (isset($row->from_user_id)) {
                $user_mapper = new Ee_Model_Users();
                $user = $user_mapper->find($row->from_user_id);
                $notify->from_user = $user;
            }
            // se a notificação vem de alguma empresa
            if (isset($row->from_company_id)) {
                $company_mapper = new Ee_Model_Companies();
                $company = $company_mapper->find($row->from_company_id);
                $notify->from_company = $company;
            }
            
            $notifies[] = $notify;
        }
        
        return $notifies;
    }


    
    /**
     * Número de notificações para um usuário
     * 
     * @param $user_id          id do sujeito
     * @return int              número de notificações
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function count($user_id) {
        $select = $this->_dbTable->select()
            ->from('notifies', array('COUNT(*) as num'))
            ->where('user_id = ?', $user_id);
        return $this->_dbTable->fetchRow($select)->num;
    }

}


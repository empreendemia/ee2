<?php

/**
 * Invites.php - Ee_Model_Invites
 * Mapper de envio de convites
 * 
 * @package models
 * @author Mauro Ribeiro
 * @since 2011-09
 */

class Ee_Model_Invites extends Ee_Model_Mapper
{
    
    /**
     * Salva um convite
     * 
     * @param $invite           dados do convite
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function save($invite) {

        // verifica se já existe o convite de um usuário para um email
        $select = $this->_dbTable
                ->select()
                ->where('user_id = ?', $invite->user_id)
                ->where('email = ?', $invite->email);

        $rows = $this->_dbTable->fetchAll($select);

        $invite->date = date('Y-m-d H:i:s');
        // se já convidou, atualiza
        if (count($rows) > 0) {
            $invite->id = $rows[0]->id;
        }
        // se não, insere
        return parent::save($invite);
    }

    
    /**
     * Verifica se um convite existe
     * 
     * @param int $invite_id        id do convite
     * @param string $email         email do broder que foi convidado
     * @param date $date            data que o broder foi convidado
     * @return object | boolean false
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function check($invite_id, $email, $date) {

        $select = $this->_dbTable
                ->select()
                ->where('id = ?', $invite_id)
                ->where('email = ?', $email)
                ->where('date = ?', $date);

        $rows = $this->_dbTable->fetchAll($select);

        // se encontrou, retorna os dados do convite
        if (count($rows) > 0) {
            return $rows[0];
        }
        // se não, retorna falso
        return false;
    }

}


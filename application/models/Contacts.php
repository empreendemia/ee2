<?php

/**
 * Contacts.php - Ee_Model_Contacts
 * Mapper de troca de cartões
 * 
 * @package models
 * @author Mauro Ribeiro
 * @since 2011-06
 */

class Ee_Model_Contacts extends Ee_Model_Mapper
{

    /**
     * Procura contatos de um usuário
     * 
     * @param int $user_id                  id do usuário
     * @return array(Ee_Model_Data_User)    usuários que são contatos
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function find($user_id) {
        // quem mandou cartão pro usuário
        $select = $this->_dbTable
                ->select()
                ->from('users', array('id', 'name', 'family_name', 'job', 'company_id', 'image'))
                ->join('contacts', 'contacts.user_id = users.id', null)
                ->where('contacts.contact_id = ?', $user_id)
                ->group('users.id')
                ->order(array('users.name', 'users.family_name'))
                ->setIntegrityCheck(false);

        $rows = $this->_dbTable->fetchAll($select);

        // caso não achou
        if (count($rows) == 0) return false;

        $users = array();

        foreach ($rows as $row) {
            $user = new Ee_Model_Data_User($row);
            $company_mapper = new Ee_Model_Companies();
            // procura a empresa do contato
            $user->company = $company_mapper->find($user->company_id);
            $users[] = $user;
        }

        return $users;
    }

    /**
     * Lista de ids dos contatos de um usuário
     * 
     * @param int $user_id      id do usuário
     * @return array(int)       lista de ids de contatos
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function findIds($user_id) {
        // quem mandou cartão pro usuário
        $select = $this->_dbTable
                ->select()
                ->from('users', array('id'))
                ->join('contacts', 'contacts.user_id = users.id', null)
                ->where('contacts.contact_id = ?', $user_id)
                ->group('users.id')
                ->setIntegrityCheck(false);

        $rows = $this->_dbTable->fetchAll($select);

        // se não encontrou nada
        if (count($rows) == 0) return false;

        $users_ids = array();

        foreach ($rows as $row) {
            $users_ids[] = $row->id;
        }

        return $users_ids;
    }
    
    
    /**
     * Lista de pessoas que mandaram mensagem para um usuário
     * 
     * @param int $user_id                  id do usuário
     * @return array(Ee_Model_Data_User)    usuários que são contatos
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function findMessageSenders($user_id) {
        $message_db = new Ee_Model_DbTable_Messages();
        
        // lista todo mundo que mandou mensagem para o usuário
        $select = $this->_dbTable
                ->select()
                ->from('users', array('id', 'name', 'family_name', 'job', 'company_id', 'image'))
                ->join('messages', 'messages.user_id = users.id', null)
                ->where('messages.to_user_id = ?', $user_id)
                ->group('users.id')
                ->order(array('users.name', 'users.family_name'))
                ->setIntegrityCheck(false);
        $contacts = $this->findIds($user_id);
        
        // arranca fora os contatos do usuário
        if ($contacts) {
            $select->where('messages.user_id NOT IN ('.implode(',',$contacts).')');
        }
        
        $rows = $this->_dbTable->fetchAll($select);

        // se não encontrou nada
        if (count($rows) == 0) return false;

        $users = array();
        $company_mapper = new Ee_Model_Companies();
            
        foreach ($rows as $row) {
            $user = new Ee_Model_Data_User($row);
            // procura a empresa do usuário
            $user->company = $company_mapper->find($user->company_id);
            $users[] = $user;
        }

        return $users;
        
    }

    /**
     * Conta quantos contatos um usuário tem
     * 
     * @param int $user_id = null           id do usuário
     * @return int                          número de contatos
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function count($user_id = null) {
        // número de contatos de um usuário
        if ($user_id) {
            $select = $this->_dbTable->select()
                ->from($this->_dbTable, array('COUNT(*) as num'))
                ->where('contact_id = ?', $user_id);
        }
        // número total de troca de cartões
        else {
            $select = $this->_dbTable->select()
                ->from($this->_dbTable, array('COUNT(*) as num'));
        }
        $return = $this->_dbTable->fetchRow($select)->num;
        return $return;
    }


    /**
     * Salva os dados de uma troca de cartão
     * 
     * @param int $contact->user_id     id do usuário enviando cartão
     * @param int $contact->contact_id  id do usuário recebendo o pedido
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function save($contact) {
        // procura pelos ids dos dois usuários
        $select = $this->_dbTable
                ->select()
                ->where('user_id = ?', $contact->user_id)
                ->where('contact_id = ?', $contact->contact_id);

        $rows = $this->_dbTable->fetchAll($select);

        if (count($rows) > 0) return false;
        return parent::save($contact);
    }

    /**
     * Relação entre dois contatos
     * 
     * @param int $user_id                  id de um usuário
     * @param int $contact_id               id de outro usuário
     * @return string $status = "self"      user e contact é a mesma pessoa
     * @return string $status = "contact"   user e contact são contatos
     * @return string $status = "sender"    user mandou cartão de contact
     * @return string $status = "receiver"  user recebeu cartão de contact
     * @return boolean $status = false      user e contact não se conhecem
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function status($user_id, $contact_id) {

        // ambos tem que estar setados
        if (!$user_id || !$contact_id) return false;

        // não pode ser o mesmo
        if ($user_id == $contact_id) return 'self';

        $sender = false;
        $receiver = false;

        // procura o contato dos dois
        $select = $this->_dbTable
                ->select()
                ->where('user_id = ?', $user_id)
                ->where('contact_id = ?', $contact_id);

        $rows = $this->_dbTable->fetchAll($select);

        if (count($rows) > 0) $sender = true;

        // procura a recíproca
        $select = $this->_dbTable
                ->select()
                ->where('user_id = ?', $contact_id)
                ->where('contact_id = ?', $user_id);

        $rows = $this->_dbTable->fetchAll($select);

        if (count($rows) > 0) $receiver = true;

        if ($sender && $receiver) return 'contact';
        else if ($sender) return 'sender';
        else if ($receiver) return 'receiver';
        else return false;

    }

    /**
     * Relação entre uma empresa e um usuário
     * 
     * @param int $company_id               id da empresa
     * @param int $user_id                  id de um usuário
     * @return string $status = "contact"   o usuário e a empresa são contatos
     * @return string $status = "sender"    a empresa tem cartão do usuário
     * @return string $status = "receiver"  o usuário tem cartão da empresa
     * @return boolean $status = false      a empresa nem o usuário se conhecem
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function companyStatus($company_id, $user_id) {

        if (!$user_id || !$company_id) return false;
        $sender = false;
        $receiver = false;

        // procura se o cara mandou cartão para alguém da empresa
        $select = $this->_dbTable
                ->select()
                ->from('contacts', array('id'))
                ->join('users', 'contacts.contact_id = users.id', null)
                ->where('contacts.user_id = ?', $user_id)
                ->where('users.company_id = ?', $company_id)
                ->setIntegrityCheck(false);
        $rows = $this->_dbTable->fetchAll($select);

        if (count($rows) > 0) $sender = true;

        // procura se alguém da empresa mandou cartão pro cara
        $select = $this->_dbTable
                ->select()
                ->from('contacts', array('id'))
                ->join('users', 'contacts.user_id = users.id', null)
                ->where('contact_id = ?', $user_id)
                ->where('users.company_id = ?', $company_id)
                ->setIntegrityCheck(false);

        $rows = $this->_dbTable->fetchAll($select);

        if (count($rows) > 0) $receiver = true;

        if ($sender && $receiver) return 'contact';
        else if ($sender) return 'sender';
        else if ($receiver) return 'receiver';
        else return false;

    }


    /**
     * Lista pessoas que pediram troca de cartões para um usuário
     * 
     * @param int $user_id                  id de um usuário
     * @return array(Ee_Model_Data_User)    lista de usuários
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function findRequests($user_id) {

        // pessoas que o usuário pediu o cartão
        $select = $this->_dbTable
                ->select()
                ->from('contacts', array('contact_id'))
                ->where('contacts.user_id = ?', $user_id);

        $rows = $this->_dbTable->fetchAll($select);

        $ids = array();

        foreach ($rows as $row) {
            $ids[] = $row->contact_id;
        }

        // pessoas que mandaram o cartão pro usuário
        $select = $this->_dbTable
                ->select()
                ->from('users', array('id', 'name', 'family_name', 'job', 'company_id', 'image'))
                ->join('contacts', 'contacts.user_id = users.id', null)
                ->where('contacts.contact_id = ?', $user_id)
                ->group('users.id')
                ->order(array('users.name', 'users.family_name'))
                ->setIntegrityCheck(false);

        if (count($rows) > 0) $select->where('contacts.user_id NOT IN ('.implode(',',$ids).')');

        $rows = $this->_dbTable->fetchAll($select);

        if (count($rows) == 0) return false;
        
        $users = array();

        foreach ($rows as $row) {
            $user = new Ee_Model_Data_User($row);
            $company_mapper = new Ee_Model_Companies();
            $user->company = $company_mapper->find($user->company_id);
            $users[] = $user;
        }

        return $users;
    }

    /**
     * Conta trocas de cartões pendentes
     * 
     * @param int $user_id      id de um usuário
     * @return int              número de trocas de cartões pendentes
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function countRequests($user_id) {

        // para quem o usuário mandou cartão
        $select = $this->_dbTable
                ->select()
                ->from('contacts', array('contact_id'))
                ->where('user_id = ?', $user_id);

        $rows = $this->_dbTable->fetchAll($select);

        
        $ids = array();

        foreach ($rows as $row) {
            $ids[] = $row->contact_id;
        }
        
        // quem mandou cartão para o usuário
        $select = $this->_dbTable->select()
            ->from('contacts', array('COUNT(*) as num'))
            ->where('user_id IS NOT NULL')
            ->where('contact_id = ?', $user_id);

        if (count($rows) != 0) $select->where('user_id NOT IN ('.implode(',',$ids).')');

        return $this->_dbTable->fetchRow($select)->num;
    }


    /**
     * Recusar troca de cartão
     * 
     * @param int $user_id      id do usuário que pediu troca de cartão
     * @param int $user_id_2    id do usuário que recusou
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function refuse($user_id, $user_id_2) {
        $where = 'user_id = '.$user_id.' AND contact_id = '.$user_id_2;
        $this->_dbTable->delete($where);
    }

}


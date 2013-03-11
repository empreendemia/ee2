<?php
/**
 * Users.php - Ee_Model_Users
 * Mapper de usuários
 * 
 * @package models
 * @author Mauro Ribeiro
 * @since 2011-06
 */
require_once APPLICATION_PATH.'/../library/components/UserFile/UserFile.php';

class Ee_Model_Users extends Ee_Model_Mapper
{

    /**
     * Encontra um usuário
     * 
     * @param int $id               id do camarada
     * @return Ee_Model_Data_User   objeto do camarada
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function find($id) {
        $user = new Ee_Model_Data_User();

        $row = $this->_dbTable->find($id);

        // se não encontrou nada
        if (0 == count($row)) {
            return;
        }

        $user->set($row->current());

        // procura a empresa
        $company_mapper = new Ee_Model_Companies();
        $user->company = $company_mapper->find($user->company_id);

        return $user;
    }

    /**
     * Procura um usuário aleatório ativo e com foto
     * 
     * @return Ee_Model_Data_User   objeto do camarada
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function random() {
        $select = $this->_dbTable
                ->select()
                ->where('image IS NOT NULL')
                ->where('`group` = ?', "user")
                ->limit(1)
                ->order('RAND()');

        $rows = $this->_dbTable->fetchAll($select);

        // se não encontrou nada
        if (0 == count($rows)) {
            return;
        }

        $user = new Ee_Model_Data_User();
        $user->set($rows->current());

        // procura a empresa
        $company_mapper = new Ee_Model_Companies();
        $user->company = $company_mapper->find($user->company_id);

        return $user;
    }

    /**
     * Usuario responsavel pelo chat na empresa
     * 
     * @param Ee_Model_Data_Company $company    empresa que você quer consultar
     * @return array(Ee_Model_User)             membros da empresa
     * @author Rafael Erthal
     * @since 2012-06
     */
    public function findToChat($company) {
        if(!isset($company) || !isset($company->id)) return;

        $select = $this->_dbTable->select()
                ->where(' `company_id` = ?', $company->id)
		->order(array('date_updated DESC'));

        $rows = $this->_dbTable->fetchAll($select);

        // se não encontrar nada
        if (0 == count($rows)) {
            return;
        }

        $users = array();

        foreach ($rows as $row) {
            $user = new Ee_Model_Data_User();
            $user->set($row);
            $user->company = $company;

            $users[] = $user;
        }

        return $users;

    }
    


    /**
     * Membros de uma empresa
     * 
     * @param Ee_Model_Data_Company $company    empresa que você quer consultar
     * @param string $options['where']          filtro de busca
     * @return array(Ee_Model_User)             membros da empresa
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function findByCompany($company, $options = null) {
        $options['where'] = isset($options['where']) ? $options['where'] : '`group` = "user" OR `group` = "admin"';

        $select = $this->_dbTable->select()
                ->where('`company_id` = ?', $company->id)
                ->where($options['where']);

        $rows = $this->_dbTable->fetchAll($select);

        // se não encontrar nada
        if (0 == count($rows)) {
            return;
        }

        $users = array();

        foreach ($rows as $row) {
            $user = new Ee_Model_Data_User();
            $user->set($row);
            $user->company = $company;

            $users[] = $user;
        }

        return $users;

    }
    

    /**
     * Busca usuário por login
     * 
     * @param string $login             login (email) do usuário
     * @return Ee_Model_Data_User       usuário
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function findByLogin($login) {
        $select = $this->_dbTable->select()
                ->where('`login` = ?', $login);

        $rows = $this->_dbTable->fetchAll($select);

        if (0 == count($rows)) {
            return;
        }

        $user = new Ee_Model_Data_User($rows->current());

        // encontra a empresa do cara
        $company_mapper = new Ee_Model_Companies();
        $user->company = $company_mapper->find($user->company_id);

        return $user;

    }


    /**
     * Procura os admins do sistema
     * 
     * @param string $login             login (email) do usuário
     * @return Ee_Model_Data_User       usuário
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function findAdmins() {
        $select = $this->_dbTable->select()
                ->where('`group` = "admin"');

        $rows = $this->_dbTable->fetchAll($select);

        // se não achou nada
        if (0 == count($rows)) {
            return;
        }

        $users = array();

        foreach ($rows as $row) {
            $user = new Ee_Model_Data_User();
            $user->set($row);

            $users[] = $user;
        }

        return $users;

    }

    /**
     * Contatos de um usuário
     * 
     * @param $user                         (int)id do usuário ou (Ee_Model_User)
     * @return array(Ee_Model_Data_User)    contatos do usuário
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function contacts($user) {
        if (is_object($user)) $user_id = $user->id;
        else $user_id = $user;

        $contact_mapper = new Ee_Model_Contacts();
        return $contact_mapper->find($user_id);
    }

    /**
     * Id de contatos de um usuário
     * 
     * @param Ee_Model_Data_User    usuário
     * @return array(int)           id dos contatos
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function contactsIds($user) {
        if (is_object($user)) $user_id = $user->id;
        else $user_id = $user;

        $contact_mapper = new Ee_Model_Contacts();
        return $contact_mapper->findIds($user_id);
    }
    

    /**
     * Número de contatos de um usuário
     * 
     * @param Ee_Model_Data_User    usuário
     * @return int                  número de contatos de um usuário
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function countContacts($user) {
        if (is_object($user)) $user_id = $user->id;
        else $user_id = $user;

        $contact_mapper = new Ee_Model_Contacts();
        return $contact_mapper->count($user_id);
    }

    /**
     * Pessoas que mandaram mensagem para um usuário
     * 
     * @param $user                         id ou objeto do usuário
     * @return array(Ee_Model_Data_User)    lista de usuários
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function messageContacts($user) {
        if (is_object($user)) $user_id = $user->id;
        else $user_id = $user;

        $contact_mapper = new Ee_Model_Contacts();
        return $contact_mapper->findMessageSenders($user_id);
    }

    /**
     * Verifica se um login está disponível
     * 
     * @param string $login         login (email)
     * @return boolean              true se está disponível, false se não
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function isLoginAvailable($login) {
        $select = $this->_dbTable->select()
                ->where('`login` = ?', $login);
        $rows = $this->_dbTable->fetchAll($select);
        if (0 == count($rows)) return true;
        else return false;
    }

    /**
     * Altera imagem de um usuário
     * 
     * @param $user->id             id da empresa para trocar a foto
     * @param $user->image          nome da imagem antiga
     * @param $image                nova imagem
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function imageUpdate($user, $image) {
        $userfile = new Ee_UserFile();
        $userfile->setUserThumb($user->id, $user->image, $image);
        $save_data->id = $user->id;
        $save_data->date_updated = date('Y-m-d H:i:s');
        $save_data->image = $userfile->file;
        $this->save($save_data);
        $user->image = $userfile->file;
    }

}

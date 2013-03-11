<?php
/**
 * Updates.php - Ee_Model_Updates
 * Mapper de status updates 
 * 
 * @package models
 * @author Mauro Ribeiro
 * @since 2011-06
 */
class Ee_Model_Updates extends Ee_Model_Mapper
{

    /**
     * Statuses updates dos contatos de um usuário
     * 
     * @param $user                                 (int)id do usuário ou (Ee_Model_User) do subscriber
     * @param $page                                 (int)número da página
     * @param array(int) $options['companies_ids']  opcional - lista de empresas 
     * @return array(Ee_Model_Data_Update)          lista de updates 
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function subscribedUsers($user, $page = 1, $options = null) {
        // aceita objeto ou id
        if (is_object($user)) $user_id = $user->id;
        else $user_id = $user;

        $user_mapper = new Ee_Model_Users();
        // lista de ids dos contatos do usuário
        $users_ids = $user_mapper->contactsIds($user);

        // adiciona o id do próprio usuário na lista
        if ($users_ids) $users_ids = array_merge($users_ids, array($user_id));
        else $users_ids = array($user_id);

        $company_mapper = new Ee_Model_Companies();

        if (isset($options['companies_ids'])) {
            $companies_ids = $options['companies_ids'];
        }
        else {
            $companies_ids = $company_mapper->contactsIds($user);
            $companies_ids_2 = $company_mapper->subscribedsIds($user);
            
            if ($companies_ids && $companies_ids_2) {
                $companies_ids = array_merge($companies_ids, $companies_ids_2);
            }
            else if ($companies_ids_2) {
                $companies_ids = $companies_ids_2;
            }
        }

        $select = $this->_dbTable
                ->select()
                ->from('updates', array('*'))
                ->where('
                    updates.user_id IN ('.implode(',', $users_ids).')
                    OR updates.company_id IN ('.implode(',', $companies_ids).')
                ')
                ->order('updates.id DESC')
                ->limitPage($page, 10);

        $rows = $this->_dbTable->fetchAll($select);

        if (count($rows) == 0) return false;

        $updates = array();
        $users = array();
        $companies = array();

        foreach ($rows as $row) {
            $update = new Ee_Model_Data_Update();
            $update->set($row);
            // procura o autor do post
            if ($update->user_id) {
                if (!isset($users[$update->user_id])) {
                    $users[$update->user_id] = $user_mapper->find($update->user_id);
                }
                $update->user = $users[$update->user_id];
            }
            // procura a empresa autora do post
            if ($update->company_id) {
                if (!isset($companies[$update->company_id])) {
                    $companies[$update->company_id] = $company_mapper->find($update->company_id);
                }
                $update->company = $companies[$update->company_id];
            }

            $updates[] = $update;
        }

        return $updates;
    }


    /**
     * Status updates das empresas que o cara assina
     * @deprecated
     */
    public function subscribedCompanies($user, $page = 1) {
        if (is_object($user)) $user_id = $user->id;
        else $user_id = $user;

        $company_mapper = new Ee_Model_Companies();
        $companies = $company_mapper->contacts($user);
        $companies_ids = $company_mapper->getIds($companies);

        $updates_companies = array();

        if ($companies) {
            $select = $this->_dbTable
                    ->select()
                    ->from('updates', array('*'))
                    ->where('updates.company_id IN ('.implode(',', $companies_ids).')')
                    ->order('updates.id DESC')
                    ->limitPage($page, 2);

            $rows = $this->_dbTable->fetchAll($select);

            foreach ($rows as $row) {
                $update = new Ee_Model_Data_Update();
                $update->set($row);
                $update->company = $company_mapper->find($update->company_id);

                if (isset($update->user_id)) $update->user = $user_mapper->find($update->user_id);

                $updates_companies[] = $update;
            }
        }

        return $updates_companies;
    }
    
    /**
     * Lista os updates de uma empresa
     * 
     * @param $company                      (int)id da empresa ou (Ee_Model_Data_Company) da empresa
     * @param int $options['limit']         opcional - número máximo de resultados por página
     * @param int $options['page']          opcional - página
     * @return array(Ee_Model_Data_Update)  lista de updates 
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function findByCompany($company, $options = null) {
        // aceita id ou objeto
        if (is_object($company)) $company_id = $company->id;
        else $company_id = $company;
        
        $options['limit'] = isset($options['limit']) ? $options['limit'] : 10;
        $options['page'] = isset($options['page']) ? $options['page'] : 1;

        $updates = array();
        
        $select = $this->_dbTable
                ->select()
                ->from('updates', array('*'))
                ->where('updates.company_id = ?', $company_id)
                ->where('updates.type = ?', 'companyMessage')
                ->order('updates.id DESC')
                ->limitPage($options['page'], $options['limit']);

        $rows = $this->_dbTable->fetchAll($select);

        foreach ($rows as $row) {
            $update = new Ee_Model_Data_Update();
            $update->set($row);
            $update->company = $company;

            $updates[] = $update;
        }

        return $updates;
    }

}


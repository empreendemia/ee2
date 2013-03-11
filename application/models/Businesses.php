<?php

/**
 * Businesses.php - Ee_Model_Businesses
 * Mapper de negócios, avaliações e depoimentos
 * 
 * @package models
 * @author Mauro Ribeiro
 * @since 2011-06
 */

class Ee_Model_Businesses extends Ee_Model_Mapper
{

    /**
     * Procura avaliações feitas para uma empresa
     * 
     * @param Ee_Model_Data_Company $company     empresa que você quer consultar
     * @return array(Ee_Model_Data_Business)     negócios da empresa
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function findByCompany($company) {
        // aceita objeto ou id da empresa
        if (is_object($company)) $company_id = $company->id;
        else $company_id = $company;

        // negócios feitos para a empresa
        $select = $this->_dbTable->select()
                ->where('`to_company_id` = ?', $company_id)
                ->order('date DESC');

        $rows = $this->_dbTable->fetchAll($select);

        // se não encontrou nenhum negócio
        if (0 == count($rows)) {
            return;
        }

        $businesses = array();

        foreach ($rows as $row) {
            $business = new Ee_Model_Data_Business();
            $business->set($row);

            // quem fez o negócio
            $user_mapper = new Ee_Model_Users();
            $user = $user_mapper->find($row->user_id);
            $business->user = $user;

            // de que empresa ele é
            $company_mapper = new Ee_Model_Companies();
            $company = $company_mapper->find($row->company_id);
            $business->company = $company;

            $businesses[] = $business;
        }

        return $businesses;

    }

    /**
     * Conta o número de avaliações feitos na rede
     * 
     * @return int $num             número total de avaliações feitas
     * @author Mauro Ribeiro
     * @since 2011-08
     */
    public function count() {
        $select = $this->_dbTable->select()
            ->from($this->_dbTable, array('COUNT(*) as num'));
        return $this->_dbTable->fetchRow($select)->num;
    }

    /**
     * Conta o número de avaliações que uma empresa recebeu
     * 
     * @param Ee_Model_Data_Company $company     empresa que você quer consultar
     * @return int                          número de negócios com a empresa
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function countByCompany($company) {
        // aceita o objeto ou o id da empresa
        if (is_object($company)) $company_id = $company->id;
        else $company_id = $company;

        $select = $this->_dbTable->select()
                ->from('businesses', 'count(*) as count')
                ->where('`to_company_id` = ?', $company_id);

        return $this->_dbTable->fetchRow($select)->count;
    }


    /**
     * Avalia uma empresa
     * 
     * @param int $business->user_id            id do usuário que avaliou
     * @param int $business->company_id         id da empresa que avaliou
     * @param int $business->to_company_id      id da empresa sendo avaliada
     * @param enum("+","-") $business->rate     avaliação positiva ou negativa
     * @param string $business->testimonial     opcional - depoimento
     * @author Mauro Ribeiro
     * @since 2011-08
     */
    public function rate($business) {

        // não deixa empressa = null avaliar
        if (!isset($business->company_id)) {
            throw new Exception('Ghost company can`t rate');
        }
        // não deixa avaliar empressa = null
        if (!isset($business->to_company_id)) {
            throw new Exception('Can`t rate ghost company');
        }
        // não deixa avaliar a mesma empresa
        if ($business->to_company_id == $business->company_id) {
            throw new Exception('Can`t rate the same company');
        }

        $business->date = date('Y-m-d H:i:s');

        // se não tem depoimento junto com a avaliação
        if (!isset($business->testimonial) || $business->testimonial == '') $business->testimonial = '';

        // procura avaliações que a empresa do usuário já fez para a outra
        $select = $this->_dbTable->select()
                ->where('company_id = ?', $business->company_id)
                ->where('to_company_id = ?', $business->to_company_id);

        $rows = $this->_dbTable->fetchAll($select);

        $data = array();
        foreach ($business as $index => $value) {
            if (is_object($value) == false && $value != null) {
                $data[$index] = $value;
            }
        }
        
        // se ainda não tem nenhuma avaliação da empresa para a outra, insere
        if (count($rows) == 0) {
            return $this->_dbTable->insert($data);
        }

        // se já tem avaliação, atualiza
        $data['id'] = $rows[0]->id;
        return $this->_dbTable->update($data, array('id = ?' => $data['id']));
    }

    /**
     * Lista os últimos depoimentos de usuários com foto
     * 
     * @param int $num                          quantas avaliações mostrar
     * @return array(Ee_Model_Data_Business)    últimas avaliações
     * @author Mauro Ribeiro
     * @since 2011-09
     */
    public function last($num = 10) {
        $select = $this->_dbTable->select()
                ->from($this->_dbTable)
                ->order('date DESC')
                ->limit($num)
                ->join('users', 'businesses.user_id = users.id', null)
                ->where('testimonial != ""')
                ->where('testimonial IS NOT NULL')
                ->where('users.image != ""')
                ->where('users.image IS NOT NULL')
                ->setIntegrityCheck(false)
                ->group('user_id');

        $rows = $this->_dbTable->fetchAll($select);

        // se não encontrou nada
        if (0 == count($rows)) {
            return;
        }

        $user_mapper = new Ee_Model_Users();
        $company_mapper = new Ee_Model_Companies();
        $businesses = array();

        foreach ($rows as $row) {
            $business = new Ee_Model_Data_Business($row);
            // autor do depoimento
            $business->user = $user_mapper->find($business->user_id);
            // empresa que recebeu o depoimento
            $business->to_company = $company_mapper->find($business->to_company_id);
            $businesses[] = $business;
        }

        return $businesses;

    }
    
    public function hasBusinesses($user_id, $company_id) {
        $select = $this->_dbTable->select()
                ->from($this->_dbTable)
                ->where('
                    user_id = '.$user_id.'
                    and to_company_id = '.$company_id.'
                 ');

        $rows = $this->_dbTable->fetchAll($select);

        return (0 != count($rows));
    }

    /**
     * Últimas avaliações feitas ou recebidas por um usuário
     * 
     * @param int $num                          quantas avaliações mostrar
     * @param int $options['limit'] = 5         opcional - número máximo de resultados
     * @param array(int) $options['companies_ids']  opcional - lista de empresas
     * @return array(Ee_Model_Data_Business)    últimas avaliações
     * @return array()                          caso não encontrou nada
     * @author Mauro Ribeiro
     * @since 2011-09
     */
    public function findBySubscriber($user_id, $options = null) {
        $company_mapper = new Ee_Model_Companies();

        // por padrão, lista as 5 últimas
        $limit = isset($options['limit']) ? $options['limit'] : 5;
        
        // empresas que o cara tem contato
        if (isset($options['companies_ids'])) {
            $companies_ids = $options['companies_ids'];
        }
        else {
            $companies_ids = $company_mapper->contactsIds($user);
        }

        // se o cara não tem contato com ninguém, não tem depoimento
        if (!$companies_ids) return;

        $select = $this->_dbTable->select()
                ->from($this->_dbTable)
                ->order('date DESC')
                ->where('
                    company_id IN ('.implode(',', $companies_ids).')
                    OR to_company_id IN ('.implode(',', $companies_ids).')
                 ')
                ->limit($limit);

        $rows = $this->_dbTable->fetchAll($select);

        // se não achou nada
        if (0 == count($rows)) {
            return;
        }

        $user_mapper = new Ee_Model_Users();
        $company_mapper = new Ee_Model_Companies();
        $businesses = array();

        foreach ($rows as $row) {
            $business = new Ee_Model_Data_Business($row);
            // quem fez a avaliação
            $business->user = $user_mapper->find($business->user_id);
            // empresa que recebei a avaliação
            $business->to_company = $company_mapper->find($business->to_company_id);
            $businesses[] = $business;
        }

        return $businesses;
    }

}


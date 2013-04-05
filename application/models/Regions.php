<?php

/**
 * Regions.php - Ee_Model_Regions
 * Mapper de regiões (estdos)
 * 
 * @package models
 * @author Mauro Ribeiro
 * @since 2011-06
 */
class Ee_Model_Regions extends Ee_Model_Mapper
{

    /**
     * Procura os dados de região
     * 
     * @param $id_or_slug              (int)id ou (string)slug da região
     * @return Ee_Model_Data_Region    objeto da região
     * @since 2011-06
     */
    public function find($id_or_slug) {

        if (is_numeric($id_or_slug)) {
            $result = $this->_dbTable->find($id_or_slug);
        }
        else {
            $result = $this->_dbTable->findBySlug($id_or_slug);
        }

        if (0 == count($result)) {
            return;
        }

        $region = new Ee_Model_Data_Region($result->current());
        return $region;
    }


    /**
     * Procura todas as regiões em ordem alfabética
     * 
     * @return array(Ee_Model_Data_Region)  lista de regiões
     * @since 2011-06
     */
    public function findAll() {

        $select = $this->_dbTable
                ->select()
                ->order('name ASC');

        $rows = $this->_dbTable->fetchAll($select);

        $regions = array();

        foreach ($rows as $row) {
            $region = new Ee_Model_Data_Region($row);
            $regions[] = $region;
        }

        return $regions;

    }


    /**
     * Lista de ids de todas as regiões
     * 
     * @param string $id = "id"     nome da coluna que está o id
     * @since 2011
     */
    public function formArray($id = 'id') {

        $select = $this->_dbTable
                ->select()
                ->order('name ASC');

        $rows = $this->_dbTable->fetchAll($select);

        $regions = array();

        foreach ($rows as $row) {
            $region = new Ee_Model_Data_Region($row);
            $regions[$region->$id] = $region->name;
        }

        return $regions;

    }


    /**
     * Número de empresas em uma região
     * 
     * @return array(Ee_Model_Data_Company)     lista de empresas
     * @since 2011-06
     */
    public function countCompanies() {
        $company_db = new Ee_Model_DbTable_Companies();
        $select = $company_db->select()
            ->from('companies', array('COUNT(*) as count_companies'))
            ->join('cities', 'cities.id = companies.city_id', null)
            ->join('regions', 'regions.id = cities.region_id', array('id','symbol','name'))
            ->group('regions.id')
            ->where('status = ?','active')
            ->setIntegrityCheck(false);
        $rows = $company_db->fetchAll($select);

        $regions = array();
        foreach ($rows as $row) {
            $region = new Ee_Model_Data_Region($row);
            $region->count_companies = $row->count_companies;
            $regions[] = $region;
        }

        return $regions;
    }
}


<?php
/**
 * Cities.php - Ee_Model_Cities
 * Mapper de cidades
 * 
 * @package models
 * @author Mauro Ribeiro
 * @since 2011-06
 */
class Ee_Model_Cities extends Ee_Model_Mapper
{

    /**
     * Procura uma cidade 
     * @param $id_or_slug           id ou slug da cidade
     * @return Ee_Model_Data_City 
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

        $city = new Ee_Model_Data_City($result->current());
        return $city;
    }
    

    /**
     * Array de lista de cidades de uma região (estado) para formulário no formato vetor[$id] = $value
     * @param int $region_id            id da região
     * @param string $id                que coluna vai no índice do array
     * @param string $value             que coluna vai no valor do array
     * @return array()
     */
    public function formArray($region_id = null, $id = 'id', $value = 'name') {

        // aceita objeto ou id numérico
        if (!is_numeric($region_id)) {
            $region_mapper = new Ee_Model_Regions();
            $region = $region_mapper->find($region_id);
            $region_id = $region->id;
        }
        
        // procura as cidades da região
        $select = $this->_dbTable->select()
            ->where('region_id = ?', $region_id)
            ->order('name ASC');

        $rows = $this->_dbTable->fetchAll($select);

        $cities = array();

        foreach ($rows as $row) {
            $cities[$row->$id] = $row->$value;
        }

        return $cities;
    }
    
    /**
     * Número de empresas em uma região por cidade
     * @param int $regions_ids
     * @return array(Ee_Model_Data_City)
     */
    public function countCompaniesByRegions($regions_ids) {
        $company_db = new Ee_Model_DbTable_Companies();
        $select = $company_db->select()
            ->from('companies', array('COUNT(*) as count_companies'))
            ->join('cities', 'cities.id = companies.city_id', array('id','name'))
            ->where('cities.region_id IN ('.implode(',', $regions_ids).')')
            ->where('status = ?','active')
            ->group('cities.id')
            ->setIntegrityCheck(false);
        $rows = $company_db->fetchAll($select);

        $cities = array();
        foreach ($rows as $row) {
            $city = new Ee_Model_Data_City($row);
            $city->count_companies = $row->count_companies;
            $cities[] = $city;
        }

        return $cities;
    }

    /**
     * Cidades mais populares (com mais empresas)
     * @param int $limit                    número máximo de cidades
     * @return array(Ee_Model_Data_City)    lista das cidades
     */
    public function mostPopular($limit = 10) {
        $company_db = new Ee_Model_DbTable_Companies();
        $select = $company_db->select()
            ->from('companies', array('city_id', 'COUNT(*) as count_companies'))
            ->join('cities', 'cities.id = companies.city_id', array('id','name','slug'))
            ->join('regions', 'cities.region_id = regions.id', array('region_id'=>'id','region_name'=>'name','region_slug'=>'slug'))
            ->group('companies.city_id')
            ->order('count_companies DESC')
            ->limit($limit)
            ->setIntegrityCheck(false);
        $rows = $company_db->fetchAll($select);

        $cities = array();
        foreach ($rows as $row) {
            $city = new Ee_Model_Data_City($row);
            $city->count_companies = $row->count_companies;
            $region['id'] = $row['region_id'];
            $region['name'] = $row['region_name'];
            $region['slug'] = $row['region_slug'];
            $city->region = new Ee_Model_Data_Region($region);
            $city->count_companies = $row['count_companies'];
            $cities[] = $city;
        }

        return $cities;
    }

}


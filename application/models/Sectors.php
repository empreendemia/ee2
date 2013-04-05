<?php
/**
 * Sectors.php - Ee_Model_Sectors
 * Mapper de setores
 * 
 * @package models
 * @author Mauro Ribeiro
 * @since 2011-06
 */
class Ee_Model_Sectors extends Ee_Model_Mapper
{
    /**
     * Procura um setor
     * 
     * @param $id_or_slug              (int)id ou (string)slug do setor
     * @return Ee_Model_Data_Sector    objeto do setor
     * @author Mauro Ribeiro
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

        $sector = new Ee_Model_Data_Sector($result->current());
        return $sector;
    }

    /**
     * Procura todos os setores
     * 
     * @return array(Ee_Model_Data_Sector)    lista de setores
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function findAll() {

        $select = $this->_dbTable
                ->select()
                ->order('name ASC');

        $rows = $this->_dbTable->fetchAll($select);

        $sectors = array();

        foreach ($rows as $row) {
            $sector = new Ee_Model_Data_Sector($row);
            $sectors[] = $sector;
        }

        return $sectors;

    }
    
    /**
     * Dados para formulário
     * 
     * @return array(id=>name)  Lista de ids e nomes dos setores
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function formArray() {

        $select = $this->_dbTable
                ->select()
                ->order('name ASC');

        $rows = $this->_dbTable->fetchAll($select);

        $sectors = array();

        foreach ($rows as $row) {
            $sector = new Ee_Model_Data_Sector($row);
            $sectors[$sector->id] = $sector->name;
        }

        return $sectors;

    }

    /**
     * Setores presentes em uma cidade
     * 
     * @param int $city_id                  id da cidade
     * @return array(Ee_Model_Data_Sector)  setores
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function findByCity($city_id) {
        // lista todos os setores
        $select = $this->_dbTable
                ->select()
                ->order('name ASC');
        $rows = $this->_dbTable->fetchAll($select);
        $sectors = array();
        foreach ($rows as $row) {
            $company_db = new Ee_Model_DbTable_Companies();
            // procura se tem empresas da cidade num setor
            $select = $company_db->select()
                ->from('companies', array('COUNT(*) as num'))
                ->where('city_id = ?', $city_id)
                ->where('sector_id = ?', $row->id);
            $count = $company_db->fetchRow($select)->num;
            // se tiver, adiciona à resposta
            if ($count > 0) {
                $sector = new Ee_Model_Data_Sector($row);
                $sector->count_companies = $count;
                $sectors[] = $sector;
            }
        }
        return $sectors;
    }

    /**
     * Número de empresas por setor
     * 
     * @return array(Ee_Model_Data_Sector)                      lista de setores
     * @return array(Ee_Model_Data_Sector)->count_companies     número de empresas
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function countCompanies() {
        $select = $this->_dbTable
                ->select()
                ->order('name ASC');
        $rows = $this->_dbTable->fetchAll($select);
        $sectors = array();
        foreach ($rows as $row) {
            $company_db = new Ee_Model_DbTable_Companies();
            $select = $company_db->select()
                ->from('companies', array('COUNT(*) as num'))
                ->where('status = ?','active')
                ->where('sector_id = ?', $row->id);
            $count = $company_db->fetchRow($select)->num;
            if ($count > 0) {
                $sector = new Ee_Model_Data_Sector($row);
                $sector->count_companies = $count;
                $sectors[] = $sector;
            }
        }
        return $sectors;
    }

    /**
     * Número de empresas por setores dentro de uma lista de cidades
     * 
     * @param array(int) $cities_ids                            lista de ids de cidades
     * @return array(Ee_Model_Data_Sector)                      lista de setores
     * @return array(Ee_Model_Data_Sector)->count_companies     número de empresas
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function countCompaniesByCities($cities_ids) {
        $select = $this->_dbTable
                ->select()
                ->order('name ASC');
        $rows = $this->_dbTable->fetchAll($select);
        $sectors = array();
        foreach ($rows as $row) {
            $company_db = new Ee_Model_DbTable_Companies();
            $select = $company_db->select()
                ->from('companies', array('COUNT(*) as num'))
                ->where('city_id IN ('.implode(',',$cities_ids).')')
                ->where('status = ?','active')
                ->where('sector_id = ?', $row->id);
            $count = $company_db->fetchRow($select)->num;
            if ($count > 0) {
                $sector = new Ee_Model_Data_Sector($row);
                $sector->count_companies = $count;
                $sectors[] = $sector;
            }
        }
        return $sectors;
    }
    /**
     * Número de ofertas por setor
     * 
     * @return array(Ee_Model_Data_Sector)                      lista de setores
     * @return array(Ee_Model_Data_Sector)->count_companies     número de empresas
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function countOffers() {
        $select = $this->_dbTable
                ->select()
                ->order('name ASC');
        $rows = $this->_dbTable->fetchAll($select);
        $sectors = array();
        foreach ($rows as $row) {
            $product_db = new Ee_Model_DbTable_Products();
            $select = $product_db->select()
                ->from('products', array('COUNT(*) as num'))
                ->join('companies', 'companies.id = products.company_id', null)
                ->where('companies.sector_id = ?', $row->id)
                ->where('companies.status = "active"')
                ->where('products.offer_status = ?', 'active')
                ->where('products.offer_date_deadline >= NOW()')
                ->setIntegrityCheck(false);
            $count = $product_db->fetchRow($select)->num;
            if ($count > 0) {
                $sector = new Ee_Model_Data_Sector($row);
                $sector->count_companies = $count;
                $sectors[] = $sector;
            }
        }
        return $sectors;
    }

    /**
     * Número de ofertas por setor dentro de uma lista de cidades
     * 
     * @param array(int) $cities_ids                            lista de ids de cidades
     * @return array(Ee_Model_Data_Sector)                      lista de setores
     * @return array(Ee_Model_Data_Sector)->count_companies     número de empresas
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function countOffersByCities($cities_ids) {
        $select = $this->_dbTable
                ->select()
                ->order('name ASC');
        $rows = $this->_dbTable->fetchAll($select);
        $sectors = array();
        foreach ($rows as $row) {
            $product_db = new Ee_Model_DbTable_Products();
            $select = $product_db->select()
                ->from('products', array('COUNT(*) as num'))
                ->join('companies', 'companies.id = products.company_id', null)
                ->where('companies.city_id IN ('.implode(',',$cities_ids).')')
                ->where('companies.sector_id = ?', $row->id)
                ->where('companies.status = "active"')
                ->where('products.offer_status = ?', 'active')
                ->where('products.offer_date_deadline >= NOW()')
                ->setIntegrityCheck(false);
            $count = $product_db->fetchRow($select)->num;
            if ($count > 0) {
                $sector = new Ee_Model_Data_Sector($row);
                $sector->count_companies = $count;
                $sectors[] = $sector;
            }
        }
        return $sectors;
    }

}


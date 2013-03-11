<?php

/**
 * Companies.php - Ee_Model_Companies
 * Mapper de empresas
 * 
 * @package models
 * @author Mauro Ribeiro
 * @since 2011-06
 */

class Ee_Model_Companies extends Ee_Model_Mapper
{

    /**
     * Procura empresa
     * 
     * @param $id_or_slug               (int)id ou (string)slug da empresa
     * @return Ee_Model_Data_Company    objeto da empresa encontrada
     * @return false                    caso não encontre nada
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function find($id_or_slug) {

        // procura por id
        if (is_numeric($id_or_slug)) {
            $result = $this->_dbTable->find($id_or_slug);
        }
        // procura por slug
        else {
            $result = $this->_dbTable->findBySlug($id_or_slug);
        }

        // se não encontrou
        if (0 == count($result)) {
            return false;
        }

        $company = new Ee_Model_Data_Company($result->current());

        // pega o setor
        $sector_mapper = new Ee_Model_Sectors();
        $company->sector = $sector_mapper->find($company->sector_id);

        // pega a cidade
        $city_mapper = new Ee_Model_Cities();
        $company->city = $city_mapper->find($company->city_id);

        // pega o estado
        $region_mapper = new Ee_Model_Regions();
        $company->city->region = $region_mapper->find($company->city->region_id);

        return $company;
    }

    /**
     * Procura empresa aleatória ativa e com imagem
     * 
     * @return false|Ee_Model_Data_Company    objeto da empresa encontrada
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function random() {

        $select = $this->_dbTable
                ->select()
                ->where('image IS NOT NULL')
                ->where('status = "active"')
                ->limit(1)
                ->order('RAND()');

        $rows = $this->_dbTable->fetchAll($select);
        
        // se não encontrou
        if (0 == count($rows)) {
            return false;
        }

        $company = new Ee_Model_Data_Company($rows->current());

        // pega o setor
        $sector_mapper = new Ee_Model_Sectors();
        $company->sector = $sector_mapper->find($company->sector_id);

        // pega a cidade
        $city_mapper = new Ee_Model_Cities();
        $company->city = $city_mapper->find($company->city_id);

        // pega o estado
        $region_mapper = new Ee_Model_Regions();
        $company->city->region = $region_mapper->find($company->city->region_id);

        return $company;
    }

    /**
     * Retorna lista de empresas que um usuário tem contato
     * 
     * @param $user                             (int)id do usuário ou (Ee_Model_Data_User)
     * @return array(Ee_Model_Data_Company)     lista de empresas
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function contacts($user) {
        // aceita objeto ou id
        if (is_object($user)) $user_id = $user->id;
        else $user_id = $user;

        // lista todo mundo que o cara é contato
        $select = $this->_dbTable
                ->select()
                ->from('companies', array('companies.id', 'companies.name', 'companies.slug'))
                ->join('users', 'users.company_id = companies.id', null)
                ->join('contacts', 'contacts.user_id = users.id', null)
                ->where('contacts.contact_id = ?', $user_id)
                ->group('companies.id')
                ->order('companies.id')
                ->setIntegrityCheck(false);

        $rows = $this->_dbTable->fetchAll($select);

        $companies = array();

        foreach ($rows as $row) {
            $company = new Ee_Model_Data_Company();
            $company->set($row);
            $companies[] = $company;
        }

        return $companies;

    }
    
    /**
     * Retorna lista de ids de empresas que um usuário tem contato
     * 
     * @param $user         (int)id do usuário ou (Ee_Model_Data_User)
     * @return array(int)   lista de ids
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function contactsIds($user) {
        // aceita objeto ou id
        if (is_object($user)) $user_id = $user->id;
        else $user_id = $user;

        // lista todo mundo que o cara é contato
        $select = $this->_dbTable
                ->select()
                ->from('companies', array('companies.id'))
                ->join('users', 'users.company_id = companies.id', null)
                ->join('contacts', 'contacts.user_id = users.id', null)
                ->where('contacts.contact_id = ?', $user_id)
                ->group('companies.id')
                ->order('companies.id')
                ->setIntegrityCheck(false);

        $rows = $this->_dbTable->fetchAll($select);

        $companies_ids = array();

        foreach ($rows as $row) {
            $companies_ids[] = $row->id;
        }

        return $companies_ids;

    }
    
    
    
    /**
     * Retorna lista de ids de empresas que o cara segue
     * 
     * @param $user         (int)id do usuário ou (Ee_Model_Data_User)
     * @return array(int)   lista de ids
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function subscribedsIds($user) {
        // aceita objeto ou id
        if (is_object($user)) $user_id = $user->id;
        else $user_id = $user;

        // procura por todas empresas que o $user segue
        $select = $this->_dbTable
                ->select()
                ->from('companies', array('companies.id'))
                ->join('subscriptions', 'subscriptions.company_id = companies.id', null)
                ->where('subscriptions.user_id = ?', $user_id)
                ->group('companies.id')
                ->order('companies.id')
                ->setIntegrityCheck(false);

        $rows = $this->_dbTable->fetchAll($select);

        $companies_ids = array();

        foreach ($rows as $row) {
            $companies_ids[] = $row->id;
        }

        return $companies_ids;

    }

    
    /**
     * Salva os dados de uma empresa
     * 
     * @param $company         dados para salvar
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function save($company) {
        $save = parent::save($company);
        // se o nome foi alterado
        if (isset($company->name)) {
            // cria novo slug
            $new_slug = $this->slug($company);
            // se o novo slug for diferente do slug antigo
            if (!isset($company->slug) || $new_slug != $company->slug) {
                $company->slug = $new_slug;
                $save = parent::save($company);
            }
        }
        return $save;
    }

    
    /**
     * Altera imagem de uma empresa
     * 
     * @param $company->id          id da empresa para trocar a foto
     * @param $company->image       nome da imagem antiga
     * @param $image                nova imagem
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function imageUpdate($company, $image) {
        $userfile = new Ee_UserFile();
        // salva a imagem na pasta da empresa
        $userfile->setCompanyThumb($company->id, $company->image, $image);
        // dados a serem salvo no banco de dados
        $save_data->id = $company->id;
        $save_data->date_updated = date('Y-m-d H:i:s');
        $save_data->image = $userfile->file;
        // salva
        $this->save($save_data);
        // atualiza objeto
        $company->image = $userfile->file;
    }

    /**
     * Altera imagem do cartão de uma empresa
     * 
     * @param $company->id          id da empresa para trocar a imagem do cartão
     * @param $company->card_image  nome da imagem antiga
     * @param $image                nova imagem
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function cardImageUpdate($company, $image) {
        $userfile = new Ee_UserFile();
        // salva a imagem na pasta da empresa
        $userfile->setCompanyCardImage($company->id, $company->card_image, $image);
        // dados para atualizar db
        $save_data->id = $company->id;
        $save_data->card_image = $userfile->file;
        // salva no db
        $this->save($save_data);
        // atualiza objeto
        $company->card_image = $userfile->file;
    }


    /**
     * Altera imagem do lado de uma empresa
     * 
     * @param $company->id          id da empresa para trocar a imagem
     * @param $company->side_image  nome da imagem antiga
     * @param $image                nova imagem
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function sideImageUpdate($company, $image) {
        $userfile = new Ee_UserFile();
        // salva a imagem na pasta da empresa
        $userfile->setCompanySideImage($company->id, $company->side_image, $image);
        // dados para atualizar db
        $save_data->id = $company->id;
        $save_data->side_image = $userfile->file;
        // salva no db
        $this->save($save_data);
        // atualiza objeto
        $company->side_image = $userfile->file;
    }

    
    /**
     * Conta o número de empresas ativas na rede
     * 
     * @return int              número de empresas ativas na rede
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function count() {
        $select = $this->_dbTable->select()
            ->from($this->_dbTable, array('COUNT(*) as num'))
            ->where('status = "active"');
        return $this->_dbTable->fetchRow($select)->num;
    }

    
    /**
     * Conta o número de empresas de uma cidade ativas na rede
     * 
     * @param $city_id          id da cidade
     * @return int              número de empresas ativas na rede
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function countByCity($city_id) {
        $select = $this->_dbTable->select()
            ->from('companies', array('COUNT(*) as num'))
            ->where('city_id = ?', $city_id)
            ->where('status = "active"');
        return $this->_dbTable->fetchRow($select)->num;
    }

       
    /**
     * Procura empresas
     * 
     * @param int $options['sector_id'] = null  id do setor
     * @param int $options['city_id'] = null    id da cidade
     * @param int $option['region_id'] = null   id do estado
     * @param int $option['page'] = 1           página
     * @param int $option['limit'] = 10         limite por página
     * @param string $option['order']           ordenação
     * @param string $option['search'] = null   string da busca
     * @return array()
     * @return Ee_Model_Data_Sector $directory->sector      dados do setor pesquisado
     * @return Ee_Model_Data_City $directory->city          dados da cidade pesquisada
     * @return array(int) $directory->cities_ids            ids das cidades pesquisadas
     * @return Ee_Model_Data_Region $directory->region      dados da região pesquisada
     * @return int $directory->page                         página atual
     * @return int $directory->pages                        número total de páginas
     * @return int $directory->limit                        número de empresas por página
     * @return int $directory->count                        total do resultado
     * @return string $directory->search                    string procurada
     * @return array(string) $directory->terms              termos procurados
     * @return array(Ee_Model_Data_Company) $directory->companies  empresas
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function directory($options) {
        // seta as opcoes padrões
        $options['sector_id'] = isset($options['sector_id'])? $options['sector_id'] : null;
        $options['city_id'] = isset($options['city_id'])? $options['city_id'] : null;
        $options['region_id'] = isset($options['region_id'])? $options['region_id'] : null;
        $options['page'] = isset($options['page'])? $options['page'] : 1;
        $options['limit'] = isset($options['limit'])? $options['limit'] : 10;
        $options['order'] = isset($options['order'])? $options['order'] : 'IF(ISNULL(image),1,0), date_updated DESC';
        if (isset($options['search']) == false || trim($options['search']) == '' || strlen($options['search']) < 2) $options['search'] = null;

        $directory->sector = null;
        $directory->city = null;
        $directory->cities_ids = array();
        $directory->region = null;
        $directory->page = $options['page'];
        $directory->limit = $options['limit'];
        $directory->search = $options['search'];
        $directory->terms = null;

        // se o cara fez uma busca
        if ($options['search']) {
            // quebra os termos em uma array
            $terms = str_word_count($options['search'], 1, 'ÁÃÊÉÍÔÓÕÚÇáãêéíôóõúç');

            // ordena os termos por ordem decrescente de tamanho
            function sortByLength($a,$b){
                return strlen($b)-strlen($a);
            }
            usort($terms,'sortByLength');

            $directory->terms = $terms;
        }


        $city_mapper = new Ee_Model_Cities();
        $region_mapper = new Ee_Model_Regions();
        $sector_mapper = new Ee_Model_Sectors();

        // lista de empresas
        // obs: essa query não está terminada, ela continua sendo montada 
        $select = $this->_dbTable
                ->select()
                ->from($this->_dbTable, array('id', 'name', 'activity', 'description', 'slug', 'sector_id', 'city_id', 'image'))
                ->where('companies.status = "active"')
                ->limitPage($options['page'], $options['limit'])
                ->order($options['order']);

        // número de resultados encontrados
        // obs: essa query não está terminada, ela continua sendo montada 
        $select_count = $this->_dbTable
                ->select()
                ->from('companies', array('count(*) as count'))
                ->where('companies.status = "active"')
                ->order($options['order']);

        // se escolheu uma cidade
        if ($options['city_id']) {
            $directory->city = $city_mapper->find($options['city_id']);
            $directory->cities_ids[] = $directory->city->id;
            $select->where('city_id = ?', $directory->city->id);
            $select_count->where('city_id = ?', $directory->city->id);
        }
        // se escolheu um estado
        if ($options['region_id']) {
            $directory->region = $region_mapper->find($options['region_id']);
            // se não escolheu uma cidade específica, lista todas cidades do estado
            if ($directory->city == null) {
                $cities_ids = $city_mapper->formArray($directory->region->id, 'id', 'id');
                $directory->cities_ids = $cities_ids;
                $select->where('city_id IN ('.implode(',',$cities_ids).')');
                $select_count->where('city_id IN ('.implode(',',$cities_ids).')');
            }
        }
        // se escolheu um setor
        if ($options['sector_id']) {
            $directory->sector = $sector_mapper->find($options['sector_id']);
            $select->where('sector_id = ?', $directory->sector->id);
            $select_count->where('sector_id = ?', $directory->sector->id);
        }
        // se fez uma busca
        if ($options['search']) {
            // tabela que junta todas as informações da empresa numa coluna só
            $db_company_search = new Ee_Model_DbTable_ViewCompaniesSearch();
            $search_select = $db_company_search
                ->select()
                ->from($db_company_search, array('company_id'));

            // monta a busca com cada termo
            foreach ($directory->terms as $term) {
                $len = strlen($term);
                if ($len > 1) {
                    if ($len > 4 && ($term[$len-1]='S' || $term[$len-1]='s')) $term = substr($term, 0, $len - 1);
                    $search_select->where('company_text LIKE "%'.$term.'%"');
                }
            }

            $search_rows = $this->_dbTable->fetchAll($search_select);

            // se não encontrou nada na busca
            if (count($search_rows) == 0) {
                $directory->page = $options['page'];
                $directory->limit = $options['limit'];
                $directory->count = 0;
                return $directory;
            }

            $companies_ids = array();
            foreach ($search_rows as $row) {
                $companies_ids[] = $row->company_id;
            }
            
            // mostra no diretório apenas as empresas procuradas na busca
            $select->where('id IN ('.implode(',',$companies_ids).')');
            $select_count->where('id IN ('.implode(',',$companies_ids).')');
        }

        $rows = $this->_dbTable->fetchAll($select);

        // se não encontrou nenhuma empresa
        if (count($rows) == 0) {
            $directory->page = $options['page'];
            $directory->limit = $options['limit'];
            $directory->count = 0;
            return $directory;
        }

        $count_row = $this->_dbTable->fetchRow($select_count);
        $directory->count = $count_row->count;

	$business_mapper = new Ee_Model_Businesses ();

        $companies = array();

        foreach ($rows as $row) {
            $company = new Ee_Model_Data_Company($row);

            if ($options['city_id']) {
                $company->city = $directory->city;
            }
            else {
                $company->city = $city_mapper->find($company->city_id);
            }

            if ($options['region_id']) {
                $company->city->region = $directory->region;
            }
            else {
                $company->city->region = $region_mapper->find($company->city->region_id);
            }

            if ($options['sector_id']) {
                $company->sector = $directory->sector;
            }
            else {
                $company->sector = $sector_mapper->find($company->sector_id);
            }

	    $company->businesses = $business_mapper->findByCompany($company);

            // se fez a busca, procura os produtos que sejam relevantes
            if ($options['search']) {
                $company->products = array();
                $product_mapper = new Ee_Model_Products();
                $products = $product_mapper->searchByCompany($directory->terms, $company->id);
                if ($products) {
                    foreach ($products as $product) {
                        $product->company = $company;
                        $company->products[] = $product;
                    }
                }
            }

            $companies[] = $company;
        }

        $directory->pages = ceil($directory->count / $options['limit']);
        $directory->companies = $companies;

        return $directory;
    }

    
    /**
     * Últimas empresas cadastradas ativas com imagem
     * 
     * @param int $num = 10                     número de empresas para mostrar
     * @return array(Ee_Model_Data_Company)     últimas empresas cadastradas
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function last($num = 10) {

        $select = $this->_dbTable
                ->select()
                ->from($this->_dbTable, array('id', 'name', 'slug', 'image'))
                ->where('image IS NOT NULL')
                ->where('status = "active"')
                ->limit($num)
                ->order('date_created desc');

        $rows = $this->_dbTable->fetchAll($select);

        $companies = array();

        foreach ($rows as $row) {
            require_once 'data/Company.php';
            $company = new Ee_Model_Data_Company();
            $company->set($row);
            $companies[] = $company;
        }

        return $companies;
    }

}


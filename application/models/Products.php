<?php

/**
 * Products.php - Ee_Model_Products
 * Mapper de produtos
 * 
 * @package models
 * @author Mauro Ribeiro
 * @since 2011-06
 */
class Ee_Model_Products extends Ee_Model_Mapper
{

    /**
     * Procura os dados de uma empresa
     * 
     * @param $product_id               (int)id ou (string)slug do produto
     * @param $company_id               se o slug for passado, tem que passar o id da empresa também
     * @return Ee_Model_Data_Product    objeto do produto
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function find($product_id, $company_id = null) {

        // procura por id
        if (is_numeric($product_id)) {
            $select = $this->_dbTable->select()
                    ->where('`id` = ?', $product_id)
                    ->order('sort');
        }
        // procura por slug
        else {
            $select = $this->_dbTable->select()
                    ->where('`slug` = ?', $product_id)
                    ->where('`company_id` = ?', $company_id)
                    ->order('sort');
        }
        $rows = $this->_dbTable->fetchAll($select);

        // se não achou nada        
        if (0 == count($rows)) {
            return;
        }

        $product = new Ee_Model_Data_Product();
        $product->set($rows[0]);

        // procura pela empresa
        $company_mapper = new Ee_Model_Companies();
        $product->company = $company_mapper->find($product->company_id);

        return $product;
    }

    /**
     * Salva os dados de um produto
     * 
     * @param object $product           dados do produto
     * @return Ee_Model_Data_Product    objeto do produto
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function save($product) {
        $save = parent::save($product);
        if (isset($product->name)) {
            $new_slug = $this->slug($product);
            if (!isset($product->slug) || $new_slug != $product->slug) {
                $product->slug = $new_slug;
                $save = parent::save($product);
            }
        }
        return $save;
    }
    
    /**
     * Procura produto aleatória no banco de dados
     * 
     * @return false|Ee_Model_Data_Product    objeto do produto encontrado
     * @author Lucas Gaspar
     * @since 2012-03
     */
    public function random() {

        $select = $this->_dbTable
                ->select()
                ->where('image IS NOT NULL')
                ->limit(1)
                ->order('RAND()');

        $rows = $this->_dbTable->fetchAll($select);
        
        // se não encontrou
        if (0 == count($rows)) {
            return false;
        }

        $product = new Ee_Model_Data_Product($rows->current());
        
        // procura pela empresa
        $company_mapper = new Ee_Model_Companies();
        $product->company = $company_mapper->find($product->company_id);

        return $product;
    }

    /**
     * Lista produtos de uma empresa
     * 
     * @param Ee_Model_Data_Company $company     empresa que você quer consultar
     * @return array(Ee_Model_Data_Product)      produtos da empresa
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function findByCompany($company) {
        $select = $this->_dbTable->select()
                ->where('`company_id` = ?', $company->id)
                ->order('sort');

        $rows = $this->_dbTable->fetchAll($select);

        // se não achou nada
        if (0 == count($rows)) {
            return;
        }

        $products = array();

        foreach ($rows as $row) {
            $product = new Ee_Model_Data_Product();
            $product->set($row);

            $product->company = $company;

            $products[] = $product;
        }

        return $products;

    }

    /**
     * Lista os 4 produtos ordenados
     * 
     * @param Ee_Model_Data_Company $company    empresa que você quer consultar
     * @return array(Ee_Model_Data_Product)     produtos da empresa
     * @return null                             se não achou nada
     * @author Mauro Ribeiro
     * @since 2011-07
     */
    public function findTopByCompany($company) {
        $select = $this->_dbTable->select()
                ->where('`company_id` = ?', $company->id)
                ->order('sort')
                ->limit(4);

        $rows = $this->_dbTable->fetchAll($select);

        // se não encontrou nada
        if (0 == count($rows)) {
            return;
        }

        $products = array();

        foreach ($rows as $row) {
            $product = new Ee_Model_Data_Product();
            $product->set($row);

            $product->company = $company;

            $products[] = $product;
        }

        return $products;

    }


    /**
     * Produtos mais recentes e com foto das empresas que o usuário tem contato
     * 
     * @param int $user_id                  id do usuário
     * @param int $option['limit']          opcional - número máximo de resultados
     * @param int $option['companies_ids']  opcional - empresas a serem consultadas
     * @return array(Ee_Model_Data_Product) lista de produtos
     * @return null                         caso não encontrou nada
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function findBySubscriber($user_id, $options = null) {
        $company_mapper = new Ee_Model_Companies();

        $limit = isset($options['limit']) ? $options['limit'] : 5;

        if (isset($options['companies_ids'])) {
            $companies_ids = $options['companies_ids'];
        }
        else {
            $companies_ids = $company_mapper->contactsIds($user);
        }

        if (!$companies_ids) return;

        $select = $this->_dbTable->select()
                ->from($this->_dbTable)
                ->where('company_id IN ('.implode(',', $companies_ids).')')
                ->where('image IS NOT NULL')
                ->where('image != ""')
                ->order('offer_date_created DESC')
                ->limit($limit);

        $rows = $this->_dbTable->fetchAll($select);

        // caso não achou nada
        if (0 == count($rows)) {
            return;
        }

        $company_mapper = new Ee_Model_Companies();
        $products = array();

        foreach ($rows as $row) {
            $product = new Ee_Model_Data_Product();
            $product->set($row);
            // procura a empresa
            $product->company = $company_mapper->find($product->company_id);
            $products[] = $product;
        }

        return $products;
        
    }


    /**
     * Produtos mais recentes e com foto das empresas que o usuário tem contato
     * 
     * @param int $user_id                  id do usuário
     * @param int $option['limit']          opcional - número máximo de resultados
     * @param int $option['companies_ids']  opcional - empresas a serem consultadas
     * @return array(Ee_Model_Data_Product) lista de produtos
     * @return null                         caso não encontrou nada
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function lastOffers($num = 10) {

        $select = $this->_dbTable->select()
                ->from($this->_dbTable)
                ->where('offer_status = "active"')
                ->where('offer_date_deadline >= NOW()')
                ->where('image IS NOT NULL')
                ->where('image != ""')
                ->order('offer_date_created DESC')
                ->limit($num);

        $rows = $this->_dbTable->fetchAll($select);

        if (0 == count($rows)) {
            return;
        }

        $company_mapper = new Ee_Model_Companies();
        $products = array();

        foreach ($rows as $row) {
            $product = new Ee_Model_Data_Product();
            $product->set($row);
            $product->company = $company_mapper->find($product->company_id);
            $products[] = $product;
        }

        return $products;

    }
    

    /**
     * Ofertas em destaque (de empresas premium e mais recentes)
     * 
     * @return array(Ee_Model_Data_Product) lista de produtos
     * @return null                         caso não encontrou nada
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function featuredOffers() {
        $select = $this->_dbTable->select()
                ->from($this->_dbTable)
                ->join('companies', 'companies.id = products.company_id', null)
                ->where('products.`offer_status` = "active"')       // está ativo
                ->where('products.`offer_date_deadline` >= NOW()')  // dentro do prazo
                ->where('companies.`plan` = "premium"')             // empresas premium
                ->where('companies.`plan_expiration` >= NOW()')
                ->order('offer_date_created DESC')                  // ordena por mais recente
                ->limit(9)
                ->setIntegrityCheck(false);

        $rows = $this->_dbTable->fetchAll($select);

        // caso não encontrou nada
        if (0 == count($rows)) {
            return;
        }

        $products = array();

        foreach ($rows as $row) {
            $product = new Ee_Model_Data_Product();
            $product->set($row);
            
            // procura empresa
            $company_mapper = new Ee_Model_Companies();
            $product->company = $company_mapper->find($product->company_id);

            $products[] = $product;
        }

        return $products;
    }

    /**
     * Mural de ofertas
     * 
     * @param int $options['sector_id'] = null      filtrar por id do setor
     * @param int $options['city_id'] = null        filtrar por id da cidade
     * @param int $options['region_id'] = null      filtrar por id do estado
     * @param int $options['page'] = 1              página a ser exibida     
     * @param int $options['limit'] = 10            resultados por página
     * @param string $options['order']              critério de ordenação
     * @return array(Ee_Model_Data_Product)         lista de ofertas
     * @return null                                 caso não encontrou nada
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function offers($options) {
        $options['sector_id'] = isset($options['sector_id'])? $options['sector_id'] : null;
        $options['city_id'] = isset($options['city_id'])? $options['city_id'] : null;
        $options['region_id'] = isset($options['region_id'])? $options['region_id'] : null;
        $options['page'] = isset($options['page'])? $options['page'] : 1;
        $options['limit'] = isset($options['limit'])? $options['limit'] : 10;
        $options['order'] = isset($options['order'])? $options['order'] : 'IF(companies.plan = "premium",0,1), offer_date_created DESC';

        $offers->sector = null;
        $offers->city = null;
        $offers->cities_ids = array();
        $offers->region = null;
        $offers->page = $options['page'];
        $offers->limit = $options['limit'];

        $city_mapper = new Ee_Model_Cities();
        $region_mapper = new Ee_Model_Regions();
        $sector_mapper = new Ee_Model_Sectors();
        
        // query que será usada para procurar as ofertas
        $select = $this->_dbTable
                ->select()
                ->from('products', array('id', 'name', 'company_id', 'slug', 'image', 'description', 'offer_description', 'offer_date_created', 'offer_date_deadline'))
                ->join('companies', 'companies.id = products.company_id', array('id as company__id', 'name as company__nane', 'slug as company__slug', 'sector_id as company__sector_id', 'city_id as company__city_id', 'plan as company__plan', 'plan_expiration as company__plan_expiration'))
                ->where('companies.status = "active"')
                ->where('products.offer_status = ?', 'active')
                ->where('products.offer_date_deadline >= NOW()')
                ->limitPage($options['page'], $options['limit'])
                ->order($options['order'])
                ->setIntegrityCheck(false);

        // query que será usada para contar os resultados
        $select_count = $this->_dbTable
                ->select()
                ->from('products', array('count(*) as count'))
                ->join('companies', 'companies.id = products.company_id', null)
                ->where('companies.status = "active"')
                ->where('products.offer_status = ?', 'active')
                ->where('products.offer_date_deadline >= NOW()')
                ->setIntegrityCheck(false);

        // filtro por cidade
        if ($options['city_id']) {
            $offers->city = $city_mapper->find($options['city_id']);
            $offers->cities_ids[] = $offers->city->id;
            $select->where('companies.city_id = ?', $offers->city->id);
            $select_count->where('companies.city_id = ?', $offers->city->id);
        }
        // filtro por estado
        if ($options['region_id']) {
            $offers->region = $region_mapper->find($options['region_id']);
            if ($offers->city == null) {
                $cities_ids = $city_mapper->formArray($offers->region->id, 'id', 'id');
                $offers->cities_ids = $cities_ids;
                $select->where('companies.city_id IN ('.implode(',',$cities_ids).')');
                $select_count->where('companies.city_id IN ('.implode(',',$cities_ids).')');
            }
        }
        // filtro por setor
        if ($options['sector_id']) {
            $offers->sector = $sector_mapper->find($options['sector_id']);
            $select->where('companies.sector_id = ?', $offers->sector->id);
            $select_count->where('companies.sector_id = ?', $offers->sector->id);
        }

        $rows = $this->_dbTable->fetchAll($select);

        // se não achou nada
        if (count($rows) == 0) {
            $offers->page = $options['page'];
            $offers->limit = $options['limit'];
            $offers->count = 0;
        }

        $count_row = $this->_dbTable->fetchRow($select_count);
        $offers->count = $count_row->count;

        $products = array();

        foreach ($rows as $row) {
            $p_row = null;
            $c_row = null;

            // pega os valores da empresa da query executada pelo prefixo "company__"
            foreach($row as $id => $value) {
                $explode = explode('__',$id);
                if ($explode[0] == 'company') $c_row->$explode[1] = $value;
                else $p_row->$id = $value;
            }

            $product = new Ee_Model_Data_Product($p_row);
            $product->company = new Ee_Model_Data_Company($c_row);

            if ($options['city_id']) {
                $product->company->city = $offers->city;
            }
            else {
                $product->company->city = $city_mapper->find($product->company->city_id);
            }

            if ($options['region_id']) {
                $product->company->city->region = $offers->region;
            }
            else {
                $product->company->city->region = $region_mapper->find($product->company->city->region_id);
            }

            if ($options['sector_id']) {
                $product->company->sector = $offers->sector;
            }
            else {
                $product->company->sector = $sector_mapper->find($product->company->sector_id);
            }

            $products[] = $product;
        }

        $offers->pages = ceil($offers->count / $options['limit']);
        $offers->products = $products;

        return $offers;

    }


    /**
     * Busca uma oferta
     * 
     * @param string $query                 busca do usuário
     * @return array(Ee_Model_Data_Product) lista de produtos
     * @return null                         caso não encontrou nada
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function search($query, $options = null) {
        $terms = str_word_count($query, 1);

        $select = $this->_dbTable->select();

        foreach ($terms as $term) {
            if (strlen($term) > 1)
                $select->where('name LIKE "%'.$term.'%" OR description LIKE "%'.$term.'%"');
        }

        $rows = $this->_dbTable->fetchAll($select);

        // se não achou nada
        if (0 == count($rows)) {
            return;
        }

        $products = array();

        foreach ($rows as $row) {
            $product = new Ee_Model_Data_Product();
            $product->set($row);

            $products[] = $product;
        }

        return $products;
    }

    /**
     * Buscar produtos dentro de uma empresa
     * 
     * @param array(string) $terms          termos buscados
     * @param int $company_id               id da empresa
     * @return array(Ee_Model_Data_Product) lista de produtos
     * @return null                         caso não encontrou nada
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function searchByCompany($terms, $company_id, $options = null) {
        $products = false;
    
        if (count($terms) > 0) {

            $select = $this->_dbTable->select();

            $where = '';
            // procura os termos no nome ou na descrição
            foreach ($terms as $term) {
                if (strlen($term) > 1)
                    $where .= 'OR LOCATE("'.$term.'", name) > 0 OR LOCATE("'.$term.'", description)';
            }
            // para arrancar o primeiro "OR " fora
            $where = substr($where, 3);

            $select->where($where);
            // dentro de uma empresa
            $select->where('company_id = ?', $company_id);

            $rows = $this->_dbTable->fetchAll($select);

            if (0 == count($rows)) {
                return;
            }

            $products = array();

            foreach ($rows as $row) {
                $product = new Ee_Model_Data_Product();
                $product->set($row);

                $products[] = $product;
            }
        }

        return $products;
    }


    /**
     * Atualiza imagem de um produto
     * 
     * @param Ee_Model_Data_Product $product    produto a ser atualizado
     * @param $image                            nova imagem
     * @param int $index = false                qual índice atualizar         
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function imageUpdate($product, $image, $index = false) {
        
        $userfile = new Ee_UserFile();
        if (!isset($product->image)) $product->image = null;
        // para imagem de destaque do produto
        if (!$index) {
            $userfile->setProductThumb($product->company->id, $product->id, $product->image, $image);
            $save_data->id = $product->id;
            $save_data->image = $userfile->file;
            $save_data->date_updated = date('Y-m-d H:i:s');
            $product->image = $userfile->file;
        }
        // para galeria de imagens
        else {
            $index_name = 'image_'.$index;
            $userfile->setProductImage($product->company->id, $product->id, $product->$index_name, $image);
            $save_data->id = $product->id;
            $index_name = 'image_'.$index;
            $save_data->$index_name = $userfile->file;
            $product->$index_name = $userfile->file;
        }
        $this->save($save_data);
    }

}


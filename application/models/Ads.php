<?php
/**
 * Ads.php - Ee_Model_Ads
 * Mapper de anúncios
 * 
 * @package models
 * @author Mauro Ribeiro
 * @since 2011-06
 */
class Ee_Model_Ads extends Ee_Model_Mapper
{

    /**
     * Lista anúncios de um setor específico ou cidade.
     * 
     * @param int $sector_id             id do setor da empresa do usuário
     * @param int $city_id               id da cidade do usuário
     * @param int $limit                 número de anúncios a serem listados
     * @return array(Ee_Model_Data_Ad)   lista de anúncios
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function advertises($sector_id, $city_id, $limit = 4) {

        $select = $this->_dbTable
                ->select()
                ->from('ads', array('id', 'product_id'))
                ->join('ads_sectors', 'ads.id = ads_sectors.ad_id', null)
                ->join('ads_cities', 'ads.id = ads_cities.ad_id', null)
                ->where('ads_sectors.sector_id = ?', $sector_id)
                ->where('ads_cities.city_id = ?', $city_id)
                ->where('ads.status = "active"')
                ->where('ads.date_deadline >= NOW()')
                ->group('ads.id')
                ->order('RAND()')
                ->limit($limit)
                ->setIntegrityCheck(false);
        
        // se setor ou cidade não estiver setado, mostra apenas anúncios para o
        // público deslogado ou ambos
        if ($sector_id == 0 & $city_id == 0) {
            $select->where('ads.public = "all" OR ads.public = "visitors"');
        }
        
        $rows = $this->_dbTable->fetchAll($select);
        $ads = array();

        foreach ($rows as $row) {

            $ad = new Ee_Model_Data_Ad();
            $ad->set($row);

            // procura produto do anúncio
            $product_mapper = new Ee_Model_Products();
            $ad->product = $product_mapper->find($ad->product_id);
            
            // procura empresa do produto
            $company_mapper = new Ee_Model_Companies();
            $ad->product->company = $company_mapper->find($ad->product->company->id);

            $ads[] = $ad;

            // atualização das impressões
            $data = array('views' => new Zend_Db_Expr('views + 1'));
            
            // atualiza a tabela de impressões do anúncio por setor
            $ad_sector_table = new Ee_Model_DbTable_AdsSectors();
            $ad_sector_table->update(
                    $data,
                    array(
                        'ad_id = ?' => $ad->id,
                        'sector_id = ?' => $sector_id
                    )
                );

            // atualiza a tabela de impressoes do anúncio por cidade
            $ad_city_table = new Ee_Model_DbTable_AdsCities();
            $ad_city_table->update(
                    $data,
                    array(
                        'ad_id = ?' => $ad->id,
                        'city_id = ?' => $city_id
                    )
                );
        }

        return $ads;
    }


    /**
     * Incrementa o número de cliques no anúncio no banco de dados
     * 
     * @param $ad               (Ee_Model_Data_Ad)anúncio ou (int)id do anúncio
     * @param int $sector_id    id do setor da empresa do usuário
     * @param int $city_id      id da cidade do usuário
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function addClickCount($ad, $sector_id, $city_id) {
        // aceita o objeto ou o id
        if (is_object($ad)) $ad_id = $ad->id;
        else $ad_id = $ad;

        // incrementa
        $data = array('clicks' => new Zend_Db_Expr('clicks + 1'));

        // atualiza a tabela de impressões do anúncio por setor
        $ad_sector_table = new Ee_Model_DbTable_AdsSectors();
        $ad_sector_table->update(
                $data,
                array(
                    'ad_id = ?' => $ad_id,
                    'sector_id = ?' => $sector_id
                )
            );

        // atualiza a tabela de impressões do anúncio por cidade
        $ad_city_table = new Ee_Model_DbTable_AdsCities();
        $ad_city_table->update(
                $data,
                array(
                    'ad_id = ?' => $ad_id,
                    'city_id = ?' => $city_id
                )
            );
    }

    /**
     * Cria um novo anúncio
     * 
     * @param int $ad->product_id           id do produto
     * @param date $ad->date_deadline       data do prazo
     * @param array(int) $ad->sectors       ids dos setores
     * @param array(int) $ad->cities        ids das cidades
     * @author Mauro Ribeiro
     * @since 2011-07
     */
    public function create($ad) {
        // passa os valores de $ad para $ad_data
        $ad_data->product_id = $ad->product_id;
        $ad_data->status = 'inactive';
        $ad_data->date_created = date('Y-m-d H:i:s');
        // coloca 5 dias a mais de bonus
        $ad_data->date_deadline = date('Y-m-d', strtotime(date("Y-m-d", strtotime($ad->date_deadline)) . " +5 days"));

        // salva o anúncio
        $this->save($ad_data);
        $ad->id = $ad_data->id;

        // adiciona uma linha por setor na tabela AdsSectors
        $ad_sector_table = new Ee_Model_DbTable_AdsSectors();
        foreach ($ad->sectors as $sector_id) {
            $ad_sector['ad_id'] = $ad->id;
            $ad_sector['sector_id'] = $sector_id;
            $ad_sector_table->insert($ad_sector);
        }

        // adiciona uma linha por cidade na tabela AdsCities
        $ad_city_table = new Ee_Model_DbTable_AdsCities();
        foreach ($ad->cities as $city_id) {
            $ad_city['ad_id'] = $ad->id;
            $ad_city['city_id'] = $city_id;
            $ad_city_table->insert($ad_city);
        }

    }

}


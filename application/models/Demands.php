<?php

/**
 * Demands.php - Ee_Model_Demands
 * Mapper das requisições de serviços
 * 
 * @package models
 * @author Mauro Ribeiro
 * @since 2011-07
 */

class Ee_Model_Demands extends Ee_Model_Mapper
{
    
    /**
     * Salva um serviço 
     * 
     * @param $demand           dados do serviço
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function save($demand) {
        $description = $demand->description;
        // quebra a descricao em varios campos varchar(255) do mysql
        $demand->description = substr($description, 0, 254);
        $demand->description_2 = substr($description, 255, 509);
        $demand->description_3 = substr($description, 510, 764);
        $demand->description_4 = substr($description, 765, 1019);
        // por padrao espera autorização de um administrador
        $demand->status = 'inactive';
        $demand->date_created = date('Y-m-d H:i:s');
        
        $save = parent::save($demand);
        $new_slug = $this->slug($demand, 'title');
        $demand->slug = $new_slug;
        $save = parent::save($demand);
        
        // retorna o objeto sem a quebra do description
        $demand->description = $description;
        unset($demand->description_2);
        unset($demand->description_3);
        unset($demand->description_4);

        return $save;
    }
    
    /**
     * Procura um serviço
     * 
     * @param string $demand_id           slug do serviço
     * @return Ee_Model_Data_Demand       serviço procurado
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function find($demand_id) {
        $select = $this->_dbTable
                ->select()
                ->where('status = "active"')
                ->where('date_deadline > NOW()')    // dentro do prazo
                // retirado por causa do Mural de Serviços para não cadastrados no Empreendemia
                //->where('user_id IS NOT NULL')     // tem que ter responsável
                ->where('slug = ?', $demand_id);
                
        $rows = $this->_dbTable->fetchAll($select);
        $row = $rows[0];

        // concatena todos os campos varchar(255)
        $row->description = $row->description.$row->description_2.$row->description_3.$row->description_4;
        unset($row->description_2);
        unset($row->description_3);
        unset($row->description_4);
        $demand = new Ee_Model_Data_Demand($row);

        $user_mapper = new Ee_Model_Users();
        $sector_mapper = new Ee_Model_Sectors();
        $user = $user_mapper->find($row->user_id);
        $sector = $sector_mapper->find($row->sector_id);
        
        if($user != NULL)
            $demand->user = $user;
            $demand->sector = $sector;

        return $demand;

    }
    
    /**
     * Encontra todos os serviços ativos
     * 
     * @return array(Ee_Model_Data_Demand)  Lista de serviços
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function findActive() {
        $select = $this->_dbTable
                ->select()
                ->where('status = "active"')
                ->where('date_deadline > NOW()')    // dentro do prazo
                // retirado por causa do Mural de Serviços para não cadastrados no Empreendemia
                //->where('user_id IS NOT NULL')     // tem que ter responsável
                ->order('date_created DESC');       // mais recente primeiro

        $rows = $this->_dbTable->fetchAll($select);

        $demands = array();
        $user_mapper = new Ee_Model_Users();
        $sector_mapper = new Ee_Model_Sectors();
        
        foreach ($rows as $row) {
            // concatena os campos varchar(255)
            $row->description = $row->description.$row->description_2.$row->description_3.$row->description_4;
            unset($row->description_2);
            unset($row->description_3);
            unset($row->description_4);
            $demand = new Ee_Model_Data_Demand($row);
            // autor da requisição de serviço
            $user = $user_mapper->find($row->user_id);
            // setor do serviço pedido
            if ($user != NULL){
                $sector = $sector_mapper->find($row->sector_id);
                $demand->user = $user;
                $demand->sector = $sector;
            }
            $demands[] = $demand;
        }

        return $demands;

    }

}


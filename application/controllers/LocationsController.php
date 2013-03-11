<?php
/**
 * LocationsController.php - LocationsController
 * Controlador de locais (cidades, estados, países)
 * 
 * @package controllers
 * @author Mauro Ribeiro
 * @since 2011-07-20
 */
class LocationsController extends Zend_Controller_Action
{
    /**
     * Método chamado antes de qualquer e toda action
     */
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
                // action body
    }

    /**
     * Seletor de cidades
     */
    public function citiesSelectAction()
    {
                $region_id = isset($_POST['region_id']) ? $_POST['region_id'] : null;
        
        if ($region_id == null) {
            $this->_helper->Access->error('É preciso escolher um estado', 400);
        }
        
        $city_mapper = new Ee_Model_Cities();

        if (is_numeric($region_id)) $id = 'id';
        else $id = 'slug';
        
        $cities = $city_mapper->formArray($region_id, $id);
        $this->view->cities = $cities;
        $this->view->region_id = $region_id;
    }


}

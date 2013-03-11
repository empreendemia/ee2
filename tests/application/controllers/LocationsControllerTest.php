<?php
/**
 * LocationsControllerTest.php - LocationsControllerTest
 * Testes do Locations Controller
 * 
 * @package tests
 * @subpackage controllers
 * @author Mauro Ribeiro
 * @since 2012-03-08
 */

require_once APPLICATION_PATH.'/../tests/application/ControllerTestCase.php';

class LocationsControllerTest extends ControllerTestCase
{
    /*
     * -------------------------------------------------------------------------
     * Teste do citiesSelectAction()
     * -------------------------------------------------------------------------
     */
    
    /**
     * Teste de lista de cidades do estado de SP passando region_id
     * @author Mauro Ribeiro
     * @since 2012-03-08
     */
    public function testCitiesSelectFromSpId() {
        // envia region_id do estado de sao paulo
        $this->request->setMethod('POST')
                ->setPost(array(
                    'region_id'=> 26
                ));
        $this->dispatch('/locations/cities-select');
        // id da cidade de campinas
        $this->assertQuery('option[value="8781"]');
        // id da cidade de sao paulo
        $this->assertQuery('option[value="9422"]');
    }
    
    /**
     * Teste de lista de cidades do estado de SP passando region_slug
     * @author Mauro Ribeiro
     * @since 2012-03-08
     */
    public function testCitiesSelectFromSpSlug() {
        // envia region_id do estado de sao paulo
        $this->request->setMethod('POST')
                ->setPost(array(
                    'region_id'=> 'sao-paulo'
                ));
        $this->dispatch('/locations/cities-select');
        // id da cidade de campinas
        $this->assertQuery('option[value="campinas-sp"]');
        // id da cidade de sao paulo
        $this->assertQuery('option[value="sao-paulo-sp"]');
    }
    
    /**
     * Teste de lista de cidades sem escolher estado nenhum
     * @author Mauro Ribeiro
     * @since 2012-03-08
     */
    public function testCitiesSelectWithoutRegionError() {
        $this->setExpectedException('Zend_Controller_Action_Exception');
        $this->dispatch('/locations/cities-select');
        //$this->assertResponseCode(400);
    }
    

    
}
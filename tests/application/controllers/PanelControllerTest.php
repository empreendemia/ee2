<?php
/**
 * PanelControllerTest.php - PanelControllerTest
 * Testes do Panel Controller
 * 
 * @package tests
 * @subpackage controllers
 * @author Lucas Gaspar
 * @since 2012-03-14
 */
require_once APPLICATION_PATH.'/../tests/application/ControllerTestCase.php';

class PanelControllerTest extends ControllerTestCase
{
    /*
     * -------------------------------------------------------------------------
     * Teste do userInviteAction()
     * -------------------------------------------------------------------------
     */
    
    /**
     * Verifica um convite novo é enviado
     * @author Lucas Gaspar
     * @since 2012-03-12
     */
    public function testSendtInvite() {
        // pega um usuário qualquer na Empreendemia para logar
        $user_mapper = new Ee_Model_Users();
        $user = $user_mapper->random();
        // prepara o login
        $this->request->setMethod('POST')
            ->setPost(array(
                'login'=>$user->login,
                'password'=>'testando'
            ));
        // submete o login
        $this->dispatch('/login');
        // com o usuário logado, preenche formulario de convite e envia
        $invited = 'inv_'.date('His');
        $this->request->setMethod('POST')
            ->setPost(array(
                'name_0'=> $invited,
                'email_0'=>'testes'.'+'.$invited.'@empreendemia.com.br'
            ));
        $this->dispatch('/painel/usuario/convidar');
        $this->assertRedirect();
    }
    
    /**
     * Testa se o convidado preencheu corretamente o formulário
     * @author Lucas Gaspar
     * @since 2012-03-12
     */
    /*
    public function testInvalidInviteForm(){
        // pega um usuário qualquer na Empreendemia para logar
        $user_mapper = new Ee_Model_Users();
        $user = $user_mapper->random();
        // prepara o login
        $this->request->setMethod('POST')
            ->setPost(array(
                'login'=>$user->login,
                'password'=>'testando'
            ));
        // submete o login
        $this->dispatch('/login');
        // com o usuário logado, preenche formulario incorreto de convite e envia
        $invited = 'inv_'.date('His');
        $this->request->setMethod('POST')
            ->setPost(array(
                'name_0'=> $invited,
                'email_0'=>'ThisWillNeverBeAnEmail'
            ));
        $this->dispatch('/painel/usuario/convidar');
        //$this->assertQuery('option[value="campinas-sp"]');
        $this->assertQuery('.content .invite_error_list');
    }
    */
}
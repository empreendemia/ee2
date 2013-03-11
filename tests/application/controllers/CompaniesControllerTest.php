<?php
/**
 * CompaniesControllerTest.php - CompaniesControllerTest
 * Testes do Companies Controller
 * 
 * @package tests
 * @subpackage controllers
 * @author Mauro Ribeiro
 * @since 2012-03-08
 */

require_once APPLICATION_PATH.'/../tests/application/ControllerTestCase.php';

class CompaniesControllerTest extends ControllerTestCase
{
    /*
     * -------------------------------------------------------------------------
     * Teste do viewAction()
     * -------------------------------------------------------------------------
     */
    
    /**
     * Teste de visualizar perfil de uma empresa
     * @author Mauro Ribeiro
     * @since 2012-03-08
     */
    public function testViewCompanyProfile() {
        // escolhe uma empresa aleatória
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->random();
        $this->dispatch('/e/'.$company->slug);
        $this->assertResponseCode(200);
    }
    
    /**
     * Teste de visualizar empresa que não existe
     * @author Mauro Ribeiro
     * @since 2012-03-08
     */
    public function testViewCompanyProfileThatDoesNotExist() {
        // escolhe uma empresa aleatória
        $slug = sha1(date('Y-m-d H:i:s'));
        // tem que retornar um erro
        $this->setExpectedException('Zend_Controller_Action_Exception');
        $this->dispatch('/e/'.$slug);
    }

    /*
     * -------------------------------------------------------------------------
     * Teste do businessesAction()
     * -------------------------------------------------------------------------
     */
    
    /**
     * Teste de rota da página de avaliações de uma empresa 
     * @author Lucas Gaspar
     * @since 2012-03-16
     */
    public function testBusinessesRoute() {
        // escolhe uma empresa aleatória
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->random();
        // acessa página de avaliações
        $this->dispatch('/e/'.$company->slug.'/avaliacoes');
        $this->assertResponseCode(200);
    }
    
    /*
     * -------------------------------------------------------------------------
     * Teste do membersAction()
     * -------------------------------------------------------------------------
     */
    
    /**
     * Testa a página de um membro de uma empresa aleatória
     * @author Lucas Gaspar
     * @since 2012-03-16
     */
    public function testViewOfRandomMember() {
        // escolhe uma empresa aleatória
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->random();
        // escolhe um usuário aleatório da empresa
        $user_mapper = new Ee_Model_Users();
        $users = $user_mapper->findByCompany($company);
        $user = $users[0];
        // acessa perfil do usuário
        $this->dispatch('/e/'.$company->slug.'/pessoas/'.$user->id);
        $this->assertResponseCode(200);
    }
    
    /**
     * Testa rota da página dos membros de uma empresa aleatória
     * @author Lucas Gaspar
     * @since 2012-03-16
     */
    public function testMembersRoute() {
        // escolhe uma empresa aleatória
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->random();
        // acessa página de membros da empresa (pessoas)
        $this->dispatch('/e/'.$company->slug.'/pessoas');
        $this->assertResponseCode(200);
    }
    
    /*
     * -------------------------------------------------------------------------
     * Teste do productsAction()
     * -------------------------------------------------------------------------
     */
    
    /**
     * Testa a página de produtos de uma empresa aleatória
     * @author Lucas Gaspar
     * @since 2012-03-16
     */
    public function testViewOfRandomProduct() {
        // escolhe um produto aleatória
        $product_mapper = new Ee_Model_Products();
        $product = $product_mapper->random();
        // acessa perfil do produto
        $this->dispatch('/e/'.$product->company->slug.'/produtos/'.$product->slug);
        $this->assertResponseCode(200);
    }
    
    /**
     * Testa rota da página produtos de uma empresa aleatória
     * @author Lucas Gaspar
     * @since 2012-03-16
     */
    public function testProductsRoute() {
        // escolhe uma empresa aleatória
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->random();
        // acessa página de membros da empresa (pessoas)
        $this->dispatch('/e/'.$company->slug.'/produtos');
        $this->assertResponseCode(200);
    }
    
    /*
     * -------------------------------------------------------------------------
     * Teste do listAction()
     * -------------------------------------------------------------------------
     */
    
    /**
     * Testa a página com a lista de empresas
     * @author Lucas Gaspar
     * @since 2012-03-19
     */
    public function testCompaniesListRoute() {
        // testa a página da lista de empresas
        $this->dispatch('/lista-de-empresas');
        $this->assertResponseCode(200);
    }
    
    /**
     * Testa uma filtragem de empresas por 3 cidades grandes
     * @author Lucas Gaspar
     * @since 2012-03-19
     */
    public function testFilterByBigCity() {
        // testa filtrando por São Paulo/SP
        $this->dispatch('/lista-de-empresas/sao-paulo/sao-paulo-sp');
        $this->assertResponseCode(200);
        // testa filtrando por Campinas/SP
        $this->dispatch('/lista-de-empresas/sao-paulo/campinas-sp');
        $this->assertResponseCode(200);
        // testa filtrando por Curitiba/PR
        $this->dispatch('/lista-de-empresas/parana/curitiba');
        $this->assertResponseCode(200);
    }
    
    /*
     * -------------------------------------------------------------------------
     * Teste do imageUpdateAction()
     * -------------------------------------------------------------------------
     */
    
    /**
     * Testa o upload de uma imagem
     * @author Lucas Gaspar
     * @since 2012-03-19
     */
    /*
    public function testImageUpdate() {
        // entra como um usuário aleatório
        $user_mapper = new Ee_Model_Users();
        $user = $user_mapper->random();
        $this->request->setMethod('POST')
                ->setPost(array(
                    'login'=>$user->login,
                    'password'=>'testando'
                ));
        $this->dispatch('/login');
    }
    */
    
    /*
     * -------------------------------------------------------------------------
     * Teste do budgetAction()
     * -------------------------------------------------------------------------
     */
    
    /**
     * Testa o envio de um pedido de orçamento
     * @author Lucas Gaspar
     * @since 2012-03-19
     */
    public function testValidBudget() {
        // escolhe uma empresa aleatória
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->random();
        // preenche as informações do orçamento
        $this->request->setMethod('POST')
                ->setPost(array(
                    'name'=>'João Testando',
                    'email'=>'teste@teste.com.br',
                    'company_name'=>'Teste S/A',
                    'message'=>'Isto é só um teste, cara. Não se empolgue.'
                ));
        $this->dispatch('/e/'.$company->slug.'/pedir-orcamento');
        $this->assertResponseCode(200);
    }
    
    /**
     * Testa o envio de um pedido de orçamento inválido
     * @author Lucas Gaspar
     * @since 2012-03-19
     */
    public function testInvalidBudget() {
        // escolhe uma empresa aleatória
        $company_mapper = new Ee_Model_Companies();
        $company = $company_mapper->random();
        // preenche as informações do orçamento
        $this->request->setMethod('POST')
                ->setPost(array(
                    'company_name'=>'Teste S/A',
                    'message'=>'Isto é só um teste, cara. Não se empolgue.'
                ));
        $this->dispatch('/e/'.$company->slug.'/pedir-orcamento');
        $this->assertResponseCode(200);
    }
    
    /**
     * Testa o envio de um pedido de orçamento para empresa inexistente
     * @author Lucas Gaspar
     * @since 2012-03-19
     */
    public function testInexistentCompanyBudget() {
        // prepara pra receber erro de preenchimento de formulário
        $this->setExpectedException('Zend_Controller_Action_Exception');
        $this->dispatch('/e/'.'NãoExisteeee'.'/pedir-orcamento');
    }
    
    /*  Daqui para baixo, falta implementar e/ou ajustar */
    
    /*
     * -------------------------------------------------------------------------
     * Teste do rateAction()
     * -------------------------------------------------------------------------
     */
    
    /*
     * Testa se falha ao avaliar a minha própria empresa
     * @author Lucas Gaspar
     * @since 2012-03-19
     */
    /*
    public function testRateMyCompany() {
        // pega um usuário qualquer na Empreendemia
        $user_mapper = new Ee_Model_Users();
        $user = $user_mapper->random();
        // submete o login
        $this->request->setMethod('POST')
                ->setPost(array(
                    'login'=>$user->login,
                    'password'=>'testando'
                ));
        $this->dispatch('/login');
        $my_company = $user->company_id;
        $this->dispatch('/e/'.$my_company.'/avaliar');
        $this->assertResponseCode(403);
    }
    */
    /*
     * Testa uma avaliação positiva a uma empresa
     * @author Lucas Gaspar
     * @since 2012-03-20
     */
    public function testGoodRate(){
        
    }
    
    /*
     * Testa uma avaliação negativa a uma empresa
     * @author Lucas Gaspar
     * @since 2012-03-20
     */
    public function testBadRate(){
        
    }
    
    /*
     * Testa uma avaliação a uma empresa que não se tenha cartões trocados
     * @author Lucas Gaspar
     * @since 2012-03-19
     */
    public function testNoContactCompany(){
        
    }
    
    /*
     * -------------------------------------------------------------------------
     * Teste do startPremiumAction()
     * -------------------------------------------------------------------------
     */
    
    /*
     * Testa a aquisição do serviço Premium por uma nova empresa
     * @author Lucas Gaspar
     * @since 2012-03-20
     */
    public function testNewPremiumCompany(){
        
    }
    
    /*
     * Testa a aquisição do serviço Premium por uma empresa que já é Premium
     * @author Lucas Gaspar
     * @since 2012-03-20
     */
    public function testOrderForAlreadyPremiumUser(){
        
    }
    
    /*
     * Testa a aquisição do serviço Premium através do Test Drive
     * @author Lucas Gaspar
     * @since 2012-03-20
     */
    public function testTestDrivePremiumUser(){
        
    }
}

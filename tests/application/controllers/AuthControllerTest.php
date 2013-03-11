<?php
/**
 * AuthControllerTest.php - AuthControllerTest
 * Testes do Auth Controller
 * 
 * @package tests
 * @subpackage controllers
 * @author Lucas Gaspar, Mauro Ribeiro
 * @since 2012-03-07
 */
require_once APPLICATION_PATH.'/../tests/application/ControllerTestCase.php';

class AuthControllerTest extends ControllerTestCase
{
    /*
     * -------------------------------------------------------------------------
     * Teste do loginAction()
     * -------------------------------------------------------------------------
     */
    
    /**
     * Testa se a rota do login está correta
     * @author Lucas Gaspar, Mauro Ribeiro
     * @since 2012-03-07
     */
    public function testLoginRoute() {
        $this->dispatch('/login');
        $this->assertController('auth');
        $this->assertAction('login');
    }
    
    /**
     * Teste de login inválido
     * @author Lucas Gaspar, Mauro Ribeiro
     * @since 2012-03-07
     */
    public function testInvalidLogin() {
        // logins e senhas errados
        $this->request->setMethod('POST')
                ->setPost(array(
                    'login'=>'errado@muitoerrado.com.br',
                    'password'=>'errado'
                ));
        $this->dispatch('/login');
        // não redireciona para lugar nenhum
        $this->assertNotRedirect();
        // tem que gerar erro 403 (forbidden)
        $this->assertResponseCode(403);
    }

    /**
     * Teste de login válido
     * @author Lucas Gaspar, Mauro Ribeiro
     * @since 2012-03-07
     */
    public function testValidLogin() {
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
        // direciona para qualquer outra página que não seja o de login
        $this->assertRedirect();
    }

    /*
     * -------------------------------------------------------------------------
     * Teste do logoutAction()
     * -------------------------------------------------------------------------
     */
    
    /**
     * Testa se a rota do logout está correta
     * @author Lucas Gaspar
     * @since 2012-03-08
     */
    public function testLogoutRoute() {
        // pega um usuário qualquer na Empreendemia para logar
        $user_mapper = new Ee_Model_Users();
        $user = $user_mapper->random();
        // submete o login
        $this->request->setMethod('POST')
                ->setPost(array(
                    'login'=>$user->login,
                    'password'=>'testando'
                ));
        $this->dispatch('/login');
        // prepara ambiente para deslogar
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $userdata = new Zend_Session_Namespace('UserData');
        // desloga
        unset($userdata->user);
        // testa se ação de deslogar foi um sucesso
        $this->assertController('auth');
        $this->dispatch('/logout');
    }
    
    /*
     * -------------------------------------------------------------------------
     * Teste do forgotPasswordAction()
     * -------------------------------------------------------------------------
     */
    
    /**
     * Envia e-mail e testa o controller "fogot-password" quando o usuário esquece
     * a senha ao fornecer um login válido
     * @author Lucas Gaspar
     * @since 2012-03-12
     */
    public function testValidLoginForgot() {
        // pega um usuário aleatório
        $user_mapper = new Ee_Model_Users();
        $user = $user_mapper->random();
        // enva e senha para esse usuário
        $this->dispatch('/esqueci-a-senha/'.$user->login);
        $this->assertAction('forgot-password');
    }
        
    /**
     * Testa quando o usuário esquece a senha ao fornecer um login inválido
     * @author Lucas Gaspar
     * @since 2012-03-12
     */
    public function testInvalidLoginForgot() {
        // url que tem que acessar
        $url = 'esqueci-a-senha';
        // "date('Ymd')" garante a criação de um login inválido
        $random = date('Y-m-dd');
        // se prepara para a exceção que será disparada
        $this->setExpectedException('Zend_Controller_Action_Exception');
        $this->dispatch('/'.urlencode($url).'/'.$random);
    }
    
    /*
     * -------------------------------------------------------------------------
     * Teste do indexAction()
     * -------------------------------------------------------------------------
     */
    
    /**
     * Testa o redirecionamento do indexAction (autenticação)
     * @author Lucas Gaspar
     * @since 2012-03-12
     */
    public function testIndexAction() {
        $this->dispatch('/autenticar');
        $this->assertAction('sign-up');
    }
    
    /*
     * -------------------------------------------------------------------------
     * Teste do inviteAction()
     * -------------------------------------------------------------------------
     */
    
    /**
     * Verifica se o caminho de um convite inexistente é o esperado
     * @author Lucas Gaspar
     * @since 2012-03-13
     */
    public function testInexistentInvite() {
        // pega um usuário qualquer na Empreendemia usar o e-mail
        $user_mapper = new Ee_Model_Users();
        $user = $user_mapper->random();
        // direciona para um convite inexistente
        $this->dispatch('/aceitar-convite/'.$user->login.'/1/2012-03-13+16%3A38%3A56');
        // por definição, como o convite não existe, redireciona para 'cadastre-se'
        $this->assertRedirect();
    }
    
    /*
     * -------------------------------------------------------------------------
     * Teste do signUpAction()
     * -------------------------------------------------------------------------
     */
    
    /**
     * Testa se o usuário que quer fazer o cadastro já está logado
     * @author Lucas Gaspar
     * @since 2012-03-12
     */
    public function testAlreadyLogged(){
        // pega um usuário qualquer na Empreendemia para logar
        $user_mapper = new Ee_Model_Users();
        $user = $user_mapper->random();
        // submete o login
        $this->request->setMethod('POST')
            ->setPost(array(
                'login'=>$user->login,
                'password'=>'testando'
            ));
        $this->dispatch('/login');
        $this->dispatch('/cadastre-se');
        $this->assertRedirect();
    }
    
    /**
     * Testa se o usário já está registrado no sistema
     * @author Lucas Gaspar
     * @since 2012-03-12
     */
    public function testAlreadyRegistered(){
        // pega um usuário já existente no Empreendemia
        $user_mapper = new Ee_Model_Users();
        $user = $user_mapper->random();
        $random_user = $user->login.'@teste.com.br';
        // preenche formulário de cadastro
        $this->request->setMethod('POST')
            ->setPost(array(
                'user_name'=>'João',
                'user_family_name'=>'Testando',
                'user_password'=>'testando',
                'user_password_confirmation'=>'testando',
                'user_login'=>$user->login,
                'user_login_confirmation'=>$user->login,
                'company_name'=>'test_'.$random_user,
                'company_type'=>'freelancer',
                'company_region_id_select'=>26,
                'company_city_id_select'=>8781,
                'company_city_id'=>8781,
                'company_sector_id'=>19,
                'company_profile'=>'all',
                'terms_of_service'=>1    
            ));
        // despacha o cadastro
        $this->dispatch('/cadastre-se');
        $this->assertNotRedirect();
    }
    
    /**
     * Testa se o processo de novo cadastro funciona como previsto
     * @author Lucas Gaspar
     * @since 2012-03-12
     */
    public function testNewSignup(){
        // gera login de usuário aleatório
        $random_user = 'x'.date('His');
        $random_user = $random_user.'@teste.com.br';
        // gera empresa aleatória
        $random_company = $random_user.date('u');
        // preenche formulário de cadastro
        $this->request->setMethod('POST')
            ->setPost(array(
                'user_name'=>'João',
                'user_family_name'=>'Testando',
                'user_password'=>'testando',
                'user_password_confirmation'=>'testando',
                'user_login'=>$random_user,
                'user_login_confirmation'=>$random_user,
                'company_name'=>'Test'.$random_company,
                'company_type'=>'freelancer',
                'company_region_id_select'=>26,
                'company_city_id_select'=>8781,
                'company_city_id'=>8781,
                'company_sector_id'=>19,
                'company_profile'=>'all',
                'terms_of_service'=>1    
            ));
        // despacha o cadastro
        $this->dispatch('/cadastre-se');
        $this->assertRedirect();
    }
    
    /*
     * -------------------------------------------------------------------------
     * Teste do autoLoginAction()
     * -------------------------------------------------------------------------
     */
    
    /**
     * Teste do redirecionamento do auto login (sendo válido ou inválido)
     * @author Mauro Ribeiro
     * @since 2012-03-08
     */
    public function testAutoLoginRedirection() {
        // pega um usuário aleatório
        $user_mapper = new Ee_Model_Users();
        $user = $user_mapper->random();
        // gera a hash qualquer
        $hash = 'hashqualquer';
        // url que o cara quer acessar
        $url = '/novidades';
        $this->dispatch('/voltar/'.urlencode($url).'/'.$user->id.'/'.$hash);
        // tem que redirecionar para a página
        $this->assertRedirectTo('/novidades');
    }

    /**
     * Teste de auto login inválido 
     * @author Mauro Ribeiro
     * @since 2012-03-08
     */
    public function testInvalidAutoLogin() {
        // pega um usuário aleatório
        $user_mapper = new Ee_Model_Users();
        $user = $user_mapper->random();
        // gera a hash incorreta
        $hash = 'ehaehehea_to_errado_mano';
        // url que o cara quer acessar e precisa de autorização
        $url = '/novidades';
        $this->dispatch('/voltar/'.urlencode($url).'/'.$user->id.'/'.$hash);
        // verifica se o usuário foi logado
        $auth = Zend_Auth::getInstance();
        $this->assertNull($auth->getIdentity());
    }

    /**
     * Teste de auto login válido
     * @author Mauro Ribeiro
     * @since 2012-03-08
     */
    public function testValidAutoLogin() {
        // pega um usuário aleatório
        $user_mapper = new Ee_Model_Users();
        $user = $user_mapper->random();
        // gera a hash correta
        $hash = sha1($user->login.'eeAutoLogin'.date('YW'));
        $hash .= sha1($user->password.'eeAutoLogin'.date('YW'));
        // url que o cara quer acessar e precisa de autorização
        $url = '/novidades';
        $this->dispatch('/voltar/'.urlencode($url).'/'.$user->id.'/'.$hash);
        // verifica se o usuário logado é o mesmo que logou
        $auth = Zend_Auth::getInstance();
        $logged_user = null;
        if ($auth->getIdentity()) $logged_user = $auth->getIdentity();
        $this->assertEquals($user->id, $auth->getIdentity()->id);
    }
    
    /*
     * -------------------------------------------------------------------------
     * Teste do generatePasswordAction()
     * -------------------------------------------------------------------------
     */
    
    /**
     * Testa os passos de enviar o e-mail para usuário que esqueceu a senha
     * @author Lucas Gaspar
     * @since 2012-03-13
     */
}

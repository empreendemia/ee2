/**
 * Empreendemia.js
 * Controlador básico da navegação e funcionalidades da empreendemia
 * 
 * @author Rafael Almeida Erthal Hermano
 * @since 2012-03
 */

var Empreendemia = function()
{       
    /**
     * Carregamento inicial da página
     * Este método só deve ser chamado na inicialização da página não precisa ser thread safe
     * Os módulos aqui carregados, só devem ser colocados caso só sejam inicializados uma vez no sistema.
     * 
     * @author Rafael Almeida Erthal Hermano
     * @since 2012-03
     */
    this.load = function()
    {    
        Auth.load(); 
        Tracker.ga.load();
        Tracker.mp.load();
        Loading.load();
        Empreendemia.loadGlobalEvents();
        Tracker.mp.register({'status':'not logged','landing_page':Empreendemia.Navigation.url});
    };

    return true;
}

/**
* Carregamento de componentes de cada página
* Este método deve ser chamado sempre que temos o retorno de uma ajax request que não fique em um modal.
* Este método precisa ser thread safe
* Recomenda-se que os módulos aqui chamados, sejam todos orientados com eventos, e que sempre remova os marcadores utiliados nos objetos para não dar bubble
* 
* @author Rafael Almeida Erthal Hermano
* @since 2012-03
*/
Empreendemia.loadGlobalEvents = function()
{
    Empreendemia.loadEvents();
    Empreendemia.Navigation.load();
    
    AdsList.load();
    FlashMessages.load();
    Tracker.load();
    Feedback.load();
    
    //SocialNetworks.facebook(document, 'script', 'facebook-jssdk');
    SocialNetworks.googleplus();
    
};

/**
* Carregamento de modal
* Este método deve ser chamado sempre que temos o retorno de uma ajax request em um modal.
* Este método precisa ser thread safe
* Recomenda-se que os módulos aqui chamados, sejam todos orientados com eventos, e que sempre remova os marcadores utiliados nos objetos para não dar bubble
* 
* @author Rafael Almeida Erthal Hermano
* @since 2012-03
*/
Empreendemia.loadEvents = function()
{    
    Modal.load();
    AjaxLink.load();
    AjaxForm.load(); 
}

/**
 * Navigation
 * Controlador da navegação no navegador
 * 
 * @package Empreendemia
 * @author Rafael Almeida Erthal Hermano
 * @since 2012-03
 */
Empreendemia.Navigation = function(){};

/**
 * Titulo que é exibido na página
 * @type string
 */
Empreendemia.Navigation.title = "";

/**
 * Path name da url que é exibida na página
 * @type string
 * @example e/minha-empresa
 */
Empreendemia.Navigation.url = "";

/**
 * URL base da instância rodando
 * @type string
 * @example http://localhost/ee/dev/
 */
Empreendemia.Navigation.base = "";

/**
 * Última URL que o usuário visitou
 * @type string
 * @example lista-de-empresa/sao-paulo/campinas-sp
 */
Empreendemia.Navigation.last = "/passo-a-passo";

/**
 * Marcador para avisar se podemos utilizar ajax na página ou não
 * @type boolean
 */
Empreendemia.Navigation.ajaxBlocked = false;

/**
* Carregamento de telas especificas do sistema
* Este método detecta se a pagina que esta sendo exibida necessita carregar algum módulo java script específico
* 
* @author Rafael Almeida Erthal Hermano
* @since 2012-03
*/
Empreendemia.Navigation.load = function()
{
    if(Empreendemia.Navigation.url == "") Empreendemia.Navigation.url = "/";
    
    // Verifica se podemos usar ajax na navegação
    try{
        history.pushState({}, Empreendemia.Navigation.title, Empreendemia.Navigation.url);
    }
    catch(e){
        Empreendemia.Navigation.ajaxBlocked = true;
    }
    
    $(document).attr("title", Empreendemia.Navigation.title);
    
    // atualiza base url
    Empreendemia.Navigation.base = $('base').attr('href').
        replace('http://'+window.location.hostname, '')
        .replace(':'+window.location.port,'');

    // envia pageviews e eventos do Google Analytics
    Tracker.ga.pageview();
    Tracker.ga.pushEvents();
    Tracker.mp.pushTracks();
    
    // carrega módulos
    if(location.pathname.indexOf('/painel/empresa/produtos') >= 0) Products.load();
    if(location.pathname.indexOf('/meus-contatos') >= 0) Contacts.load();
    if(location.pathname.indexOf('/novidades') >= 0) Updates.load();
    if(location.pathname.indexOf('/login') >= 0) Login.load();
    if(location.pathname.indexOf('/autenticar') >= 0) Login.load();
    if(location.pathname.indexOf('/publicidade') >= 0) AdsPage.load();
    if(location.pathname.indexOf('/publicidade/configurar-campanha') >= 0) AdsConfig.load();
    if(location.pathname.indexOf('/planos/premium') >= 0) Premium.load();
    if(location.pathname.indexOf('/painel/usuario/convidar') >= 0) UserInvite.load();
    if(location.pathname.indexOf('/minha-empresa/produtos/excluir') >= 0) {
        Products.remove($('#productId').val());
    }
    if(location.pathname.indexOf('/minha-empresa/produtos/editar') >= 0) Products.edit($('#productId').val(),$('#productName').html(),$('#productImage').html());    
    if(location.pathname.indexOf('/faq') >= 0) Faq.load();
    if(location.pathname.indexOf('/aceitar-convite') >= 0) Invite.load();
    if(location.pathname.indexOf('/esqueci-a-senha') >= 0) ForgotPassword.load();
    if(location.pathname.indexOf('/painel/usuario/notificacoes') >= 0) Notify.load();
    if(location.pathname.indexOf('/lista-de-empresas') >= 0) CompaniesList.load();
    if(Empreendemia.Navigation.url == Empreendemia.Navigation.base) Input.load();
    if(location.pathname.indexOf('/autenticar-se') >= 0) Input.load();
    if(location.pathname.indexOf('/cadastre-se') >= 0) Input.load();
    if(location.pathname.indexOf('/login') >= 0) Input.load();
    if(location.pathname.indexOf('/pessoas') >= 0) Message.load();
    if(location.pathname.indexOf('/meus-contatos') >= 0) Message.load();
    if(Empreendemia.Navigation.url == Empreendemia.Navigation.base) Home.load();
    
    // carrega última url visitada para navegação interrompida
    if(
        location.pathname.indexOf('/login') < 0
        && location.pathname.indexOf('/cadastre-se') < 0
        && location.pathname.indexOf('/autenticar') < 0
      )
      Empreendemia.Navigation.last = document.URL
            .replace('http://'+window.location.hostname+Empreendemia.Navigation.base, '')
            .replace(':'+window.location.port,'');
}


$(document).ready(function(){    
    Empreendemia.Navigation.title = document.title;
    Empreendemia.Navigation.url = location.pathname;
    new Empreendemia().load();
});

window.onpopstate = function(e){
    if(e.state){
        location.href = document.location;
    }
};
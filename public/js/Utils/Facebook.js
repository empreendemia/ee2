/** 
 * Facebook.js
 * Controla integração com Facebook
 * 
 * @package Utils
 * @author Mauro Ribeiro
 * @since 2012-05
 */

// ============================================================
// Carrega o Facebook JS SDK
// ============================================================

(function(d){
 var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
 if (d.getElementById(id)) {return;}
 js = d.createElement('script'); js.id = id; js.async = true;
 js.src = "//connect.facebook.net/pt_BR/all.js";
 ref.parentNode.insertBefore(js, ref);
}(document));
 
// Init the SDK upon load
window.fbAsyncInit = function() {
    FB.init({
        appId      : '150883145042338', // App ID
        //channelUrl : '//'+window.location.hostname+'/channel', // Path to your Channel File
        status     : true, // check login status
        cookie     : true, // enable cookies to allow the server to access the session
        xfbml      : true  // parse XFBML
    });

}
// ============================================================
// Atualiza e cria elementos na página
// ============================================================

function facebookLogin() {
    
    $('.facebook-login').html('<span>Logando pelo Facebook...</span>')
                
    FB.login(
        function(response){
            if (response.authResponse) {
                var access_token = FB.getAuthResponse()['accessToken'];
                FB.api('/me', function(response){
                   window.location.replace('login/facebook/'+response.id+'/'+access_token);
                });
            } else {

            }
        },
        { scope:'email,publish_actions' } // publish_stream
    );
}

function facebookSignup() {
    
    $('.facebook-signup').html('Logando pelo Facebook...')
                
    FB.login(
        function(response){
            if (response.authResponse) {
                var access_token = FB.getAuthResponse()['accessToken'];
                FB.api('/me', function(response){
                   window.location.replace('cadastre-se/facebook/'+response.id+'/'+access_token+'?redirect='+window.location.href);
                });
            } else {

            }
        },
        { scope:'email,publish_actions' } // publish_stream
    );
}
// ============================================================
// Associa ações do FB
// ============================================================

// ------------------------------------------------------------
// Publicar ação no mural
// ------------------------------------------------------------
$(function() {
        
});


// ============================================================
// Convites
// ============================================================


function facebookInvite() {
    /*
    FB.ui({method: 'apprequests',
        message: 'quer trocar cartões com você'
    },*/
    FB.ui({
        method: 'feed',
        name: 'Vamos trocar cartões?',
        link: 'http://www.empreendemia.com.br',
        picture: '',
        caption: 'www.empreendemia.com.br',
        description: 'Acabei de cadastrar a minha empresa na Empreendemia, rede para acelerar negócios entre empresas. Que tal cadastrar a sua também?'
    },
    function(request) { 
        if (request) {
            $('.facebookInvites .text').html('Convites enviados para seus contatos no Facebook.');
            Tracker.ga.userEvent('invited facebook contacts');
        }
    });
}
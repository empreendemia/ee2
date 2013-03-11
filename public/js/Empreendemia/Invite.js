/*
 * Invite.js
 * Script para página que usuário recebe quando é convidado ao Empreendemia
 * 
 * @author Mauro Ribeiro, Lucas Gaspar*
 * @since 2012-03-21
 */

var Invite = function(){}

Invite.load = function(){

    var throw_event = false;

    $('#page .container').prepend('<div id="must_auth_message" style="display:none"><h1>Você precisa estar logado para realizar essa ação.</h1><h2>Se você ainda não faz parte da rede, <strong>cadastre-se gratuitamente</strong>!</h2></div>');
    $('#must_auth_message').slideDown(500);
    $('.signup_form input, .faq').hover(function() {
        if (throw_event == false) {
        Tracker.ga.userEvent('started sign up');
        throw_event = true;
        }
    });

}

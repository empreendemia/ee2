/** 
 * Auth.js
 * Carrega dados de login do usuário para o escopo da Empreendemia.js
 * 
 * @package Empreendemia
 * @author Mauro Ribeiro
 * @since 2012-03
 */


var Auth = function(){}

/**
 * Dados do usuário
 * @type object
 */
Auth.user = {};

/**
 * Puxa os dados do usuário no servidor
 */
Auth.getUser = function() {
    $.ajax({
        url: "ajax/get-auth",
        async: false,
        dataType: 'json',
        success: function(data) {
            Auth.user = data;
        }
    });
}

/**
 * Pega o email se o cara tiver logado ou o IP caso deslogado
 */
Auth.getIdentifier = function() {
    // se não carregou o login
    if (!Auth.user.hasOwnProperty('login') == false){ //&& !Auth.user.hasOwnProperty('ip')) {
        // carrega
        Auth.getUser();
    }
    
    // se o cara ta logado
    if (Auth.user.hasOwnProperty('login')) {
        return Auth.user.login;
    }
    // se o cara tá deslogado
    else if (Auth.user.hasOwnProperty('ip')) {
        return "not logged: "+Auth.user.ip;
    }
    else {
        return false;
    }
}

/**
 * Carrega o módulo
 */
Auth.load = function() {
    Auth.getUser();
}
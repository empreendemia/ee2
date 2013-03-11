/** 
 * Login.js
 * Login do usuário
 * 
 * @package Empreendemia
 * @author Mauro Ribeiro
 * @since 2012-03
 */

var Login = function() {}

/** 
 * Atualiza email do cara no link de esqueci-a-senha
 */
Login.password = function() {
    $('#forgot_password_link').attr('href', 'esqueci-a-senha/'+$('.user_login input#login').val());
}

/**
 * Adiciona eventos e propriedades ao formulário de login
 */
Login.load = function() {
    // quando coloca o mouse em cima do "esqueci a senha", atualiza link
    $('#forgot_password_link').hover(function() { Login.password() });
    // quando altera o campo de login, atualiza link
    $('.user_login input#login').keydown(function() { Login.password() });
    $('.user_login input#login').change(function() { Login.password() });
    // atualiza a url para redirecionamento da navegação interrompida
    $('.user_login input#return').attr('value', Empreendemia.Navigation.last);
    $('.login input#return').attr('value', Empreendemia.Navigation.last);
    $('#signup input#return').attr('value', Empreendemia.Navigation.last);
}
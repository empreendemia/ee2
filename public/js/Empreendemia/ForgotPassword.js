/*
 * ForgotPassword.js
 * Script da página de login
 * 
 * @author Mauro Ribeiro, Lucas Gaspar*
 * @since 2012-03-21
 */

var ForgotPassword = function(){}

ForgotPassword.password = function (){
   $('#forgot_password_link').attr('href', 'esqueci-a-senha/'+$('.user_login input#login').val());
}

ForgotPassword.load = function(){
    $('#forgot_password_link').hover(function() { password() });
    $('.user_login input#login').keydown(function() { password() });
    $('.user_login input#login').change(function() { password() });
}
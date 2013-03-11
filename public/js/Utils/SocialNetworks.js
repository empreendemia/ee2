/** 
 * SocialNetworls.js
 * Controla os botões das redes sociais
 * 
 * @package Empreendemia
 * @author Rafael Almeida Erthal Hermano
 * @since 2012-03
 */

var SocialNetworks = function(){}

/**
 * Carrega o botão de like do facebook
 */
SocialNetworks.facebook = function(d, s, id)
{
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#appId=285597901457818&xfbml=1";
    fjs.parentNode.insertBefore(js, fjs);                
}

/**
 * Carrega o botão de like do googleplus
 */
SocialNetworks.googleplus = function()
{
    var po = document.createElement('script');
    po.type = 'text/javascript';
    po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(po, s);
}
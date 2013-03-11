/*
 * Faq.js
 * Script que expande os tópicos do FAQ
 * 
 * @author Mauro Ribeiro, Lucas Gaspar*
 * @since 2012-03-21
 */

var Faq = function(){}

Faq.toggleDiv = function (divid){
    var div = document.getElementById(divid);
    div.style.display = div.style.display == 'block' ? 'none' : 'block';
}

Faq.load = function(){
    $('a[div-id]').each(function(){
        $(this).click(function(){
            Faq.toggleDiv($(this).attr('div-id'));
        });
    });
}

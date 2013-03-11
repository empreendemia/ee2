/** 
 * InputPhone.js
 * Controla os inputs que receberão mascara de telefone
 * 
 * @package Empreendemia
 * @author Rafael Almeida Erthal Hermano
 * @since 2012-03
 */

var InputPhone = function(obj){
    //$(obj).mask("55 99 99999999");
    
    $(obj).removeClass('phone');
}

/**
 * Carrega os inputs que receberão mascara de telefone
 */
InputPhone.load = function()
{
    $('.phone').each(function(){     
        var inputPhone = new InputPhone($(this));
    });
}
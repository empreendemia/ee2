/** 
 * Modal.js
 * Controla ajaxação dos modais
 * 
 * @package Empreendemia
 * @author Rafael Almeida Erthal Hermano
 * @since 2012-03
 */

var Modal = function(obj)
{
    var url  = $(obj).attr('href') + "?" + "template=modal";             
    var width = $(obj).attr('modal-width') ? $(obj).attr('modal-width') : 200;
    var height = $(obj).attr('modal-height') ? $(obj).attr('modal-height') : 160;
    
    $(obj).attr('href', url)
    
    $(obj).colorbox({
        ajax:true,
        speed:100,
        initialWidth:200,
        initialHeight:160,
        innerWidth:width,
        innerHeight:height,
        onComplete: function() {
            Empreendemia.loadGlobalEvents(); 
        }
    });

    $(obj).removeAttr('method');
    $(obj).removeAttr('modal-width');
    $(obj).removeAttr('modal-height');
    $(obj).removeAttr('target');
    
    return true;
}

/**
 * Carrega os modais que serão ajaxados
 */
Modal.load = function()
{    
    $('a[target="modal"]').each(function(){    
        var modal = new Modal($(this));
    });

    $(".modal_close").click(function() {
        $.fn.colorbox.close();
    });
}
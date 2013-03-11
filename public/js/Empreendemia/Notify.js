/** 
 * Notify.js
 * Controla os pedidos de troca de cartão e a remoção das notificações
 * 
 * @package Empreendemia
 * @author Rafael Almeida Erthal Hermano
 * @since 2012-03
 */

var Notify = function()
{}

Notify.acceptCard = function(obj)
{
    $(obj).html('<div class="action_made">aceito</div>');    
}

Notify.refuseCard = function(obj)
{
    $(obj).html('<div class="action_made">recusado</div>');
}

Notify.remove = function(obj)    
{
    $(obj).parent().parent().fadeTo(0, 0.5);
    $(obj).parent().parent().slideUp(200);
}

Notify.load = function()
{
    $("#refuse_card").each(function(){
        var obj = this;
        $(obj).click(function(e){            
            Notify.refuseCard($(obj).attr('updater-id'));
            e.stopPropagation();
        });
    });
    
    $("#accept_card").each(function(){
        var obj = this;
        $(obj).click(function(e){            
            Notify.acceptCard($(obj).attr('updater-id'));
            e.stopPropagation();
        });
    });
    
    $(".remove").each(function(){
        var obj = this;
        $(obj).click(function(e){            
            Notify.remove(obj);            
            e.stopPropagation();
        });
    });
}
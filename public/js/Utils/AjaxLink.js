/** 
 * AjaxLink.js
 * Controla a ajaxação das ancoras
 * 
 * @package Empreendemia
 * @author Rafael Almeida Erthal Hermano
 * @since 2012-03
 */

var AjaxLink = function(obj)
{
    var targetId = $(obj).attr('target');
    var url      = $(obj).attr('href');         
    var template = 'template=' + ($(obj).attr('template') ? $(obj).attr('template') : 'clean');         

    $(obj).click(function(){
        var ajaxit = false;
        if (Empreendemia.Navigation.ajaxBlocked == false) ajaxit = true;
        else if (targetId =='.modal_content' || targetId.indexOf('#notify_') >=0 || targetId.indexOf('#thread_') >= 0) ajaxit = true;
        
        //Toggle de classe selected para o menu, para que fique marcada a tela selecionada
        if($(obj).parent().parent().prop('tagName') == 'MENU')
        {
            $(obj).parent().parent().children('.selected').removeClass('selected');
            $(obj).parent().addClass('selected');
        }
    
        if (ajaxit) {
            $.ajax({
                url    : url,
                method : 'post',
                data   : template,

                beforeSend : function()
                {
                    if (targetId == '#body') {
                        $('#page').fadeTo(0, 0.2);
                    }
                    else {
                        $(targetId).fadeTo(0, 0.2);
                    }
                    Loading.start(targetId);
                },

                success : function(data)
                {
                    if (targetId == '#body') {
                        $("#page").fadeTo(0, 1);
                    }
                    else {
                        $(targetId).fadeTo(0, 1);
                    }
                    Loading.stop();
                    $('html, body').animate({scrollTop:0});

                    if($(targetId)){
                        $(targetId).html(data);                
                        $(targetId).focus();
                    }                
                    Empreendemia.loadGlobalEvents();
                },

                error : function()
                {
                    //todo tratamento de erros entra aqui
                }
            });
        }
        
        //Coloca a url no módula da Empreedemia.Navigation
        var new_url = $(obj).attr("url");
        if (typeof(new_url) == 'undefined') {
            Empreendemia.Navigation.url = url;
        }
        else if (new_url != '') {
            Empreendemia.Navigation.url = new_url;
        }
        
        //Verifica se pode de fato ajaxar o link ou teremos que usar uma ancora normal
        return Empreendemia.Navigation.ajaxBlocked && targetId!='.modal_content' && targetId.indexOf('#notify_') == -1 && targetId.indexOf('#thread_') == -1;
    });

    $(obj).removeAttr('method');
    $(obj).removeAttr('target');
    $(obj).removeAttr('template');

    return true;
}

/**
 * Carrega as ancoras que serão ajaxadas
 */
AjaxLink.load = function()
{    
    $('a[method="ajax"]').each(function(){     
        var ajaxLink = new AjaxLink($(this));
    });
}
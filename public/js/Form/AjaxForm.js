/** 
 * AjaxForm.js
 * Controla a ajaxação dos formulários
 * 
 * @package Empreendemia
 * @author Rafael Almeida Erthal Hermano
 * @since 2012-03
 */

var AjaxForm = function(obj)
{
    var targetId = $(obj).attr('target');
    var url      = $(obj).attr('action');         
    var template = 'template=' + ($(obj).attr('template') ? $(obj).attr('template') : 'clean'); 
    var type = $(obj).attr('type') ? $(obj).attr('type') : 'POST';
    var enctype = $(obj).attr('enctype');
    
    DatePicker.load();
    InputPhone.load(); 
    RegionSelect.load(); 
    Wordcount.load(); 
    //Ckeditor.load(); 

    $(obj).submit(function(){                
        if(targetId != '.modal_content') Empreendemia.Navigation.url = url; 
        
        if(enctype == 'multipart/form-data')
        {
            //Controla os formulários que postam imagens
            $('<iframe>')
            .attr({
                'id' : 'file-uploader',
                'name' : 'file-uploader',
                'onload' : 'iframeLoaded("'+targetId+'");',
                'style' : 'display:none'
            })
            .appendTo($(obj));

            $(obj).attr('target','file-uploader');
            $(obj).attr('method','POST');
            
            return true;
        }
        else
        {
            //Controla todos os outros formulários
            $.ajax({
                url    : url,
                type   : type,
                data   : $(obj).serialize() + "&" + template,

                beforeSend : function()
                {
                    $(obj).fadeTo(0, 0.2);
                    Loading.start(obj);
                },

                success : function(data)
                {
                    $(obj).fadeTo(0, 1);
                    $('html, body').animate({scrollTop:0});
                    Loading.stop(obj);
                    $(targetId).html(data);
                    Empreendemia.loadGlobalEvents();
                },

                error : function()
                {
                //todo tratamento de erros entra aqui
                }
            });     
        
            return false;
        }
    });
    
    $(obj).removeAttr('method');
    $(obj).removeAttr('target');
    $(obj).removeAttr('template');
    $(obj).removeAttr('type');

    return true;
}

/**
 * Carrega os formulários que serão ajaxados
 */
AjaxForm.load = function()
{    
    $('form[method="ajax"]').each(function(){     
        var ajaxForm = new AjaxForm($(this));
    });
}

function iframeLoaded(targetId)
{
    if($('#file-uploader').contents().find('body').html() != "")
    {
        $(targetId).html($('#file-uploader').contents().find('body').html());
        $('file-uploader').remove();
        Empreendemia.loadGlobalEvents();
    }
}
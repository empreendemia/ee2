/** 
 * Loading.js
 * Controla a barra de loading da pagina
 * 
 * @package Empreendemia
 * @author Mauro Ribeiro
 * @since 2012-03
 */
var Loading = function(){}

Loading.load = function()
{
    $('body').append('<div id="loading" style="display:none"><img src="images/ui/ajax-loader.gif" /></div>');
};

Loading.start = function(targetId)
{
       
    // posição da imagem
    // se for um formulário
    if ($(targetId).get(0).tagName.toLowerCase() == "form") {
        var submit = $(targetId).find("input[type=submit]");
        // container do loading
        $('#loading').css({
            'position'         : 'absolute',
            'background-color' : 'transparent',
            'display'          : 'none',
            'left'             : Math.floor($(targetId).offset().left),
            'top'              : Math.floor($(targetId).offset().top),
            'width'            : Math.ceil($(targetId).width()),
            'height'           : Math.ceil($(targetId).offset().top)
        });
        
        var pos_left = submit.offset().left - $(targetId).offset().left + submit.width() + 60;
        var pos_top = submit.offset().top - $(targetId).offset().top + submit.height() - 10;
        $('#loading img').css({
            'position'         : 'relative',
            'left'             : Math.floor(pos_left),
            'top'              : Math.floor(pos_top)
        });
    }
    // se não for um formulário
    else {
        // container do loading
        $('#loading').css({
            'position'         : 'absolute',
            'background-color' : 'transparent',
            'display'          : 'none',
            'left'             : Math.floor($(targetId).offset().left),
            'top'              : Math.floor($(targetId).offset().top),
            'width'            : Math.ceil($(targetId).width()),
            'height'           : Math.ceil($(targetId).height())
        });
        // imagem
        $('#loading img').css({
            'position'         : 'relative',
            'left'             : Math.ceil($('#loading').width() / 2),
            'top'              : Math.ceil($('#loading').width() / 5)
        });
    }

    $('#loading').fadeIn(0);
};

Loading.stop = function()
{
    $('#loading').fadeOut(0);
};
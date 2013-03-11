/** 
 * AdsList.js
 * Exibe lista de anúncios 
 * 
 * @package Empreendemia
 * @author Mauro Ribeiro
 * @since 2012-03
 */

var AdsList = function(){}

/**
 * Carrega os anúncios
 */
AdsList.load = function() 
{
    // carrega anúncios para cada container .ads_display 
    $('.ads_display').each(function() {
        var container = $(this);
        // por padrão, carrega 4 anúncios
        if (container.hasClass('ads_display_4')) number = 4;
        else if (container.hasClass('ads_display_1')) number = 1;
        else if (container.hasClass('ads_display_2')) number = 2;
        else if (container.hasClass('ads_display_3')) number = 3;
        else if (container.hasClass('ads_display_5')) number = 5;
        else number = 4;
        
        container.load(
            'ads/list',
            {
                number : number
            },
            function() {
                Empreendemia.loadEvents();
            }
        );
    });
}
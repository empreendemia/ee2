/** 
 * CompaniesList.js
 * Controla o hightlight de palavras na busca de empresas
 * 
 * @package Empreendemia
 * @author Rafael Almeida Erthal Hermano
 * @since 2012-03
 */

var CompaniesList = function(){}

CompaniesList.load = function()
{
    $('.search_form #s').change(function(){
        $('.location_filter #s').val($(this).val());
    });
    
    $('.location_filter #s').val($('.search_form #s').val());
}
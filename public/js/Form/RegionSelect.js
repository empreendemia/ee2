/** 
 * RegionSelect.js
 * Controla os inputs que serão seletores de região
 * 
 * @package Empreendemia
 * @author Rafael Almeida Erthal Hermano
 * @since 2012-03
 */

var RegionSelect = function(obj){
        
    if ((obj.val() != '')) {
        selectRegion($('.select_region'));
    }
    
    // seletor de cidade e estado
    $(obj).change(function() {
        selectRegion($(this));
    });
        
    $(obj).removeClass('select_region');        
}

/**
 * Carrega os inputs que receberão o seletor de região
 */
RegionSelect.load = function()
{
    $('.select_region').each(function(){     
        var regionSelect = new RegionSelect($(this));
    });
    
    $('.select_city').change(function() {
        $('.city_id_hidden').val($(this).val());
    });        
}

function selectRegion(select) {
    var select_region = select;
    var region_id = select_region.val();
    var select_city = select_region.parent().parent().find('.select_city');
        
    select_city.html('<option value="">carregando...</option>');
    select_city.parent().append(' <img src="images/ui/ajax-loader-small.gif" />');
        
    select_city.load(
        'locations/cities-select',
        {
            'region_id' : region_id
        },
        function(response, status, xhr) {
            if (status == 'error') {
                select_city.html('<option value="">- cidade</option>');
                select_city.parent().find('img').remove();
                $('.city_id_hidden').val(null);
            }
            else {
                select_city.removeAttr('disabled');
                select_city.parent().find('img').remove();
                select_city.val($('.city_id_hidden').val());
            }
        }
        ); 
}
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

$(function() {
    
    if (($('.select_region').val() != '')) {
        selectRegion($('.select_region'));
        //$('.select_city').val($('.company_city_id_hidden').val());
    }

    // seletor de cidade e estado
    $('.select_region').change(function() {
        $('.city_id_hidden').val($(this).val());
        selectRegion($(this));
    });

    $('.select_city').change(function() {
        $('.city_id_hidden').val($(this).val());
    });

});
var selected_checkboxes = {};

function loadCities() {
    $('.sectors_list').html('');
    $('#all_cities').prop("checked", false);
    $('#all_sectors').prop("checked", false);
    selected_checkboxes = {};
    var count = 0;
    $('.regions_list input[type=checkbox]:checked').each(function(){
        var checkbox = $(this);
        selected_checkboxes[checkbox.attr('name')] = checkbox.val();
        count++;
    });
    if (count > 0) {
        $('.cities_list').html('<div class="loading"><img src="images/ui/ajax-loader.gif" /><br />carregando...</div>');
        $.ajax({
           type: "POST",
           url: "ads/select-cities",
           data: selected_checkboxes,
           success: function(html) {
               $('.cities_list').html(html);
           },
           error: function() {
               alert('Erro ao carregar cidades. Tente novamente mais tarde.');
           }
        });
    }
    else {
       $('.cities_list').html('');
    }
    regionsCounter();
    citiesCounter();
    companiesCounter();
}

function loadSectors() {
    $('#all_sectors').prop("checked", false);
    selected_checkboxes = {};
    var count = 0;
    $('.regions_list input[type=checkbox]:checked').each(function(){
        var checkbox = $(this);
        selected_checkboxes[checkbox.attr('name')] = checkbox.val();
        count++;
    });
    $('.cities_list input[type=checkbox]:checked').each(function(){
        var checkbox = $(this);
        selected_checkboxes[checkbox.attr('name')] = checkbox.val();
        count++;
    });
    if (count > 0) {
        $('.sectors_list').html('<div class="loading"><img src="images/ui/ajax-loader.gif" /><br />carregando...</div>');
        $.ajax({
           type: "POST",
           url: "ads/select-sectors",
           data: selected_checkboxes,
           success: function(html) {
               $('.sectors_list').html(html);
           },
           error: function() {
               alert('Erro ao carregar setores. Tente novamente mais tarde.');
           }
        });
    }
    else {
       $('.sectors_list').html('');
    }
    citiesCounter();
    companiesCounter();
}

function regionsCounter(){
    var count = 0;
    $('.regions_list input[type=checkbox]:checked').each(function() {
        count++;
    });
    $('#count_regions').html(count);
}

function citiesCounter(){
    var count = 0;
    $('.cities_list input[type=checkbox]:checked').each(function() {
        count++;
    });
    $('#count_cities').html(count);
}

function companiesCounter(){
    var count = 0;
    var sectors_count = 0;
    $('.sectors_list input[type=checkbox]:checked').each(function() {
        var check = $(this);
        count = count + parseInt(check.parent().find('input.companies_count').val());
        sectors_count++;
    });
    $('#count_sectors').html(sectors_count);
    $('#count_companies').html(count);
    $('#total_companies_count').val(count);

    var price = 0;
    if (count == 0) price = 0;
    else if (count <= 5000) price = 1;
    else price = 2;

    var price = $('#price_'+price).val();

    $('#campaign_price').html(price);
}

function finishAd() {
    var companies_count = $('#total_companies_count').val();

    if (companies_count > 0) {
        $('.buy a').hide();
        $('.buy .loading').show();
        $('.ads_create').fadeTo(0, 0.2);
        var post_data = {};
        var count = 0;
        $('.regions_list input[type=checkbox]:checked').each(function(){
            var checkbox = $(this);
            post_data[checkbox.attr('name')] = checkbox.val();
            count++;
        });
        $('.cities_list input[type=checkbox]:checked').each(function(){
            var checkbox = $(this);
            post_data[checkbox.attr('name')] = checkbox.val();
            count++;
        });
        $('.sectors_list input[type=checkbox]:checked').each(function(){
            var checkbox = $(this);
            post_data[checkbox.attr('name')] = checkbox.val();
            count++;
        });
        post_data['total_companies_count']  = companies_count;
        post_data['product_id'] = $('#products_select').val();
        post_data['months'] = $('#months_select').val();
        $.ajax({
           type: "POST",
           url: "publicidade/promocao/configurar-campanha/pagamento",
           data: post_data,
           success: function(html) {
                $('.ads_create').html(html).fadeTo(100, 1);
                $('.buy').slideUp();
           },
           error: function(html) {
               alert('Erro cadastrar campanha. Tente novamente.');
                $('.buy a').show();
                $('.buy .loading').hide();
                $('.ads_create').fadeTo(0, 1);
                $('.ads_create').html(html).fadeTo(100, 1);
           }
        });
    }
    else {
        var count = 0;
        $('.regions_list input[type=checkbox]:checked').each(function() {count++;});
        if (count == 0) alert('Você precisa escolher pelo menos um estado.');
        else {
            count = 0;
            $('.cities_list input[type=checkbox]:checked').each(function() {count++;});
            if (count == 0) alert('Você precisa escolher pelo menos uma cidade.');
            else {
                count = 0;
                $('.sectors_list input[type=checkbox]:checked').each(function() {count++;});
                if (count == 0) alert('Você precisa escolher pelo menos um setor.');
            }
        }
    }
    
}

$(function() {

    $('#products_select').change(function() {
        var select = $(this);
        $('.ad').hide();
        $('#show_product_'+select.val()).fadeIn();
    });

    $('.regions_list input[type=checkbox]').change(function() {
        loadCities();
        $('.sectors_list').html('');
    });

    $('.cities_list input[type=checkbox]').live('change', function() {
        loadSectors();
    });

    $('.sectors_list input[type=checkbox]').live('change', function() {
        companiesCounter();
    });

    $('.regions_list').each(function(i) {
        var regions_list = $(this);
        $(this).animate({
            scrollTop: regions_list.find("input:checked").offset().top - regions_list.offset().top
        })
    });

    $('.cities_list').each(function(i) {
        var cities_list = $(this);
        $(this).animate({
            scrollTop: cities_list.find("input:checked").offset().top - cities_list.offset().top
        })
    });

    $('#all_regions').change(function() {
       var check = $(this);
       if (check.is(':checked')) {
           $('.regions_list input[type=checkbox]').prop("checked", true);
           loadCities();
       }
       else {
           $('.regions_list input[type=checkbox]').prop("checked", false);
           $('.cities_list').html('');
            regionsCounter();
            citiesCounter();
            companiesCounter();
       }
    });

    $('#all_cities').change(function() {
       var check = $(this);
       if (check.is(':checked')) {
           $('.cities_list input[type=checkbox]').prop("checked", true);
           loadSectors();
       }
       else {
           $('.cities_list input[type=checkbox]').prop("checked", false);
           $('.sectors_list').html('');
            citiesCounter();
            companiesCounter();
       }
    });

    $('#all_sectors').change(function() {
       var check = $(this);
       if (check.is(':checked')) {
           $('.sectors_list input[type=checkbox]').prop("checked", true);
       }
       else {
           $('.sectors_list input[type=checkbox]').prop("checked", false);
       }
       companiesCounter();
    });

    $('#months_select').change(function() {
       companiesCounter();
    });
});
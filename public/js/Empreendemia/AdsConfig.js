/** 
 * AdsConfig.js
 * Configurador de anúncios 
 * 
 * @package Empreendemia
 * @author Mauro Ribeiro
 * @since 2012-03
 */

var AdsConfig = function(){};

/**
 * Configurador de criação de anúncio
 * @author Mauro Ribeiro
 * @since 2012-03
 */
AdsConfig.create = function() {
    var selected_checkboxes = {};

    /**
     * Lista cidades que possuem empresas a partir dos estados selecionados
     */
    function loadCities() {
        $('.sectors_list').html('');
        $('#all_cities').prop("checked", false);
        $('#all_sectors').prop("checked", false);
        selected_checkboxes = {};
        var count = 0;
        // verifica estados selecionados
        $('.regions_list input[type=checkbox]:checked').each(function(){
            var checkbox = $(this);
            selected_checkboxes[checkbox.attr('name')] = checkbox.val();
            count++;
        });
        // se tem pelo menos um estado selecionado
        if (count > 0) {
            $('.cities_list').html('<div class="loading"><img src="images/ui/ajax-loader.gif" /><br />carregando...</div>');
            $.ajax({
               type: "POST",
               url: "ads/select-cities",
               data: selected_checkboxes,
               success: function(html) {
                   // atualiza lista de cidades
                   $('.cities_list').html(html);
               },
               error: function() {
                   alert('Erro ao carregar cidades. Tente novamente mais tarde.');
               }
            });
        }
        // se não tem nenhum estado selecionado, não mostra cidade nenhuma
        else {
           $('.cities_list').html('');
        }
        // atualiza contadores
        regionsCounter();
        citiesCounter();
        companiesCounter();
    }

    /**
     * Lista setores que possuem empresas a partir das cidades selecionadas
     */
    function loadSectors() {
        $('#all_sectors').prop("checked", false);
        selected_checkboxes = {};
        var count = 0;
        // checa estados selecionados
        $('.regions_list input[type=checkbox]:checked').each(function(){
            var checkbox = $(this);
            selected_checkboxes[checkbox.attr('name')] = checkbox.val();
            count++;
        });
        // checa cidades selecionadas
        $('.cities_list input[type=checkbox]:checked').each(function(){
            var checkbox = $(this);
            selected_checkboxes[checkbox.attr('name')] = checkbox.val();
            count++;
        });
        // se tiver pelo menos uma cidade selecionada
        if (count > 0) {
            $('.sectors_list').html('<div class="loading"><img src="images/ui/ajax-loader.gif" /><br />carregando...</div>');
            $.ajax({
               type: "POST",
               url: "ads/select-sectors",
               data: selected_checkboxes,
               success: function(html) {
                   // atualiza lista de setores
                   $('.sectors_list').html(html);
               },
               error: function() {
                   alert('Erro ao carregar setores. Tente novamente mais tarde.');
               }
            });
        }
        // se não tiver nenhuma cidade selecionada, não mostra setor
        else {
           $('.sectors_list').html('');
        }
        // atualiza contadores
        citiesCounter();
        companiesCounter();
    }

    // conta quantos estados estão selecionados
    function regionsCounter(){
        var count = 0;
        $('.regions_list input[type=checkbox]:checked').each(function() {
            count++;
        });
        $('#count_regions').html(count);
    }

    // conta quantas cidades estão selecionadas
    function citiesCounter(){
        var count = 0;
        $('.cities_list input[type=checkbox]:checked').each(function() {
            count++;
        });
        $('#count_cities').html(count);
    }

    // conta quantas empresas serão atingidas e o preço final
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
        else if (count <= 2000) price = 1;
        else if (count <= 5000) price = 2;
        else if (count <= 10000) price = 3;
        else if (count > 10000) price = 4;

        var months = $('#months_select').val();
        price = $('#price_'+price).val();

        $('#campaign_price').html(price*months);
    }

    // quando selecionar um produto, atualiza painel
    $('#products_select').change(function() {
        var select = $(this);
        $('.ad').hide();
        $('#show_product_'+select.val()).fadeIn();
    });

    // quando alterar estados selecionados, atualiza cidades
    $('.regions_list input[type=checkbox]').change(function() {
        loadCities();
        $('.sectors_list').html('');
    });

    // quando alterar cidades selecionadas, atualiza setores
    $('.cities_list input[type=checkbox]').live('change', function() {
        loadSectors();
    });

    // quando alterar setores selecionados, atualiza público atingido
    $('.sectors_list input[type=checkbox]').live('change', function() {
        companiesCounter();
    });

    // dá scroll para o estado selecionado
    $('.regions_list').each(function(i) {
        var regions_list = $(this);
        $(this).animate({
            scrollTop: regions_list.find("input:checked").offset().top - regions_list.offset().top
        })
    });

    // dá scroll para a cidade selecionada
    $('.cities_list').each(function(i) {
        var cities_list = $(this);
        $(this).animate({
            scrollTop: cities_list.find("input:checked").offset().top - cities_list.offset().top
        })
    });

    // quando usuário seleciona "Todos os estados", seleciona todos os estados
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

    // quando usuário seleciona "Todos as cidades", seleciona todos as cidades
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

    // quando usuário seleciona "Todos os setores", seleciona todos os setores
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

    // quando seleciona o número de meses, atualiza o preço
    $('#months_select').change(function() {
       companiesCounter();
    });
    
    $('.buy div').click(function(){
        AdsConfig.finishAd();
    });
}

/**
 * Submete configuração do anúncio
 */
AdsConfig.finishAd = function() {
    var companies_count = $('#total_companies_count').val();

    // se o anúncio atinge alguma empresa
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
           url: "publicidade/configurar-campanha/pagamento",
           data: post_data,
           success: function(html) {
                $('.ads_create').html(html).fadeTo(100, 1);
                $('.buy').slideUp();
                AjaxLink.load();
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
    // se a configuração não atinge ninguém
    else {
        // verifica se o cara escolheu pelo menos um estado
        var count = 0;
        $('.regions_list input[type=checkbox]:checked').each(function() {count++;});
        if (count == 0) alert('Você precisa escolher pelo menos um estado.');
        else {
            // verifica se o cara escolheu pelo menos uma cidade
            count = 0;
            $('.cities_list input[type=checkbox]:checked').each(function() {count++;});
            if (count == 0) alert('Você precisa escolher pelo menos uma cidade.');
            else {
                // verifica se o cara escolheu pelo menos um setor
                count = 0;
                $('.sectors_list input[type=checkbox]:checked').each(function() {count++;});
                if (count == 0) alert('Você precisa escolher pelo menos um setor.');
            }
        }
    }

}

/**
 * Carrega o módulo
 */
AdsConfig.load = function() 
{
    if ($("#ads_create")) AdsConfig.create(); // se existe 
}
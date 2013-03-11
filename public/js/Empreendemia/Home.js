/** 
 * Home.js
 * Controla o carrossel na pagina principal, a busca na pagina inicial e as abas da pagina principal
 * 
 * @package Empreendemia
 * @author Rafael Almeida Erthal Hermano
 * @since 2012-03
 */

var Home = function(){}

Home.show = function(what)
{
    anchor = $('.tab_'+what);
    anchor.blur();
    $('.tabs a').removeClass('selected');
    anchor.addClass('selected');
    $('.information').hide();
    $('.information_'+what).fadeIn();
}

Home.load = function()
{
    $('.search_field').focus(function() {
        $('.fake_search').fadeOut(100);
    });
    
    $('.fake_search').click(function() {
        $('.fake_search').fadeOut(100);
        $('.search_field').focus();
    });
    
    $(".last_companies").jCarouselLite({
        auto: 5000,
        speed: 1500,
        visible: 8
    });
    
    $(".slide_show").jCarouselLite({
        auto: 15000,
        speed: 500,
        visible: 1,
        vertical: true,
        btnGo: [
        '#button_show_slide_1',
        '#button_show_slide_2',
        '#button_show_slide_3'
        ],
        afterEnd: function(a) {
            var id = $(a[0]).attr('id');
            $('.slide_show_buttons li').removeClass('selected');
            $('#button_'+id).addClass('selected');
        }
    });

    $('.businesses .business').hover(function(){
        var business = $(this);
        var business_id = business.attr('id');
        $('.testimonials').fadeIn();
        $('.testimonials .testimonial').hide();
        $('.businesses .business').fadeTo(0, 0.5);
        $('.businesses .business').find('.arrow_up').remove();
        $('.businesses .business .rate').hide();
        business.fadeTo(0, 1);
        business.find('.rate').show();
        business.append('<img src="images/ui/balloon_arrow_up.gif" class="arrow_up" />');
        $('.testimonials #show_'+business_id).show();
    });
    
    $('.tab_know').click(function(){Home.show('know')});
    $('.tab_buy').click(function(){Home.show('buy')});
    $('.tab_sell').click(function(){Home.show('sell')});
}
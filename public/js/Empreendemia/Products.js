/** 
 * Products.js
 * Controla as ações que podem ser feitas no painel de controle de produtos da empresa
 * 
 * @package Empreendemia
 * @author Rafael Almeida Erthal Hermano
 * @since 2012-03
 */

var Products = function(){};

Products.setImages = function()
{   
    $('#product_<?php echo $this->product->id ?> .thumb').html('<?php echo $this->product->image(120) ?>');
}

Products.remove = function(productId)
{
    $('#product_'+productId).fadeOut(2000);
}

Products.edit = function(productId,name,image)
{    
    $('#product_'+productId+' .name').html(name);
    $('#product_'+productId+' .thumb').html(image);
}

Products.add = function()
{

    $('.add_offer').change(function() {
        var checkbox = $(this);
        if (checkbox.is(':checked')) {
            $('.display_offer').slideDown(100);
        }
        else {
            $('.display_offer').slideUp(100);
        }
    });
}

Products.saveOrder = function()
{
    $('.save_order_button').hide();
    $('.saving_order_text').fadeIn();
    var ids = {};
    $('.products_matrix input.product_id').each(function(i) {
        var input = $(this);
        ids[input.val()] = i+1;
    });
    $.ajax({
       url: 'minha-empresa/produtos/ordenar',
       type: 'POST',
       cache: false,
       data: {
            products_ids: ids
       },
       success: function() {
            $('.saving_order_text').fadeOut();
       },
       error: function() {
           alert('Erro ao salvar ordem dos produtos. Tente novamente.');
            $('.saving_order_text').hide();
            $('.save_order_button').fadeIn();
       }
    });
}

Products.load = function()
{
    $('.products_matrix').sortable({
        update: function() {
            $('.save_order_button').fadeIn();
        },
        refreshPositions: true,
        scroll:true,
        placeholder: 'placeholder'
    });
    
    $('.products_matrix').disableSelection();
    
    $('.save_order_button').click(function(){Products.saveOrder();return false;});
}
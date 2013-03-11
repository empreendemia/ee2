/** 
 * Contacts.js
 * Controla a escrita e exibição de mensagens dos contatos
 * 
 * @package Empreendemia
 * @author Rafael Almeida Erthal Hermano
 * @since 2012-03
 */

var Contacts = function(){}

Contacts.composeMessage = function()
{    
    var form = $('#compose .form_message');
    
    if (form.hasClass('empty')) {
        form.removeClass('empty');
        form.addClass('reading');
    }
    else if (form.hasClass('hidden')) {
        form.show();
        form.removeClass('hidden');
        form.addClass('reading');
    }
    else  {
        form.hide();
        form.addClass('hidden');
        form.removeClass('reading');
    }    
}

Contacts.load = function()
{
    $('#compose .thread_title a').click(function(e){Contacts.composeMessage();return false;});        
    
    $(".reply_link").click(function() {
         var anchor = $(this);
         anchor.hide();

        var formContainer = anchor.parent().find('.form_message');
         formContainer.fadeIn(200);
         anchor.parent().parent().find('.user_thumb').fadeTo(200, 1);

        var wordCountText = formContainer.find('textarea');
        var maxLength = 1000;
        wordCountText.parent().append('<div class="word_counter"><span class="count">'+maxLength+'</span> caracteres permitidos</div>');
        Wordcount.count(wordCountText, maxLength);

      });
}
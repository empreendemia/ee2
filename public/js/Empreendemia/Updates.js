/** 
 * Updates.js
 * Controla as atualizações e o envio de mensagens na tela de novidades
 * 
 * @package Empreendemia
 * @author Rafael Almeida Erthal Hermano
 * @since 2012-03
 */

var Updates = function(){};

Updates.WordCount = function(obj)
{
    var wordCount = $(obj);
    var maxLength = 255;
    var wordCounter = $('#char_counter .counter');

    var length = wordCount.val().length;
    length = maxLength - length;

    if (length < 0) {
        wordCounter.html( '<span style="color:red">' + length + '<span>'  );
    }
    else
        wordCounter.html( length );

    wordCount.keyup(function(){
        var new_length = wordCount.val().length;
        new_length = maxLength - new_length;
        if (new_length < 0) {
            wordCount.val( wordCount.val().substr( 0, maxLength ));
            new_length++;
            wordCounter.html( '<span style="color:red">' + new_length + '<span>'  );
        }
        else
            wordCounter.html( new_length );
    });
}

Updates.SendUpdate = function(obj)
{
    $("#updates_list").html('<div class="ajax_loading"><p>recarregando updates</p></div>');
    var form = $(obj);
    $.ajax({
        type: "POST",
        url: "novidades/enviar-mensagem",
        data: {
            text: form.find('#message_update_field').val()
        },
        success: function() {
            $("#updates_list_1").load('updates');
            form.find('#message_update_field').val("");
        }
    });
    return false;
}

Updates.load = function()
{    
    Updates.updatePage(1);     
    
    //$('#message_update_field').each(Updates.WordCount(this));
    $('#message_update_form').submit(function(){Updates.SendUpdate(this);return false;});
}

Updates.updatePage = function(number) {
    $("#updates_list_"+number).html('<div class="ajax_loading"><p>carregando página #'+number+'</p></div>');
    $("#updates_list_"+number).load('updates?page='+number, 
        function() {
            $('.more_updates .button').click(function() {
                Updates.updatePage($(this).attr('nextpage'));
            })
    });    
}
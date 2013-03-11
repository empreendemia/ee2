/** 
 * FlashMessages.js
 * Carrega todos os flash messages do servidor e exibe para o usuário
 * 
 * @package Empreendemia
 * @author Mauro Ribeiro
 * @since 2012-03
 */

var FlashMessages = function(){};

/**
 * Pega os flash messages e exibe
 */
FlashMessages.get = function() {
    $.ajax({
        url: "ajax/get-flash-messages",
        async: false,
        dataType: 'json',
        success: function(data) {
            // conta quantas mensagens já tem
            var count_old_messages = $("#flash_messages ul li").length;
            $.each(data, function(index, msg) {
                // monta o html do item da mensagem
                var html = '';
                var status = '';
                var message = '';
                if (msg.hasOwnProperty("status")) {
                    status = msg.status;
                    message = msg.message;
                }
                else {
                    status = 'success';
                    message = msg;
                }
                var new_index = index + count_old_messages;
                html += '<li id="flash_message_'+new_index+'" class="'+status+'" style="top:'+new_index*2+'px;left:'+new_index*2+'px;" >';
                html += msg.message;
                html += '<span class="close">X</span>';
                html += '</li>';
                // adiciona o html
                $("#flash_messages ul").append(html);
                var li = $('#flash_message_'+new_index);
                // quando clica, fecha a mensagem
                li.click(function() {
                    li.animate({
                        opacity: 0.2,
                        top: '+=10'
                    }, 200, function() {
                        li.remove()
                    });
                });
            });
        }
    });
}

/**
 * Carrega o módulo 
 */
FlashMessages.load = function() {
    FlashMessages.get();
}
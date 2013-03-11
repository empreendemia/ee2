/** 
 * Message.js
 * Controla os inputs das mensagens
 * 
 * @package Empreendemia
 * @author Mauro Ribeiro
 * @since 2012-04
 */

var Message = function(){}

Message.load = function() {
    $('#sendmessage').each(function() {
        var form = $(this);
        var contact_start = false;
        
        form.find('input').focus(function() {
            if (contact_start == false) {
                var input = $(this);
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: 'ajax/get-user',
                    data: {
                        user_id: form.find('#to_user_id').val()
                    },
                    success: function(data) {
                        contact_start = true;
                        Tracker.ga.userEvent('contact start');
                    }
                });
            }
        });
    });
}

$(function() {
   Message.load(); 
});
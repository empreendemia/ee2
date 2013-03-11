$(function() {

    /* Ação tomada pelo botão de submit do form de resposta de mensagem (usuário logado) */
    $('.form_message form').live('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var submit = form.find('input[type=submit]');
        submit.val('Enviando mensagem...');
        submit.parent().append(' <img src="images/ui/ajax-loader-small.gif" />')
        submit.attr('disabled', 'disabled');
        form.find('input').attr('disabled', 'disabled');
        form.find('textarea').attr('disabled', 'disabled');

        var to_user_id = form.find('input[name=to_user_id]').val();
        var title = form.find('input[name=title]').val();
        var body = form.find('textarea[name=body]').val();
        var parent_id = form.find('input[name=parent_id]').val();

        $.ajax({
           type: "POST",
           url: "messages/write",
           data: {
                to_user_id: to_user_id,
                title: title,
                body: body,
                parent_id: parent_id
           },
           success: function() {
               var form_container = form.parent();
               form_container.html('<div class="message_success">Mensagem enviada com sucesso!</div>');
               if (form_container.hasClass('form_message_reply')) {
                   var text = (body + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ '<br />' +'$2');
                   form_container.html('<div class="message_reply_success">Mensagem respondida com sucesso</div>'+text);
                   form_container.hide().fadeIn();
                   form_container.find(".message_reply_success").delay(2000).slideUp();
               }
               else {
                    form_container.hide().fadeIn(500);
               }
           },
           error: function() {
               alert('Erro ao enviar mensagem');
               submit.val('Enviar mensagem');
               submit.parent().find('img').remove();
               submit.removeAttr('disabled');
               form.find('input').removeAttr('disabled');
               form.find('textarea').removeAttr('disabled');
           }
        });

    });

    /* Ação tomada pelo botão de submit do form de resposta de mensagem (usuário deslogado) */
    $('.form_message_loggedout form').live('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var submit = form.find('input[type=submit]');
        submit.val('Enviando mensagem...');
        submit.parent().append(' <img src="images/ui/ajax-loader-small.gif" />')
        submit.attr('disabled', 'disabled');
        form.find('input').attr('disabled', 'disabled');
        form.find('textarea').attr('disabled', 'disabled');

        var to_user_id = form.find('input[name=to_user_id]').val();
        var name = form.find('input[name=name]').val();
        var email = form.find('input[name=email]').val();
        var title = form.find('input[name=title]').val();
        var body = form.find('textarea[name=body]').val();

        $.ajax({
           type: "POST",
           url: "messages/logged-out-write",
           data: {
                to_user_id: to_user_id,
                name: name,
                email: email,
                title: title,
                body: body
           },
           success: function() {
               var form_container = form.parent();
               form_container.html('<div class="message_success">Mensagem enviada com sucesso!</div>');
               if (form_container.hasClass('form_message_reply')) {
                   var text = (body + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ '<br />' +'$2');
                   form_container.html('<div class="message_reply_success">Mensagem respondida com sucesso</div>'+text);
                   form_container.hide().fadeIn();
                   form_container.find(".message_reply_success").delay(2000).slideUp();
               }
               else {
                    form_container.hide().fadeIn(500);
               }
           },
           error: function() {
               alert('Erro ao enviar mensagem');
               submit.val('Enviar mensagem');
               submit.parent().find('img').remove();
               submit.removeAttr('disabled');
               form.find('input').removeAttr('disabled');
               form.find('textarea').removeAttr('disabled');
           }
        });

    });

});
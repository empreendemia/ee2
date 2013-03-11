$(function() {

    $('#feedback form').live('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var submit = form.find('input[type=submit]');
        submit.val('Enviando feedback...');
        submit.parent().append(' <img src="images/ui/ajax-loader-small.gif" />')
        submit.attr('disabled', 'disabled');
        form.find('input').attr('disabled', 'disabled');
        form.find('textarea').attr('disabled', 'disabled');

        var body = form.find('textarea[name=body]').val();
        var url = window.location+'';

        $.ajax({
           type: "POST",
           url: "feedback",
           data: {
                body: body,
                url: url
           },
           success: function(html) {
               $('#feedback').html(html);
               $('#colorbox').colorbox.resize();

           },
           error: function(html) {
               alert('Erro ao enviar feedback. Tente novamente.');
                submit.val('Tentar enviar novamente');
                submit.parent().find('img').remove();
                submit.removeAttr('disabled');
                form.find('input').removeAttr('disabled');
                form.find('textarea').removeAttr('disabled');
           }
        });

    });

});
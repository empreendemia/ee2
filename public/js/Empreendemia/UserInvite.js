/** 
 * UserInvite.js
 * Controla o formulário de convidar usuarios para empreendemia
 * 
 * @package Empreendemia
 * @author Rafael Almeida Erthal Hermano
 * @since 2012-03
 */

var UserInvite = function(){}

UserInvite.rowCount = 1;

UserInvite.addRow = function()
{
    $('.invite dl').append(
        '<dt><label>Nome</label><input type="text" name="name_'+UserInvite.rowCount+'" /></dt>'
        +'<dd><label>Email</label><input type="text" name="email_'+UserInvite.rowCount+'" /></dd>'
    );
    $('.invite input[name=name_'+UserInvite.rowCount+']').focus();
    $('.invite input[name=name_'+UserInvite.rowCount+']').parent().find('label').fadeOut();
    UserInvite.rowCount++;
}

UserInvite.load = function()
{
    $('.add_row_a').click(function(){UserInvite.addRow();return false;});

    $('.invite input').live('click focus change', function() {
        var input = $(this);
        var label = input.parent().find('label');
        label.fadeOut(50);
    });
    
    $('.invite input').live('blur', function() {
        var input = $(this);
        if (input.val() == '') {
            var label = input.parent().find('label');
            label.fadeIn();
        }
    });
    
    $('.invite label').live('click', function() {
        var label = $(this);
        var input = label.parent().find('input');
        label.fadeOut(50);
        input.focus();
    });
    
    $('.add_row .focus_add').bind('focus', function() {
        UserInvite.addRow();
    });
}
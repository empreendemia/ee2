/** 
 * Input.js
 * Todos (ou quase) inputs do sistema
 * 
 * @package Empreendemia
 * @author Mauro Ribeiro
 * @since 2012-04
 */


var Input = function(){};

/**
 * Carrega o módulo
 */
Input.load = function() {
    $('input').change(function() {
        var input = $(this);
        var form_name = input.closest('form').attr('id');
        if (form_name == 'signup' || form_name == 'login') {
            /*if (input.attr('type') == 'password') {
                Tracker.mp.track('form field fill', {'form_field_name':input.attr('name'),'form_field_type':'password','form_field_value':input.val().length, 'form_name':form_name });
            }
            else if (input.attr('type') == 'checkbox') {
                Tracker.mp.track('form field fill', {'form_field_name':input.attr('name'),'form_field_type':'checkbox','form_field_value':input.is(':checked'), 'form_name':form_name });
            }
            else {
                Tracker.mp.track('form field fill', {'form_field_name':input.attr('name'),'form_field_type':input.attr('type'),'form_field_value':input.val(), 'form_name':form_name });
            }*/
        }
    });
    $('select').change(function() {
        var input = $(this);
        var form_name = input.closest('form').attr('id');
        if (form_name == 'signup' || form_name == 'login') {
            //Tracker.mp.track('form field fill', {'form_field_name':input.attr('name'),'form_field_type':'select','form_field_value':input.val(), 'form_name':form_name });
        }
    });
}
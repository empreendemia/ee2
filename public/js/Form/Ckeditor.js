/** 
 * Ckeditor.js
 * Controla os text areas que possuem atributos extras
 * 
 * @package Empreendemia
 * @author Rafael Almeida Erthal Hermano
 * @since 2012-03
 */

var Ckeditor = function() {}

/**
 * Carrega os inputs que receberão a text area
 */
Ckeditor.load = function() {
    
    $('.ckeditor_form').each(function() {
        $(this).ckeditor();
        $(this).removeClass('ckeditor_form');
    });
}
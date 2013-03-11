/** 
 * Wordcount.js
 * Controla os inputs que terão contagem de caracteres
 * 
 * @package Empreendemia
 * @author Rafael Almeida Erthal Hermano
 * @since 2012-03
 */

var Wordcount = function(){}

Wordcount.count = function(wordCountText, maxLength) {
    var wordCounter = wordCountText.parent().find('.word_counter .count');
    var length = wordCountText.val().length;
    length = maxLength - length;

    if (length < 0) {
        wordCounter.html( '<span style="color:red">' + length + '<span>'  );
    }
    else
        wordCounter.html( length );

    wordCountText.keyup(function(){
        var new_length = wordCountText.val().length;
        new_length = maxLength - new_length;
        if (new_length < 0) {
            wordCountText.val( wordCountText.val().substr( 0, maxLength ));
            new_length++;
            wordCounter.html( '<span style="color:red">' + new_length + '<span>'  );
        }
        else
            wordCounter.html( new_length );
    });
}

/**
 * Carrega os inputs que receberão o wordcount
 */
Wordcount.load = function()
{
    $('.wordcount').each(function() {
        var wordCountText = $(this);
        var maxLength = 1000;

        if(wordCountText.hasClass('wc250')) maxLength = 250;
        else if(wordCountText.hasClass('wc100')) maxLength = 100;
        wordCountText.parent().append('<div class="word_counter"><span class="count">'+maxLength+'</span> caracteres permitidos</div>');
        Wordcount.count(wordCountText, maxLength);
        
        wordCountText.removeClass('wordcount');
    });
}
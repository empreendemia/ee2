function wordCount(wordCountText, maxLength) {
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

$(function() {

	$('.wordcount').each(function() {
		var wordCountText = $(this);
		var maxLength = 1000;

        if(wordCountText.hasClass('wc250')) maxLength = 250;
        else if(wordCountText.hasClass('wc100')) maxLength = 100;
        wordCountText.parent().append('<div class="word_counter"><span class="count">'+maxLength+'</span> caracteres permitidos</div>');
        wordCount(wordCountText, maxLength);
	});

});
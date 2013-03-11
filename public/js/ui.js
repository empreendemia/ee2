$(function(){
    $(".modal").colorbox({
        scrolling:false,
        speed:100,
        initialWidth:200,
        initialHeight:160,
        onOpen:function(){
            $("#cboxTitle").hide();
            $("#cboxContent").hide();
        },
        onComplete: function() {
            $("#cboxTitle").fadeIn(200);
            $("#cboxContent").fadeIn(200);
        }
    });
    $(".modal_iframe").colorbox({
        iframe:true,
        scrolling:false,
        speed:100,
        initialWidth:200,
        initialHeight:160,
        innerWidth:200,
        innerHeight:160,
        onOpen: function() {
            $("#cboxTitle").hide();
            $("#cboxContent").hide();
        },
        onComplete: function() {
            $("#cboxTitle").fadeIn(200);
            $("#cboxContent").fadeIn(200);
        }
    });
    $(".modal_iframe_scroll").colorbox({
        iframe:true,
        scrolling:true,
        speed:100,
        initialWidth:200,
        initialHeight:160,
        innerWidth:200,
        innerHeight:160,
        onOpen: function() {
            $("#cboxTitle").hide();
            $("#cboxContent").hide();
        },
        onComplete: function() {
            $("#cboxTitle").fadeIn(200);
            $("#cboxContent").fadeIn(200);
        }
    });
    $(".modal_login").colorbox({
        href:"#user_auth_login",
        inline:true,
        speed:100,
        initialWidth:200,
        initialHeight:160,
        onOpen: function() {
            $("#cboxTitle").hide();
            $("#cboxContent").hide();
        },
        onComplete: function() {
            $("#cboxTitle").fadeIn(200);
            $("#cboxContent").fadeIn(200);
            $('#user_auth_login input[name=login]').focus();
        }
    });

    $("#flash_messages ul li").hide();
    $("#flash_messages").show();

    $("#flash_messages ul li").each(function(i) {
        var item = $(this);
        var index = i+1;
        item.delay(i*1500).append('<a href="javascript:void" class="close">X</a>').fadeIn(200);
    });

    $("#flash_messages ul li a.close").click(function(i) {
        var item = $(this);
        item.parent().fadeOut(100);
    });

    $(".tip_tool, .tip_tool_top").tipTip({
        defaultPosition: 'top',
        delay: 500,
        fadeIn: 100,
        fadeOut: 400
    });

    $(".tip_tool_bottom").tipTip({
        defaultPosition: 'bottom',
        delay: 500,
        fadeIn: 100,
        fadeOut: 400
    });

    $(".tip_tool_form").tipTip({
        activation: 'focus',
        defaultPosition: 'right',
        delay: 100,
        fadeIn: 100,
        fadeOut: 400
    });

    $(".modal_content .tip_tool_form").tipTip({
        activation: 'focus',
        defaultPosition: 'right',
        delay: 100,
        fadeIn: 100,
        fadeOut: 400
    });

    $(".tip_tool_right").tipTip({
        defaultPosition: 'right',
        delay: 500,
        fadeIn: 100,
        fadeOut: 400
    });
});
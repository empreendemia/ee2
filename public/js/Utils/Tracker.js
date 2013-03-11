/** 
 * Tracker.js
 * Sistema de tracking do usuário
 * 
 * @package Empreendemia
 * @author Mauro Ribeiro
 * @since 2012-03
 */


var Tracker = function(){};

/**
 * Carrega o módulo
 */
Tracker.load = function() {
    
    /**
     * Substitui aspas simples por duplas
     */
    function replaceQuotes(string) {
        while (typeof(string) == 'string' && string.indexOf("\'") != -1) {
            string = string.replace("\'", '\"');
        }
        return string;
    }
    
    // associa um evento para toda tag html com a propriedade event-name
    $("*[event-name]").each(function() {
        // pega o nome do evento
        var event_name = $(this).attr('event-name');
        // pega as propriedades do evento em JSON
        var event_properties = (typeof($(this).attr('event-properties')) === 'undefined') ? '' : $.parseJSON(replaceQuotes($(this).attr('event-properties')));
        // pega o tipo de evento (click por padrão)
        var event_type = $(this).attr('event-type') ? $(this).attr('event-type') : 'click';
        
        // se tiver alguma propriedade, coloca no nome do evento
        $.each(event_properties, function(index, value) {
           event_name += ": " + value;
        });
          
        // se for do tipo load
        if (event_type == 'load') {
            Tracker.ga.userEvent(event_name);
        }
        // se for de qualquer outro tipo que o jQuery reconheça
        else {
            $(this).bind(event_type, function(){
                Tracker.ga.userEvent(event_name);
            });
        }
        
        // remove atributos para evitar bubbles
        $(this).removeAttr("event-name");
        $(this).removeAttr("event-properties");
        $(this).removeAttr("event-type");
    });
}

/**
 * Google Analytics
 */
Tracker.ga = function(){};

/**
 * Carrega o módulo do Google Analytics
 */
Tracker.ga.load = function(){
    
    var ga = document.createElement('script');
    ga.type = 'text/javascript';
    ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    
    var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(ga, s);      
    _gaq.push(['_setAccount', 'UA-7274902-2'])
}

/**
 * Pega os eventos da SESSION e submete tudo 
 */
Tracker.ga.pushEvents = function() {
    $.ajax({
        url: "ajax/get-events",
        async: false,
        dataType: 'json',
        success: function(data) {
            $.each(data, function(index, event) {
                Tracker.ga.push(event);
            });
        }
    });
}

Tracker.ga.push = function(event) {
    if (window.location.host.indexOf('empreendemia.com.br') >= 0) {
        if (
            event.hasOwnProperty('category') &&
            event.hasOwnProperty('action') && 
            event.hasOwnProperty('label') &&
            event.hasOwnProperty('value')
        )
            _gaq.push(['_trackEvent', event.category, event.action, event.label, event.value]);
        else if (
            event.hasOwnProperty('category') &&
            event.hasOwnProperty('action') && 
            event.hasOwnProperty('label')
        )
            _gaq.push(['_trackEvent', event.category, event.action, event.label]);
        else if (
            event.hasOwnProperty('category') &&
            event.hasOwnProperty('action')
        )
            _gaq.push(['_trackEvent', event.category, event.action]);
        else if (
            event.hasOwnProperty('category')
        )
            _gaq.push(['_trackEvent', event.category]);
    }
    else {
        if (
            event.hasOwnProperty('category') &&
            event.hasOwnProperty('action') && 
            event.hasOwnProperty('label') &&
            event.hasOwnProperty('value')
        )
            console.log('_gaq.push([_trackEvent, '+event.category+', '+event.action+', '+event.label+', '+event.value+'])');
        else if (
            event.hasOwnProperty('category') &&
            event.hasOwnProperty('action') && 
            event.hasOwnProperty('label')
        )
            console.log('_gaq.push([_trackEvent, '+event.category+', '+event.action+', '+event.label+'])');
        else if (
            event.hasOwnProperty('category') &&
            event.hasOwnProperty('action')
        )
            console.log('_gaq.push([_trackEvent, '+event.category+', '+event.action+'])');
        else if (
            event.hasOwnProperty('category')
        )
            console.log('_gaq.push([_trackEvent, '+event.category+'])');
        else {
            console.log('_gaq.push([_trackEvent])')
        }
    }
}

/**
 * Submete um pageview 
 */
Tracker.ga.pageview = function() {    
    if (window.location.host.indexOf('empreendemia.com.br') >= 0) {
        _gaq.push(['_trackPageView', Empreendemia.Navigation.url]);
        _gaq.push(['_trackPageLoadTime']);
    }
    else {
        console.log('_gaq.push([_trackPageView, '+Empreendemia.Navigation.url+'])');
        console.log('_gaq.push([_trackPageLoadTime])');
    }
}

/**
 * Submete um evento genérico
 */
Tracker.ga.event = function (category, action, label) {
    _gaq.push(['_trackEvent', category, action, label]);
}

/**
 * Submete um evento do usuário
 */
Tracker.ga.userEvent = function(action) {
    $.ajax({
        url: "ajax/get-auth",
        dataType: 'json',
        success: function(data) {
            userId = null;
            if (data.hasOwnProperty('id')) userId = data.id; 
            else if (data.hasOwnProperty('ip'))  userId = 'not logged: '+data.ip;
            
            if (window.location.host.indexOf('empreendemia.com.br') >= 0) {
                _gaq.push(['_trackEvent', "User", action, userId]);
            }
            else {
                console.log('_gaq.push(["_trackEvent", "User", '+action+', '+userId+']);');
            }
        }
    });
}





/**
 * Mixpanel
 * @author Mauro Ribeiro
 * @since 2012-03-30
 */
Tracker.mp = function(){};

/**
 * Inicializa a conversação com o Mixpanel
 * @author Mauro Ribeiro
 * @since 2012-03-30
 */
Tracker.mp.load = function(){
   
    if (window.location.host.indexOf('empreendemia.com.br') >= 0) {
        (function(d,c){var a,b,g,e;a=d.createElement("script");a.type="text/javascript";
        a.async=!0;a.src=("https:"===d.location.protocol?"https:":"http:")+
        '//mixpanel.com/site_media/js/api/mixpanel.2.js';b=d.getElementsByTagName("script")[0];
        b.parentNode.insertBefore(a,b);c._i=[];c.init=function(a,d,f){var b=c;
        "undefined"!==typeof f?b=c[f]=[]:f="mixpanel";g=['disable','track','track_links',
        'track_forms','register','register_once','unregister','identify','name_tag','set_config'];
        for(e=0;e<g.length;e++)(function(a){b[a]=function(){b.push([a].concat(
        Array.prototype.slice.call(arguments,0)))}})(g[e]);c._i.push([a,d,f])};window.mixpanel=c}
        )(document,[]);
        mixpanel.init("fcf67da2ecbe515e604349a6b0d49db1");
    }
    
    Tracker.mp.register_once('landing_page', window.location.href);
}

/**
 * Pega os eventos da SESSION do PHP e joga tudo pro Mixpanel
 * @author Mauro Ribeiro
 * @since 2012-03-30
 */
Tracker.mp.pushTracks = function() {
    $.ajax({
        url: "ajax/get-tracks",
        async: false,
        dataType: 'json',
        success: function(data) {
            $.each(data, function(index, track) {
                switch(track.type) {
                    case 'identify':
                        Tracker.mp.identify(track.id);
                        Tracker.mp.name_tag(track.name_tag);
                        break;
                    case 'register':
                        if (track.hasOwnProperty('name') && track.hasOwnProperty('value'))
                            Tracker.mp.register(track.name, track.value);
                        else if (track.hasOwnProperty('properties'))
                            Tracker.mp.register(track.properties);
                        break;
                    case 'register_once':
                        if (track.hasOwnProperty('name') && track.hasOwnProperty('value'))
                            Tracker.mp.register_once(track.name, track.value);
                        else if (track.hasOwnProperty('properties'))
                            Tracker.mp.register_once(track.properties);
                        break;
                    case 'track':
                        if (track.properties != null)
                            Tracker.mp.track(track.event, track.properties);
                        else
                            Tracker.mp.track(track.event);
                        break;
                    default:
                        console.log('mixpanel.'+track.type+' does not exist');
                        break;
                }        
            });
        }
    });
}

Tracker.mp.register = function(data, value) {
    if (window.location.host.indexOf('empreendemia.com.br') >= 0) {
        if (typeof(data) != 'object' && value != null) {
            mixpanel.register(data, value)
        }
        else {
            mixpanel.register(data)
        }
    }
    else {
        if (typeof(data) != 'object' && value != null) {
            console.log('mixpanel.register('+data+','+value+')');
        }
        else {
            console.log('mixpanel.register('+data+')');
            console.log(data);
        }
    }
}

Tracker.mp.register_once = function(data, value) {
    if (window.location.host.indexOf('empreendemia.com.br') >= 0) {
        if (typeof(data) != 'object' && value != null) {
            mixpanel.register_once(data, value)
        }
        else {
            mixpanel.register_once(data)
        }
    }
    else {
        if (typeof(data) != 'object' && value != null) {
            console.log('mixpanel.register_once('+data+','+value+')');
        }
        else {
            console.log('mixpanel.register_once('+data+')');
            console.log(data);
        }
    }
}


Tracker.mp.identify = function(id) {
    if (window.location.host.indexOf('empreendemia.com.br') >= 0)
        mixpanel.identify(id);
    else
        console.log('mixpanel.identify('+id+')');
}


Tracker.mp.track = function(event, properties) {
    if (window.location.host.indexOf('empreendemia.com.br') >= 0) {
        if (properties != null && typeof(properties) == 'object') 
            mixpanel.track(event, properties);
        else
            mixpanel.track(event);
    }
    else {
        if (properties != null && typeof(properties) == 'object') {
            console.log('mixpanel.track('+event+','+properties+')');
            console.log(properties);
        }
        else {
            console.log('mixpanel.track('+event+')');
        }
    }
}

Tracker.mp.name_tag = function (tag) {
    if (window.location.host.indexOf('empreendemia.com.br') >= 0)
        mixpanel.name_tag(tag);
    else
        console.log('mixpanel.name_tag('+tag+')');
}
/**
 * Armazenamento do chat
 *
 * @author  Mauro Ribeiro
 * @since   2014-01-28
 */
EeChatHistory = {}

if (typeof(Storage) !== 'undefined') {
    if (!localStorage.hasOwnProperty('eeChatHistory')) {
        localStorage.eeChatHistory = JSON.stringify({});
    }
}

EeChatHistory.exists = function () {
    if (typeof(Storage) !== 'undefined') {
        return true;
    } else {
        return false;
    }
}

EeChatHistory.getOpenThreads = function () {
    if (EeChatHistory.exists()) {
        var threads = [];
        var store = JSON.parse(localStorage.eeChatHistory);
        for (var i in store) {
            if (store[i].open) {
                threads[i] = store[i];
            }
        }
        return threads;
    } else {
        return {};
    }
}

EeChatHistory.get = function (i) {
    if (EeChatHistory.exists()) {
        return JSON.parse(localStorage.eeChatHistory)[i];
    } else {
        return {};
    }
}

EeChatHistory.set = function (i, data) {
    var store = JSON.parse(localStorage.eeChatHistory);
    if (EeChatHistory.exists()) {
        store[i] = data;
        localStorage.eeChatHistory = JSON.stringify(store);
    }
}

EeChatHistory.setOpen = function (i, data) {
    var store = JSON.parse(localStorage.eeChatHistory);
    if (EeChatHistory.exists()) {
        store[i].open = data;
        localStorage.eeChatHistory = JSON.stringify(store);
    }
}

EeChatHistory.addMessage = function (i, message) {
    var store = JSON.parse(localStorage.eeChatHistory);
    if (EeChatHistory.exists()) {
        store[i].messages.push(message);
        localStorage.eeChatHistory = JSON.stringify(store);
    }
}

/**
 * Classe do histórico do chat
 *
 * @author  Mauro Ribeiro
 * @since   2014-01-28
 */
EeChatThread = function (user) {
    if (EeChatHistory.exists()) {
        if (!EeChatHistory.get(user.userId)) {
            EeChatHistory.set(user.userId, {
                open : true,
                messages : [],
                user : user
            });
        }
    }
    
    this.addMessage = function (data) {
        if (EeChatHistory.exists()) {
            EeChatHistory.addMessage(user.userId, data);
        }
    }
    
    this.getMessages = function () {
        if (EeChatHistory.exists()) {
            return EeChatHistory.get(user.userId).messages;
        } else {
            return [];
        }
    }
    
    this.setOpen = function (status) {
        if (EeChatHistory.exists()) {
            EeChatHistory.setOpen(user.userId, status);
        }
    }
    
    this.getOpen = function () {
        if (EeChatHistory.exists()) {
            return EeChatHistory.get(user.userId).open;
        } else {
            return false;
        }
    }
}

/**
 * Classe da Janela de Chat
 *
 * @author  Mauro Ribeiro
 * @since   2014-01-24
 */
EeChatWindow = function (user, socket) {
    /**
     * Thread
     */
    var thread = new EeChatThread(user);
    /**
     * Elemento principal
     */
    var html = document.createElement('div');
    /**
     * Elemento html do header
     */
    var htmlHeader = document.createElement('div');
    /**
     * Elemento html do conteiner das mensagens
     */
    var htmlMessages = document.createElement('div');
    /**
     * Elemento html do status do usuáo
     */
    var htmlStatus = document.createElement('div');
    /**
     * Elemento html do input de mensagens
     */
    var htmlInput = document.createElement('input');
    /**
     * Elemento html do input de mensagens
     */
    var htmlClose = document.createElement('div');

    var that = this;

    html.appendChild(htmlHeader);
    html.appendChild(htmlMessages);
    html.appendChild(htmlStatus);
    html.appendChild(htmlInput);
    html.appendChild(htmlClose);
    
    html.setAttribute('class', 'ee-chat-window');
    htmlHeader.setAttribute('class', 'ee-chat-header');
    htmlMessages.setAttribute('class', 'ee-chat-messages');
    htmlStatus.setAttribute('class', 'ee-chat-status');
    htmlInput.setAttribute('class', 'ee-chat-input');
    htmlInput.setAttribute('type', 'text');
    htmlInput.setAttribute('placeholder', 'digite uma mensagem');
    htmlClose.setAttribute('class', 'ee-chat-close');
    
    htmlStatus.innerHTML = 'o usuario desconectou';
    
    if (user.companyName)
    {
        htmlHeader.innerHTML = '<span class="ee-chat-header-user-name">'+user.userName + '</span><span class="ee-chat-header-company-name">'+user.companyName+'</span>';
    }
    else
    {
        htmlHeader.innerHTML = '<span class="ee-chat-header-user-name">'+user.userName + '</span>';
    }
    
    
    /**
     * Pega o html
     *
     * @author  Mauro Ribeiro
     * @since   2014-01-24
     */
    this.getHtml = function () {
        return html;
    }
    
    /**
     * Adiciona mensagem
     *
     * @author  Mauro Ribeiro
     * @since   2014-01-24
     */
    this.addMessage = function (data, save) {
        if (data.self) {
            htmlMessages.innerHTML = htmlMessages.innerHTML + '<div class="ee-chat-message my-message"><span class="ee-chat-message-user">'+data.userName+'</span><span class="ee-chat-message-text">'+data.message.replace(/(<([^>]+)>)/ig,"");+'</span></div>';
        } else {
            htmlMessages.innerHTML = htmlMessages.innerHTML + '<div class="ee-chat-message"><span class="ee-chat-message-user">'+data.userName+'</span><span class="ee-chat-message-text">'+data.message.replace(/(<([^>]+)>)/ig,"");+'</span></div>';
        }
        htmlMessages.scrollTop = htmlMessages.scrollHeight;
        this.reconnect();
        this.open();
        if (save) {
            thread.addMessage(data);
        }
    }
    
    /**
     * Maximiza a janela
     *
     * @author  Mauro Ribeiro
     * @since   2014-01-24
     */
    this.open = function () {
        html.setAttribute('class', html.getAttribute('class').replace('ee-chat-window-closed', ''));
    }
    
    /**
     * Minimiza a janela
     *
     * @author  Mauro Ribeiro
     * @since   2014-01-24
     */
    this.close = function () {
        html.setAttribute('class', html.getAttribute('class') + ' ee-chat-window-closed');
        thread.setOpen(false);
    }
    
    /**
     * Mensagem de usuáo desconectado e desabilita janela
     *
     * @author  Mauro Ribeiro
     * @since   2014-01-24
     */
    this.disconnect = function () {
        htmlInput.setAttribute('disabled', 'disabled');
        htmlStatus.setAttribute('class', 'ee-chat-status ee-chat-status-offline');
        html.setAttribute('class', html.getAttribute('class') + ' ee-chat-window-offline');
        setTimeout(function () { 
            EeChat.companyStatus(user.companyId, function (data) {
                if (user.userId == data.userId) { 
                    that.reconnect();
                } else {
                    setTimeout(function () {
                        EeChat.companyStatus(user.companyId, function (data) {
                            if (user.userId == data.userId) that.reconnect();
                        });
                    }, 3000);
                }
            });
        }, 3000);
    }
    
    /**
     * Se o usuáo volta
     *
     * @author  Mauro Ribeiro
     * @since   2014-01-24
     */
    this.reconnect = function () {
        htmlInput.removeAttribute('disabled');
        htmlStatus.setAttribute('class', 'ee-chat-status');
        html.setAttribute('class', html.getAttribute('class').replace('ee-chat-window-offline', ''));
    }
    
    this.getThread = function () {
        return thread;
    }
    
    /**
     * Evento de enviar mensagem
     *
     * @author  Mauro Ribeiro
     * @since   2014-01-24
     */
     htmlInput.addEventListener('keydown', function (e) {
        if(e.which == 13 && htmlInput.value.length > 0) {
            socket.emit('send message', {
                to : user.userId,
                message : htmlInput.value
            });
            that.addMessage({ userName : 'Eu', message : htmlInput.value, self : true }, true);
            htmlMessages.scrollTop = htmlMessages.scrollHeight;
            htmlInput.value = '';
        }
     }, false);
     
     htmlClose.addEventListener('click', this.close, false);
    
    thread.setOpen(true);
    var messages = thread.getMessages();
    for (var i in messages) {
        this.addMessage(messages[i], false);
    }
}

/**
 * Chat Class
 *
 * @author  Mauro Ribeiro
 * @since   2014-01-24
 */
EeChat = function (user) {
    /**
     * Socket IO
     */
    var socket = io.connect('http://chat.empreendemia.com.br');
    /**
     * Chat windows container
     */
    var container = document.getElementById('ee-chat');
    /**
     * Open windows
     */
    var windows = {};
    /**
     * Count new messages
     */
    var count = 0;
    /**
     * Flashing title handler
     */
    var interval;
    /**
     * Title of the page
     */
    var pageTitle;

    socket.on('connect', function () {
        socket.emit('new user', user);
     
        socket.on('receive message', function (data) {
            if (!windows.hasOwnProperty(data.userId)) {
                windows[data.userId] = new EeChatWindow(data, socket);
                container.insertBefore(windows[data.userId].getHtml(), container.firstChild);
            }
            windows[data.userId].addMessage(data, true);
            count++;
            if (count == 1) {
                interval = setInterval(flashTitle, 1000);
            }
        });
     
        socket.on('user disconnected', function (data) {
            if (windows.hasOwnProperty(data.userId)) {
                windows[data.userId].disconnect();
            }
        });
    });

    pageTitle = document.title;

    $('#body').mouseover(function () {
        document.title = pageTitle;
        clearInterval(interval);
        count = 0;
    });    

    /**
     * Automatic open a chat window
     *
     * @author  Mauro Ribeiro
     * @since   2014-01-24
     */
    this.attend = function (companyId) {
        $.getJSON('http://chat.empreendemia.com.br/company-status/'+companyId, function (data) {
            if (data.status === 'online') {
                var attendant = data;
                attendant.message = 'Olá '+user.userName+',  em que posso ajudar?';
                if (!windows.hasOwnProperty(attendant.userId)) {
                    windows[attendant.userId] = new EeChatWindow(attendant, socket);
                    windows[attendant.userId].addMessage(attendant, false);
                    container.insertBefore(windows[attendant.userId].getHtml(), container.firstChild);
                }
            }
       });
    }

    /**
     * Flash title when receive a new message
     *
     * @author  Mauro Ribeiro
     * @since   2014-01-27
     */
    var flashTitle = function () {
        if (pageTitle == document.title) document.title = (count > 1) ? '('+count + ' mensagens)' : '('+count + ' mensagem)';
        else document.title = pageTitle;
    }

    /**
     * Open old windows
     *
     * @author  Mauro Ribeiro
     * @since   2014-01-27
     */
    this.getOldMessages = function () {
        var openThreads = EeChatHistory.getOpenThreads();
        for (var i in openThreads) {
            if (!windows.hasOwnProperty(openThreads[i].user.userId)) {
                windows[openThreads[i].user.userId] = new EeChatWindow(openThreads[i].user, socket);
                container.insertBefore(windows[openThreads[i].user.userId].getHtml(), container.firstChild);
                setTimeout(function () { windows[openThreads[i].user.userId].disconnect() }, 50);
            }
        }
    }
}

EeChat.companyStatus = function (companyId, callback) {
    $.getJSON('http://chat.empreendemia.com.br/company-status/'+companyId, function (data) {
        callback(data);
    });
}

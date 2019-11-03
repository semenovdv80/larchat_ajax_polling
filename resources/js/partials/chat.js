$(function () {

    var messagesList = $('#msg-list');
    var usersList = $('#users-list');
    var chatRoom = $('.room_list.active_chat');
    var currentPage;
    var roomId = 0;
    var userId = 0;

    if (typeof chatRoom !== 'undefined') {
        roomId = chatRoom.data('id');
        roomUsers(roomId);
    }

    // choose chat room
    $('.room_list').on('click', function () {
        userId = 0;
        roomId = $(this).data('id');
        $('.room_list').removeClass('active_chat');
        $(this).addClass('active_chat');
        roomUsers(roomId);
    });

    // choose user for chat
    $('#users-list').on('click', '.chat_list', function () {
        userId = $(this).data('id');
        $('.chat_list').removeClass('active_chat');
        $(this).addClass('active_chat');
        roomMessages();
    });

    // send message
    $('.chat-send-button').on('click', function (e) {
        e.preventDefault();
        var msg = $('#chat-msg-input').val();
        if (typeof(roomId) == "undefined" || userId === 0 || msg === '') {
            return false;
        }
        $.post({
            url: '/api/chat/send',
            data:{room_id:roomId, user_id:userId, message: msg, api_token:api_token},
            success: function (response) {
                if (response.success) {
                    currentPage = 1;
                    $('#chat-msg-input').val('');
                    roomMessages();
                }
            }
        });
    });

    // get list of room users
    function roomUsers(roomId) {
        $.post({
            url: '/api/chat/users?api_token='+api_token,
            data:{room_id:roomId},
            success: function (response) {
                if (response.success) {
                    usersList.html('');
                    messagesList.html('');
                    $.each(response.data.data, function(index, user) {
                        usersList.append('<div data-id='+ user.id +' class="chat_list'+ (index === 0 ? ' active_chat' : '') +'">' +
                            '<div class="chat_user"><div class="chat_img">' +
                            '<img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"></div>' +
                            '<div class="chat_ib"><h5>'+ user.name +' <span class="chat_date">Dec 25</span></h5>' +
                            '<p>'+ user.email +'</p></div></div></div>');

                        //get messages from first
                        if (index === 0) {
                            userId = user.id;
                            roomMessages();
                        }
                    });
                }
            }
        });
    }

    //get list of chat messages
    function roomMessages() {
        $.post({
            url: '/api/chat/messages?api_token='+api_token,
            data:{room_id:roomId, receiver_id: userId},
            success: function (response) {
                messagesList.html('');
                if (response.success) {
                    messagesShow(response);
                }
            },
            complete: function () {
                scrollToBottom();
            }
        });
    }

    //show message block
    function messagesShow(response) {
        data = response.data;
        currentPage = data.current_page;
        var messages = data.data;
        $.each(messages, function(index, msg) {
            if (msg.sender_id === parseInt(auth_id)) {
                messagesList.prepend('<div data-page = '+ currentPage +' class="outgoing_msg">' +
                    '<div class="sent_msg"><p>'+ msg.message +'</p>' +
                    '<span class="time_date"> 11:01 AM | June 9</span>' +
                    '</div></div>')
            } else {
                messagesList.prepend('<div data-page = '+ currentPage +' class="incoming_msg">' +
                    '<div class="incoming_msg_img"><img src="https://ptetutorials.com/images/user-profile.png"></div>' +
                    '<div class="received_msg"><div class="received_width_msg"><p>'+ msg.message +'</p>' +
                    '<span class="time_date"> 11:01 AM | June 9</span>' +
                    '</div></div></div>');
            }
        });

        if (data.next_page_url) {
            messagesList.prepend('<a class="chat-page-next" href="'+data.next_page_url+'"></a>');
        }
    }

    //Ajax Autoload lids to column on scrolling
    messagesList.scroll(function () {
        var elem = $(this);
        var scrollPos  = elem.scrollTop();
        var elemInnerHeight = elem.innerHeight();
        var elemScrollHeight  = elem.get(0).scrollHeight;

        //check if scrolled to bottom of div element
        if (scrollPos === 0) {
            //search for next link
            messagesList.stop();
            var link = $(this).find('a.chat-page-next:visible').first();
            //show image download process
            link.html("<img src='/img/ajax-loaders/ajax-loader-7.gif' alt='Loading' />");
            if (link.is(':visible')) {
                $.post({
                    url: link.attr('href'),
                    data:{room_id:roomId, receiver_id: userId, api_token: api_token},
                    success: function (response) {
                        messagesShow(response);
                        messagesList.scrollTop(link.offset().top-elemInnerHeight/2+60);
                        link.hide();
                    }
                });
            }
        }

        if (scrollPos + elemInnerHeight >= (elemScrollHeight-100)) {
            currentPage = 1;
        }
    });

    //refresh chat messages window
    function refreshMessages() {
        $.post({
            url: '/api/chat/messages?api_token='+api_token,
            data: {room_id:roomId, receiver_id:userId, page: currentPage},
            success: function (response) {
                if (currentPage > 1) {
                    return false;
                }
                messagesList.html('');
                if (response.success && userId > 0) {
                    messagesShow(response);
                }
            },
            complete: function () {
                setTimeout(refreshMessages, 1500);
            }
        });
    }
    refreshMessages();

    //scroll chat to bottom
    function scrollToBottom() {
        //messagesList.stop().animate({ scrollTop: messagesList[0].scrollHeight}, 1000);
        messagesList.scrollTop(messagesList.get(0).scrollHeight);
    }
});
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title></title>
</head>
<body>
    <div id="app">
        This is Laravel Broadcasting Test
    </div>
    <div>
        <textarea cols="100" rows="10" id="message"></textarea>
        <input type="button" value="send" onclick="sendMessage()">
        <input type="hidden" id="user" value="{{ $name }}">
    </div>
    <hr>
    留言區域
    <div id="comment">
        
    </div>
    <script src="/js/app.js"></script>
    <script>
        function sendMessage()
        {
            $.ajax({
                url: '/test/channelSendMessage',
                type: 'post',
                data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        user: $('#user').val(),
                        message: $('#message').val()
                    },
                dataType: 'json',
                success:function(response) {
                    console.log(response);
                    if (response.status == 1) {
                        $('#message').val('');
                    }
                },
                error:function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
        new Vue({
            el: '#app',
            created() {
                Echo.channel('public-channel')
                    .listen('PushMessage', (e) => {
                        let html = this.makeHTML(e);
                        this.insertContent(html);
                });
            },
            methods: {
                makeHTML: function(e) {
                    return '<p>'+ e.user + ':' + e.message + '</p>';
                },
                insertContent: function(html) {
                    let $main = $('#comment');
                    $main.append(html);
                }
            }
        });
    </script>
</body>
</html>
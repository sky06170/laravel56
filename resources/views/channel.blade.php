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
    <div id="content">
        <hr>
    </div>
    <script src="/js/app.js"></script>
    <script>
        new Vue({
            el: '#app',
            created() {
                Echo.channel('public-channel')
                    .listen('PushMessage', (e) => {
                        let html = '';
                        html += '<p>' + e.message + '</p>';
                        $('hr').after(html);
                });
            }
        });
    </script>
</body>
</html>
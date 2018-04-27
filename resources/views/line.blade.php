<!doctype html>
<html lang="tw">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">

            <div class="content">
                <div class="title m-b-md">
                    Line Message
                    <form id="MsgForm">
                        @method('post')
                        @csrf
                        <div><textarea name="message" cols="100" rows="10"></textarea></div>
                        <div>
                            <input type="button" value="發送訊息" onclick="sendMsg()">
                            <input type="button" value="發送圖片" onclick="sendImage()">
                            <input type="button" value="發送按鈕模板" onclick="sendButtonTemplate()">
                            <input type="button" value="發送確認模板" onclick="sendConfirmTemplate()">
                            <input type="button" value="發送Carousel模板" onclick="sendCarouselTemplate()">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-1.11.3.js"></script>
        <script>
            //jQuery 寫法
            function sendMsg()
            {
                $.ajax({
                    url:'/line/sendText',
                    type:'post',
                    data: $('#MsgForm').serialize(),
                    dataType:'json',
                    success:function(response)
                    {
                        if(response.status){
                            $('textarea[name=message]').val('');
                            console.log('sendMsg success!');
                        }
                    },
                    error:function(xhr)
                    {
                        console.log(xhr.responseText());
                    }
                });
            }
            function sendImage()
            {
                $.ajax({
                    url:'/line/sendImage',
                    type:'post',
                    data: $('#MsgForm').serialize(),
                    dataType:'json',
                    success:function(response)
                    {
                        if(response.status){
                            console.log('sendImage success!');
                        }
                    },
                    error:function(xhr)
                    {
                        console.log(xhr.responseText());
                    }
                });
            }
            function sendButtonTemplate()
            {
                $.ajax({
                    url:'/line/sendButtonTemplate',
                    type:'post',
                    data: $('#MsgForm').serialize(),
                    dataType:'json',
                    success:function(response)
                    {
                        if(response.status){
                            console.log('sendButtonTemplate success!');
                        }
                    },
                    error:function(xhr)
                    {
                        console.log(xhr.responseText());
                    }
                });
            }
            function sendConfirmTemplate()
            {
                $.ajax({
                    url:'/line/sendConfirmTemplate',
                    type:'post',
                    data: $('#MsgForm').serialize(),
                    dataType:'json',
                    success:function(response)
                    {
                        if(response.status){
                            console.log('sendConfirmTemplate success!');
                        }
                    },
                    error:function(xhr)
                    {
                        console.log(xhr.responseText());
                    }
                });
            }
            function sendCarouselTemplate()
            {
                $.ajax({
                    url:'/line/sendCarouselTemplate',
                    type:'post',
                    data: $('#MsgForm').serialize(),
                    dataType:'json',
                    success:function(response)
                    {
                        if(response.status){
                            console.log('sendCarouselTemplate success!');
                        }
                    },
                    error:function(xhr)
                    {
                        console.log(xhr.responseText());
                    }
                });
            }
        </script>
    </body>
</html>

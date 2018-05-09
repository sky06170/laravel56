<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <title>Youtube Embedder</title>
    <style type="text/css">
        html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,center,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,embed,figure,figcaption,footer,header,hgroup,menu,nav,output,ruby,section,summary,time,mark,audio,video{border:0;font-size:100%;font:inherit;vertical-align:baseline;margin:0 auto;padding:0}article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{display:block}body{line-height:1}ol,ul{list-style:none}blockquote,q{quotes:none}blockquote:before,blockquote:after,q:before,q:after{content:none}table{border-collapse:collapse;border-spacing:0}
        html,body {width:100%;height:0;padding-bottom: 56.25%;}
        iframe {position: absolute;width: 100%;height: 100%;
}}
    </style>
    <script async src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/3.5.11/iframeResizer.contentWindow.min.js"></script>
    <script id="youtube_api" src="https://www.youtube.com/iframe_api"></script>
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-W4923N7');</script>
<!-- End Google Tag Manager -->
</head>
<body>
    <div id="player"></div>
    <script type="text/javascript">
        var player;
        function onYouTubeIframeAPIReady() {
            player = new YT.Player('player', {
                videoId: "{{ request('v') }}", // YouTube 影片ID
                height: "{{ request('h', '100%') }}",
                width: "{{ request('w', '100%') }}",
                playerVars: {
                    rel: {{ request('rel', '0') }}, // 播放結束後推薦其他影片
                    controls: {{ request('controls', '1') }}, // 在播放器顯示暫停／播放按鈕
                    start: {{ request('start', '0') }}, //指定起始播放秒數
                    end: {{ request('end', 'null') }}, //指定結束播放秒數
                    autoplay: {{ request('autoplay', '0') }}, // 在讀取時自動播放影片
                    loop: {{ request('loop', '0') }}, // 讓影片循環播放
                    showinfo: 0, // 隱藏影片標題
                    modestbranding: 1, // 隱藏YouTube Logo
                    cc_load_policty: 0, // 隱藏字幕
                    iv_load_policy: 3 // 隱藏影片註解
                },
                events: {
                    'onReady': fireEventCommand('youtube_ready'),
                    'onStateChange': fireEventCommand('youtube_state_change')
                }
            });
        }

        function fireEventCommand(key) {
            return function (event) {
                if (window.parent && window.parent.extraMessage ) {
                    window.parent.extraMessage(key, event.data);
                }
            }
        }
    </script>
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W4923N7"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
</body>
</html>

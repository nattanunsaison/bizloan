<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/velocity/1.2.2/velocity.min.js"></script>
        <style>
            @import url(https://fonts.googleapis.com/css?family=Mandali);

            html{
                height: 100%;
                font-family: 'Mandali';
            }

            body {
                margin: 0;
                padding: 0;
                height: 100%;
            }

            .backDrop {
                position: absolute;
                overflow: hidden;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgb(10,15,20);
            }

            .pixel {
                display: none;
                height: 4px;
                width: 4px;
                border-radius: 2px;
                position: absolute;
                background-color: rgb(128,128,128);
            }

            .pageTop {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .pageTop h1 {
                padding: 0.125em 0.25em;
                background-color: rgba(50, 70, 80, 0.33);
                border-radius: 0.125em;
                color: rgba(50, 70, 80, 0.65);
            }
        </style>
    </head>
    <body>
        <div class='backDrop'>
        </div>

        <div class='pageTop'>
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right on_top">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
            <br>
            <h1>
                Supply Chain Finance system!
            </h1>
        </div>

    </body>
</html>
<script>
    var addPixel = function(color, startPos) {
        $('.backDrop').prepend(
            "<div class='pixel' "+
            "style='background-color: rgba("+
            color[0]+", "+
            color[1]+", "+
            color[2]+", "+
            "0.65); "+
            "top: "+startPos[1]+"px; "+
            "left: "+startPos[0]+"px;"+
            "box-shadow: 0px 0px 7px 7px rgba("+
            (color[0] - 5)+", "+
            (color[1] - 5)+", "+
            (color[2] - 5)+", "+
            "0.55); "+
            "'></div>"
        );
    },
  
  
    randNum = function(min, max) {
        return Math.floor(
            Math.random() * (max - min) + min
        );
    },
  
  
    letFly = function() {
        var flying = setInterval(function(){
        var angle = randNum(0, 361),
            dist = randNum(100, 450),
            wWidth = $(window).width(),
            wHeight = $(window).height(),
            toX = Math.cos(angle * Math.PI / 180) * dist,
            toY = Math.sin(angle * Math.PI / 180) * (dist/1.5),
            color = [
                randNum(40, 80),
                randNum(100, 140),
                randNum(120, 160)
            ],
        
            startPos = [
                randNum(0, wWidth),
                randNum(0, wHeight)
            ];

        addPixel(color, startPos);

        $('.pixel:first').show(750).velocity({
            'left': '+=' + toX + 'px',
            'top': '+=' + toY + 'px'
        }, 6000, function() {
            $(this).hide(1000, function() {
            $(this).remove()
            });
        })
        }, 100);
    };

    var hideTitle = function() {
        $('.pageTop h1').delay(1000).fadeOut(1000);
    }

    hideTitle();
    letFly();

</script>

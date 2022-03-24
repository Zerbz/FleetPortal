<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Fleet Portal</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <style>
            html, body {
                background-color:#36393f;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }
            
            .logo{
                margin-top:-15%;
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

            .dispatchers{
                margin-right:35%;
            }

            .drivers{
                margin-right:5%;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .buttons{
                margin-top:15%;              
            }

            .homeButton{
                background:#CA3030;
                color:white;
                border:none;
            }

            .homeButton:hover{
                background:#F55142;
                color:white;
            }
            .btn:hover, .btn:focus, .btn.focus{
                background:#F55142 !important;
                color:white;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="logo">
                  <img src="https://i.imgur.com/QSYd3qN.png">
                </div>

                <div class="buttons">
                    <a href="{{Request::url()}}/login" class="btn btn-default dispatchers homeButton" role="button">Dispatchers</a>
                    <a href="{{Request::url()}}/driver/login" class="btn btn-default drivers homeButton" role="button">Drivers</a>
                </div>
            </div>
        </div>
    </body>
</html>

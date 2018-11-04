<!DOCTYPE html>
<!--
Landing page based on Pratt: http://blacktie.co/demo/pratt/
-->
<html lang="en">
<head>
    
    <title>Progresa</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('/css/main.css') }}" rel="stylesheet">

    <script src="{{ asset('/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('/js/smoothscroll.js') }}"></script>

</head>

<body data-spy="scroll" data-offset="0" data-target="#navigation" style="background-color: #34495e">

    <!-- Fixed navbar -->
    <div id="navigation" class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">{{ trans('adminlte_lang::message.login') }}</a></li>
                        <li><a href="{{ url('/register') }}">{{ trans('adminlte_lang::message.register') }}</a></li>
                    @else
                        <li><a href="/home">{{ Auth::user()->name }}</a></li>
                    @endif
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>

    <div id="headerwrap" background-color: #3c8dbc">
        <div class="container">
            <div class="row centered">
                <div class="col-lg-12">
                    <h1>PROGRESA</h1>
                    <h3>
                        Progresa es una aplicación para la gestión por parte de alumnos y profesores de las prácticas
                        y tareas a realizar en las diferentes asignaturas de un curso.<br/><br/>
                        Gracias a Progresa podremos saber el tiempo que se dedica a cada tarea, las fechas de entrega, el
                        ranking de tiempo dedicado entre los compañeros de clase...
                    <h3>

                    <a href="{{ url('/register') }}" class="btn btn-lg btn-success">{{ trans('adminlte_lang::message.gedstarted') }}</a></h3>
                </div>
            </div>
        </div> <!--/ .container -->
    </div><!--/ #headerwrap -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{{ asset('/js/bootstrap.min.js') }}" type="text/javascript"></script>
</body>

</html>

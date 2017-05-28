<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ url('/home') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>P</b>RG</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Progresa</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">{{ trans('adminlte_lang::message.togglenav') }}</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                

                <!-- Notifications Menu -->
                <li class="dropdown notifications-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning">{{ Auth::user()->numNotificaciones() }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">
                            Tiene {{ Auth::user()->numNotificaciones() }}
                            @if (Auth::user()->numNotificaciones() == 1)
                                notificación
                            @else
                                notificaciones
                            @endif
                        </li>
                        <li>
                            <!-- Inner Menu: contains the notifications -->
                            <ul class="menu">
                                @if (Auth::user()->numNotificacionesAlertaRoja() > 0)
                                    <li><!-- start notification -->
                                        <a href="{{ url('mistareasalumno') }}">
                                            <i class="fa fa-clock-o text-red"></i>
                                            {{ Auth::user()->numNotificacionesAlertaRoja() }}
                                            
                                            @if (Auth::user()->numNotificacionesAlertaRoja() == 1)
                                                tarea acaba
                                            @else
                                                tareas acaban
                                            @endif

                                            en menos de 1 semana
                                        </a>
                                    </li><!-- end notification -->
                                @endif
                                @if (Auth::user()->numNotificacionesAlertaAmarilla() > 0)
                                    <li><!-- start notification -->
                                        <a href="{{ url('mistareasalumno') }}">
                                            <i class="fa fa-clock-o text-yellow"></i>
                                            {{ Auth::user()->numNotificacionesAlertaAmarilla() }}
                                            tareas acaban en menos de 3 semanas
                                        </a>
                                    </li><!-- end notification -->
                                @endif
                            </ul>
                        </li>                        
                    </ul>
                </li>

                <li>

                    <a href="{{ url('/logout') }}">
                        <i class='fa fa-power-off'></i>
                    </a>
                
                </li>
                
                @if (Auth::guest())
                    <li><a href="{{ url('/register') }}">{{ trans('adminlte_lang::message.register') }}</a></li>
                    <li><a href="{{ url('/login') }}">{{ trans('adminlte_lang::message.login') }}</a></li>
                   
                @endif

                <!-- Control Sidebar Toggle Button -->
                <!--
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li> -->
            </ul>
        </div>
    </nav>
</header>

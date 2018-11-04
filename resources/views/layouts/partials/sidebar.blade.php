<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        @if (! Auth::guest())
            <div class="user-panel">
                <div class="pull-left image">
                    @if (Auth::user()->rol == 'alumno')
                        <i class="fa fa-graduation-cap fa-2x"  style="color:white" aria-hidden="true"></i>
                    @elseif (Auth::user()->rol == 'profesor')
                        <i class="fa fa-pencil fa-2x"  style="color:white" aria-hidden="true"></i>                     
                    @elseif (Auth::user()->rol == 'administrador')
                        <i class="fa fa-user-plus fa-2x"  style="color:white" aria-hidden="true"></i>
                    @endif
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->nombre }}</p>
                    
                </div>
            </div>
        @endif

        
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">

             <li class="header">MENÚ</li>
            
            <li class="active">
                <a href="{{ url('home') }}">
                    <i class='fa fa-home'></i>
                    <span>{{ trans('adminlte_lang::message.home') }}</span>
                </a>
            </li>

            @if (Auth::user()->activo and Auth::user()->rol != 'administrador')
                <li>
                    <a href="{{ url('mistareas') }}">
                        <i class='fa fa-tasks'></i>
                        <span>{{ trans('adminlte_lang::message.mistareas') }}</span>   
                    </a>
                </li>


                <li>
                    <a href="{{ url('mistareasfinalizadas') }}">
                        <i class='fa fa-clock-o'></i>
                        <span>{{ trans('adminlte_lang::message.mistareasfinalizadas') }}</span>   
                    </a>
                </li>
            @elseif (Auth::user()->activo and Auth::user()->rol == 'administrador')
                <li>
                    <a href="{{ url('usuarios') }}">
                        <i class='fa fa-group'></i>
                        <span>{{ trans('adminlte_lang::message.usuarios') }}</span>   
                    </a>
                </li>
            @endif

        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
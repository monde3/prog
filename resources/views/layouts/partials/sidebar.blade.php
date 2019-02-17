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
                    <p>{{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</p>
                    
                </div>
            </div>
        @endif

        
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">

            <li class="header">MENÚ</li>
            
            <li id="sidebar_item_home">
                <a href="{{ url('home') }}">
                    <i class='fa fa-home'></i>
                    <span>{{ trans('adminlte_lang::message.home') }}</span>
                </a>
            </li>

            @if (Auth::user()->activo and Auth::user()->rol != 'administrador')
                <li id="sidebar_item_mistareas">
                    <a href="{{ url('mistareas') }}">
                        <i class='fa fa-tasks'></i>
                        <span>{{ trans('adminlte_lang::message.mistareas') }}</span>   
                    </a>
                </li>
                <li id="sidebar_item_mistareasfinalizadas">
                    <a href="{{ url('mistareasfinalizadas') }}">
                        <i class='fa fa-clock-o'></i>
                        <span>{{ trans('adminlte_lang::message.mistareasfinalizadas') }}</span>   
                    </a>
                </li>
                @if (Auth::user()->rol == 'alumno')
                <li id="sidebar_item_avatar">
                    <a href="{{ url('avatar') }}">
                        <i class='fa fa-user-ninja'></i>
                        <span>{{ trans('adminlte_lang::message.avatar') }}</span>   
                    </a>
                </li>
                @endif
            @elseif (Auth::user()->activo and Auth::user()->rol == 'administrador')
                <li id="sidebar_item_usuarios">
                    <a href="{{ url('usuarios') }}">
                        <i class='fa fa-group'></i>
                        <span>{{ trans('adminlte_lang::message.usuarios') }}</span>   
                    </a>
                </li>
            @endif
            @if (Auth::user()->rol != 'profesor')
                <li id="sidebar_item_tienda">
                    <a href="{{ route('tienda') }}">
                        <i class='fa fa-shopping-cart'></i>
                        <span>{{ trans('adminlte_lang::message.shop') }}</span>   
                    </a>
                </li>
            @endif

        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
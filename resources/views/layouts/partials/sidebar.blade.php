<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        @if (! Auth::guest())
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{asset('/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image" />
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->nombre }}</p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> {{ trans('adminlte_lang::message.online') }}</a>
                </div>
            </div>
        @endif

        
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">{{ trans('adminlte_lang::message.header') }}</li>
            <!-- Optionally, you can add icons to the links -->
            
            <li class="active">
                <a href="{{ url('home') }}">
                    <i class='fa fa-home'></i>
                    <span>{{ trans('adminlte_lang::message.home') }}</span>
                </a>
            </li>

            <li>
                <a href="{{ url('mistareasalumno') }}">
                    <i class='fa fa-tasks'></i>
                    <span>{{ trans('adminlte_lang::message.mistareas') }}</span>   
                </a>
            </li>


            <li>
                <a href="{{ url('mistareasfinalizadasalumno') }}">
                    <i class='fa fa-clock-o'></i>
                    <span>{{ trans('adminlte_lang::message.mistareasfinalizadas') }}</span>   
                </a>
            </li>

            <li>
                <a href="{{ url('calendarioalumno') }}">
                    <i class='fa fa-calendar'></i>
                    <span>{{ trans('adminlte_lang::message.calendario') }}</span>   
                </a>
            </li>
            
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>

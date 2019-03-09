<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        @yield('contentheader_title', 'Inicio')
        <small>@yield('contentheader_description')</small>
    </h1>
    <ol class="breadcrumb">
    	<li><a href="{{ url('home') }}"><i class="fa fa-home"></i> {{ trans('adminlte_lang::message.home') }}</a></li>
    	@yield('contentheader_breadcrumb')
    </ol>
</section>
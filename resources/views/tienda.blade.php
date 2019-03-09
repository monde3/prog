@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.shop') }}
@endsection

@section('contentheader_title')
@endsection
	
@section('contentheader_description')
	<a id="btn_help"><i class="fa fa-question-circle"></i></a>
@endsection

@section('main-content')
	@if (Auth::user()->rol=='alumno')
		@include('layouts.alumno.tienda')
	@elseif (Auth::user()->rol=='administrador')
		@include('layouts.administrador.tienda')
	@endif
@endsection

@section('scripts')
    @parent
<script>

	$(document).ready(function(){
		$('#btn_help').click( function(e) {
			e.preventDefault();
			$("#modal_mensaje_imagen").show();
			$("#modal_mensaje_imagen").attr("src","images/avatar-help.png");
			$("#modal_mensaje_titulo").text("{{ trans('adminlte_lang::message.help') }}");
			$("#modal_mensaje_texto").text("{{ (Auth::user()->rol=='alumno') ? trans('adminlte_lang::message.helpusershoptext') : trans('adminlte_lang::message.helpadminshoptext') }}");
			$("#modal_mensaje").show();
			return false; } );
	});

</script>
@endsection
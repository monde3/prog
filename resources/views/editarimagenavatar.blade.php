@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.editavatar') }}
@endsection

@section('contentheader_title')
@endsection
	
@section('contentheader_description')
	<a id="btn_help"><i class="fa fa-question-circle"></i></a>
@endsection

@section('contentheader_breadcrumb')
	<li><a href="{{ url('avatar') }}"> {{ trans('adminlte_lang::message.avatar') }}</a></li>
	<li class="active">{{ trans('adminlte_lang::message.editavatarimage') }}</li>
@endsection

@section('main-content')
	@if (Auth::user()->activo and (Auth::user()->rol == 'administrador' or Auth::user()->rol == 'profesor'))
	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="box box-solid box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">
							<b>{{ trans('adminlte_lang::message.avatar') }}</b>
						</h3>
					</div>
					<div class="box-body">
						{{ trans('adminlte_lang::message.noavatarrol') }}							
					</div>
				</div>
			</div>
		</div>
	</div>
	@else
	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="box box-solid box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">
						<b>{{ trans('adminlte_lang::message.editavatarimage') }}</b>
					</h3>					
				</div>
			<div class="box-body">
				<div class="gallery-row">
				@if($imagenes_compradas->count() == 0)
						{{ trans('adminlte_lang::message.noskinsbought') }}
				@else
					@foreach($imagenes_compradas as $avatar_imagen)
					<div class="gallery-column">
						@if(!in_array($avatar_imagen->imagen->id, $imagenes_seleccionadas))
						<div class="gallery-img hovereffect">
							@if($avatar_imagen->imagen->parte == 'avatar')
							<a href="javascript:void(0);" onclick="showModalEstado({{$avatar_imagen->imagen->id}});">
							@else
							<a href="{{ route('cambiarImagenAvatar', array(
										'imagen_id' => $avatar_imagen->imagen->id,
										'estado' => '-'
										)) }}">
							@endif
						@else
						<div class="gallery-img selectedimage">
						@endif
								<img class="img-responsive" src="{{ route('getImage', ['filename' => $avatar_imagen->imagen->filename]) }}">
							@if(!in_array($avatar_imagen->imagen->id, $imagenes_seleccionadas))
							</a>
							@endif
						</div>
						<div class="gallery-text text-center">
							<b>{{ trans('adminlte_lang::message.'.$avatar_imagen->imagen->parte) }}</b>
						</div>
						<div class="gallery-text text-center">
						@if(!in_array($avatar_imagen->imagen->id, $imagenes_seleccionadas))
							<b>{{ trans('adminlte_lang::message.select') }}</b>
						@else
							<b>{{ trans('adminlte_lang::message.selected') }}</b>
						@endif
						</div>
					</div>
					@endforeach
				@endif
				</div>
			</div>
		</div>
		{{ $imagenes_compradas->links() }}
	</div>
	<!--MODAL PARA PREGUNTAR POR EL ESTADO AL QUE ASIGNARLE LA IMAGEN-->
	<!--<div class="modal fade" tabindex="-1" role="dialog" id="modal_pomodoro">-->
	<div id="div_imagen_id" style="display:none;"></div>
	<div class="modal" tabindex="-1" role="dialog" id="modal_estado">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title">Estado</h3>
				</div>
				<div class="modal-body">
					<div class="container-fluid spark-screen">
						<h4>{{ trans('adminlte_lang::message.selectstate') }}</h4>
						<div class="col-sm-3">
							<select id="selector_estado" name="role" class="form-control" >             
								<option value="activo">{{ trans('adminlte_lang::message.active') }}</option>
								<option value="inactivo">{{ trans('adminlte_lang::message.inactive') }}</option>
								<option value="herido">{{ trans('adminlte_lang::message.hurt') }}</option>
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input id="btn-aceptar" type="button" class="btn btn-primary" data-dismiss="modal" value="{{ trans('adminlte_lang::message.accept') }}" onclick="cambiarImagen()" >
					<input id="btn-cerrar" type="button" class="btn btn-secondary" data-dismiss="modal" value="{{ trans('adminlte_lang::message.close') }}" onclick="modalEstadoToggle()" >
				</div>
			</div>
		</div>
	</div>
	@endif
@endsection

@section('scripts')
    @parent
<script>

	$(document).ready(function(){
		$('#btn_help').click( function(e) {
			e.preventDefault();
			$("#modal_mensaje_imagen").show();
			$("#modal_mensaje_imagen").attr("src","/images/help.png");
			$("#modal_mensaje_titulo").text("{{ trans('adminlte_lang::message.help') }}");
			$("#modal_mensaje_texto").text("{{ trans('adminlte_lang::message.helpavatarimagetext') }}");
			$("#modal_mensaje").show();
			return false; } );
	});

	function cambiarImagen(){
		window.location.replace("{{ url ('cambiarImagenAvatar') }}"
								.concat("/").concat($("#div_imagen_id").text())
								.concat("/").concat($('#selector_estado').val()));
	}

	function showModalEstado(imagen_id){
		$("#div_imagen_id").text(imagen_id);
		$("#modal_estado").show();
	}

	function modalEstadoToggle(){
		$("#div_imagen_id").text('');
		$("#modal_estado").modal('toggle');
	}
</script>
@endsection
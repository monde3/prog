@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.editimage') }}
@endsection

@section('contentheader_title')
	{{ trans('adminlte_lang::message.editavatarimage') }}
@endsection

@section('main-content')
	@if (Auth::user()->activo and (Auth::user()->rol == 'administrador' or Auth::user()->rol == 'profesor'))
	<div class="container spark-screen">
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
	<div class="container spark-screen">
		<div class="row">
			<div class="col-md-9">
				<div class="gallery-row">
				@foreach($imagenes_compradas as $avatar_imagen)
						<div class="gallery-column">
							<div class="gallery-img">
								@if(!in_array($avatar_imagen->imagen->id, $imagenes_seleccionadas))
									@if($avatar_imagen->imagen->parte == 'avatar')
									<a href="javascript:void(0);" onclick="showModal({{$avatar_imagen->imagen->id}});">
									@else
									<a href="{{ route('cambiarImagenAvatar', array(
												'imagen_id' => $avatar_imagen->imagen->id,
												'estado' => '-'
												)) }}">
									@endif
								@endif
									<img class="img-responsive" src="{{ route('getImage', ['filename' => $avatar_imagen->imagen->filename]) }}">
									<div class="text-center">
										<b>{{ trans('adminlte_lang::message.'.$avatar_imagen->imagen->parte) }}</b>
									</div>
									<div class="text-center">
									@if(!in_array($avatar_imagen->imagen->id, $imagenes_seleccionadas))
										<b>{{ trans('adminlte_lang::message.select') }}</b>
									@else
										<b>{{ trans('adminlte_lang::message.selected') }}</b>
									@endif
									</div>
								@if(!in_array($avatar_imagen->imagen->id, $imagenes_seleccionadas))
								</a>
								@endif
							</div>
						</div>
				@endforeach
				</div>
			</div>
		</div>
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
					<div class="container spark-screen">
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
					<input id="btn-cerrar" type="button" class="btn btn-secondary" data-dismiss="modal" value="{{ trans('adminlte_lang::message.close') }}" onclick="modalToggle()" >
				</div>
			</div>
		</div>
	</div>
	@endif
@endsection

@section('scripts')
    @parent
<script>

	function cambiarImagen(){
		window.location.replace("{{ url ('cambiarImagenAvatar') }}"
								.concat("/").concat($("#div_imagen_id").text())
								.concat("/").concat($('#selector_estado').val()));
	}

	function showModal(imagen_id){
		$("#div_imagen_id").text(imagen_id);
		$("#modal_estado").show();
	}

	function modalToggle(){
		$("#div_imagen_id").text('');
		$("#modal_estado").modal('toggle');
	}
</script>
@endsection
<div class="container-fluid spark-screen">
	<div class="row">
		<div class="box box-solid box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">
					<b>{{ trans('adminlte_lang::message.shop') }}</b>
				</h3>					
			</div>
		<div class="box-body">
			<div class="col-md-9">
				<div class="gallery-row">
				@foreach($imagenes as $imagen)
					<div class="gallery-column">
						<div class="gallery-img">
							<div class="hovereffect">
								<img class="img-responsive" src="{{ route('getImage', ['filename' => $imagen->filename]) }}" alt="">
						        <div class="overlay">
							    	<h4><b><a href="#" onclick="showModalEdicion({{ $imagen->id }}, '{{ $imagen->parte }}', {{ $imagen->precio }});">{{ trans('adminlte_lang::message.edit') }}</a></b></h4>
					            	<h2></h2>
							    	<h4><b><a href="{{ route('eliminarImagen', ['imagen_id' => $imagen->id]) }}">{{ trans('adminlte_lang::message.delete') }}</a></b></h4>
						        </div>
							</div>
						</div>
						<div class="gallery-text text-center">
							<b>{{ trans('adminlte_lang::message.'.$imagen->parte) }}</b>
						</div>
						<div class="gallery-text text-center">
	                        <i class='glyphicon glyphicon-usd'></i>
							<b>{{ $imagen->precio }}</b>
						</div>
					</div>
				@endforeach
				</div>
			</div>
			<form action=" {{ route('subirSkin') }}" method="post" enctype="multipart/form-data" class="col-md-3">
				{!! csrf_field() !!}

				<div class="form-group">
					<div class="box box-solid box-primary">
						<div class="box-header with-border">
							<label>{{ trans('adminlte_lang::message.loadimage') }}</label>
						</div>
						<div class="box-body">
							<label for="parte">{{ trans('adminlte_lang::message.part') }}</label>
							<select name="parte" class="form-control" id="parte">
								<option value="avatar" selected>{{ trans('adminlte_lang::message.avatar') }}</option>
						    	<option value="head">{{ trans('adminlte_lang::message.head') }}</option>
						    	<option value="body">{{ trans('adminlte_lang::message.body') }}</option>
								<option value="hands">{{ trans('adminlte_lang::message.hands') }}</option>
						    	<option value="feet">{{ trans('adminlte_lang::message.feet') }}</option>
						    	<option value="weapon">{{ trans('adminlte_lang::message.weapon') }}</option>
							</select>

							<label for="precio">{{ trans('adminlte_lang::message.price') }}</label>
							<input name="precio" autocomplete="off" class="form-control" id="precio" value="{{ old('precio') }}"></input>

							<label for="imagen">{{ trans('adminlte_lang::message.image') }}</label>
							<input type="file" name="imagen" class="form-control" id="imagen" value="{{ old('file') }}">
						    <h6>{{ trans('adminlte_lang::message.maximagesize') }}</h6>
						</div>
					</div>
				</div>

				@if($errors->any())
				<div class="alert alert-danger">
					<ul>
						@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
				@endif
				<button type="submit" class="btn btn-success">{{ trans('adminlte_lang::message.accept') }}</button>
			</form>
		</div>
	</div>
	{{ $imagenes->links() }}


	<!--MODAL PARA LA EDICIÓN DE SKINS-->
	<!--<div class="modal fade" tabindex="-1" role="dialog" id="modal_pomodoro">-->
	<div class="modal" tabindex="-1" role="dialog" id="modal_edicion">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title">{{ trans('adminlte_lang::message.edit') }}</h3>
				</div>
				<div class="modal-body">
					<div class="container-fluid spark-screen">

						<form id="editSkinForm" method="post">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="form-group">
								<img id="imagen_edicion" class="img-responsive img-edicion" alt="">
								<label for="parte_edicion">{{ trans('adminlte_lang::message.part') }}</label>
								<select id="parte_edicion" name="parte_edicion" class="form-control">
									<option value="avatar" selected>{{ trans('adminlte_lang::message.avatar') }}</option>
							    	<option value="head">{{ trans('adminlte_lang::message.head') }}</option>
							    	<option value="body">{{ trans('adminlte_lang::message.body') }}</option>
									<option value="hands">{{ trans('adminlte_lang::message.hands') }}</option>
							    	<option value="feet">{{ trans('adminlte_lang::message.feet') }}</option>
							    	<option value="weapon">{{ trans('adminlte_lang::message.weapon') }}</option>
								</select>
								<label for="precio_edicion">{{ trans('adminlte_lang::message.price') }}</label>
								<input id="precio_edicion" name="precio_edicion" autocomplete="off" class="form-control" value="{{ old('precio_edicion') }}"></input>
							</div>

							@if($errors->hasBag('editionerrors'))
							<div class="alert alert-danger">
								<ul>
									@foreach($errors->editionerrors->all() as $error)
									<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
							@endif
						</form>

					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" type="submit" form="editSkinForm">{{ trans('adminlte_lang::message.accept') }}</button>
					<input id="btn-cerrar" type="button" class="btn btn-secondary" data-dismiss="modal" value="{{ trans('adminlte_lang::message.close') }}" onclick="modalEdicionToggle()" >
				</div>
			</div>
		</div>
	</div>
</div>


@section('scripts')
    @parent
<script>


    $(document).ready(function() {
    	if({!! $errors->hasBag('editionerrors') ? '1' : '0' !!} == '1'){
    		var id = '{{ session('id') ? session('id') : '0' }}';
    		var parte = '{{ session('parte') ? session('parte') : '0' }}';
    		var precio = 
    			'{{ session('precio') && trim(session('precio')) != '' ? session('precio') : '0' }}';

    		showModalEdicion(id, parte, precio);
    	}
    });

		//https://stackoverflow.com/questions/36946071/update-data-dynamically-using-modal-in-laravel
	function showModalEdicion(id, parte, precio){
	  	var url_form = "{{ url ('editarSkin') }}"
						.concat("/").concat(id);
		$("#editSkinForm").attr("action",url_form);

		$("#modal_edicion").show();
		$("#precio_edicion").val(precio);

		$('#parte_edicion option')
			.filter(function(i, e) {return $(e).val() == parte})
			.prop('selected', true);
		var id_imagen = id;
	  	var url_estado = "{{ url ('getImageById') }}"
						.concat("/").concat(id);
		$('#imagen_edicion').attr("src",url_estado);
	}

	function modalEdicionToggle(){
		$("#modal_edicion").modal('toggle');
	}

</script>
@endsection
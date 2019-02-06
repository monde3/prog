<div class=" spark-screen">
	<div class="row">
		<div class="col-md-9">
			<div class="gallery-row">
			@foreach($imagenes as $imagen)
				<div class="gallery-column">
					<div class="gallery-img">
						<img class="img-responsive" src="{{ route('getImage', ['filename' => $imagen->filename]) }}">
						<div class="text-center">
							<b>{{ trans('adminlte_lang::message.'.$imagen->parte) }}</b>
						</div>
						<div class="text-center">
							<button class="btn btn-primary" onclick="location.href='{{ route('eliminarImagen', ['imagen_id' => $imagen->id]) }}';"><i class="fa fa-trash"></i></button>
	                        <i class='glyphicon glyphicon-usd'></i>
							<b>{{ $imagen->precio }}</b>
						</div>
					</div>
				</div>
			@endforeach
			</div>
		</div>
		<form action=" {{ route('subirImagenAvatar') }}" method="post" enctype="multipart/form-data" class="col-md-3">
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
	{{ $imagenes->links() }}
</div>
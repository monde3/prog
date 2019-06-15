<div class="container-fluid spark-screen">
	<div class="row">
		<div class="box box-solid box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">
					<b>{{ trans('adminlte_lang::message.shop') }}</b>
				</h3>					
			</div>
		<div class="box-body">
			<div class="gallery-row">
			@foreach($imagenes as $imagen)
				<div class="gallery-column">
					@if(!$imagenes_compradas->contains('imagen_id', $imagen->id))
					<div class="gallery-img hovereffect">
						<a href="{{ route('comprarImagen', ['imagen_id' => $imagen->id]) }}">
					@else
					<div class="gallery-img selectedimage">
					@endif
							<img class="img-responsive" src="{{ route('getImage', ['filename' => $imagen->filename]) }}">
						@if(!$imagenes_compradas->contains('imagen_id', $imagen->id))
						</a>
						@endif
					</div>
					<div class="gallery-text text-center">
						<b>{{ trans('adminlte_lang::message.'.$imagen->parte) }}</b>
					</div>
					<div class="gallery-text text-center">
					@if($imagenes_compradas->contains('imagen_id', $imagen->id))
						<b>{{ trans('adminlte_lang::message.imagebought') }}</b>
					@else
                        <i class='glyphicon glyphicon-usd'></i>
						<b>{{ $imagen->precio }}</b>
					@endif
					</div>
				</div>
			@endforeach
			</div>
		</div>
	</div>
	{{ $imagenes->links() }}
</div>
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
						@if(!$imagenes_compradas->contains('imagen_id', $imagen->id))
						<div class="gallery-img hovereffect">
							<a href="{{ route('comprarImagen', ['imagen_id' => $imagen->id]) }}">
						@else
						<div class="gallery-img selectedimage">
						@endif
								<img class="img-responsive" src="{{ route('getImage', ['filename' => $imagen->filename]) }}">
								<div class="text-center">
									<b>{{ trans('adminlte_lang::message.'.$imagen->parte) }}</b>
								</div>
							@if(!$imagenes_compradas->contains('imagen_id', $imagen->id))
							</a>
							@endif
						</div>
					@if($imagenes_compradas->contains('imagen_id', $imagen->id))
						<div class="text-center">
							<b>{{ trans('adminlte_lang::message.imagebought') }}</b>
						</div>
					@else
						<div class="text-center">
	                        <i class='glyphicon glyphicon-usd'></i>
							<b>{{ $imagen->precio }}</b>
						</div>
					@endif
					</div>
				@endforeach
				</div>
			</div>
		</div>
	</div>
	{{ $imagenes->links() }}
</div>
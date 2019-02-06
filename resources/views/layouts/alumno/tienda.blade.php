<div class=" spark-screen">
	<div class="row">
		<div class="col-md-9">
			<div class="gallery-row">
			@foreach($imagenes as $imagen)
				<div class="gallery-column">
					<div class="gallery-img">
						@if(!$imagenes_compradas->contains('imagen_id', $imagen->id))
						<a href="{{ route('comprarImagen', ['imagen_id' => $imagen->id]) }}">
						@endif
							<img class="img-responsive" src="{{ route('getImage', ['filename' => $imagen->filename]) }}">
							<div class="text-center">
								<b>{{ trans('adminlte_lang::message.'.$imagen->parte) }}</b>
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
						@if(!$imagenes_compradas->contains('imagen_id', $imagen->id))
						</a>
						@endif
					</div>
				</div>
			@endforeach
			</div>
		</div>
	</div>
	{{ $imagenes->links() }}
</div>
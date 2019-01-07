@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.luchar') }}
@endsection

@section('contentheader_title')	
@endsection

@section('contentheader_breadcrumb')
	<li class="active">{{ trans('adminlte_lang::message.luchar') }}</li>
@endsection

@section('main-content')
	<div class="container spark-screen">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">


				<div class="box box-solid box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><b>Variables de DEBUG</b></h3>	
					</div>
					<div class="box-body">
						<b>$avatares = yeah</b>
					</div>
				</div>


				<div class="box box-solid box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><b>{{ trans('adminlte_lang::message.opponents') }}</b></h3>
					</div>
					<div class="box-body">
					    @if( isset($avatares) and $avatares->count() > 0 )
					    	<table id="tareas" class="table table-hover" cellspacing="0" width="100%">
							  	<thead>
								  	<th class="hidden-xs hidden-sm col-md-1 col-lg-1">
								  		{{ trans('adminlte_lang::message.avatar') }}
								  	</th>
								  	<th class="col-xs-10 col-sm-10 col-md-5 col-lg-5">
								  		{{ trans('adminlte_lang::message.name') }}
								  	</th>
								    <th class="hidden-xs hidden-sm col-md-2 col-lg-2">
								    	{{ trans('adminlte_lang::message.experience') }}
								    </th>
								    <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1"/>
							  	</thead>
							  	<tbody>
							  		@foreach ($avatares as $avatar_)
							        <tr>
							        	<td style="display:none;">
											{{ $avatar_->user_id }}
										</td>
										<td class="hidden-xs hidden-sm col-md-1 col-lg-1">
											<div class="item active">
												<img class="img-responsive img-rounded" src="{{ $avatar_->rutaImagen() }}" alt="">
											</div>
										</td>
										<td class="col-md-1 col-lg-1">
											{{ $avatar_->user_id }}
										</td>
										<td class="col-md-1 col-lg-1">
											{{ $avatar_->alumno->nombre }} {{ $avatar_->alumno->apellidos }}
										</td>
										<td class="hidden-xs hidden-sm col-md-1 col-lg-1">
											{{ $avatar_->exp }}
										</td>
										<td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
											<a class="btn btn-block btn-info"
												onclick="luchar({{ $avatar_->user_id }})">
												{{ trans('adminlte_lang::message.luchar') }}
											</a>
										</td>
								  	</tr>
									@endforeach
							  	</tbody>
							</table>
						@else
							No hay tareas activas
						@endif
						
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
    @parent

    @if (Auth::user()->rol=='alumno')

    	<script type="text/javascript">

    		function luchar(oponente_id){
                var url_tarea = "{{ url ('lucharContra') }}"
                                .concat("/").concat(oponente_id);
				$.ajax({
				  type: "GET",
				  url: url_tarea
				}).done(function t(response) {
					var resp = response.split("\\");
					if(resp[0]=="err"){
						$("#modal_mensaje_titulo").text("ERROR");
						$("#modal_mensaje_texto").text(resp[1]);
						$("#modal_mensaje").show();
					}else{
						if(resp[0]=="vic"){
							$("#modal_mensaje_titulo").text("VICTORIA");
							$("#modal_mensaje_texto").text("¡Enhorabuena, has ganado!");
							$("#modal_mensaje").show();
						}else if(resp[0]=="der"){
							$("#modal_mensaje_titulo").text("DERROTA");
							$("#modal_mensaje_texto").text("Lástima, perdiste.");
							$("#modal_mensaje").show();
						}
						$("#header-oro").text(resp[1]);
						$("#header-vida").text(resp[2]);
					}
                });
    		}

    	</script>

    @endif
@endsection
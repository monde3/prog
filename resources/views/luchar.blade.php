@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.fight') }}
@endsection

@section('contentheader_title')
@endsection
	
@section('contentheader_description')
	<a id="btn_help"><i class="fa fa-question-circle"></i></a>
@endsection

@section('contentheader_breadcrumb')
	<li><a href="{{ url('avatar') }}"> {{ trans('adminlte_lang::message.avatar') }}</a></li>
	<li class="active">{{ trans('adminlte_lang::message.fight') }}</li>
@endsection

@section('main-content')
	<div class="container-fluid spark-screen">
		<div class="row">

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
						  		@foreach ($avatares as $avatar)
						        <tr>
						        	<td style="display:none;">
										{{ $avatar->user_id }}
									</td>
									<td class="hidden-xs hidden-sm col-md-1 col-lg-1">
										<div class="item active">
											<img class="img-responsive img-rounded" src="{{ route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'avatar']) }}" alt="">
										</div>
									</td>
									<td class="col-md-1 col-lg-1">
										{{ $avatar->alumno->nombre }} {{ $avatar->alumno->apellidos }}
									</td>
									<td class="hidden-xs hidden-sm col-md-1 col-lg-1">
										{{ $avatar->exp }}
									</td>
									<td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
										<a class="btn btn-block btn-info"
											onclick="luchar({{ $avatar->user_id }})">
											{{ trans('adminlte_lang::message.fight') }}
										</a>
									</td>
							  	</tr>
								@endforeach
						  	</tbody>
						</table>
					@else
						{{ trans('adminlte_lang::message.noopponents') }}
					@endif
				</div>
			</div>

		</div>
	</div>
	<!--MODAL PARA LOS COMBATES-->
	<!--Comentamos el fade para que vaya más rápido en la máquina virtual-->
	<!--<div class="modal fade" tabindex="-1" role="dialog" id="modal_pomodoro">-->
	<div class="modal" tabindex="-1" role="dialog" id="modal_lucha">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title text-center" id="modal_lucha_titulo"></h3>
				</div>
					<div class="modal-body">
						<div class="row">
							<div class="text-center">
								<div class="col-md-1"></div>
								<div class="col-md-4">
									<h4 class="modal-content" id="modal_lucha_nombre_1"></h4>
								</div>
								<div class="col-md-2"></div>
								<div class="col-md-4">
									<h4 class="modal-content" id="modal_lucha_nombre_2"></h4>
								</div>
								<div class="col-md-1"></div>
							</div>
						</div>
						<div class="row">
							<div class="text-center">
								<div class="col-md-1"></div>
								<div class="col-md-4">
									<img id="modal_lucha_imagen_op_1" class="img-responsive img-rounded img-vs">
									<img id="modal_lucha_imagen_cruz_1" class="img-responsive img-rounded img-tachado fade-in-delay" src="images/tachado.png" hidden>
								</div>
								<div class="col-md-2">
									<img id="modal_lucha_imagen_vs" class="img-responsive img-rounded">
								</div>
								<div class="col-md-4">
									<img id="modal_lucha_imagen_op_2" class="img-responsive img-rounded img-vs">
									<img id="modal_lucha_imagen_cruz_2" class="img-responsive img-rounded img-tachado fade-in-delay" src="images/tachado.png" hidden>
								</div>
								<div class="col-md-1"></div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div id="modal_lucha_pie" class='text-center fade-in-delay'></div>
						<input id="btn_cerrar_modal_lucha" type="button" class="btn btn-primary" data-dismiss="modal" onclick="modalLuchaToggle()" value="Cerrar">
					</div>
				</div>
		</div>
	</div>
@endsection

@section('scripts')
    @parent

    @if (Auth::user()->rol=='alumno')

    	<script type="text/javascript">

    		$(document).ready(function(){
	    		$('#btn_help').click( function(e) {
	    			e.preventDefault();
      				$("#modal_mensaje_imagen").show();
      				$("#modal_mensaje_imagen").attr("src","images/help.png");
					$("#modal_mensaje_titulo").text("{{ trans('adminlte_lang::message.help') }}");
					$("#modal_mensaje_texto").text("{{ trans('adminlte_lang::message.helpfighttext') }}");
					$("#modal_mensaje").show();
	    			return false; } );

			    // Modal de combate externo
				@if( isset($oponente) )
					var resultado = "{{ $resultado }}".split("%");
					if(resultado[0] == "OK"){
						$("#modal_lucha_imagen_cruz_2").hide();
						$("#modal_lucha_imagen_cruz_2").removeClass("img-tachado fade-in-delay");
					}else{
						$("#modal_lucha_imagen_cruz_1").removeClass("img-tachado fade-in-delay");
						$("#modal_lucha_imagen_cruz_1").hide();
					}
      				$("#modal_lucha_imagen_op_1").attr("src", "{{ route('imagenAvatar', ['user_id' => $oponente->user_id, 'parte' => 'avatar']) }}" );
      				$("#modal_lucha_imagen_op_2").attr("src", "{{ route('imagenAvatar', ['user_id' => Auth::user()->id, 'parte' => 'avatar']) }}" );
      				$("#modal_lucha_imagen_vs").attr("src","images/versus.png");
					$("#modal_lucha_titulo").text("{{trans('adminlte_lang::message.fightexternaltitle')}}");
					$("#modal_lucha_nombre_1").text(resultado[3]);
					$("#modal_lucha_nombre_2").text(resultado[2]);
					$("#modal_lucha_pie").text(resultado[1]);
					$("#modal_lucha").show();
			    @endif
    		});

			function modalLuchaToggle(){
				$("#modal_lucha").modal('toggle');
			};

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
						$("#modal_lucha_titulo").text("{{ trans('adminlte_lang::message.fightexclam') }}");
						$("#modal_lucha_nombre_1").text(resp[4]);
						$("#modal_lucha_nombre_2").text(resp[5]);
	      				$("#modal_lucha_imagen_vs").attr("src","images/versus.png");
	      				$("#modal_lucha_imagen_op_1").attr("src", "{{ route('imagenAvatar', ['user_id' => Auth::user()->id, 'parte' => 'avatar']) }}" );
	      				$("#modal_lucha_imagen_op_2").attr("src", "http://progresa.com.devel/imagenAvatar/"+oponente_id+"/avatar");

						if(resp[0]=="vic"){
							$("#modal_lucha_imagen_cruz_1").hide();
              				$("#modal_lucha_pie").text("+{{ \App\Avatar::ORO_VICTORIA }} {{ trans('adminlte_lang::message.gold') }}".concat(" -").concat(resp[3]).concat(" {{ trans('adminlte_lang::message.life') }}"));
						}else if(resp[0]=="der"){
							$("#modal_lucha_imagen_cruz_2").hide();
              				$("#modal_lucha_pie").text("-".concat(resp[3]).concat(" {{ trans('adminlte_lang::message.life') }}"));
						}
						$("#modal_lucha").show();

						$("#header-oro").text(resp[1]);
						$("#header-vida").text(resp[2]);
					}
                });
    		}

    	</script>

    @endif
@endsection
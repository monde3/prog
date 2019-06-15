@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.avatar') }}
@endsection

@section('contentheader_title')
@endsection
	
@section('contentheader_description')
	<a id="btn_help"><i class="fa fa-question-circle"></i></a>
@endsection

@section('contentheader_breadcrumb')
	<li class="active">{{ trans('adminlte_lang::message.avatar') }}</li>
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
			<!--INFORMACION DEL AVATAR-->
			<div class="row">
				<div class="box box-solid box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">
							<b>{{ trans('adminlte_lang::message.avatar') }}</b>
						</h3>					
					</div>
					<div class="box-body">
						<!-- IMAGEN AVATAR -->
						<div class="col-sm-4">
							<div class="hovereffect">
								<img id="avatar_img" class="img-responsive img-rounded" src="{{ route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'avatar']) }}" alt="">
						        <div class="overlay">
					            	<h2></h2>
							    	<p><a href="{{ route('editarImagenAvatar') }}">{{ trans('adminlte_lang::message.editavatar') }}</a></p> 
						        </div>
							</div>
						</div>
						<!-- PARTES -->
						<div class="col-sm-3">
							<div class="row row-margin-bottom">
								<div class="col-sm-5">
									<span class="bell">
									@if($avatar->img_head!=0)
										<img class="img-responsive img-rounded" src="{{ route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'head']) }}" alt="">
									@else
										<b>{{ trans('adminlte_lang::message.head') }}</b>
									@endif
									    <span id="head_value" class="bellnumbers">{{ $avatar->head }}</span>
									</span>
								</div>
								<div class="col-sm-2 vcenter">
									<button id="btn_head" type="button" class="btn btn-primary btn-circle" onclick="aumentar_nivel(this.id)" data-toggle="tooltip" data-placement="right" title="{{ trans('adminlte_lang::message.improve') }}">
										<i class="glyphicon glyphicon-plus"></i>
									</button>
								</div>
							</div>
							<div class="row row-margin-bottom">
								<div class="col-sm-5">
									<span class="bell">
									@if($avatar->img_body!=0)
										<img class="img-responsive img-rounded" src="{{ route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'body']) }}" alt="">
									@else
										<b>{{ trans('adminlte_lang::message.body') }}</b>
									@endif
									    <span id="body_value" class="bellnumbers">{{ $avatar->body }}</span>
									</span>
								</div>
								<div class="col-sm-2 vcenter">
									<button id="btn_body" type="button" class="btn btn-primary btn-circle" onclick="aumentar_nivel(this.id)" data-toggle="tooltip" data-placement="right" title="{{ trans('adminlte_lang::message.improve') }}">
										<i class="glyphicon glyphicon-plus"></i>
									</button>
								</div>
							</div>
							<div class="row row-margin-bottom">
								<div class="col-sm-5">
									<span class="bell">
									@if($avatar->img_hands!=0)
										<img class="img-responsive img-rounded" src="{{ route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'hands']) }}" alt="">
									@else
										<b>{{ trans('adminlte_lang::message.hands') }}</b>
									@endif
									    <span id="hands_value" class="bellnumbers">{{ $avatar->hands }}</span>
									</span>
								</div>
								<div class="col-sm-2 vcenter">
									<button id="btn_hands" type="button" class="btn btn-primary btn-circle" onclick="aumentar_nivel(this.id)" data-toggle="tooltip" data-placement="right" title="{{ trans('adminlte_lang::message.improve') }}">
										<i class="glyphicon glyphicon-plus"></i>
									</button>
								</div>
							</div>
							<div class="row row-margin-bottom">
								<div class="col-sm-5">
									<span class="bell">
									@if($avatar->img_feet!=0)
										<img class="img-responsive img-rounded" src="{{ route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'feet']) }}" alt="">
									@else
										<b>{{ trans('adminlte_lang::message.feet') }}</b>
									@endif
									    <span id="feet_value" class="bellnumbers">{{ $avatar->feet }}</span>
									</span>
								</div>
								<div class="col-sm-2 vcenter">
									<button id="btn_feet" type="button" class="btn btn-primary btn-circle" onclick="aumentar_nivel(this.id)" data-toggle="tooltip" data-placement="right" title="{{ trans('adminlte_lang::message.improve') }}">
										<i class="glyphicon glyphicon-plus"></i>
									</button>
								</div>
							</div>
							<div class="row row-margin-bottom">
								<div class="col-sm-5">
									<span class="bell">
									@if($avatar->img_weapon!=0)
										<img class="img-responsive img-rounded" src="{{ route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'weapon']) }}" alt="">
									@else
										<b>{{ trans('adminlte_lang::message.weapon') }}</b>
									@endif
									    <span id="weapon_value" class="bellnumbers">{{ $avatar->weapon }}</span>
									</span>
								</div>
								<div class="col-sm-2 vcenter">
									<button id="btn_weapon" type="button" class="btn btn-primary btn-circle" onclick="aumentar_nivel(this.id)" data-toggle="tooltip" data-placement="right" title="{{ trans('adminlte_lang::message.improve') }}">
										<i class="glyphicon glyphicon-plus"></i>
									</button>
								</div>
							</div>
						</div>
						<!-- BOTON LUCHAR Y SELECTOR ESTADO -->
						<div class="col-sm-3">
					    @if($avatar->estado == 'herido')
							<select name="role" class="form-control" disabled>
					    @else
							<select name="role" class="form-control" >
					    @endif
						    
						    @if($avatar->estado == 'activo')
						    	<option value="activo" selected>{{ trans('adminlte_lang::message.active') }}</option>
						    @else
						    	<option value="activo">{{ trans('adminlte_lang::message.active') }}</option>
						    @endif
						    
						    @if($avatar->estado == 'inactivo')
						    	<option value="inactivo" selected>{{ trans('adminlte_lang::message.inactive') }}</option>
						    @else
						    	<option value="inactivo">{{ trans('adminlte_lang::message.inactive') }}</option>
						    @endif
						    
						    @if($avatar->estado == 'herido')
						    	<option value="herido" disabled selected>{{ trans('adminlte_lang::message.hurt') }}</option>
						    @else
						    	<option value="herido" disabled>{{ trans('adminlte_lang::message.hurt') }}</option>
						    @endif
							</select>

						    @if($avatar->estado == 'activo')
			                <a id="btn_luchar" class="btn btn-block btn-danger btn-luchar" href="{{ route('luchar') }}">
						    @else
		                	<a id="btn_luchar" class="btn btn-block btn-danger btn-luchar disabled" href="{{ route('luchar') }}">
						    @endif
			                	<b>{{ trans('adminlte_lang::message.fight') }}</b>
			                </a>
						</div>
					</div>
				</div>
			</div>

		</div>


	@endif
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
					$("#modal_mensaje_texto").text("{{ trans('adminlte_lang::message.helpavatartext') }}");
					$("#modal_mensaje").show();
	    			return false; } );
    		});

    		$('select').on('change', function() {
			  	var url_estado = "{{ url ('cambiarEstadoAvatar') }}"
								.concat("/").concat({{ $avatar->user_id }})
								.concat("/").concat(this.value);
				$.ajax({
				  type: "GET",
				  url: url_estado
				})
				.done(function t(response) {
					var resp = response.split("\\");
					if(resp[0]=="error"){
						$("#modal_mensaje_titulo").text("Vida insuficiente");
						$("#modal_mensaje_texto").text(resp[1]);
						$("#modal_mensaje").show();
					}
					else{
						// El navegador cachea la imagen y no cambia si no
						// forzamos que recargue la imagen
						d = new Date();
            			$("#avatar_img").attr("src", resp[2]+"?"+d.getTime());

            			if(resp[1] == 'inactivo'){
            				$("#btn_luchar").addClass('disabled');
            			}
            			else{
            				$("#btn_luchar").removeClass('disabled');
            			}
					}
                });
			});

    		function aumentar_nivel(clicked_id){
    			var parte = get_valor(clicked_id);

                var url_tarea = "{{ url ('aumentarNivelAvatar') }}"
                                .concat("/").concat({{ $avatar->user_id }})
                                .concat("/").concat(parte);
				$.ajax({
				  	type: "GET",
        			async: false,
				  	url: url_tarea
				}).done(function t(response) {
					var resp = response.split("/");
					if(resp[0]=="error"){
						$("#modal_mensaje_titulo").text("{{ trans('adminlte_lang::message.notenoughgold') }}");
						$("#modal_mensaje_texto").text("{{ trans('adminlte_lang::message.getgoldmessage') }}");
						$("#modal_mensaje").show();
					}
					else{
						$("#header-oro").text(resp[0]);
	                	set_valor(parte,resp[1]);	
					}
                });
    		};

    		function get_valor(id){
    			return id.replace("btn_","");
    		}

    		function set_valor(parte,value){
    			$("#".concat(parte).concat("_value")).html(value);
    		}
    	</script>

    @endif
@endsection
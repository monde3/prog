@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.avatar') }}
@endsection

@section('contentheader_title')
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
		<div class="container-fluid spark-screen">
			<!--INFORMACION DEL AVATAR-->
			<div class="row">
				<div class="box box-solid box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">
							<b>Avatar</b>
						</h3>					
					</div>
					<div class="box-body">
						<div class="col-sm-3">
							<div class="item active">
								<a href="{{ route('editarImagenAvatar') }}" data-toggle="tooltip" data-placement="bottom" title="{{ trans('adminlte_lang::message.editimage') }}">
									<img id="avatar_img" class="img-responsive img-rounded" src="{{ route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'avatar']) }}" alt="alt">
								</a>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="row row-margin-bottom">
								<div class="col-sm-4">
								@if($avatar->img_head!=0)
									<img class="img-responsive img-rounded" src="{{ route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'head']) }}" alt="" data-toggle="tooltip" data-placement="left" title="{{ trans('adminlte_lang::message.head') }}">
								@else
									<p class="text-primary text-big">
										<b>{{ trans('adminlte_lang::message.head') }}</b>
									</p>
								@endif
								</div>
								<div class="col-sm-2 vcenter">
									<p id="head_value" class="text-primary text-big">
										<b>{{ $avatar->head }}</b>
									</p>
								</div>
								<div class="col-sm-2 vcenter">
									<button id="btn_head" type="button" class="btn btn-primary btn-circle" onclick="aumentar_nivel(this.id)" data-toggle="tooltip" data-placement="top" title="{{ trans('adminlte_lang::message.improve') }}">
										<i class="glyphicon glyphicon-plus"></i>
									</button>
								</div>
							</div>
							<div class="row row-margin-bottom">
								<div class="col-sm-4">
								@if($avatar->img_body!=0)
									<img class="img-responsive img-rounded" src="{{ route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'body']) }}" alt="" data-toggle="tooltip" data-placement="left" title="{{ trans('adminlte_lang::message.body') }}">
								@else
									<p class="text-primary text-big">
										<b>{{ trans('adminlte_lang::message.body') }}</b>
									</p>
								@endif
								</div>
								<div class="col-sm-2 vcenter">
									<p id="body_value" class="text-primary text-big">
										<b>{{ $avatar->body }}</b>
									</p>
								</div>
								<div class="col-sm-2 vcenter">
									<button id="btn_body" type="button" class="btn btn-primary btn-circle" onclick="aumentar_nivel(this.id)" data-toggle="tooltip" data-placement="top" title="{{ trans('adminlte_lang::message.improve') }}">
										<i class="glyphicon glyphicon-plus"></i>
									</button>
								</div>
							</div>
							<div class="row row-margin-bottom">
								<div class="col-sm-4">
								@if($avatar->img_hands!=0)
									<img class="img-responsive img-rounded" src="{{ route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'hands']) }}" alt="" data-toggle="tooltip" data-placement="left" title="{{ trans('adminlte_lang::message.hands') }}">
								@else
									<p class="text-primary text-big">
										<b>{{ trans('adminlte_lang::message.hands') }}</b>
									</p>
								@endif
								</div>
								<div class="col-sm-2 vcenter">
									<p id="hands_value" class="text-primary text-big">
										<b>{{ $avatar->hands }}</b>
									</p>
								</div>
								<div class="col-sm-2 vcenter">
									<button id="btn_hands" type="button" class="btn btn-primary btn-circle" onclick="aumentar_nivel(this.id)" data-toggle="tooltip" data-placement="top" title="{{ trans('adminlte_lang::message.improve') }}">
										<i class="glyphicon glyphicon-plus"></i>
									</button>
								</div>
							</div>
							<div class="row row-margin-bottom">
								<div class="col-sm-4">
								@if($avatar->img_feet!=0)
									<img class="img-responsive img-rounded" src="{{ route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'feet']) }}" alt="" data-toggle="tooltip" data-placement="left" title="{{ trans('adminlte_lang::message.feet') }}">
								@else
									<p class="text-primary text-big">
										<b>{{ trans('adminlte_lang::message.feet') }}</b>
									</p>
								@endif
								</div>
								<div class="col-sm-2 vcenter">
									<p id="hands_value" class="text-primary text-big">
										<b>{{ $avatar->feet }}</b>
									</p>
								</div>
								<div class="col-sm-2 vcenter">
									<button id="btn_foot" type="button" class="btn btn-primary btn-circle" onclick="aumentar_nivel(this.id)" data-toggle="tooltip" data-placement="top" title="{{ trans('adminlte_lang::message.improve') }}">
										<i class="glyphicon glyphicon-plus"></i>
									</button>
								</div>
							</div>
							<div class="row row-margin-bottom">
								<div class="col-sm-4">
								@if($avatar->img_weapon!=0)
									<img class="img-responsive img-rounded" src="{{ route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'weapon']) }}" alt="" data-toggle="tooltip" data-placement="left" title="{{ trans('adminlte_lang::message.weapon') }}">
								@else
									<p class="text-primary text-big">
										<b>{{ trans('adminlte_lang::message.weapon') }}</b>
									</p>
								@endif
								</div>
								<div class="col-sm-2 vcenter">
									<p id="weapon_value" class="text-primary text-big">
										<b>{{ $avatar->weapon }}</b>
									</p>
								</div>
								<div class="col-sm-2 vcenter">
									<button id="btn_weapon" type="button" class="btn btn-primary btn-circle" onclick="aumentar_nivel(this.id)" data-toggle="tooltip" data-placement="top" title="{{ trans('adminlte_lang::message.improve') }}">
										<i class="glyphicon glyphicon-plus"></i>
									</button>
								</div>
							</div>
						</div>
						<div class="col-sm-2">
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
			                <a id="btn_luchar" class="btn btn-block btn-danger" href="{{ route('luchar') }}">
						    @else
		                	<a id="btn_luchar" class="btn btn-block btn-danger disabled" href="{{ route('luchar') }}">
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

    		$('select').on('change', function() {
			  	var url_estado = "{{ url ('cambiarEstadoAvatar') }}"
								.concat("/").concat({{ $avatar->user_id }})
								.concat("/").concat(this.value);
				$.ajax({
				  type: "GET",
				  url: url_estado
				}).done(function t(response) {

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
    			var valor = get_valor(clicked_id);

                var url_tarea = "{{ url ('aumentarNivelAvatar') }}"
                                .concat("/").concat({{ $avatar->user_id }})
                                .concat("/").concat(valor);
				$.ajax({
				  type: "GET",
				  url: url_tarea
				}).done(function t(response) {

					var resp = response.split("/");
					if(resp[0]=="error"){
						$("#modal_mensaje_titulo").text("Oro insuficiente");
						$("#modal_mensaje_texto").text(resp[1]);
						$("#modal_mensaje").show();
					}
					else{
						$("#header-oro").text(resp[0]);
	                	set_valor(clicked_id,resp[1]);	
					}
                });
    		};

    		function get_valor(id){
    			if(id == "btn_head"){
    				return "head";
    			}
    			else if(id == "btn_body"){
    				return "body";
    			}
    			else if(id == "btn_hands"){
    				return "hands";
    			}
    			else if(id == "btn_foot"){
    				return "foot";
    			}
    			else if(id == "btn_weapon"){
    				return "weapon";
    			}
    		}

    		function set_valor(id,value){
    			if(id == "btn_head"){
                	$("#head_value").html("<b>".concat(value).concat("</b>"));
    			}
    			else if(id == "btn_body"){
                	$("#body_value").html("<b>".concat(value).concat("</b>"));
    			}
    			else if(id == "btn_hands"){
                	$("#hands_value").html("<b>".concat(value).concat("</b>"));
    			}
    			else if(id == "btn_foot"){
                	$("#foot_value").html("<b>".concat(value).concat("</b>"));
    			}
    			else if(id == "btn_weapon"){
                	$("#weapon_value").html("<b>".concat(value).concat("</b>"));
    			}
    		}
    	</script>

    @endif
@endsection
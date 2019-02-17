@if(isset($tarea))

	<div id="alumno_tarea_id" style="display:none;">{{ $tarea->id }}</div>
	<div class="row">
		<div class="col-md-4 col-md-offset-1">
			<div class="box box-solid box-primary">
				<div class="box-header with-border">
					
					<h3 class="box-title"><b>{{ $tarea->tarea->titulo }}</b></h3>					
				</div>
				<div class="box-body">
				    {{ $tarea->tarea->des_tarea }}
				</div>				
			</div>
		</div>
		<!-- (amondejar) CONTROL DEL TIEMPO MEDIANTE POMODOROS -->
		<div class="col-md-7">
			@if ($tarea->estado() === \App\AlumnoTarea::ACTIVA)
              	<button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#modal_pomodoro">
					<span class="glyphicon glyphicon-star" aria-hidden="true">&nbsp;</span>
					Iniciar
				</button>
            @elseif ($tarea->estado() === \App\AlumnoTarea::FINALIZADA)
              	<button type="button" class="btn btn-danger btn-lg" disabled="true">
					<span class="glyphicon glyphicon-star" aria-hidden="true">&nbsp;</span>
					Tarea finalizada
				</button>
            @elseif ($tarea->estado() === \App\AlumnoTarea::COMPLETADA)
              	<button type="button" class="btn btn-secondary btn-lg" disabled="true">
					<span class="glyphicon glyphicon-star" aria-hidden="true">&nbsp;</span>
					Tarea completada
				</button>
            @endif 
		</div>
	</div>

	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="box box-solid box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><b>Características</b></h3>
					<div class="box-tools pull-right">
            			<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          			</div>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
							 		<label class="">Propietario:</label>
							 	</div>
							 	<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
							 		{{ $tarea->tarea->propietario->apellidos }}, {{ $tarea->tarea->propietario->nombre }}
							 	</div>
						 	</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
							 		<label>Asignatura:</label>
							 	</div>
							 	<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
							 		{{ $tarea->tarea->asignatura->cod_asi }} {{ $tarea->tarea->asignatura->des_asi }}
							 	</div>
						 	</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
							 		<label>Curso Académico:</label>
							 	</div>
							 	<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
							 		{{ $tarea->tarea->curso_academico }}/{{ $tarea->tarea->curso_academico + 1 }}
							 	</div>
						 	</div>						 	
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
							 		<label>Fecha fin:</label>
							 	</div>
							 	<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
							 		{{ $tarea->tarea->fechaFormateada() }}
							 	</div>
						 	</div>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
							 		<label>Tiempo Estimado:</label>
							 	</div>
							 	<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
							 		{{ $tarea->tarea->tiempoTareaFormateado()}}
							 	</div>
						 	</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
							 		<label>Tiempo Trabajado:</label>
							 	</div>
							 	<div id="cronometro" class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
							 		{{ $tarea->tiempoTotalFormateado() }}
							 	</div>
						 	</div>						 	
						</div>
					</div>

					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
							 		<label>Ranking:</label>
							 	</div>
							 	<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
							 		@if($tarea->miRanking() === 1)
								 	    <i class='fa fa-trophy text-yellow' title="Eres el nº1"></i>
							 	    @endif
							 	    {{ $tarea->miRanking() }}/{{ $tarea->tarea->alumnos->count() }}
							 	</div>
						 	</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
							 		<label>Estado:</label>
							 	</div>
								<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
							 		@if ($tarea->estado() === \App\AlumnoTarea::ACTIVA)
				                    	<span class="label label-primary">{{ trans('adminlte_lang::message.activa') }}</span>
				                    @elseif ($tarea->estado() === \App\AlumnoTarea::EN_PROGRESO)
				                    	<span class="label label-success">{{ trans('adminlte_lang::message.enprogreso') }}</span>
				                    @elseif ($tarea->estado() === \App\AlumnoTarea::FINALIZADA)
				                    	<span class="label label-default">{{ trans('adminlte_lang::message.finalizada') }}</span>
				                    @elseif ($tarea->estado() === \App\AlumnoTarea::COMPLETADA)
				                    	<span class="label label-success">{{ trans('adminlte_lang::message.completada') }}</span>
				                    @elseif ($tarea->estado() === \App\AlumnoTarea::ERROR)
				                    	<span class="label label-danger">{{ trans('adminlte_lang::message.error') }}</span>
				                    @endif
							 	</div>
								<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
							 		@if ($tarea->estado() === \App\AlumnoTarea::ACTIVA)
										<a class="btn btn-small btn-success pull-right" href="{{ route('completarTarea', ['id' => $tarea->id]) }}">
											{{ trans('adminlte_lang::message.complete') }}
					                </a>
				                    @elseif ($tarea->estado() === \App\AlumnoTarea::ERROR)
				                    	<span class="label label-danger">{{ trans('adminlte_lang::message.error') }}</span>
				                    @endif
							 	</div>
						 	</div>					 		
						</div>						
					</div>
				</div>
			</div>
		</div>
	</div>



	<div class="row">
		<div class="col-md-5 col-md-offset-1">
			<div class="box box-solid box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><b>Tiempo registrado</b></h3>
					<div class="box-tools pull-right">
            			<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          			</div>
				</div>
				<div class="box-body">
					<table class="table" cellspacing="0" width="100%">
						<tbody>
							<td class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
								@if(!isset($tarea->tiemposTareas) or $tarea->tiemposTareas->count()==0)
									{{ trans('adminlte_lang::message.nocreatedtimes') }}
								@endif
							</td>
							<td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
					 		@if ($tarea->estado() === \App\AlumnoTarea::ACTIVA)
								<a class="btn btn-block btn-success"
									href="{{ route('crearTiempo', ['id' => $tarea->id]) }}">
		                    @else
								<a class="btn btn-block btn-danger disabled" href="#" >
		                    @endif
									{{ trans('adminlte_lang::message.create') }}
				                </a>
							</td>
						</tbody>
					</table>
					
					@if(isset($tarea->tiemposTareas) and $tarea->tiemposTareas->count()>0)
						<table id="tiempos" class="table table-hover" cellspacing="0" width="100%">
							<thead>
								<th class="col-xs-4 col-sm-4 col-md-4 col-lg-4">Inicio</th>
								<th class="col-xs-4 col-sm-4 col-md-4 col-lg-4">Fin</th>
								<th class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Tiempo</th>
								<th class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></th>

							</thead>
							@foreach ($tarea->tiemposTareas->sortByDesc('id') as $tt)
								<tbody>
									<td style="display:none;">{{ $tt->id }}</td>
									<td style="display:none;">{{ $tt->estado() }}</td>
									<td class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
										{{ $tt->fechaInicioFormateada() }}
									</td>
									<td class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
										{{ $tt->fechaFinFormateada() }}
									</td>
									<td class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
										{{ $tt->tiempoParcialFormateado() }}
									</td>
									<td class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
						 		@if ($tarea->estado() === \App\AlumnoTarea::ACTIVA)
										<a class="btn btn-block btn-info" href="{{ route('tiempotarea.edit', ['id' => $tt->id]) }}">
			                    @else
										<a class="btn btn-block btn-info disabled" href="#">
			                    @endif
						                  {{ trans('adminlte_lang::message.edit') }}
						                </a>
									</td>
								</tbody>
							@endforeach
						</table>
				    @endif
				</div>				
			</div>
		</div>

		<div class="col-md-5">
			<div class="box box-solid box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><b>Gráfica</b></h3>
					<div class="box-tools pull-right">
            			<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          			</div>
				</div>
				<div class="box-body">
					<div id="bar-chart" style="height: 300px;"></div>
				</div>
			</div>
		</div>
	</div>

	@include('layouts.alumno.modal_pomodoro')
	
@else
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="box box-solid box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><b>Mi tarea</b></h3>					
				</div>
				<div class="box-body">
				    No tiene acceso a la tarea
				</div>				
			</div>
		</div>
	</div>
@endif

@section('scripts')
@parent
	@if(isset($exito))
		<script>
	    	$(document).ready(function() {
					$("#modal_mensaje_titulo").text("¡Enhorabuena!");
					$("#modal_mensaje_texto").text("Has completado la tarea "+"{{ $tarea->tarea->titulo }}");
					$("#modal_mensaje_pie").text("+15 EXP  -  +50 ORO");
					$("#modal_mensaje").show();
	    		if ({{ $exito }} == 1) {
					$("#modal_mensaje_titulo").text("¡Enhorabuena!");
					$("#modal_mensaje_texto").text("Has completado la tarea "+"{{ $tarea->tarea->titulo }}");
					$("#modal_mensaje_pie").text("+15 EXP  -  +50 ORO");
					$("#modal_mensaje").show();
				}elseif ({{ $exito }} == 2) {
					$("#modal_mensaje_titulo").text("¡Enhorabuena, sube de nivel!");
					$("#modal_mensaje_texto").text("Has completado la tarea "+"{{ $tarea->tarea->titulo }}");
					$("#modal_mensaje_pie").text("+15 EXP  -  +50 ORO - Level UP");
					$("#modal_mensaje").show();
				}else{
					$("#modal_mensaje_titulo").text("¡Vaya!");
					$("#modal_mensaje_texto").text("Por alguna razón no se ha podido marcar la tarea como completada");
					$("#modal_mensaje_pie").text("-");
					$("#modal_mensaje").show();
				}
			})
	  	</script>
	@endif
@endsection

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
		<!-- CONTROL DEL TIEMPO NORMAL -->
		<div class="col-md-3">
			@if ($tarea->estado() === \App\AlumnoTarea::ACTIVA)
            	<a id="boton" class="btn btn-block btn-success btn-lg" href="{{ route('mistareasactivas.edit', ['alumno_tarea_id' => $tarea->id]) }}">
					Iniciar
            	</a>
            @elseif ($tarea->estado() === \App\AlumnoTarea::EN_PROGRESO)
	            <a id="boton" class="btn btn-block btn-danger btn-lg" href="{{ route('mistareasactivas.edit', ['alumno_tarea_id' => $tarea->id]) }}">
	            	Parar
	            </a>       
            @endif 
		</div>
		<!-- CONTROL DEL TIEMPO MEDIANTE POMODOROS -->
		<div class="col-md-3">
			@if ($tarea->estado() === \App\AlumnoTarea::ACTIVA)
              	<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modal_pomodoro">
					<span class="glyphicon glyphicon-star" aria-hidden="true">&nbsp;</span>
					Iniciar Pomodoro
				</button>
            @elseif ($tarea->estado() === \App\AlumnoTarea::FINALIZADA)
              	<button type="button" class="btn btn-danger btn-lg" disabled="true">
					<span class="glyphicon glyphicon-star" aria-hidden="true">&nbsp;</span>
					Tarea finalizada
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
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
							 		<label>Estado:</label>
							 	</div>
							 	<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
							 		@if ($tarea->estado() === \App\AlumnoTarea::ACTIVA)
				                    	<span class="label label-primary">{{ trans('adminlte_lang::message.activa') }}</span>
				                    @elseif ($tarea->estado() === \App\AlumnoTarea::EN_PROGRESO)
				                    	<span class="label label-success">{{ trans('adminlte_lang::message.enprogreso') }}</span>
				                    @elseif ($tarea->estado() === \App\AlumnoTarea::FINALIZADA)
				                    	<span class="label label-default">{{ trans('adminlte_lang::message.finalizada') }}</span>
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
									No hay tiempos registrados
								@endif
							</td>
							<td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
								<a class="btn btn-block btn-success" href="{{ route('crearTiempo', ['id' => $tarea->id]) }}">
				                	Crear
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
										<a class="btn btn-block btn-info" href="{{ route('tiempotarea.edit', ['id' => $tt->id]) }}">
						                  Editar
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

@if(isset($tarea))
	<div class="row">
		<div class="col-md-5 col-md-offset-1">
			<div class="box box-solid box-primary">
				<div class="box-header with-border">
					
					<h3 class="box-title"><b>{{ $tarea->tarea->titulo }}</b></h3>					
				</div>
				<div class="box-body">
				    {{ $tarea->tarea->des_tarea }}
				</div>				
			</div>
		</div>
		<div class="col-md-5">
			@if ($tarea->estado() === \App\AlumnoTarea::ACTIVA)
              <a class="btn btn-block btn-success btn-lg" href="{{ route('tareasalumno.edit', ['alumno_tarea_id' => $tarea->id]) }}">
                Iniciar
              </a>
            @elseif ($tarea->estado() === \App\AlumnoTarea::EN_PROGRESO)
              <a class="btn btn-block btn-danger btn-lg" href="{{ route('tareasalumno.edit', ['alumno_tarea_id' => $tarea->id]) }}">
                Parar
              </a>       
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
							 		{{ $tarea->curso_academico }}/{{ $tarea->curso_academico + 1 }}
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
						 			({{ $tarea->tarea->tiempo_estimado }} minutos)
							 	</div>
						 	</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
							 		<label>Tiempo Trabajado:</label>
							 	</div>
							 	<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
							 		{{ $tarea->tiempoTotalFormateado() }}
						 			({{ $tarea->tiempoTotal() }} minutos)
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
					@if(isset($tarea->tiemposTareas) and $tarea->tiemposTareas->count()>0)
						<table class="table table-hover" cellspacing="0" width="100%">
							<thead>
								<th class="col-xs-4 col-sm-4 col-md-4 col-lg-4">Inicio</th>
								<th class="col-xs-4 col-sm-4 col-md-4 col-lg-4">Fin</th>
								<th class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Tiempo</th>
								<th class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></th>

							</thead>
							@foreach ($tarea->tiemposTareas->sortByDesc('id') as $tt)

								<tbody>
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
										<a class="btn btn-block btn-info" href="#">
						                  Editar
						                </a>
									</td>
								</tbody>
							@endforeach
						</table>							
						
					@else
				    	No hay tiempos registrados
				    @endif
				</div>				
			</div>
		</div>
	</div>
	
@else
    <div class="panel-heading">Mi tareas</div>
	<div class="panel-body">
		No tiene acceso a la tarea
	</div>
@endif
@if(isset($tarea))
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="box box-solid box-primary">
				<div class="box-header with-border">
					
					<h3 class="box-title"><b>{{ $tarea->titulo }}</b></h3>					
				</div>
				<div class="box-body">
				    {{ $tarea->des_tarea }}
				</div>				
			</div>
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
							 		<label>Asignatura:</label>
							 	</div>
							 	<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
							 		{{ $tarea->asignatura->cod_asi }} {{ $tarea->asignatura->des_asi }}
							 	</div>
						 	</div>
						</div>
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
					</div>
					
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
							 		<label>Fecha fin:</label>
							 	</div>
							 	<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
							 		{{ $tarea->fechaFormateada() }}
							 	</div>
						 	</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
							 		<label>Tiempo Estimado:</label>
							 	</div>
							 	<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
							 		{{ $tarea->tiempoTareaFormateado()}}
							 	</div>
						 	</div>
						</div>
					</div>

					<div class="row">
						
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
							 		<label>Estado:</label>
							 	</div>
							 	<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
							 	    @if($tarea->activa())
							 			<span class="label label-primary">{{ trans('adminlte_lang::message.activa') }}</span>
							 		@else
				                    	<span class="label label-default">{{ trans('adminlte_lang::message.finalizada') }}</span>
				                    @endif
				                    
							 	</div>
						 	</div>					 		
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
							 		<label>Nº de alumnos:</label>
							 	</div>
							 	<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
							 	    {{ $tarea->alumnos->count() }}
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
					<h3 class="box-title"><b>Ranking Alumnos</b></h3>
					<div class="box-tools pull-right">
            			<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          			</div>
				</div>
				<div class="box-body">
					@if(isset($tarea->alumnos) and $tarea->alumnos->count()>0)
						<table class="table table-hover" cellspacing="0" width="100%">
							<thead>
								<th class="col-xs-1 col-sm-1 col-md-1 col-lg-1"/>
								<th class="col-xs-1 col-sm-1 col-md-1 col-lg-1"/>
								<th class="col-xs-7 col-sm-7 col-md-7 col-lg-7">Alumno</th>
								<th class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Tiempo</th>
							</thead>
							@foreach ($tarea->alumnos->sortBy(function($alumnoTarea){return $alumnoTarea->miRanking();}) as $alumno)

								<tbody>
									<td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
									@if($alumno->miRanking() === 1)
									 	    <i class='fa fa-trophy text-yellow'></i>
								 	    @endif
									</td>
									<td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
										{{ $alumno->miRanking() }}
									</td>
									<td class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
										{{ $alumno->alumno->apellidos }}, {{ $alumno->alumno->nombre }}
									</td>
									<td class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
										{{ $alumno->tiempoTotalFormateado() }}
									</td>									
								</tbody>
							@endforeach
						</table>							
						
					@else
				    	No hay alumnos registrados
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
	
	
@else
    <div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="box box-solid box-primary">
				<div class="box-header with-border">
					
					<h3 class="box-title"><b>Mi tarea {{$cod_tarea}}</b></h3>					
				</div>
				<div class="box-body">
				    No tiene acceso a la tarea
				</div>				
			</div>
		</div>
	</div>
@endif


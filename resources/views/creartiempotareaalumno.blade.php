@extends('layouts.app')

@section('htmlheader_title')
	Crear tiempo
@endsection

@section('contentheader_title')	
@endsection

@section('contentheader_breadcrumb')
	@if ($tarea->estado() === \App\AlumnoTarea::FINALIZADA)
		<li><a href="{{ url('mistareasfinalizadasalumno') }}"> {{ trans('adminlte_lang::message.mistareasfinalizadas') }}</a>
	@else
		<li><a href="{{ url('mistareasalumno') }}"> {{ trans('adminlte_lang::message.mistareas') }}</a>
	@endif
	<li><a href="{{ route('tareaalumno', ['alumno_tarea_id' => $tarea->id]) }}"> {{ $tarea->tarea->titulo }}</a>
	<li class="active">Crear tiempo registrado</li>
@endsection

@section('main-content')
	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-6 col-md-offset-1">
				<div class="box box-solid box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><b>Crear tiempo registrado</b></h3>
					</div>
					<div class="box-body">
						@if (count($errors) > 0)
					        <div class="alert alert-danger">
					            <strong>Whoops!</strong> {{ trans('adminlte_lang::message.problems') }}<br><br>
					            <ul>
					                @foreach ($errors->all() as $error)
					                    <li>{{ $error }}</li>
					                @endforeach
					            </ul>
					        </div>
					    @endif
					    {!! Form::model($tarea, ['route' => ['tiempotarea.store'], 'method' => 'POST']) !!}
					    	<div class="form-group">
							   	<div class="row">
									<label class="col-md-12 control-label" for="FechaInicial">Fecha inicio</label>
							    </div>
							    <div class="row">
								    <div class="col-md-6">
								        <div class="input-group">
								            
								        	<input type="text" class="form-control" id="AlumnoTareaId" name="AlumnoTareaId" value="{{ $tarea->id }}" style="display:none"/>


								            <div class="input-group-addon">
								                <i class="fa fa-calendar"></i>
								            </div>

								            <input type="text" class="form-control fecha" id="FechaInicial" name="FechaInicial" value="{{ \Carbon\Carbon::parse('now', 'Europe/Madrid')->subMinutes(30)->format('d/m/Y') }}" maxlength="10"/>

								        </div>
								    </div>
								    <div class="col-md-6">
									    <div class="input-group bootstrap-timepicker timepicker">
									    	<div class="input-group-addon">
									            <i class="fa fa-clock-o"></i>
									        </div>
									        <input type="text" class="form-control tiempo" id="TiempoInicial" name="TiempoInicial" value="{{ \Carbon\Carbon::parse('now', 'Europe/Madrid')->subMinutes(30)->format('H:i:s') }}" maxlength="8"/>        
									    </div>
									</div>
								</div>
							</div>
							<div class="form-group">
							   	<div class="row">
									<label class="col-md-12 control-label" for="FechaFinal">Fecha fin</label>
							    </div>
							    <div class="row">
								    <div class="col-md-6">
								        <div class="input-group">
								            <div class="input-group-addon">
								                <i class="fa fa-calendar"></i>
								            </div>
								            <input type="text" class="form-control fecha" id="FechaFinal" name="FechaFinal" value="{{ \Carbon\Carbon::parse('now', 'Europe/Madrid')->format('d/m/Y') }}" maxlength="10"/>
								        </div>
								    </div>
								    <div class="col-md-6">
									    <div class="input-group bootstrap-timepicker timepicker">
									    	<div class="input-group-addon">
									            <i class="fa fa-clock-o"></i>
									        </div>
									        <input type="text" class="form-control tiempo" id="TiempoFinal" name="TiempoFinal" value="{{ \Carbon\Carbon::parse('now', 'Europe/Madrid')->format('H:i:s') }}" maxlength="8"/>        
									    </div>
									</div>
								</div>
							</div>
							<button type="submit" class="btn btn-info">Crear</button>
							<a class="btn btn-info" href="{{ route('tareaalumno', ['alumno_tarea_id' => $tarea->id]) }}">
                  				Cancelar
                			</a>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
    @parent

    <script type="text/javascript">
		$('.fecha').datepicker({
			autoclose: true,
			format: 'dd/mm/yyyy',
			language: 'es',
			weekStart: 1
		});

		$('.tiempo').timepicker({
	    	showMeridian: false,
	    	showSeconds: true,
	    	minuteStep: 1,
	    	secondStep: 1
	    });
	</script>

@endsection



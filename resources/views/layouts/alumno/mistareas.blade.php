<table id="tareas" class="table table-hover" cellspacing="0" width="100%">
  <thead>
  	<th class="hidden-xs hidden-sm col-md-1 col-lg-1">Curso</th>
  	<th class="col-xs-10 col-sm-10 col-md-5 col-lg-5">Tarea</th>
    <th class="hidden-xs hidden-sm col-md-2 col-lg-2">Fecha fin</th>
    <th class="hidden-xs hidden-sm col-md-2 col-lg-2">Tiempo</th>
    <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1"/>
    <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1"/>
  </thead>
  <tbody>
  	@foreach ($tareas as $alumnoTarea)
      @if ($alumnoTarea->tarea->tiempoRestante() < \App\Tarea::ALERTA_ROJA)
        <!--menos que una semana-->
        <tr class="danger">
      @elseif($alumnoTarea->tarea->tiempoRestante() < \App\Tarea::ALERTA_AMARILLA)
        <!--menos que tres semanas-->
        <tr class="warning">
      @else
        <tr>
      @endif
	  	  <td style="display:none;">{{ $alumnoTarea->id }}</td>
	  		<td class="hidden-xs hidden-sm col-md-1 col-lg-1">
          {{ $alumnoTarea->tarea->curso_academico }}/{{ $alumnoTarea->tarea->curso_academico + 1 }}
        </td>
	  		<td class="col-xs-10 col-sm-10 col-md-5 col-lg-5">
          <p>
            {{ $alumnoTarea->tarea->asignatura->des_asi }}<br>
            @if ($alumnoTarea->estado() === \App\AlumnoTarea::ACTIVA)
              <span class="label label-primary">{{ trans('adminlte_lang::message.activa') }}</span>
            @elseif ($alumnoTarea->estado() === \App\AlumnoTarea::EN_PROGRESO)
              <span class="label label-success">{{ trans('adminlte_lang::message.enprogreso') }}</span>
            @elseif ($alumnoTarea->estado() === \App\AlumnoTarea::FINALIZADA)
              <span class="label label-default">{{ trans('adminlte_lang::message.finalizada') }}</span>
            @elseif ($alumnoTarea->estado() === \App\AlumnoTarea::ERROR)
              <span class="label label-danger">{{ trans('adminlte_lang::message.error') }}</span>
            @endif
            {{ $alumnoTarea->tarea->titulo }}
          </p>            
        </td>
        <td class="hidden-xs hidden-sm col-md-2 col-lg-2">
          {{ $alumnoTarea->tarea->fechaFormateada() }}
        </td>
        <td class="hidden-xs hidden-sm col-md-2 col-lg-2" class="cronometro">
          {{ $alumnoTarea->tiempoTotalFormateado() }}
        </td>
	  		<td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
          @if ($alumnoTarea->estado() === \App\AlumnoTarea::ACTIVA)
            <a class="btn btn-block btn-success" href="{{ route('mistareasactivas.edit', ['alumno_tarea_id' => $alumnoTarea->id]) }}">
              Iniciar
            </a>
          @elseif ($alumnoTarea->estado() === \App\AlumnoTarea::EN_PROGRESO)
            <a class="btn btn-block btn-danger" href="{{ route('mistareasactivas.edit', ['alumno_tarea_id' => $alumnoTarea->id]) }}">
              Parar
            </a>
          @endif                
        </td>
        <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
          <a class="btn btn-block btn-info" href="{{ route('tareaalumno', ['alumno_tarea_id' => $alumnoTarea->id]) }}">
            Ver
          </a>
        </td>
	  	</tr>
	  @endforeach
  </tbody>
</table>
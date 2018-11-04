<table class="table table-hover" cellspacing="0" width="100%">
  <thead>
  	<th class="hidden-xs hidden-sm col-md-1 col-lg-1">Curso</th>
  	<th class="col-xs-11 col-sm-11 col-md-7 col-lg-7">Tarea</th>
    <th class="hidden-xs hidden-sm col-md-2 col-lg-2">Fecha fin</th>
    <th class="hidden-xs hidden-sm col-md-1 col-lg-1">Tiempo</th>
    <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1"/>
  </thead>
  <tbody>
  	@foreach ($tareas as $alumnoTarea)
	  	<tr>
	  		<td class="hidden-xs hidden-sm col-md-1 col-lg-1">
          {{ $alumnoTarea->tarea->curso_academico }}/{{ $alumnoTarea->tarea->curso_academico + 1 }}
        </td>
	  		<td class="col-xs-11 col-sm-11 col-md-6 col-lg-6">
          <p>
            {{ $alumnoTarea->tarea->asignatura->des_asi }}<br>
            <span class="label label-default">{{ trans('adminlte_lang::message.finalizada') }}</span>
            {{ $alumnoTarea->tarea->titulo }}
          </p>            
        </td>
        <td class="hidden-xs hidden-sm col-md-2 col-lg-2">
          {{ $alumnoTarea->tarea->fechaFormateada() }}
        </td>
        <td class="hidden-xs hidden-sm col-md-2 col-lg-2">
          {{ $alumnoTarea->tiempoTotalFormateado() }}
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
<table class="table table-hover" cellspacing="0" width="100%">
  <thead>
  	<th class="hidden-xs hidden-sm col-md-1 col-lg-1">Curso</th>
  	<th class="col-xs-11 col-sm-11 col-md-8 col-lg-8">Tarea</th>
    <th class="hidden-xs hidden-sm col-md-2 col-lg-2">Fecha fin</th>
    <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1"/>
  </thead>
  <tbody>
  	@foreach ($tareas as $tarea)
	  	<tr>
	  		<td class="hidden-xs hidden-sm col-md-1 col-lg-1">
          {{ $tarea->curso_academico }}/{{ $tarea->curso_academico + 1 }}
        </td>
	  		<td class="col-xs-11 col-sm-11 col-md-8 col-lg-8">
          <p>
            {{ $tarea->asignatura->des_asi }}<br>
            <span class="label label-default">{{ trans('adminlte_lang::message.finalizada') }}</span>
            {{ $tarea->titulo }}
          </p>            
        </td>
        <td class="hidden-xs hidden-sm col-md-2 col-lg-2">
          {{ $tarea->fechaFormateada() }}
        </td>
        <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
          <a class="btn btn-block btn-info" href="{{ route('tareaprofesor', ['cod_tarea' => $tarea->cod_tarea]) }}">
            Ver
          </a>
        </td>
	  	</tr>
	  @endforeach
  </tbody>
</table>

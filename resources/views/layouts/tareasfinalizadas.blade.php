<form class="form-horizontal" role="form" method="POST" action="{{ url('mistareasactivas') }}" enctype="multipart/form-data">
  {{ csrf_field() }}
  
    @if(isset($tareas) and $tareas->count()>0)
      <table class="table table-hover" cellspacing="0" width="100%">
      <!--<table class="table table-hover">-->
        <thead>
        	<th class="col-xs-0 col-sm-0 col-md-1 col-lg-1">Curso</th>
        	<th class="col-xs-11 col-sm-11 col-md-7 col-lg-7">Tarea</th>
          <th class="col-xs-0 col-sm-0 col-md-2 col-lg-2">Fecha fin</th>
          <th class="col-xs-0 col-sm-0 col-md-1 col-lg-1">Tiempo</th>
          <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1"/>
        </thead>
        <tbody>
        	@foreach ($tareas as $t)
      	  	<tr>
      	  		<td class="col-xs-0 col-sm-0 col-md-1 col-lg-1">
                {{ $t->curso_academico }}/{{ $t->curso_academico+1 }}
              </td>
      	  		<td>
                <p>
                  {{ $t->tarea->asignatura->des_asi }}<br>
                  <span class="label label-default">{{ trans('adminlte_lang::message.finalizada') }}</span>
                  {{ $t->tarea->titulo }}
                </p>            
              </td>
              <td class="col-xs-0 col-sm-0 col-md-2 col-lg-2">
                {{ $t->tarea->fechaFormateada() }}
              </td>
              <td class="col-xs-0 col-sm-0 col-md-1 col-lg-1">
                {{ $t->tiempoTotal() }}m
              </td>
      	  		<td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                <a class="btn btn-block btn-info" href="{{ route('tareaalumno', ['alumno_tarea_id' => $t->id]) }}">
                  Ver
                </a>
              </td>
      	  	</tr>
      	  @endforeach
        </tbody>
      </table>
      
    @else
      No hay tareas finalizadas
    @endif
  
</form>
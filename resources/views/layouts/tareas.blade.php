<form class="form-horizontal" role="form" method="POST" action="{{ url('mistareasactivas') }}" enctype="multipart/form-data">
  {{ csrf_field() }}
  
    @if(isset($tareas) and $tareas->count()>0)
      <table class="table table-hover" cellspacing="0" width="100%">
      <!--<table class="table table-hover">-->
        <thead>
        	<th class="col-xs-0 col-sm-0 col-md-1 col-lg-1">Curso</th>
        	<th class="col-xs-10 col-sm-10 col-md-6 col-lg-6">Tarea</th>
          <th class="col-xs-0 col-sm-0 col-md-2 col-lg-2">Fecha fin</th>
          <th class="col-xs-0 col-sm-0 col-md-1 col-lg-1">Tiempo</th>
          <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1"/>
          <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1"/>
        </thead>
        <tbody>
        	@foreach ($tareas as $t)
            @if ($t->tarea->tiempoRestante() < \App\Tarea::ALERTA_ROJA)
              <!--menos que una semana-->
              <tr class="danger">
            @elseif($t->tarea->tiempoRestante() < \App\Tarea::ALERTA_AMARILLA)
              <!--menos que tres semanas-->
              <tr class="warning">
            @else
              <tr>
            @endif
      	  	
      	  		<td class="col-xs-0 col-sm-0 col-md-1 col-lg-1">
                {{ $t->curso_academico }}/{{ $t->curso_academico+1 }}
              </td>
      	  		<td>
                <p>
                  {{ $t->tarea->asignatura->des_asi }}<br>
                  @if ($t->estado() === \App\AlumnoTarea::ACTIVA)
                    <span class="label label-primary">{{ trans('adminlte_lang::message.activa') }}</span>
                  @elseif ($t->estado() === \App\AlumnoTarea::EN_PROGRESO)
                    <span class="label label-success">{{ trans('adminlte_lang::message.enprogreso') }}</span>
                  @elseif ($t->estado() === \App\AlumnoTarea::FINALIZADA)
                    <span class="label label-default">{{ trans('adminlte_lang::message.finalizada') }}</span>
                  @elseif ($t->estado() === \App\AlumnoTarea::ERROR)
                    <span class="label label-danger">{{ trans('adminlte_lang::message.error') }}</span>
                  @endif
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
                @if ($t->estado() === \App\AlumnoTarea::ACTIVA)
                  <a class="btn btn-block btn-success" href="{{ route('mistareasactivas.edit', ['alumno_tarea_id' => $t->id]) }}">
                    Iniciar
                  </a>
                @elseif ($t->estado() === \App\AlumnoTarea::EN_PROGRESO)
                  <a class="btn btn-block btn-danger" href="{{ route('mistareasactivas.edit', ['alumno_tarea_id' => $t->id]) }}">
                    Parar
                  </a>
                @endif                
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
      No hay tareas activas
    @endif
  
</form>
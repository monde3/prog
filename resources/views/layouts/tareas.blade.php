  <table class="table table-hover">
    @if(isset($tareas))
    <thead>
    	<th>Curso Académico</th>
    	<th>Tarea</th>
      <th>Tiempo</th>
      <th/>
      <th/>
      <th/>
    </thead>
    <tbody>
    	@foreach ($tareas as $t)
  	  	<tr>
  	  		<td>{{ $t->curso_academico }} / {{ $t->curso_academico + 1 }}</td>
  	  		<td>
            <p>{{ $t->des_asi}}</p>
            <p>{{ $t->titulo }}</p>
          </td>
          <td>{{ $t->tiempo }}</td>
  	  		<td><button type="button" class="btn btn-success">Empezar</button></td>
  	  		<td><button type="button" class="btn btn-danger">Parar</button></td>
  	  		<td><button type="button" class="btn btn-info">Detalles</button></td>
  	  	</tr>
  	@endforeach
    </tbody>
    @endif
  </table>
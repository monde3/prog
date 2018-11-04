<table class="table table-hover" cellspacing="0" width="100%">
  <thead>
  	<th class="col-xs-2 col-sm-2 col-md-2 col-lg-2">Dni</th>
    <th class="col-xs-7 col-sm-7 col-md-7 col-lg-7">Nombre</th>
    <th class="col-xs-2 col-sm-2 col-md-2 col-lg-2">Rol</th>
    <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1"/>
  </thead>
  <tbody>
  	@foreach ($usuarios as $usuario)
	  	<tr>
	  		<td class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
          {{ $usuario->dni }}
        </td>
        <td class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
          {{ $usuario->apellidos }}, {{ $usuario->nombre }}
        </td>	  		

        <td class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
          {{ $usuario->rol }}
        </td>
	  		<td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
          <a class="btn btn-block btn-info" href="{{ route('misusuarios.edit', ['id' => $usuario->id]) }}">
            Editar
          </a>
        </td>
	  	</tr>
	  @endforeach
  </tbody>
</table>
{{ $usuarios->links() }}
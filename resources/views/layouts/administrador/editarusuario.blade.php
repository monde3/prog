<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
		 		<label class="">DNI:</label>
		 	</div>
		 	<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
		 		{{ $usuario->dni }}
		 	</div>
	 	</div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
		 		<label class="">Nombre:</label>
		 	</div>
		 	<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
		 		{{ $usuario->apellidos }}, {{ $usuario->nombre }}
		 	</div>
	 	</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
		 		<label class="">Email:</label>
		 	</div>
		 	<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
		 		{{ $usuario->email }}
		 	</div>
	 	</div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
		 		<label class="">Rol:</label>
		 	</div>
		 	<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
		 		{{ $usuario->rol }}
		 	</div>
	 	</div>
	</div>
</div>

{!! Form::model($usuario, ['route' => ['misusuarios.update', $usuario->id], 'method' => 'PUT']) !!}
	<div class="row">
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				 		<label class="">Editar Rol:</label>
				 	</div>
			 	</div>
			</div>
			<div class="col-md-offset-1 col-xs-11 col-sm-11 col-md-11 col-lg-11">
				<div class="row">
					<div class="radio">
	        			<label>
	        				@if ($usuario->rol === 'alumno')
	          					<input type="radio" name="rol" id="rol1" value="alumno" checked="">
	          				@else
	          					<input type="radio" name="rol" id="rol1" value="alumno">
	          				@endif
		              		Alumno
	    		   		</label>
		          	</div>
		          	<div class="radio">
		            	<label>
		            		@if ($usuario->rol === 'profesor')
		         		    	<input type="radio" name="rol" id="rol2" value="profesor" checked="">
		         		    @else
		         		    	<input type="radio" name="rol" id="rol2" value="profesor">
		         		    @endif
		         			Profesor
		            	</label>
		          	</div>
		          	<div class="radio">
		            	<label>
		            		@if ($usuario->rol === 'administrador')
		              			<input type="radio" name="rol" id="rol3" value="administrador" checked="">
		              		@else
		              			<input type="radio" name="rol" id="rol3" value="administrador">
		              		@endif
		          			Administrador
		            	</label>
		            </div>
		        </div>
		    </div>
	    </div>
	</div>
	<button type="submit" class="btn btn-info">Actualizar</button>
	<a class="btn btn-info" href="{{ url('usuarios') }}">Cancelar</a>
{!! Form::close() !!}
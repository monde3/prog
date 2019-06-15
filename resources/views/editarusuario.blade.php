@extends('layouts.app')

@section('htmlheader_title')
	Administrar usuarios
@endsection

@section('contentheader_title')	
@endsection

@section('contentheader_breadcrumb')
	<li><a href="{{ url('usuarios') }}"> {{ trans('adminlte_lang::message.usuarios') }} </a>
	<li class="active">{{ $usuario->apellidos }}, {{ $usuario->nombre }}</li>
@endsection

@section('main-content')
	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="box box-solid box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><b>Editar usuario</b></h3>
					</div>
					<div class="box-body">
					    @include('layouts.administrador.editarusuario')
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

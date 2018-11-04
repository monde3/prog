@extends('layouts.app')

@section('htmlheader_title')
	Administrar usuarios
@endsection

@section('contentheader_title')	
@endsection

@section('contentheader_breadcrumb')
	<li class="active">{{ trans('adminlte_lang::message.usuarios') }}</li>
@endsection

@section('main-content')
	<div class="container spark-screen">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="box box-solid box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><b>Usuarios</b></h3>
					</div>
					<div class="box-body">
					    @include('layouts.administrador.usuarios')
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

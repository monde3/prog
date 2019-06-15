@extends('layouts.app')

@section('htmlheader_title')
	Mis Tareas
@endsection

@section('contentheader_title')	
@endsection

@section('contentheader_breadcrumb')
	<li class="active">{{ trans('adminlte_lang::message.mistareasfinalizadas') }}</li>
@endsection


@section('main-content')
	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="box box-solid box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><b>Mis Tareas Finalizadas</b></h3>
					</div>
					<div class="box-body">
						@if(isset($tareas) and $tareas->count()>0)
						    @if (Auth::user()->rol=='alumno')
								@include('layouts.alumno.misfinalizadas')
							@elseif (Auth::user()->rol=='profesor')
								@include('layouts.profesor.misfinalizadas')
							@endif
						@else
							No hay tareas finalizadas
						@endif
					    
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

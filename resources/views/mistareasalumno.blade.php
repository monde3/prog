@extends('layouts.app')

@section('htmlheader_title')
	Mis Tareas
@endsection


@section('main-content')
	<div class="container spark-screen">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="box box-solid box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><b>Mis Tareas</b></h3>
					</div>
					<div class="box-body">
						@include('layouts.tareas')
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

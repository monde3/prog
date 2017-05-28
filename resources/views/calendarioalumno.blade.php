@extends('layouts.app')

@section('htmlheader_title')
	Calendario
@endsection


@section('main-content')
	<div class="container spark-screen">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<div id='calendar'></div>
				</div>
			</div>
		</div>
	</div>
@endsection

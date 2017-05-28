@extends('layouts.app')

@section('htmlheader_title')
	Home
@endsection


@section('main-content')
	<div class="container spark-screen">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="box box-solid box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><b>Home</b></h3>						
					</div>
					<div class="box-body">
						@if (Auth::user()->rol=='alumno')
							@if (isset($misProximasTareas) and $misProximasTareas->count()>0)
								<ul>
									@foreach ($misProximasTareas as $tarea)
										<li>
											@if ($tarea->tarea->tiempoRestante() < \App\Tarea::ALERTA_ROJA)
												<!--menos que una semana-->
												<p class="text-red">
											@elseif($tarea->tarea->tiempoRestante() < \App\Tarea::ALERTA_AMARILLA)
												<!--menos que tres semanas-->
												<p class="text-yellow">
											@else
												<p>
											@endif
											La tarea "<em>{{ $tarea->tarea->titulo }}</em>"
											@if ($tarea->tarea->tiempoRestante()==0)
												ha terminado.
											@else
												termina en {{ $tarea->tarea->tiempoRestanteFormateado() }}.
											@endif
											</p>
										</li>
									@endforeach
								<ul>
							@else
								{{ trans('adminlte_lang::message.logged') }}
							@endif
						@else
							{{ trans('adminlte_lang::message.logged') }}
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

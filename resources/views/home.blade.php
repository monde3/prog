@extends('layouts.app')

@section('htmlheader_title')
	Inicio
@endsection

@section('contentheader_title')
@endsection

@section('main-content')
	@if (Auth::user()->activo and Auth::user()->rol == 'administrador')
		<div class="container spark-screen">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="box box-solid box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><b>Home</b></h3>						
						</div>
						<div class="box-body">
							{{ trans('adminlte_lang::message.welcomeadmin') }}							
						</div>
					</div>
				</div>
			</div>
		</div>

	@else
		<div class="container spark-screen">
			<div class="row">
				<div class="col-md-4 col-md-offset-1">
					<div class="row">
						<div class="box box-solid box-primary">
							<div class="box-header with-border">
								<h3 class="box-title"><b>Variables de DEBUG</b></h3>	
							</div>
							<div class="box-body">
								<b>Valor</b>
							</div>
						</div>
					</div>
					<!--INFORMACION DEL AVATAR-->
					@if (Auth::user()->rol == 'alumno')
					<div class="row">
						<div class="box box-solid box-primary">
							<div class="box-header with-border">
								<h3 class="box-title"><b>Avatar</b></h3>						
							</div>
							<div class="box-body">
								<div class="col-md-5">
									<div class="item active">
										<a href="{{ url('/avatar') }}">
											<img class="img-responsive img-rounded" src="{{ asset('images/avatar2.png') }}" alt="">
										</a>
									</div>
								</div>
								<div class="col-md-2">
									<p class="text-primary">
										<b>Cabeza</b></br>
										<b>Torso</b></br>
										<b>Manos</b></br>
										<b>Pies</b></br>
										<b>Arma</b>
									</p>
								</div>
								<div class="col-md-3">
									<p class="text-primary">
										<b>{{ $avatar->head }}</b></br>
										<b>{{ $avatar->body }}</b></br>
										<b>{{ $avatar->hands }}</b></br>
										<b>{{ $avatar->foot }}</b></br>
										<b>{{ $avatar->weapon }}</b>
									</p>
								</div>
								<div class="col-md-2">
									<p class="text-primary">
										<b>Nivel</b></br>
									</p>
									<p class="text-primary text-center" style="font-size: 3em">
										<b>{{ $nivelAvatar }}</b>
									</p>
								</div>
							</div>
						</div>
					</div>
					@endif
					<div class="row">
						<div class="box box-solid box-primary">
							<div class="box-header with-border">
								<h3 class="box-title"><b>Home</b></h3>						
							</div>
							<div class="box-body">
								@if (isset($misProximasTareas) and $misProximasTareas->count()>0)
									<ul>
										@foreach ($misProximasTareas as $tarea)
											<li>
												@if ($tarea->tiempoRestante() < \App\Tarea::ALERTA_ROJA)
													<!--menos de una semana-->
													<p class="text-red">
												@elseif ($tarea->tiempoRestante() < \App\Tarea::ALERTA_AMARILLA)
													<!--menos de tres semanas-->
													<p class="text-yellow">
												@else
													<p>
												@endif
												La tarea "<em>{{ $tarea->titulo }}</em>"
												@if ($tarea->tiempoRestante()==0)
													ha terminado.
												@else
													termina en {{ $tarea->tiempoRestanteFormateado() }}.
												@endif
												</p>
											</li>
										@endforeach
									<ul>
								@else
									{{ trans('adminlte_lang::message.logged') }}
								@endif
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="box box-solid box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><b>Calendario</b></h3>
						</div>
						<div class="box-body">
							<div id='calendar'></div>
						</div>
					</div>
				</div>
			</div>
		</div>

	@endif
@endsection

@section('scripts')
    @parent
    
<script>

	$(document).ready(function() {
		var urlCalendario = "{{ url('micalendario') }}"
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},
			firstDay: 1,
			navLinks: true, // can click day/week names to navigate views
			editable: false,
			eventLimit: true, // allow "more" link when too many events
			events: urlCalendario

			alert('Pomodoros: Evitar que al cerrar el modal de pomodoros queden las tareas abiertas');
		});
	});
</script>
	
@endsection

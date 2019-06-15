@extends('layouts.app')

@section('htmlheader_title')
	Inicio
@endsection

@section('contentheader_title')
@endsection

@section('main-content')
	@if (Auth::user()->activo and Auth::user()->rol == 'administrador')
		<div class="container-fluid spark-screen">
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
		<div class="container-fluid spark-screen">
			<div class="row">
				<div class="col-md-5">
					<!--INFORMACION DEL AVATAR-->
					@if (Auth::user()->rol == 'alumno')
					<div class="row">
						<div class="box box-solid box-primary">
							<div class="box-header with-border">
								<h3 class="box-title"><b>Avatar</b></h3>						
							</div>
							<div class="box-body">
								<div class="col-md-6">
									<div class="hovereffect">
										<a href="{{ url('/avatar') }}" data-toggle="tooltip" data-placement="bottom" title="{{ trans('adminlte_lang::message.avatar') }}">
											<img class="img-responsive img-rounded" src="{{ route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'avatar']) }}" alt="">
										</a>
									</div>
								</div>
								<div class="col-md-2">
									<div class="row">
										<span class="bell">
										@if($avatar->img_head!=0)
											<img class="img-responsive img-rounded" src="{{ route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'head']) }}" alt="">
										@else
											<b>{{ trans('adminlte_lang::message.head') }}</b>
										@endif
										    <span class="bellnumbers">{{ $avatar->head }}</span>
										</span>
									</div>
									<div class="row">
										<span class="bell">
										@if($avatar->img_body!=0)
											<img class="img-responsive img-rounded" src="{{ route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'body']) }}" alt="">
										@else
											<b>{{ trans('adminlte_lang::message.body') }}</b>
										@endif
										    <span class="bellnumbers">{{ $avatar->body }}</span>
										</span>
									</div>
									<div class="row">
										<span class="bell">
										@if($avatar->img_hands!=0)
											<img class="img-responsive img-rounded" src="{{ route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'hands']) }}" alt="">
										@else
											<b>{{ trans('adminlte_lang::message.hands') }}</b>
										@endif
										    <span class="bellnumbers">{{ $avatar->hands }}</span>
										</span>
									</div>
									<div class="row">
										<span class="bell">
										@if($avatar->img_feet!=0)
											<img class="img-responsive img-rounded" src="{{ route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'feet']) }}" alt="">
										@else
											<b>{{ trans('adminlte_lang::message.feet') }}</b>
										@endif
										    <span class="bellnumbers">{{ $avatar->feet }}</span>
										</span>
									</div>
									<div class="row">
										<span class="bell">
										@if($avatar->img_weapon!=0)
											<img class="img-responsive img-rounded" src="{{ route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'weapon']) }}" alt="">
										@else
											<b>{{ trans('adminlte_lang::message.weapon') }}</b>
										@endif
										    <span class="bellnumbers">{{ $avatar->weapon }}</span>
										</span>
									</div>
								</div>
								<div class="col-md-2 col-md-offset-1">
								    <span class="text-primary nivel-home">{{ trans('adminlte_lang::message.level') }} {{ $nivelAvatar }}</span>
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

				<div class="col-md-7">
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
		});
	});
</script>
	
@endsection

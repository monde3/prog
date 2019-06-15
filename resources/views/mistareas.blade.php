@extends('layouts.app')

@section('htmlheader_title')
	Mis Tareas
@endsection

@section('contentheader_title')	
@endsection

@section('contentheader_breadcrumb')
	<li class="active">{{ trans('adminlte_lang::message.mistareas') }}</li>
@endsection

@section('main-content')
	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="box box-solid box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><b>Mis Tareas</b></h3>
					</div>
					<div class="box-body">
					    @if( isset($tareas) and $tareas->count() > 0 )
						    @if (Auth::user()->rol=='alumno')
								@include('layouts.alumno.mistareas')
							@elseif (Auth::user()->rol=='profesor')
								@include('layouts.profesor.mistareas')
							@endif
						@else
							No hay tareas activas
						@endif
						
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
    @parent
	
	@if (Auth::user()->rol=='alumno')
	    <script>
	    	setInterval(function() {
				$("#tareas tbody tr").each(function () {
					var boton = $(this).find("td").eq(5).find("a").html().trim();
					if (boton == "Parar"){
						var alumno_tarea_id = $(this).find("td").eq(0).html();
						var self = this;
						var url_tarea = "{{ url ('cronometrotarea') }}".concat("/").concat(alumno_tarea_id);
						$.ajax({
				            type: "GET",
				            url: url_tarea
				        }).done(function t(response) {
				        	//console.log(response);
				        	$(self).find("td").eq(4).html(response)
	    				});	
					}
				});
			}, 1000); // Do this every 1 second

		</script>
	@endif
@endsection


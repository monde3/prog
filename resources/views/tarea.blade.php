@extends('layouts.app')

@section('htmlheader_title')
	Ver tarea
@endsection

@section('contentheader_title')	
@endsection

@section('contentheader_description')
    @if (Auth::user()->rol=='alumno')
	<a id="btn_help"><i class="fa fa-question-circle"></i></a>
    @endif
@endsection

<!-- RUTA PARTE SUPERIOR -->
@section('contentheader_breadcrumb')
    @if (Auth::user()->rol=='alumno')
		@if ($tarea->estado() === \App\AlumnoTarea::FINALIZADA)
			<li><a href="{{ url('mistareasfinalizadas') }}"> {{ trans('adminlte_lang::message.mistareasfinalizadas') }}</a>
		@else
			<li><a href="{{ url('mistareas') }}"> {{ trans('adminlte_lang::message.mistareas') }}</a>
		@endif
		<li class="active">{{ $tarea->tarea->titulo }}</li>
	@elseif (Auth::user()->rol=='profesor')
		@if($tarea->activa())
 			<li><a href="{{ url('mistareas') }}"> {{ trans('adminlte_lang::message.mistareas') }}</a>
 		@else
        	<li><a href="{{ url('mistareasfinalizadas') }}"> {{ trans('adminlte_lang::message.mistareasfinalizadas') }}</a>
        @endif
        <li class="active">{{ $tarea->titulo }}</li>
	@endif
@endsection

@section('main-content')
	<div class="container-fluid spark-screen">
		@if (Auth::user()->rol=='alumno')
			@include('layouts.alumno.tarea')
		@elseif (Auth::user()->rol=='profesor')
			@include('layouts.profesor.tarea')
		@endif
	</div>
@endsection

@section('scripts')
    @parent
    
    @if (Auth::user()->rol=='alumno')

    	<script>

			$(document).ready(function(){
				$('#btn_help').click( function(e) {
					e.preventDefault();
					$("#modal_mensaje_imagen").show();
					$("#modal_mensaje_imagen").attr("src","../images/help.png");
					$("#modal_mensaje_titulo").text("{{ trans('adminlte_lang::message.help') }}");
					$("#modal_mensaje_texto").text("{{ (Auth::user()->rol=='alumno') ? trans('adminlte_lang::message.helpusertask') : trans('adminlte_lang::message.helpproftask') }}");
					$("#modal_mensaje").show();
					return false; } );
			});

    		setInterval(function() {
	    		var boton = $("#boton").text().trim();
	    		if (boton == "Parar"){
					var alumno_tarea_id = $("#alumno_tarea_id").text().trim();
					var url_tarea = "{{ url ('cronometrotarea') }}".concat("/").concat(alumno_tarea_id);
					$.ajax({
			            type: "GET",
			            url: url_tarea
			        }).done(function t(response) {
			        	$("#cronometro").text(response);
    				});


			        $("#tiempos tbody tr").each(function () {
			        	var tiempo_tarea_id = $(this).find("td").eq(0).html();
			        	var estado = $(this).find("td").eq(1).html();
			        	var en_progreso = '{{ \App\TiempoTarea::EN_PROGRESO }}'
			        	
			        	if (estado == en_progreso){
			        		var self = this;
			        		url_tiempo = "{{ url ('cronometrotiempo') }}".concat("/").concat(tiempo_tarea_id);
			        		console.log(url_tiempo);
							
							$.ajax({
					            type: "GET",
					            url: url_tiempo
					        }).done(function t(response) {
					       		$(self).find("td").eq(4).html(response)
					        });

					        return false;	

			        	}
			        });
				}
			}, 1000); // Do this every 1 second

    	</script>


		<script type="text/javascript">
		    $(function() {
		    	$.ajax({
		            url: "{{ url ('graficaalumno', ['alumno_tarea_id' => $tarea->id]) }}",
		            dataType: 'json',
		            method: 'GET'
		            }).done(function(data) {

		            	/*
				         * BAR CHART
				         * ---------                 
				         */
				        
				        var bar_data = {
				            data: data,
				            color: "#3c8dbc"
				        };
				        $.plot("#bar-chart", [bar_data], {
				            grid: {
				                borderWidth: 1,
				                borderColor: "#f3f3f3",
				                tickColor: "#f3f3f3"
				            },
				            series: {
				                bars: {
				                    show: true,
				                    barWidth: 0.5,
				                    align: "center"
				                }
				            },
				            xaxis: {
				                mode: "categories",
				                tickLength: 0
				            }
				        });
			        	/* END BAR CHART */
		    	});    
		    });
		    /*
		     * Custom Label formatter
		     * ----------------------
		     */
		    function labelFormatter(label, series) {
		        return "<div style='font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;'>"
		                + label
		                + "<br/>"
		                + Math.round(series.percent) + "%</div>";
		    }
		</script>
	@elseif (Auth::user()->rol=='profesor')
		<script type="text/javascript">
		    $(function() {
		    	$.ajax({
		            url: "{{ url ('graficaprofesor', ['cod_tarea' => $tarea->cod_tarea]) }}",
		            dataType: 'json',
		            method: 'GET'
		            }).done(function(data) {

		            	/*
				         * BAR CHART
				         * ---------                 
				         */
				        
				        var bar_data = {
				            data: data,
				            color: "#3c8dbc"
				        };
				        $.plot("#bar-chart", [bar_data], {
				            grid: {
				                borderWidth: 1,
				                borderColor: "#f3f3f3",
				                tickColor: "#f3f3f3"
				            },
				            series: {
				                bars: {
				                    show: true,
				                    barWidth: 0.5,
				                    align: "center"
				                }
				            },
				            xaxis: {
				                mode: "categories",
				                tickLength: 0
				            }
				        });
			        	/* END BAR CHART */
		    	});    
		    });
		    /*
		     * Custom Label formatter
		     * ----------------------
		     */
		    function labelFormatter(label, series) {
		        return "<div style='font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;'>"
		                + label
		                + "<br/>"
		                + Math.round(series.percent) + "%</div>";
		    }
		</script>
	@endif
@endsection

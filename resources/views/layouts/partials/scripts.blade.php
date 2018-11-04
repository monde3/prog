<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.1.4 -->
<script src="{{ asset('/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="{{ asset('/js/bootstrap.min.js') }}" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="{{ asset('/js/app.min.js') }}" type="text/javascript"></script>

<!-- Datepicker Files -->
<script src="{{ asset('/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('/plugins/datepicker/locales/bootstrap-datepicker.es.js') }}"></script>

<!--Timepicker Files-->
<script src="{{ asset('/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>

<!--Full Calendar-->
<script src="{{ asset('/plugins/daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('/plugins/fullcalendar/fullcalendar.min.js') }}"></script>

<!-- FLOT CHARTS -->
<script src="{{ asset('/plugins/flot/jquery.flot.min.js') }}"></script>
<script src="{{ asset('/plugins/flot/jquery.flot.resize.min.js') }}"></script>
<script src="{{ asset('/plugins/flot/jquery.flot.categories.min.js') }}"></script>


<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience. Slimscroll is required when using the
      fixed layout.
 -->

<script>
    $(document).ready(function() {
      var mostrarModal = ({{ Auth::user()->mostrarModalFirstLogin() }});
      // Modal de primer login en plantilla app.blade.php
      if ( mostrarModal > 0)
          $("#first_login").modal();
      
    	var porcentaje = ({{ Auth::user()->porcentajeNivel() }}) + '%';
    	// Establecemos el progreso en el nivel actual
	    $("#level_bar").width(porcentaje);
    });
</script>

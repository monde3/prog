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
      // Activamos el item del menú izquierdo en función de la url en la que estemos
      var url_actual = window.location.protocol + "//" + window.location.host + window.location.pathname;
      var sideBarItem = $("#sidebar_item_home");
      if (url_actual.search("mistareasfinalizadas") > 0){
          sideBarItem = $("#sidebar_item_mistareasfinalizadas");
      }
      else if (url_actual.search("mistareas") > 0 || url_actual.search("tareaalumno") > 0){
          sideBarItem = $("#sidebar_item_mistareas");
      }
      else if (url_actual.search("usuarios") > 0){
          sideBarItem = $("#sidebar_item_usuarios");
      }
      sideBarItem.addClass('active');

      // Modal de primer login en plantilla app.blade.php
      var url_modal = "{{ url ('mostrarModalFirstLogin') }}";
        $.ajax({
          type: "GET",
          url: url_modal
        }).done(function t(response) {
          var mostrarModal = response;
          if ( mostrarModal > 0){
              $("#modal_mensaje_titulo").text("¡Felicidades!");
              $("#modal_mensaje_texto").text("Has conseguido 5 puntos de experiencia por el primer login del día");
              $("#modal_mensaje_pie").text("+5 EXP");
              $("#modal_mensaje").show();
          }else if ( mostrarModal < 0){
              $("#modal_mensaje_titulo").text("¡Vaya!");
              $("#modal_mensaje_texto").text("Has perdido 2 puntos de experiencia por haber permanecido más de un día sin entrar");
              $("#modal_mensaje_pie").text("-2 EXP");
              $("#modal_mensaje").show();
          }
      });
      
      // Establecemos el progreso en el nivel actual
    	var porcentaje = ({{ Auth::user()->avatar->porcentajeNivel() }}) + '%';
      $("#level_bar").width(porcentaje);
      $("#progress_li").prop('title', porcentaje);
    });
</script>

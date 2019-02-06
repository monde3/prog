<!--MODAL PARA MOSTRAR MENSAJES-->
<!--Comentamos el fade para que vaya más rápido en la máquina virtual-->
<!--<div class="modal fade" tabindex="-1" role="dialog" id="modal_pomodoro">-->
<div class="modal" tabindex="-1" role="dialog" id="modal_mensaje">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="modal_mensaje_titulo"></h3>
			</div>
				<div class="modal-body">
					<h4 class="modal-content" id="modal_mensaje_texto"></h4>
				</div>
				<div class="modal-footer">
					<div id="modal_mensaje_pie"></div>
					<input id="btn-cerrar-error" type="button" class="btn btn-primary" data-dismiss="modal" onclick="modalToggle()" value="Cerrar">
				</div>
			</div>
	</div>
</div>
@section('scripts')
	@parent
	<script>
		function modalToggle(){
			$("#modal_mensaje").modal('toggle');
		}
  	</script>
@endsection
<?php //echo array_debug($detalle); ?>
<?php echo form_open('almacen/eliminarCompra', array('class' => 'upd')); ?>
<input type="hidden" name="compra_id" value="<?php echo $ncompra; ?>" ?>
<div class="text-left">
	<div class="alert alert-danger">
	  ¿Está seguro que desea eliminar esta compra? <strong>Esta acción no se puede deshacer!</strong>
	</div>
	<button type="submit" id="btnDevolucionGuardar" class="btn btn-danger submit-ajax-button"><i class="glyphicon glyphicon-trash"></i> Eliminar</button>
</div>
<?php echo form_close(); ?>

<script>
$(document).ready(function(){
	$("#btnDevolucionGuardar").click(function(){

	})
})
</script>
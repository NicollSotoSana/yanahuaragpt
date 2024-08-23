<?php //echo array_debug($detalle); ?>
<?php echo form_open('almacen/editarCompra', array('class' => 'upd')); ?>
<input type="hidden" name="compra_id" value="<?php echo $ncompra; ?>" ?>
<div class="text-right">
	<input type="text" class="form-control" name="guiafact" value="<?php echo $act?>"><br/>
	<button type="submit" id="btneditcmp" class="btn btn-success submit-ajax-button"><i class="glyphicon glyphicon-check"></i> Guardar</button>
</div>
<?php echo form_close(); ?>

<script>
$(document).ready(function(){
	$("#btneditcmp").click(function(){

	})
})
</script>
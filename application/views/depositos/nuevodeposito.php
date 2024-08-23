<?php echo form_open('depositos/ajax/guardar', array('class' => 'upd')); 

if(!empty($npago)){
	$pago = str_pad($npago, 6, '0', STR_PAD_LEFT);
	echo '<input type="hidden" value="'.$npago.'" class="form-control" name="id_compra" placeholder="id_compra">';
}

?>
<input type="hidden" name="Comprobante_id" value="" ?>
<div class="col-md-4">
	<div class="form-group">
		<label>Banco</label>
		<input type="text" class="form-control" name="banco" placeholder="Banco">
	</div>
</div>
<div class="col-md-4">
	<div class="form-group">
		<label>Monto</label>
		<input type="text" class="form-control" name="monto" placeholder="Monto Depositado">
	</div>
</div>
<div class="col-md-4">
	<div class="form-group">
		<label>Fecha</label>
		<input type="date" class="form-control" name="fecha" value="<?php echo date("Y-m-d");?>">
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		<label>Nro. Operación/Voucher (Opcional)</label>
		<input type="text" class="form-control" name="operacion" placeholder="Nro. de Operación y/o voucher" value="">
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		<label>Origen de Dinero</label>
		<select class="form-control" name="origen_dinero">
			<option value="1">Caja Chica</option>
			<option value="2">Cuenta Bancaria</option>
		</select>
	</div>
</div>
<div class="form-group">
	<label>Descripcion</label>
	<input type="text" class="form-control" name="descripcion" placeholder="Descripción del Depósito" value="<?php echo (!empty($npago)) ? "Pago a cuenta de la compra #".$pago:"";?>">
</div>

<div class="text-right">
	<button data-confirm="Una vez realizado el registro del depósito este no se podrá modificar. ¿Desea continuar?" type="submit" id="btnDevolucionGuardar" class="btn btn-success submit-ajax-button"><i class="glyphicon glyphicon-refresh"></i> Guardar</button>
</div>
<?php echo form_close(); ?>


<script>
$(document).ready(function(){
	$("#btnDevolucionGuardar").click(function(){

	})
})
</script>
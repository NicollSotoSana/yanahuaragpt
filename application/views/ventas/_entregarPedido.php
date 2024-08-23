<?php //echo array_debug($detalle); ?>
<?php echo form_open('ventas/ajax/Entregar', array('class' => 'upd')); ?>
<input type="hidden" name="Comprobante_id" value="<?php echo $comprobante_id; ?>" ?>
<div class="alert alert-info">
	Ingrese la cantidad que <strong style="font-size: 14px;">realmente fue entregada</strong> para cada producto.
</div>
<table class="table">
	<thead>
		<th>Producto</th>
		<th>UDM</th>
		<th class="text-right">Cant. Pedida</th>
		<th class="text-right">Cant. Entregada</th>
	</thead>
	<tbody>
	<?php foreach($detalle as $k => $d): ?>
		<tr>
			<td>
				<?php echo $d->ProductoNombre; ?>
			</td>
			<td class="text-left"><?php echo $d->UnidadMedida_id; ?></td>
			<td class="text-right"><?php echo $d->Cantidad; ?></td>

			<td style="width:90px;">
				<input name="detalle_id[]" type="hidden" value="<?php echo $d->id; ?>" />
				<input name="detalle_devuelto[]" id="<?php echo 'd' . $k; ?>" type="text" class="form-control input-sm price text-right" value="<?php echo $d->Cantidad; ?>" />
				<input name="compid[]" type="hidden" value="<?php echo $d->Comprobante_Id; ?>" />
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<input type="checkbox" name="pagado" value="1" style="display:none;"> <b style="display:none;">Pedido pagado al momento de la entrega.</b>
<div class="text-right">
	<button data-confirm="Atencion! Verifique las cantidades ingresadas ya que no se podrán modificar en un futuro. ¿Desea continuar?" type="submit" id="btnDevolucionGuardar" class="btn btn-success submit-ajax-button"><i class="glyphicon glyphicon-check"></i> Guardar</button>
</div>
<?php echo form_close(); ?>


<script>
$(document).ready(function(){
	$("#btnDevolucionGuardar").click(function(){

	})
})
</script>
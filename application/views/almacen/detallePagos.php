<?php echo form_open('ventas/ajax/Devolver', array('class' => 'upd')); ?>
<table class="table">
	<thead>
		<th>Fecha</th>
		<th>Monto Pagado</th>
		<th>Nro. Operación</th>
		<th>Detalle</th>
	</thead>
	<tbody>
	<?php foreach($pagos as $k => $d): ?>
		<tr>
			<td><?php echo date("d/m/y", strtotime($d->fecha)); ?></td>
			<td><strong>S/. <?php echo $d->monto; ?></strong></td>
			<td><?php echo $d->nro_operacion; ?></td>
			<td><?php echo $d->descripcion; ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php echo form_close(); ?>


<script>
$(document).ready(function(){
	$("#btnDevolucionGuardar").click(function(){

	})
})
</script>
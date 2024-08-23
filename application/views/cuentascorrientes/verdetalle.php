<?php echo form_open('ventas/ajax/Devolver', array('class' => 'upd')); ?>
<input type="hidden" name="Comprobante_id" value="" ?>
<table class="table">
	<thead>
		<th>Fecha</th>
		<th>Monto Pagado</th>
		<th>Referencia</th>
		<th>Comprobante</th>
	</thead>
	<tbody>
	<?php foreach($deudas as $k => $d): ?>
		<tr>
			<td style="width:20px;">
				<?php echo date("d/m/y", strtotime($d->fecha)); ?>
			</td>
			<td style="width:20px;"><strong>S/. <?php echo $d->monto_pagado; ?></strong></td>
			<td style="width:40px;"> <?php echo $d->referencia ?></td>
			<td style="width:80px;">
				<?php
					if($d->id_comprobante != null){
				?>
					<a href="<?php echo base_url("ventas/comprobante/".$d->id_comprobante); ?>" target="_blank" class="btn btn-success">Ver Comprobante</a>
				<?php
					}else{
				?>
					<a href="#" class="btn btn-primary">Sin Comprobante</a>
				<?php
					}
				?>
			</td>
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
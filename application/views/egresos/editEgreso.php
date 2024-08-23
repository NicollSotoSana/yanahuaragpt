<div class="row">
	<div class="col-md-12">
	<form method="post" action="<?php echo base_url("egresos/updEgreso");?>">
		<div class="form-group">
			<?php //var_dump($egre);?>
			<label>Monto</label>
			<input type="text" name="monto" class="form-control" value="<?php echo $egre->monto_egreso;?>">
			<input type="hidden" name="ide" class="form-control" value="<?php echo $egre->id_egreso;?>">
		</div>
		<div class="form-group">
			<label>Descripcion</label>
			<input type="text" name="concepto" class="form-control" value="<?php echo $egre->concepto;?>">
		</div>
		<div class="form-group">
			<label>Origen Dinero</label>
			<select class="form-control" name="origen_dinero">
				<option value="1" <?php echo ($egre->origen_dinero == 1) ? 'selected':'';?>>Caja Chica</option>
				<option value="2" <?php echo ($egre->origen_dinero == 2) ? 'selected':'';?>>Cuenta Bancaria</option>
			</select>
		</div>
		<div class="form-group">
			<label>Fecha</label>
			<input type="date" name="fecha" class="form-control" value="<?php echo $egre->fecha;?>">
		</div>
		<center><button class="btn btn-success"><b><i class="icon-save"></i> Guardar</b></button></center>
	</div>
	</form>
</div>
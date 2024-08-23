<div class="row">
	<div class="col-md-12">
	<form method="post" action="<?php echo base_url("egresos/addEgreso");?>">
		<div class="form-group">
			<label>Monto</label>
			<input type="text" name="monto" class="form-control" value="">
		</div>
		<div class="form-group">
			<label>Descripcion</label>
			<input type="text" name="concepto" class="form-control" value="">
		</div>
		<div class="form-group">
			<label>Origen Dinero</label>
			<select class="form-control" name="origen_dinero">
				<option value="1">Caja Chica</option>
				<option value="2">Cuenta Bancaria</option>
				<option value="3">Caja Fuerte</option>
			</select>
		</div>
		<div class="form-group">
			<label>Fecha</label>
			<input type="date" name="fecha" class="form-control" value="<?php echo date('Y-m-d');?>">
		</div>
		<center><button class="btn btn-success"><b><i class="icon-save"></i> Guardar</b></button></center>
	</div>
	</form>
</div>
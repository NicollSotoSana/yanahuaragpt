<div class="row">
	<div class="col-md-12">
	<form method="post" action="<?php echo base_url("mantenimiento/updDoctor");?>">
    <div class="col-md-6">
		<div class="form-group">
			<label>Nombre Doctor</label>
			<input type="text" name="doctor" class="form-control" value="<?php echo $datos->doctor;?>">
			<input type="hidden" name="id_doctor" value="<?php echo $datos->id_doctor;?>">
		</div>
    </div>
	<div class="col-md-3">
		<div class="form-group">
			<label>Email Doctor</label>
			<input type="text" name="email" class="form-control" value="<?php echo $datos->email;?>">
		</div>
    </div>
	<div class="col-md-3">
		<div class="form-group">
			<label>Cumpleaños Dr.</label>
			<input type="date" name="cumpleanios" class="form-control" value="<?php echo $datos->cumpleanios;?>">
		</div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
			<label>% Comisión 1</label>
			<input type="number" name="porcentaje" class="form-control" value="<?php echo $datos->porcentaje;?>">
		</div>
    </div>
	<div class="col-md-3">
        <div class="form-group">
			<label>% Comisión 2</label>
			<input type="number" name="porcentaje2" class="form-control" value="<?php echo $datos->porcentaje2;?>">
		</div>
    </div>
		<center><button class="btn btn-success"><b><i class="icon-save"></i> Guardar</b></button></center>
	</div>
	</form>
</div>
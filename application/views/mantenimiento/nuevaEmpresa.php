<div class="row">
	<div class="col-md-12">
	<form method="post" action="<?php echo base_url("mantenimiento/addEmpresa");?>">
    <div class="col-md-7">
		<div class="form-group">
			<label>Nombre Empresa</label>
			<input type="text" name="empresa" class="form-control" value="">
		</div>
    </div>
    <div class="col-md-5">
        <div class="form-group">
			<label>Email</label>
			<input type="text" name="email" class="form-control" value="">
		</div>
    </div>
		<center><button class="btn btn-success"><b><i class="icon-save"></i> Guardar</b></button></center>
	</div>
	</form>
</div>
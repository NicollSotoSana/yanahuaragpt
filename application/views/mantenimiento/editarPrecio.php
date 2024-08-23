<div class="row">
	<div class="col-md-12">
	<form method="post" action="<?php echo base_url("mantenimiento/updLuna");?>">
		<div class="col-md-6">
			<div class="form-group">
				<label>Diseño</label>
				<input type="text" name="disenio" class="form-control" value="<?php echo $prec->disenio;?>">
				<input type="hidden" name="id_precio" class="form-control" value="<?php echo $prec->id_precio;?>">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Fabricación</label>
				<input type="text" name="fabricacion" class="form-control" value="<?php echo $prec->fabricacion;?>">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Material</label>
				<input type="text" name="material" class="form-control" value="<?php echo $prec->material;?>">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Serie</label>
				<input type="text" name="serie" class="form-control" value="<?php echo $prec->serie;?>">
			</div>
		</div>	
		<div class="col-md-6">
			<div class="form-group">
				<label>Tratamiento</label>
				<input type="text" name="tratamiento" class="form-control" value="<?php echo $prec->tratamiento;?>">
			</div>
		</div>	
		<div class="col-md-6">
			<div class="form-group">
				<label>Nombre</label>
				<input type="text" name="nombre" class="form-control" value="<?php echo $prec->nombre;?>">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Fotocromático</label>
				<input type="text" name="fotocromatico" class="form-control" value="<?php echo $prec->fotocromatico;?>">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Color Fotocromático</label>
				<input type="text" name="color_fotocromatico" class="form-control" value="<?php echo $prec->color_fotocromatico;?>">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Laboratorio</label>
				<input type="text" name="laboratorio" class="form-control" value="<?php echo $prec->laboratorio;?>">
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label>Costo Laboratorio</label>
				<input type="text" name="precio_compra" class="form-control" value="<?php echo $prec->precio_compra;?>">
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label>Precio</label>
				<input type="text" name="precio" class="form-control" value="<?php echo $prec->precio;?>">
			</div>
		</div>
		
		<div class="col-md-6">
			<div class="form-group">
				<label>Nombre Propio</label>
				<input type="text" name="nombre_propio_fin" class="form-control" value="<?php echo $prec->nombre_propio_fin;?>">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Nombre Laboratorio</label>
				<input type="text" name="nombre_lab_fin" class="form-control" value="<?php echo $prec->nombre_lab_fin;?>">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Estado</label>
				<select name="estado" class="form-control">
					<option value="1" <?php echo $prec->estado=='1' ? "selected":"";?>>Activo</option>
					<option value="0" <?php echo $prec->estado=='0' ? "selected":"";?>>Inactivo</option>
				</select>
			</div>
		</div>
		<center><button class="btn btn-success"><b><i class="icon-save"></i> Guardar</b></button></center>
	</div>
	</form>
</div>
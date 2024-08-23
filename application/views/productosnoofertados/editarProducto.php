<div class="row">
	<div class="col-md-12">
	<form method="post" action="<?php echo base_url("productosnoofertados/updProducto");?>">
		<div class="form-group">
			<label>Producto o Servicio No Ofertado</label>
			<input type="text" name="producto" class="form-control" value="<?php echo $datos->producto_solicitado;?>">
            <input type="hidden" name="id_producto" value="<?php echo $datos->id_prod_no_ofertado;?>">
		</div>

		<center><button class="btn btn-success"><b><i class="icon-save"></i> Guardar</b></button></center>
	</div>
	</form>
</div>
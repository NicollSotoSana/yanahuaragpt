<?php
	//array_debug($proveedor); 
?>
<script>
$(document).ready(function(){
	BuscarClientes();
	buscarRuc();
})
function buscarRuc(){
	$( "#rucemp" ).keyup(function( event ) {
		var ruc = $("#rucemp").val();

		if(ruc.length == 11){
			var form = $(this).closest("form");
			var block = $('<div class="block-loading" id="bloquecarga">');
        	form.prepend(block);

			$.get(base_url('services/getSunatData/'+ruc), function( data ) {
				if(data.success==true){
					var rs = data.nombre_o_razon_social;
			  		var dir = data.direccion_completa;
			  		var dep = data.departamento;
			  		var ciu = data.provincia;
			  		var dis = data.distrito;
			  		var condicionDom = data.condicion_de_domicilio;
			  		var estadoCont = data.estado_del_contribuyente;
			  		$("#txtCliente").val(rs);
			  		$("#direcli").val(dir);
			  		$("#depcli").val(dep);
			  		$("#ciucli").val(ciu);
			  		$("#discli").val(dis);
			  		$("#resulcond").html("");
			  		if(condicionDom=="HABIDO" && estadoCont=="ACTIVO"){
			  			$("#resulcond").append('<div class="alert alert-success">El contribuyente se encuentra <strong>Habido</strong> y <strong>Activo</strong>.</div>');
			  		}else{
			  			$("#resulcond").append('<div class="alert alert-danger">El contribuyente se encuentra <strong>'+condicionDom+'</strong> y su estado es: <strong>'+estadoCont+'</strong>.</div>');
			  		}

			  		$("#bloquecarga").remove();
				}else{
					alert(data.error);
					$("#bloquecarga").remove();
				}
			  	
			}, 'json');

		}
	});
}
function BuscarClientes(){
	var input = $("#txtCliente");

    input.autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: base_url('services/proveedores'),
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            id: item.id,
                            value: item.Nombre,
                            identidad: item.Identidad,
                            direccion: item.Direccion
                        }
                    }))
                }
            })
        },
        search  : function(){$(this).addClass('ui-autocomplete-loading');},
        open    : function(){$(this).removeClass('ui-autocomplete-loading');},
        select: function (e, ui) {
        	return false;
        }
    })
}
</script>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1><?php echo $proveedor == null ? "Nuevo Proveedor" : $proveedor->Nombre; ?></h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li><a href="<?php echo base_url('index.php/mantenimiento/proveedores'); ?>">Proveedores</a></li>
		  <li class="active"><?php echo $proveedor == null ? "Nuevo Item" : $proveedor->Nombre; ?></li>
		</ol>
		<div class="row">
			<div class="col-md-12">
				<?php echo form_open('mantenimiento/proveedorcrud', array('class' => 'upd')); ?>
				<?php if($proveedor != null): ?>
				<input type="hidden" name="id" value="<?php echo $proveedor->id; ?>" />
				<?php endif; ?>
				  <div class="well well-sm">(*) Campos obligatorios</div>
				  <div class="form-group">
				    <label>RUC</label>
				    <input autocomplete="off" id="rucemp" maxlength="11" name="Ruc" type="text" class="form-control" placeholder="Ingrese el RUC" value="<?php echo $proveedor != null ? $proveedor->Ruc : null; ?>" />
				  </div>
				  <div class="form-group">
				    <label>Nombre (*)</label>
				    <input id="txtCliente" autocomplete="off" name="Nombre" type="text" class="form-control required" placeholder="Nombre del proveedor" value="<?php echo $proveedor != null ? $proveedor->Nombre : null; ?>" />
				    <div id="resulcond"></div>
				  </div>
				  
				  <div class="form-group">
				    <label>DNI</label>
				    <input autocomplete="off" maxlength="8" name="Dni" type="text" class="form-control" placeholder="Ingrese el DNI" value="<?php echo $proveedor != null ? $proveedor->Dni : null; ?>" />
				  </div>
				  <div class="form-group">
				    <label>Télefono Principal</label>
				    <input autocomplete="off" name="Telefono1"  type="text" class="form-control" placeholder="Télefono Principal" value="<?php echo $proveedor != null ? $proveedor->Telefono1 : null; ?>" />
				  </div>
				  <div class="form-group">
				    <label>Télefono Adicional</label>
				    <input autocomplete="off" name="Telefono2"  type="text" class="form-control" placeholder="Télefono Adicional" value="<?php echo $proveedor != null ? $proveedor->Telefono2 : null; ?>" />
				  </div>
				  <div class="form-group">
				    <label>Correo</label>
				    <input autocomplete="off" name="Correo"  type="text" class="form-control" placeholder="Correo" value="<?php echo $proveedor != null ? $proveedor->Correo : null; ?>" />
				  </div>
				  <div class="form-group">
				    <label>Dirección</label>
				    <textarea name="Direccion" id="direcli" class="form-control" placeholder="Dirección"><?php echo $proveedor != null ? $proveedor->Direccion : null; ?></textarea>
				  </div>
				  <div class="form-group">
				    <label>Nro. Cuenta</label>
				    <input autocomplete="off" name="nro_cuenta"  type="text" class="form-control" placeholder="Nro. Cuenta" value="<?php echo $proveedor != null ? $proveedor->nro_cuenta : null; ?>" />
				  </div>
				  <div class="form-group">
				    <label>Código de Proveedor</label>
				    <input autocomplete="off" name="codigo_proveedor"  type="text" class="form-control" placeholder="Código de Proveedor" value="<?php echo $proveedor != null ? $proveedor->codigo_proveedor : null; ?>" />
				  </div>
				  <div class="clearfix text-right">
				  <?php if(isset($proveedor)): ?>
				  	<button type="button" class="btn btn-danger submit-ajax-button del" value="<?php echo base_url('index.php/mantenimiento/proveedorliminar/' . $proveedor->id); ?>">Eliminar</button>
			  	  <?php endif; ?>
				  	<button type="submit" class="btn btn-info submit-ajax-button">Guardar</button>
				  </div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>
<hr>
<center><h2>Detalle de Compras</h2></center>
<hr>
<div class="row">
	<table class="table table-border">
		<thead>
			<th>Fecha</th>
			<th>Monto</th>
			<th>Monto Cancelado</th>
			<th>Ver</th>
		</thead>
		<tbody>
			<?php
			foreach($deudas as $d){?>
			<tr>
				<td><?php echo $d->fecha;?></td>
				<td>S/. <?php echo $d->monto;?></td>
				<td>S/. <?php echo $d->monto_cancelado;?></td>
				<td><a target="_blank" href="<?php echo base_url("almacen/nuevaCompra/0/".$d->id_compra);?>" class="btn btn-success"><b>Ver Detalle</b></a></td>
			</tr>
			<?php
				}
			?>
		</tbody>
	</table>
</div>
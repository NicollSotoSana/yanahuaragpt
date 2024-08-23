<?php
	//array_debug($cliente); 
?>
<script>
$(document).ready(function(){
	BuscarClientes();
	buscarRuc();
	buscarDni();
})

function buscarDni(){
	$( "#dnicli" ).keyup(function( event ) {
		var dni = $("#dnicli").val();

		if(dni.length == 8){
			var form = $(this).closest("form");
			var block = $('<div class="block-loading" id="bloquecarga">');
        	form.prepend(block);

			$.get(base_url('services/getReniec/'+dni), function( data ) {
				if(data.success==true){
					//alert(data.result.DNI);
					var nom = data.result.Nombres+' '+data.result.Apellidos;
					$("#txtCliente").val(nom);
					$("#depcli").val(data.result.Departamento);
			  		$("#ciucli").val(data.result.Provincia);
			  		$("#discli").val(data.result.Distrito);
			  		$("#bloquecarga").remove();
				}else{
					//alert(data.error);
					$("#bloquecarga").remove();
				}
			  	
			}, 'json');

		}
	});
}


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
			  		var condicionDom = data.condicion;
			  		var estadoCont = data.estado;
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
                url: base_url('services/clientes'),
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
			<h2><?php echo $cliente == null ? "Nuevo Paciente" : $cliente->Nombre; ?></h2>
			<?php
			if($cliente != null){
				if(!empty($cliente->Dni)){
					echo '<a href="'.base_url("procesocliente/anmanesisPaciente/".$cliente->Dni."/".$cliente->tipo_doc).'" class="btn btn-success"><i class="icon icon-plus"></i> <b>Nueva Anamnesis</b></a>';
				}else{
					echo '<a href="'.base_url("procesocliente/anmanesisPaciente/".$cliente->Ruc."/".$cliente->tipo_doc).'" class="btn btn-success"><i class="icon icon-plus"></i> <b>Nueva Anamnesis</b></a>';
				}
				
			}
			
			?>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li><a href="<?php echo base_url('index.php/mantenimiento/clientes'); ?>">Pacientes</a></li>
		  <li class="active"><?php echo $cliente == null ? "Nuevo Item" : $cliente->Nombre; ?></li>
		</ol>
		<div class="row">
			<div class="col-md-12">
				<?php echo form_open('mantenimiento/clientecrud', array('class' => 'upd')); ?>
				<?php if($cliente != null): ?>
				<input type="hidden" name="id" value="<?php echo $cliente->id; ?>" />
				<?php endif; ?>
				  <div class="well well-sm">(*) Campos obligatorios</div>
				  <div class="col-md-3">
					  <div class="form-group">
					    <label>RUC </label>
					    <input autocomplete="off" maxlength="11" name="Ruc" type="text" class="form-control" placeholder="Ingrese el RUC" id="rucemp" value="<?php echo $cliente != null ? $cliente->Ruc : null; ?>" />
					  </div>
				  </div>
				  <div class="col-md-3">
					  <div class="form-group">
					    <label>Nombre/Razon Social (*)</label>
					    <input id="txtCliente" autocomplete="off" name="Nombre" type="text" class="form-control required" placeholder="Nombre del cliente" value="<?php echo $cliente != null ? $cliente->Nombre : null; ?>" />
					    <div id="resulcond"></div>
					  </div>
				  </div>
				  <div class="col-md-3">
					  <div class="form-group">
					    <label>DNI / CE / Pasaporte</label>
					    <input autocomplete="off" maxlength="9" name="Dni" id="dnicli" type="text" class="form-control" placeholder="Ingrese el DNI" value="<?php echo $cliente != null ? $cliente->Dni : null; ?>" />
					  </div>
				  </div>
				  <div class="col-md-3">
					  <div class="form-group">
					    <label>Fecha Nacimiento</label>
					    <input autocomplete="off" name="fecha_nac" type="date" class="form-control" placeholder="Fecha de Nacimiento" value="<?php echo $cliente != null ? $cliente->fecha_nac : null; ?>" />
					  </div>
				  </div>
				  
				  <div class="col-md-3">
					  <div class="form-group">
					    <label>Télefono Principal</label>
					    <input autocomplete="off" name="Telefono1"  type="text" class="form-control" placeholder="Télefono Principal" value="<?php echo $cliente != null ? $cliente->Telefono1 : null; ?>" />
					  </div>
				  </div>
				  <div class="col-md-3">
					  <div class="form-group">
					    <label>Télefono Adicional</label>
					    <input autocomplete="off" name="Telefono2"  type="text" class="form-control" placeholder="Télefono Adicional" value="<?php echo $cliente != null ? $cliente->Telefono2 : null; ?>" />
					  </div>
				  </div>
				  <div class="col-md-3">
						<div class="form-group">
							<label>Correo</label>
							<input autocomplete="off" name="Correo"  type="text" class="form-control" placeholder="Correo" value="<?php echo $cliente != null ? $cliente->Correo : null; ?>" />
						</div>
					</div>
					<div class="col-md-3">
					<div class="form-group">
						<label>Rubro de Trabajo</label>
						<select name="id_rubro_trabajo" id="id_rubro_trabajo" class="form-control">
							<option value="0">Seleccione</option>
							<?php
							
								foreach($rubros_trabajo as $rb){
									if($cliente != null && $rb->id_rubro_trabajo == $cliente->id_rubro_trabajo){
										echo '<option value="'.$rb->id_rubro_trabajo.'" selected>'.$rb->rubro_trabajo.'</option>';
									}else{
										echo '<option value="'.$rb->id_rubro_trabajo.'">'.$rb->rubro_trabajo.'</option>';
									}
									
								}
							?>
						</select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Profesión</label>
						<select name="id_profesion" id="id_profesion" class="form-control">
							<option value="0">Seleccione</option>
							<?php
								foreach($profesiones as $pf){
									if($cliente != null && $pf->id_profesion == $cliente->id_profesion){
										echo '<option value="'.$pf->id_profesion.'" selected>'.$pf->profesion.'</option>';
									}else{
										echo '<option value="'.$pf->id_profesion.'">'.$pf->profesion.'</option>';
									}
								}
							?>
						</select>
					</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Ocupación/Trabajo</label>
							<input autocomplete="off" name="trabajo"  type="text" class="form-control" placeholder="Ocupación/Trabajo" value="<?php echo $cliente != null ? $cliente->trabajo : null; ?>" />
						</div>
					</div>

				  	<div class="col-md-3">
						<div class="form-group">
							<label>Distrito</label>
							<select name="id_distrito" id="id_distrito" class="form-control">
								<option value="0">Seleccione</option>
								<?php
									foreach($distritos as $dt){
										if($cliente != null && $dt->id_distrito == $cliente->id_distrito){
											echo '<option value="'.$dt->id_distrito.'" selected>'.$dt->distrito.'</option>';
										}else{
											echo '<option value="'.$dt->id_distrito.'">'.$dt->distrito.'</option>';
										}
									}
								?>
							</select>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label>Ciudad</label>
							<select name="id_ciudad" id="id_ciudad" class="form-control">
								<option value="0">Seleccione</option>
								<?php
									foreach($ciudades as $dt){
										if($cliente != null && $dt->id_ciudad == $cliente->id_ciudad){
											echo '<option value="'.$dt->id_ciudad.'" selected>'.$dt->ciudad.'</option>';
										}else{
											echo '<option value="'.$dt->id_ciudad.'">'.$dt->ciudad.'</option>';
										}
									}
								?>
							</select>
						</div>
					</div>

				  <div class="col-md-12">
					  <div class="form-group">
					    <label>Dirección</label>
					    <textarea name="Direccion" id="direcli" class="form-control" placeholder="Dirección"><?php echo $cliente != null ? $cliente->Direccion : null; ?></textarea>
					  </div>
				  </div>

				  <div class="col-md-4" style="display:none;">
					  <div class="form-group">
					    <label>Departamento</label>
					    <input autocomplete="off" name="departamento"  type="text" class="form-control" placeholder="Departamento" value="<?php echo $cliente != null ? $cliente->departamento : null; ?>" />
					  </div>
				  </div>

				  <div class="col-md-4" style="display:none;">
					  <div class="form-group">
					    <label>Provincia</label>
					    <input autocomplete="off" name="provincia"  type="text" class="form-control" placeholder="Provincia" value="<?php echo $cliente != null ? $cliente->provincia : null; ?>" />
					  </div>
				  </div>

				  <div class="col-md-4" style="display:none;">
					  <div class="form-group">
					    <label>Distrito</label>
					    <input autocomplete="off" name="distrito"  type="text" class="form-control" placeholder="Distrito" value="<?php echo $cliente != null ? $cliente->distrito : null; ?>" />
					  </div>
				  </div>

				  <div class="col-md-12">
					  <div class="form-group">
					    <label>Usuario en Lista Negra?</label>
					    <input name="lista_negra"  type="radio" value="1" <?php echo $cliente != null && $cliente->lista_negra == 1 ? "checked" : null; ?> /> <b>Si</b>
						<input name="lista_negra"  type="radio" value="0" <?php echo $cliente != null && $cliente->lista_negra == 0 ? "checked" : null; ?> /> <b>No</b>
					  </div>
				  </div>

				  <div style="clear:both;"></div>
				  <div class="clearfix text-right">
				  <?php if(isset($cliente)): ?>
				  	<button type="button" class="btn btn-danger submit-ajax-button del" value="<?php echo base_url('index.php/mantenimiento/clienteeliminar/' . $cliente->id); ?>">Eliminar</button>
			  	  <?php endif; ?>
				  	<button type="submit" class="btn btn-info submit-ajax-button">Guardar</button>
				  </div>
				<?php echo form_close(); ?>
			</div>

		</div>
<?php //var_dump($evaluaciones);?>
		<div class="row" style="margin-top:20px;">
			<div class="col-md-12">
					<?php
					if($cliente != null){
					?>
					
					<div class="panel-group" id="accordion">
					  <div class="panel panel-default">
					    <div class="panel-heading">
					      <h4 class="panel-title">
					        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1"><b>Ver Historial del Paciente</b></a>
					      </h4>
					    </div>
					    <div id="collapse1" class="panel-collapse collapse in">
					      <div class="panel-body">
					      	<table class="table table-hover">
					      		<thead>
						      		<tr>
						      			<th style="text-align:center;">#</th>
						      			<th style="text-align:center;">Fecha</th>
						      			<th style="text-align:center;">Anamnesis</th>
						      			<th style="text-align:center;">Evaluación</th>
						      			<th style="text-align:center;">Orden Lab.</th>
						      			<th style="text-align:center;">Comprobante</th>
										<th style="text-align:center;">Encuesta</th>
						      		</tr>
					      		</thead>
					      		<tbody>
					      		<?php
					      			foreach($evaluaciones as $ev){
					      				if(isset($ev["idorden"])){
					      					$olab = "Ver ";
					      				}else{
					      					$olab = "Generar ";
					      				}
					      				echo '<tr>
					      				<td style="text-align:center;"><b>#'.str_pad($ev["ideval"], 6, "0",  STR_PAD_LEFT).'</b></td>
					      				<td style="text-align:center;"><b>'.date('d/m/Y', strtotime($ev["fecha"])).'</b></td>
					      				<td style="text-align:center;"><a href="'.base_url("procesocliente/veranamnesis/".$ev["id_anamnesis"]."/".$ev["ideval"]).'" class="btn btn-warning"><b><i class="icon icon-search"></i> Ver Anamnesis</b></a></td>

					      				<td style="text-align:center;"><a href="'.base_url("mantenimiento/evaluacion/".$cliente->id."/".$ev["ideval"]).'" class="btn btn-primary"><b><i class="icon icon-check"></i> Ver Evaluación</b></a></td>

					      				<td style="text-align:center;"><a href="'.base_url("mantenimiento/ordenLaboratorio/".$ev["ideval"]."/".$ev["idorden"]).'" class="btn btn-info"><b><i class="icon icon-file"></i> '.$olab.' Orden Lab.</b></a></td>';
					      				if(!empty($ev["idorden"])){
					      					echo '<td style="text-align:center;"><a href="'.base_url("ventas/comprobanteOrdenLab/".$ev["idorden"]."/".$ev["id_anamnesis"]).'" class="btn btn-success"><b><i class="icon icon-money"></i> Generar Comprobante</b></a></td>';
					      				}else{
					      					echo '<td style="text-align:center;"><button disabled type="button" class="btn btn-success"><b><i class="icon icon-money"></i> Generar Comprobante</b></button><br/><b>* Primero genere orden de laboratorio.</b></td>';
										  }
										  
										  echo '<td style="text-align:center;"><a href="'.base_url("encuesta/nuevaEncuesta/".$cliente->id."/".$ev["id_anamnesis"]).'" class="btn btn-primary"><b><i class="icon icon-check"></i> Encuesta</b></a><br><a href="'.base_url("encuesta/verEncuesta/".$cliente->id."/".$ev["id_anamnesis"]).'" ><b><i class="icon icon-search"></i> Ver Respuestas</b></a></td>';

										  
										  echo '</tr>';
					      				
					      			}
					      		?>
					      		</tbody>
					      	</table>
					      	
					      </div>
					    </div>
					  </div>
					   <div class="panel panel-default">
					    <div class="panel-heading">
					      <h4 class="panel-title">
					        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2"><b>Ver Comprobantes del Paciente</b></a>
					      </h4>
					    </div>
					    <div id="collapse2" class="panel-collapse collapse">
					      <div class="panel-body">
					      	<table class="table table-hover">
					      		<thead>
						      		<tr>
						      			<th>Tipo</th>
						      			<th>Serie</th>
						      			<th>Correlativo</th>
						      			<th>Fecha</th>
						      			<th>Total</th>
						      			<th>Estado</th>
						      			<th>Ver</th>
						      		</tr>
					      		</thead>
					      		<tbody>
					      			<?php
					      				foreach ($comprobantes as $comp) {
					      					echo '
					      						<tr>
					      							<td>'.$comp->tipocmp.'</td>
					      							<td>'.$comp->Serie.'</td>
					      							<td>'.$comp->Correlativo.'</td>
					      							<td>'.$comp->FechaEmitido.'</td>
					      							<td>S/. '.$comp->Total.'</td>
					      							<td>'.$comp->estadocmp.'</td>
					      							<td><a href="'.base_url().'ventas/comprobante/'.$comp->idecmp.'" class="btn btn-success"><b><i class="icon icon-search"></i> Ver</b></a></td>
					      						</tr>
					      					';
					      				}
					      			?>
					      		</tbody>
					      	</table>
					      </div>
					    </div>
					  </div>
				<div class="panel panel-default">
					    <div class="panel-heading">
					      <h4 class="panel-title">
					        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3"><b>Ver Deudas del Paciente</b></a>
					      </h4>
					    </div>
					    <div id="collapse3" class="panel-collapse collapse">
					      <div class="panel-body">
					<table id="example" class="table table-hover" cellspacing="0" width="100%">
			        <thead>
			            <tr>
			                <th>Fecha</th>
			                <th>Documento</th>
			                <th>Monto Deuda</th>
			                <th>Monto Cancelado</th>
			                <th>Monto Restante</th>
			                <th>Agregar Pago</th>
			                <th>Detalles</th>
			            </tr>
			        </thead>
			        <tfoot>
			            <tr>
			                <th>Fecha</th>
			                <th>Documento</th>
			                <th>Monto Deuda</th>
			                <th>Monto Cancelado</th>
			                <th>Monto Restante</th>
			                <th>Agregar Pago</th>
			                <th>Detalles</th>
			            </tr>
			        </tfoot>
			        <tbody>
       
						<?php
							foreach($deudas as $d){
								echo '<tr>
									<td>'.date("d/m/y", strtotime($d->fecha)).'</td>
									<td><a href="'.base_url().'ventas/comprobante/'.$d->comprobante_id.'" target="_blank">Comprobante #'.$d->Serie.' '.$d->Correlativo.'</a></td>
									<td>S/. '.$d->monto_deuda.'</td>
									<td>S/. '.$d->monto_cancelado.'</td>';

									if($d->monto_deuda == $d->monto_cancelado || $d->monto_deuda < $d->monto_cancelado){
										echo '<td style="color:green; font-weight:bold;">S/. '.number_format($d->monto_deuda-$d->monto_cancelado, 2).'</td><td><button class="btn btn-success addpago" type="button" disabled><i class="glyphicon glyphicon-plus"></i> Agregar Pago</button></td>';
									}else{
										echo '<td style="color:red; font-weight:bold;">S/. '.number_format($d->monto_deuda-$d->monto_cancelado, 2).'</td><td><button class="btn btn-success addpago" type="button" onclick="addPago('.$d->id_deuda.', \''.$d->Correlativo.'\');"><i class="glyphicon glyphicon-plus"></i> Agregar Pago</button></td>';
									}
									echo '<td><button class="btn btn-primary" onclick="verDetalle('.$d->id_deuda.');"><i class="glyphicon glyphicon-search"></i> Ver Detalle</button></td>
								</tr>';
							}
						?>
					 	</tbody>
    				</table>
					      	<center><a href="<?php echo base_url();?>mantenimiento/excelDeudas/<?php echo $cliente->id;?>" target="_blank" class="btn btn-success"><strong><i class="glyphicon glyphicon-save"></i> Descargar Resumen</a></strong></center>
					      </div>
					    </div>
					  </div>
					</div>
				<?php }?>
			</div>
		</div>
	</div>
	
</div>

<script type="text/javascript">
	function addPago(deuda_id, correlativonum){
		//alert(correlativonum);
		AjaxPopupModal('mAddPago', 'Agregar Pago', 'cuentacorriente/ajax/agregarPago', { id_deuda : deuda_id, id_cliente:<?php echo $cliente->id;?>, correlativ:correlativonum})
	}

	function verDetalle(deuda_id){
		//alert(correlativonum);
		AjaxPopupModal('mDetallePagos', 'Pagos Realizados', 'cuentacorriente/ajax/verDetalle', { id_deuda : deuda_id})
	}

</script>

<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h2><?php echo $cliente->Nombre;?></h2>
			<h4>Cuenta Corriente</h4>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li><a href="<?php echo base_url('index.php/mantenimiento/clientes'); ?>">Pacientes</a></li>
		  <li class="active">Cuenta Corriente</li>
		</ol>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table id="example" class="display" cellspacing="0" width="100%">
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
				</div>
			</div>
		</div>
	</div>

	<center><a href="<?php echo base_url();?>mantenimiento/excelDeudas/<?php echo $cliente->id;?>" target="_blank" class="btn btn-success"><strong><i class="glyphicon glyphicon-save"></i> Descargar Resumen</a></strong></center>

</div>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
    	$('#example').DataTable({
			"language": {
		        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
			}
		});
	} );

	function addPago(deuda_id, correlativonum){
		//alert(correlativonum);
		AjaxPopupModal('mAddPago', 'Agregar Pago', 'cuentacorriente/ajax/agregarPago', { id_deuda : deuda_id, id_cliente:<?php echo $cliente->id;?>, correlativ:correlativonum})
	}

	function verDetalle(deuda_id){
		//alert(correlativonum);
		AjaxPopupModal('mDetallePagos', 'Pagos Realizados', 'cuentacorriente/ajax/verDetalle', { id_deuda : deuda_id})
	}

</script>
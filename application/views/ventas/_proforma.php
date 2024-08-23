<!DOCTYPE html>
<html>
	<head>
		<title>Vista Preliminar</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<style type="text/css">
		body{
			font-family: "Arial";
			font-size:10px;
		}
		
		td{
			height:12px;}
	</style>
	</head>
	<body>
		<table style="width:100%;">
			<tr>
				<td><img src="http://consultoriadigitalperu.com/guillentamayo/logo3.png" style="width:200px;" width="200" height="70"></td>
				<td><p style="color:#505050; font-size:20px;text-align:center;font-weight:bold;">CENTRO OPTICO GUILLEN TAMAYO S.R.L.</p>
<p style="color:#505050;text-align:center;">MZA. F LOTE. 1 URB. LOS CEDROS (A MEDIA CUADRA CALLE CHULLO) AREQUIPA - AREQUIPA - YANAHUARA</p></td>
			</tr>
		</table>
		<hr>
		<table style="width:100%;border:2px solid #d3d3d3;padding:2px;">
			<tr>
				<td style="width:40%;">
					<table style="width:100%;">
						<tr>
							<td style="text-align:center;background:#174485;color:#fff;font-size:10px;font-weight:bold;">
								<strong>Señores:</strong><br/><?php echo $comprobante->ClienteNombre.'<br/>'; ?>
								<strong>DNI/RUC: </strong><?php echo (!empty($cli->Dni))?$cli->Dni:$cli->Ruc;?><br/>
								<strong>Telf.: </strong><?php echo (!empty($cli->Telefono1))?$cli->Telefono1:"";?><br/>
							</td>
						</tr>
						
					</table>				
				</td>
				<td>
					<table style="width:100%;border:1px solid #ddd;">
						<tr style="text-align:center;background:#174485;color:#fff;font-size:10px;font-weight:bold;">
							<td><strong><?php echo ($comprobante->ComprobanteTipo_id=='1') ? "Cotización":"Nota de Pedido";?>: </strong></td>
							<td>#<?php echo $comprobante->Correlativo;?></td>
							<?php
							if(!empty($comprobante->cotizacion)){
								$expo = explode("||", $comprobante->cotizacion);
							}
							?>
						</tr>
						<tr style="text-align:center;background:#174485;color:#fff;font-size:10px;font-weight:bold;">
							<td><strong>Medio de Pago: </strong></td>
							<td><?php echo ucwords($comprobante->mediopago);?></td>
						</tr>
					</table>				
				</td>
			</tr>
			<!--<tr>
				<td colspan="2">
					<table style="width:100%;">
						<tr>
							<td style="width:100px;height:30px;"><b>Señores:</b></td>
							<td style="border-bottom:1px solid #ddd;"><?php echo $comprobante->ClienteNombre; ?></td>
						</tr>
						<tr>
							<td style="width:100px;height:30px;"><b>Dirección:</b></td>
							<td style="border-bottom:1px solid #ddd;"><?php echo $comprobante->ClienteDireccion; ?></td>
						</tr>
						<tr>
							<td style="width:100px;height:30px;"><b>Emitido:</b></td>
							<td style="border-bottom:1px solid #ddd;"><?php echo $comprobante->FechaEmitido; ?></td>
						</tr>
					</table>
				</td>
			</tr>-->
			<tr>
				<td colspan="2">
					<table style="width:100%;">
						<tr>
							<td colspan="6" style="text-align:center;color:#fff;background:#174485;font-size:12px;font-weight:bold;">Productos</td>
						</tr>
						<tr>
							<th style="width:40px;text-align:left;border-bottom:1px solid #ddd;">Cantidad</th>
							<th style="width:40px;text-align:left;border-bottom:1px solid #ddd;">UND</th>
							<th style="height:30px;text-align:left;border-bottom:1px solid #ddd;">Descripción</th>
							
							<th style="width:80px;text-align:right;border-bottom:1px solid #ddd;">P. Unitario</th>
							<th style="width:80px;text-align:right;border-bottom:1px solid #ddd;">Total</th>
						</tr>
						<?php foreach($comprobante->Detalle as $k => $c):?>
						<?php if($c->UnidadMedida_id!="SER"){
							?>
						
						<tr style="line-height:11px;">
							<td style="border-bottom:1px solid #ddd;"><?php echo $c->Cantidad; ?></td>
							<td style="border-bottom:1px solid #ddd;"><?php echo $c->UnidadMedida_id; ?></td>
							<td style="height:20px;border-bottom:1px solid #ddd;"><?php echo $c->ProductoNombre; ?></td>
							
							<td style="text-align:right;border-bottom:1px solid #ddd;"><?php echo number_format($c->PrecioUnitario, 2); ?></td>
							<td style="text-align:right;border-bottom:1px solid #ddd;"><?php echo number_format($c->PrecioUnitario * $c->Cantidad, 2); ?></td>
						</tr>
						<?php } endforeach; ?>
						<!--<?php for($i= count($comprobante->Detalle)+1; $i <= $this->conf->Lineas; $i++):?>
						<tr>
							<td style="height:20px;border-bottom:1px solid #ddd;"></td>
							<td style="border-bottom:1px solid #ddd;"></td>
							<td style="border-bottom:1px solid #ddd;"></td>
							<td style="border-bottom:1px solid #ddd;"></td>
							<td style="border-bottom:1px solid #ddd;"></td>
						</tr>
						<?php endfor; ?>-->
					<?php
					if($comprobante->ComprobanteTipo_id=='1'){?>
						<tr>
							<td colspan="4" style="text-align:right;">
								<strong>Subtotal:</strong>
							</td>
							<td><?php $tot = $comprobante->Total-($comprobante->Total*0.18); echo "S/. ".number_format($tot, 2); ?></td>
						</tr>
						<tr>
							<td colspan="4" style="text-align:right;">
								<strong>IGV:</strong>
							</td>
							<td><?php 
							
							echo "S/. ".number_format($comprobante->Total-$tot, 2); ?></td>
						</tr>
					<?php }?>
						<tr>
							<td colspan="4" style="text-align:right;">
								<strong>Total:</strong>
							</td>
							<td>S/. <?php echo number_format($comprobante->Total, 2); ?></td>
						</tr>
					<?php
					if($comprobante->ComprobanteTipo_id=='1'){?>
						<tr>
							<td colspan="4" style="text-align:right;">
								<strong>Adelanto:</strong>
							</td>
							<td>S/. <?php echo number_format($comprobante->adelanto, 2); ?></td>
						</tr>
						<tr>
							<td colspan="4" style="text-align:right;">
								<strong>Restante:</strong>
							</td>
							<td>S/. <?php echo number_format(($comprobante->Total-$comprobante->adelanto), 2); ?></td>
						</tr>
					<?php }?>
						<?php
							if($comprobante->Estado==3){
								echo '<tr>
									<td colspan="5" style="text-align:center;"><span style="color:red; font-weight:bold; font-size:18px;">ANULADO</span></td>
								</tr>';
							}
						?>
					</table>

					
				</td>
			</tr>
			<!--<tr>
				<td colspan="2">
					<table style="width:100%;">
						<tr>
							<th style="height:30px;text-align:right;" colspan="4">Total</th>
							<td style="border:1px solid #ddd;text-align:right;width:140px;"><?php echo number_format($comprobante->Total, 2); ?></td>
						</tr>
					</table>
				</td>
			</tr>-->
			<hr>
			<tr>
				<td>
				Atentamente,<br/>			
				Centro Óptico Guillen Tamayo S.R.L.<br/>			
			</td>
			</tr>
		</table>
	</body>
</html>
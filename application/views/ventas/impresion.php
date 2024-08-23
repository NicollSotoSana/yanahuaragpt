<?php
	$format = '';
	if($comprobante->ComprobanteTipo_id == 2)
	{
		$format = $this->conf->BoletaFormato;
		
		// Correlativo Temporal
		if($comprobante->Correlativo == '') 
		{
			$comprobante->Serie = $this->conf->SBoleta;
			$comprobante->Correlativo = str_pad($this->conf->NBoleta, $this->conf->Zeros, '0', STR_PAD_LEFT);
		}	
	}
	if($comprobante->ComprobanteTipo_id == 3)
	{
		$format = $this->conf->FacturaFormato;
		
		//Correlativo Temporal
		if($comprobante->Correlativo == '') 
		{
			$comprobante->Serie = $this->conf->NFactura;
			$comprobante->Correlativo = str_pad($this->conf->NFactura, $this->conf->Zeros, '0', STR_PAD_LEFT);
		}
	}

	if($comprobante->ComprobanteTipo_id == 5)
	{
		$format = $this->conf->GuiaFormato;
		
		//Correlativo Temporal
		if($comprobante->Correlativo == '') 
		{
			$comprobante->Serie = $this->conf->NGuia;
			$comprobante->Correlativo = str_pad($this->conf->NGuia, $this->conf->Zeros, '0', STR_PAD_LEFT);
		}
	}

	if($comprobante->ComprobanteTipo_id == 6)
	{
		$format = $this->conf->OrdenFormato;
		
		//Correlativo Temporal
		if($comprobante->Correlativo == '') 
		{
			$comprobante->Serie = $this->conf->NOrden;
			$comprobante->Correlativo = str_pad($this->conf->NOrden, $this->conf->Zeros, '0', STR_PAD_LEFT);
		}
	}

	if($comprobante->ComprobanteTipo_id == 7)
	{
		$format = $this->conf->ProformaFormato;
		
		//Correlativo Temporal
		if($comprobante->Correlativo == '') 
		{
			$comprobante->Serie = $this->conf->NProf;
			$comprobante->Correlativo = str_pad($this->conf->NProf, $this->conf->Zeros, '0', STR_PAD_LEFT);
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Vista Preliminar</title>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    
		<?php echo link_tag('assets/bootstrap/css/print.css'); ?>		
		<?php echo link_tag('assets/bootstrap/css/ui-lightness/jquery-ui-1.10.4.custom.min.css'); ?>
		
		<script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/jquery-1.10.2.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/jquery-ui-1.10.3.custom.min.js'); ?>"></script>
		
		<script>
			var id = <?php echo $comprobante->id; ?>;
			var base_url = '<?php echo base_url('index.php') . '/'; ?>';
			
			$(document).ready(function(){
				$(".absolute").draggable();
				$(".row").resizable({
					 resize: function(event, ui) {
					 	ui.size.width = ui.originalSize.width;
					 }
				})
				$(".ui-icon-gripsmall-diagonal-s,.ui-icon-gripsmall-diagonal-se").remove();
				$("#btnImprimirCancelar").click(function(){
					<?php if($comprobante->id != 0){ ?>
						if($(this).data('impresion') == '1')
						{
							$.post(base_url + 'ventas/ajax/CancelarImpresion',{
								id: id
							}, function(r){
								Volver();						
							}, 'json');						
						}else
						{
							Volver();						
						}
					<?php }else{ ?>
						Volver();
					<?php } ?>
				})
				$("#btnImprimir").click(function(){
					var f = '';
					<?php if($comprobante->ComprobanteTipo_id==6){?>
					
						f += '#fecha?' + $("#fecha").attr('style') + '|';
					f += '#cliente?' + $("#cliente").attr('style') + '|';
					f += '#ruc?' + $("#ruc").attr('style') + '|';
					f += '#direccion?' + $("#direccion").attr('style') + '|';
					f += '#inventario1?' + $("#inventario1").attr('style') + '|';
					f += '#inventario2?' + $("#inventario2").attr('style') + '|';
					f += '#inventario3?' + $("#inventario3").attr('style') + '|';
					f += '#inventario4?' + $("#inventario4").attr('style') + '|';
					f += '#inventario5?' + $("#inventario5").attr('style') + '|';
					f += '#detalle?' + $("#detalle").attr('style') + '|';
					f += '#detalle .row?';

					//PARA PROFORMA

					<?php }elseif($comprobante->ComprobanteTipo_id==7){?>


					f += '#fecha?' + $("#fecha").attr('style') + '|';
					f += '#cliente?' + $("#cliente").attr('style') + '|';
					f += '#ruc?' + $("#ruc").attr('style') + '|';
					f += '#direccion?' + $("#direccion").attr('style') + '|';
					f += '#distrito?' + $("#distrito").attr('style') + '|';
					f += '#ciudad?' + $("#ciudad").attr('style') + '|';
					f += '#departamento?' + $("#departamento").attr('style') + '|';
					f += '#vendedor?' + $("#vendedor").attr('style') + '|';
					f += '#serie?' + $("#serie").attr('style') + '|';
					f += '#SubTotal?' + $("#SubTotal").attr('style') + '|';
					f += '#total?' + $("#total").attr('style') + '|';
					f += '#TotalLetras?' + $("#TotalLetras").attr('style') + '|';
					f += '#IvaTotal?' + $("#IvaTotal").attr('style') + '|';
					f += '#Iva?' + $("#Iva").attr('style') + '|';
					f += '#SubTotal2?' + $("#SubTotal2").attr('style') + '|';
					f += '#descuento?' + $("#descuento").attr('style') + '|';
					f += '#Iva?' + $("#Iva").attr('style') + '|';
					f += '#detalle?' + $("#detalle").attr('style') + '|';
					f += '#placa?' + $("#placa").attr('style') + '|';
					f += '#condicion?' + $("#condicion").attr('style') + '|';
					f += '#orden_trabajo2?' + $("#orden_trabajo2").attr('style') + '|';
					f += '#nro_bomba?' + $("#nro_bomba").attr('style') + '|';
					f += '#tipo_bomba?' + $("#tipo_bomba").attr('style') + '|';
					f += '#codigo?' + $("#codigo").attr('style') + '|';
					f += '#motor2?' + $("#motor2").attr('style') + '|';
					f += '#nro_bomba2?' + $("#tipo_bomba2").attr('style') + '|';
					f += '#tipo_bomba2?' + $("#tipo_bomba2").attr('style') + '|';
					f += '#orden_trabajo2?' + $("#orden_trabajo2").attr('style') + '|';
					f += '#moneda?' + $("#moneda").attr('style') + '|';
					f += '#detalle .row?';
					

					<?php }else{?>

						f += '#fechaFo?' + $("#fechaFo").attr('style') + '|';
					f += '#fecha?' + $("#fecha").attr('style') + '|';
					f += '#cliente?' + $("#cliente").attr('style') + '|';
					f += '#ruc?' + $("#ruc").attr('style') + '|';
					f += '#direccion?' + $("#direccion").attr('style') + '|';
					f += '#distrito?' + $("#distrito").attr('style') + '|';
					f += '#ciudad?' + $("#ciudad").attr('style') + '|';
					f += '#departamento?' + $("#departamento").attr('style') + '|';
					f += '#vendedor?' + $("#vendedor").attr('style') + '|';
					f += '#serie?' + $("#serie").attr('style') + '|';
					f += '#SubTotal?' + $("#SubTotal").attr('style') + '|';
					f += '#total?' + $("#total").attr('style') + '|';
					f += '#TotalLetras?' + $("#TotalLetras").attr('style') + '|';
					f += '#IvaTotal?' + $("#IvaTotal").attr('style') + '|';
					f += '#Iva?' + $("#Iva").attr('style') + '|';
					f += '#SubTotal2?' + $("#SubTotal2").attr('style') + '|';
					f += '#descuento?' + $("#descuento").attr('style') + '|';
					f += '#cuentasoles?' + $("#cuentasoles").attr('style') + '|';
					f += '#cuentadolares?' + $("#cuentadolares").attr('style') + '|';
					f += '#Iva?' + $("#Iva").attr('style') + '|';
					f += '#detalle?' + $("#detalle").attr('style') + '|';
					f += '#placa?' + $("#placa").attr('style') + '|';
					f += '#condicion?' + $("#condicion").attr('style') + '|';
					f += '#orden_trabajo?' + $("#orden_trabajo").attr('style') + '|';
					f += '#detalle .row?';
					<?php }?>
					$('#detalle .row').each(function(){
						f += $(this).attr('style') + '!';
					})
					
					if($('#detalle .row').size() > 0)
					{
						f = f.substring(0,f.length - 1);
					}

					var button = $(this);

					<?php if($comprobante->id != 0){ ?>
						$.post(base_url + 'ventas/ajax/Imprimir',{
							id: id,
							f: f
						}, function(r){
							if(r.response)
							{
								PrepararHoja();
								window.print();
								alert('La impresión ha sido enviada, lo redireccionaremos a la página anterior.');
								Volver();
							}else
							{
								alert(r.message);
							}
						}, 'json');
					<?php }else{ ?>
						$.post(base_url + 'mantenimiento/ajax/GuardarConfiguracionImpresora',{
							tipo: <?php echo $comprobante->ComprobanteTipo_id; ?>,
							f: f
						}, function(r){
							if(!r.response)
							{
								alert(r.message);
							}else
							{
								PrepararHoja();
								window.print();	
							}
						}, 'json');
					<?php } ?>
				})
				SetearImpresion();
			})
			
			function Volver()
			{
				<?php if($comprobante->id == 0){ ?>
					window.location.href = base_url + 'mantenimiento/configuracion';
				<?php }else{ ?>
					window.location.href = base_url + 'ventas/comprobante/' + id;
				<?php } ?>
			}
			function PrepararHoja()
			{
				$(".hidden").hide();
				$("body, .absolute, .row").css('background', 'none');
				$(".row,#container").css('border', 'none');
				$("#container").css('font-size', '9px');
			}
			function SetearImpresion()
			{
				var f = '<?php echo $format; ?>'.split('|');
				for(var i = 0; i < f.length; i++)
				{
					var data = f[i].split('?');
					if(data[0] != '#detalle .row')
					{
						$(data[0]).attr('style', data[1]);						
					}else
					{
						var w = data[1].split('!');
						$('#detalle .row').each(function(i){
							$(this).attr('style',w[i]);
						})
					}
				}
			}
		</script>
		<style type="text/css" media="print">
			.no-print{ display: none; font-size:12px;}
			@page{margin: 0;padding:0; font-size:12px;}
		</style>
	</head>
	<body>
		<img class="no-print" id="boceto" src="../../../uploads/<?php echo $comprobante->ComprobanteTipo_id == 2 ? $this->conf->BoletaFoto : $this->conf->FacturaFoto; ?>" />
		<div id="botones" class="no-print">
			<button data-impresion="<?php echo $comprobante->Impresion; ?>" id="btnImprimirCancelar">Cancelar</button>
			<button data-impresion="<?php echo $comprobante->Impresion; ?>" id="btnImprimir">Imprimir</button>
		</div>
		<div id="container">
			<div class="margin-left margin no-print"></div>
			<div class="margin-right margin no-print"></div>
			
			<div title="Nombre del Cliente" class="absolute" id="cliente" style="left:80px;top:127px;"><?php echo $clienteImp->Nombre; ?></div>
			
			<div title="<?php echo $comprobante->ComprobanteTipo_id == 3 ? 'RUC' : 'DNI' ?> del Cliente" class="absolute" id="ruc" class="text-right"  style="left:420px;top:127px;"><?php echo $comprobante->ClienteIdentidad; ?></div>
			<div title="Dirección del Cliente" class="absolute" id="direccion" style="left:80px;top:153px;"><?php echo $clienteImp->Direccion; ?></div>
			
		<?php
		if($comprobante->ComprobanteTipo_id!=7&&$comprobante->ComprobanteTipo_id!=2){
		?>	
			<div title="Sub Total" class="absolute" id="SubTotal" class="text-right" style="left:440px;top:410px;<?php echo ($comprobante->ComprobanteTipo_id==7) ? "display:none;":"";?>">
				<?php if($comprobante->moneda=="usd" || $comprobante->moneda=="Usd"){
					echo number_format(($comprobante->SubTotal/$this->conf->tipo_cambio), 2);
				}else{
					echo number_format($comprobante->SubTotal, 2);
				}
				?></div>
				<?php
 
$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 
//Salida: Viernes 24 de Febrero del 2012
 
?>
				<div title="fechaFo" class="absolute" id="fechaFo" style="left:80px;top:147px;"><?php echo date('d', strtotime($comprobante->FechaEmitido))." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$meses[date('n', strtotime($comprobante->FechaEmitido))-1]. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".date('y', strtotime($comprobante->FechaEmitido)) ; ?></div>
		<?php 
	}else{
		?>

		<div title="Fecha de Emisión" class="absolute" id="fecha" style="left:80px;top:147px;"><?php echo ToDate($comprobante->FechaEmitido); ?></div>
		<?php
	}
		?>
			<div title="Total a Pagar" class="absolute" id="total" class="text-right" style="left:540px;top:410px;"><?php if($comprobante->moneda=="usd" || $comprobante->moneda=="Usd"){echo number_format(($comprobante->Total/$this->conf->tipo_cambio), 2);}else{echo number_format($comprobante->Total, 2);} ?></div>
			<div title="Impuesto Total"  class="absolute" id="IvaTotal" class="text-right" style="left:400px;top:410px;">
				<?php if(($comprobante->moneda=="usd" || $comprobante->moneda=="Usd")&&$comprobante->ComprobanteTipo_id == 3){echo number_format(($comprobante->IvaTotal/$this->conf->tipo_cambio), 2);}elseif($comprobante->ComprobanteTipo_id == 3){echo number_format($comprobante->IvaTotal, 2);} ?></div>
			
			<div title="Importe Total en Letras" class="absolute" id="TotalLetras" style="left:90px;top:192px;"><?php 
			if(($comprobante->moneda=="usd" || $comprobante->moneda=="Usd")&&$comprobante->ComprobanteTipo_id == 3){
				$tvle = number_format(($comprobante->Total/$this->conf->tipo_cambio), 2);
				$val = $EnLetras->ValorEnLetras($tvle, $this->conf->moneda->Nombre);
				echo str_replace("SOLES", "DOLARES", $val);
			}elseif($comprobante->ComprobanteTipo_id == 3){
				echo $EnLetras->ValorEnLetras($comprobante->Total, $this->conf->moneda->Nombre);
				}?></div>

			<!-- SOLO EN PROFORMAS -->

			<?php
				if($comprobante->ComprobanteTipo_id==7){
			?>
				<div title="Nro. Bomba" class="absolute" id="nro_bomba" class="text-right" style="left:500px;top:63px;"><?php echo $comprobante->nro_bomba; ?></div>
				<div title="Tipo Bomba" class="absolute" id="tipo_bomba" class="text-right" style="left:500px;top:63px;"><?php echo $comprobante->tipo_bomba; ?></div>
				<div title="Codigo" class="absolute" id="codigo" class="text-right" style="left:500px;top:63px;"><?php echo $comprobante->codigo; ?></div>
				<div title="Motor" class="absolute" id="motor2" class="text-right" style="left:500px;top:63px;"><?php echo $comprobante->motor2; ?></div>
				<div title="Orden trabajo2" class="absolute" id="orden_trabajo2" class="text-right" style="left:500px;top:63px;"><?php echo $comprobante->orden_trabajo2; ?></div>
<!--
				<div title="Nro. Bomba" class="absolute" id="nro_bomba2" class="text-right" style="left:500px;top:63px;"><?php echo $comprobante->nro_bomba; ?></div>
				<div title="Tipo Bomba" class="absolute" id="tipo_bomba2" class="text-right" style="left:500px;top:63px;"><?php echo $comprobante->tipo_bomba; ?></div>
				<div title="Codigo" class="absolute" id="codigo2" class="text-right" style="left:500px;top:63px;"><?php echo $comprobante->codigo; ?></div>
				<div title="Motor" class="absolute" id="motor22" class="text-right" style="left:500px;top:63px;"><?php echo $comprobante->motor2; ?></div>-->
			<?php }?>
			<!-- SOLO EN ORDEN DE TRABAJO -->
			<?php
				if($comprobante->ComprobanteTipo_id==6){
			?>
			

			<div title="inventario1" class="absolute" id="inventario1" style="left:90px;top:192px;">
				<table>
				<?php 
				$salbul = explode(',', $comprobante->inventario); 
				for($i=1; $i<=11; $i++){
					if(in_array($i, $salbul)){
						echo "/<br/>";
					}else{
						echo "X<br/>";
					}
				}
				?>
				</table>
			</div>

			<div title="inventario2" class="absolute" id="inventario2" style="left:90px;top:192px;">
				<table>
				<?php 
				$salbul = explode(',', $comprobante->inventario); 
				for($i=12; $i<=22; $i++){
					if(in_array($i, $salbul)){
						echo "/<br/>";
					}else{
						echo "X<br/>";
					}
				}
				?>
				</table>
			</div>

			<div title="inventario3" class="absolute" id="inventario3" style="left:90px;top:192px;">
				<table>
				<?php 
				$salbul = explode(',', $comprobante->inventario); 
				for($i=23; $i<=33; $i++){
					if(in_array($i, $salbul)){
						echo "/<br/>";
					}else{
						echo "X<br/>";
					}
				}
				?>
				</table>
			</div>

			<div title="inventario4" class="absolute" id="inventario4" style="left:90px;top:192px;">
				<table>
				<?php 
				$salbul = explode(',', $comprobante->inventario); 
				for($i=34; $i<=44; $i++){
					if(in_array($i, $salbul)){
						echo "/<br/>";
					}else{
						echo "X<br/>";
					}
				}
				?>
				</table>
			</div>
			<div title="inventario5" class="absolute" id="inventario4" style="left:90px;top:192px;">
				<table>
				<?php 
				$salbul = explode(',', $comprobante->inventario); 
				for($i=34; $i<=44; $i++){
					if(in_array($i, $salbul)){
						echo "/<br/>";
					}else{
						echo "X<br/>";
					}
				}
				?>
				</table>
			</div>
			<?php
				}
			?>
			<!-- SOLO EN GUIAS -->


			<div title="Detalle del Comprobante"  style="left:90px;top:192px;" class="absolute" id="detalle">
				<div <?php echo 'style="width:60px;"'; ?> class="row">
					<?php foreach($comprobante->Detalle as $k => $c):?>
						<div ><?php echo $c->Cantidad; ?> <?php
				if($comprobante->ComprobanteTipo_id!=2){
			?> <span style="margin-left:35%;"> <?php echo $c->UnidadMedida_id; ?></span><?php }?></div>
					<?php endforeach;?>
				</div>

				<div <?php echo 'style="width:280px;"'; ?> class="row">
					<?php foreach($comprobante->Detalle as $k => $c):?>
						<div><?php echo $c->ProductoNombre; ?></div>
					<?php endforeach;?>
				</div>
				
				<div <?php echo 'style="width:74px;"'; ?> class="row">
					<?php foreach($comprobante->Detalle as $k => $c):?>
						<div class="text-right">
							<?php 
						if($comprobante->moneda=="usd" || $comprobante->moneda=="Usd"){
							echo number_format(($c->PrecioUnitario/$this->conf->tipo_cambio), 2);
						}else{
							echo number_format($c->PrecioUnitario, 2);} 
					?></div>
					<?php endforeach;?>
				</div>
				
				<div <?php echo 'style="width:74px;"'; ?> class="row">
					<?php foreach($comprobante->Detalle as $k => $c):?>
						<div class="text-right">
					<?php 
						if($comprobante->moneda=="usd" || $comprobante->moneda=="Usd"){
							echo number_format(($c->PrecioUnitario/$this->conf->tipo_cambio) * $c->Cantidad, 2);
						}else{
							echo number_format($c->PrecioUnitario * $c->Cantidad, 2);} 
					?></div>
					<?php endforeach;?>
				</div>
			</div>		
		</div>
	</body>
</html>
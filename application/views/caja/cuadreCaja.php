<div class="row" style="background:#fff;">

	<div class="col-md-4">
		<h2>Egresos/Gastos</h2>
		<table class="table table-border">
			<thead>
				<th>Concepto</th>
				<th>Caja Chica</th>
			</thead>
			<tbody>
				<tr>
					<td><b>Egresos</b></td>
					<td>S/. <?php echo $datos["egresosCC"]["total"];?></td>
				</tr>
				<tr>
					<td><b>Depósitos</b></td>
					<td>S/. <?php echo $datos["egresosComCC"]["total"];?></td>
				</tr>
				<tr style="font-size:1.5em;">
					<td><span style="color:red;"><b>Total Egresos: </b></span></td>
					<td>S/. <?php echo number_format($datos["egresosCC"]["total"]+$datos["egresosComCC"]["total"],2);?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-8">
		<h2>Ingresos</h2>
		<table class="table table-border">
			<thead>
				<th>Concepto</th>
				<th>Efectivo</th>
				<th>Visa</th>
				<th>MasterCard</th>
				<th>Estilos</th>
				<th>Deposito</th> 
				<th>Yape</th> 
			</thead>
			<tbody>
				<tr>
					<td><b>Ventas</b></td>
					<td>S/. <?php echo $datos["ingresosVentasEfe"]["total"];?></td>
					<td>S/. <?php echo $datos["ingresosVentasVis"]["total"];?></td>
					<td>S/. <?php echo $datos["ingresosVentasMc"]["total"];?></td>
					<td>S/. <?php echo $datos["ingresosVentasEst"]["total"];?></td>
					<td>S/. <?php echo $datos["ingresosVentasDepo"]["total"];?></td>
					<td>S/. <?php echo $datos["ingresosVentasYape"]["total"];?></td>
				</tr>
				<tr>
					<td><b>Deudas Clientes</b></td>
					<td>S/. <?php echo $datos["ingresosDeudasEfe"]["total"];?></td>
					<td>S/. <?php echo $datos["ingresosDeudasVis"]["total"];?></td>
					<td>S/. <?php echo $datos["ingresosDeudasMc"]["total"];?></td>
					<td>S/. <?php echo $datos["ingresosDeudasEst"]["total"];?></td>
					<td>S/. <?php echo $datos["ingresosDeudasDepo"]["total"];?></td>
					<td>S/. <?php echo $datos["ingresosDeudasYape"]["total"];?></td>
				</tr>
				<tr>
					<td><b>Totales</b></td>
					<td><b>S/. <?php echo number_format(($datos["ingresosDeudasEfe"]["total"]+$datos["ingresosVentasEfe"]["total"]), 2);?></b></td>
					<td><b>S/. <?php echo number_format(($datos["ingresosDeudasVis"]["total"]+$datos["ingresosVentasVis"]["total"]), 2);?></b></td>
					<td><b>S/. <?php echo number_format(($datos["ingresosDeudasMc"]["total"]+$datos["ingresosVentasMc"]["total"]), 2);?></b></td>
					<td><b>S/. <?php echo number_format(($datos["ingresosDeudasEst"]["total"]+$datos["ingresosVentasEst"]["total"]), 2);?></b></td>
					<td><b>S/. <?php echo number_format(($datos["ingresosDeudasDepo"]["total"]+$datos["ingresosVentasDepo"]["total"]), 2);?></b></td>
					<td><b>S/. <?php echo number_format(($datos["ingresosDeudasYape"]["total"]+$datos["ingresosVentasYape"]["total"]), 2);?></b></td>
				</tr>
				<tr>
					<td><b>Caja Inicial</b></td>
					<td colspan="5"><b>S/. <?php echo number_format($datos["cajaDelDia"],2);?></b></td>
				</tr>
				<tr>
					<td style="font-size:1.5em;"><span style="color:green;"><b>Total Ingresos</b></span></td>
					<td colspan="2" style="font-size:1.5em;"><b>S/. <?php echo number_format($datos["totalIngresos"]+$datos["cajaDelDia"],2);?></b></td>
					
				</tr>
				<tr>
					<td style="font-size:1.5em;"><b><span style="color:green;">Ingresos</span> - <span style="color:red;">Egresos</span></b></td>
					<td colspan="5" style="font-size:1.5em;"><b>S/. <?php echo number_format(($datos["totalIngresos"]+$datos["cajaDelDia"])-($datos["egresosCC"]["total"]+$datos["egresosComCC"]["total"]),2);?></b></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>



<script>
/*$(document).ready(function(){
	$("#btnDevolucionGuardar").click(function(){

	})
})*/
</script>
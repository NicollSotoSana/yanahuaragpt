<div class="row" style="background:#fff;">

    <div class="col-md-12"><h1>Reportes de Caja</h1></div>
	<?php //var_dump($datos);?>
	<form method="POST" action="">
		<div class="col-md-4">
			<div class="form-group">
				<label>Fecha:</label>
				<input type="date" name="fecha_caja" class="form-control" value="<?php echo $fecha;?>">
			</div>
		</div>
		<div class="col-md-4">
			<br/>
			<button class="btn btn-success" type="submit"><i class="icon icon-filter"></i> Filtrar</button>
		</div>
	</form>

    
    <div class="col-md-12">
        <h1>Comprobantes</h1>
        <table class="table table-bordered table-hover" id="example">
            <thead>
                <tr>
                    <th>Comprobante</th>
                    <th>Cliente</th>
                    <th>Adelanto</th>
                    <th>Total</th>
                    <th>Medio Pago</th>
                    <th>Deuda</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($comprobantes as $comp){
                        $serie = !empty($comp->Serie) ? $comp->Serie."-" : "";
                        $deuda = $comp->deuda_generada == 1 ? "SI":"NO";
                        echo '<tr>
                            <td>'.$serie.$comp->Correlativo.'</td>
                            <td>'.$comp->ClienteNombre.'</td>
                            <td>'.$comp->adelanto.'</td>
                            <td>'.$comp->Total.'</td>
                            <td>'.$comp->mediopago.'</td>
                            <td>'.$deuda.'</td>
                        </tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>

	<div class="col-md-12">
        <h1>Pagos Deudas</h1>
        <table class="table table-bordered table-hover" id="example">
            <thead>
                <tr>
                    <th>Comprobante</th>
                    <th>Cliente</th>
                    <th>Monto</th>
					<th>Medio Pago</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($pagosDeudas as $e){
						if($e->serie == null){
							$cpe = "NP01-".$e->correlativo;
						}else{
							$cpe = $e->serie."-".$e->correlativo;
						}
                        echo '<tr>
                            <td>'.$cpe.'</td>
                            <td>'.$e->ClienteNombre.'</td>
							<td>'.$e->monto_pagado.'</td>
							<td>'.ucwords($e->medio_pago).'</td>
                        </tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>

    <div class="col-md-12">
        <h1>Egresos</h1>
        <table class="table table-bordered table-hover" id="example">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>Usuario</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($egresos as $e){
                        echo '<tr>
                            <td>'.$e->concepto.'</td>
							<td>'.$e->Nombre.'</td>
                            <td>'.$e->monto_egreso.'</td>
                            
                        </tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
	
</div>

<div class="row" style="background:#fff;">

	<div class="col-md-4">
		<h2>Egresos/Gastos</h2>
		<table class="table table-border">
			<thead>
				<th>Concepto</th>
				<th>Caja Chica</th>
				<!--<th>Cuenta Bancaria</th>-->
			</thead>
			<tbody>
				<tr>
					<td><b>Egresos</b></td>
					<td>S/. <?php echo $datos["egresosCC"]["total"];?></td>
					<!--<td>S/. <?php echo $datos["egresosCB"]["total"];?></td>-->
				</tr>
				<tr>
					<td><b>Depósitos</b></td>
					<td>S/. <?php echo $datos["egresosComCC"]["total"];?></td>
					<!--<td>S/. <?php echo $datos["egresosComCB"]["total"];?></td>-->
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

    <div class="col-md-12">
        <center><a href="<?php echo base_url();?>reportes/getCajaDiariaExcel/<?php echo $fecha;?>" target="_blank" class="btn btn-success">Descargar Excel</a></center>
    </div>
</div>

<style>
.dataTables_wrapper .dataTables_filter {
float: right;
text-align: right;
visibility: hidden;
}
</style>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
 
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
    	$('#example').DataTable( {
            "dom": 'Bfrtip',
            "pageLength": 800,
            "order": [[ 1, "desc" ]],
            "buttons": [
            {
                    extend: 'excel',
                    text: 'Excel',
                    className: 'btn btn-success'
            },
            {
                extend: 'pdf',
                text: 'PDF',
                className: 'btn btn-danger'
            }
            ],
		    "language": {
		        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
			}
		});
	});
</script>
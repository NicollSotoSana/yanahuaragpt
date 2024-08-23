<?php //echo array_debug($ProductosSinStock); ?>
<?php
$correcto = $this->session->flashdata('correcto');
    if ($correcto) 
    {
    ?>
       <div class="alert alert-success"><strong><?php echo $correcto; ?></strong></div>
    <?php
    }
    ?>

<div class="row">
	<div class="quick-actions_homepage">
      <ul class="quick-actions">
      	<li class="bg_lg span2"> <a href="<?php echo base_url()."procesocliente"; ?>"> <i class="icon-plus"></i> <strong>Iniciar Venta</strong><br/>(Anamnesis)</a> </li>

		<li class="bg_lr span2"> <a href="<?php echo base_url()."Productosnoofertados"; ?>"> <i class="icon-search"></i> Productos No Ofertados</a> </li>
      	<!--<li class="bg_ly span2"> <a href="<?php echo base_url();?>encuesta"> <i class="icon-list-alt"></i> <strong>Registrar<br/>Nueva Encuesta</strong></a> </li>-->
      	<?php
      	    if($this->user->Tipo == 1){
      	?>
            <li class="bg_lb span2"> <a href="#"> <i class="icon-money"></i> <strong>Vendido Hoy<br/><?php echo $this->conf->Moneda_id; ?> <?php echo number_format($resumen->Vendido, 2); ?>	</strong></a> </li>
            <li class="bg_lg span2"> <a href="#"> <i class="icon-bar-chart"></i> <strong>Total Ganancia<br/><?php echo $this->conf->Moneda_id; ?> <?php echo number_format($resumen->Ganado, 2); ?></strong></a> </li>
            <li class="bg_lo span2"> <a href="#"> <i class="icon-user"></i> <strong>Total Clientes<br/><?php echo $resumen->Clientes; ?></strong> </a> </li>
            <li class="bg_ly span2"> <a href="#l"> <i class="icon-th"></i> <strong>Total Productos<br/><?php echo $resumen->Productos; ?></strong></a> </li>
            <!--<li class="bg_lg span2"> <a href="<?php echo base_url();?>asistencia"> <i class="icon-check"></i> <strong>Registrar<br/>Asistencia</strong></a> </li>-->
        <?php
      	    }
      	?>
      </ul>
    </div>
	<div class="col-md-12">
		<hr>
		<legend class="text-center">Órdenes Laboratorio</legend>
		<div class="well well-sm">
					<table class="table table-striped">
						<thead>
							<tr>
								<td><b>Nro. Orden</b></td>
								<td><b>Vendedor</b></td>
								<td><b>Fecha Recep.</b></td>
								<td><b>Fecha Entrega</b></td>
								<td><b>Cliente</b></td>
								<td><b>Estado</b></td>
								<td><b>Ver</b></td>
							</tr>
						<thead>
						<?php //var_dump($ordenes)?>
						<tbody>
					<?php foreach($ordenes as $ord): ?>
						<?php 
							$hoy = date('Y-m-d');
							$fechaord = date('Y-m-d', strtotime($ord["fecha_entrega"]));
							$tomorrow = date('Y-m-d', strtotime("+1 day"));
							if($hoy == $fechaord){
								$clase = 'class="danger"';
								$estilo = 'style="color:red;"';
							}else if($fechaord == $tomorrow){
								$clase = 'class="warning"';
								$estilo = 'style="color: #e88102;"';
							}else{
								$clase = '';
								$estilo = '';
							}
						?>
						<tr <?php echo $clase;?>>
							<td style="width:10px;"><span <?php echo $estilo;?>>#<?php echo str_pad($ord["id_orden"], 6, '0', STR_PAD_LEFT); ?></span></td>
							<td style="width:10px;"><span <?php echo $estilo;?>><?php echo $ord["vendedor"]; ?></span></td>
							<td style="width:10px;"><span <?php echo $estilo;?>><?php echo date("d/m/y", strtotime($ord["fecha_orden"])); ?></span></td>
							<td style="width:10px;"><b <?php echo $estilo;?>><?php echo date("d/m/y H:i", strtotime($ord["fecha_entrega"])); ?></b></td>
							<td style="width:30px;">
								<?php 
									echo '<a target="_blank" '.$estilo.' href="'.base_url('mantenimiento/Cliente/' . $ord["id_cli"]).'">'.$ord["nombre"].'</a>';
								?>
							</td>
							<td style="width:30px;">
								<?php 
									if($ord["id_estado_orden"]==1){
										echo '<span class="label label-info" style="font-size:11px;">Enviado a Laboratorio</span>';
									}else if($ord["id_estado_orden"]==2){
										echo '<span class="label label-warning" style="font-size:11px;">Listo para Entregar</span>';
									}else if($ord["id_estado_orden"]==5){
										echo '<span class="label label-danger" style="font-size:11px;">Observado</span>';
									}
								?>
							</td>
							
							<td style="width:20px;">
								<a target="_blank" href="<?php echo base_url('mantenimiento/ordenLaboratorio/'.$ord["id_evaluacion"].'/' . $ord["id_orden"]); ?>" class="btn btn-primary btn-xs">
									<i class="glyphicon glyphicon-search"></i> Ver Orden de Lab.
								</a>
							</td>
						</tr>

					<?php endforeach; ?>
					<tbody>
					</table>
				</div>
		</div>

		<div class="col-md-6">
			<hr>
			<legend class="text-center">Próximas Evaluaciones</legend>
			<?php //var_dump($nearEvals)?>
			<div class="well well-sm" style="height: 320px; overflow-y: auto;">
					<table class="table table-striped">
						<tr>
							<td><b>Ult. Evaluación</b></td>
							<td><b>Nombre</b></td>
							<td><b>Tlf.</b></td>
						</tr>
					<?php foreach($nearEvals as $nv): ?>
						<tr <?php echo (date("d/m/Y", strtotime($nv->fecha))==date("d/m/Y")) ? 'class="success"':"";?>>
							<td style="width:20px;"><b><?php echo date("d/m/Y", strtotime($nv->fecha)); ?></b></td>
							<td style="width:100px;">
								<a target="_blank" href="<?php echo base_url().'mantenimiento/cliente/'.$nv->id_cliente; ?>" ><?php echo $nv->Nombre; ?></a>
							</td>
							<td style="width:100px;">
								<?php echo $nv->Telefono1; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</table>
				</div>
		</div>

		<div class="col-md-6">
			<hr>
			<legend class="text-center">Cumpleaños del Mes</legend>
			<div class="well well-sm" style="height: 320px; overflow-y: auto;">
					<table class="table table-striped">
						<tr>
							<td><b>Fecha</b></td>
							<td><b>Nombre</b></td>
							<td><b>Teléfono</b></td>
						</tr>
					<?php foreach($cumples as $cum): ?>
						<tr <?php echo (date("d/m/Y", strtotime($cum->fecha_nac))==date("d/m/Y")) ? 'class="success"':"";?>>
							<td style="width:20px;"><?php echo date("d/m/Y", strtotime($cum->fecha_nac)); ?></td>
							<td style="width:100px;">
								<b><?php echo $cum->Nombre; ?></b>
							</td>
							<td  style="width:50px;">
								<?php echo $cum->Telefono1; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</table>
				</div>
		</div>
</div>
<?php 
if($isOpen!=1){
	echo '<div class="alert alert-danger">
  <strong>Atención!</strong> Aún no has aperturado la caja para el día de hoy.
</div>';
}else{
	echo '<div class="alert alert-success" style="text-align:center;"><span style="font-size:16px; font-weight:bold;">A continuación se muestra el movimiento de caja para el día de hoy.</span>
</div>';
?>
<div class="row" style="background:#fff; display:none;">

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
					<td>S/. <?php echo $caja["egresosCC"]["total"];?></td>
					<!--<td>S/. <?php echo $caja["egresosCB"]["total"];?></td>-->
				</tr>
				<tr>
					<td><b>Depósitos</b></td>
					<td>S/. <?php echo $caja["egresosComCC"]["total"];?></td>
					<!--<td>S/. <?php echo $caja["egresosComCB"]["total"];?></td>-->
				</tr>
				<tr style="font-size:1.5em;">
					<td><span style="color:red;"><b>Total Egresos: </b></span></td>
					<td>S/. <?php echo number_format($caja["egresosCC"]["total"]+$caja["egresosComCC"]["total"],2);?></td>
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
					<td>S/. <?php echo $caja["ingresosVentasEfe"]["total"];?></td>
					<td>S/. <?php echo $caja["ingresosVentasVis"]["total"];?></td>
					<td>S/. <?php echo $caja["ingresosVentasMc"]["total"];?></td>
					<td>S/. <?php echo $caja["ingresosVentasEst"]["total"];?></td>
					<td>S/. <?php echo $caja["ingresosVentasDepo"]["total"];?></td>
					<td>S/. <?php echo $caja["ingresosVentasYape"]["total"];?></td>
				</tr>
				<tr>
					<td><b>Deudas Clientes</b></td>
					<td>S/. <?php echo $caja["ingresosDeudasEfe"]["total"];?></td>
					<td>S/. <?php echo $caja["ingresosDeudasVis"]["total"];?></td>
					<td>S/. <?php echo $caja["ingresosDeudasMc"]["total"];?></td>
					<td>S/. <?php echo $caja["ingresosDeudasEst"]["total"];?></td>
					<td>S/. <?php echo $caja["ingresosDeudasDepo"]["total"];?></td>
					<td>S/. <?php echo $caja["ingresosDeudasYape"]["total"];?></td>
				</tr>
				<tr>
					<td><b>Totales</b></td>
					<td><b>S/. <?php echo number_format(($caja["ingresosDeudasEfe"]["total"]+$caja["ingresosVentasEfe"]["total"]), 2);?></b></td>
					<td><b>S/. <?php echo number_format(($caja["ingresosDeudasVis"]["total"]+$caja["ingresosVentasVis"]["total"]), 2);?></b></td>
					<td><b>S/. <?php echo number_format(($caja["ingresosDeudasMc"]["total"]+$caja["ingresosVentasMc"]["total"]), 2);?></b></td>
					<td><b>S/. <?php echo number_format(($caja["ingresosDeudasEst"]["total"]+$caja["ingresosVentasEst"]["total"]), 2);?></b></td>
					<td><b>S/. <?php echo number_format(($caja["ingresosDeudasDepo"]["total"]+$caja["ingresosVentasDepo"]["total"]), 2);?></b></td>
					<td><b>S/. <?php echo number_format(($caja["ingresosDeudasYape"]["total"]+$caja["ingresosVentasYape"]["total"]), 2);?></b></td>
				</tr>
				<tr>
					<td><b>Caja Inicial</b></td>
					<td colspan="5"><b>S/. <?php echo number_format($caja["cajaDelDia"],2);?></b></td>
				</tr>
				<tr>
					<td style="font-size:1.5em;"><span style="color:green;"><b>Total Ingresos</b></span></td>
					<td colspan="2" style="font-size:1.5em;"><b>S/. <?php echo number_format($caja["totalIngresos"]+$caja["cajaDelDia"],2);?></b></td>
					
				</tr>
				<tr>
					<td style="font-size:1.5em;"><b><span style="color:green;">Ingresos</span> - <span style="color:red;">Egresos</span></b></td>
					<td colspan="5" style="font-size:1.5em;"><b>S/. <?php echo number_format(($caja["totalIngresos"]+$caja["cajaDelDia"])-($caja["egresosCC"]["total"]+$caja["egresosComCC"]["total"]),2);?></b></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<div class="row" style="background:#fff;">

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
                    foreach($caja["comprobantes"] as $comp){
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
                    foreach($caja["pagosDeudas"] as $e){
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
                    foreach($caja["egresos"] as $e){
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
					<td>S/. <?php echo $caja["datos"]["egresosCC"]["total"];?></td>
					<!--<td>S/. <?php echo $caja["datos"]["egresosCB"]["total"];?></td>-->
				</tr>
				<tr>
					<td><b>Cancelaciones</b></td>
					<td>S/. <?php echo $caja["datos"]["egresosComCC"]["total"];?></td>
					<!--<td>S/. <?php echo $caja["datos"]["egresosComCB"]["total"];?></td>-->
				</tr>
				<tr style="font-size:1.5em;">
					<td><span style="color:red;"><b>Total Egresos: </b></span></td>
					<td>S/. <?php echo number_format($caja["datos"]["egresosCC"]["total"]+$caja["datos"]["egresosComCC"]["total"],2);?></td>
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
					<td>S/. <?php echo $caja["datos"]["ingresosVentasEfe"]["total"];?></td>
					<td>S/. <?php echo $caja["datos"]["ingresosVentasVis"]["total"];?></td>
					<td>S/. <?php echo $caja["datos"]["ingresosVentasMc"]["total"];?></td>
					<td>S/. <?php echo $caja["datos"]["ingresosVentasEst"]["total"];?></td>
					<td>S/. <?php echo $caja["datos"]["ingresosVentasDepo"]["total"];?></td>
					<td>S/. <?php echo $caja["datos"]["ingresosVentasYape"]["total"];?></td>
				</tr>
				<tr>
					<td><b>Deudas Clientes</b></td>
					<td>S/. <?php echo $caja["datos"]["ingresosDeudasEfe"]["total"];?></td>
					<td>S/. <?php echo $caja["datos"]["ingresosDeudasVis"]["total"];?></td>
					<td>S/. <?php echo $caja["datos"]["ingresosDeudasMc"]["total"];?></td>
					<td>S/. <?php echo $caja["datos"]["ingresosDeudasEst"]["total"];?></td>
					<td>S/. <?php echo $caja["datos"]["ingresosDeudasDepo"]["total"];?></td>
					<td>S/. <?php echo $caja["datos"]["ingresosDeudasYape"]["total"];?></td>
				</tr>
				<tr>
					<td><b>Totales</b></td>
					<td><b>S/. <?php echo number_format(($caja["datos"]["ingresosDeudasEfe"]["total"]+$caja["datos"]["ingresosVentasEfe"]["total"]), 2);?></b></td>
					<td><b>S/. <?php echo number_format(($caja["datos"]["ingresosDeudasVis"]["total"]+$caja["datos"]["ingresosVentasVis"]["total"]), 2);?></b></td>
					<td><b>S/. <?php echo number_format(($caja["datos"]["ingresosDeudasMc"]["total"]+$caja["datos"]["ingresosVentasMc"]["total"]), 2);?></b></td>
					<td><b>S/. <?php echo number_format(($caja["datos"]["ingresosDeudasEst"]["total"]+$caja["datos"]["ingresosVentasEst"]["total"]), 2);?></b></td>
					<td><b>S/. <?php echo number_format(($caja["datos"]["ingresosDeudasDepo"]["total"]+$caja["datos"]["ingresosVentasDepo"]["total"]), 2);?></b></td>
					<td><b>S/. <?php echo number_format(($caja["datos"]["ingresosDeudasYape"]["total"]+$caja["datos"]["ingresosVentasYape"]["total"]), 2);?></b></td>
				</tr>
				<tr>
					<td><b>Caja Inicial</b></td>
					<td colspan="6"><b>S/. <?php echo number_format($caja["datos"]["cajaDelDia"],2);?></b></td>
				</tr>
				<tr>
					<td style="font-size:1.5em;"><span style="color:green;"><b>Total Ingresos</b></span></td>
					<td colspan="6" style="font-size:1.5em;"><b>S/. <?php echo number_format($caja["datos"]["totalIngresos"]+$caja["datos"]["cajaDelDia"],2);?></b></td>
					
				</tr>
				<tr>
					<td style="font-size:1.5em;"><b><span style="color:green;">Ingresos</span> - <span style="color:red;">Egresos</span></b></td>
					<td colspan="6" style="font-size:1.5em;"><b>S/. <?php echo number_format(($caja["datos"]["totalIngresos"]+$caja["datos"]["cajaDelDia"])-($caja["datos"]["egresosCC"]["total"]+$caja["datos"]["egresosComCC"]["total"]),2);?></b></td>
				</tr>
			</tbody>
		</table>
	</div>

    <div class="col-md-12">
        <center><a href="<?php echo base_url();?>reportes/getCajaDiariaExcel/<?php echo date("Y-m-d");?>" target="_blank" class="btn btn-success">Descargar Excel</a></center>
    </div>
</div>

<?php }?>
<h1>Listado de Órdenes de Laboratorio</h1>
<?php //var_dump($datos);?>
<form method="POST" action="">
	<div class="col-md-2">
		<div class="form-group">
			<label>Fecha Inicio:</label>
			<input type="date" name="fecha_inicio" class="form-control" value="<?php echo ($datospost!=null)? $datospost["fecha_inicio"]:date("Y-m-d");?>">
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<label>Fecha Fin:</label>
			<input type="date" name="fecha_fin" class="form-control" value="<?php echo ($datospost!=null)? $datospost["fecha_fin"]:date("Y-m-d");?>">
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label>Laboratorio:</label>
			<select class="form-control" name="laboratorio">
				<option value="0">Todos</option>
				<?php
					foreach($laboratorios as $lab){
						if($datospost != null && $lab->laboratorio == $datospost["laboratorio"]){
							echo '<option value="'.$lab->laboratorio.'" selected>'.$lab->laboratorio.'</option>';
						}else{
							echo '<option value="'.$lab->laboratorio.'">'.$lab->laboratorio.'</option>';
						}
						
					}
				?>
			</select>
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<label>Usuario:</label>
			<select class="form-control" name="usuario">
				<option value="0" name="usuario">Todos</option>
				<?php
					foreach($usuarios as $u){
						if($datospost != null && $u->id == $datospost["usuario"]){
							echo '<option value="'.$u->id.'" selected>'.$u->Nombre.'</option>';
						}else{
							echo '<option value="'.$u->id.'">'.$u->Nombre.'</option>';
						}
						
					}
				?>
			</select>
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<label>Estado:</label>
			<select class="form-control" name="estado">
				<option value="0">Todos</option>
				<?php
					foreach($estados as $e){
						if($datospost != null && $e->id_estado == $datospost["estado"]){
							echo '<option value="'.$e->id_estado.'" selected>'.$e->estado.'</option>';
						}else{
							echo '<option value="'.$e->id_estado.'">'.$e->estado.'</option>';
						}
						
					}
				?>
			</select>
		</div>
	</div>
	
	<div class="col-md-1">
		<br/>
		<button class="btn btn-success" type="submit"><i class="icon icon-filter"></i> Filtrar</button>
	</div>
</form>


<table class="table table-bordered table-hover" id="example">
	<thead>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Cliente</th>
			<th>Usuario</th>
			<th>Lente</th>
			<th>Lab.</th>
			<th>Clínica</th>
			<th>Doctor</th>
			<th>Empresa</th>
			<th>Comprob.</th>
			<th>Compra Proyec.</th>
			<th>Compra Final</th>
			<th>Estado</th>
			<th>Ver</th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach($datos as $dat){
				$montocomp = !empty($dat->monto_compra) ? $dat->monto_compra:"0.00";
				if($dat->monto_compra_proyectado>=$montocomp){
					$mcomprafin = "<b style='color:green;'>S/. ".$montocomp."</b>";
				}else{
					$mcomprafin = "<b style='color:red;'>S/. ".$montocomp."</b>";
				}

				if($dat->id_estado_orden==5){
					$clase = "danger";
				}else{
					$clase = "";
				}

				echo '<tr class="'.$clase.'">
					<td>'.$dat->id_orden.'</td>
					<td>'.date("d/m/Y", strtotime($dat->fecha_orden)).'</td>
					<td>'.$dat->nomcli.'</td>
					<td>'.$dat->nomusu.'</td>
					<td>'.$dat->lente.'</td>
					<td>'.$dat->laboratorio.'</td>';

					if(!empty($dat->clinica)){
						echo '<td>'.$dat->clinica.'</td>';
					}else{
						echo '<td>N/A</td>';
					}

					if(!empty($dat->doctor)){
						echo '<td>'.$dat->doctor.'</td>';
					}else{
						echo '<td>N/A</td>';
					}

					if(!empty($dat->empresa_convenio)){
						echo '<td>'.$dat->empresa_convenio.'</td>';
					}else{
						echo '<td>N/A</td>';
					}

					if(isset($dat->idcomprobante) && $dat->idcomprobante>0){
						echo '<td><a href="'.base_url('/ventas/comprobante/'.$dat->idcomprobante).'" target="_blank" class="btn btn-info"><i class="icon icon-file"></i> Ver</a></td>';
					}else{
						echo '<td><a href="#" class="btn btn-info" disabled><i class="icon icon-file"></i> Ver</a></td>';
					}
					

					echo '<td>S/. '.$dat->monto_compra_proyectado.'</td>
					<td>'.$mcomprafin.'</td>';

					if($dat->id_estado_orden==1){
						echo '<td><span class="label label-info" style="font-size:11px;">Enviado a Laboratorio</span></td>';
					}else if($dat->id_estado_orden==2){
						echo '<td><span class="label label-warning" style="font-size:11px;">Listo para Entregar</span></td>';
					}else if($dat->id_estado_orden==3){
						echo '<td><span class="label label-success" style="font-size:11px;">Entregado</span></td>';
					}else if($dat->id_estado_orden==4){
						echo '<td><span class="label label-danger" style="font-size:11px;">Anulado</span></td>';
					}else if($dat->id_estado_orden==5){
						echo '<td><span class="label label-danger" style="font-size:11px;">Observado</span></td>';
					}

					echo '<td><a href="'.base_url('/mantenimiento/ordenLaboratorio/'.$dat->id_evaluacion.'/'.$dat->id_orden).'" target="_blank" class="btn btn-primary"><i class="icon icon-search"></i> Ver</a></td>';
				echo '</tr>';
			}
		?>
	</tbody>
</table>
		

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
			"scrollX": true,
			"responsive": true,
            "order": [[ 0, "desc" ]],
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
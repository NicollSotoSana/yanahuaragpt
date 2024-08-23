<div class="row">

	<h1>Listado de Anamnesis</h1>
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
		<div class="col-md-3">
			<div class="form-group">
				<label>Estado:</label>
				<select class="form-control" name="estado">
				<?php
					if($datospost != null && $datospost["estado"] == 0){
						echo '<option value="0" selected>Todos</option>';
					}else{
						echo '<option value="0">Todos</option>';
					}

					if($datospost != null && $datospost["estado"] == 1){
						echo '<option value="1" selected>Archivadas</option>';
					}else{
						echo '<option value="1">Archivadas</option>';
					}

					if($datospost != null && $datospost["estado"] == 2){
						echo '<option value="2" selected>Completadas</option>';
					}else{
						echo '<option value="2">Completadas</option>';
					}
				?>
				</select>
			</div>
		</div>
		<div class="col-md-2">
			<br/>
			<button class="btn btn-success" type="submit"><i class="icon icon-filter"></i> Filtrar</button>
		</div>
	</form>
	<table class="table table-bordered table-hover" id="example">
		<thead>
			<tr>
				<th>Fecha</th>
				<th>Cliente</th>
				<th>Usuario</th>
				<th>Estado</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$archivadas = 0;
				$completadas = 0;
				foreach($datos as $d){
					if($d->estado == 1){
						$estado = '<span class="label label-warning" style="font-size:11px;">Archivada</span>';
						$archivadas++;
					}else{
						$estado = '<span class="label label-success" style="font-size:11px;">Completada</span>';
						$completadas++;
					}
					echo '<tr>
						<td>'.date("d/m/Y", strtotime($d->fecha)).'</td>
						<td><a href="'.base_url().'mantenimiento/Cliente/'.$d->idcli.'" target="_blank">'.$d->NombreCliente.'</a></td>
						<td>'.$d->NombreUsuario.'</td>
						<td>'.$estado.'</td>
					</tr>';
				}
			?>
		</tbody>
	</table>
	<center><p style="text-align:center;">
	<h4>Archivadas: <?php echo $archivadas;?></h4>
	<h4>Completadas: <?php echo $completadas;?></h4>
	</p></center>
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
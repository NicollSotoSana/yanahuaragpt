<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h3><b>Doctores / Clínicas</b></h3>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li><a href="<?php echo base_url('index.php/mantenimiento'); ?>">Mantenimiento</a></li>
		  <li class="active">Doctores / Clínicas</li>
		</ol>
		<div class="row vdivide">
			<div class="col-md-12" style="margin-bottom:20px;">
				<button class="btn btn-success" onclick="nuevoDoctor();"><b><i class="icon-user-md"></i> Nuevo Doctor</b></button>
				<button class="btn btn-warning" onclick="nuevaClinica();"><b><i class="icon-plus"></i> Nueva Clínica</b></button>
			</div>
			<?php 
				$correcto = $this->session->flashdata('correcto');
			    if ($correcto) 
			    {
			    ?>
			       <div class="alert alert-success"><strong><?php echo $correcto; ?></strong></div>
			    <?php
			    }
			    ?>
			<div class="col-md-6">
				<h1>Doctores</h1>
				

				<div class="table-responsive">
					<table id="example" class="display" cellspacing="0" width="100%">
			        <thead>
			            <tr>
			                <th>ID</th>
			                <th>Doctor</th>
			                <th>Editar</th>
			            </tr>
			        </thead>
			        <tbody>
			        	<?php
				        	foreach($doctores as $d){
				        		echo '<tr><td>'.$d->id_doctor.'</td><td>'.$d->doctor.'</td><td><button class="btn btn-warning" onclick="editarDoctor('.$d->id_doctor.');">Editar</button></td></tr>';
				        	}
			        	?>
			        </tbody>
			        <tfoot>
			            <tr>
			                <th>ID</th>
			                <th>Doctor</th>
			                <th>Editar</th>
			            </tr>
			        </tfoot>
			        <tbody>
       				
					</tbody>
    				</table>
				</div>
			</div>

			<div class="col-md-6">
				<h1>Clínicas</h1>
			
				<div class="table-responsive">
					<table id="example2" class="display" cellspacing="0" width="100%">
			        <thead>
			            <tr>
			                <th>ID</th>
			                <th>Clínica</th>
			                <th>Editar</th>
			            </tr>
			        </thead>
			        <tbody>
			        	<?php
				        	foreach($clinicas as $c){
				        		echo '<tr><td>'.$c->id_clinica.'</td><td>'.$c->clinica_nombre.'</td><td><button class="btn btn-warning">Editar</button></td></tr>';
				        	}
			        	?>
			        </tbody>
			        <tfoot>
			            <tr>
			                <th>ID</th>
			                <th>Clínica</th>
			                <th>Editar</th>
			            </tr>
			        </tfoot>
			        <tbody>
       				
					</tbody>
    				</table>
				</div>
			</div>
		</div>
	</div>
</div>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
    	$('#example, #example2').DataTable( {
		        "language": {
		            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		        }
		    } );
		} );

	function editarDoctor(id){
		//alert(correlativonum);
		AjaxPopupModal('mDetallePagos', 'Editar Doctor', 'mantenimiento/editarDoctor', { id_doctor : id})
	}

	function nuevoDoctor(){
		//alert(correlativonum);
		AjaxPopupModal('mDetallePagos', 'Nuevo Doctor', 'mantenimiento/nuevoDoctor', {})
	}

	function editarClinica(id){
		//alert(correlativonum);
		AjaxPopupModal('mDetallePagos', 'Editar Clínica', 'mantenimiento/editarClinica', { id_doctor : id})
	}

	function nuevaClinica(){
		//alert(correlativonum);
		AjaxPopupModal('mDetallePagos', 'Nueva Clínica', 'mantenimiento/nuevaClinica', {})
	}
</script>
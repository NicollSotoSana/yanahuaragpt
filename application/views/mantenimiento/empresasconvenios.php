<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h3><b>Empresas Convenios</b></h3>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li><a href="<?php echo base_url('index.php/mantenimiento'); ?>">Mantenimiento</a></li>
		  <li class="active">Empresas Convenios</li>
		</ol>
		<div class="row">
			<div class="col-md-12" style="margin-bottom:20px;">
				<button class="btn btn-success pull-right" onclick="nuevaEmpresa();"><b><i class="icon-user"></i> Nueva Empresa</b></button>
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
			<div class="col-md-12">

				<div class="table-responsive">
					<table id="example" class="display" cellspacing="0" width="100%">
			        <thead>
			            <tr>
			                <th>ID</th>
			                <th>Empresa</th>
                            <th>Email</th>
			                <th>Editar</th>
			            </tr>
			        </thead>
			        <tbody>
			        	<?php
				        	foreach($empresas as $e){
				        		echo '<tr><td>'.$e->id_emp_conv.'</td><td>'.$e->empresa.'</td><td>'.$e->email.'</td><td><button class="btn btn-warning" onclick="editarEmpresa('.$e->id_emp_conv.');">Editar</button></td></tr>';
				        	}
			        	?>
			        </tbody>
			        <tfoot>
			            <tr>
			                <th>ID</th>
			                <th>Empresa</th>
                            <th>Email</th>
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

	function editarEmpresa(id){
		//alert(correlativonum);
		AjaxPopupModal('mDetallePagos', 'Editar Empresa', 'mantenimiento/editarEmpresa', { id_empresa : id})
	}

	function nuevaEmpresa(){
		//alert(correlativonum);
		AjaxPopupModal('mDetallePagos', 'Nueva Empresa', 'mantenimiento/nuevaEmpresa', {})
	}
</script>
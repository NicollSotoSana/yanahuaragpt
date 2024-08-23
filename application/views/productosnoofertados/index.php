<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h3><b>Productos solicitados y no ofertados</b></h3>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li><a href="<?php echo base_url('index.php/mantenimiento'); ?>">Otros</a></li>
		  <li class="active">Productos solicitados y no ofertados</li>
		</ol>
		<div class="row">
			<?php 
				$correcto = $this->session->flashdata('correcto');
			    if ($correcto){
			    ?>
			       	<div class="col-md-12">
					   <div class="alert alert-success"><strong><?php echo $correcto; ?></strong></div>
					</div>
			    <?php
			    }
			?>
			<div class="col-md-12" style="margin-bottom:20px;">

				<button class="btn btn-warning" onclick="nuevoProducto();"><b><i class="icon-plus"></i> Nuevo</b></button>
			</div>
			
			<div class="col-md-12">

				<div class="table-responsive">
					<table id="example" class="display" cellspacing="0" width="100%">
			        <thead>
			            <tr>
			                <th>Producto solicitado</th>
			                <th>Usuario</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
			            </tr>
			        </thead>
			        <tbody>
			        	<?php
				        	foreach($productos as $p){
				        		echo '<tr><td>'.$p->producto_solicitado.'</td><td>'.$p->Nombre.'</td><td>'.$p->fecha.'</td><td><button class="btn btn-warning" onclick="editarProducto('.$p->id_prod_no_ofertado.');">Editar</button></td></tr>';
				        	}
			        	?>
			        </tbody>
			        <tfoot>
			            <tr>
			                <th>Producto solicitado</th>
			                <th>Usuario</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
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
			"bFilter": false,
			"responsive": true,
            "order": [[ 0, "desc" ]],
			"buttons": [
			{
				extend: 'excel',
				text: 'Excel',
				className: 'btn btn-success',
				filename: 'Productos_solicitados_no_ofertados'
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
<script type="text/javascript">
	function editarProducto(id){
		//alert(correlativonum);
		AjaxPopupModal('mDetallePagos', 'Editar Producto', 'productosnoofertados/editarProducto', { id_producto : id})
	}

	function nuevoProducto(){
		//alert(correlativonum);
		AjaxPopupModal('mDetallePagos', 'Nuevo Producto no ofertado', 'productosnoofertados/nuevoProducto', {})
	}
</script>
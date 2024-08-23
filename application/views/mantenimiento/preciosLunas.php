<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h3><b>Precios de Lentes</b></h3>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li><a href="<?php echo base_url('index.php/mantenimiento'); ?>">Mantenimiento</a></li>
		  <li class="active">Precios de Lentes</li>
		</ol>
		<div class="row">
			<div class="col-md-12" style="margin-bottom:20px;">
				<button class="btn btn-success" onclick="nuevoPrecio();"><b><i class="icon-plus"></i> Nuevo Producto</b></button>
			</div>
			
			<div class="col-md-12">
				<?php 
				$correcto = $this->session->flashdata('correcto');
			    if ($correcto) 
			    {
			    ?>
			       <div class="alert alert-success"><strong><?php echo $correcto; ?></strong></div>
			    <?php
			    }
			    ?>

				<div class="table-responsive">
					<table id="example" class="display" cellspacing="0" width="100%">
			        <thead>
			            <tr>
			                <th>Modelo</th>
			                <th>Fabricación</th>
			                <th>Material</th>
			                <th>Serie</th>
			                <th>Tratamiento</th>
			                <th>Nombre</th>
			                <th>Fotocromatico</th>
							<th>Nom. Prop.</th>
			                <th>Precio</th>
			                <th>Editar</th>
			            </tr>
			        </thead>
			        <tfoot>
			            <tr>
			                <th>Modelo</th>
			                <th>Fabricación</th>
			                <th>Material</th>
			                <th>Serie</th>
			                <th>Tratamiento</th>
			                <th>Nombre</th>
			                <th>Fotocromatico</th>
							<th>Nom. Prop.</th>
			                <th>Precio</th>
			                <th>Editar</th>
			            </tr>
			        </tfoot>
			        <tbody>
       				<?php
						foreach($precios as $d){
							echo '<tr>
								<td>'.$d->disenio.'</td>
								<td>'.$d->fabricacion.'</td>
								<td>'.$d->material.'</td>
								<td>'.$d->serie.'</td>
								<td>'.$d->tratamiento.'</td>
								<td>'.$d->nombre.'</td>
								<td>'.$d->fotocromatico.'</td>
								<td>'.$d->nombre_propio_fin.'</td>
								<td>S/. '.$d->precio.'</td>
								<td><button class="btn btn-warning" onclick="editarPrecio('.$d->id_precio.')"><b><i class="icon-pencil"></i> Editar</b></button></td>
								</tr>
							';
						}
					?>
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
        "pageLength": 100,
        "order": [],
        "fixedColumns": false,
        "columnDefs": [
            { "width": "200px", "targets": [7] },
        ],
        "buttons": [
          {
                extend: 'excel',
                text: 'Excel',
                title: 'Precios_lunas',
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

	function editarPrecio(id){
		//alert(correlativonum);
		AjaxPopupModal('mDetallePagos', 'Editar Precio', 'mantenimiento/editarPrecioLuna', { id_precio : id})
	}

	function nuevoPrecio(){
		//alert(correlativonum);
		AjaxPopupModal('mDetallePagos', 'Editar Precio', 'mantenimiento/nuevoPrecioLuna', {})
	}
</script>
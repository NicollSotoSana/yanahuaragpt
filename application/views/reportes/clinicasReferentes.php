<div class="row">

    <div class="col-md-12"><h1>Clínicas Referentes</h1></div>
	<?php //var_dump($datos);?>
	<form method="POST" action="">
		<div class="col-md-4">
			<div class="form-group">
				<label>Fecha Inicio:</label>
				<input type="date" name="fecha_inicio" class="form-control" value="<?php echo ($datospost!=null)? $datospost["fecha_inicio"]:date("Y-m-d");?>">
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label>Fecha Fin:</label>
				<input type="date" name="fecha_fin" class="form-control" value="<?php echo ($datospost!=null)? $datospost["fecha_fin"]:date("Y-m-d");?>">
			</div>
		</div>
		<div class="col-md-4">
			<br/>
			<button class="btn btn-success" type="submit"><i class="icon icon-filter"></i> Filtrar</button>
		</div>
	</form>
    <div class="col-md-12">
        <table class="table table-bordered table-hover" id="example">
            <thead>
                <tr>
                    <th>Clínica</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($datos as $d){
                        echo '<tr>
                            <td>'.$d->clinica_nombre.'</td>
                            <td>'.$d->total.'</td>
                            
                        </tr>';
                    }
                ?>
            </tbody>
        </table>
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
            "pageLength": 100,
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
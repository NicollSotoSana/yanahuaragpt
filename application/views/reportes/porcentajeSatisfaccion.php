<div class="row">

    <div class="col-md-12">
        <h2>Porcentaje de Satisfacción - <?php echo $titulo;?></h2>
    </div>

    <div class="col-md-12">
        <table class="table table-bordered table-hover" id="example">
        <thead>
            <th>Nivel</th>
            <th>Total</th>
            <th>Porcentaje</th>
        </thead>
            <tbody>
                <tr>
                    <td><b>Muy Buena</b></td>
                    <td><?php echo $datos["total_mb"];?></td>
                    <td><?php echo $datos["porcen_mb"];?> %</td>
                </tr>
                <tr>
                    <td><b>Buena</b></td>
                    <td><?php echo $datos["total_b"];?></td>
                    <td><?php echo $datos["porcen_b"];?> %</td>
                </tr>
                <tr>
                    <td><b>Regular</b></td>
                    <td><?php echo $datos["total_r"];?></td>
                    <td><?php echo $datos["porcen_r"];?> %</td>
                </tr>
                <tr>
                    <td><b>Malo</b></td>
                    <td><?php echo $datos["total_m"];?></td>
                    <td><?php echo $datos["porcen_m"];?> %</td>
                </tr>
                <tr>
                    <td><b>Muy Malo</b></td>
                    <td><?php echo $datos["total_mm"];?></td>
                    <td><?php echo $datos["porcen_mm"];?> %</td>
                </tr>
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
            "bSort": false,
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
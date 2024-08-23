<div class="row">
    <div class="col-md-12">
        <p style="text-align:center; font-size:16px;"><b>MM</b> = Muy Malo | <b>M</b> = Malo | <b>R</b> = Regular | <b>B</b> = Bueno | <b>MB</b> = Muy Bueno</p>
    </div>
    <div class="col-md-12">
        <table class="table table-bordered table-hover" id="example">
        <thead>
            <th>Nombre</th>
            <th>Total Protoc.</th>
            <th>Total MM</th>
            <th>Total M</th>
            <th>Total R</th>
            <th>Total B</th>
            <th>Total MB</th>
            <th>% MM</th>
            <th>% M</th>
            <th>% R</th>
            <th>% B</th>
            <th>% MB</th>
        </thead>
            <tbody>
            <?php
                foreach($datos as $da){
            ?>
                <tr>
                    <td><b><?php echo $da["nombre"];?></b></td>
                    <td><?php echo $da["total"];?></td>
                    <td><?php echo $da["total_mm"];?></td>
                    <td><?php echo $da["total_m"];?></td>
                    <td><?php echo $da["total_r"];?></td>
                    <td><?php echo $da["total_b"];?></td>
                    <td><?php echo $da["total_mb"];?></td>
                    <td><?php echo $da["porcen_mm"];?> %</td>
                    <td><?php echo $da["porcen_m"];?> %</td>
                    <td><?php echo $da["porcen_r"];?> %</td>
                    <td><?php echo $da["porcen_b"];?> %</td>
                    <td><?php echo $da["porcen_mb"];?> %</td>
                </tr>
            <?php
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
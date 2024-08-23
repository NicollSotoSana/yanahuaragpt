<div class="row">

    <div class="col-md-12"><h1>Doctores Referentes</h1></div>
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
                    <th>Doctor</th>
                    <th>Total Ventas</th>
                    <th>Total Gastos</th>
                    <th>Total Utilidad</th>
                    <th>% Com. 1</th>
                    <th>% Com. 2</th>
                    <th>Total 1</th>
                    <th>Total 2</th>
                    <th>Total Comisión</th>
                    <th>Detallado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($datos as $d){
                        //array_debug($d);
                        $total_comision = number_format($d["comision_1"]+$d["comision_2"], 2);
                        if($datospost!=null){
                            $fi = $datospost["fecha_inicio"];
                            $ff = $datospost["fecha_fin"];
                        }else{
                            $fi = date("Y-m-d");
                            $ff = date("Y-m-d");
                        }
                        echo '<tr>
                            <td>'.$d["nombre_doctor"].'</td>
                            <td>'.number_format($d["total_venta"], 2).'</td>
                            <td>'.number_format($d["total_gasto"], 2).'</td>
                            <td>'.number_format($d["total_utilidad"], 2).'</td>
                            <td>'.$d["porcentaje_doc"].'</td>
                            <td>'.$d["porcentaje_doc2"].'</td>
                            <td>'.number_format($d["comision_1"], 2).'</td>
                            <td>'.number_format($d["comision_2"], 2).'</td>
                            <td>'.$total_comision.'</td>
                            <td><a href="'.base_url().'reportes/detalleDoctoresReferentes/'.$fi.'/'.$ff.'/'.$d["id_doctor"].'" class="btn btn-success" target="_blank">Ver Detalle</a></td>
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
        "order": [[ 8, "desc" ]],
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
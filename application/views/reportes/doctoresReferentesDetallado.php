<div class="row">

    <div class="col-md-12"><h1>Detalle</h1></div>
	<?php //var_dump($datos);?>
    <div class="col-md-12">
        <table class="table table-bordered table-hover" id="example">
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Orden Lab.</th>
                    <th>Paciente</th>
                    <th>Lente</th>
                    <th>Montura</th>
                    <th>P.C. Montura</th>
                    <th>P.V. Montura</th>
                    <th>P.C. Lentes</th>
                    <th>P.V. Lentes</th>
                    <th>Total Venta</th>
                    <th>Total Compra</th>
                    <th>Total Utilidad</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($datos as $d){
                        //array_debug($d);
                        $tventa = $d->pv_montura + $d->monto_venta;
                        $tcompra = $d->pc_montura + $d->compra_lentes;
                        $utilidad = number_format($tventa - $tcompra, 2);
                        echo '<tr>
                            <td>'.$d->doctor.'</td>

                            <td><a target="_blank" href="'.base_url('mantenimiento/ordenLaboratorio/'.$d->id_evaluacion.'/' . $d->idorden).'" class="btn btn-primary btn-xs">'.str_pad($d->idorden, 6, '0', STR_PAD_LEFT).'</a></td>

                            <td>'.$d->nombrecliente.'</td>
                            <td>'.$d->nombre_lente.'</td>
                            <td>'.$d->montura_nombre.'</td>
                            <td>'.number_format($d->pc_montura, 2).'</td>
                            <td>'.number_format($d->pv_montura, 2).'</td>
                            <td>'.number_format($d->compra_lentes, 2).'</td>
                            <td>'.number_format($d->monto_venta, 2).'</td>
                            <td>'.number_format($tventa, 2).'</td>
                            <td>'.number_format($tcompra, 2).'</td>
                            <td>'.number_format($utilidad, 2).'</td>
                            <td>'.date("d/m/Y", strtotime($d->fecha_ol)).'</td>
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
        "order": [[ 11, "desc" ]],
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
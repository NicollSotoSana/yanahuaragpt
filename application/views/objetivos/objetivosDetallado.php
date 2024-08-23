<?php //array_debug($indicadores);?>
<div class="row" style="background:#fff; overflow-y:auto;">
    <div class="col-md-12"><h1>Indicadores Detallado <?php echo $titulo;?> (<?php echo $mes;?>/<?php echo $anio;?>)</h1></div>
    <div class="col-md-12">
        <table class="table table-bordered table-hover" id="example">
            <thead>
                <tr>
                    <th style="text-align:center;">Fecha</th>
                    <th style="text-align:center;">Nro. Comprobant</th>
                    <th style="text-align:center;">Cliente</th>
                    <th style="text-align:center;">Comprobante</th>
                    <th style="text-align:center;">Orden Lab.</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($datos as $d){
                        $serie = empty($d->Serie) ? "NV01":$d->Serie;
                        echo '<tr>';
                        echo '<td>'.date("d/m/Y", strtotime($d->fecha_emision)).'</td>';
                        echo '<td>'.$serie.'-'.$d->Correlativo.'</td>';
                        echo '<td>'.$d->ClienteNombre.'</td>';
                        echo '<td style="text-align:center;"><a href="'.base_url('ventas/comprobante/'.$d->id).'" target="_blank" class="btn btn-primary"><i class="icon icon-search" style="font-size:0.9em;"></i></a></td>';
                        echo '<td style="text-align:center;"><a href="'.base_url('mantenimiento/ordenLaboratorio/0/'.$d->id_orden_lab).'" target="_blank" class="btn btn-warning"><i class="icon icon-search" style="font-size:0.9em;"></i></a></td>';
                        echo '</tr>';
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
        "order": [],
        "fixedColumns": false,
        "columnDefs": [
            { "width": "400px", "targets": [2] },
            { "width": "90px", "targets": [3,4] }
        ],
        "buttons": [
          {
                extend: 'excel',
                text: 'Excel',
                title: 'Indicadores_detallado',
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
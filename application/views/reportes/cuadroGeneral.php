<div class="row">

    <div class="col-md-12"><h1>Cuadro General</h1></div>
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
    <?php if($datos != null):?>
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="example">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Comprobante</th>
                            <th>Cliente</th>
                            <th>Doc.</th>
                            <th>Telefono</th>
                            <th>Correo</th>
                            <th>Servicio</th>
                            <th>Montura</th>
                            <th>Tipo Montura</th>
                            <th>Prec. Montura</th>
                            <th>Material Lentes</th>
                            <th>Diseño</th>
                            <th>AR</th>
                            <th>Fotosensible</th>
                            <th>Fabricación</th>
                            <th>Medida</th>
                            <th>Cant. Lentes</th>
                            <th>Prec. Lentes</th>
                            <th>Prov. Lentes</th>
                            <th>Fact. Lentes</th>
                            <th>Fec. Entrega</th>
                            <th>Fec. Entregado</th>
                            <th>% Desc.</th>
                            <th>Monto Total</th>
                            <th>Estado</th>
                            <th>Costo Lab.</th>
                            <th>Costo Montura</th>
                            <th>Ganancia Neta</th>
                            <th>Clinica Ref.</th>
                            <th>Doctor Ref.</th>
                            <th>Convenio Ref.</th>
                            <th>Vendedor</th>
                            <th>Nivel Satisfac.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            //var_dump($datos);
                            foreach($datos as $d){
                                echo '<tr>
                                    <td>'.date("d/m/Y", strtotime($d->fecha_cpe)).'</td>
                                    <td>'.$d->cpe.'</td>
                                    <td>'.$d->nombre.'</td>
                                    <td>'.$d->doc.'</td>
                                    <td>'.$d->telefono.'</td>
                                    <td>'.$d->correo.'</td>
                                    <td>'.$d->nombre_servicio.'</td>
                                    <td>'.$d->montura.'</td>
                                    <td>'.$d->tipo_montura.'</td>
                                    <td>'.$d->precio_montura.'</td>
                                    <td>'.$d->material_lentes.'</td>
                                    <td>'.$d->disenio_lentes.'</td>
                                    <td>'.$d->tratamiento_lentes.'</td>
                                    <td>'.$d->fotocroma_lentes.'</td>
                                    <td>'.$d->fabricacion_lentes.'</td>
                                    <td>'.$d->medida.'</td>
                                    <td>'.$d->cantidad_lentes.'</td>
                                    <td>'.$d->precio_lentes.'</td>
                                    <td>'.$d->proveedor_lentes.'</td>
                                    <td>'.$d->comprobante_compra.'</td>
                                    <td>'.$d->fecha_entrega.'</td>
                                    <td>'.$d->fecha_entregado.'</td>
                                    <td>'.$d->porcen_dscto.'</td>
                                    <td>'.$d->monto_total_venta.'</td>
                                    <td>'.$d->estado_trabajo.'</td>
                                    <td>'.$d->compra_laboratorio.'</td>
                                    <td>'.$d->compra_montura.'</td>
                                    <td>'.$d->ganancia_bruta.'</td>
                                    <td>'.$d->nombre_clinica.'</td>
                                    <td>'.$d->nombre_doctor.'</td>
                                    <td>'.$d->nombre_convenio.'</td>
                                    <td>'.$d->nombre_vendedor.'</td>
                                    <td>'.$d->satisfaccion.'</td>
                                </tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
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
            { "width": "300px", "targets": [7,13,12] }
        ],
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
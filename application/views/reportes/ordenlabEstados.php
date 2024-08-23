<h1>Órdenes de Laboratorio Por Día</h1>
<?php //var_dump($datos);?>
<form method="POST" action="">
    <div class="col-md-2">
        <div class="form-group">
            <label>Fecha Inicio:</label>
            <input type="date" name="fecha_inicio" class="form-control" value="<?php echo ($datospost!=null)? $datospost["fecha_inicio"]:date("Y-m-d");?>">
        </div>
    </div>
    <!--<div class="col-md-2">
        <div class="form-group">
            <label>Fecha Fin:</label>
            <input type="date" name="fecha_fin" class="form-control" value="<?php echo ($datospost!=null)? $datospost["fecha_fin"]:date("Y-m-d");?>">
        </div>
    </div>-->
    <div class="col-md-3">
        <div class="form-group">
            <label>Estado:</label>
            <select class="form-control" name="estado">
                <option value="0">Todos</option>
                <?php
                    foreach($estados as $e){
                        if($datospost != null && $e->id_estado == $datospost["estado"]){
                            echo '<option value="'.$e->id_estado.'" selected>'.$e->estado.'</option>';
                        }else{
                            echo '<option value="'.$e->id_estado.'">'.$e->estado.'</option>';
                        }
                    }
                ?>
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <br/>
        <button class="btn btn-success" type="submit"><i class="icon icon-filter"></i> Filtrar</button>
    </div>

    <div class="col-md-2">
    </div>
</form>
<table class="table table-bordered table-hover" id="example">
    <thead>
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Usuario</th>
            <th>Lente</th>
            <th>Compra Proyec.</th>
            <th>Compra Final</th>
            <th>Estado</th>
            <th>Ver</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach($datos as $dat){
                $montocomp = !empty($dat->monto_compra) ? $dat->monto_compra:"0.00";
                if($dat->monto_compra_proyectado>=$montocomp){
                    $mcomprafin = "<b style='color:green;'>S/. ".$montocomp."</b>";
                }else{
                    $mcomprafin = "<b style='color:red;'>S/. ".$montocomp."</b>";
                }
                echo '<tr>
                    <td>'.$dat->id_orden.'</td>
                    <td>'.date("d/m/Y", strtotime($dat->fecha_orden)).'</td>
                    <td>'.$dat->nomcli.'</td>
                    <td>'.$dat->nomusu.'</td>
                    <td>'.$dat->lente.'</td>
                    <td>S/. '.$dat->monto_compra_proyectado.'</td>
                    <td>'.$mcomprafin.'</td>';

                    if($dat->id_estado_orden==1){
                        echo '<td><span class="label label-info" style="font-size:11px;">Enviado a Laboratorio</span></td>';
                    }else if($dat->id_estado_orden==2){
                        echo '<td><span class="label label-warning" style="font-size:11px;">Listo para Entregar</span></td>';
                    }else if($dat->id_estado_orden==3){
                        echo '<td><span class="label label-success" style="font-size:11px;">Entregado</span></td>';
                    }else if($dat->id_estado_orden==4){
                        echo '<td><span class="label label-danger" style="font-size:11px;">Anulado</span></td>';
                    }

                    echo '<td><a href="'.base_url('/mantenimiento/ordenLaboratorio/'.$dat->id_evaluacion.'/'.$dat->id_orden).'" class="btn btn-primary"><i class="icon icon-search"></i> Ver Orden</a></td>';
                echo '</tr>';
            }
        ?>
    </tbody>
</table>
	

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
            "order": [[ 0, "desc" ]],
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
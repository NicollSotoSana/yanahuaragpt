<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Reporte Clientes</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li class="active">Reporte Clientes</li>
		</ol>
		<div class="row">
            <form method="post" action="">
			    <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Distrito:</label>
                        <select id="distrito" name="distrito" class="form-control">
                            <option hidden>Seleccione Distrito</option>
                            <?php 
                                foreach($distritos as $d){
                                    echo '<option value="'.$d->id_distrito.'">'.$d->distrito.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Profesión:</label>
                        <select name="profesion" id="profesion" class="form-control"><option hidden>Seleccione Profesión</option>
                            <?php 
                                foreach($profesiones as $p){
                                    echo '<option value="'.$p->id_profesion.'">'.$p->profesion.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Rubros Trabajo:</label>
                        <select name="rubro_trabajo" id="rubro_trabajo" class="form-control">
                            <option hidden>Rubro Trabajo</option>
                            <?php 
                                foreach($rubros_trabajo as $rt){
                                    echo '<option value="'.$rt->id_rubro_trabajo.'">'.$rt->rubro_trabajo.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <br/>
                        <button class="btn btn-success" id="buscar" type="submit">Buscar</button>
                    </div>
                </div>
            </form>
		</div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="kardex" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Comprobante</th>
                                <th>Cliente</th>
                                <th>Dirección</th>
                                <th>Distrito</th>
                                <th>F. Nac.</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>Profesión</th>
                                <th>Rubro Trabajo</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Marca</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpo">
                            <?php 
                                if(isset($datos)){
                                    foreach($datos as $d){
                                        if(empty($d->Serie)){
                                            $serie = "NP01";
                                        }else{
                                            $serie = $d->Serie;
                                        }
                                        echo '<tr>
                                            <td>'.$serie.'-'.$d->Correlativo.'</td>
                                            <td>'.$d->Nombre.'</td>
                                            <td>'.$d->Direccion.'</td>
                                            <td>'.$d->distrito.'</td>
                                            <td>'.date("d/m/Y", strtotime($d->fecha_nac)).'</td>
                                            <td>'.$d->Correo.'</td>
                                            <td>'.$d->Telefono1.'</td>
                                            <td>'.$d->profesion.'</td>
                                            <td>'.$d->rubro_trabajo.'</td>
                                            <td>'.$d->ProductoNombre.'</td>
                                            <td>'.$d->categoria.'</td>
                                            <td>'.$d->Marca.'</td>
                                            <td>'.$d->Total.'</td>
                                        </tr>';
                                    }
                                }else{
                                    echo '<tr><td colspan="9">No hay datos para mostrar.</td></tr>';
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Comprobante</th>
                                <th>Cliente</th>
                                <th>Dirección</th>
                                <th>Distrito</th>
                                <th>F. Nac.</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>Profesión</th>
                                <th>Rubro Trabajo</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Marca</th>
                                <th>Total</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
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

<script>
    var tabla = "";


    $(document).ready(function() {
    	tabla = $('#kardex').DataTable( {
            "dom": 'Bfrtip',
            "pageLength": 50,
            "order": [[ 1, "asc" ]],
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
	} );

</script>
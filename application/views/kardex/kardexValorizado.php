<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Kardex Valorizado</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li class="active">Kardex Valorizado</li>
		</ol>
		<div class="row">
            <form method="post" action="">
			    <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Mes:</label>
                        <select id="mes" name="mes" class="form-control" />
                            <option hidden>Seleccione Mes</option> 
                            <option value="01" <?php echo isset($mes) && $mes == "01" ? "selected":"" ;?>>Enero</option> 
                            <option value="02" <?php echo isset($mes) && $mes == "02" ? "selected":"" ;?>>Febrero</option> 
                            <option value="03" <?php echo isset($mes) && $mes == "03" ? "selected":"" ;?>>Marzo</option> 
                            <option value="04" <?php echo isset($mes) && $mes == "04" ? "selected":"" ;?>>Abril</option> 
                            <option value="05" <?php echo isset($mes) && $mes == "05" ? "selected":"" ;?>>Mayo</option> 
                            <option value="06" <?php echo isset($mes) && $mes == "06" ? "selected":"" ;?>>Junio</option> 
                            <option value="07" <?php echo isset($mes) && $mes == "07" ? "selected":"" ;?>>Julio</option> 
                            <option value="08" <?php echo isset($mes) && $mes == "08" ? "selected":"" ;?>>Agosto</option> 
                            <option value="09" <?php echo isset($mes) && $mes == "09" ? "selected":"" ;?>>Septiembre</option> 
                            <option value="10" <?php echo isset($mes) && $mes == "10" ? "selected":"" ;?>>Octubre</option> 
                            <option value="11" <?php echo isset($mes) && $mes == "11" ? "selected":"" ;?>>Noviembre</option> 
                            <option value="12" <?php echo isset($mes) && $mes == "12" ? "selected":"" ;?>>Diciembre</option> 
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Año:</label>
                        <select name="anio" id="anio" class="form-control"><option hidden>Seleccione Año</option>
                            <?php
                                for($i=2021; $i<=date('Y')+3;$i++){
                                    if(isset($anio) && $i == $anio){
                                        echo '<option value="'.$i.'" selected>'.$i.'</option>';
                                    }else{
                                        echo '<option value="'.$i.'">'.$i.'</option>';
                                    }
                                    
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <br/>
                        <button class="btn btn-success" id="buscar" type="submit">Buscar</button>
                    </div>
                </div>
            </form>
		</div>
        <div class="row">
            <div class="col-md-12">
                <table id="kardex" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Marca</th>
                            <th>Unidad</th>
                            <th>Unidades Físicas Vendidas</th>
                            <th>Costo unitario</th>
                            <th>Valor de Ventas</th>
                            <th>Costo de Producto</th>
                            <th>Unidad Valorizada</th>
                            <th>Exportar</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo">
                        <?php 
                            if(isset($datos)){
                                foreach($datos as $d){
                                    echo '<tr>
                                        <td>'.$d["nombre"].'</td>
                                        <td>'.$d["categoria"].'</td>
                                        <td>'.$d["marca"].'</td>
                                        <td>'.$d["udm"].'</td>
                                        <td>'.$d["cantidad"].'</td>
                                        <td>'.$d["precio_unit_compra"].'</td>
                                        <td>'.$d["valor_ventas"].'</td>
                                        <td>'.$d["costo_producto"].'</td>
                                        <td>'.$d["unidad_valorizada"].'</td>
                                        <td style="text-align:center;"><a href="'.base_url('Kardex/getCompras/'.$mes.'/'.$anio.'/'.$d["id"]).'"><i style="font-size: 2em;" class="icon icon-download"></i></a></td>
                                    </tr>';
                                }
                            }else{
                                echo '<tr><td colspan="9">No hay datos para mostrar.</td></tr>';
                            }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Marca</th>
                            <th>Unidad</th>
                            <th>Unidades Físicas Vendidas</th>
                            <th>Costo unitario</th>
                            <th>Valor de Ventas</th>
                            <th>Costo de Producto</th>
                            <th>Exportar</th>
                        </tr>
                    </tfoot>
                </table>
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
            //"order": [[ 1, "desc" ]],
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

    /*$("#buscar").on("click", function(){
        var prod_id = $("#select_prod").val();
        
        $.ajax({
            type: "POST", 
            url: base_url('Kardex/getKardexValorizado/'),
            data: {fecha_inicio: $("#fecha_inicio").val(), fecha_fin: $("#fecha_fin").val()},
            success:function(datos){
                console.log(datos);
                $.each(datos, function( index, value ) {

                    var valorizado = parseFloat(value.Precio - value.PrecioCompra).toFixed(2);
                    if(value.Tipo == "2"){
                        tabla.row.add([ value.Nombre, value.categoria, value.Marca, "UND", value.Cantidad, value.PrecioCompra, value.Precio, value.PrecioCompra, valorizado]).draw();
                    }
                    
                });
            },
            dataType: 'json'
        })
    });*/
</script>
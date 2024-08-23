<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Kardex por Producto</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li class="active">Kardex por Producto</li>
		</ol>
		<div class="row">
			<div class="col-md-4">
                <div class="form-group">
                    <label for="">Producto:</label>
                    <input type="hidden" id="select_prod" name="select_prod" style="width:300px" class="input-xlarge" />
                </div>
			</div>
            <div class="col-md-8">
                <div class="form-group">
                    <br/>
                    <button class="btn btn-success" id="buscar">Buscar</button>
                </div>
			</div>
		</div>
        <div class="row">
            <div class="col-md-12">
                <table id="kardex" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Tipo Transacción</th>
                            <th>Comprobante</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo">

                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Tipo Transacción</th>
                            <th>Comprobante</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Saldo</th>
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
        //$('.productos_list').select2();
        $('#select_prod').select2({
            ajax: {
                url: base_url('Kardex/searchProd'),
                data: function (params) {
                var query = {
                    search: params,
                    type: 'public'
                }
                //console.log(params);
                // Query parameters will be ?search=[term]&type=public
                return query;
                },
                results: function (data) {
                    return {
                        results: $.map(JSON.parse(data), function (item) {
                            return {
                                text: item.text,
                                slug: item.term,
                                id: item.id
                            }
                        })
                    };
                }
            }
        });
    });


    $(document).ready(function() {
    	tabla = $('#kardex').DataTable( {
            "dom": 'Bfrtip',
            "pageLength": 800,
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
	} );

    $("#buscar").on("click", function(){
        var prod_id = $("#select_prod").val();

        var rowsw = tabla.rows().remove().draw();
        
        $.ajax({
            type: "GET", 
            url: base_url('Kardex/getKardex/'+prod_id),
            success:function(datos){
                console.log(datos);
                $.each(datos, function( index, value ) {
                    
                    if(value.Tipo == 1){
                        var tipo_transaccion = "Entrada";
                        var entrada = value.Cantidad;
                        var salida = 0;
                    }else if(value.Tipo == 2){
                        var tipo_transaccion = "Salida";
                        var entrada = 0;
                        var salida = value.Cantidad;
                    }else if(value.Tipo == 3){
                        var tipo_transaccion = "Devolución";
                        var entrada = 0;
                        var salida = value.Cantidad;
                    }else if(value.Tipo == 4){
                        var tipo_transaccion = "Stock Inicial";
                        var entrada = value.Cantidad;
                        var salida = 0;
                    }

                    if(value.id_compra != null){
                        var comprobante = '<a href="'+base_url('almacen/nuevaCompra/0/'+value.id_compra)+'">Ver</a>';
                    }else{
                        var comprobante = '<a href="'+base_url('ventas/comprobantes/'+value.Comprobante_id)+'">Ver</a>';
                    }

                    var fecha_mov = new Date(value.fecha_movimiento);
                    var fecha_fin = (fecha_mov.getMonth()+1) + '/' + fecha_mov.getDate() + '/' +  fecha_mov.getFullYear();

                    var stock_actual = value.stock_actual ? parseFloat(value.stock_actual).toFixed(2) : 0.00;

                    tabla.row.add([ fecha_fin, value.Nombre, tipo_transaccion, comprobante, parseFloat(entrada).toFixed(2), parseFloat(salida).toFixed(2), stock_actual]).draw();
                });
            },
            dataType: 'json'
        })
    });
</script>
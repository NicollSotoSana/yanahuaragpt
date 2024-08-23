<?php echo form_open('cuentacorriente/generarComprobanteCpe', array('class' => 'upd')); ?>
<input type="hidden" name="deuda_id" value="<?php echo $deuda_id; ?>" >
<input type="hidden" name="id_cliente" value="<?php echo $id_cliente; ?>" >
<input type="hidden" name="correlativ" value="<?php echo $correlativ; ?>" >
<input type="hidden" name="id_cpe" value="<?php echo $id_comprobante; ?>" >
<div class="alert alert-success">
  Desde aquí usted puede agregar pagos a la deuda seleccionada, <strong style="font-size:14px;">sea cuidadoso con los montos</strong>.
</div>
<!--<input type="checkbox" name="nota" value="1" id="nota"> <label>Pago con Nota de Crédito</label>
<div class="col-md-12" id="notapago" style="display:none;">
	<div class="form-group">
		<label>Nro. Nota de Crédito:</label>
		<input type="text" name="nota_credito" class="form-control">
	</div>
</div>-->

<table class="table" id="tablapago">
	<thead>
		<th>Fecha</th>
		<th>Monto</th>
		<th>Medio</th>
		<th>Generar Comprobante?</th>
	</thead>
	<tbody>
		<tr>
			<td style="width:20px;"><input type="date" name="fechapago" class="form-control" value="<?php echo date('Y-m-d');?>"></td>
			<td>
				<input type="text" class="form-control" name="montopago" placeholder="Monto">
			</td>
			<td>
			    <select name="mediopago" class="form-control">
    				<?php if ($tipo_comprobante == 4): ?>
    					<option value="Deposito">Deposito</option>
    				<?php else: ?>
    					<option value="Efectivo">Efectivo</option>
    					<option value="Visa">Visa</option>
    					<option value="MC">Mastercard</option>
    					<option value="Estilos">Estilos</option>
    					<option value="Yape">Yape</option>
    					<option value="Deposito">Deposito</option>
    				<?php endif; ?>
    			</select>
			</td>
			<td>
				<select class="form-control" name="comprobanteTipo" id="sltComprobantePago">
					<option value="0">No</option>
					<option value="2">Boleta</option>
					<option value="3">Factura</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="12">
				<textarea rows="3" name="referencia" id="referencia" placeholder="Referencia (Nro. Operación)" class="form-control"></textarea>
			</td>
		</tr>
	</tbody>
</table>

<div class="col-md-8 filacliente" style="display:none;">
	<div class="form-group">
		<label for="">Cliente: </label>
		<input type="text" class="form-control" name="clientePago" id="txtClientePago" value="<?php echo $cliente->Nombre; ?>">
		<input type="hidden" class="form-control" name="id_clientePago" id="txtid" value="<?php echo $id_cliente; ?>">
	</div>
</div>

<div class="col-md-4 filacliente" style="display:none;">
	<div class="form-group">
		<label for="">RUC/DNI: </label>
		<input type="text" class="form-control" name="identidadPago" id="txtRucPago" value="<?php echo $cliente->Ruc != null ? $cliente->Ruc:$cliente->Dni ; ?>" readonly>
	</div>
</div>

<div class="col-md-12 filacliente" style="display:none;">
	<div class="form-group">
		<label for="">Dirección: </label>
		<input type="text" class="form-control" name="direccionPago" id="txtDireccionPago" value="<?php echo $cliente->Direccion; ?>" readonly>
	</div>
</div>

<div class="text-right">
	<button data-confirm="Una vez realizado el registro del pago este no se podrá modificar. ¿Desea continuar?" type="submit" id="btnDevolucionGuardar" class="btn btn-primary submit-ajax-button"><i class="glyphicon glyphicon-refresh"></i> Guardar</button>
</div>
<?php echo form_close(); ?>


<script>
$(document).ready(function(){
	$("#btnDevolucionGuardar").click(function(){

	});
	
	BuscarClientesPago();

});

$("#nota").on("change", function(){
	var value = $("#nota").val();
	//alert(value);
	if($('#nota').is(':checked')){
		$("#tablapago").hide();
		$("#notapago").fadeIn();
	}else{
		$("#tablapago").fadeIn();
		$("#notapago").hide();
	}
});

$("#sltComprobantePago").on("change", function(){
	var value = $("#sltComprobantePago").val();
	//alert(value);
	if(value != 0){
		$(".filacliente").fadeIn();
	}else{
		$(".filacliente").fadeOut();
	}
});

function BuscarClientesPago(){
	var input = $("#txtClientePago");
    input.autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
        	var tipo = $("#sltComprobante").val();    
        	
            jQuery.ajax({
                url: base_url('services/clientes'),
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term,
                    tipo: $("#sltComprobantePago").val()
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            id: item.id,
                            value: item.Nombre,
                            identidad: item.Identidad,
                            direccion: item.Direccion
                        }
                    }))
                }
            })
        },
        search  : function(){$(this).addClass('ui-autocomplete-loading');},
        open    : function(){$(this).removeClass('ui-autocomplete-loading');},
        select: function (e, ui) {
            $("#txtRucPago").val(ui.item.identidad);
            $("#txtDireccionPago").val(ui.item.direccion);
            $("#txtid").val(ui.item.id);

            var tipo = $("#sltComprobantePago").val();
            
            if(ui.item.identidad == '' && tipo == 3)
            {
            	alert('Este cliente no tiene Ruc, actualize su información para proceder con la factura.');
            }
            
            input.blur();
        }
    })
    input.focus(function () {
        $(this).val('');
    });
    input.blur(function () {
        $(this).val($(this).attr('data-name'));
    });
}
</script>

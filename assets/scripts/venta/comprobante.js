function BuscarClientes(){
	var input = $("#txtCliente");
    input.attr('data-name', input.val());
    input.attr('data-precio');
    input.attr('data-unidad');

    var cliente = $("#hdCliente_id");

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
                    tipo: $("#sltComprobante").val()
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
        	cliente.val(ui.item.id);
            input.attr('data-name', ui.item.value);
            $("#txtRuc").val(ui.item.identidad);
            $("#txtDireccion").val(ui.item.direccion);
            
            var tipo = $("#sltComprobante").val();
            
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
function BuscarProductos(id)
{
	var input = $("#" + id);

	if(input.hasClass('ui-autocomplete-input')) return;
	
    input.attr('data-name', input.val());
    input.attr('data-precio');
    input.attr('data-unidad');
    
    var tr = input.closest('tr');
    var producto = input.parent().find('input:hidden');

    input.autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: base_url('services/productosyserviciosstock'),
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            id: item.id,
                            value: item.Nombre.replace('[S/M] - ', ''),
                            und: item.UnidadMedida_id,
                            precio: item.Precio,
                            compra: item.PrecioCompra,
                            tipo: item.Tipo,
                            marca: item.Marca,
                            stock: item.Stock,
                            udmeq: item.udm_equivalente,
                            cant_eq: item.cant_equivalente,
                            gravado: item.gravado,
                        }
                    }))
                }
            })
        },
        search  : function(){$(this).addClass('ui-autocomplete-loading');},
        open    : function(){$(this).removeClass('ui-autocomplete-loading');},
        select: function (e, ui) {
        	
        	if(!e.ctrlKey)
        	{
        		if(HasModule('stock') && ui.item.stock == 0 && ui.item.tipo == 1)
        		{
        			alert('Esta agregando un producto que no contiene stock, de todas formas lo puede vender si es que se tratara de un error.\nLuego de esto es recomendable ajustar el Stock. ')
        		}

        		if(ui.item.gravado==0){
        			$("#txtIva").val("0.00");
        		}
        		
            	producto.val(ui.item.id);
            	
                input.attr('data-name', ui.item.value);
                input.attr('data-id', ui.item.id);

                tr.find('.txtUnidad').val(ui.item.und);
                //tr.find('.cant_eq').val(ui.item.cant_eq);
                /*tr.find('.txtUnidad').empty();
                tr.find('.txtUnidad').append('<option value="'+ui.item.und+'">'+ui.item.und+'</option>');*/
                /*if(ui.item.udmeq!=null){
                	tr.find('.txtUnidad').append('<option value="'+ui.item.udmeq+'">'+ui.item.udmeq+'</option>');
                }*/
                
                //tr.find('.txtUnidad').attr('readonly', false);
                tr.find('.txtCantidad').attr('readonly', false).val('1.00');
                tr.find('.txtPrecioUnitario').val(ui.item.precio);
                tr.find('.txtPrecioUnitario').attr('data-compra', ui.item.compra);
                tr.find('.txtPrecioUnitario').attr('title', 'PC: ' + moneda + ' ' + ui.item.compra);
                tr.find('.hdPrecioUnitarioCompra').val(ui.item.compra);
                tr.find('.hdTipo').val(ui.item.tipo);


                tr.find('.btnProductoQuitar').attr('disabled', false);
                
                input.blur();
                CalcularComprobante();
        	}
        	else
        	{
        		AjaxPopupModal('pdetalle', ui.item.label, 'popup/productoservicio', { id: ui.item.id, tipo: ui.item.tipo});
        		input.blur();
        		return false;
        	}
        }
    }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li>" )
		.append( '<a title="Para ver más detalle presione CTRL + CLICK.">' + item.value + '<span title="Precio de Compra / Precio de Venta en ' + moneda + ' ' + (HasModule('stock') && item.tipo == 1 ? ' / Stock Actual' : '') + '" style="float:right;font-size:12px;">' + item.compra + ' / ' + item.precio + (item.tipo == 1 && HasModule('stock') ? ' / ' + item.stock + ' ' + item.und.toLowerCase() : '') +  '</span></a>')
		.appendTo( ul );
    };
    
    input.focus(function () {
        $(this).val('');
    });
    input.blur(function () {
        $(this).val($(this).attr('data-name'));
    });
}

function CalcularComprobante()
{
	var total = 0;
	
	$(".txtProducto").each(function(){
		var tr = $(this).closest('tr');
		if($(this).attr('data-id') != '0')
		{
			var p  = parseFloat(tr.find('.txtPrecioUnitario').val());
			var c  = parseFloat(tr.find('.txtCantidad').val());
			var st = parseFloat(p*c);

			tr.find('.txtTotal').val(st.toFixed(2));
			total += st;
		}else
		{
			tr.find('.hdProducto_id').val('');
			tr.find('.hdPrecioUnitarioCompra').val('');
			tr.find('.txtProducto').val('').attr('data-name', '');
			tr.find('.txtCantidad').val('').attr('readonly', true);
			tr.find('.txtUnidad').val('');
			tr.find('.txtPrecioUnitario').val('').attr('readonly', true);
			tr.find('.txtPrecioUnitario').attr('title', '');
			tr.find('.txtPrecioUnitario').attr('data-compra', '0.00');
			tr.find('.txtTotal').val('');

			tr.find('.btnProductoQuitar').attr('disabled', true);
		}
	})

	total            = (total).toFixed(2);
	var iva          = ((parseFloat($("#txtIva").val()) / 100) + 1).toFixed(2);

	if($("#txtDsc").val()>0 && $("#txtDscT2").val()==0){
		var dsc1 = ((parseFloat($("#txtDsc").val()) / 100)).toFixed(2);
		var dsc = total * dsc1;
		
	}else if($("#txtDsc").val()==0 && $("#txtDscT2").val()>0){
		var dsc = parseFloat($("#txtDscT2").val()).toFixed(2);

	}else{
		var dsc=0;
	}
	
	var SubTotal     = ((total-dsc) / iva).toFixed(2);
	var IvaSubTotal  = ((total-dsc) - SubTotal).toFixed(2);
	var totfin = (total - dsc).toFixed(2);
	var dscSub = (totfin - total).toFixed(2);
	$("#txtSubTotal").val(SubTotal);
	$("#txtIvaSubTotal").val(IvaSubTotal);
	$("#txtDscT").val(dscSub);
	$("#txtTotal").val(totfin);

	/* Detracciones */
	if(totfin>=700 && iva>0){
		var porcentajeDetrac = parseFloat($("#txtPorDet").val()/100);
		//alert(porcentajeDetrac);
		var totalDetrac = parseFloat(totfin * porcentajeDetrac).toFixed(2);
		//alert(totalDetrac);
		$("#comp_detraccion").val(totalDetrac);
	}else{
		$("#comp_detraccion").val("0.00");
	}
	
}
function ValidarCampos()
{
	var v = ComprobanteTipo != 0 ? ComprobanteTipo : $("#sltComprobante").val();
	
	$("#txtCliente").attr('readonly', false);
	
	if(v=='3')
	{
		$("#txtCliente").addClass('required');
		$("#txtRuc").addClass('required');
		$("#txtDireccion").addClass('required');
		$("#spClienteRequerido").show();
		$("#spRucRequerido").show();
		$("#spDireccionRequerido").show();
		$("#trSubTotal").show();
		$("#trIva").show();
		$("#divOrden").hide();
		$("#spIdentidad").text('RUC');
		$("#txtRuc").attr('placeholder', 'RUC');
		$("#fact_adicional").show();
		$("#cotizacion_adicional").hide();
		$("#trAdela").show();

	}else if(v=='4'){
		$("#txtCliente").addClass('required');
		$("#txtRuc").addClass('required');
		$("#txtDireccion").addClass('required');
		$("#spClienteRequerido").show();
		$("#spRucRequerido").show();
		$("#spDireccionRequerido").show();
		$("#trSubTotal").show();
		$("#trIva").show();
		$("#divOrden").hide();
		$("#spIdentidad").text('RUC');
		$("#txtRuc").attr('placeholder', 'RUC');
		$("#fact_adicional").show();
		$("#cotizacion_adicional").hide();
		$("#trAdela").show();

	}else if(v=='0'){
		$("#txtCliente").attr('readonly', true);
		$("#spRucRequerido").hide();
		$("#spIdentidad").text('DNI');
		$("#txtRuc").attr('placeholder', 'DNI');
	}else if(v=='1'){
		$("#fact_adicional").hide();
		$("#trDetrac").hide();
		$("#txtCliente").removeClass('required');
		$("#txtRuc").removeClass('required');
		$("#txtDireccion").removeClass('required');
		$("#cotizacion_adicional").show();
		$("#divOrden").hide();
		$("#trDetrac").hide();
		$("#trAdela").show();
	}else if(v=='2'){
		$("#fact_adicional").hide();
		//$("#trDsc").hide();
		//$("#trDsc2").hide();
		$("#txtCliente").removeClass('required');
		$("#txtRuc").removeClass('required');
		$("#txtDireccion").removeClass('required');
		$("#cotizacion_adicional").hide();
	}else if(v=='8'){
		$("#trDetrac").hide();
		$("#proforma_adicional").hide();
		$("#trIva").show();
		$("#txtCliente").removeClass('required');
		$("#txtRuc").removeClass('required');
		$("#txtDireccion").removeClass('required');
		$("#cotizacion_adicional").hide();
	}else{
		$("#txtCliente").removeClass('required');
		$("#txtRuc").removeClass('required');
		$("#txtDireccion").removeClass('required');
		$("#spClienteRequerido").hide();
		$("#spRucRequerido").hide();
		$("#spDireccionRequerido").hide();
		$("#trSubTotal").hide();
		$("#trIva").hide();
		$("#spIdentidad").text('DNI');
		$("#txtRuc").attr('placeholder', 'DNI');
	}
}

$(document).ready(function(){
	$("#sltEstado").change(function(){
		var _default = $(this).data('estado');
		var select = $(this);
		if(select.val()=='3')
		{
			if(confirm('¿Esta seguro de anular el comprobante actual?'))
			{	
				$("#txtCliente").removeClass('required');
				$("#txtRuc").removeClass('required');
				$("#txtDireccion").removeClass('required');
				$("#btnGuardar").click();
			}else
			{
				$(this).val(_default);
			}
		}
		if(select.val()=='2' && _default == '4')
		{
			if(confirm('¿Esta seguro de aprobar el comprobante actual?,\nuna ves aprobado ya no podra editarlo en el futuro.'))
			{	
				$("#btnGuardar").click();
			}else
			{
				$(this).val(_default);
			}
		}
	})
	CalcularComprobante();
	$(".txtUnidad").change(function(){
		var tr = $(this).closest('tr');
		var index = tr.find('.txtUnidad').prop('selectedIndex');

		if(index==1){
			var equi 	= tr.find('.cant_eq').val();
			var preuni  = tr.find('.txtPrecioUnitario').val();
			var prequi  = parseFloat(preuni) / parseFloat(equi);
			tr.find('.precio_org').val(preuni);
			tr.find('.txtPrecioUnitario').val(prequi.toFixed(2));
		}else{
			var precio_org = tr.find('.precio_org').val();
			tr.find('.txtPrecioUnitario').val(precio_org);

		}
		CalcularComprobante();
	})
	$(".txtProducto").click(function(){
		BuscarProductos($(this).attr('id'));		
	})
	$("input").keypress(function(e){
		if(e.which == 13) return false;
	})
	$(".txtCantidad,#txtIva").keyup(function(e){
		if(e.which == 13) return false;
		
		var n = $(this).val();
		if(n >= 0)
		{
			CalcularComprobante();
		}
		else
		{
			$(this).val('');
		}
	})
	$(document).on("keyup", ".txtCantidad,#txtIva", function(e){
		if(e.which == 13) return false;
		
		var n = $(this).val();
		if(n >= 0)
		{
			CalcularComprobante();
		}
		else
		{
			$(this).val('');
		}
	})
	$("#txtDsc").keyup(function(e){
		
		CalcularComprobante();
		
	})
	$("#txtDscT2").keyup(function(e){
		
		CalcularComprobante();
		
	})
	$(".txtCantidad,#txtIva").focus(function(){
		$(this).attr('data-value', $(this).val())
		$(this).val('');
	})
	$(".txtCantidad,#txtIva").blur(function(){
		if($(this).val()=='')
		{
			$(this).val($(this).attr('data-value'));
		}
		else if($(this).hasClass('txtPrecioUnitario'))
		{
			var pc = parseFloat($(this).data('compra'));
			var pu = parseFloat($(this).val());

			/*if(pu <= pc)
			{
				$(this).val($(this).attr('data-value'));
				CalcularComprobante();
				alert('No puede vender el producto a menos de lo que vale.');
			}*/
		}
	})
	$(".btnProductoQuitar").click(function(){
		var tr = $(this).closest('tr');
		tr.find('.txtProducto').attr('data-id', '0');
		tr.find('.txtUnidad').empty();
		tr.find('.txtUnidad').attr('readonly', true);
		CalcularComprobante();
	})
	$("#sltComprobante").change(function(){
		/*if($(this).val() == '3') $("#btnClienteLimpiar").click();*/
		ValidarCampos();
	})

	$("#btnClienteLimpiar").click(function(){
		$("#txtCliente").val('');
		$("#txtCliente").removeClass('failed');

		$("#txtRuc").val('');
		$("#txtRuc").removeClass('failed');

		$("#txtDireccion").val('');
		$("#txtDireccion").removeClass('failed');

		$("#hdCliente_id").val('');
	});
	$("#btnImprimirComprobante").click(function(){
		ImprimirComprobante();
	})
	$("input[name='factura_gratuita']").change(function () {
		if ($("input[name='factura_gratuita']").is(':checked')) {
			
			$(".txtProducto").each(function () {
				//Si se marca factura gratuita, le quitamos igv a todos los precios unitarios
				var tr = $(this).closest('tr');
				var p = parseFloat(tr.find('.txtPrecioUnitario').val());
				if (!isNaN(p) && p != "0.00" && p != 0) {
					var sinigv = parseFloat(p / 1.18).toFixed(2);
					tr.find('.txtPrecioUnitario').val(sinigv);
				}
				
			});
			CalcularComprobante();
		} else {
			$(".txtProducto").each(function () {
				//Si se des-marca factura gratuita, le quitamos igv a todos los precios unitarios
				var tr = $(this).closest('tr');
				var p = parseFloat(tr.find('.txtPrecioUnitario').val());
				if (!isNaN(p) && p != "0.00" && p != 0) {
					var sinigv = parseFloat(p * 1.18).toFixed(2);
					tr.find('.txtPrecioUnitario').val(sinigv);
				}
				
			});
			CalcularComprobante();
		}
	})


	BuscarClientes();
	ValidarCampos();
})


function ImprimirComprobante()
{
	var id   = $("#btnImprimirComprobante").data('id');
	var idcli   = $("#btnImprimirComprobante").data('cliente');
	var correlativo = $("#btnImprimirComprobante").data('correlativo');
	var imp  = $("#btnImprimirComprobante").data('impresion');
	var tipo = $("#btnImprimirComprobante").data('tipo');
	var estado  = $("#btnImprimirComprobante").data('estado');
	
	$.post(base_url('ventas/ajax/DisponibleParaImprimir'),{
		id: id,
		idcli: idcli
	},function(r){
		if(!r.response)
		{
			alert(r.message);
		}else
		{
			if(confirm('¿Desea imprimir este comprobante?'))
			{
				if(imp == 2 || correlativo != '' || estado == 3)
				{
					alert('Por favor, coloque la hoja en la impresora y luego presione ACEPTAR.');
					window.location.href = base_url('ventas/impresion/' + id +'/'+idcli);					
				}else
				{
					if(confirm(r.message) == true)
					{
						alert('Por favor, coloque la hoja en la impresora y luego presione ACEPTAR.');
						window.location.href = base_url('ventas/impresion/' + id +'/'+idcli);
					}
					else
					{
						AjaxPopupModalDontClose('mpCorrelativo', 'Correlativo Incorrecto: <b style="color:red;">' + r.result + '</b>', 'ventas/ajax/CorrelativoIncorrecto', { id: id, correlativo: r.result, tipo: tipo });
					}						
				}
			}else
			{
				$.post(base_url('ventas/ajax/CancelarImpresion'),{
					id: id
				}, function(r){
					Volver();						
				}, 'json');
			}
		}
	}, 'json');
}

function ImpresionPendiente()
{
	var id   = $("#btnImprimirComprobante").data('id');
	var cliente   = $("#btnImprimirComprobante").data('cliente');
	var correlativo = $("#btnImprimirComprobante").data('correlativo');
	var tipo = $("#btnImprimirComprobante").data('tipo');
	var imp  = $("#btnImprimirComprobante").data('impresion');
	var estado  = $("#btnImprimirComprobante").data('estado');
	

	if(confirm('¿Este comprobante tiene una impresión pendiente, desea proseguir?'))
	{
		$.post(base_url('ventas/ajax/DisponibleParaImprimir'),{
			id: id
		},function(r){
			if(!r.response)
			{
				alert(r.message);
			}else
			{
				if(imp == 2 || correlativo != '' || estado == 3)
				{
					alert('Por favor, coloque la hoja en la impresora y luego presione ACEPTAR.');
					window.location.href = base_url('ventas/impresion/' + id+'/'+cliente);					
				}else
				{
					if(confirm(r.message))
					{
						alert('Por favor, coloque la hoja en la impresora y luego presione ACEPTAR.');
						window.location.href = base_url('ventas/impresion/' + id + '/'+cliente);
					}
					else
					{
						AjaxPopupModalDontClose('mpCorrelativo', 'Correlativo Incorrecto: <b style="color:red;">' + r.result + '</b>', 'ventas/ajax/CorrelativoIncorrecto', { id: id, correlativo: r.result, tipo: tipo });
					}						
				}
			}
		}, 'json');	
	}
	else
	{
		$.post(base_url('ventas/ajax/CancelarImpresion'),{
			id: id
		}, function(r){
			Volver();						
		}, 'json');
	}
}
function ComboEstadoDefault()
{
	var estado = $("sltEstado").data('estado');
	$("#sltEstado").val(estado);
}

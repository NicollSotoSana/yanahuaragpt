function BuscarProveedor(){
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
                url: base_url('services/proveedores'),
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
                            value: item.Nombre
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
                url: base_url('services/productosyservicios'),
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
                            precio: item.PrecioCompra,
                            compra: item.PrecioCompra,
                            costobase: item.CostoBase,
                            tipo: item.Tipo,
                            marca: item.Marca,
                            stock: item.Stock,
                            udmeq: item.udm_equivalente,
                            cant_eq: item.cant_equivalente,
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
        			//alert('Esta agregando un producto que no contiene stock, de todas formas lo puede vender si es que se tratara de un error.\nLuego de esto es recomendable ajustar el Stock. ')
        		}
        		
            	producto.val(ui.item.id);
            	
                input.attr('data-name', ui.item.value);
                input.attr('data-id', ui.item.id);

                tr.find('.txtUnidad').val(ui.item.und);
                tr.find('.cant_eq').val(ui.item.cant_eq);
                tr.find('.txtUnidad').empty();
                
                tr.find('.txtUnidad').attr('readonly', true);
                tr.find('.txtCantidad').attr('readonly', false).val('1.00');
                tr.find('.txtPrecioUnitario').attr('readonly', false).val(ui.item.compra);
                tr.find('.txtPrecioUnitarioReal').val(ui.item.compra);
                tr.find('.txtPrecioUnitario').attr('data-compra', ui.item.compra);
                tr.find('.txtPrecioUnitario').attr('title', 'PC: ' + moneda + ' ' + ui.item.compra);
                tr.find('.hdPrecioUnitarioCompra').val(ui.item.compra);
                tr.find('.hdTipo').val(ui.item.tipo);


                tr.find('.btnProductoQuitar').attr('disabled', false);
                
                input.blur();
                CalcularCompra();
        	}
        	else
        	{
        		AjaxPopupModal('pdetalle', ui.item.label, 'popup/productoservicio', { id: ui.item.id, tipo: ui.item.tipo});
        		input.blur();
        		return false;
        	}
        }
    });
    
    input.focus(function () {
        $(this).val('');
    });
    input.blur(function () {
        $(this).val($(this).attr('data-name'));
    });
}

function CalcularIGV(){
    $(".txtProducto").each(function(){
		var tr = $(this).closest('tr');
        if($(this).attr('data-id') != '0')
		{
            var p  = parseFloat(tr.find('.txtPrecioUnitario').val());
            var precio_igv = parseFloat(p*1.18).toFixed(2);
            tr.find('.txtPrecioUnitario').val(precio_igv);
        }
    });
    CalcularCompra();
}

function CalcularCompra(){
	var total = 0;
	
	$(".txtProducto").each(function(){
		var tr = $(this).closest('tr');
        
		if($(this).attr('data-id') != '0')
		{
            if(tr.find('.base_total').is(":checked")){
                //alert(tr.find('.base_total').val());
                var tot = parseFloat(tr.find('.txtTotal').val())/parseFloat(tr.find('.txtCantidad').val());
                tr.find('.txtPrecioUnitario').val(tot.toFixed(2));
                var p  = parseFloat(tot);
                var c  = parseFloat(tr.find('.txtCantidad').val());
                var st = parseFloat(p*c);

                tr.find('.txtTotal').val(st.toFixed(2));
                total += st;
            }else{
                //tr.find('.txtPrecioUnitario').val(tr.find('.txtPrecioUnitarioReal').val());
                var p  = parseFloat(tr.find('.txtPrecioUnitario').val());
                var c  = parseFloat(tr.find('.txtCantidad').val());
                var st = parseFloat(p*c);

                tr.find('.txtTotal').val(st.toFixed(2));
                total += st;
            }
			
		}else{

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

    //var igv   = ((parseFloat($("#txtIva").val()) / 100) + 1).toFixed(2);
    
	total            = (total).toFixed(2);
    //var total_igv = (total-(total/igv)).toFixed(2);
    var total_igv = $("#txtIvaSubTotal").val();
    var total_fin = parseFloat(total)+parseFloat(total_igv);
    //$("#txtIvaSubTotal").val(total_igv);
	$("#txtTotal").val(total_fin.toFixed(2));

}

$(document).ready(function(){
	BuscarProveedor();

	$(".txtProducto").click(function(){
		BuscarProductos($(this).attr('id'));		
	})

	$("input").keypress(function(e){
		if(e.which == 13) return false;
	})

    $(".base_total").change(function(e){
        CalcularCompra();
    })

	$(".txtCantidad,.txtPrecioUnitario,#txtIva,#txtIvaSubTotal").keyup(function(e){
		if(e.which == 13) return false;
			
		var n = $(this).val();
		if(n >= 0)
		{
			CalcularCompra();
		}
		else
		{
			$(this).val('');
		}
    })
    
    $(document).on("keyup", ".txtCantidad,.txtPrecioUnitario,#txtIva,#txtIvaSubTotal",function(e){
		if(e.which == 13) return false;
			
		var n = $(this).val();
		if(n >= 0)
		{
			CalcularCompra();
		}
		else
		{
			$(this).val('');
		}
	})

	$(".btnProductoQuitar").click(function(){
		var tr = $(this).closest('tr');
		tr.find('.txtProducto').attr('data-id', '0');
		tr.find('.txtUnidad').empty();
		tr.find('.txtUnidad').attr('readonly', true);
		CalcularCompra();
    })
    
    $(document).on("click", ".txtProducto", function(){
        BuscarProductos($(this).attr('id'));        
    })
    
    $(document).on("click", ".btnProductoQuitar", function(){
		var tr = $(this).closest('tr');
		tr.find('.txtProducto').attr('data-id', '0');
		tr.find('.txtUnidad').empty();
		tr.find('.txtUnidad').attr('readonly', true);
		CalcularCompra();
	})
})

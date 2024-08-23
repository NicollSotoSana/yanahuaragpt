$(document).on("change", "#checkcalculo",function(){
  //alert("asd");
  var valorg = parseFloat($("#PrecioCompra").val());

  if($("#checkcalculo").is(':checked')){
    $("#costoOrigen").val(valorg);
    var calcu = valorg + (valorg * 0.22);
    $("#PrecioCompra").val(calcu.toFixed(2));
  }else{
    var prevValor = parseFloat($("#costoOrigen").val());
    $("#PrecioCompra").val(prevValor.toFixed(2));
  }
});

$(document.body).on('keyup', "#Precio", function(event) {
  changeRange();
});

$(document).ready(function(){
    BuscarMarcas();
    BuscarMedidas();
    BuscarCat();
    
    $("#CostoBase").on("keyup", function(){
        var valorg = parseFloat($("#CostoBase").val());
        $("#costoOrigen").val(valorg);
        var cbase_igv = parseFloat(valorg - (valorg/1.18));
        var cbase_per = parseFloat(valorg * 0.055);
        var costotal = parseFloat(valorg + cbase_igv + cbase_per);

        $("#PrecioCompra").val(costotal.toFixed(2));

        var pventa = parseFloat(costotal * 2.3);

        $("#Precio").val(pventa.toFixed(2));

        changeRange();
        
    });
    
})

function changeRange(){
  var prec = $("#Precio").val();
  if(parseFloat(prec) <= 150){
    $("#rango").val("ECONOMICA");
  }else if(parseFloat(prec) > 150 && parseFloat(prec) <= 350){
    $("#rango").val("INTERMEDIA");
  }else if(parseFloat(prec) > 350){
    $("#rango").val("CARA");
  }
}

function BuscarMedidas()
{
  var input = $("#txtUnidadMedida_id");

    input.autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: base_url('services/medidas'),
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            value: item.UnidadMedida_id
                        }
                    }))
                }
            })
        },
        search  : function(){$(this).addClass('ui-autocomplete-loading');},
        open    : function(){$(this).removeClass('ui-autocomplete-loading');},
        select: function(e, ui){
            input.blur();
    }
    })
}
function BuscarMarcas()
{
  var input = $("#txtMarca");

    input.autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: base_url('services/marcas'),
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            value: item.Marca
                        }
                    }))
                }
            })
        },
        search  : function(){$(this).addClass('ui-autocomplete-loading');},
        open    : function(){$(this).removeClass('ui-autocomplete-loading');},
        select: function(e, ui){
            input.blur();
    }
    })
}

function BuscarCat()
{
    var input = $("#categoria_id");

    input.autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: base_url('services/productosCat'),
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            value: item.categoria
                        }
                    }))
                }
            })
        },
        search  : function(){$(this).addClass('ui-autocomplete-loading');},
        open    : function(){$(this).removeClass('ui-autocomplete-loading');},
        select: function (e, ui) {
          input.blur();
        }
    })
}



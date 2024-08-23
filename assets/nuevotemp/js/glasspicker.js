$("#disenio_lente").on("change", function(e){
	$("#fabricacion_lente").empty();
	$.ajax({
		url: base_url('services/buscarLentes'),
		type: "post",
        dataType: "json",
        data: {tipo: "fabricacion", wh: "disenio", val: $("#disenio_lente").val(), aw:""},
		success: function(respuesta) {
			$("#fabricacion_lente").append('<option hidden>Seleccione</option>');
			$.each(respuesta,function(key, value){
    			$("#fabricacion_lente").append("<option value='" + value.fabricacion + "'>" + value.fabricacion + "</option>");
			});
			console.log(respuesta);
		},
		error: function() {
		    console.log("No se ha podido obtener la información");
		}
	});
});

$("#fabricacion_lente").on("change", function(e){
	$("#material_lente2").empty();
	$.ajax({
		url: base_url('services/buscarLentes'),
		type: "post",
        dataType: "json",
        data: {tipo: "material", wh: "fabricacion", val: $("#fabricacion_lente").val(), aw:" AND disenio='"+$("#disenio_lente").val()+"'"},
		success: function(respuesta) {
			$("#material_lente2").append('<option hidden>Seleccione</option>');
			$.each(respuesta,function(key, value){
    			$("#material_lente2").append("<option value='" + value.material + "'>" + value.material + "</option>");
			});
			//console.log(respuesta);
		},
		error: function() {
		    console.log("No se ha podido obtener la información");
		}
	});
});


$("#material_lente2").on("change", function(e){
	$("#serie_lente").empty();
	$.ajax({
		url: base_url('services/buscarLentes'),
		type: "post",
        dataType: "json",
        data: {tipo: "serie", wh: "material", val: $("#material_lente2").val(), aw:" AND disenio='"+$("#disenio_lente").val()+"' AND fabricacion='"+$("#fabricacion_lente").val()+"'"},
		success: function(respuesta) {
			$("#serie_lente").append('<option hidden>Seleccione</option>');
			$.each(respuesta,function(key, value){
    			$("#serie_lente").append("<option value='" + value.serie + "'>" + value.serie + "</option>");
			});
			//console.log(respuesta);
		},
		error: function() {
		    console.log("No se ha podido obtener la información");
		}
	});
});


$("#serie_lente").on("change", function(e){
	$("#tratamiento_lente").empty();
	$.ajax({
		url: base_url('services/buscarLentes'),
		type: "post",
        dataType: "json",
        data: {tipo: "tratamiento", wh: "material", val: $("#material_lente2").val(), aw:" AND disenio='"+$("#disenio_lente").val()+"' AND fabricacion='"+$("#fabricacion_lente").val()+"' AND serie='"+$("#serie_lente").val()+"'"},
		success: function(respuesta) {
			$("#tratamiento_lente").append('<option hidden>Seleccione</option>');
			$.each(respuesta,function(key, value){
    			$("#tratamiento_lente").append("<option value='" + value.tratamiento + "'>" + value.tratamiento + "</option>");
			});
			//console.log(respuesta);
		},
		error: function() {
		    console.log("No se ha podido obtener la información");
		}
	});
});

$("#tratamiento_lente").on("change", function(e){
	$("#nombre_lente").empty();
	$.ajax({
		url: base_url('services/buscarLentes'),
		type: "post",
        dataType: "json",
        data: {tipo: "nombre", wh: "material", val: $("#material_lente2").val(), aw:" AND disenio='"+$("#disenio_lente").val()+"' AND fabricacion='"+$("#fabricacion_lente").val()+"' AND serie='"+$("#serie_lente").val()+"' AND tratamiento='"+$("#tratamiento_lente").val()+"'"},
		success: function(respuesta) {
			$("#nombre_lente").append('<option hidden>Seleccione</option>');
			$.each(respuesta,function(key, value){
    			$("#nombre_lente").append("<option value='" + value.nombre + "'>" + value.nombre + "</option>");
			});
			//console.log(respuesta);
		},
		error: function() {
		    console.log("No se ha podido obtener la información");
		}
	});
});

$("#nombre_lente").on("change", function(e){
	$("#fotocroma_lente").empty();
	$.ajax({
		url: base_url('services/buscarLentes'),
		type: "post",
        dataType: "json",
        data: {tipo: "fotocromatico", wh: "material", val: $("#material_lente2").val(), aw:" AND disenio='"+$("#disenio_lente").val()+"' AND fabricacion='"+$("#fabricacion_lente").val()+"' AND serie='"+$("#serie_lente").val()+"' AND tratamiento='"+$("#tratamiento_lente").val()+"' AND nombre='"+$("#nombre_lente").val()+"'"},
		success: function(respuesta) {
			$("#fotocroma_lente").append('<option hidden>Seleccione</option>');
			$.each(respuesta,function(key, value){
    			$("#fotocroma_lente").append("<option value='" + value.fotocromatico + "'>" + value.fotocromatico + "</option>");
			});
			//console.log(respuesta);
		},
		error: function() {
		    console.log("No se ha podido obtener la información");
		}
	});
});

$("#fotocroma_lente").on("change", function(e){
	$("#color_fotocroma_lente").empty();
	$.ajax({
		url: base_url('services/buscarLentes'),
		type: "post",
        dataType: "json",
        data: {tipo: "color_fotocromatico", wh: "material", val: $("#material_lente2").val(), aw:" AND disenio='"+$("#disenio_lente").val()+"' AND fabricacion='"+$("#fabricacion_lente").val()+"' AND serie='"+$("#serie_lente").val()+"' AND tratamiento='"+$("#tratamiento_lente").val()+"' AND nombre='"+$("#nombre_lente").val()+"' AND fotocromatico='"+$("#fotocroma_lente").val()+"'"},
		success: function(respuesta) {
			$("#color_fotocroma_lente").append('<option hidden>Seleccione</option>');
			$.each(respuesta,function(key, value){
    			$("#color_fotocroma_lente").append("<option value='" + value.color_fotocromatico + "'>" + value.color_fotocromatico + "</option>");
			});
			//console.log(respuesta);
		},
		error: function() {
		    console.log("No se ha podido obtener la información");
		}
	});
});

$("#color_fotocroma_lente").on("change", function(e){
	$("#precio_lente").val("");
	$.ajax({
		url: base_url('services/buscarPrecioLente'),
		type: "post",
        dataType: "json",
        data: {disenio: $("#disenio_lente").val(), fabricacion: $("#fabricacion_lente").val(), material: $("#material_lente2").val(), serie: $("#serie_lente").val(), tratamiento_lente: $("#tratamiento_lente").val(), nombre: $("#nombre_lente").val(), fotocromatico: $("#fotocroma_lente").val(), color_fotocromatico: $("#color_fotocroma_lente").val()},
		success: function(respuesta) {
			if(respuesta.success == true){
				$("#id_material").val(respuesta.id_precio);
				$("#precio_lente").val(respuesta.precio);
				$("#material_lente").val(respuesta.material_lente);
				$("#material_lente_hide").val(respuesta.nombre_lab);
				$("#precio_compra").val(respuesta.precio_compra);
				$("#precio_venta").val(respuesta.precio);
			}else{
				alert("Vuelva a intentarlo");
			}
		},
		error: function() {
		    console.log("No se ha podido obtener la información");
		}
	});
});
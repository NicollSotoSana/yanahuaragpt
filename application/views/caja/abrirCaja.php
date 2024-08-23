<?php 
echo form_open('caja/guardarCaja'); 
if($flag==0){
?>
<div class="col-md-4">
	<p><strong>Último cierre de caja:</strong> S/. <?php echo $caja_ayer;?></p>
	<div class="form-group">
		<label>Monto en Caja:</label>
		<input type="text" class="form-control" name="monto_inicial" placeholder="S/.">
	</div>
</div>
<div class="col-md-2" style="display:none;">
	<div class="form-group">
		<label>Monto en Cuenta Bancaria:</label>
		<input type="text" class="form-control" name="monto_cb" placeholder="S/.">
	</div>
</div>

<div class="col-md-12">
	<button data-confirm="Sea cuidadoso, una vez aperturada la caja no se podrá modificar. ¿Desea continuar?" type="submit" id="btnDevolucionGuardar" class="btn btn-success"><i class="glyphicon glyphicon-refresh"></i> Guardar</button>
</div>
<?php 
	echo form_close(); 
	}else if($flag==1 && ($this->user->Tipo == 1 || $this->user->Tipo == 5)){
?>

<div class="alert alert-warning" style="font-size:1.3em;">
	
	<p><strong>Último cierre de caja:</strong> S/. <?php echo $caja_ayer;?></p>
  	<srong>Atención!</strong> Ya se ha aperturado caja el día de hoy. Puede ver el <a href="<?php echo base_url('caja/cuadrarCaja');?>"><b>movimiento de caja de hoy, modificar el monto de caja inicial o cerrar caja.</b></a>
</div>
<div class="col-md-3">
	<div class="form-group">
		<label>Monto en Caja:</label>
		<input type="text" class="form-control" id="monto_inicial" name="monto_inicial" placeholder="S/." value="<?php echo $caja_dia;?>">
	</div>
	<button type="button" class="btn btn-success" id="modificarCaja"><i class="glyphicon glyphicon-refresh"></i> Actualizar Monto</button>
</div>
<div class="col-md-offset-3 col-md-6">
	<h3>Cierre de Caja</h3>
	<table class="table table-hover">
		<thead>
			<th></th>
			<th>Contabilizado</th>
			<th>Sistema</th>
			<th>Diferencia</th>
		</thead>
		<tbody>
			<tr>
				<td><b style="font-size: 12px;">Efectivo</b></td>
				<td><input type="number" class="form-control" name="total_efectivo" value="0.00"></td>
				<td><input type="text" class="form-control" name="sup_efectivo" value="<?php echo number_format($caja["ingresosVentasEfe"]["total"]+$caja["ingresosDeudasEfe"]["total"]+$caja["inicio"], 2);?>" readonly></td>
				<td><input type="text" class="form-control" name="diferencia_efectivo" value="0.00" readonly></td>
			</tr>
			<tr>
				<td><b style="font-size: 12px;">Visa</b></td>
				<td><input type="number" class="form-control" name="total_visa" value="0.00"></td>
				<td><input type="text" class="form-control" name="sup_visa" value="<?php echo number_format($caja["ingresosVentasVisa"]["total"]+$caja["ingresosDeudasVisa"]["total"], 2);?>" readonly></td>
				<td><input type="text" class="form-control" name="diferencia_visa" value="0.00" readonly></td>
			</tr>
			<tr>
				<td><b style="font-size: 12px;">MasterCard</b></td>
				<td><input type="number" class="form-control" name="total_mc" value="0.00"></td>
				<td><input type="text" class="form-control" name="sup_mc" value="<?php echo number_format($caja["ingresosVentasMc"]["total"]+$caja["ingresosDeudasMc"]["total"], 2);?>" readonly></td>
				<td><input type="text" class="form-control" name="diferencia_mc" value="0.00" readonly></td>
			</tr>
			<tr>
				<td><b style="font-size: 12px;">Estilos</b></td>
				<td><input type="number" class="form-control" name="total_estilos" value="0.00"></td>
				<td><input type="text" class="form-control" name="sup_estilos" value="<?php echo number_format($caja["ingresosVentasEst"]["total"]+$caja["ingresosDeudasEst"]["total"], 2);?>" readonly></td>
				<td><input type="text" class="form-control" name="diferencia_estilos" value="0.00" readonly></td>
			</tr>
			<tr>
				<td><b style="font-size: 12px;">Depósito</b></td>
				<td><input type="number" class="form-control" name="total_deposito" value="0.00"></td>
				<td><input type="text" class="form-control" name="sup_deposito" value="<?php echo number_format($caja["ingresosVentasDepo"]["total"]+$caja["ingresosDeudasDepo"]["total"], 2);?>" readonly></td>
				<td><input type="text" class="form-control" name="diferencia_deposito" value="0.00" readonly></td>
			</tr>
			<tr>
				<td><b style="font-size: 12px;">Yape</b></td>
				<td><input type="number" class="form-control" name="total_yape" value="0.00"></td>
				<td><input type="text" class="form-control" name="sup_yape" value="<?php echo number_format($caja["ingresosVentasYape"]["total"]+$caja["ingresosDeudasYape"]["total"], 2);?>" readonly></td>
				<td><input type="text" class="form-control" name="diferencia_yape" value="0.00" readonly></td>
			</tr>
		</tbody>
	</table>

	<button type="button" class="btn btn-danger" id="cerrarCaja"><i class="glyphicon glyphicon-remove"></i> Cerrar Caja</button>
</div>
<?php }else if($flag==2){?>
	<div class="col-md-12">
		<div class="alert alert-danger" style="font-size:1.3em;">
			Atención!</strong> La caja del día de hoy <strong>se encuentra cerrada</strong>.</b></a>
		</div>
	</div>
<?php 
	}
?>

<script>
$("#modificarCaja").on("click", function(){
	$.ajax({
		data:  {monto: $("input[name='monto_inicial']").val()}, //datos que se envian a traves de ajax
		url:   'actualizarCaja', //archivo que recibe la peticion
		type:  'post', //método de envio
		beforeSend: function () {
				//$("#resultado").html("Procesando, espere por favor...");
		},
		success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
			$.toast({
				text: "Monto inicial de caja actualizado correctamente!",
				heading: 'Correcto',
				icon: 'success',
				showHideTransition: 'fade',
				allowToastClose: true,
				hideAfter: 5000,
				stack: 5,
				position: 'top-right',
				textAlign: 'left',
				loader: true,
				loaderBg: '#9EC600'
			});
		}
	});
});



$("#cerrarCaja").on("click", function(){
	let datos_send = {total_efectivo: $("input[name='total_efectivo']").val(), total_visa: $("input[name='total_visa']").val(), total_mc: $("input[name='total_mc']").val(), total_estilos: $("input[name='total_estilos']").val(), total_deposito: $("input[name='total_deposito']").val(), total_yape: $("input[name='total_yape']").val()};

	console.log(datos_send);

	$.ajax({
		data: datos_send, //datos que se envian a traves de ajax
		url:   'cerrarCaja', //archivo que recibe la peticion
		type:  'POST', //método de envio
		success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
			$.toast({
				text: "Caja cerrada correctamente!",
				heading: 'Correcto',
				icon: 'success',
				showHideTransition: 'fade',
				allowToastClose: true,
				hideAfter: 5000,
				stack: 5,
				position: 'top-right',
				textAlign: 'left',
				loader: true,
				loaderBg: '#9EC600'
			});
			location.reload();
		}
	});
});

$("input[name='total_efectivo']").on("keyup", function(){
	let sistema = $("input[name='sup_efectivo']").val();
	let contabilizado = $("input[name='total_efectivo']").val();

	let diferencia = parseFloat(contabilizado) - parseFloat(sistema);

	if(!isNaN(diferencia)){
		$("input[name='diferencia_efectivo']").val(diferencia.toFixed(2));
	}else{
		$("input[name='diferencia_efectivo']").val("0.00");
	}
	
});

$("input[name='total_visa']").on("keyup", function(){
	let sistema = $("input[name='sup_visa']").val();
	let contabilizado = $("input[name='total_visa']").val();

	let diferencia = parseFloat(contabilizado) - parseFloat(sistema);

	if(!isNaN(diferencia)){
		$("input[name='diferencia_visa']").val(diferencia.toFixed(2));
	}else{
		$("input[name='diferencia_visa']").val("0.00");
	}
	
});

$("input[name='total_mc']").on("keyup", function(){
	let sistema = $("input[name='sup_mc']").val();
	let contabilizado = $("input[name='total_mc']").val();

	let diferencia = parseFloat(contabilizado) - parseFloat(sistema);

	if(!isNaN(diferencia)){
		$("input[name='diferencia_mc']").val(diferencia.toFixed(2));
	}else{
		$("input[name='diferencia_mc']").val("0.00");
	}

});

$("input[name='total_estilos']").on("keyup", function(){
	let sistema = $("input[name='sup_estilos']").val();
	let contabilizado = $("input[name='total_estilos']").val();

	let diferencia = parseFloat(contabilizado) - parseFloat(sistema);

	if(!isNaN(diferencia)){
		$("input[name='diferencia_estilos']").val(diferencia.toFixed(2));
	}else{
		$("input[name='diferencia_estilos']").val("0.00");
	}

});

$("input[name='total_deposito']").on("keyup", function(){
	let sistema = $("input[name='sup_deposito']").val();
	let contabilizado = $("input[name='total_deposito']").val();

	let diferencia = parseFloat(contabilizado) - parseFloat(sistema);

	if(!isNaN(diferencia)){
		$("input[name='diferencia_deposito']").val(diferencia.toFixed(2));
	}else{
		$("input[name='diferencia_deposito']").val("0.00");
	}
});

$("input[name='total_yape']").on("keyup", function(){
	let sistema = $("input[name='sup_yape']").val();
	let contabilizado = $("input[name='total_yape']").val();

	let diferencia = parseFloat(contabilizado) - parseFloat(sistema);

	if(!isNaN(diferencia)){
		$("input[name='diferencia_yape']").val(diferencia.toFixed(2));
	}else{
		$("input[name='diferencia_yape']").val("0.00");
	}
});

</script>
<div class="row form-group">
        <div class="col-xs-12">
            <ul class="nav nav-pills nav-justified thumbnail setup-panel">
                <li class="active">
					<a href="#step-1">
						<h4 class="list-group-item-heading">Paso 1</h4>
						<p class="list-group-item-text">Datos del cliente y Anamnesis</p>
					</a>
				</li>
                <li class="disabled">
					<a href="#step-2">
						<h4 class="list-group-item-heading">Paso 2</h4>
						<p class="list-group-item-text">Orden de Laboratorio</p>
					</a>
				</li>
				<li class="disabled">
					<a href="#step-3">
						<h4 class="list-group-item-heading">Paso 3</h4>
						<p class="list-group-item-text">Emisión de Comprobante</p>
					</a>
				</li>
            </ul>
        </div>
	</div>

<div class="row">	  
	<div class="col-md-12 setup-content" id="step-1">
		<form method="post" action="" id="formpaciente">
			<div class="col-md-2">
				<div class="form-group">
					<label>DNI</label>
					<input type="text" name="dni" id="dniclie" class="form-control" autocomplete="off" maxlength="8" value="<?php echo $paciente->Dni?>" readonly>
					<input type="hidden" name="id_cliente" id="id_cliente" class="form-control" value="<?php echo $paciente->id;?>">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Nombre</label>
					<input type="text" name="nombre" id="nombreclie" class="form-control" autocomplete="no" value="<?php echo $paciente->Nombre;?>" readonly>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Direccion</label>
					<input type="text" name="direccion" id="direccionclie" class="form-control" autocomplete="no" value="<?php echo $paciente->Direccion;?>" readonly>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Correo Electrónico</label>
					<input type="text" name="correo_electronico" id="correoclie" class="form-control" autocomplete="no" value="<?php echo $paciente->Correo;?>" readonly>
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group">
					<label>Fecha de Nacimiento</label>
					<input type="date" name="fecha_nacimiento" id="nacimientoclie" class="form-control" value="<?php echo $paciente->fecha_nac;?>" readonly>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Teléfono</label>
					<input type="text" name="telefono" id="telefonoclie" class="form-control" autocomplete="no" value="<?php echo $paciente->Telefono1;?>" readonly>
				</div>
			</div>
			<div class="col-md-3" style="display:none; ">
				<div class="form-group">
					<label>Ocupación</label>
					<input type="text" name="trabajo" id="trabajoclie" class="form-control" autocomplete="no" value="<?php echo $paciente->trabajo;?>" readonly>
				</div>
			</div>
		
		<div class="col-md-4"><!-- en blanco para rellenar espacio --></div>

		<div class="col-md-12"><hr></div>

		<div class="col-md-6">
			<div class="form-group">
				<label>Antecedentes del Padre</label>
				<textarea name="antecedentes_padre" class="form-control" readonly><?php echo $anamnesis->antecedentes_padre;?></textarea>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Antecedentes de la Madre</label>
				<textarea name="antecedentes_madre" class="form-control" readonly><?php echo $anamnesis->antecedentes_madre;?></textarea>
			</div>
		</div>

		<div class="col-md-12"><hr></div>

		<div class="col-md-4">
			<div class="funkyradio">
		        <div class="funkyradio-success">
		            <input type="checkbox" name="ojo_seco" id="checkbox1" value="1" <?php echo ($anamnesis->ojo_seco == 1)?"checked":"";?> disabled/>
		            <label for="checkbox1">Ojo Seco</label>
		        </div>
		        <div class="funkyradio-success">
		            <input type="checkbox" name="estrabismo" id="checkbox2" value="1" <?php echo ($anamnesis->estrabismo == 1)?"checked":"";?> disabled/>
		            <label for="checkbox2">Estrabismo</label>
		        </div>
		        <div class="funkyradio-success">
		            <input type="checkbox" name="fatiga_visual" id="checkbox3" value="1" <?php echo ($anamnesis->fatiga_visual == 1)?"checked":"";?> disabled/>
		            <label for="checkbox3">Fatiga Visual</label>
		        </div>
		        <div class="funkyradio-success">
		            <input type="checkbox" name="dolor_cabeza" id="checkbox4" value="1" <?php echo ($anamnesis->dolor_cabeza == 1)?"checked":"";?> disabled/>
		            <label for="checkbox5">Dolor de Cabeza</label>
		        </div>

		    </div>
		</div>
		<div class="col-md-4">
			<div class="funkyradio">
		        <div class="funkyradio-success">
		            <input type="checkbox" name="diabetes" id="checkbox5" value="1" <?php echo ($anamnesis->diabetes == 1)?"checked":"";?> disabled/>
		            <label for="checkbox5">Diabetes</label>
		        </div>
		        <div class="funkyradio-success">
		            <input type="checkbox" name="ojos_rojos" id="checkbox6" value="1" <?php echo ($anamnesis->ojos_rojos == 1)?"checked":"";?> disabled/>
		            <label for="checkbox6">Ojos Rojos</label>
		        </div>
		        <div class="funkyradio-success">
		            <input type="checkbox" name="leganas" id="checkbox7" value="1" <?php echo ($anamnesis->leganas == 1)?"checked":"";?> disabled/>
		            <label for="checkbox7">Legañas</label>
		        </div>
		        <div class="funkyradio-success">
		            <input type="checkbox" name="mareos" id="checkbox8" value="1" <?php echo ($anamnesis->mareos == 1)?"checked":"";?> disabled/>
		            <label for="checkbox8">Mareos</label>
		        </div>
		    </div>
		</div>
		<div class="col-md-4">
			<div class="funkyradio">
		        <div class="funkyradio-success">
		            <input type="checkbox" name="daltonismo" id="checkbox9" value="1" <?php echo ($anamnesis->daltonismo == 1)?"checked":"";?> disabled/>
		            <label for="checkbox9">Daltonismo</label>
		        </div>
		        <div class="funkyradio-success">
		            <input type="checkbox" name="degeneracion_macular" id="checkbox10" value="1" <?php echo ($anamnesis->degeneracion_macular == 1)?"checked":"";?> disabled/>
		            <label for="checkbox10">Degeneración Macular</label>
		        </div>
		        <div class="funkyradio-success">
		            <input type="checkbox" name="reumatismo" id="checkbox11" value="1" <?php echo ($anamnesis->reumatismo == 1)?"checked":"";?> disabled/>
		            <label for="checkbox11">Reumatismo</label>
		        </div>
		    </div>
		</div>

		<div class="col-md-12"><hr></div>

		<div class="col-md-6">
			<div class="form-group">
				<label>Medicamentos que actualmente utiliza:</label>
				<input type="text" name="mecanismos" class="form-control" value="<?php echo $anamnesis->mecanismos;?>">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Frecuencia de visita al oculista:</label>
				<input type="text" name="frecuencia" class="form-control" value="<?php echo $anamnesis->frecuencia;?>">
			</div>
		</div>
		<div style="clear:both;"></div>
		<!--<button id="activate-step-2" class="btn btn-primary btn-lg pull-right">Siguiente</button>-->
		</form>

		<div class="col-md-12"><hr></div>
	</div>

	<!--<div class="col-md-12 setup-content" id="step-2">
		
	</div>-->

	<div class="col-md-12 setup-content" id="step-2">
		<input type="hidden" name="id_eval" id="id_eval" class="form-control" value="<?php echo isset($evaluacion->id_evaluacion) ? $evaluacion->id_evaluacion : ''; ?>">
		<div class="col-md-12">
			<!--<hr>
				<center><h2>Orden de Laboratorio</h2></center>
			<hr>-->
			<div class="col-md-12" style="display:none;">
				<div class="form-group">
					<label>Nro. Orden Laboratorio</label>
					<input type="text" name="orden_lab_nro" class="form-control" id="orden_lab_nro" value="">
				</div>
			</div>

			
		</div>
		
		<div class="col-md-12">
			<form method="post" action="" id="ordenlabfrm">
			<hr>
				<center><h2>Datos de Montura & Lentes</h2></center>
			<hr>
			<div class="col-md-5">
				<div class="form-inline">
					<label>Buscar Montura ó Escanear Cod. Barras</label><br>
					<input type="text" name="montura" class="form-control" id="txtProducto" value=""  style="width:90%!important;">
					<button class="btn btn-danger" id="del_montura" type="button"><i class="fa fa-times"></i></button>
					<input type="hidden" name="id_montura" class="form-control" id="id_montura">
				</div>
			</div>
			<div class="col-md-2">
				<center><p style="font-size:2em;">ó</p></center>
			</div>
			<div class="col-md-5">
				<div class="form-group">
					<label>Montura de Paciente</label>
					<input type="text" name="montura_paciente" class="form-control" id="txtPaciente" value="">
				</div>
			</div>
			<div class="col-md-5">
				<div class="form-group">
					<label style="font-size: 1.4em; color:#000;">Tipo de Montura: </label><br/>
					<input type="radio" name="tipo_montura" value="Aro Completo" style="margin-left:20px;"> <label>Aro Completo</label>
					<input type="radio" name="tipo_montura" value="Semi al Aire" style="margin-left:20px;"> <label>Semi al Aire</label>
					<input type="radio" name="tipo_montura" value="Al Aire" style="margin-left:20px;"> <label>Al Aire</label>
				</div>
			</div>
			<div class="col-md-7">
				<div class="form-group">
					<label style="font-size: 1.4em; color:#000;">Montaje Con: </label><br/>
					<input type="radio" name="montaje" value="Bisell Brillante" style="margin-left:20px;"> <label>Bisell Brillante</label>
					<input type="radio" name="montaje" value="Faceteado" style="margin-left:20px;"> <label>Faceteado</label>
					<input type="radio" name="montaje" value="Pase de Lunas" style="margin-left:20px;"> <label>Pase de Lunas</label>
					<input type="radio" name="montaje" value="Reduccion de Diametro" style="margin-left:20px;"> <label>Reduccion de Diametro</label>
				</div>
			</div>

			<div class="col-md-12">
				<hr>
				<div class="col-md-4">
						<div class="form-group">
							<label style="font-size: 1.2em; color:#000;">1. Diseño</label>
							<select class="form-control" name="disenio_lente" id="disenio_lente">
								<option hidden>Selecccione</option>
								<?php
									foreach($disenio as $d){
										echo '<option value="'.$d->disenio.'">'.$d->disenio.'</option>';
									}
								?>
							</select>

							<input type="hidden" name="material_lente" id="material_lente" class="form-control">
							<input type="hidden" name="material_lente_hide" id="material_lente_hide" class="form-control">
							<input type="hidden" name="id_material" id="id_material" class="form-control">
							<input type="hidden" name="precio_compra" id="precio_compra" class="form-control">
							<input type="hidden" name="precio_venta" id="precio_venta" class="form-control">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label style="font-size: 1.2em; color:#000;">2. Fabricación</label>
							<select class="form-control" name="fabricacion_lente" id="fabricacion_lente">
								<option hidden>Selecccione</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label style="font-size: 1.2em; color:#000;">3. Material</label>
							<select class="form-control" name="material_lente2" id="material_lente2">
								<option hidden>Selecccione</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label style="font-size: 1.2em; color:#000;">4. Serie</label>
							<select class="form-control" name="serie_lente" id="serie_lente">
								<option hidden>Selecccione</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label style="font-size: 1.2em; color:#000;">5. Tratamiento</label>
							<select class="form-control" name="tratamiento_lente" id="tratamiento_lente">
								<option hidden>Selecccione</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label style="font-size: 1.2em; color:#000;">6. Nombre</label>
							<select class="form-control" name="nombre_lente" id="nombre_lente">
								<option hidden>Selecccione</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label style="font-size: 1.2em; color:#000;">7. Fotocromat.</label>
							<select class="form-control" name="fotocroma_lente" id="fotocroma_lente">
								<option hidden>Selecccione</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label style="font-size: 1.2em; color:#000;">8. Color Fotocromat.</label>
							<select class="form-control" name="color_fotocroma_lente" id="color_fotocroma_lente">
								<option hidden>Selecccione</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label style="font-size: 1.2em; color:#000;">9. Precio</label>
							<input type="text" class="form-control" name="precio_lente" id="precio_lente" readonly>
						</div>
					</div>
				<hr>
			</div>
			</form>
		</div>

		<form id="evaluacion" method="post" action="">
			<input type="hidden" name="id_anamnesis" id="id_anamnesis" value="<?php echo $anamnesis->id_anamnesis;?>">
			<input type="hidden" name="id_clie" id="id_clie" class="form-control">
			<div class="col-md-5">
				<div class="form-group">
					<h3>Lensometría (<b>Lentes Actuales</b>)</h3>
					<table class="table">
						<thead>
							<tr>
								<th> </th>
								<th>Esfera (esf)</th>
								<th>Cilindro (cil)</th>
								<th>Eje</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>O.D.</td>
								<td><input type="text" name="lens_od_esf" class="form-control" value=""></td>
								<td><input type="text" name="lens_od_cil" class="form-control" value=""></td>
								<td><input type="text" name="lens_od_eje" class="form-control" value=""></td>
							</tr>
							<tr>
								<td>O.I.</td>
								<td><input type="text" name="lens_oi_esf" class="form-control" value=""></td>
								<td><input type="text" name="lens_oi_cil" class="form-control" value=""></td>
								<td><input type="text" name="lens_oi_eje" class="form-control" value=""></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<div class="col-md-7" style="margin-top:80px;">
				<div class="col-md-4">
					<label>Ángulo Panorámico</label>
					<input type="text" name="angulo_panoramico" class="form-control" value="<?php echo isset($eval_meta["angulo_panoramico"]) ? $eval_meta["angulo_panoramico"] : ''; ?>">
				</div>
				<div class="col-md-4">
					<label>Ángulo Pantoscópico</label>
					<input type="text" name="angulo_pantoscopico" class="form-control" value="<?php echo isset($eval_meta["angulo_pantoscopico"]) ? $eval_meta["angulo_pantoscopico"] : ''; ?>">
				</div>
				<div class="col-md-4">
					<label>Distancia Vértice</label>
					<input type="text" name="distancia_vertice" class="form-control" value="<?php echo isset($eval_meta["distancia_vertice"]) ? $eval_meta["distancia_vertice"] : ''; ?>">
				</div>
			</div>
			<div class="col-md-12">
				<h3>Refracción</h3>
				
				<table class="table table-bordered table-hover">
					<thead>
						<tr style="text-align: center;">
							<th colspan="6" style="font-size: 2em;color:#000;">Lejos</th>
							<th colspan="3" style="font-size: 2em;color:#000;"><button type="button" id="btncerca" class="btn btn-warning" style="margin-right:20px;">Visión de Cerca</button> <button type="button" id="btntransp" class="btn btn-info">Trasposición de Cilindros</button></th>
						</tr>
						<tr>
							<th> </th>
							<th>ESF</th>
							<th>CYL</th>
							<th>EJE</th>
							<th>ADICIÓN</th>
							<th>DIP</th>
							<th>ALT</th>
							<th>PRISMAS</th>
							<th>A.V.C.C.</th>
						</tr>
					</thead>
					<tbody>

						<tr>
							<td><strong>O.D.</strong></td>
							<td><input type="text" name="lejos_refra_od_esf" class="form-control" value=""></td>
							<td><input type="text" name="lejos_refra_od_cyl" class="form-control" value=""></td>
							<td><input type="text" name="lejos_refra_od_eje" class="form-control" value=""></td>
							<td><input type="text" name="lejos_refra_od_adicion" class="form-control" value=""></td>
							<td><input type="text" name="lejos_refra_od_dnp" class="form-control" value=""></td>
							<td><input type="text" name="lejos_refra_od_alt" class="form-control" value=""></td>
							<td><input type="text" name="lejos_refra_od_prismas" class="form-control" value=""></td>
							<td><input type="text" name="lejos_refra_od_avcc" class="form-control" value=""></td>
						</tr>
						<tr>
							<td><strong>O.I.</strong></td>
							<td><input type="text" name="lejos_refra_oi_esf" class="form-control" value=""></td>
							<td><input type="text" name="lejos_refra_oi_cyl" class="form-control" value=""></td>
							<td><input type="text" name="lejos_refra_oi_eje" class="form-control" value=""></td>
							<td><input type="text" name="lejos_refra_oi_adicion" class="form-control" value=""></td>
							<td><input type="text" name="lejos_refra_oi_dnp" class="form-control" value=""></td>
							<td><input type="text" name="lejos_refra_oi_alt" class="form-control" value=""></td>
							<td><input type="text" name="lejos_refra_oi_prismas" class="form-control" value=""></td>
							<td><input type="text" name="lejos_refra_oi_avcc" class="form-control" value=""></td>
						</tr>
						<tr style="text-align: center;">
							<th colspan="8" style="font-size: 2em;color:#000;">Cerca</th>
						</tr>

						<tr>
							<td><strong>O.D.</strong></td>
							<td><input type="text" name="cerca_refra_od_esf" class="form-control" value=""></td>
							<td><input type="text" name="cerca_refra_od_cyl" class="form-control" value=""></td>
							<td><input type="text" name="cerca_refra_od_eje" class="form-control" value=""></td>
							<td><input type="text" name="cerca_refra_od_adicion" class="form-control" value=""></td>
							<td><input type="text" name="cerca_refra_od_dnp" class="form-control" value=""></td>
							<td><input type="text" name="cerca_refra_od_alt" class="form-control" value=""></td>
							<td><input type="text" name="cerca_refra_od_prismas" class="form-control" value=""></td>
							<td><input type="text" name="cerca_refra_od_avcc" class="form-control" value=""></td>
						</tr>
						<tr>
							<td><strong>O.I.</strong></td>
							<td><input type="text" name="cerca_refra_oi_esf" class="form-control" value=""></td>
							<td><input type="text" name="cerca_refra_oi_cyl" class="form-control" value=""></td>
							<td><input type="text" name="cerca_refra_oi_eje" class="form-control" value=""></td>
							<td><input type="text" name="cerca_refra_oi_adicion" class="form-control" value=""></td>
							<td><input type="text" name="cerca_refra_oi_dnp" class="form-control" value=""></td>
							<td><input type="text" name="cerca_refra_oi_alt" class="form-control" value=""></td>
							<td><input type="text" name="cerca_refra_oi_prismas" class="form-control" value=""></td>
							<td><input type="text" name="cerca_refra_oi_avcc" class="form-control" value=""></td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="col-md-8">
				<div class="form-group">
					<label for="" style="font-size:14px;">Observaciones</label>
					<textarea name="observaciones_lab" id="observaciones_lab" class="form-control" cols="30" rows="3"></textarea>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Fecha de Entrega</label>
					<input type="text" name="fecha_entrega" class="form-control" id="fecha_entrega" value="">
				</div>
			</div>
			</form>

		<button id="activate-step-3" class="btn btn-success btn-lg pull-right">Siguiente</button>
	</div>

	<div class="col-md-12 setup-content" id="step-3">
		<h3> Pago</h3>

	</div>
</div>

<script>
	$(document).ready(function() {
		BuscarMateriales();
		BuscarProductos();
		$("#fecha_entrega").datetimepicker({format: 'DD/MM/YYYY|HH:mm'});
		var navListItems = $('ul.setup-panel li a'),
			allWells = $('.setup-content');

		allWells.hide();

		navListItems.click(function(e)
		{
			e.preventDefault();
			var $target = $($(this).attr('href')),
				$item = $(this).closest('li');
			
			if (!$item.hasClass('disabled')) {
				navListItems.closest('li').removeClass('active');
				$item.addClass('active');
				allWells.hide();
				$target.show();
			}
		});
		
		$('ul.setup-panel li.active a').trigger('click');
		
		var idanam = 0;


			$('ul.setup-panel li:eq(1)').removeClass('disabled');
			$('ul.setup-panel li:eq(2)').removeClass('disabled');
			$('ul.setup-panel li a[href="#step-2"]').trigger('click');
			$(this).remove();
	

		$('#activate-step-3').on('click', function(e) {
			if($("#fecha_entrega").val()==''){
				errorToast("Debe colocar una fecha y hora de entrega.");
				return false;
			}

			if($("#id_montura").val()=='' && $("#txtPaciente").val()==''){
				errorToast("Debe seleccionar una montura (de la tienda o del paciente).");
				return false;
			}
			if($("#precio_lente").val()==''){
				errorToast("No ha seleccionado el lente correctamente (debe seleccionar todos los campos, incluso si contiene n/a).");
				return false;
			}
			$('ul.setup-panel li:eq(3)').removeClass('disabled');
			$('ul.setup-panel li a[href="#step-4"]').trigger('click');
			//$(this).remove();
			$.ajax({
				url:"<?php echo base_url();?>procesocliente/guardarEvaluacion",
				method:"POST", //First change type to method here
				dataType: "json",
				data: { data: $('#evaluacion').serializeArray(), id_clie: $("#id_cliente").val(), id_anamnesis: $("#id_anamnesis").val() },
				success:function(response) {
					$("#id_eval").val(response.id_evaluacion);
					$.ajax({
						url:"<?php echo base_url();?>procesocliente/guardarOrdenlab",
						method:"POST", //First change type to method here
						dataType: "json",
						data: { data: $('#ordenlabfrm').serializeArray(), id_eval: $("#id_eval").val(), nro_orden: $("#orden_lab_nro").val(), fecha_entrega: $("#fecha_entrega").val(), precio_compra: $("#precio_compra").val(), id_clie: $("#id_cliente").val(), id_anamnesis: $("#id_anamnesis").val(), observaciones: $("#observaciones_lab").val()},
						success:function(response) {
							//$("#id_eval").val(response.id_evaluacion);
							//$("#step-4").html('<div class="box"><iframe src="<?php echo base_url();?>" width = "500px" height = "500px"></iframe></div>');

							window.location.href = "<?php echo base_url();?>ventas/comprobanteOrdenLab/"+response.id_orden+"/"+$("#id_anamnesis").val();
						},
						error:function(){
							alert("error");
						}
					});
				},
				error:function(){
					alert("error");
				}
			});
		})
	});

$("#del_montura").on("click", function(){
	$("#txtProducto").val("");
	$("#id_montura").val("");
});

$(document).on('keydown', '#txtProducto', function (e) {
    var key = e.which;
    if(key == 13) {
    	e.preventDefault();
        $.ajax({
			url: base_url('services/productosporcod'),
			type: "post",
            dataType: "json",
            data: {criterio: $("#txtProducto").val()},
			success: function(respuesta) {
				$("#txtProducto").attr('data-name',respuesta[0].Nombre);
	            $("#txtProducto").attr('data-id', respuesta[0].id);
				$("#id_montura").val(respuesta[0].id);
	        	$("#txtProducto").val(respuesta[0].Nombre);
				console.log(respuesta[0].id);
			},
			error: function() {
		        console.log("No se ha podido obtener la información");
		    }
		});
    }
});

function BuscarMateriales()
{
	var input = $("#material_lente2");

    input.autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: base_url('services/preciolente'),
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            value: item.material,
                            precio: item.precio,
                            laboratorio: item.laboratorio,
                        }
                    }))
                }
            })
        },
        search  : function(){$(this).addClass('ui-autocomplete-loading');},
        open    : function(){$(this).removeClass('ui-autocomplete-loading');},
        select: function(e, ui){
            $("#precio_lente").val(ui.item.precio);
            $("#descripcion_lente_hide").val(ui.item.value);
            $("#laboratorio_lente").val(ui.item.laboratorio);
		}
    })
}

function BuscarAnamnesis(idcliente){
	$.ajax({
		url: base_url('services/buscarAnamnesis'),
		type: "post",
		dataType: "json",
		data: {idcliente: idcliente},
		success: function(respuesta) {
			if(respuesta.fecha_anamnesis){
				var str = respuesta.fecha_anamnesis;
				var strDate = str.replace(/-/g,":").replace(/ /g,":").split(":");
				$("#step-1").prepend('<div class="alert alert-warning"><p style="font-size:1.3em;">Última Anamnesis de este paciente fue: <b>'+strDate[2]+'/'+strDate[1]+'/'+strDate[0]+'</b>.<br/>¿Desea retomar esta anamnesis? <button type="button" class="btn btn-success">Si</button>  <button type="button" class="btn btn-danger">No</button></p></div>');
			}
			
		},
		error: function() {
			console.log("No se ha podido obtener la información");
		}
	});
}

function BuscarProductos()
{
	var input = $("#txtProducto");

    input.autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: base_url('services/productos'),
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            id: item.id,
                            value: item.Nombre,
                            und: item.UnidadMedida_id,
                            nombre: item.Nombre,
                            marca: item.Marca,
                            pc: item.PrecioCompra,
							stock: item.Stock,
							tipo_aro: item.tipo_aro
                        }
                    }))
                }
            })
        },
        search  : function(){$(this).addClass('ui-autocomplete-loading');},
        open    : function(){$(this).removeClass('ui-autocomplete-loading');},
        select: function (e, ui) {
        	input.blur();
        	$("#producto_id").val(ui.item.id);
        	
            input.attr('data-name', ui.item.value);
            input.attr('data-id', ui.item.id);

        	input.val(ui.item.nombre);
        	$("#txtUnidadMedida_id").val(ui.item.und);
        	$("#txtCosto").val(ui.item.costo);
        	$("#txtPrecioCompra").val(ui.item.pc);
            $("#txtStock").val(ui.item.stock);
			$("#id_montura").val(ui.item.id);
			
			if(ui.item.tipo_aro == "ARO COMPLETO"){
				$("input[name=tipo_montura][value='Aro Completo']").prop("checked",true);
			}
			if(ui.item.tipo_aro == "SEMI AL AIRE"){
				$("input[name=tipo_montura][value='Semi al Aire']").prop("checked",true);
			}
			if(ui.item.tipo_aro == "AL AIRE"){
				$("input[name=tipo_montura][value='Al Aire']").prop("checked",true);
			}
            //CalcularPrecio();
        	return false;
        }
    })

    input.focus(function () {
        $(this).val('');
    });
    input.blur(function () {
        $(this).val($(this).attr('data-name'));
    });
}

function errorToast(str){
	$.toast({
			text: str,
			heading: 'Error',
			icon: 'error',
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

$(document).on("click", "input[name='montaje']", function(){
    thisRadio = $(this);
    if (thisRadio.hasClass("imChecked")) {
        thisRadio.removeClass("imChecked");
        thisRadio.prop('checked', false);
    } else { 
        thisRadio.prop('checked', true);
        thisRadio.addClass("imChecked");
    };
})
</script>
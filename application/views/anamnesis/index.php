<div class="row form-group">
        <div class="col-xs-12">
            <ul class="nav nav-pills nav-justified thumbnail setup-panel">
                <li class="active">
					<a href="#step-1">
						<h4 class="list-group-item-heading">Paso 1</h4>
						<p class="list-group-item-text">Datos del cliente y Anamnesis</p>
					</a>
				</li>
                <!--<li class="disabled">
					<a href="#step-2">
						<h4 class="list-group-item-heading">Paso 2</h4>
						<p class="list-group-item-text">Registro de Evaluación</p>
					</a>
				</li>-->
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

<div class="row" id="bloqueocarga">	  
	<div class="col-md-12 setup-content" id="step-1">
		<form method="post" action="" id="formpaciente">
			<div class="col-md-2">
				<div class="form-group">
					<label>Tipo Doc.</label>
					<select name="tipo_doc" id="tipo_doc" class="form-control">
						<option value="0">Seleccione</option>
						<option value="1">DNI</option>
						<option value="2">C.E.</option>
						<option value="3">Pasaporte</option>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Nro. Doc.</label>
					<input type="text" name="dni" id="dniclie" class="form-control" autocomplete="off" maxlength="8">
					<input type="hidden" name="id_cliente" id="id_cliente" class="form-control">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Nombre</label>
					<input type="text" name="nombre" id="nombreclie" class="form-control" autocomplete="no">
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Direccion</label>
					<input type="text" name="direccion" id="direccionclie" class="form-control" autocomplete="no">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Correo Electrónico</label>
					<input type="text" name="correo_electronico" id="correoclie" class="form-control" autocomplete="no">
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group">
					<label>Fecha de Nacimiento</label>
					<input type="date" name="fecha_nacimiento" id="nacimientoclie" class="form-control">
				</div>
			</div>

			<div class="col-md-1">
				<div class="form-group">
					<label>Edad</label>
					<input type="text" name="edad_clie" id="edad_clie" class="form-control" readonly>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Teléfono</label>
					<input type="text" name="telefono" id="telefonoclie" class="form-control" autocomplete="no">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Rubro de Trabajo</label>
					<select name="id_rubro_trabajo" id="id_rubro_trabajo" class="form-control">
						<option value="0">Seleccione</option>
						<?php
							foreach($rubros_trabajo as $rb){
								echo '<option value="'.$rb->id_rubro_trabajo.'">'.$rb->rubro_trabajo.'</option>';
							}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Profesión</label>
					<select name="id_profesion" id="id_profesion" class="form-control">
						<option value="0">Seleccione</option>
						<?php
							foreach($profesiones as $pf){
								echo '<option value="'.$pf->id_profesion.'">'.$pf->profesion.'</option>';
							}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-4" style="display:none;">
				<div class="form-group">
					<label>Ocupación</label>
					<input type="text" name="trabajo" id="trabajoclie" class="form-control" autocomplete="no">
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group">
					<label>Sexo</label>
					<select name="sexo" id="sexo" class="form-control">
						<option value="0">Seleccione</option>
						<option value="m">Masculino</option>
						<option value="f">Femenino</option>
					</select>
				</div>
			</div>
		
			<div class="col-md-3">
				<div class="form-group">
					<label>Distrito</label>
					<select name="id_distrito" id="id_distrito" class="form-control">
						<option value="0">Seleccione</option>
						<?php
							foreach($distritos as $dt){
								echo '<option value="'.$dt->id_distrito.'">'.$dt->distrito.'</option>';
							}
						?>
					</select>
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Ciudad</label>
					<select name="id_ciudad" id="id_ciudad" class="form-control">
						<option value="0">Seleccione</option>
						<?php
							foreach($ciudades as $dt){
								echo '<option value="'.$dt->id_ciudad.'">'.$dt->ciudad.'</option>';
							}
						?>
					</select>
				</div>
			</div>

		<div class="col-md-12"><hr></div>

		<div class="col-md-6">
			<div class="form-group">
				<label>Antecedentes del Padre</label>
				<textarea name="antecedentes_padre" class="form-control"></textarea>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Antecedentes de la Madre</label>
				<textarea name="antecedentes_madre" class="form-control"></textarea>
			</div>
		</div>

		<div class="col-md-12"><hr></div>

		<div class="col-md-4">
			<div class="funkyradio">
		        <div class="funkyradio-success">
		            <input type="checkbox" name="ojo_seco" id="checkbox1" value="1"/>
		            <label for="checkbox1">Ojo Seco</label>
		        </div>
		        <div class="funkyradio-success">
		            <input type="checkbox" name="estrabismo" id="checkbox2" value="1"/>
		            <label for="checkbox2">Estrabismo</label>
		        </div>
		        <div class="funkyradio-success">
		            <input type="checkbox" name="fatiga_visual" id="checkbox3" value="1"/>
		            <label for="checkbox3">Fatiga Visual</label>
		        </div>
		        <div class="funkyradio-success">
		            <input type="checkbox" name="dolor_cabeza" id="checkbox4" value="1"/>
		            <label for="checkbox4">Dolor de Cabeza</label>
		        </div>

		    </div>
		</div>
		<div class="col-md-4">
			<div class="funkyradio">
		        <div class="funkyradio-success">
		            <input type="checkbox" name="diabetes" id="checkbox5" value="1"/>
		            <label for="checkbox5">Diabetes</label>
		        </div>
		        <div class="funkyradio-success">
		            <input type="checkbox" name="ojos_rojos" id="checkbox6" value="1"/>
		            <label for="checkbox6">Ojos Rojos</label>
		        </div>
		        <div class="funkyradio-success">
		            <input type="checkbox" name="leganas" id="checkbox7" value="1"/>
		            <label for="checkbox7">Legañas</label>
		        </div>
		        <div class="funkyradio-success">
		            <input type="checkbox" name="mareos" id="checkbox8" value="1"/>
		            <label for="checkbox8">Mareos</label>
		        </div>
		    </div>
		</div>
		<div class="col-md-4">
			<div class="funkyradio">
		        <div class="funkyradio-success">
		            <input type="checkbox" name="daltonismo" id="checkbox9" value="1"/>
		            <label for="checkbox9">Daltonismo</label>
		        </div>
		        <div class="funkyradio-success">
		            <input type="checkbox" name="degeneracion_macular" id="checkbox10" value="1"/>
		            <label for="checkbox10">Degeneración Macular</label>
		        </div>
		        <div class="funkyradio-success">
		            <input type="checkbox" name="reumatismo" id="checkbox11" value="1"/>
		            <label for="checkbox11">Reumatismo</label>
		        </div>
		    </div>
		</div>

		<div class="col-md-12"><hr></div>

		<div class="col-md-6">
			<div class="form-group">
				<label>Medicamentos que actualmente utiliza:</label>
				<input type="text" name="mecanismos" class="form-control">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Frecuencia de visita al oculista:</label>
				<input type="text" name="frecuencia" class="form-control">
			</div>
		</div>

		<div class="col-md-3">
			<div class="form-group">
				<label>Clínica Referente:</label>
				<select class="js-selector" name="id_clinica" id="clinica_ref">
					<option value="0">-- Seleccione --</option>
					<?php
						foreach($clinicas as $cli){
							echo '<option value="'.$cli->id_clinica.'">'.$cli->clinica_nombre.'</option>';
						}
					?>
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label>Doctor Referente:</label>
				<select class="js-selector" name="id_doctor" id="doctor_ref">
					<option value="0">-- Seleccione --</option>
					<?php
						foreach($doctores as $docs){
							echo '<option value="'.$docs->id_doctor.'">'.$docs->doctor.'</option>';
						}
					?>
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label>Empresa Convenio:</label>
				<select class="js-selector" name="id_empresa_conv" id="id_empresa_conv">
					<option value="0">-- Seleccione --</option>
					<?php
						foreach($convenios as $cv){
							echo '<option value="'.$cv->id_emp_conv.'">'.$cv->empresa.'</option>';
						}
					?>
				</select>
			</div>
		</div>
		<div style="clear:both;"></div>
		<!--<button id="activate-step-2" class="btn btn-primary btn-lg pull-right">Siguiente</button>-->
		</form>

		<div class="col-md-12"><hr></div>
			<button id="activate-step-2" class="btn btn-primary btn-lg pull-right">Siguiente</button>
		</form>
	</div>

	<!--<div class="col-md-12 setup-content" id="step-2">
		
	</div>-->

	<div class="col-md-12 setup-content" id="step-2">
		<input type="hidden" name="id_eval" id="id_eval" class="form-control" value="">
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
					<label>Buscar Montura ó Escanear Cod. Barras</label>
					<input type="text" name="montura" class="form-control" id="txtProducto" value="" style="width:90%!important;">
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
			<div class="col-md-4">
				<div class="form-group">
					<label style="font-size: 1.4em; color:#000;">Tipo de Montura: </label><br/>
					<input type="radio" name="tipo_montura" value="Aro Completo" style="margin-left:18px;"> <label>Aro Completo</label>
					<input type="radio" name="tipo_montura" value="Semi al Aire" style="margin-left:18px;"> <label>Semi al Aire</label>
					<input type="radio" name="tipo_montura" value="Al Aire" style="margin-left:18px;"> <label>Al Aire</label>
				</div>
			</div>
			<div class="col-md-8">
				<div class="form-group">
					<label style="font-size: 1.4em; color:#000;">Montaje Con: </label><br/>
					<input type="radio" name="montaje" value="Bisell Brillante" style="margin-left:18px;"> <label>Bisell Brillante</label>
					<input type="radio" name="montaje" value="Faceteado" style="margin-left:18px;"> <label>Faceteado</label>
					<input type="radio" name="montaje" value="Pase de Lunas" style="margin-left:18px;"> <label>Pase de Lunas</label>
					<input type="radio" name="montaje" value="Reduccion de Diametro" style="margin-left:18px;"> <label>Reduccion de Diametro</label>
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

			<form id="evaluacion" method="post" action="">
			<input type="hidden" name="id_anamnesis" id="id_anamnesis" value="">
			<input type="hidden" name="id_clie" id="id_clie" class="form-control">
			<div class="col-md-5">
				<div class="form-group">
					<label>Lensometría (<i>Lentes Actuales</i>)</label>
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
					<input type="text" name="angulo_panoramico" class="form-control" value="">
				</div>
				<div class="col-md-4">
					<label>Ángulo Pantoscópico</label>
					<input type="text" name="angulo_pantoscopico" class="form-control" value="">
				</div>
				<div class="col-md-4">
					<label>Distancia Vértice</label>
					<input type="text" name="distancia_vertice" class="form-control" value="">
				</div>
			</div>
			<div class="col-md-12">
				<label>Refracción</label>
				
				<table class="table table-bordered table-hover">
					<thead>
						<tr style="text-align: center;">
							<th colspan="4" style="font-size: 2em;color:#000;">Lejos</th>
							<th colspan="5" style="font-size: 2em;color:#000;">
								<button type="button" id="verultreceta" class="btn btn-primary" style="margin-right:20px;"><i class="fa fa-search"></i> Ver Última Receta</button>
								<button type="button" id="btncerca" class="btn btn-warning" style="margin-right:20px;">Visión de Cerca</button> 
								<button type="button" id="btntransp" class="btn btn-info">Trasposición de Cilindros</button>
							</th>
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
					<input type="datetime" name="fecha_entrega" class="form-control" id="fecha_entrega" value="">
				</div>
			</div>
			</form>
		</div>

		<button id="activate-step-3" class="btn btn-success btn-lg pull-right">Siguiente</button>
	</div>

	<div class="col-md-12 setup-content" id="step-3">
		<h3> Pago</h3>

	</div>
</div>

<div id="modalultreceta" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="row">
			<div class="col-md-12">
				<table class="table table-bordered table-hover">
					<thead>
						<tr style="text-align: center;">
							<th colspan="9" style="font-size: 1.8;color:#000;" id="fecha_ult_eval"></th>
						</tr>
						<tr style="text-align: center;">
							<th colspan="9" style="font-size: 1.5em;color:#000;">Lejos</th>
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
							<td><input type="text" id="ult_lejos_refra_od_esf" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_lejos_refra_od_cyl" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_lejos_refra_od_eje" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_lejos_refra_od_adicion" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_lejos_refra_od_dnp" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_lejos_refra_od_alt" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_lejos_refra_od_prismas" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_lejos_refra_od_avcc" class="form-control" value="" readonly></td>
						</tr>
						<tr>
							<td><strong>O.I.</strong></td>
							<td><input type="text" id="ult_lejos_refra_oi_esf" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_lejos_refra_oi_cyl" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_lejos_refra_oi_eje" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_lejos_refra_oi_adicion" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_lejos_refra_oi_dnp" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_lejos_refra_oi_alt" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_lejos_refra_oi_prismas" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_lejos_refra_oi_avcc" class="form-control" value="" readonly></td>
						</tr>
						<tr style="text-align: center;">
							<th colspan="9" style="font-size: 1.5em;color:#000;">Cerca</th>
						</tr>

						<tr>
							<td><strong>O.D.</strong></td>
							<td><input type="text" id="ult_cerca_refra_od_esf" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_cerca_refra_od_cyl" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_cerca_refra_od_eje" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_cerca_refra_od_adicion" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_cerca_refra_od_dnp" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_cerca_refra_od_alt" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_cerca_refra_od_prismas" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_cerca_refra_od_avcc" class="form-control" value="" readonly></td>
						</tr>
						<tr>
							<td><strong>O.I.</strong></td>
							<td><input type="text" id="ult_cerca_refra_oi_esf" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_cerca_refra_oi_cyl" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_cerca_refra_oi_eje" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_cerca_refra_oi_adicion" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_cerca_refra_oi_dnp" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_cerca_refra_oi_alt" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_cerca_refra_oi_prismas" class="form-control" value="" readonly></td>
							<td><input type="text" id="ult_cerca_refra_oi_avcc" class="form-control" value="" readonly></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
			<button type="button" class="btn btn-success" id="usar_ult_receta">Usar Receta</button>
		</div>
    </div>

  </div>
</div>
<input type="hidden" value="" id="ultima_receta">

<script>

	$(document).ready(function() {
	$('.js-selector').select2();
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

	$("#tipo_doc").on("change", function(e){
		var tipodoc = $("#tipo_doc").val();
		console.log("tipodoc: "+tipodoc);
		if(tipodoc != "1"){
			$("#dniclie").attr('maxlength','12');
		}else{
			$("#dniclie").attr('maxlength','8');
		}
		
	});


    $('#activate-step-2').on('click', function(e) {
		var flag = 0;
		var dniIng = $("#dniclie").val();
		var tipo_doc = $("#tipo_doc").val();
		var doc_len = dniIng.length;
		var nomIng = $("#nombreclie").val();
		var rubroTrabajo = $("#id_rubro_trabajo").val();
		var sexo = $("#sexo").val();

		if(tipo_doc == 0){
			errorToast("Debe seleccionar un tipo de documento.");
			return false;
		}

		if(tipo_doc == 1 && doc_len < 8){
			errorToast("Debe ingresar un número de DNI válido.");
			return false;
		}

		if(tipo_doc == 1 && doc_len > 8){
			errorToast("Debe ingresar un número de DNI válido.");
			return false;
		}

		if(sexo == "0"){
			errorToast("Debe seleccionar un sexo.");
			return false;
		}

		if(dniIng == ""){
			errorToast("Debe ingresar un Nro. de Doc.");
			return false;
		}

		if(nomIng == ""){
			errorToast("Debe ingresar un nombre.");
			return false;
		}

		
		//$(this).remove();
		
		$(this).prop('disabled', true);
		
        //Guardamos anamnesis
        $.ajax({
			url:"<?php echo base_url();?>procesocliente/addanamnesis",
	        method:"POST", //First change type to method here
	        dataType: "json",
			data: $('#formpaciente').serialize(),
			success:function(response) {
				if(response.result == "ok"){
					$('ul.setup-panel li:eq(1)').removeClass('disabled');
        			$('ul.setup-panel li:eq(2)').removeClass('disabled');
        			$('ul.setup-panel li a[href="#step-2"]').trigger('click');
					idanam = response.id_anamnesis;
					console.log(idanam);
					$("#id_anamnesis").val(response.id_anamnesis);
					$("#id_cliente").val(response.id_cliente);
					$("#id_clie").val(response.id_cliente);
					
					
					$("#step-1 :input").attr("disabled", true);
					$("#step-1 :select").attr("disabled", true);
				}else{

					errorToast(response.message);
					$("#activate-step-2").removeAttr('disabled');
				}
				
			},
			error:function(){
				alert("error");
			}
		});
        
    }) 

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
        $(this).remove();

		//llamamos funcion que guarda evaluacion
		var id_anamnesis_rsp = $("#id_anamnesis").val();
		guardarEvaluacion(id_anamnesis_rsp);

    })

	$("#nacimientoclie").on("blur", function(e){
		var birth = $("#nacimientoclie").val();
		var birthdate = new Date(birth);
		var cur = new Date();
		var diff = cur-birthdate;
		var age = Math.floor(diff/31536000000);
		$("#edad_clie").val(age);
	});
});


$("#dniclie").on("keyup", function(e){
	var dni = $(this).val();
	var tipo_doc = $("#tipo_doc").val();

	if(dni.length > 5){
		var block = $('<div class="block-loading" id="carga"/>');
        $("#bloqueocarga").prepend(block);
		$.post( "<?php echo base_url();?>services/buscarCliente", { dni: dni, tipo: 1 }, function( data ) {
			if(data.Nombre != null || data.success != false){

				/* Si el cliente existe, buscamos anamnesis pasadas */
				BuscarAnamnesis(data.id);

				$.toast({
				    text: "Paciente encontrado.",
				    heading: 'Listo!',
				    icon: 'success',
				    showHideTransition: 'fade',
				    allowToastClose: true,
				    hideAfter: 3000,
				    stack: 5,
				    position: 'top-right',
				    textAlign: 'left',
				    loader: true,
				    loaderBg: '#9EC600'
				});
				$("#id_clie").val(data.id);
				$("#id_cliente").val(data.id);
				$("#nombreclie").val(data.Nombre);
				$("#direccionclie").val(data.Direccion);
				$("#correoclie").val(data.Correo);
				$("#nacimientoclie").val(data.fecha_nac);
				$("#trabajoclie").val(data.trabajo);
				$("#telefonoclie").val(data.Telefono1);
				$("#id_rubro_trabajo").val(data.id_rubro_trabajo);
				$("#id_profesion").val(data.id_profesion);
				$("#sexo").val(data.sexo);
				$("#id_distrito").val(data.id_distrito);
				$("#id_ciudad").val(data.id_ciudad);

				if(data.lista_negra == 1){
					$("#step-1").prepend('<div class="alert alert-danger"><p style="font-size:1.3em;"><b>Usuario !</b></p></div>');
				}
			}else if(dni.length == 8 && tipo_doc == 1){
				$.get(base_url('services/getReniec/'+dni), function( data ) {
					if(data.success==true){
						//alert(data.result.DNI);
						var nom = data.result.Nombres+' '+data.result.Apellidos;
						$("#nombreclie").val(nom);
						$.toast({
							text: "Paciente encontrado en RENIEC, debe llenar los demás datos del paciente.",
							heading: 'Listo!',
							icon: 'warning',
							showHideTransition: 'fade',
							allowToastClose: true,
							hideAfter: 5000,
							stack: 5,
							position: 'top-right',
							textAlign: 'left',
							loader: true,
							loaderBg: '#9EC600'
						});
					}else{
						//alert(data.error);
						$.toast({
							text: "No se encontró el paciente, debe llenar los datos para registrarlo.", 
							heading: 'Info',
							icon: 'error',
							showHideTransition: 'fade',
							allowToastClose: true,
							hideAfter: 4000,
							stack: 5,
							position: 'top-right',
							textAlign: 'left',
							loader: true,
							loaderBg: '#9EC600'
						});
					}

					$("#carga").remove();
					
				}, 'json');

			}
			
		}, 'json');
		$("#carga").remove();
	}
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

$("#del_montura").on("click", function(){
	$("#txtProducto").val("");
	$("#id_montura").val("");
});


function guardarEvaluacion(idanamnesis){
	//console.log($('#evaluacion').serializeArray());
	//Guardamos evaluacion
	$.ajax({
		url:"<?php echo base_url();?>procesocliente/guardarEvaluacion",
	    method:"POST", //First change type to method here
	    dataType: "json",
		data: { data: $('#evaluacion').serializeArray(), id_clie: $("#id_cliente").val(), id_anamnesis: idanamnesis },
		success:function(response) {
			$("#id_eval").val(response.id_evaluacion);

			/* Guardamos orden de lab */
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
}

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
				$("#step-1").prepend('<div class="alert alert-warning"><p style="font-size:1.3em;">Última Anamnesis de este paciente fue: <b>'+strDate[2]+'/'+strDate[1]+'/'+strDate[0]+'</b>.<br/>¿Desea retomar esta anamnesis? <a href="<?php echo base_url();?>procesocliente/veranamnesis/'+respuesta.id_anamnesis+'" class="btn btn-success">Si</a>  <button type="button" class="btn btn-danger" data-dismiss="alert">No</button></p></div>');
			}
			$("#carga").remove();
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

//Logica cerca
$("#btncerca").on("click", function(e){
	var od_esf = $("input[name*='lejos_refra_od_esf']").val();
	var od_adicion = $("input[name*='lejos_refra_od_adicion']").val();

	var oi_esf = $("input[name*='lejos_refra_oi_esf']").val();
	var oi_adicion = $("input[name*='lejos_refra_oi_adicion']").val();

	var lejos_refra_od_cyl = $("input[name*='lejos_refra_od_cyl']").val();
	var lejos_refra_od_eje = $("input[name*='lejos_refra_od_eje']").val();

	var lejos_refra_oi_cyl = $("input[name*='lejos_refra_oi_cyl']").val();
	var lejos_refra_oi_eje = $("input[name*='lejos_refra_oi_eje']").val();

	if(od_adicion!='0' && od_adicion!=''){
		var sum = parseFloat(od_esf)+parseFloat(od_adicion);
		$("input[name*='cerca_refra_od_esf']").val((sum<0?"":"+")+sum);

		var sum2 = parseFloat(oi_esf)+parseFloat(oi_adicion);
		$("input[name*='cerca_refra_oi_esf']").val((sum<0?"":"+")+sum2);

		$("input[name*='cerca_refra_od_cyl']").val(lejos_refra_od_cyl);
		$("input[name*='cerca_refra_od_eje']").val(lejos_refra_od_eje);

		$("input[name*='cerca_refra_oi_cyl']").val(lejos_refra_oi_cyl);
		$("input[name*='cerca_refra_oi_eje']").val(lejos_refra_oi_eje);
	}
});

$("#btntransp").on("click", function(e){
	var lejos_refra_od_cyl = parseFloat($("input[name*='lejos_refra_od_cyl']").val());

	if(lejos_refra_od_cyl<0){
		var lejos_refra_od_esf = $("input[name*='lejos_refra_od_esf']").val();
		$("input[name*='lejos_refra_od_esf']").val(lejos_refra_od_esf);

		var lejos_refra_od_cyl = $("input[name*='lejos_refra_od_cyl']").val();
		$("input[name*='lejos_refra_od_cyl']").val(lejos_refra_od_cyl);

		var lejos_refra_od_eje = parseFloat($("input[name*='lejos_refra_od_eje']").val());
		$("input[name*='lejos_refra_od_eje']").val(lejos_refra_od_eje);

	}else{
		var lejos_refra_od_esf = $("input[name*='lejos_refra_od_esf']").val();
		var lejos_refra_od_cyl = $("input[name*='lejos_refra_od_cyl']").val();
		var sum = parseFloat(lejos_refra_od_esf) + parseFloat(lejos_refra_od_cyl);
		$("input[name*='lejos_refra_od_esf']").val((sum<0?"":"+")+sum);

		var lejos_refra_od_cyl_parsed = parseFloat(lejos_refra_od_cyl);
		var sum2 = Math.abs(lejos_refra_od_cyl_parsed) - lejos_refra_od_cyl_parsed * 2;

		$("input[name*='lejos_refra_od_cyl']").val((sum2<0?"":"+")+sum2);

		var lejos_refra_od_eje = parseFloat($("input[name*='lejos_refra_od_eje']").val());

		if(lejos_refra_od_eje<=90){
			var valor = lejos_refra_od_eje + 90;
		}else if(lejos_refra_od_eje>90){
			var valor = lejos_refra_od_eje - 90;
		}else{
			var valor = 0;
		}

		$("input[name*='lejos_refra_od_eje']").val(valor);
	}

	//OI

	var lejos_refra_oi_cyl = parseFloat($("input[name*='lejos_refra_oi_cyl']").val());

	if(lejos_refra_oi_cyl<0){
		var lejos_refra_oi_esf = $("input[name*='lejos_refra_oi_esf']").val();
		$("input[name*='cerca_refra_oi_esf']").val(lejos_refra_oi_esf);

		var lejos_refra_oi_cyl = $("input[name*='lejos_refra_oi_cyl']").val();
		$("input[name*='cerca_refra_oi_cyl']").val(lejos_refra_oi_cyl);

		var lejos_refra_oi_eje = parseFloat($("input[name*='lejos_refra_oi_eje']").val());
		$("input[name*='cerca_refra_oi_eje']").val(lejos_refra_oi_eje);

	}else{
		var lejos_refra_oi_esf = $("input[name*='lejos_refra_oi_esf']").val();
		var lejos_refra_oi_cyl = $("input[name*='lejos_refra_oi_cyl']").val();
		var sum3 = parseFloat(lejos_refra_oi_esf) + parseFloat(lejos_refra_oi_cyl);
		$("input[name*='cerca_refra_oi_esf']").val((sum3<0?"":"+")+sum3);

		var lejos_refra_oi_cyl_parsed = parseFloat(lejos_refra_oi_cyl);
		var sum4 = Math.abs(lejos_refra_oi_cyl_parsed) - lejos_refra_oi_cyl_parsed * 2;

		$("input[name*='cerca_refra_oi_cyl']").val((sum4<0?"":"+")+sum4);

		var lejos_refra_oi_eje = parseFloat($("input[name*='lejos_refra_oi_eje']").val());

		if(lejos_refra_oi_eje<=90){
			var valor2 = lejos_refra_oi_eje + 90;
		}else if(lejos_refra_oi_eje>90){
			var valor2 = lejos_refra_oi_eje - 90;
		}else{
			var valor2 = 0;
		}

		$("input[name*='cerca_refra_oi_eje']").val(valor2);
	}
});

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

$(document).on("click", "input[name='radioBtn']", function(){
    thisRadio = $(this);
    if (thisRadio.hasClass("imChecked")) {
        thisRadio.removeClass("imChecked");
        thisRadio.prop('checked', false);
    } else { 
        thisRadio.prop('checked', true);
        thisRadio.addClass("imChecked");
    };
})

var ultima_receta_json = "";

$("#verultreceta").on("click", function(){
	$.ajax({
		url: base_url('procesocliente/buscarUltReceta'),
		type: "post",
		dataType: "json",
		data: {idcliente: $("#id_cliente").val()},
		success: function(respuesta) {
			$("#fecha_ult_eval").html("Fecha de Evaluación: "+respuesta.datos_eval.fecha+"");

			$.each( respuesta.meta_eval, function( key, value ) {
				$("#ult_"+value.meta_key+"").val(value.meta_value);
			});

			ultima_receta_json = respuesta.meta_eval;

			$('#modalultreceta').modal('toggle');
		},
		error: function() {
			console.log("No se ha podido obtener la información");
		}
	});
})


$("#usar_ult_receta").on("click", function(){

	console.log(ultima_receta_json);

	$.each( ultima_receta_json, function( key, value ) {
		$("input[name='"+value.meta_key+"']").val(value.meta_value);
	});

	$('#modalultreceta').modal('toggle');
})
</script>
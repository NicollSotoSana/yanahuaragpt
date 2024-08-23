<div class="row">
	<?php

		foreach($locales as $loc){
			echo '<div class="col-md-4"><form method="post" action="'.base_url().'objetivos/guardarObjetivos">';

			echo '<table class="table table-bordered">
				<input name="id_empresa" type="hidden" value="'.$loc->id.'">
				<tr>
					<td colspan="2" style="text-align:center;"><h4 style="font-weight: bold;">'.$loc->Nombre.'</h4></td>
				</tr>
				<tr>
					<th style="text-align:center; font-weight: bold; width: 70%;">Objetivo</th>
					<th width="20" style="text-align:center; font-weight: bold; width: 30%;">Cantidad</th>
				</tr>';

			echo '<tbody>
					<tr>
						<td>
							<select id="mes" name="mes" class="form-control" />
								<option hidden>Seleccione Mes</option> 
								<option value="01">Enero</option> 
								<option value="02">Febrero</option> 
								<option value="03">Marzo</option> 
								<option value="04">Abril</option> 
								<option value="05">Mayo</option> 
								<option value="06">Junio</option> 
								<option value="07">Julio</option> 
								<option value="08">Agosto</option> 
								<option value="09">Septiembre</option> 
								<option value="10">Octubre</option> 
								<option value="11">Noviembre</option> 
								<option value="12">Diciembre</option> 
							</select>
						</td>
						<td>';
						echo '<select name="anio" id="anio" class="form-control"><option hidden>Seleccione Año</option>';
						for($i=2019; $i<=date('Y')+3;$i++){
							echo '<option value="'.$i.'">'.$i.'</option>';
						}
						echo '</select>';
					echo '	</td>
					</tr>
					<tr>
						<td>Contratos Plan Familiar</td>
						<td><input type="text" name="plan_fam" id="plan_fam" class="form-control"></td>
					</tr>
					<tr>
						<td>Lentes Digitales Monofocales</td>
						<td><input type="text" name="lentes_digi" id="lentes_digi" class="form-control"></td>
					</tr>
					<tr>
						<td>Marcas descontinuadas</td>
						<td><input type="text" name="marcas_desc" id="marcas_desc" class="form-control"></td>
					</tr>
					<tr>
						<td>Multifocal Top</td>
						<td><input type="text" name="multifocal_top" id="multifocal_top" class="form-control"></td>
					</tr>
					<tr>
						<td>Líquidos</td>
						<td><input type="text" name="liquidos" id="liquidos" class="form-control"></td>
					</tr>
					<tr>
						<td>Lentes Solares</td>
						<td><input type="text" name="lentes_solares" id="lentes_solares" class="form-control"></td>
					</tr>
					<tr>
						<td>Peeps</td>
						<td><input type="text" name="peeps" id="peeps" class="form-control"></td>
					</tr>
					<tr>
						<td colspan="2" style="text-align:right;"><button class="btn btn-success"><i class="icon icon-save"></i> Guardar</button></form></td>
					</tr>
				</tbody>';

			echo '</table></div>';
		}
	?>
</div>
<script>
$("#anio").on("change", function(){
	var anio = $("#anio").val();
	var mes = $("#mes").val();
	$.ajax({
		url: '<?php echo base_url();?>objetivos/getObjetivosVi/'+mes+'/'+anio,
		dataType: 'json',
		success: function(respuesta) {
			if(respuesta.anio){
				$("#plan_fam").val(respuesta.plan_fam);
				$("#lentes_digi").val(respuesta.lentes_digi);
				$("#lentes_solares").val(respuesta.lentes_solares);
				$("#liquidos").val(respuesta.liquidos);
				$("#marcas_desc").val(respuesta.marcas_desc);
				$("#multifocal_top").val(respuesta.multifocal_top);
				$("#peeps").val(respuesta.peeps);
			}
		},
		error: function() {
			console.log("No se ha podido obtener la información");
		}
	});
});
</script>
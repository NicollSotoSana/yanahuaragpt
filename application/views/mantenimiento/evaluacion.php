<script>
$(document).ready(function(){
	BuscarDocs();
})
function BuscarDocs(){
	var input = $("#receta");

    input.autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: base_url('services/recetas'),
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            value: item.meta_value,
                        }
                    }))
                }
            })
        },
        search  : function(){$(this).addClass('ui-autocomplete-loading');},
        open    : function(){$(this).removeClass('ui-autocomplete-loading');},
        select: function (e, ui) {
        	$("#receta").val(ui.item.meta_value);
        }
    })
}


</script>
<div class="row">
	<?php //var_dump($eval);?>
	<div class="col-md-12">
		<div class="page-header">
			<h3><?php echo $eval == null ? "Nueva Evaluación de <b>".$cliente->Nombre."</b>" : "Evaluación <b>#".str_pad($ideval, 4, '0', STR_PAD_LEFT)."</b>"; ?></h3>
			<?php
				if($eval != null){
					//echo '<div class="pull-right"><a href="'.base_url("mantenimiento/receta_pdf/".$ideval).'" target="_blank" class="btn btn-success"><b>Descargar Receta</b></a><a href="'.base_url("mantenimiento/ordenLaboratorio/".$ideval).'" class="btn btn-info"><b>Generar Orden Lab.</b></a></div>';
				}
			?>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li><a href="<?php echo base_url('index.php/mantenimiento/clientes'); ?>">Clientes</a></li>
		  <li class="active"><?php echo $cliente == null ? "Nuevo Item" : $cliente->Nombre; ?></li>
		</ol>
		<form id="formeva" method="post" action="<?php echo base_url();?>mantenimiento/guardarEvaluacion">
			<input type="hidden" name="id_clie" class="form-control" value="<?php echo $idclie;?>">
			<input type="hidden" name="nombre_cliente" class="form-control" value="<?php echo $cliente->Nombre;?>">
		
		<hr class="separator"><center style="font-size:1.5em;font-weight: bold;">Evaluación</center></hr>
		<hr>
		<div class="row">
			<div class="col-md-8">
			
				<table class="table table-bordered table-hover">
					<thead>
						<tr style="text-align: center;">
							<th colspan="9" style="font-size: 2em;color:#000;">Lejos</th>
						</tr>
						<tr>
							<th> </th>
							<th>ESF</th>
							<th>CIL</th>
							<th>EJE</th>
							<th>ADICIÓN</th>
							<th>DIP</th>
							<th>ALT</th>
							<th>PRISMAS</th>
						</tr>
					</thead>
					<tbody>

						<tr>
							<td><strong>O.D.</strong></td>
							<td><input type="text" name="lejos_refra_od_esf" class="form-control" value="<?php echo isset($eval["lejos_refra_od_esf"]) ? $eval["lejos_refra_od_esf"] : ''; ?>"></td>
							<td><input type="text" name="lejos_refra_od_cyl" class="form-control" value="<?php echo isset($eval["lejos_refra_od_cyl"]) ? $eval["lejos_refra_od_cyl"] : ''; ?>"></td>
							<td><input type="text" name="lejos_refra_od_eje" class="form-control" value="<?php echo isset($eval["lejos_refra_od_eje"]) ? $eval["lejos_refra_od_eje"] : ''; ?>"></td>
							<td><input type="text" name="lejos_refra_od_adicion" class="form-control" value="<?php echo isset($eval["lejos_refra_od_adicion"]) ? $eval["lejos_refra_od_adicion"] : ''; ?>"></td>
							<td><input type="text" name="lejos_refra_od_dnp" class="form-control" value="<?php echo isset($eval["lejos_refra_od_dnp"]) ? $eval["lejos_refra_od_dnp"] : ''; ?>"></td>
							<td><input type="text" name="lejos_refra_od_alt" class="form-control" value="<?php echo isset($eval["lejos_refra_od_alt"]) ? $eval["lejos_refra_od_alt"] : ''; ?>"></td>
							<td><input type="text" name="lejos_refra_od_prismas" class="form-control" value="<?php echo isset($eval["lejos_refra_od_prismas"]) ? $eval["lejos_refra_od_prismas"] : ''; ?>"></td>
						</tr>
						<tr>
							<td><strong>O.I.</strong></td>
							<td><input type="text" name="lejos_refra_oi_esf" class="form-control" value="<?php echo isset($eval["lejos_refra_oi_esf"]) ? $eval["lejos_refra_oi_esf"] : ''; ?>"></td>
							<td><input type="text" name="lejos_refra_oi_cyl" class="form-control" value="<?php echo isset($eval["lejos_refra_oi_cyl"]) ? $eval["lejos_refra_oi_cyl"] : ''; ?>"></td>
							<td><input type="text" name="lejos_refra_oi_eje" class="form-control" value="<?php echo isset($eval["lejos_refra_oi_eje"]) ? $eval["lejos_refra_oi_eje"] : ''; ?>"></td>
							<td><input type="text" name="lejos_refra_oi_adicion" class="form-control" value="<?php echo isset($eval["lejos_refra_oi_adicion"]) ? $eval["lejos_refra_oi_adicion"] : ''; ?>"></td>
							<td><input type="text" name="lejos_refra_oi_dnp" class="form-control" value="<?php echo isset($eval["lejos_refra_oi_dnp"]) ? $eval["lejos_refra_oi_dnp"] : ''; ?>"></td>
							<td><input type="text" name="lejos_refra_oi_alt" class="form-control" value="<?php echo isset($eval["lejos_refra_oi_alt"]) ? $eval["lejos_refra_oi_alt"] : ''; ?>"></td>
							<td><input type="text" name="lejos_refra_oi_prismas" class="form-control" value="<?php echo isset($eval["lejos_refra_oi_prismas"]) ? $eval["lejos_refra_oi_prismas"] : ''; ?>"></td>
						</tr>
						<tr style="text-align: center;">
							<th colspan="8" style="font-size: 2em;color:#000;">Cerca</th>
						</tr>

						<tr>
							<td><strong>O.D.</strong></td>
							<td><input type="text" name="cerca_refra_od_esf" class="form-control" value="<?php echo isset($eval["cerca_refra_od_esf"]) ? $eval["cerca_refra_od_esf"] : ''; ?>"></td>
							<td><input type="text" name="cerca_refra_od_cyl" class="form-control" value="<?php echo isset($eval["cerca_refra_od_cyl"]) ? $eval["cerca_refra_od_cyl"] : ''; ?>"></td>
							<td><input type="text" name="cerca_refra_od_eje" class="form-control" value="<?php echo isset($eval["cerca_refra_od_eje"]) ? $eval["cerca_refra_od_eje"] : ''; ?>"></td>
							<td><input type="text" name="cerca_refra_od_adicion" class="form-control" value="<?php echo isset($eval["cerca_refra_od_adicion"]) ? $eval["cerca_refra_od_adicion"] : ''; ?>"></td>
							<td><input type="text" name="cerca_refra_od_dnp" class="form-control" value="<?php echo isset($eval["cerca_refra_od_dnp"]) ? $eval["cerca_refra_od_dnp"] : ''; ?>"></td>
							<td><input type="text" name="cerca_refra_od_alt" class="form-control" value="<?php echo isset($eval["cerca_refra_od_alt"]) ? $eval["cerca_refra_od_alt"] : ''; ?>"></td>
							<td><input type="text" name="cerca_refra_od_prismas" class="form-control" value="<?php echo isset($eval["cerca_refra_od_prismas"]) ? $eval["cerca_refra_od_prismas"] : ''; ?>"></td>
						</tr>
						<tr>
							<td><strong>O.I.</strong></td>
							<td><input type="text" name="cerca_refra_oi_esf" class="form-control" value="<?php echo isset($eval["cerca_refra_oi_esf"]) ? $eval["cerca_refra_oi_esf"] : ''; ?>"></td>
							<td><input type="text" name="cerca_refra_oi_cyl" class="form-control" value="<?php echo isset($eval["cerca_refra_oi_cyl"]) ? $eval["cerca_refra_oi_cyl"] : ''; ?>"></td>
							<td><input type="text" name="cerca_refra_oi_eje" class="form-control" value="<?php echo isset($eval["cerca_refra_oi_eje"]) ? $eval["cerca_refra_oi_eje"] : ''; ?>"></td>
							<td><input type="text" name="cerca_refra_oi_adicion" class="form-control" value="<?php echo isset($eval["cerca_refra_oi_adicion"]) ? $eval["cerca_refra_oi_adicion"] : ''; ?>"></td>
							<td><input type="text" name="cerca_refra_oi_dnp" class="form-control" value="<?php echo isset($eval["cerca_refra_oi_dnp"]) ? $eval["cerca_refra_oi_dnp"] : ''; ?>"></td>
							<td><input type="text" name="cerca_refra_oi_alt" class="form-control" value="<?php echo isset($eval["cerca_refra_oi_alt"]) ? $eval["cerca_refra_oi_alt"] : ''; ?>"></td>
							<td><input type="text" name="cerca_refra_oi_prismas" class="form-control" value="<?php echo isset($eval["cerca_refra_oi_prismas"]) ? $eval["cerca_refra_oi_prismas"] : ''; ?>"></td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label>Ángulo Panorámico</label>
					<input type="text" name="angulo_panoramico" class="form-control" value="<?php echo isset($eval["angulo_panoramico"]) ? $eval["angulo_panoramico"] : ''; ?>">
				</div>
				<div class="form-group">
					<label>Ángulo Pantoscópico</label>
					<input type="text" name="angulo_pantoscopico" class="form-control" value="<?php echo isset($eval["angulo_pantoscopico"]) ? $eval["angulo_pantoscopico"] : ''; ?>">
				</div>
				<div class="form-group">
					<label>Distancia Vértice</label>
					<input type="text" name="distancia_vertice" class="form-control" value="<?php echo isset($eval["distancia_vertice"]) ? $eval["distancia_vertice"] : ''; ?>">
				</div>
			</div>
		</div>

		<div class="row">
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
								<td><input type="text" name="lens_od_esf" class="form-control" value="<?php echo isset($eval["lens_od_esf"]) ? $eval["lens_od_esf"] : ''; ?>"></td>
								<td><input type="text" name="lens_od_cil" class="form-control" value="<?php echo isset($eval["lens_od_cil"]) ? $eval["lens_od_cil"] : ''; ?>"></td>
								<td><input type="text" name="lens_od_eje" class="form-control" value="<?php echo isset($eval["lens_od_eje"]) ? $eval["lens_od_eje"] : ''; ?>"></td>
							</tr>
							<tr>
								<td>O.I.</td>
								<td><input type="text" name="lens_oi_esf" class="form-control" value="<?php echo isset($eval["lens_oi_esf"]) ? $eval["lens_oi_esf"] : ''; ?>"></td>
								<td><input type="text" name="lens_oi_cil" class="form-control" value="<?php echo isset($eval["lens_oi_cil"]) ? $eval["lens_oi_cil"] : ''; ?>"></td>
								<td><input type="text" name="lens_oi_eje" class="form-control" value="<?php echo isset($eval["lens_oi_eje"]) ? $eval["lens_oi_eje"] : ''; ?>"></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		
		<?php
			if($eval == null){
				echo '<center><button type="submit" class="btn btn-lg btn-success"><b><i class="icon-save"></i> Guardar</b></button></center>';
			}else{
				echo '<script>$("#formeva :input").attr("disabled", true);</script>';
			}
		?>
		

		
	
	</div>
</div>
	</form>
<script>
	$(document).ready(function() {
	  $(window).keydown(function(event){
	    if(event.keyCode == 13) {
	      event.preventDefault();
	      return false;
	    }
	  });
	});
	$("#formeva").submit(function(event){
    	event.preventDefault(); //prevent default action 
    	var post_url = $(this).attr("action"); //get form action url
    	var request_method = $(this).attr("method"); //get form GET/POST method
    	var form_data = $(this).serialize(); //Encode form elements for submission
    
	    $.ajax({
	        url : post_url,
	        type: request_method,
	        data : form_data,
	        dataType: 'JSON',
	    }).done(function(response){ //
	        if (response.href != undefined) {
                    if (response.href == 'self') window.location.reload(true);
                    else window.location.href = base_url(response.href);
             }
             //alert(JSON.stringify(response));

	    });
	});

</script>
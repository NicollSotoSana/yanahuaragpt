<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h2>Asistencias</h2>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li><a href="<?php echo base_url('index.php/asistencias'); ?>">Asistencias</a></li>
		</ol>
		<div class="row">
			<?php //var_dump($tipos);?>
			<div class="col-md-12">
				
				<div class="well well-sm text-center">
					<form action="<?php echo base_url("asistencia/saveAsistencia");?>" method="post" id="frmasis" >
					<div class="btn-group" data-toggle="buttons">
						<h4>Seleccione Tipo:</h4>
					<?php
						foreach($tipos as $t){
							echo '<label class="btn btn-primary" style="margin-right:10px;">
								<input type="radio" name="tipo" id="tipo" autocomplete="off" value="'.$t->id_tipo.'">
								<span class="glyphicon glyphicon-ok"></span> '.$t->tipo.'
							</label>';
						}
					?>
					
					<br/><br/><br/>
						<h4>DNI:</h4>
						<input type="text" name="dni" class="form-control" required>
					<br/><br/>
					</div>
						<div id="res"><button type="submit" class="btn btn-success"><b><i class="icon icon-save"></i> Registrar</b></button></div>
					</form>
					
				</div>
				
			</div>
		</div>
	</div>
</div>

<script>
	$("#frmasis").submit(function(event){
		//alert("ad");
    	event.preventDefault(); //prevent default action 
    	var post_url = $(this).attr("action"); //get form action url
    	var request_method = $(this).attr("method"); //get form GET/POST method
    	var form_data = $(this).serialize();
    	$("#res").html("Cargando...");
	    $.ajax({
	        url : post_url,
	        type: request_method,
	        data : form_data,
	        dataType: 'JSON',
	    }).done(function(response){ //
	    	$("#res").html('<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><span style="font-size:1.3em;"><strong>Listo!</strong> Asistencia registrada con éxito.</span></div><button type="submit" class="btn btn-success"><b><i class="icon icon-save"></i> Registrar</b></button>');
	    });
	});
</script>
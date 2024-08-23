<div class="row mydivs">
<form method="post" action="<?php echo base_url("encuesta/saveAnswers");?>">
	<input type="hidden" name="id_cli" value="<?php echo $cliente;?>">
	<input type="hidden" name="id_anam" value="<?php echo $anamnesis;?>">
	<input type="hidden" name="step" value="<?php echo $step;?>">
    <?php
	    foreach($preguntas as $p){
	    	echo '<div class="col-md-12"><div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <span class="glyphicon glyphicon-hand-right"></span> <b>'.$p["nombre_cat"].'</b></h3>
                </div>
                <div class="panel-body">
                    <table class="table">';
            foreach($p["preguntas"] as $pre){
            	echo 	'<tr>
	            		<td><span style="font-size:1.2em;">'.$pre["pregunta"].'</span></td>
	            		<td>
	            			<div class="funkyradio">
						        <div class="funkyradio-success">
						            <input type="radio" name="pregunta_'.$pre["id_pregunta"].'" id="si'.$pre["id_pregunta"].'" value="1"/>
						            <label for="si'.$pre["id_pregunta"].'" style="margin-top:0;">Si</label>
						        </div>
					        </div>
					    </td>
	            		<td>
	            			<div class="funkyradio">
						        <div class="funkyradio-danger">
						            <input type="radio" name="pregunta_'.$pre["id_pregunta"].'" id="no'.$pre["id_pregunta"].'" value="0"/>
						            <label for="no'.$pre["id_pregunta"].'" style="margin-top:0;">No</label>
						        </div>
					        </div>
					</td>';
				if($pre["id_pregunta"] == 7 || $pre["id_pregunta"] == 11){
					echo '<td>
						<div class="funkyradio">
							<div class="funkyradio-primary">
								<input type="radio" name="pregunta_'.$pre["id_pregunta"].'" id="na'.$pre["id_pregunta"].'" value="2"/>
								<label for="na'.$pre["id_pregunta"].'" style="margin-top:0;">N/A</label>
							</div>
						</div>
					</td>';
				}
				echo '</tr>';
            }

            echo ' </table>
                </div>
		               
				</div>
			</div>';
	    }
	?>
	<?php if($step==2){?>
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title"><span class="glyphicon glyphicon-hand-right"></span> <b>Satisfacción General</b></h3>
				</div>
				<div class="panel-body">
					<table class="table">
						<tbody>
							<tr>
								<td><span style="font-size:1.2em;">¿Cómo calificaría la atención recibida?</span></td>
								<td>
									<div class="funkyradio">
										<div class="funkyradio-success">
											<input type="radio" name="pregunta_sat" id="sat1" value="1">
											<label for="sat1" style="margin-top:0; text-indent:1.80em!important;"><i class="fa fa-sad-tear" style="font-size:1.5em;"></i> Muy Mala</label>
										</div>
									</div>
								</td>
								<td>
									<div class="funkyradio">
										<div class="funkyradio-success">
											<input type="radio" name="pregunta_sat" id="sat2" value="2">
											<label for="sat2" style="margin-top:0; text-indent:1.80em!important;"><i class="fa fa-frown" style="font-size:1.5em;"></i> Mala</label>
										</div>
									</div>
								</td>
								<td>
									<div class="funkyradio">
										<div class="funkyradio-success">
											<input type="radio" name="pregunta_sat" id="sat3" value="3">
											<label for="sat3" style="margin-top:0; text-indent:1.80em!important;"><i class="fa fa-frown-open" style="font-size:1.5em;"></i> Regular</label>
										</div>
									</div>
								</td>
								<td>
									<div class="funkyradio">
										<div class="funkyradio-success">
											<input type="radio" name="pregunta_sat" id="sat4" value="4">
											<label for="sat4" style="margin-top:0; text-indent:1.80em!important;"><i class="fa fa-smile" style="font-size:1.5em;"></i> Buena</label>
										</div>
									</div>
								</td>
								<td>
									<div class="funkyradio">
										<div class="funkyradio-success">
											<input type="radio" name="pregunta_sat" id="sat5" value="5">
											<label for="sat5" style="margin-top:0; text-indent:1.80em!important;"><i class="fa fa-laugh-beam" style="font-size:1.5em;"></i> Muy Buena</label>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="6">
									<div class="form-group">
										<label for="">Sugerencia</label>
										<textarea name="sugerencia" class="form-control" id="sugerencia"></textarea>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php }?>
	<center><button class="btn btn-success btn-lg" id="saveanswers"><i class="icon icon-check"></i> Finalizar</button></center>
	</form>
</div>
<!--<div class="panel-footer text-center">
		                    <button class="btn btn-warning anterior" id="ante" name="next" style="margin-right:100px;"><i class="icon icon-arrow-left"></i> <b>Anterior</b></button>
							<button class="btn btn-success siguiente" id="sig" name="next"><b>Siguiente</b> <i class="icon icon-arrow-right"></i></button>
							
		             	</div>-->


<script>
	$(document).ready(function() {
		/*//$("#ante").hide();
	    var divs = $('.mydivs>div');
	    var now = 0; // currently shown div
	    divs.hide().first().show(); // hide all divs except first
	    $(".siguiente").click(function() {
			console.log(now);
			if(now==0){ $("#ante").hide();}
			if(now==1){ $("#sig").hide();}else if(now==0){ $("#sig").show();}
	        divs.eq(now).hide();
	        now = (now + 1 < divs.length) ? now + 1 : 0;
	        divs.eq(now).fadeIn(); // show next
	    });
	    $(".anterior").click(function() {
			console.log(now);
			if(now==0){ $("#ante").hide();}
			if(now==1){ $("#sig").hide();}else if(now==0){ $("#sig").show();}
	        divs.eq(now).hide();
	        now = (now > 0) ? now - 1 : divs.length - 1;
	        divs.eq(now).fadeIn(); // show previous
	    });*/
	});

	$(document).on('click', '#saveanswers', function(e) {
		var checked = $(".mydivs :radio:checked");
		var groups = [];
		$(".mydivs :radio").each(function() {
			if (groups.indexOf(this.name) < 0) {
				groups.push(this.name);
			}
		});
		if (groups.length != checked.length) {
			var total = groups.length - checked.length;
			alert('Debe responder todas las preguntas!');
			e.preventDefault();
		}
	});
</script>
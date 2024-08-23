<div class="row">
    <div class="col-md-12">
        <h1>Respeto de Protocolo</h1>
    </div>
    <div class="col-md-4">
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Seleccione Año</label>
            <?php
                echo '<select name="anio" id="anio" class="form-control"><option hidden>Seleccione Año</option>';
                for($i=2019; $i<=date('Y')+3;$i++){
                    echo '<option value="'.$i.'">'.$i.'</option>';
                }
                echo '</select>';
            ?>
        </div>
    </div>
    <div class="col-md-4">
        <br/>
        <button type="button" class="btn btn-success" id="btnver"><i class="icon icon-search"></i> Ver</button>
    </div>
    
</div>
<div id="resulthtml" style="background:#fff;">

</div>
<script>
$("#btnver").on("click", function(){
	var anio = $("#anio").val();
    $("#resulthtml").html("");
	$.ajax({
		url: '<?php echo base_url();?>objetivos/respetoProtocolo/'+anio,
		dataType: 'html',
		success: function(respuesta) {
			$("#resulthtml").html(respuesta);
            
		},
		error: function() {
			console.log("No se ha podido obtener la información");
		}
	});
});
</script>
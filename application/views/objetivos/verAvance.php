<div class="row">
    <div class="col-md-12">
        <h1>Cuadro de Indicadores</h1>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Seleccione Mes</label>
            <select id="mes" name="mes" class="form-control">
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
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Seleccione Mes</label>
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
<div id="resulthtml">

</div>
<script>
$("#btnver").on("click", function(){
	var anio = $("#anio").val();
	var mes = $("#mes").val();
    $("#resulthtml").html("");
	$.ajax({
		url: '<?php echo base_url();?>objetivos/verIndicadores/'+mes+'/'+anio,
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
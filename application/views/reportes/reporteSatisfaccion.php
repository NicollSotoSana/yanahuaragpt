<div class="row">
    <div class="col-md-12"><h2>Porcentajes de Satisfacción Por Empresa</h2></div>
        <div class="col-md-3">
			<div class="form-group">
				<label>Empresa:</label>
				<select name="empresa" id="empresa" class="form-control">
					<option value="0">- TODAS -</option>
                    <?php
                        foreach($empresas_convenios as $emp){
                            echo '<option value="'.$emp->id_emp_conv.'">'.$emp->empresa.'</option>';
                        }
                    ?>
                </select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label>Fecha Inicio:</label>
				<input type="date" name="e_fecha_inicio" id="e_fecha_inicio" class="form-control" value="<?php echo date("Y-m-d");?>">
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label>Fecha Fin:</label>
				<input type="date" name="e_fecha_fin" id="e_fecha_fin" class="form-control" value="<?php echo date("Y-m-d");?>">
			</div>
		</div>
		<div class="col-md-3">
			<br/>
			<button class="btn btn-success" type="button" onclick="btnEmpresa()"><i class="icon icon-filter"></i> Filtrar</button>
		</div>
        <hr>
</div>

<div class="row">
    <div class="col-md-12"><h2>Porcentajes de Satisfacción Por Doctor</h2></div>
        <div class="col-md-3">
			<div class="form-group">
				<label>Doctor:</label>
				<select name="doctor" id="doctor" class="form-control">
					<option value="0">- TODOS -</option>
                    <?php
                        foreach($doctores as $doc){
                            echo '<option value="'.$doc->id_doctor.'">'.$doc->doctor.'</option>';
                        }
                    ?>
                </select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label>Fecha Inicio:</label>
				<input type="date" name="d_fecha_inicio" id="d_fecha_inicio" class="form-control" value="<?php echo date("Y-m-d");?>">
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label>Fecha Fin:</label>
				<input type="date" name="d_fecha_fin" id="d_fecha_fin" class="form-control" value="<?php echo date("Y-m-d");?>">
			</div>
		</div>
		<div class="col-md-3">
			<br/>
			<button class="btn btn-success" type="button" onclick="btnDoctor()"><i class="icon icon-filter"></i> Filtrar</button>
		</div>
        <hr>
</div>

<div class="row">
    <div class="col-md-12"><h2>Porcentajes de Satisfacción Por Clínica</h2></div>
        <div class="col-md-3">
			<div class="form-group">
				<label>Clínicas:</label>
				<select name="clinica" id="clinica" class="form-control">
					<option value="0">- TODAS -</option>
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
				<label>Fecha Inicio:</label>
				<input type="date" name="c_fecha_inicio" id="c_fecha_inicio" class="form-control" value="<?php echo date("Y-m-d");?>">
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label>Fecha Fin:</label>
				<input type="date" name="c_fecha_fin" id="c_fecha_fin" class="form-control" value="<?php echo date("Y-m-d");?>">
			</div>
		</div>
		<div class="col-md-3">
			<br/>
			<button class="btn btn-success" type="button" onclick="btnClinica()"><i class="icon icon-filter"></i> Filtrar</button>
		</div>
        <hr>
</div>

<script>
	function btnEmpresa(){
		var inicio = $("#e_fecha_inicio").val();
		var fin = $("#e_fecha_fin").val();
		var empresa = $("#empresa").val();

		window.open('<?php echo base_url();?>reportes/porcentajeSatisfaccion/3/'+empresa+'/'+inicio+'/'+fin, '_blank');
	}

	function btnDoctor(){
		var inicio = $("#d_fecha_inicio").val();
		var fin = $("#d_fecha_fin").val();
		var doctor = $("#doctor").val();

		window.open('<?php echo base_url();?>reportes/porcentajeSatisfaccion/2/'+doctor+'/'+inicio+'/'+fin, '_blank');
	}

	function btnClinica(){
		var inicio = $("#c_fecha_inicio").val();
		var fin = $("#c_fecha_fin").val();
		var clinica = $("#clinica").val();

		window.open('<?php echo base_url();?>reportes/porcentajeSatisfaccion/1/'+clinica+'/'+inicio+'/'+fin, '_blank');
	}
</script>
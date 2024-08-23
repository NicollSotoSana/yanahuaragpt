<!DOCTYPE html>
<html>
<head>
	<title>Receta</title>
	<meta charset="utf-8">
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>-->
	<style type="text/css">
		table {
		    border-collapse: collapse;
		}

		table, th, td {
		    border: 1px solid black;
		}
		.noborder, .noborder tr, .noborder th, .noborder td { border: none; }
	</style>
</head>
<body>
	<?php //var_dump($eval);?>
	<?php //var_dump($cli);?>
	<img src="http://guillentamayo.com/wp-content/themes/guillen_tamayo/images/logo-oftalmologia.png" style="width:200px;"><br/>
	<table border="0" class="noborder" style="border:none;">
		<tr>
			<td width="250"><strong>Paciente:</strong> <?php echo $cli->Nombre?></td>
			<td><strong>Edad:</strong> <?php echo date_diff(date_create($cli->fecha_nac), date_create('now'))->y;?></td>
		</tr>
		<tr>
			<td width="250"><strong>DNI/RUC:</strong> <?php echo !empty($cli->Ruc)? $cli->Ruc:$cli->Dni?></td>
			<td><strong>Fecha:</strong> <?php echo date("d/m/Y", strtotime($fecha));?></td>
		</tr>
		<tr>
			<td width="250"><strong>Celular:</strong> <?php echo $cli->Telefono1?></td>
		</tr>
	</table>
	<table width=100% style="border:none;" border="0"> 
	  <tr style="border:none;" > 
	    <td valign="top" style="border:none;" >
	    	<label><strong>Lensometría</strong></label>
				<table>
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
							<td><?php echo isset($eval["lens_od_esf"]) ? $eval["lens_od_esf"] : ''; ?></td>
							<td><?php echo isset($eval["lens_od_cil"]) ? $eval["lens_od_cil"] : ''; ?></td>
							<td><?php echo isset($eval["lens_od_eje"]) ? $eval["lens_od_eje"] : ''; ?></td>
						</tr>
						<tr>
							<td>O.I.</td>
							<td><?php echo isset($eval["lens_oi_esf"]) ? $eval["lens_oi_esf"] : ''; ?></td>
							<td><?php echo isset($eval["lens_oi_cil"]) ? $eval["lens_oi_cil"] : ''; ?></td>
							<td><?php echo isset($eval["lens_oi_eje"]) ? $eval["lens_oi_eje"] : ''; ?></td>
						</tr>
					</tbody>
				</table>
	    </td>
	  </tr>
	</table>
	
	<label><strong>Refracción</strong></label>
	<table class="table table-bordered table-hover">
		<thead>
			<tr style="text-align: center;">
				<th colspan="9" style="font-size: 1em;color:#000;">Lejos</th>
			</tr>
			<tr>
				<th> </th>
				<th>ESF</th>
				<th>CYL</th>
				<th>EJE</th>
				<th>ADICIÓN</th>
				<th>DNP</th>
				<th>ALT</th>
				<th>PRISMAS</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><strong>O.D.</strong></td>
				<td><?php echo isset($eval["lejos_refra_od_esf"]) ? $eval["lejos_refra_od_esf"] : ''; ?></td>
				<td><?php echo isset($eval["lejos_refra_od_cyl"]) ? $eval["lejos_refra_od_cyl"] : ''; ?></td>
				<td><?php echo isset($eval["lejos_refra_od_eje"]) ? $eval["lejos_refra_od_eje"] : ''; ?></td>
				<td><?php echo isset($eval["lejos_refra_od_adicion"]) ? $eval["lejos_refra_od_adicion"] : ''; ?></td>
				<td><?php echo isset($eval["lejos_refra_od_dnp"]) ? $eval["lejos_refra_od_dnp"] : ''; ?></td>
				<td><?php echo isset($eval["lejos_refra_od_alt"]) ? $eval["lejos_refra_od_alt"] : ''; ?></td>
				<td><?php echo isset($eval["lejos_refra_od_prismas"]) ? $eval["lejos_refra_od_prismas"] : ''; ?></td>
			</tr>
			<tr>
				<td><strong>O.I.</strong></td>
				<td><?php echo isset($eval["lejos_refra_oi_esf"]) ? $eval["lejos_refra_oi_esf"] : ''; ?></td>
				<td><?php echo isset($eval["lejos_refra_oi_cyl"]) ? $eval["lejos_refra_oi_cyl"] : ''; ?></td>
				<td><?php echo isset($eval["lejos_refra_oi_eje"]) ? $eval["lejos_refra_oi_eje"] : ''; ?></td>
				<td><?php echo isset($eval["lejos_refra_oi_adicion"]) ? $eval["lejos_refra_oi_adicion"] : ''; ?></td>
				<td><?php echo isset($eval["lejos_refra_oi_dnp"]) ? $eval["lejos_refra_oi_dnp"] : ''; ?></td>
				<td><?php echo isset($eval["lejos_refra_oi_alt"]) ? $eval["lejos_refra_oi_alt"] : ''; ?></td>
				<td><?php echo isset($eval["lejos_refra_oi_prismas"]) ? $eval["lejos_refra_oi_prismas"] : ''; ?></td>
			</tr>
			<tr style="text-align: center;">
				<th colspan="9" style="font-size: 1em;color:#000;">Cerca</th>
			</tr>
			<tr>
				<td><strong>O.D.</strong></td>
				<td><?php echo isset($eval["cerca_refra_od_esf"]) ? $eval["cerca_refra_od_esf"] : ''; ?></td>
				<td><?php echo isset($eval["cerca_refra_od_cyl"]) ? $eval["cerca_refra_od_cyl"] : ''; ?></td>
				<td><?php echo isset($eval["cerca_refra_od_eje"]) ? $eval["cerca_refra_od_eje"] : ''; ?></td>
				<td><?php echo isset($eval["cerca_refra_od_adicion"]) ? $eval["cerca_refra_od_adicion"] : ''; ?></td>
				<td><?php echo isset($eval["cerca_refra_od_dnp"]) ? $eval["cerca_refra_od_dnp"] : ''; ?></td>
				<td><?php echo isset($eval["cerca_refra_od_alt"]) ? $eval["cerca_refra_od_alt"] : ''; ?></td>
				<td><?php echo isset($eval["cerca_refra_od_prismas"]) ? $eval["cerca_refra_od_prismas"] : ''; ?></td>
			</tr>
			<tr>
				<td><strong>O.I.</strong></td>
				<td><?php echo isset($eval["cerca_refra_oi_esf"]) ? $eval["cerca_refra_oi_esf"] : ''; ?></td>
				<td><?php echo isset($eval["cerca_refra_oi_cyl"]) ? $eval["cerca_refra_oi_cyl"] : ''; ?></td>
				<td><?php echo isset($eval["cerca_refra_oi_eje"]) ? $eval["cerca_refra_oi_eje"] : ''; ?></td>
				<td><?php echo isset($eval["cerca_refra_oi_adicion"]) ? $eval["cerca_refra_oi_adicion"] : ''; ?></td>
				<td><?php echo isset($eval["cerca_refra_oi_dnp"]) ? $eval["cerca_refra_oi_dnp"] : ''; ?></td>
				<td><?php echo isset($eval["cerca_refra_oi_alt"]) ? $eval["cerca_refra_oi_alt"] : ''; ?></td>
				<td><?php echo isset($eval["cerca_refra_oi_prismas"]) ? $eval["cerca_refra_oi_prismas"] : ''; ?></td>
			</tr>
		</tbody>
	</table>
	<div style="float:right;">
		<br/><br/><br/><br/>
		<p style="text-align:center;"><span>.................................</span><br/>
		<span>Especialista</span></p>
	</div>
	<hr>

</body>
</html>
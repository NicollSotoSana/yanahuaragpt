<script>
$(document).ready(function(){
	var colsNames = ['id','Nombre','Identidad','Correo','T. Principal', 'Deuda', 'Cuenta'];
	var colsModel = [ 
		{name:'id',index:'id', width:55, hidden: true},
		{name:'Nombre', index:'Nombre', sopt: 'like', formatter: function(cellvalue, options, rowObject){
				return jqGridCreateLink('mantenimiento/Cliente/' + rowObject.idcli, cellvalue);
			}},
		{name:'Identidad',index:'Identidad', width:35, sopt: 'like', search: true},
		{name:'Correo', index:'Correo', width: 40, search: false, formatter: function(cellvalue, options, rowObject){
			return '<a href="mailto:' + cellvalue + '">' + cellvalue + '</a>';
		}},
		{name:'Telefono1', index:'Telefono1', width: 35, search: false},
		{name:'deuda',index:'deuda', width:60,
			stype: 'select',
	        searchoptions: {
	            value: "-1:Ninguno;1:Con Deuda",
	            defaultValue: '-1'
        },
		formatter: function(cellvalue, options, rowObject){
			if(rowObject.deuda>0){
				return '<span style="color:#CC0000;font-weight:bold;">Con Deuda (S/. '+rowObject.deuda+')</span>';
			}else if(rowObject.deuda==0.00){
				return '<span style="color:#006E2E;font-weight:bold;">Sin Deuda</span>';
			}
		}},
		{name:'idcli',index:'idcli', search: false, width:60,
			formatter: function(cellvalue, options, rowObject){
			return '<a class="btn btn-primary" href="'+base_url("cuentacorriente/usuario/"+rowObject.idcli)+'" ><i class="glyphicon glyphicon-search"></i> Ver Cuenta</a>';
			
		}},
	];	
		
	var grid = jqGridStart('list', 'pager', 'mantenimiento/ajax/CargarClientes', colsNames, colsModel, 'idcli', 'desc' );

	grid.jqGrid('filterToolbar', {stringResult: true, searchOnEnter: true});
})
</script>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<a class="btn btn-default pull-right" href="<?php echo base_url('index.php/mantenimiento/cliente'); ?>">
				<span class="glyphicon glyphicon-file"></span>
				Nuevo Paciente
			</a>
			<h1>Pacientes</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li class="active">Pacientes</li>
		</ol>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table id="list"></table>
					<div id="pager"></div>
				</div>
			</div>
		</div>
	</div>

	<center><a href="<?php echo base_url("mantenimiento/generarExcel/cliente");?>" target="_blank"><img src="<?php echo base_url();?>assets/images/excel.png" style="width:50px;"> <b>Exportar a Excel</b></a></center>
</div>


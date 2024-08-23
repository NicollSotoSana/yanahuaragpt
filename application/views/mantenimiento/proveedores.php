<script>
$(document).ready(function(){
	var colsNames = ['id','Nombre','Identidad','Correo','T. Principal', 'T. Adicional', 'Deuda'];
	var colsModel = [ 
		{name:'id',index:'id', width:55, hidden: true},
		{name:'Nombre', index:'Nombre', sopt: 'like', formatter: function(cellvalue, options, rowObject){
				return jqGridCreateLink('mantenimiento/proveedor/' + rowObject.id, cellvalue);
			}},
		{name:'Identidad',index:'Identidad', width:35},
		{name:'Correo', index:'Correo', width: 40, search: false, formatter: function(cellvalue, options, rowObject){
			return '<a href="mailto:' + cellvalue + '">' + cellvalue + '</a>';
		}},
		{name:'Telefono1', index:'Telefono1', width: 35, search: false},
		{name:'Telefono2', index:'Telefono2', width: 35, search: false},
		{name:'deuda_sum', index:'deuda_sum', width: 35, search: false, formatter: function(cellvalue, options, rowObject){
			if(cellvalue>0){
				return '<b style="color:red;">S/.' + cellvalue + '</b>';
			}else{
				return '<b style="color:green;">Sin Deuda</b>';
			}
			
		}}
	];	
		
	var grid = jqGridStart('list', 'pager', 'mantenimiento/ajax/CargarProveedores', colsNames, colsModel, '', '' );

	grid.jqGrid('filterToolbar', {stringResult: true, searchOnEnter: true});
})
</script>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<a class="btn btn-default pull-right" href="<?php echo base_url('index.php/mantenimiento/proveedor'); ?>">
				<span class="glyphicon glyphicon-file"></span>
				Nuevo Proveedor
			</a>
			<h1>Proveedores</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li class="active">Clientes</li>
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
</div>

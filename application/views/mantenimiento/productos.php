<script>
$(document).ready(function(){
	var colsNames = ['id','Nombre','Codigo', 'Cod. Varilla', 'Marca', 
		<?php if(HasModule('stock')): ?>
			'Stock',
		<?php endif; ?>
	'UDM', 'P.C', 'P.V','M.G (%)'];
	var colsModel = [ 
		{name:'id',index:'id', width:25, hidden: true},
		{name:'Nombre', index:'Nombre', sopt: 'like', search:true, formatter: function(cellvalue, options, rowObject){
				return jqGridCreateLink('mantenimiento/producto/' + rowObject.id, cellvalue);
			}},
		{name:'codigo_prod', index:'codigo_prod', width: 40, sopt: 'like', search:true},

		{name:'codigo_varilla', index:'codigo_varilla', width: 40, sopt: 'like', search:true},
		
		{name:'Marca', index:'Marca', width: 30},
		<?php if(HasModule('stock')): ?>
			{name:'Stock', index:'Stock', width: 45, align:"right", search:false, formatter: function(cellvalue, options, rowObject){
				return (cellvalue <= rowObject.StockMinimo ? '<span style="font-weight:bold;color:red;">' + cellvalue + '</span>' : '<span style="font-weight:bold;">' + cellvalue + '</span>');
			}},
		<?php endif; ?>
		
		{name:'UnidadMedida_id', index:'UnidadMedida_id', width: 30, search: false},
        {name:'PrecioCompra',index:'PrecioCompra', width: 30, align:"right", search: false, formatter:'decimal'},
		{name:'Precio',index:'Precio', width: 30, align:"right", search: false, formatter:'decimal'},
		{name:'MargenGanancia', index:'MargenGanancia', width: 30, align:"right", search: false, sortable: false, formatter: function(cellvalue, options, rowObject){
			return cellvalue + '%';
		}}
	];	
		
	var grid = jqGridStart('list', 'pager', 'mantenimiento/ajax/CargarProductos', colsNames, colsModel, '', '' );

	grid.jqGrid('filterToolbar', {stringResult: true, searchOnEnter: true});
})
</script>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<a class="btn btn-default pull-right" href="<?php echo base_url('index.php/mantenimiento/producto'); ?>">
				<span class="glyphicon glyphicon-file"></span>
				Nuevo Producto
			</a>
			<!--<a class="btn btn-primary pull-right" target="_blank" href="<?php echo base_url('index.php/mantenimiento/getCodBarras'); ?>">
				<span class="glyphicon glyphicon-download"></span>
				Codigos de Barra
			</a>-->
			<h1>Productos</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li class="active">Productos</li>
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
	<center><a href="<?php echo base_url("mantenimiento/generarExcel/producto");?>" target="_blank"><img src="<?php echo base_url();?>assets/images/excel.png" style="width:50px;"> <b>Exportar a Excel</b></a> | <a href="<?php echo base_url("mantenimiento/productosConStock");?>" target="_blank"><img src="<?php echo base_url();?>assets/images/excel.png" style="width:50px;"> <b>Exportar a Productos con Stock</b></a></center>
</div>


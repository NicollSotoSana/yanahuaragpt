<script>
$(document).ready(function(){
	var colsNames = ['id', 'Serie', 'Cliente','Usuario','Emitido', 'Estado', 'Total'];
	var colsModel = [
		{name:'id',index:'c.id', width:55, hidden: true},
		{name:'Codigo',index:'Codigo', width:50},
		{name:'ClienteNombre', index:'ClienteNombre', formatter: function(cellvalue, options, rowObject){
				return jqGridCreateLink('notas/nota/' + rowObject.id, cellvalue);
			}},
		{name:'Usuario', index:'Usuario', width: 70, formatter: function(cellvalue, options, rowObject){
			return '<span title="' + rowObject.Nombre + '">' + cellvalue + '</span>';
		}},
		
		{name:'FechaEmitido', index:'FechaEmitido', width: 50,
    		formatter: function(cellvalue, options, rowObject){
    			return ParseDate(cellvalue);
        		}},
		{name:'EstadoNombre',index:'EstadoNombre', width:60,
			stype: 'select',
	        searchoptions: {
	            value: "<?php echo jqGrid_searchoptions($estados, 'Value', 'Nombre'); ?>",
	            defaultValue: 't'
        },
		formatter: function(cellvalue, options, rowObject){
			if(rowObject.Estado==1) return '<span style="color:#D15600;font-weight:bold;">' + rowObject.EstadoNombre + '</span>';
			if(rowObject.Estado==2) return '<span style="color:#006E2E;font-weight:bold;">' + rowObject.EstadoNombre + '</span>';
			if(rowObject.Estado==3) return '<span style="color:#CC0000;font-weight:bold;">' + rowObject.EstadoNombre + '</span>';
			if(rowObject.Estado==4) return '<span style="color:purple;font-weight:bold;">' + rowObject.EstadoNombre + '</span>';
		}},
		{name:'Total', index:'Total', width: 40, align:"center", search: false}
	];	
		
	var grid = jqGridStart('list', 'pager', 'notas/ajax/CargarNotas', colsNames, colsModel, '', '');

	grid.jqGrid('filterToolbar', {stringResult: true, searchOnEnter: true});
})
</script>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<!--<a class="btn btn-default pull-right" href="<?php echo base_url('index.php/ventas/comprobante'); ?>">
				<span class="glyphicon glyphicon-file"></span>
				Nuevo Comprobante
			</a>-->
			<h1>Notas de Crédito</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li class="active">Comprobantes</li>
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


<script>
$(document).ready(function(){
	var colsNames = ['id','Fecha','Banco','Monto','Descripción'];
	var colsModel = [ 
		{name:'id',index:'id', width:15, hidden: true},
		{name:'fecha', index:'fecha', width:15,search: false, formatter: function(cellvalue, options, rowObject){
				var myarr = cellvalue.split("-");
				return myarr[2]+"/"+myarr[1]+"/"+myarr[0];
			}},
		{name:'banco',index:'banco', width:35, search: false},
		{name:'monto', index:'monto', width: 40, search: false, formatter: function(cellvalue, options, rowObject){
				return "S/. "+rowObject.monto;
			}},
		{name:'descripcion', index:'descripcion', width: 35, search: false},
		
	];	
		
	var grid = jqGridStart('list', 'pager', 'depositos/ajax/cargarDepositos', colsNames, colsModel, '', '' );

	grid.jqGrid('filterToolbar', {stringResult: true, searchOnEnter: true});
})
</script>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<button class="btn btn-default pull-right" id="addDepo" onclick="addDepo();" type="button">
				<span class="glyphicon glyphicon-file"></span>
				Nuevo Depósito
			</button>
			<h1>Depósitos</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li class="active">Depósitos</li>
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

<script type="text/javascript">
	function addDepo(){
		//alert(correlativonum);
		AjaxPopupModal('mAddPago', 'Agregar Depósito', 'depositos/ajax/agregarDep', {})
	}
</script>
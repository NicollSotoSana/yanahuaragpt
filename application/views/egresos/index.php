<script>
$(document).ready(function(){
	var colsNames = ['id_egreso','Fecha','Monto','Descripción','Origen de Dinero'];
	var colsModel = [ 
		{name:'id_egreso',index:'id_egreso', width:15, hidden: true},
		{name:'fecha', index:'fecha', width:15,search: false, formatter: function(cellvalue, options, rowObject){
				var myarr = cellvalue.split("-");
				return myarr[2]+"/"+myarr[1]+"/"+myarr[0];
			}},
		{name:'monto_egreso', index:'monto_egreso', width: 40, search: false, formatter: function(cellvalue, options, rowObject){
				return "S/. "+rowObject.monto_egreso;
			}},
		{name:'concepto', index:'concepto', width: 35, search: false},
		{name:'origen_dinero', index:'origen_dinero', width: 40, search: false, formatter: function(cellvalue, options, rowObject){
				//return "S/. "+rowObject.monto_egreso;
				if(cellvalue==1){
					return "Caja Chica";
				}else{
					return "Cuenta de Empresa";
				}
			}}
		
	];	
		
	var grid = jqGridStart('list', 'pager', 'egresos/ajax/cargarEgresos', colsNames, colsModel, 'id_egreso', 'asc' );

	grid.jqGrid('filterToolbar', {stringResult: true, searchOnEnter: true});
})
</script>
<div class="row">
	<div class="col-md-12">
		
		<div class="page-header">
			<button class="btn btn-success pull-right" id="addDepo" onclick="addDepo();" type="button">
				<span class="glyphicon glyphicon-file"></span>
				Nuevo Egreso
			</button>
			<h1>Egresos</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li class="active">Egresos</li>
		</ol>
		<div class="row">
			<div class="col-md-12">
				<?php 
			$correcto = $this->session->flashdata('correcto');
			if ($correcto){
		?>
			    <div class="alert alert-success"><strong><?php echo $correcto; ?></strong></div>
		<?php
			}
		?>
				<div class="table-responsive">
					<table id="list"></table>
					<div id="pager"></div>
				</div>
			</div>
		</div>
	</div>
	<center><a href="<?php echo base_url("mantenimiento/generarExcel/egresos");?>" target="_blank"><img src="<?php echo base_url();?>assets/images/excel.png" style="width:50px;"> <b>Exportar a Excel</b></a></center>
</div>

<script type="text/javascript">
	function addDepo(){
		//alert(correlativonum);
		AjaxPopupModal('mAddPago', 'Agregar Egreso', 'egresos/ajax/agregarEgreso', {})
	}
</script>
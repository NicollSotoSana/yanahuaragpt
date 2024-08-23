<script>
function jqGridStartCompra(id, pager, url, colsnames, colsmodel, sortname, sortorder)
{
	if(sortname == '') sortname = 'id';
	if(sortorder == '') sortorder = 'desc';
	
	var grid = $("#" + id);
	grid.jqGrid(
		{ 
			url: base_url(url), 
			datatype: 'json', 
			colNames:colsnames, 
			colModel:colsmodel, 
			rowNum:20, 
			rowList:[20,30,100],
			pager: '#' + pager,
			sortname: sortname,
			viewrecords: true,
			sortorder: sortorder,
			autowidth:true,
			height: 'auto',
			filterToolbar: true,
			footerrow: true,
			loadComplete: function(){
				// footer data
				$(this).jqGrid("footerData", "set", {
					//monto_cancelado: "<b style='font-size:16px;'>Total: </b>",
					saldo: "<b style='font-size:16px;'>S/. "+$(this).jqGrid('getCol', 'saldo', false, 'sum')+"</b>"
				});
			},
		}
	);
	return grid;
}

$(document).ready(function(){
	var colsNames = ['id_compra','Fecha', 'Comprobante','Monto Total','Monto Cancelado', 'Saldo', 'Estado Deuda', 'Proveedor','Ver Compra'];
	var colsModel = [ 
		{name:'id_compra',index:'id_compra', width:15, hidden: true},
		{name:'fecha', index:'fecha', width:15,search: false, formatter: function(cellvalue, options, rowObject){
				var myarr = cellvalue.split("-");
				return myarr[2]+"/"+myarr[1]+"/"+myarr[0];
			}},
		{name:'guia_factura', index:'guia_factura', width: 35, search: true},
		{name:'monto', index:'monto', width: 20, search: true, formatter: function(cellvalue, options, rowObject){
				return "S/. "+cellvalue;
			}},
		{name:'monto_cancelado', index:'monto_cancelado', width: 20, search: true, formatter: function(cellvalue, options, rowObject){
				return "S/. "+cellvalue;
			}},
		{name:'saldo', index:'saldo', width: 20, search: false},
		{name:'estado_deuda', index:'estado_deuda', width: 20, search: true,
		stype: 'select',
		searchoptions: {
			value: "t:Todo;1:Con Deuda;0:Sin Deuda",
			defaultValue: 't'
        }, formatter: function(cellvalue, options, rowObject){
			if(cellvalue == 1){
				return '<span style="color:#CC0000;font-weight:bold;">Con Deuda</span>';
			}else{
				return '<span style="color:#006E2E;font-weight:bold;">Sin Deuda</span>';
			}
		}},
		{name:'Nombre', index:'Nombre', width: 35, search: true},
		{name:'id_compra', index:'id_compra', width: 40, search: false, formatter: function(cellvalue, options, rowObject){
			return '<a title="Ver Comprobante" class="btn btn-info" href="' + base_url('almacen/nuevaCompra/0/' + cellvalue) + '"><i class="glyphicon glyphicon-search"></i> Ver Detalle</a>';
		}},
		
	];	
		
	var grid = jqGridStartCompra('list', 'pager', 'almacen/ajax/cargarCompras', colsNames, colsModel, 'id_compra', '' );

	grid.jqGrid('filterToolbar', {stringResult: true, searchOnEnter: true});

})
</script>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<a href="<?php echo base_url('almacen/nuevaCompra'); ?>" class="btn btn-primary pull-right" type="button">
				<span class="glyphicon glyphicon-file"></span>
				Nueva <b>Compra</b>
			</a>
			<h1>Compras</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li class="active">Compras</li>
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
				<div style="text-align:center;">
					<div class="col-md-offset-3 col-md-2">
						<label>Inicio</label>
						<input type="date" name="dinicio" id="dinicio" value="<?php echo date('Y-m-d');?>" class="form-control">
					</div>
					<div class="col-md-2">
						<label>Fin</label>
						<input type="date" name="dfin" id="dfin" value="<?php echo date('Y-m-d');?>" class="form-control">
					</div>
					<div class="col-md-2">
						<br/>
						<button onclick="getCompras();" class="btn btn-success">Exportar a Excel</button>
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>
			
		</div>
	</div>
</div>

<div class="modal fade" id="modal-7">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Editar Egreso</h4>
            </div>

            <div class="modal-body">

                Cargando...

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
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
<script type="text/javascript">
	function addEgre(){
		//alert(correlativonum);
		AjaxPopupModal('mAddEgre', 'Agregar Egreso', 'egresos/ajax/agregarEgreso', {})
	}
</script>

    <script type="text/javascript">
        function showAjaxModal(uid)
        {
           //var uid = $(this).data('id');
            ///alert(uid);
            jQuery('#modal-7').modal('show', {backdrop: 'static'});

            jQuery.ajax({
                url: "<?php echo base_url();?>egresos/editEgreso/" + uid,
                success: function(response)
                {
                    jQuery('#modal-7 .modal-body').html(response);
                }
            });
        }
        </script>

<script>
	function getCompras(){
		var inicio = $("#dinicio").val();
		var fin = $("#dfin").val();
		window.open('<?php echo base_url();?>egresos/reporteCompras/'+inicio+'/'+fin, '_blank');
	}

</script>
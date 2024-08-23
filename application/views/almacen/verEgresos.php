<script>

$(document).ready(function(){
	var colsNames2 = ['id_egreso','Fecha','Monto','Descripción','Origen de Dinero', 'Editar'];
	var colsModel2 = [ 
		{name:'id_egreso',index:'id_egreso', width:5, hidden: true},
		{name:'fecha', index:'fecha', width:5,search: false, formatter: function(cellvalue, options, rowObject){
				var myarr = cellvalue.split("-");
				return myarr[2]+"/"+myarr[1]+"/"+myarr[0];
			}},
		{name:'monto_egreso', index:'monto_egreso', width: 10, search: false, formatter: function(cellvalue, options, rowObject){
				return "S/. "+rowObject.monto_egreso;
			}},
		{name:'concepto', index:'concepto', width: 70, search: false},
		{name:'origen_dinero', index:'origen_dinero', width: 20, search: false, formatter: function(cellvalue, options, rowObject){
				//return "S/. "+rowObject.monto_egreso;
				if(cellvalue==1){
					return "Caja Chica";
				}else if(cellvalue==2){
					return "Cuenta Bancaria";
			    }
		}},
		{name:'id_egreso', index:'id_egreso', width: 20, search: false, formatter: function(cellvalue, options, rowObject){
			<?php
				if($this->user->Tipo == 1){
			?>
				return '<button type="button" class="btn btn-primary" title="Ver Comprobante" data-id="'+cellvalue+'" onclick="showAjaxModal('+cellvalue+');"><i class="glyphicon glyphicon-pencil"></i> Editar</button>';

			<?php
				}else{
			?>
				return '<button type="button" class="btn btn-primary" title="Ver Comprobante" data-id="'+cellvalue+'" readonly disabled><i class="glyphicon glyphicon-pencil"></i> Editar</button>';
			<?php
				}
			?>
		}}
		
	];	
		
	var grid2 = jqGridStart('list2', 'pager2', 'egresos/ajax/cargarEgresos', colsNames2, colsModel2, 'id_egreso', 'desc' );

	grid2.jqGrid('filterToolbar', {stringResult: true, searchOnEnter: true});
})
</script>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<button class="btn btn-primary pull-right" id="addEgre" onclick="addEgre();" type="button">
				<span class="glyphicon glyphicon-file"></span>
				Nuevo <b>Egreso</b>
			</button>
			<h1>Egresos</h1>
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
				<div style="clear:both;"></div>
				<div class="table-responsive">
					<table id="list2"></table>
					<div id="pager2"></div>

				</div>
				<div style="text-align:center;">
					<div class="col-md-offset-3 col-md-2">
						<label>Inicio</label>
						<input type="date" name="dinicio2" id="dinicio2" value="<?php echo date('Y-m-d');?>" class="form-control">
					</div>
					<div class="col-md-2">
						<label>Fin</label>
						<input type="date" name="dfin2" id="dfin2" value="<?php echo date('Y-m-d');?>" class="form-control">
					</div>
					<div class="col-md-2">
						<br/>
						<button onclick="getEgresos();" class="btn btn-success">Exportar a Excel</button>
					</div>
				</div>
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

	function getEgresos(){
		var inicio = $("#dinicio2").val();
		var fin = $("#dfin2").val();
		window.open('<?php echo base_url();?>egresos/reporteEgresos/'+inicio+'/'+fin, '_blank');
	}
</script>
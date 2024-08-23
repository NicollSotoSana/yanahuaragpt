<script type="text/javascript" src="<?php echo base_url('assets/scripts/venta/compras.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/scripts/venta/compras_nuevoprod.js'); ?>"></script>
	<div class="col-md-12">
		<div class="page-header">
			
			<?php 
			//var_dump($comprobante);
			if($comprobante != null){
				$ncompra = str_pad($comprobante[0]->id_compra, 6, '0', STR_PAD_LEFT);
				echo "<h1>Compra #".$ncompra."</h1><h4>Total Cancelado a la fecha: S/. ".$comprobante[0]->monto_cancelado."</h4>";
				echo '<div class="pull-right">';

				echo '<button type="button" style="margin-right:20px;" onclick="verPagos('.$comprobante[0]->id_compra.');" class="btn btn-warning"><b>Ver Pagos</b></button>';
				if($comprobante[0]->monto_cancelado < $comprobante[0]->monto){
					echo '<button type="button" onclick="addDepo('.$comprobante[0]->id_compra.');" class="btn btn-primary"><b>Agregar Pago</b></button>';
				}else{
					echo '<button type="button" class="btn btn-success" disabled><b>Deuda Cancelada</b></button>';
				}

				if($this->user->Tipo==1){
					echo '<button type="button" style="margin-left:20px;" onclick="eliminar('.$comprobante[0]->id_compra.')" class="btn btn-danger"><b>Eliminar Compra</b></button>';
				}

				echo '</div>';
			}else{
				echo '<h1>Nueva Compra</h1>';
				echo '<button class="btn btn-success" id="btn_add_prd">Nuevo Producto</button>';
			}
			?>
			
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li><a href="<?php echo base_url('index.php/almacen/index'); ?>">Almacén</a></li>
		  <li class="active">Nueva Compra</li>
		</ol>
		<div class="row">
			<div class="col-md-12">
				<?php echo form_open('almacen/guardarcompra', array('class' => 'upd')); ?>
				<input type="hidden" name="orden_lab" value="<?php echo $orden_lab;?>">
				<div class="well well-sm">(*) Campos obligatorios</div>
				<div class="row">
					<div class="col-md-4">
					  <div class="form-group">
					    <label>Proveedor <span id="spClienteRequerido">(*)</span></label>
					  
						    <div class="input-group">
						    <?php
						    if($comprobante==null){
						    ?>
						      <input id="txtCliente" autocomplete="off" name="ClienteNombre" type="text" class="form-control required ui-autocomplete-input" placeholder="Nombre del Proveedor" value="" maxlenght="100" data-name="">
						      <span class="input-group-btn">
						        <button id="btnClienteLimpiar" class="btn btn-default" type="button">
						        	<span class="glyphicon glyphicon-remove"></span>
						        </button>
						      </span>
						     <?php }else{ ?>
						     	<input autocomplete="off" name="ClienteNombre" type="text" class="form-control" placeholder="Nombre del Proveedor" value="<?php echo $proveedor->Nombre;?>" disabled>
						    <?php }?>
						    </div>
    						<input id="hdCliente_id" type="hidden" name="Cliente_id" value="" />
						  </div>
						</div>
					<div class="col-md-4">
					  <div class="form-group">
					    <label>Guía/Factura</label>
					    	<?php
						    if($comprobante==null){
						    ?>
						      <input type="text" autocomplete="off" name="guiafact" type="text" class="form-control required" placeholder="Guía de Remisión / Factura" value="" />
						     <?php }else{ ?>
						     	<input type="text" autocomplete="off" name="guiafact" type="text" class="form-control required" placeholder="Guía de Remisión / Factura" value="<?php echo $comprobante[0]->guia_factura;?>" disabled/>
						    <?php }?>
							
					    
					  </div>
					</div>
					<div class="col-md-2">
					  <div class="form-group">
					    <label>Fecha</label>
					    	<?php
						    if($comprobante==null){
						    ?>
						    	<input autocomplete="off" name="FechaEmitido" type="text" class="form-control required datepicker" placeholder="Fecha de Emisión" value="<?php echo date(DATE); ?>" maxlenght="10" />
						     <?php }else{ ?>
						     	<input autocomplete="off" name="FechaEmitido" type="text" class="form-control required datepicker" placeholder="Fecha de Emisión" value="<?php echo date('d/m/Y', strtotime($comprobante[0]->fecha));?>" disabled/>
						    <?php }?>
					  </div>
					</div>
					<div class="col-md-2">
					  <div class="form-group">
					    <label>Origen Dinero</label>
					    	<?php
						    if($comprobante==null){
						    ?>
						    	<select class="form-control" name="origen_compra">
						    		<option value="1">Caja Chica</option>
						    		<option value="2">Cuenta Bancaria</option>
						    	</select>
						     <?php }else{ ?>
						     	<select class="form-control" name="origen_compra" disabled>
						    		<option value="1" <?php echo ($comprobante[0]->origen_compra == 1) ? 'selected':'';?>>Caja Chica</option>
						    		<option value="2" <?php echo ($comprobante[0]->origen_compra == 2) ? 'selected':'';?>>Cuenta Bancaria</option>
						    	</select>
						    <?php }?>
					  </div>
					</div>
				</div>
				  <p style="margin-bottom:8px;">&nbsp;</p>
				  <!-- Detalle Factura -->
				  <table class="table" id="tablaped">
			  		<thead style="background:#eee;">
				  	<tr>
				  		<th style="width:20px;background:#eee;">#</th>
				  		<th style="width:20px;background:#eee;"></th>
				  		<th>Item</th>
				  		<th style="width:100px;">CNT</th>
				  		<th style="width:100px;">UDM</th>
				  		<th class="text-right" style="width:140px;">Precio Compra</th>
				  		<th class="text-right" style="width:140px;">Precio Total</th>
				  	</tr>
			  		</thead>
			  		<tbody>
			  		<?php if($comprobante != null): ?>
			  			<?php $totalCompra = 0;?>
					  	<?php foreach($comprobante as $k => $c): ?>
				  			<tr>
				  				<td class="text-right" style="background:#eee;padding:2px 4px;"><?php echo $k+1; ?></td>
				  				<td><span class="glyphicon glyphicon-chevron-right"></span></td>
				  				<td><?php echo $c->producto; ?></td>
				  				<td><?php echo number_format($c->cantidad, 2); ?></td>
				  				<td><?php echo $c->udm; ?></td>
				  				<td class="text-right"><?php 
				  				
				  					echo number_format($c->precio_compra, 2); 
				  					?></td>
				  				<td class="text-right">
				  					<?php 
				  					$tcc=$c->precio_compra*$c->cantidad;
				  					$tc = number_format($c->precio_compra*$c->cantidad, 2);
				  					$totalCompra += $tcc;
				  					echo $tc;
				  				?></td>
				  			</tr>
			  			<?php endforeach; ?>
			        <?php endif; ?>
			        <?php if($comprobante == null): ?>
		  			<?php for($i=0; $i<5; $i++){?>
		  			<tr>
			  				<td class="text-right numro" style="background:#eee;padding:2px 4px;"><?php echo $i+1; ?></td>
			  				<td> <button type="button" class="btn btn-default btn-sm btnProductoQuitar" title="Remover este item" disabled="disabled"> <span class="glyphicon glyphicon-remove"></span> </button> </td>
			  				<td>
			  					<input id="txtProducto_<?php echo $i; ?>" data-id="0" autocomplete="off" name="ProductoNombre[]" type="text" class="form-control input-sm txtProducto" value="" placeholder="Escriba el nombre de un producto" />
			  					<input name="Producto_id[]"  type="hidden" class="hdProducto_id" value="" />
			  				</td>
			  				<td>
								<input name="PrecioUnitarioCompra[]"  type="hidden" class="hdPrecioUnitarioCompra" value="" />
			  					<input autocomplete="off" name="Cantidad[]" type="text" class="form-control input-sm txtCantidad price" value="" placeholder="Cantidad"  maxlength="5"/>
			  					<input name="Tipo[]" type="hidden" class="hdTipo" value="" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="UnidadMedida_id[]" type="text" class="form-control input-sm txtUnidad" value="" placeholder="UND"  />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="PrecioUnitario[]" type="text" class="form-control input-sm price text-right txtPrecioUnitario" value="" placeholder="Precio Unitario" maxlength="10" />
			  					<input autocomplete="off" name="PrecioUnitarioReal[]" type="hidden" class="form-control input-sm price text-right txtPrecioUnitarioReal" value="" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" type="text" class="form-control input-sm price text-right txtTotal" value="" placeholder="Total" />
			  				</td>
			  			</tr>
			  	<?php }?>
				  <?php endif; ?>
				  <?php $colspan = ($comprobante==null) ? '8':'6'; ?>
			  		</tbody>
			  		<tfoot style="background:#eee;">
					  <?php	if($comprobante==false){ ?>
				  			<tr>
				  				<td colspan="<?php echo $colspan-2;?>">
				  					<button type="button" class="btn btn-primary" id="btnadd"><span class="glyphicon glyphicon-plus"></span> <b>Agregar Fila</b></button>
				  				</td>
								<td>
									<button class="btn btn-warning" type="button" id="calcular_igv" onclick="CalcularIGV();"><span class="glyphicon glyphicon-usd"></span> Calcular IGV</button>
								</td>
				  			</tr>
				  		<?php }?>
			  			<tr>
			  				<td colspan="7">
							  <div class="form-group">
								<label>Referencia</label>
							    <textarea name="observaciones" rows="2" cols="" class="form-control"><?php echo $comprobante != null ? $c->observaciones:""; ?></textarea>
							  </div>
			  				</td>
			  			</tr>
			  			<tr id="trIva">
			  				<th class="text-right" colspan="6">
			  					IGV (%)
								<?php if($comprobante == null){ ?>
									<input id="txtIva" name="igv" style="width:54px;margin-left:10px;" class="form-control text-right input-sm price pull-right" value="<?php echo $this->conf->Iva; ?>" />
			  					<?php }else{?>
		  							<span style="font-weight:normal;">
		  						<?php 
				  					echo number_format($comprobante[0]->igv, 2);
				  				}
				  				?></span>
			  				
			  				</th>
			  				<td class="text-right">
								<?php if($comprobante == null){ ?>
									<input autocomplete="off" id="txtIvaSubTotal" name="igv_total" class="form-control text-right input-sm price" value="0.00" />
			  					<?php }else{?>
			  					<?php 
				  				
				  					echo number_format($comprobante[0]->igv_total, 2);
				  				}
				  				?>
		  							
			  				
			  				</td>
			  			</tr>
			  			<tr>
			  				<th class="text-right" colspan="6">Total (<?php echo $this->conf->Moneda_id; ?>)</th>
			  				<td class="text-right">
			  					<?php
						    if($comprobante==null){
						    ?>
						    	<input autocomplete="off" id="txtTotal"  class="form-control text-right input-sm price" name="totalC" value="0.00" />
						     <?php }else{ ?>
						     	<input autocomplete="off" id="txtTotal" readonly="readonly" class="form-control text-right input-sm" value="<?php echo number_format($comprobante[0]->monto, 2);?>" />
						    <?php }?>
									
			  				</td>
			  			</tr>
			  			
			  		</tfoot>
				  </table>
	<div style="clear:both;margin-bottom:15px;"></div>
	<?php if($comprobante==null){ ?>
				  <div class="clearfix text-right">
					  <button type="submit" class="btn btn-info submit-ajax-button">Guardar</button>
				  </div>
	<?php }?>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>

<!-- MODAL AGREGAR PRODUCTO -->
<div id="modalproducto" class="modal fade" role="dialog">
  	<div class="modal-dialog modal-lg">

    <!-- Modal content-->
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h4 class="modal-title">Nuevo Producto</h4>
			</div>
			<div class="modal-body">
				<div class="row">
				
                	<?php echo form_open('mantenimiento/productocrud', array('class' => 'upd', 'enctype'=>'multipart/form-data', 'id' => 'nuevoProdForm')); ?>
					<div class="col-md-3">
						<div class="form-group">
							<label>Nombre</label>
							<input autocomplete="off" id="txtProducto" name="Nombre" type="text" class="form-control" placeholder="Nombre del producto" value="-" />
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label>Marca</label>
							<input id="txtMarca" autocomplete="off" name="Marca" type="text" class="form-control" placeholder="Marca" value="" />
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label>Código Interno</label>
							<input autocomplete="off" name="codigo_prod" type="text" class="form-control" placeholder="Codigo" value="" maxlength="50" />
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label>Código Varilla</label>
							<input id="codigo_varilla" autocomplete="off" name="codigo_varilla" type="text" class="form-control" placeholder="Código de Varilla" value=""/>
						</div>
                  	</div>

					<div class="col-md-2">
						<div class="form-group">
							<label>Stock Inicial</label>
							<input type="text" class="form-control" name="Stock" class="price" value="0.00" />
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label>Stock Mínimo</label>
							<input name="StockMinimo" type="text" class="form-control price" value="1" />
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label>Costo Base</label>
							<input autocomplete="off" name="CostoBase"  type="text" class="form-control required price" placeholder="S/." value="" id="CostoBase" />
							<input type="hidden" id="costoOrigen">
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label>Costo Total</label>
							<input autocomplete="off" name="PrecioCompra"  type="text" class="form-control required price" placeholder="S/." value="" id="PrecioCompra" />
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label>Precio de Venta</label>
							<input autocomplete="off" name="Precio" id="Precio" type="text" class="form-control price" placeholder="Precio de Venta" value="" />
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label>Categoria</label>
							<input autocomplete="off" name="categoria"  type="text" class="form-control categoria" placeholder="Categoria" id="categoria_id" value="" />
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label>Rango</label>
							<select name="rango" id="rango" class="form-control">
							<option value="NINGUNO">NINGUNO</option>
							<option value="ECONOMICA">ECONOMICA</option>
							<option value="INTERMEDIA">INTERMEDIA</option>
							<option value="CARA">CARA</option>
							</select>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label>Material</label>
							<input autocomplete="off" name="material"  type="text" class="form-control material" placeholder="Material" value="" />
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label>Tipo de Aro</label>
							<select name="tipo_aro" id="tipo_aro" class="form-control">
								<option value="ARO COMPLETO">ARO COMPLETO</option>
								<option value="SEMI AL AIRE">SEMI AL AIRE</option>
								<option value="AL AIRE">AL AIRE</option>
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label>Sexo</label>
							<select name="sexo" id="" class="form-control">
								<option value="NIÑA">NIÑA</option>
								<option value="NIÑO">NIÑO</option>
								<option value="VARON">VARON</option>
								<option value="DAMA">DAMA</option>';
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label>Descripción</label>
							<input autocomplete="off" name="descripcion"  type="text" class="form-control" placeholder="Descripción" value="" />
						</div>
					</div>

					<div class="col-md-4">
						<label>Código Proveedor:</label><br/>
							<select name="codigo_proveedor" id="codigo_proveedor" class="form-control">
							</select>
						</select>
					</div>
				<button type="button" class="btn btn-success btn-block" id="nueProdBtn"><b>Guardar Producto & Añadir a Compra</b></button>
				<?php echo form_close(); ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
  	</div>
</div>


<script type="text/javascript">

	$("#btn_add_prd").on('click', function(){
		$.ajax({
			url: "<?php echo base_url();?>mantenimiento/getProveedores",
			type: "GET",
			dataType: 'json',
			success: function (data) {
				$("#codigo_proveedor").empty();
				$(data).each(function( index, element ) {
					$("#codigo_proveedor").append('<option value="'+element.codigo_proveedor+'">'+element.Nombre+'</option>');
				});
			},
			error: function (data) {
				console.log('Error:', data);
			}
		});
		$("#modalproducto").modal("show");
	});

	function addDepo(nropago){
		//alert(correlativonum);
		AjaxPopupModal('mAddPago', 'Agregar Depósito', 'depositos/ajax/agregarDep', {npago: nropago})
	}

	function eliminar(nrocompra){
		//alert(correlativonum);
		AjaxPopupModal('mAddPago', 'Eliminar Compra', 'almacen/ajax/eliminarCompra', {ncompra: nrocompra})
	}

	function verPagos(nrocompra){
		//alert(correlativonum);
		AjaxPopupModal('mAddPago', 'Detalle de Pagos Realizados', 'depositos/ajax/cargarPagos', {ncompra: nrocompra})
	}

	$("#nueProdBtn").on("click", function(){
		$("#nueProdBtn").prop('disabled', true);
		$.ajax({
			data: $('#nuevoProdForm').serialize(),
			url: "<?php echo base_url();?>mantenimiento/addProdCompra",
			type: "POST",
			dataType: 'json',
			success: function (data) {
				$('#nuevoProdForm').trigger("reset");

				var trb = $("#tablaped tbody tr").find(".hdProducto_id");
				$( trb ).each(function( index ) {
					var tr = $(this).closest('tr');
					if(tr.find('.hdProducto_id').val()==''){
						var costobase = parseFloat(data.producto.CostoBase);
						tr.find('.txtProducto').attr('data-id', data.producto.id);
						tr.find('.txtProducto').attr('data-name', data.producto.Nombre);
						tr.find('.txtProducto').val(data.producto.Nombre);
						tr.find('.hdProducto_id').val(data.producto.id);

						tr.find('.hdPrecioUnitarioCompra').val(costobase);
						tr.find('.txtCantidad').val("1");
						tr.find('.hdTipo').val("1");
						tr.find('.txtUnidad').val("UND");
						tr.find('.txtPrecioUnitario').val(costobase);
						tr.find('.txtPrecioUnitarioReal').val(costobase);
						tr.find('.txtPrecioUnitario').attr('data-compra', costobase);
						CalcularCompra();

						$("#nueProdBtn").prop('disabled', false);

						return false;
					}	
				});

				$('#modalproducto').modal('hide');
			},
			error: function (data) {
				console.log('Error:', data);
				$("#nueProdBtn").prop('disabled', false);
			}
		});
	});

	$("#btnadd").on('click', function(){
		console.log("a");
		var val = $(".numro:last").html();
		var nval = parseInt(val)+1;
		$('#tablaped').append('<tr> <td class="text-right numro" style="background:#eee;padding:2px 4px;">'+nval+'</td><td> <button type="button" class="btn btn-default btn-sm btnProductoQuitar" title="Remover este item" disabled="disabled"> <span class="glyphicon glyphicon-remove"></span> </button> </td><td> <input id="txtProducto_'+nval+'" data-id="0" autocomplete="off" name="ProductoNombre[]" type="text" class="form-control input-sm txtProducto" value="" placeholder="Escriba el nombre de un producto" data-name=""> <input name="Producto_id[]" type="hidden" class="hdProducto_id" value=""> </td><td><input name="PrecioUnitarioCompra[]" type="hidden" class="hdPrecioUnitarioCompra" value=""> <input autocomplete="off" name="Cantidad[]" type="text" class="form-control input-sm txtCantidad price" value="" placeholder="Cantidad" maxlength="5" readonly="readonly"> <input name="Tipo[]" type="hidden" class="hdTipo" value=""> </td><td> <input autocomplete="off" name="UnidadMedida_id[]" type="text" class="form-control input-sm txtUnidad" value="" placeholder="UND" readonly="readonly"> </td><td> <input autocomplete="off" name="PrecioUnitario[]" type="text" class="form-control input-sm price text-right txtPrecioUnitario" value="" placeholder="Precio Unitario" maxlength="10" readonly="readonly" title="" data-compra="0.00"> <input autocomplete="off" name="PrecioUnitarioReal[]" type="hidden" class="form-control input-sm price text-right txtPrecioUnitarioReal" value=""> </td><td> <input autocomplete="off" type="text" class="form-control input-sm price text-right txtTotal" value="" placeholder="Total"> </td></tr>');
		CalcularCompra();
	});
</script>
<script type="text/javascript" src="<?php echo base_url('assets/scripts/venta/comprobante.js'); ?>"></script>
<script>
	var ComprobanteTipo = 0;
	
</script>
<?php //array_debug($comprobante); ?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<div class="pull-right">
			  
				<div class="btn-group" title="Clientes">
				  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				    <span class="glyphicon glyphicon-user"></span> <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" role="menu">
				    <li><a href="<?php echo base_url('index.php/mantenimiento/clientes'); ?>" target="_blank">Mis Clientes</a></li>
				    <li><a href="<?php echo base_url('index.php/mantenimiento/cliente'); ?>" target="_blank">Cliente Nuevo</a></li>
				  </ul>
				</div>
				<div class="btn-group" title="Productos">
				  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				    <span class="glyphicon glyphicon-shopping-cart"></span> <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" role="menu">
				    <li><a href="<?php echo base_url('index.php/mantenimiento/productos'); ?>" target="_blank">Mis Productos</a></li>
				    <li><a href="<?php echo base_url('index.php/mantenimiento/producto'); ?>" target="_blank">Producto Nuevo</a></li>
				  </ul>
				</div>
				<div class="btn-group" title="Servicios">
				  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				     <span class="glyphicon glyphicon-briefcase"></span> <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" role="menu">
				    <li><a href="<?php echo base_url('index.php/mantenimiento/servicios'); ?>" target="_blank">Mis Servicios</a></li>
				    <li><a href="<?php echo base_url('index.php/mantenimiento/servicio'); ?>" target="_blank">Servicio Nuevo</a></li>
				  </ul>
				</div>
			</div>
			<h1>
				<?php 
						echo 'Nueva Nota';
					
				?>
			</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li><a href="<?php echo base_url('index.php/ventas/comprobantes'); ?>">Comprobantes</a></li>
		  <li class="active">Nueva Nota</li>
		</ol>
		<div class="row">
			<div class="col-md-12">
				<?php echo form_open('notas/notascrud', array('class' => 'upd')); ?>
				<input type="hidden" value="<?php echo $tipo_doc; ?>" name="orgdoctype">
				<div class="well well-sm">(*) Campos obligatorios</div>
				<div class="row">
					<div class="col-md-2">
					  <div class="form-group">
					    <label>Comprobante (*)</label>
					    <select id="sltComprobante" name="ComprobanteTipo_id" class="form-control required ">
                            <option value="1" selected="selected">Nota de Crédito</option>
                        </select>
					  </div>
					</div>
					<div class="col-md-4">
					  <div class="form-group">
					    <label>Cliente <span id="spClienteRequerido">(*)</span></label>
					  
						    <div class="input-group">
						      <input id="txtCliente" autocomplete="off" name="ClienteNombre" type="text" class="form-control required ui-autocomplete-input" placeholder="Nombre del Cliente" value="<?php echo $comprobante->ClienteNombre; ?>" maxlenght="100" data-name="<?php echo $comprobante->ClienteNombre; ?>">
						      <span class="input-group-btn">
						        <button id="btnClienteLimpiar" class="btn btn-default" type="button">
						        	<span class="glyphicon glyphicon-remove"></span>
						        </button>
						      </span>
						    </div>
    						<input id="hdCliente_id" type="hidden" name="Cliente_id" value="<?php echo $comprobante->Cliente_id; ?>" />
						  </div>
						</div>
						<div class="col-md-2">
						  <div class="form-group">
						    <label><span id="spIdentidad">RUC</span> <span id="spRucRequerido">(*)</span></label>
						    <input id="txtRuc" readonly="readonly" id="txtRuc" autocomplete="off" name="ClienteIdentidad" type="text" class="form-control required" placeholder="RUC" value="<?php echo $comprobante->ClienteIdentidad; ?>" maxlenght="11" />
					  </div>
					</div>
					<div class="col-md-2">
					  <div class="form-group">
					    <label>Fecha</label>
					    
							<input autocomplete="off" name="FechaEmitido" type="text" class="form-control required datepicker" placeholder="Fecha de Emisión" value="<?php echo date(DATE); ?>" maxlenght="10" />
					    
					  </div>
					</div>
					<div class="col-md-2">
					  <div class="form-group">
					    <label>Estado (*)</label>
					    <?php 
							    	echo '<input type="text" style="color:orange;" value="Pendiente" disabled="disabled" class="form-control" />';
				
					    ?>
					  </div>
					</div>
				</div>
				  <div class="form-group">
				    <label>Dirección <span id="spDireccionRequerido">(*)</span></label>
					<input id="txtDireccion" type="text" autocomplete="off" id="txtDireccion" name="ClienteDireccion" class="form-control" value="<?php echo $comprobante->ClienteDireccion; ?>" readonly="readonly" />
				  </div>
				  <div class="col-md-8">
	                  <div class="form-group">
					    <label>Motivo/Sustento <span id="spDireccionRequerido">(*)</span></label>
						<input id="motivo_sustento" type="text" autocomplete="off" id="motivo_sustento" name="motivo_sustento" class="form-control" value="Error al emitir el comprobante"  />
					  </div>
				  </div>
				  <div class="col-md-4">
	                  <div class="form-group">
					    <label>Tipo de Nota<span id="spDireccionRequerido">(*)</span></label>
						<select class="form-control" name="tiponota">
							<option value="01">Anulación de la Operación</option>
							<option value="02">Anulación por error en el RUC</option>
							<option value="03">Corrección por error en la descripción</option>
							<option value="04">Descuento Global</option>
							<option value="05">Descuento por Item</option>
							<option value="06">Devolución Total</option>
							<option value="07">Devolución por Item</option>
							<option value="08">Bonificación</option>
							<option value="09">Disminución en el valor</option>
							<option value="13">Ajustes – montos y/o fechas de pago</option>
						</select>
					  </div>
				  </div>
                  <input type="hidden" value="<?php echo $external; ?>" name="external_id" id="external_id">


				  <div class="col-md-12"><div class="alert alert-warning" role="alert">
								Recuerde que para facturas con descuento <b>debe modificar los precios de cada item</b> hasta que el total cuadre con el monto de la factura original.
							</div></div>
				  <p style="margin-bottom:8px;">&nbsp;</p>
				  <!-- Detalle Factura -->
				  <table class="table">
			  		<thead style="background:#eee;">
				  	<tr>
				  		<th style="width:20px;background:#eee;">#</th>
				  		<th style="width:20px;background:#eee;"></th>
				  		<th>Item</th>
				  		<th style="width:100px;">CNT</th>
				  		<th style="width:84px;">UND</th>
				  		<th class="text-right" style="width:140px;">P.U (<?php echo $this->conf->Moneda_id; ?>)</th>
				  		<th class="text-right" style="width:140px;">P.T (<?php echo $this->conf->Moneda_id; ?>)</th>
				  	</tr>
			  		</thead>
			  		<tbody>
				    <?php $contar = 0; //var_dump($comprobante->Detalle);?>
					<?php foreach($comprobante->Detalle as $k => $c): ?>
						<?php 
							
							/*$c->Producto_id = ($c->Producto_id!="00") ? $c->Producto_id : "00";*/
							if($c->Producto_id!=0){
								$contar++;
						?>
			  			<tr>
			  				<td class="text-right" style="background:#eee;padding:2px 4px;"><?php echo $k+1; ?></td>
			  				<td>
			  					<button type="button" class="btn btn-default btn-sm btnProductoQuitar" title="Remover este item">
			  						<span class="glyphicon glyphicon-remove"></span>
			  					</button>
			  				</td>
			  				<td>
			  					<input id="txtProducto_<?php echo $k; ?>" data-id="<?php echo $c->Producto_id; ?>" autocomplete="off" name="ProductoNombre[]" type="text" class="form-control input-sm txtProducto ui-autocomplete-input" value="<?php echo $c->ProductoNombre; ?>" placeholder="Escriba el nombre de un producto" data-name="<?php echo $c->ProductoNombre; ?>">
			  					<input name="Producto_id[]"  type="hidden" class="hdProducto_id" value="<?php echo $c->Producto_id; ?>" />
			  				</td>
			  				<td>
								<input name="PrecioUnitarioCompra[]"  type="hidden" class="hdPrecioUnitarioCompra" value="<?php echo $c->PrecioUnitarioCompra; ?>" />
			  					<input autocomplete="off" name="Cantidad[]" type="text" class="form-control input-sm txtCantidad price" value="<?php echo $c->Cantidad; ?>" placeholder="Cantidad"  maxlength="10"/>
			  					<input name="Tipo[]" type="hidden" class="hdTipo" value="<?php echo $c->Cantidad; ?>" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="UnidadMedida_id[]" type="text" class="form-control input-sm txtUnidad" value="<?php echo $c->UnidadMedida_id; ?>" placeholder="UND" readonly="readonly" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="PrecioUnitario[]" type="text" class="form-control input-sm price text-right txtPrecioUnitario" value="<?php echo $c->PrecioUnitario; ?>" placeholder="Precio Unitario" maxlength="10" title="PC: S/. <?php echo $c->PrecioUnitarioCompra; ?>" data-compra="<?php echo $c->PrecioUnitarioCompra; ?>">
			  				</td>
			  				<td>
			  					<input autocomplete="off" type="text" class="form-control input-sm price text-right txtTotal" value="" placeholder="Total" readonly="readonly" />
			  				</td>
			  			</tr>
			  			
		  			<?php } endforeach; ?>
		  			<?php for($i=$contar+1; $i<(10-$contar)+$contar; $i++){?>
		  			<tr>
			  				<td class="text-right" style="background:#eee;padding:2px 4px;"><?php echo $i; ?></td>
			  				<td>
			  					<button type="button" class="btn btn-default btn-sm btnProductoQuitar" title="Remover este item">
			  						<span class="glyphicon glyphicon-remove"></span>
			  					</button>
			  				</td>
			  				<td>
			  					<input id="txtProducto_<?php echo $i; ?>" data-id="0" autocomplete="off" name="ProductoNombre[]" type="text" class="form-control input-sm txtProducto" value="" placeholder="Escriba el nombre de un producto" />
			  					<input name="Producto_id[]"  type="hidden" class="hdProducto_id" value="" />
			  				</td>
			  				<td>
								<input name="PrecioUnitarioCompra[]"  type="hidden" class="hdPrecioUnitarioCompra" value="" />
			  					<input autocomplete="off" name="Cantidad[]" type="text" class="form-control input-sm txtCantidad price" value="" placeholder="Cantidad"  maxlength="10"/>
			  					<input name="Tipo[]" type="hidden" class="hdTipo" value="" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="UnidadMedida_id[]" type="text" class="form-control input-sm txtUnidad" value="" placeholder="UND" readonly="readonly" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="PrecioUnitario[]" type="text" class="form-control input-sm price text-right txtPrecioUnitario" value="" placeholder="Precio Unitario" maxlength="10" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" type="text" class="form-control input-sm price text-right txtTotal" value="" placeholder="Total" readonly="readonly" />
			  				</td>
			  			</tr>
			  	<?php }?>
			  	<tr>
		  			<td colspan="7"><h3>Productos Fuera de Inventario</h3></td>
		  				</tr>
			  	<?php foreach($comprobante->Detalle as $k => $c): ?>
			  		<?php 
							/**/
							if($c->Producto_id==0){
								$contar++;
							$c->Producto_id = ($c->Producto_id!="00") ? $c->Producto_id : "00";
						?>
						<tr>
			  				<td class="text-right" style="background:#eee;padding:2px 4px;"><?php echo $i; ?></td>
			  				<td>
			  					<button type="button" class="btn btn-default btn-sm btnProductoQuitar" title="Remover este item">
			  						<span class="glyphicon glyphicon-remove"></span>
			  					</button>
			  				</td>
			  				<td>
			  					<input id="txtProducto_<?php echo $i; ?>" data-id="<?php echo $c->Producto_id; ?>" autocomplete="off" name="ProductoNombre[]" type="text" class="form-control input-sm txtProducto ui-autocomplete-input" value="<?php echo $c->ProductoNombre; ?>" placeholder="Escriba el nombre de un producto" data-name="<?php echo $c->ProductoNombre; ?>">
			  					<input name="Producto_id[]"  type="hidden" class="hdProducto_id" value="<?php echo $c->Producto_id; ?>" />
			  				</td>
			  				<td>
								<input name="PrecioUnitarioCompra[]"  type="hidden" class="hdPrecioUnitarioCompra" value="<?php echo $c->PrecioUnitarioCompra; ?>" />
			  					<input autocomplete="off" name="Cantidad[]" type="text" class="form-control input-sm txtCantidad price" value="<?php echo $c->Cantidad; ?>" placeholder="Cantidad"  maxlength="10"/>
			  					<input name="Tipo[]" type="hidden" class="hdTipo" value="<?php echo $c->Cantidad; ?>" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="UnidadMedida_id[]" type="text" class="form-control input-sm txtUnidad" value="<?php echo $c->UnidadMedida_id; ?>" placeholder="UND" readonly="readonly" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="PrecioUnitario[]" type="text" class="form-control input-sm price text-right txtPrecioUnitario" value="<?php echo $c->PrecioUnitario; ?>" placeholder="Precio Unitario" maxlength="10" title="PC: S/. <?php echo $c->PrecioUnitarioCompra; ?>" data-compra="<?php echo $c->PrecioUnitarioCompra; ?>">
			  				</td>
			  				<td>
			  					<input autocomplete="off" type="text" class="form-control input-sm price text-right txtTotal" value="" placeholder="Total" readonly="readonly" />
			  				</td>
			  			</tr>
			  	<?php } endforeach; ?>
			  	<tr>
			  				<td class="text-right" style="background:#eee;padding:2px 4px;"><?php echo $i = $i+1; ?></td>
			  				<td>
			  					<button type="button" class="btn btn-default btn-sm btnProductoQuitar" title="Remover este item">
			  						<span class="glyphicon glyphicon-remove"></span>
			  					</button>
			  				</td>
			  				<td>
			  					<input id="txtProducto_<?php echo $i; ?>" data-id="00" autocomplete="off" name="ProductoNombre[]" type="text" class="form-control input-sm txtProducto ui-autocomplete-input" value="" placeholder="Escriba el nombre de un producto" data-name="Producto adicional">
			  					<input name="Producto_id[]"  type="hidden" class="hdProducto_id" value="00" />
			  				</td>
			  				<td>
								<input name="PrecioUnitarioCompra[]"  type="hidden" class="hdPrecioUnitarioCompra" value="0.00" />
			  					<input autocomplete="off" name="Cantidad[]" type="text" class="form-control input-sm txtCantidad price" value="0" placeholder="Cantidad"  maxlength="10"/>
			  					<input name="Tipo[]" type="hidden" class="hdTipo" value="1" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="UnidadMedida_id[]" type="text" class="form-control input-sm txtUnidad" value="UND" placeholder="UND" readonly="readonly" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="PrecioUnitario[]" type="text" class="form-control input-sm price text-right txtPrecioUnitario" value="0.00" placeholder="Precio Unitario" maxlength="10">
			  				</td>
			  				<td>
			  					<input autocomplete="off" type="text" class="form-control input-sm price text-right txtTotal" value="" placeholder="Total" readonly="readonly" />
			  				</td>
			  			</tr>
			  			<tr>
			  				<td class="text-right" style="background:#eee;padding:2px 4px;"><?php echo $i = $i+1; ?></td>
			  				<td>
			  					<button type="button" class="btn btn-default btn-sm btnProductoQuitar" title="Remover este item">
			  						<span class="glyphicon glyphicon-remove"></span>
			  					</button>
			  				</td>
			  				<td>
			  					<input id="txtProducto_<?php echo $i; ?>" data-id="00" autocomplete="off" name="ProductoNombre[]" type="text" class="form-control input-sm txtProducto ui-autocomplete-input" value="" placeholder="Escriba el nombre de un producto" data-name="Producto adicional">
			  					<input name="Producto_id[]"  type="hidden" class="hdProducto_id" value="00" />
			  				</td>
			  				<td>
								<input name="PrecioUnitarioCompra[]"  type="hidden" class="hdPrecioUnitarioCompra" value="0.00" />
			  					<input autocomplete="off" name="Cantidad[]" type="text" class="form-control input-sm txtCantidad price" value="0" placeholder="Cantidad"  maxlength="10"/>
			  					<input name="Tipo[]" type="hidden" class="hdTipo" value="1" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="UnidadMedida_id[]" type="text" class="form-control input-sm txtUnidad" value="UND" placeholder="UND" readonly="readonly" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="PrecioUnitario[]" type="text" class="form-control input-sm price text-right txtPrecioUnitario" value="0.00" placeholder="Precio Unitario" maxlength="10">
			  				</td>
			  				<td>
			  					<input autocomplete="off" type="text" class="form-control input-sm price text-right txtTotal" value="" placeholder="Total" readonly="readonly" />
			  				</td>
			  			</tr>
			  		</tbody>
			  		<tfoot style="background:#eee;">
					  	
			  			<tr>
			  				<td colspan="7">
							  <div class="form-group">
								<label>Comentario</label>
							    <textarea name="Glosa" rows="2" cols="" class="form-control"></textarea>
							  </div>
			  				</td>
			  			</tr>
			  			
	
			  			<tr id="trSubTotal">
			  				<th class="text-right" colspan="6">Sub Total (<?php echo $this->conf->Moneda_id; ?>)</th>
			  				<td class="text-right">
									<input autocomplete="off" id="txtSubTotal" class="form-control text-right input-sm" value="0.00" readonly="readonly" />  					
							</td>
			  			</tr>
			  			<tr id="trIva">
			  				<th class="text-right" colspan="6">
			  					IGV (%)
								
									<input id="txtIva" name="Iva" style="width:54px;margin-left:10px;" class="form-control text-right input-sm required price pull-right" value="<?php echo $this->conf->Iva; ?>" />
			  				</th>
			  				<td class="text-right">
									<input autocomplete="off" id="txtIvaSubTotal" readonly="readonly" class="form-control text-right input-sm" value="0.00" />
			  				</td>
			  			</tr>
			  			<tr id="trDsc" style="display:none;">
			  				<th class="text-right" colspan="6">
			  					Descuento (%)
									<input id="txtDsc" name="Dsc" style="width:54px;margin-left:10px;" class="form-control text-right input-sm price pull-right" value="0" />
			  				</th>
			  				<td class="text-right">
									<input autocomplete="off" id="txtDscT" name="totalDsc" readonly="readonly" class="form-control text-right input-sm" value="0.00" /><br/>
									<input autocomplete="off" id="txtDscT2" name="totalDsc2" class="form-control text-right input-sm" value="<?php echo number_format($comprobante->totalDsc, 2);?>"/>
			  				</td>
			  			</tr>
			  			<tr>
			  				<th class="text-right" colspan="6">Total (<?php echo $this->conf->Moneda_id; ?>)</th>
			  				<td class="text-right">
									<input autocomplete="off" id="txtTotal" readonly="readonly" class="form-control text-right input-sm" value="0.00" />
			  				</td>
			  			</tr>
			  	<tr style="display:none;">
			  				<th class="text-right" colspan="6">Moneda: </th>
			  				<td class="text-right">
			  					<input type="hidden" id="tipoCambio" name="tipoCambio" value="<?php echo $this->conf->tipo_cambio; ?>">
			  					<select name="moneda" id="moneda" class="form-control">
			  						<?php
				  	if(!empty($comprobante->moneda)){
				  		if($comprobante->moneda=="Usd" || $comprobante->moneda=="usd"){
				  			echo '<option value="usd" selected>Dolares</option>
				  			<option value="pen">Soles</option>';
				  		}else{
				  			echo '<option value="usd">Dolares</option><option value="pen" selected>Soles</option>';
				  		} 
				  	}else{echo '<option value="pen">Soles</option><option value="usd">Dolares</option>';}?>
			  					
			  					
			  				</select></td>
			  			</tr>
			  			
			  			<tr id="trDetrac" style="display:none;">
			  				<th class="text-right" colspan="6">
			  					Detracción (%) 
									<input id="txtPorDet" name="txtPorDet" style="width:54px;margin-left:10px;" class="form-control text-right input-sm price pull-right" value="<?php echo $this->conf->porcentaje_detraccion;?>" />
			  					
			  				</th>
			  				<td class="text-right">
									<input autocomplete="off" id="comp_detraccion" name="comp_detraccion" class="form-control text-right input-sm" value="0" readonly="readonly"/>
			  				</td>
			  			</tr>
			  			
			  		</tfoot>
				  </table>
				  <div class="col-md-4">
					<?php if($comprobante->tipo_pago == 2){ ?>
						<table id="cuotas">
							<thead>
								<tr>
									<th>Fecha</th>
									<th>Monto</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$cuotas = json_decode($comprobante->cuotas, true);

								foreach($cuotas as $c){
									echo '<tr>
										<td>
											<input type="date" name="fecha_cuota[]" class="form-control" value="'.$c["fecha"].'">
										</td>
										<td>
											<input type="number" name="monto_cuota[]" class="form-control" value="'.$c["monto"].'">
										</td>
									</tr>';
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<td>
										<button id="btnaddcuota" type="button" class="btn btn-info">Agregar Cuota</button>
									</td>
								</tr>
							</tfoot>
						</table>
					<?php }?>
				</div>
				<div class="col-md-8">
					<div style="clear:both;margin-bottom:15px;"></div>
				  		<div class="clearfix text-right">
				  			<!--<input type="checkbox" name="generar_deuda" value="1"> <label>Generar Deuda</label><br/>-->
					  		<button type="submit" class="btn btn-info submit-ajax-button">Guardar</button>
				  		</div>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>

<?php if(HasModule('stock') && $comprobante != null): ?>
	<?php if($comprobante->Devolucion == 0 && $comprobante->Estado == 3 && ($comprobante->ComprobanteTipo_id == 2 || $comprobante->ComprobanteTipo_id == 3 || $comprobante->ComprobanteTipo_id == 4)): ?>
		<script>
			$(document).ready(function(){
				AjaxPopupModalDontClose('mDevolucion', 'Productos para devolver al almacén', 'ventas/ajax/CargarDetalleParaDevolver', { comprobante_id : <?php echo $comprobante->id; ?>})
			})
		</script>
	<?php endif; ?>
<?php endif; ?>

<script>
$(".txtPrecioUnitario").keyup(function(e){
		
	CalcularComprobante();
	
});

$("#btnaddcuota").on('click', function(){
	$('#cuotas').append('<tr><td><input type="date" name="fecha_cuota[]" class="form-control" value="<?php echo date("Y-m-d");?>"></td><td><input type="number" name="monto_cuota[]" class="form-control" value="0"></td></tr>');
});
</script>
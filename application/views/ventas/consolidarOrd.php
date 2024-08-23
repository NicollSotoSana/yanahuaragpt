<script type="text/javascript" src="<?php echo base_url('assets/scripts/venta/comprobante.js'); ?>"></script>
<script>
	var ComprobanteTipo = 0;
	
</script>
<?php //array_debug($orden_lab);?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<div class="pull-right">
			<!--
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
				-->
				<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalcoddsc"><i class="fas fa-tags"></i> Cod. Dscto.</button>
				<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalclie"><i class="fas fa-user"></i> Añadir Paciente</button>
			</div>
			<h1>
				<?php 
						echo 'Nuevo Comprobante';
					
				?>
			</h1>
			
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li><a href="<?php echo base_url('index.php/ventas/comprobantes'); ?>">Comprobantes</a></li>
		  <li class="active">Nuevo Comprobante</li>
		</ol>
		<div class="row">
			<div class="col-md-12">
				<?php echo form_open('ventas/comprobantecrud', array('class' => 'upd')); ?>
				
				<input type="hidden" name="id_anamnesis" value="<?php echo $id_anamnesis;?>">
				<input type="hidden" name="id_orden" value="<?php echo $orden_lab[0]->id_orden;?>">
				<div class="well well-sm">(*) Campos obligatorios</div>
				<div class="row">
					<div class="col-md-2">
					  <div class="form-group">
					    <label>Comprobante (*)</label>
					    <?php
					    	
					    		echo Select('ComprobanteTipo_id', $tipos, 'Nombre', 'Value', 0, true, null, array('id' => 'sltComprobante'));
					    
					    ?>
					  </div>
					</div>
					<div class="col-md-4">
					  <div class="form-group">
					    <label>Cliente <span id="spClienteRequerido">(*)</span></label>
					  
						    <div class="input-group">
						      <input id="txtCliente" autocomplete="off" name="ClienteNombre" type="text" class="form-control required ui-autocomplete-input" placeholder="Nombre del Cliente" value="<?php echo $cliente->Nombre; ?>" maxlenght="100" data-name="<?php echo $cliente->Nombre; ?>">
						      <span class="input-group-btn">
						        <button id="btnClienteLimpiar" class="btn btn-default" type="button">
						        	<span class="glyphicon glyphicon-remove"></span>
						        </button>
						      </span>
						    </div>
    						<input id="hdCliente_id" type="hidden" name="Cliente_id" value="<?php echo $cliente->id; ?>" />
						  </div>
						</div>
						<div class="col-md-2">
						  <div class="form-group">
						  	<?php
						  		if(empty($cliente->Ruc)){
						  			$identidad = $cliente->Dni;
						  		}else{
						  			$identidad = $cliente->Ruc;
						  		}
						  	?>
						    <label><span>Nro. Doc.</span> <span id="spRucRequerido">(*)</span></label>
						    <input id="txtRuc" readonly="readonly" id="txtRuc" autocomplete="off" name="ClienteIdentidad" type="text" class="form-control required" placeholder="RUC" value="<?php echo $identidad; ?>" maxlenght="11" />
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
				<div class="col-md-7">
				  <div class="form-group">
				    <label>Dirección <span id="spDireccionRequerido">(*)</span></label>
					<input id="txtDireccion" type="text" autocomplete="off" id="txtDireccion" name="ClienteDireccion" class="form-control" value="<?php echo $cliente->Direccion; ?>" readonly="readonly" />
				  </div>
				</div>
				  <div class="col-md-3">
			       <div class="form-group">
				    <label>Medio de Pago</label>
					<select name="mediopago" class="form-control" id="sltMedioPago">
					    <option value="0"> -- SELECCIONE -- </option>
						<option value="efectivo">Efectivo</option>
						<option value="visa">Visa</option>
						<option value="MC">Mastercard</option>
						<option value="estilos">Estilos</option>
						<option value="deposito">Deposito</option>   
						<option value="yape">Yape</option>   
					</select>
				  </div>
				</div>
				<div class="col-md-2">
			       <div class="form-group">
				    <label>Nro. Operación</label>
					<input type="text" class="form-control" name="nro_operacion" value="<?php echo isset($comprobante) ? $comprobante->nro_operacion : '';?>" <?php echo isset($comprobante) ? 'disabled' : ''; ?>>
				  </div>
				</div>
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
				    <?php $contar = 0;?>
						<?php $contar++;?>
			  			<tr>
			  				<td class="text-right" style="background:#eee;padding:2px 4px;"><?php echo 1; ?></td>
			  				<td>
			  					<button type="button" class="btn btn-default btn-sm btnProductoQuitar" title="Remover este item">
			  						<span class="glyphicon glyphicon-remove"></span>
			  					</button>
			  				</td>
			  				<td>
			  					<input id="txtProducto_0" data-id="00" autocomplete="off" name="ProductoNombre[]" type="text" class="form-control input-sm txtProducto ui-autocomplete-input" value="<?php echo $ord_lab_met["material_lente"]; ?>" placeholder="Escriba el nombre de un producto" data-name="<?php echo $ord_lab_met["material_lente"]; ?>">
			  					<input name="Producto_id[]"  type="hidden" class="hdProducto_id" value="00" />
			  				</td>
			  				<td>
								<input name="PrecioUnitarioCompra[]"  type="hidden" class="hdPrecioUnitarioCompra" value="<?php echo $ord_lab_met["precio_lente"]; ?>" />
			  					<input autocomplete="off" name="Cantidad[]" type="text" class="form-control input-sm txtCantidad price" value="1" placeholder="Cantidad"  maxlength="10"/>
			  					<input name="Tipo[]" type="hidden" class="hdTipo" value="1" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="UnidadMedida_id[]" type="text" class="form-control input-sm txtUnidad" value="PAR" placeholder="UND" readonly="readonly" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="PrecioUnitario[]" type="text" class="form-control input-sm price text-right txtPrecioUnitario" value="<?php echo $ord_lab_met["precio_lente"]; ?>" placeholder="Precio Unitario" maxlength="10" title="PC: S/. <?php echo $ord_lab_met["precio_lente"]; ?>" data-compra="<?php echo $ord_lab_met["precio_lente"]; ?>" readonly="readonly">
			  				</td>
			  				<td>
			  					<input autocomplete="off" type="text" class="form-control input-sm price text-right txtTotal" value="" placeholder="Total" readonly="readonly" />
			  				</td>
			  			</tr>
			  			<?php
			  				if(isset($prod->id)){
			  					$contar++;
			  			?>
			  			<tr>
			  				<td class="text-right" style="background:#eee;padding:2px 4px;">2</td>
			  				<td>
			  					<button type="button" class="btn btn-default btn-sm btnProductoQuitar" title="Remover este item">
			  						<span class="glyphicon glyphicon-remove"></span>
			  					</button>
			  				</td>
			  				<td>
			  					<input id="txtProducto_1" data-id="<?php echo $prod->id; ?>" autocomplete="off" name="ProductoNombre[]" type="text" class="form-control input-sm txtProducto ui-autocomplete-input" value="<?php echo $prod->Nombre; ?>" placeholder="Escriba el nombre de un producto" data-name="<?php echo $prod->Nombre; ?>">
			  					<input name="Producto_id[]"  type="hidden" class="hdProducto_id" value="<?php echo $prod->id; ?>" />
			  				</td>
			  				<td>
								<input name="PrecioUnitarioCompra[]"  type="hidden" class="hdPrecioUnitarioCompra" value="<?php echo $prod->PrecioCompra; ?>" />
			  					<input autocomplete="off" name="Cantidad[]" type="text" class="form-control input-sm txtCantidad price" value="1" placeholder="Cantidad"  maxlength="10"/>
			  					<input name="Tipo[]" type="hidden" class="hdTipo" value="1" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="UnidadMedida_id[]" type="text" class="form-control input-sm txtUnidad" value="<?php echo $prod->UnidadMedida_id; ?>" placeholder="UND" readonly="readonly" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="PrecioUnitario[]" type="text" class="form-control input-sm price text-right txtPrecioUnitario" value="<?php echo $prod->Precio; ?>" placeholder="Precio Unitario" maxlength="10" title="PC: S/. <?php echo $prod->Precio; ?>" data-compra="<?php echo $prod->Precio; ?>" readonly="readonly">
			  				</td>
			  				<td>
			  					<input autocomplete="off" type="text" class="form-control input-sm price text-right txtTotal" value="" placeholder="Total" readonly="readonly" />
			  				</td>
			  			</tr>
			  			<?php
			  				}
			  			?>
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
		  				
		  				<tr>
			  				<td class="text-right" style="background:#eee;padding:2px 4px;"><?php echo $i; ?></td>
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
			  					<input autocomplete="off" name="PrecioUnitario[]" type="text" class="form-control input-sm price text-right txtPrecioUnitario" value="0.00" placeholder="Precio Unitario" maxlength="10" readonly="readonly">
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
			  			<tr id="trDsc">
			  				<th class="text-right" colspan="6">
			  					Descuento (%)
									<input id="txtDsc" name="Dsc" style="width:54px;margin-left:10px;" class="form-control text-right input-sm price pull-right" value="0" />
			  				</th>
			  				<td class="text-right">
									<input autocomplete="off" id="txtDscT" name="totalDsc" readonly="readonly" class="form-control text-right input-sm" value="0.00" /><br/>
									
			  				</td>
			  			</tr>
						<tr id="trDsc2">
							<th class="text-right" colspan="6">
			  					Descuento Fijo (S/.)
			  				</th>
							<th class="text-right" colspan="6">
								<input autocomplete="off" id="txtDscT2" name="totalDsc2" class="form-control text-right input-sm" value="0"/>
							</th>
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
			  			<tr id="trAdela">
			  				<th class="text-right" colspan="6">
			  					Adelanto
			  				</th>
			  				<td class="text-right">
					
									<input autocomplete="off" id="ped_adela" name="ped_adela" class="form-control text-right price input-sm" value="0"/>
			  				</td>
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
	<div style="clear:both;margin-bottom:15px;"></div>

	<div class="col-md-4">
			<div class="form-group">
				<label for="">Tipo de Pago:</label>
				<select name="tipo_pago" id="tipo_pago" class="form-control required">
					<option value="0">- Seleccione -</option>
					<option value="1">Contado</option>
					<option value="2">Crédito</option>
				</select>
			</div>
			<table id="cuotas" style="display: none;">
				<thead>
					<tr>
						<th>Fecha</th>
						<th>Monto</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<input type="date" name="fecha_cuota[]" class="form-control" value="<?php echo date("Y-m-d");?>">
						</td>
						<td>
							<input type="number" name="monto_cuota[]" class="form-control" value="0">
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td>
							<button id="btnaddcuota" type="button" class="btn btn-info">Agregar Cuota</button>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>			
		<div class="col-md-8 text-right">
			<input type="checkbox" name="generar_deuda" value="1" checked> <label style="color:#000; font-size:16px;" >Generar Deuda</label><br/><input type="checkbox" name="factura_gratuita" value="1"> <label style="color:#000; font-size:16px;">Factura Gratuita</label><br/><br/>
		
			<button type="submit" class="btn btn-lg btn-success submit-ajax-button cpeconfirm">Guardar</button>
		</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>
<!-- Modal Add Lente -->
<div id="modalcoddsc" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Agregar Código Dscto.</h4>
      </div>
      <div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="input-group">
							<input type="text" id="cupondscto" class="form-control">
							<span class="input-group-btn">
								<button class="btn btn-warning" type="button" id="validar"><i class="fas fa-search"></i> <b>Validar</b></button>
							</span>
						</div>
					</div>
					<div class="col-md-12">
						<div id="resvalidacion" style="text-align:center;">
						</div>
					</div>
				</div>
			</div>
      <div class="modal-footer">
		<!-- FOOTER -->
      </div>
    </div>

  </div>
</div>

<!-- Modal Add Lente -->
<div id="addLente" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Agregar Lente</h4>
      </div>
      <div class="modal-body">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label style="font-size: 1.2em; color:#000;">1. Diseño</label>
							<select class="form-control" name="disenio_lente" id="disenio_lente">
								<option hidden>Selecccione</option>
								<?php
									foreach($disenio as $d){
										echo '<option value="'.$d->disenio.'">'.$d->disenio.'</option>';
									}
								?>
							</select>

							<input type="hidden" name="material_lente" id="material_lente" class="form-control">
							<input type="hidden" name="descripcion_lente_hide" id="material_lente_hide" class="form-control">
							<input type="hidden" name="id_material" id="id_material" class="form-control">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label style="font-size: 1.2em; color:#000;">2. Fabricación</label>
							<select class="form-control" name="fabricacion_lente" id="fabricacion_lente">
								<option hidden>Selecccione</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label style="font-size: 1.2em; color:#000;">3. Material</label>
							<select class="form-control" name="material_lente2" id="material_lente2">
								<option hidden>Selecccione</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label style="font-size: 1.2em; color:#000;">4. Serie</label>
							<select class="form-control" name="serie_lente" id="serie_lente">
								<option hidden>Selecccione</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label style="font-size: 1.2em; color:#000;">5. Tratamiento</label>
							<select class="form-control" name="tratamiento_lente" id="tratamiento_lente">
								<option hidden>Selecccione</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label style="font-size: 1.2em; color:#000;">6. Nombre</label>
							<select class="form-control" name="nombre_lente" id="nombre_lente">
								<option hidden>Selecccione</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label style="font-size: 1.2em; color:#000;">7. Fotocromat.</label>
							<select class="form-control" name="fotocroma_lente" id="fotocroma_lente">
								<option hidden>Selecccione</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label style="font-size: 1.2em; color:#000;">8. Color Fotocromat.</label>
							<select class="form-control" name="color_fotocroma_lente" id="color_fotocroma_lente">
								<option hidden>Selecccione</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label style="font-size: 1.2em; color:#000;">9. Precio</label>
							<input type="text" class="form-control" name="precio_lente" id="precio_lente" readonly>
						</div>
					</div>
				</div>
			</div>
      <div class="modal-footer">
        <button type="button" id="btnaddprfm" class="btn btn-success"><i class="fas fa-plus"></i> Agregar</button>
      </div>
    </div>

  </div>
</div>


<div id="modalclie" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nuevo Cliente</h4>
      </div>
      	<div class="modal-body">
         	<!--<div class="form-group">
				<label>RUC </label>
				<input autocomplete="off" maxlength="11" name="Ruc" type="text" class="form-control" placeholder="Ingrese el RUC" id="rucemp"/>
			</div>-->
			<div class="form-group">
				<label>Nombre/Razon Social (*)</label>
				<input id="Nombre" autocomplete="off" name="Nombre" type="text" class="form-control rsocialn required" placeholder="Nombre del cliente" />
				<div id="resulcond"></div>
			</div>

			<div class="form-group">
				<label>Tipo Doc</label>
				<select name="tipo_doc" id="tipo_doc" class="form-control">
					<option value="0">Seleccione</option>
					<option value="1">DNI</option>
					<option value="4">RUC</option>
					<option value="2">C.E.</option>
					<option value="3">Pasaporte</option>
				</select>
			</div>

			<div class="form-group">
				<label>Nro. Doc.</label>
				<input autocomplete="off" name="nro_doc" id="nro_doc" type="text" class="form-control" placeholder="Ingrese Nro. de Documento"/>
			</div>

			<!--<div class="form-group">
				<label>DNI (Sólo si es persona natural)</label>
				<input autocomplete="off" maxlength="8" name="Dni" id="dnicli" type="text" class="form-control" placeholder="Ingrese el DNI"/>
			</div>-->

			<div class="form-group">
				<label>Télefono Principal</label>
				<input autocomplete="off" name="Telefono1" id="Telefono1" type="text" class="form-control" placeholder="Télefono Principal"/>
			</div>

			<div class="form-group">
				<label>Trabajo/Ocupación</label>
				<input autocomplete="off" name="trabajo" id="trabajo" type="text" class="form-control" placeholder="Trabajo u ocupación"/>
			</div>
		
			<div class="form-group">
				<label>Dirección</label>
				<textarea name="Direccion" id="direcli" class="form-control" placeholder="Dirección"></textarea>
			</div>
			<div class="col-md-4">
				<div class="form-group">
				<label>Departamento</label>
				<input autocomplete="off" name="Departamento" id="depcli" type="text" class="form-control" placeholder="Departamento" />
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
				<label>Ciudad</label>
				<input autocomplete="off" name="Ciudad" id="ciucli"  type="text" class="form-control" placeholder="Ciudad" />
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
				<label>Distrito</label>
				<input autocomplete="off" name="Distrito" id="discli" type="text" class="form-control" placeholder="Distrito"/>
				</div>
			</div>
		<button type="button" class="btn btn-success btn-block" id="nuecliebtn">Guardar Cliente</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>

<script>
    $("#sltComprobante").on("change", function() {
		var cliente = $("#hdCliente_id");
		var input = $("#txtCliente");
		var tipoDoc = $("#sltComprobante");
		var medioPago = $("#sltMedioPago");
		//cliente.val(null);
		//input.attr('data-name', "");
		//input.val("");
		//$("#txtRuc").val("");
		//$("#txtDireccion").val("");
		if (tipoDoc.val() == 4) {
			$("#sltMedioPago option:not([value='deposito'], [value='0'])").remove();
		} else {
			$("#sltMedioPago option:not([value='deposito'], [value='0'])").remove();
			var nuevasOpciones = [{
					value: "pendiente",
					text: "Pendiente de Pago"
				},
				{
					value: "efectivo",
					text: "Efectivo"
				},
				{
					value: "visa",
					text: "Visa"
				},
				{
					value: "MC",
					text: "Mastercard"
				},
				{
					value: "estilos",
					text: "Estilos"
				},
				{
					value: "yape",
					text: "Yape"
				}
			];

			// Agregar las nuevas opciones al select
			nuevasOpciones.forEach(function(opcion) {
				var nuevaOpcion = $("<option>", {
					value: opcion.value,
					text: opcion.text
				});
				medioPago.append(nuevaOpcion);
			});
		}

		if (tipoDoc.val() != 3) {
			$("#checkBoxId").prop("checked", false);
			$("#checkBoxId").hide();
			$("#labelCheckboxId").hide();
		} else {
			$("#checkBoxId").show();
			$("#labelCheckboxId").show();
			$("#checkBoxId").prop("checked", false);
		}

		//sltMedioPago
	});
	/** Tipo de pago */

	$("#tipo_pago").on("change", function(){
		var tipo = $("#tipo_pago").val();
		if(tipo == 1){
			$("#cuotas").hide();
		}else{
			$("#cuotas").show();
		}
	});

	$("#btnaddcuota").on('click', function(){
		$('#cuotas').append('<tr><td><input type="date" name="fecha_cuota[]" class="form-control" value="<?php echo date("Y-m-d");?>"></td><td><input type="number" name="monto_cuota[]" class="form-control" value="0"></td></tr>');
	});

	$("#validar").on("click", function(){
		var cupon = $("#cupondscto").val();
		$.get(base_url('services/validarCupon/'+cupon), function( data ) {
			$("#resvalidacion").html(data.valido);
			if(data.success == true){
				if(data.valido == 0){
					$("#resvalidacion").html('<div class="alert alert-danger" style="font-size: 1.2em; margin-top:10px;">El cupón ingresado se encuentra <strong>vencido</strong>.</div>');
				}else if(data.usado == 1){
					$("#resvalidacion").html('<div class="alert alert-danger" style="font-size: 1.2em; margin-top:10px;">El cupón ingresado ha sido <strong>utilizado previamente</strong>.</div>');
				}else{
					$("#resvalidacion").html('<div class="alert alert-success" style="font-size: 1.2em; margin-top:10px;">El cupón es <strong>válido</strong>. El descuento ha sido agregado.</di>');

					$("#codigo_dscto").val(data.cupon);
					$("#txtDsc").val("20").keyup();
				}
			}else{
				$("#resvalidacion").html('<div class="alert alert-danger" style="font-size: 1.2em; margin-top:10px;">El cupón <strong>no existe</strong>.</div>');
			}

		}, 'json');
	});

	$("#btnaddprfm").on("click", function(e){
		alert("asddsa");
		var idmat = $("#material_lente").val();
		var precio_lent = $("#precio_lente").val();
		if(idmat==""){
			$.toast({
				    text: "Dede seleccionar todas las opciones para agregar un lente a esta venta.", // Text that is to be shown in the toast
				    heading: 'Error', // Optional heading to be shown on the toast
				    icon: 'error', // Type of toast icon
				    showHideTransition: 'fade', // fade, slide or plain
				    allowToastClose: true, // Boolean value true or false
				    hideAfter: 4000, // false to make it sticky or number representing the miliseconds as time after which toast needs to be hidden
				    stack: 5, // false if there should be only one toast at a time or a number representing the maximum number of toasts to be shown at a time
				    position: 'top-right', // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values
				    textAlign: 'left',  // Text alignment i.e. left, right or center
				    loader: true,  // Whether to show loader or not. True by default
				    loaderBg: '#9EC600',  // Background color of the toast loader
				    beforeShow: function () {}, // will be triggered before the toast is shown
				    afterShown: function () {}, // will be triggered after the toat has been shown
				    beforeHide: function () {}, // will be triggered before the toast gets hidden
				    afterHidden: function () {}  // will be triggered after the toast has been hidden
				});
		}else{
			var trb = $("#tablaped tbody tr").find(".hdProducto_id");

			$( trb ).each(function( index ) {
				var tr = $(this).closest('tr');
				if(tr.find('.hdProducto_id').val()==''){
					tr.find('.txtProducto').attr('data-id', "00");
					tr.find('.txtProducto').attr('data-name', idmat);
					tr.find('.txtProducto').val(idmat);
					tr.find('.hdProducto_id').val("00");

					tr.find('.hdPrecioUnitarioCompra').val(precio_lent);
					tr.find('.txtCantidad').val("1");
					tr.find('.hdTipo').val("1");
					tr.find('.txtUnidad').val("UND");
					tr.find('.txtPrecioUnitario').val(precio_lent);
					tr.find('.txtPrecioUnitario').attr('data-compra', precio_lent);
					CalcularComprobante();
					$('#addLente').modal('toggle');
					return false;
				}	
			});
		}
	});
	$( "#rucemp" ).keyup(function( event ) {
		var ruc = $("#rucemp").val();

		if(ruc.length == 11){
			var form = $(this).closest("form");
			var block = $('<div class="block-loading" id="bloquecarga">');
        	form.prepend(block);

			$.get(base_url('services/getSunatData/'+ruc), function( data ) {
				if(data.success==true){
					var rs = data.nombre_o_razon_social;
			  		var dir = data.direccion_completa;
			  		var dep = data.departamento;
			  		var ciu = data.provincia;
			  		var dis = data.distrito;
			  		var condicionDom = data.condicion_de_domicilio;
			  		var estadoCont = data.estado_del_contribuyente;
			  		$(".rsocialn").val(rs);
			  		$("#direcli").val(dir);
			  		$("#depcli").val(dep);
			  		$("#ciucli").val(ciu);
			  		$("#discli").val(dis);
			  		$("#resulcond").html("");
			  		if(condicionDom=="HABIDO" && estadoCont=="ACTIVO"){
			  			$("#resulcond").append('<div class="alert alert-success">El contribuyente se encuentra <strong>Habido</strong> y <strong>Activo</strong>.</div>');
			  		}else{
			  			$("#resulcond").append('<div class="alert alert-danger">El contribuyente se encuentra <strong>'+condicionDom+'</strong> y su estado es: <strong>'+estadoCont+'</strong>.</div>');
			  		}

			  		$("#bloquecarga").remove();
				}else{
					alert(data.error);
					$("#bloquecarga").remove();
				}
			  	
			}, 'json');

		}
	});

	$( "#nro_doc" ).keyup(function( event ) {
		var nrodoc = $("#nro_doc").val();
		var tipo_doc = $("#tipo_doc").val();
		var doc_len = nrodoc.length;

		if(doc_len == 8 && tipo_doc == 1){
			var form = $(this).closest("form");
			var block = $('<div class="block-loading" id="bloquecarga">');
        	form.prepend(block);

			$.get(base_url('services/getReniec/'+nrodoc), function( data ) {
				if(data.success==true){
					//alert(data.result.DNI);
					var nom = data.result.Nombres+' '+data.result.Apellidos;
					$(".rsocialn").val(nom);
					$("#depcli").val(data.result.Departamento);
			  		$("#ciucli").val(data.result.Provincia);
			  		$("#discli").val(data.result.Distrito);
			  		$("#bloquecarga").remove();
				}else{
					//alert(data.error);
					$("#bloquecarga").remove();
				}
			  	
			}, 'json');

		}else if(doc_len == 11 && tipo_doc == 4){
			var form = $(this).closest("form");
			var block = $('<div class="block-loading" id="bloquecarga">');
        	form.prepend(block);

			$.get(base_url('services/getSunatData/'+nrodoc), function( data ) {
				if(data.success==true){
					var rs = data.nombre_o_razon_social;
			  		var dir = data.direccion_completa;
			  		var dep = data.departamento;
			  		var ciu = data.provincia;
			  		var dis = data.distrito;
			  		var condicionDom = data.condicion_de_domicilio;
			  		var estadoCont = data.estado_del_contribuyente;
			  		$(".rsocialn").val(rs);
			  		$("#direcli").val(dir);
			  		$("#depcli").val(dep);
			  		$("#ciucli").val(ciu);
			  		$("#discli").val(dis);
			  		$("#resulcond").html("");
			  		if(condicionDom=="HABIDO" && estadoCont=="ACTIVO"){
			  			$("#resulcond").append('<div class="alert alert-success">El contribuyente se encuentra <strong>Habido</strong> y <strong>Activo</strong>.</div>');
			  		}else{
			  			$("#resulcond").append('<div class="alert alert-danger">El contribuyente se encuentra <strong>'+condicionDom+'</strong> y su estado es: <strong>'+estadoCont+'</strong>.</div>');
			  		}

			  		$("#bloquecarga").remove();
				}else{
					alert(data.error);
					$("#bloquecarga").remove();
				}
			  	
			}, 'json');

		}
	});

	$('#nuecliebtn').on('click', function(){
		var Nombre = encodeURIComponent($('#Nombre').val());
		var Ruc = $('#rucemp').val();
		var Dni = $('#dnicli').val();
		var Telefono1 = $('#Telefono1').val();
		var fecha_nac = $('#fecha_nac').val();
		var Direccion = $('#direcli').val();
		var Distrito = $('#discli').val();
		var Ciudad = $('#ciucli').val();
		var trabajo = $('#trabajo').val();
		var Departamento = $('#depcli').val();

		var tipo_doc = $("#tipo_doc").val();
		var nro_doc = $("#nro_doc").val();

		//console.log(nro_doc)

		var doc_len = nro_doc.length;

		if(tipo_doc == 0 && doc_len==0){
			errorToast("Debe seleccionar un tipo de documento.");
			return false;
		}

		if(tipo_doc == 1 && doc_len < 8){
			errorToast("Debe ingresar un número de DNI válido.");
			return false;
		}

		if(tipo_doc == 4 && doc_len < 11){
			errorToast("Debe ingresar un número de RUC válido.");
			return false;
		}

		$.post('<?php echo base_url('index.php/mantenimiento/clientecomp');?>', 'Nombre='+Nombre+'&Ruc='+Ruc+'&Dni='+Dni+'&Telefono1='+Telefono1+'&Distrito='+Distrito+'&Ciudad='+Ciudad+'&Departamento='+Departamento+'&Direccion='+Direccion+'&trabajo='+trabajo+'&tipo_doc='+tipo_doc+'&nro_doc='+nro_doc, function(){
			$('#modalclie').modal('toggle'); 
			$.toast({
				text: "Paciente creado exitosamente!",
				heading: 'Listo!',
				icon: 'success',
				showHideTransition: 'fade',
				allowToastClose: true,
				hideAfter: 5000,
				stack: 5,
				position: 'top-right',
				textAlign: 'left',
				loader: true,
				loaderBg: '#9EC600'
			});
	    }, 'json');
	});

	function errorToast(str){
		$.toast({
				text: str,
				heading: 'Error',
				icon: 'error',
				showHideTransition: 'fade',
				allowToastClose: true,
				hideAfter: 5000,
				stack: 5,
				position: 'top-right',
				textAlign: 'left',
				loader: true,
				loaderBg: '#9EC600'
			});
	}

</script>
<script type="text/javascript" src="<?php echo base_url('assets/scripts/venta/compras.js'); ?>"></script>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>
				<?php 
					echo 'Compra #';
				?>
			</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li><a href="<?php echo base_url('index.php/almacen/index'); ?>">Almacén</a></li>
		  <li class="active">Compra</li>
		</ol>
		<div class="row">
			<div class="col-md-12">
				<?php echo form_open('almacen/guardarcompra', array('class' => 'upd')); ?>
				
				<div class="well well-sm">(*) Campos obligatorios</div>
				<div class="row">
					<div class="col-md-6">
					  <div class="form-group">
					    <label>Proveedor <span id="spClienteRequerido">(*)</span></label>
					  
						    <div class="input-group">
						      <input id="txtCliente" autocomplete="off" name="ClienteNombre" type="text" class="form-control required ui-autocomplete-input" placeholder="Nombre del Proveedor" value="" maxlenght="100" data-name="">
						      <span class="input-group-btn">
						        <button id="btnClienteLimpiar" class="btn btn-default" type="button">
						        	<span class="glyphicon glyphicon-remove"></span>
						        </button>
						      </span>
						    </div>
    						<input id="hdCliente_id" type="hidden" name="Cliente_id" value="" />
						  </div>
						</div>
					<div class="col-md-4">
					  <div class="form-group">
					    <label>Guía/Factura</label>
					    
							<input type="text" autocomplete="off" name="guiafact" type="text" class="form-control required" placeholder="Guía de Remisión / Factura" value="" />
					    
					  </div>
					</div>
					<div class="col-md-2">
					  <div class="form-group">
					    <label>Fecha</label>
					    
							<input autocomplete="off" name="FechaEmitido" type="text" class="form-control required datepicker" placeholder="Fecha de Emisión" value="<?php echo date(DATE); ?>" maxlenght="10" />
					    
					  </div>
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
				  		<th style="width:100px;">UDM</th>
				  		<th class="text-right" style="width:140px;">Precio Compra</th>
				  		<th class="text-right" style="width:140px;">Precio Total</th>
				  	</tr>
			  		</thead>
			  		<tbody>
		  			<?php for($i=0; $i<10; $i++){?>
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
			  					<input autocomplete="off" name="Cantidad[]" type="text" class="form-control input-sm txtCantidad price" value="" placeholder="Cantidad"  maxlength="5"/>
			  					<input name="Tipo[]" type="hidden" class="hdTipo" value="" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="UnidadMedida_id[]" type="text" class="form-control input-sm txtUnidad" value="" placeholder="UND" readonly="readonly" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="PrecioUnitario[]" type="text" class="form-control input-sm price text-right txtPrecioUnitario" value="" placeholder="Precio Unitario" maxlength="5" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" type="text" class="form-control input-sm price text-right txtTotal" value="" placeholder="Total" readonly="readonly" />
			  				</td>
			  			</tr>
			  	<?php }?>
			  		</tbody>
			  		<tfoot style="background:#eee;">
					  	
			  			<tr>
			  				<td colspan="6">
							  <div class="form-group">
								<label>Comentario</label>
							    <textarea name="Glosa" rows="2" cols="" class="form-control"></textarea>
							  </div>
			  				</td>
			  			</tr>

			  			<tr>
			  				<th class="text-right" colspan="5">Total (<?php echo $this->conf->Moneda_id; ?>)</th>
			  				<td class="text-right">
									<input autocomplete="off" id="txtTotal" readonly="readonly" class="form-control text-right input-sm" name="totalC" value="0.00" />
			  				</td>
			  			</tr>
			  		</tfoot>
				  </table>
	<div style="clear:both;margin-bottom:15px;"></div>
				  <div class="clearfix text-right">
					  <button type="submit" class="btn btn-info submit-ajax-button">Guardar</button>
				  </div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>


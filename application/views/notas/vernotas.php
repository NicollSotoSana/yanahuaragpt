<script type="text/javascript" src="<?php echo base_url('assets/scripts/venta/comprobante.js'); ?>"></script>
<script>
	var ComprobanteTipo = 0;
	
</script>
<?php //array_debug($comprobante); ?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<div class="pull-right">
			  	<?php	if($comprobante!=null): ?>
			  <?php if($comprobante->ComprobanteTipo_id == 1 || $comprobante->ComprobanteTipo_id == 2 ): ?>
			  		<?php if(empty($comprobante->link_pdf)){ ?>
					  	<span id="res"></span>
					  	<a id="generarDoc" class="btn btn-info" title="Generar Comprobante SUNAT">
							<span class="glyphicon glyphicon-sort"></span>
						</a>
					<?php }else{?>
						<a href="https://guillentamayo.server5.cpe-facturacioncdperu.com/print/document/<?php echo $comprobante->external_id;?>/ticketguillen" target="_blank" class="btn btn-danger"><b><span class="glyphicon glyphicon-search"></span> PDF</b></a>
						<a href="<?php echo $comprobante->link_xml;?>" target="_blank" class="btn btn-success"><b><span class="glyphicon glyphicon-save"></span> XML</b></a>
						<?php if(!empty($comprobante->link_cdr)){
							echo '<a href="'.$comprobante->link_cdr.'" target="_blank" class="btn btn-primary"><b><span class="glyphicon glyphicon-save"></span> CDR</b></a>';
						}elseif(isset($comprobante->ComprobanteTipo_id) && $comprobante->ComprobanteTipo_id==3){
							echo '<button class="btn btn-info" id="enviarSunat" onclick="enviarSunat('.$comprobante->id.')"><b><span class="glyphicon glyphicon-sort"></span> Enviar a Sunat</b></button>';
						}
						?>
						
					<?php }?>
				<?php endif; ?>
			  	<?php if($comprobante->Correlativo != null AND ($comprobante->Estado == 2 || $comprobante->Estado == 3 )):?>
				<a title="Nuevo Comprobante" class="btn btn-success" href="<?php echo base_url('index.php/ventas/comprobante'); ?>">
					<span class="glyphicon glyphicon-file"></span>
				</a>
				<?php endif; ?>
			  <?php endif; ?>
			
				
			</div>
			<h1>
				<?php 
						echo 'Nota: '.$comprobante->Serie."-".$comprobante->Correlativo;
					
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
				
				<div class="well well-sm">(*) Campos obligatorios</div>
				<div class="row">
					<div class="col-md-2">
					  <div class="form-group">
					    <label>Comprobante (*)</label>
					    <select id="sltComprobante" name="ComprobanteTipo_id" class="form-control required " disabled="disabled" >
                            <option value="1" selected="selected">Nota de Crédito</option>
                        </select>
					  </div>
					</div>
					<div class="col-md-4">
					  <div class="form-group">
					    <label>Cliente <span id="spClienteRequerido">(*)</span></label>
					  
						    <div class="input-group">
						      <input id="txtCliente" autocomplete="off" name="ClienteNombre" type="text" class="form-control required ui-autocomplete-input" placeholder="Nombre del Cliente" value="<?php echo $comprobante->ClienteNombre; ?>" maxlenght="100" data-name="<?php echo $comprobante->ClienteNombre; ?>" disabled="disabled" >
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
						<input id="motivo_sustento" type="text" autocomplete="off" id="motivo_sustento" name="motivo_sustento" class="form-control" value="<?php echo $comprobante->motivo_anulacion; ?>" disabled="disabled" />
					  </div>
				  </div>
				  <div class="col-md-4">
	                  <div class="form-group">
					    <label>Tipo de Nota<span id="spDireccionRequerido">(*)</span></label>
						<select class="form-control" name="tiponota" readonly="readonly">
							<option value="01">Anulación de la Operación</option>
							<option value="02">Anulación por error en el RUC</option>
							<option value="03">Corrección por error en la descripción</option>
							<option value="04">Descuento Global</option>
							<option value="05">Descuento por Item</option>
							<option value="06">Devolución Total</option>
							<option value="07">Devolución por Item</option>
							<option value="08">Bonificación</option>
							<option value="09">Disminución en el valor</option>
						</select>
					  </div>
				  </div>
                  <input type="hidden" value="<?php echo $comprobante->external_id; ?>" name="external_id" id="external_id">

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
				    <?php if($comprobante != false): ?>
					  	<?php foreach($comprobante->Detalle as $k => $c): ?>
				  			<tr>
				  				<td class="text-right" style="background:#eee;padding:2px 4px;"><?php echo $k+1; ?></td>
				  				<td><span class="glyphicon glyphicon-chevron-right"></span></td>
				  				<td><?php echo $c->ProductoNombre; ?></td>
				  				<td><?php echo number_format($c->Cantidad, 2); ?></td>
				  				<td><?php echo $c->UnidadMedida_id; ?></td>
				  				<td class="text-right"><?php 
				  				if($comprobante->moneda=="usd" || $comprobante->moneda=="Usd"){
				  					$pre=$c->PrecioUnitario/$comprobante->tipo_cambio;
				  					echo number_format($pre, 2); 
				  				}else{
				  					echo number_format($c->PrecioUnitario, 2); 
				  				}	?></td>
				  				<td class="text-right">
				  					<?php 
				  				if($comprobante->moneda=="usd" || $comprobante->moneda=="Usd"){
				  					$preT=$c->PrecioTotal/$comprobante->tipo_cambio;
				  					echo number_format($preT, 2); 
				  				}else{
				  					echo number_format($c->PrecioTotal, 2); 
				  				}	?></td>
				  			</tr>
			  			<?php endforeach; ?>
			        <?php endif; ?>
					<?php for($i= isset($comprobante) ? count($comprobante->Detalle)+1 : 1; $i <= $this->conf->Lineas; $i++):?>
						<?php if($comprobante == false){ ?>
			  			<tr>
			  				<td class="text-right numro" style="background:#eee;padding:2px 4px;"><?php echo $i; ?></td>
			  				<td>
			  					<button type="button" class="btn btn-default btn-sm btnProductoQuitar" title="Remover este item">
			  						<span class="glyphicon glyphicon-remove"></span>
			  					</button>
			  				</td>
			  				<td>
			  					<input id="txtProducto_<?php echo $i; ?>" data-id="0" autocomplete="off" name="ProductoNombre[]" type="text" class="form-control input-sm txtProducto" value="" placeholder="Escriba el nombre de un producto" />
			  					<input name="Producto_id[]"  type="hidden" class="hdProducto_id" value="" />
			  					<input name="cant_eq[]"  type="hidden" class="cant_eq" value="" />
			  					<input name="precio_org[]"  type="hidden" class="precio_org" value="" />
			  				</td>
			  				<td>
								<input name="PrecioUnitarioCompra[]"  type="hidden" class="hdPrecioUnitarioCompra" value="" />
			  					<input autocomplete="off" name="Cantidad[]" type="text" class="form-control input-sm txtCantidad price" value="" placeholder="Cantidad"  maxlength="5"/>
			  					<input name="Tipo[]"  type="hidden" class="hdTipo" value="" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="UnidadMedida_id[]" type="text" class="form-control input-sm txtUnidad" value="" placeholder="UND" readonly="readonly" />
			  					<!--<select name="UnidadMedida_id[]" class="form-control input-sm txtUnidad" readonly="true">
			  						
			  					</select>-->
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="PrecioUnitario[]" type="text" class="form-control input-sm price text-right txtPrecioUnitario" value="" placeholder="Precio Unitario" maxlength="10" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" type="text" class="form-control input-sm price text-right txtTotal" value="" placeholder="Total" readonly="readonly" />
			  				</td>
			  			</tr>

			  			<?php }else if($comprobante->Estado != 4){ ?>
			  			<tr>
			  				<td class="text-right" style="background:#eee;padding:2px 4px;"><?php echo $i; ?></td>
			  				<td></td>
			  				<td></td>
			  				<td></td>
			  				<td></td>
			  				<td></td>
			  				<td></td>
			  				<td></td>
			  			</tr>

			  			<?php }else { ?>
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
			  					<input autocomplete="off" name="PrecioUnitario[]" type="text" class="form-control input-sm price text-right txtPrecioUnitario" value="" placeholder="Precio Unitario" maxlength="10" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" type="text" class="form-control input-sm price text-right txtTotal" value="" placeholder="Total" readonly="readonly" />
			  				</td>
			  			</tr>
			  			<?php } ?>
		  			<?php endfor; ?>
		  			<?php if($comprobante == false){ ?>
		  				<tr>
		  					<td colspan="7"><h3>Productos Fuera de Inventario</h3></td>
		  				</tr>
		  				
		  				<tr>
			  				<td class="text-right numro" style="background:#eee;padding:2px 4px;"><?php echo $i; ?></td>
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
			  					<input autocomplete="off" name="Cantidad[]" type="text" class="form-control input-sm txtCantidad price" value="0" placeholder="Cantidad"  maxlength="5"/>
			  					<input name="Tipo[]" type="hidden" class="hdTipo" value="1" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="UnidadMedida_id[]" type="text" class="form-control input-sm txtUnidad" value="UND" placeholder="UND" readonly="readonly" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="PrecioUnitario[]" type="text" class="form-control input-sm price text-right txtPrecioUnitario" value="0.00" placeholder="Precio Unitario" maxlength="5">
			  				</td>
			  				<td>
			  					<input autocomplete="off" type="text" class="form-control input-sm price text-right txtTotal" value="" placeholder="Total" readonly="readonly" />
			  				</td>
			  			</tr>
			  			<tr>
			  				<td class="text-right numro" style="background:#eee;padding:2px 4px;"><?php echo $i = $i+1; ?></td>
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
			  					<input autocomplete="off" name="Cantidad[]" type="text" class="form-control input-sm txtCantidad price" value="0" placeholder="Cantidad"  maxlength="5"/>
			  					<input name="Tipo[]" type="hidden" class="hdTipo" value="1" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="UnidadMedida_id[]" type="text" class="form-control input-sm txtUnidad" value="UND" placeholder="UND" readonly="readonly" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="PrecioUnitario[]" type="text" class="form-control input-sm price text-right txtPrecioUnitario" value="0.00" placeholder="Precio Unitario" maxlength="5">
			  				</td>
			  				<td>
			  					<input autocomplete="off" type="text" class="form-control input-sm price text-right txtTotal" value="" placeholder="Total" readonly="readonly" />
			  				</td>
			  			</tr>
			  			<tr>
			  				<td class="text-right numro" style="background:#eee;padding:2px 4px;"><?php echo $i = $i+1; ?></td>
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
			  					<input autocomplete="off" name="Cantidad[]" type="text" class="form-control input-sm txtCantidad price" value="0" placeholder="Cantidad"  maxlength="5"/>
			  					<input name="Tipo[]" type="hidden" class="hdTipo" value="1" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="UnidadMedida_id[]" type="text" class="form-control input-sm txtUnidad" value="UND" placeholder="UND" readonly="readonly" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="PrecioUnitario[]" type="text" class="form-control input-sm price text-right txtPrecioUnitario" value="0.00" placeholder="Precio Unitario" maxlength="5">
			  				</td>
			  				<td>
			  					<input autocomplete="off" type="text" class="form-control input-sm price text-right txtTotal" value="" placeholder="Total" readonly="readonly" />
			  				</td>
			  			</tr>
		  			<?php }?>
		  			
			  	
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
									<input autocomplete="off" id="txtDscT2" name="totalDsc2" class="form-control text-right input-sm" value="0"/>
			  				</td>
			  			</tr>
			  			<tr id="trAdela">
			  				<th class="text-right" colspan="6">
			  					Adelanto
			  				</th>
			  				<td class="text-right">
								<?php if($comprobante == null){ ?>
									<input autocomplete="off" id="ped_adela" name="ped_adela" class="form-control text-right price input-sm" value="0"/>
								
			  					<?php }else if($comprobante->Estado != 4) {

				  					if(isset($comprobante->adelanto)){
				  						echo '<input autocomplete="off" id="ped_adela"  name="ped_adela"  class="form-control text-right input-sm" value="'.number_format($comprobante->adelanto, 2).'" readonly/>';
				  					}
		  							
			  					}else{ ?>
			  						<input autocomplete="off" id="ped_adela"  name="ped_adela"  class="form-control text-right input-sm" value="0" />
			  					<?php } ?>
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
			  			<tr id="letras" style="display:none;">
			  				<td colspan="3">
							  <div class="form-group">
								<label>Letra 1 Fecha: </label>
							    <input name="letra_fecha" type="date" class="form-control" style="width:300px;" value="<?php echo (!empty($comprobante->letra1_fecha)) ? $comprobante->letra1_fecha : "";?>"><br/>
							    <label>Letra 1 Monto: </label>
							    <input name="letra_monto" type="text" class="form-control" style="width:150px;" value="<?php echo (!empty($comprobante->letra1_monto)) ? $comprobante->letra1_monto : "";?>">
							  </div>
			  				</td>
			  				<td colspan="4">
							  <div class="form-group">
								<label>Letra 2 Fecha: </label>
							    <input name="letra2_fecha" type="date" class="form-control" style="width:300px;" value="<?php echo (!empty($comprobante->letra2_fecha)) ? $comprobante->letra2_fecha : "";?>"><br/>
							    <label>Letra 2 Monto: </label>
							    <input name="letra2_monto" type="text" class="form-control" style="width:150px;" value="<?php echo (!empty($comprobante->letra2_monto)) ? $comprobante->letra2_monto : "";?>">
							  </div>
			  				</td>
			  			</tr>
			  			
			  		</tfoot>
				  </table>

	<div style="clear:both;margin-bottom:15px;"></div>
			</div>
		</div>
	</div>
	<?php if(!empty($comprobante->link_pdf)){ ?>
		<object data="<?php echo $comprobante->link_pdf;?>" type="application/pdf" width="100%" height="500px"> 
			<p>No biggie... you can <a href="<?php echo $comprobante->link_pdf;?>">click here to
						  download the PDF file.</a></p>  
		</object>
						 
	<?php }?>
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
$("#generarDoc").on("click", function(e){
		$("#generarDoc").prop("disabled",true);
		$("#generarDoc").attr("readonly", true); 
		$("#generarDoc").html('Enviando...');
		$.ajax({
			type: "GET",
			url: "<?php echo isset($comprobante->id) ? base_url("notas/enviarSunat/".$comprobante->id):"";?>",
			//data: "archivo="+archi,
			//headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			dataType: "json",
			success: function(data) {
				$("#generarDoc").fadeOut("normal", function() {
				        $(this).remove();
				    });
				if(links.pdf != undefined){
					location.reload();
				}else{
					alert("Error: "+ response.description);
				}
				
			},
			error: function() {
				alert('Ocurrio un error: '+data.error);
			}
		});
	});
</script>
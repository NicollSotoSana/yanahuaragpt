<script type="text/javascript" src="<?php echo base_url('assets/scripts/venta/comprobante.js'); ?>"></script>
<script>
	var ComprobanteTipo = 0;
	<?php if($comprobante != null): ?>
		ComprobanteTipo = '<?php echo $comprobante->ComprobanteTipo_id;?>';
	<?php endif; ?>
</script>
<?php //array_debug($conformidad_montura); ?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<div class="pull-right">
			  <?php	if($comprobante!=null): ?>
			  	<?php if($comprobante->ComprobanteTipo_id == 2 || $comprobante->ComprobanteTipo_id == 3 ): ?>
			  		<?php if(empty($comprobante->link_pdf)){ ?>
					  	<span id="res"></span>
					  	<a id="generarDoc" class="btn btn-info" title="Generar Comprobante SUNAT">
							<span class="glyphicon glyphicon-sort"></span>
						</a>
					<?php }else{?>
						<a href="https://guillentamayo.server5.cpe-facturacioncdperu.com/print/document/<?php echo $comprobante->external_id;?>/ticketguillen" target="_blank" class="btn btn-danger"><b><span class="glyphicon glyphicon-search"></span> PDF</b></a>
						<a href="https://guillentamayo.server5.cpe-facturacioncdperu.com/downloads/document/xml/<?php echo $comprobante->external_id;?>" target="_blank" class="btn btn-success"><b><span class="glyphicon glyphicon-save"></span> XML</b></a>
						
						
					<?php }?>
				<?php endif; ?>
				<?php
					if(isset($comprobante) && $deuda!=null){
						if($deuda->monto_deuda == $deuda->monto_cancelado || $deuda->monto_deuda < $deuda->monto_cancelado){
							$deu_pendiente = number_format($deuda->monto_deuda-$deuda->monto_cancelado, 2);
							echo '<br/><br/><button class="btn btn-primary addpago" type="button" disabled><i class="glyphicon glyphicon-plus"></i> Sin Deuda</button>';
						}else{
							$deu_pendiente = number_format($deuda->monto_deuda-$deuda->monto_cancelado, 2);
							$id_cliente = $comprobante->Cliente_id;

						    echo '<br/><br/><button class="btn btn-primary addpago" type="button" onclick="addPago('.$deuda->id_deuda.','. $id_cliente .' , \''.$comprobante->Correlativo.'\', '. $comprobante->id . ','. $comprobante->ComprobanteTipo_id .');"><i class="glyphicon glyphicon-plus"></i> Pago (S/. '.$deu_pendiente.')</button>';
						}
					}
				?>
				<?php if($comprobante->ComprobanteTipo_id == 1 || $comprobante->ComprobanteTipo_id == 4):?>
					<a class="btn btn-danger" target="_blank" title="Descargar PDF" href="<?php echo base_url('index.php/ventas/proforma/' . $comprobante->id); ?>">
					<b><span class="glyphicon glyphicon-search"></span> PDF</b>
					</a>
			  	<?php if($comprobante->Correlativo != null AND ($comprobante->Estado == 2 || $comprobante->Estado == 3 )):?>

				<a title="Nuevo Comprobante" class="btn btn-success" href="<?php echo base_url('index.php/ventas/comprobante'); ?>">
					<span class="glyphicon glyphicon-file"></span>
				</a>
				<?php endif; ?>
				<?php if($comprobante->Estado != 4 && $comprobante->ComprobanteTipo_id == 1):?>
					<?php if($comprobante->entregado == 0):?>
						<!--<button type="button" id="entregar" class="btn btn-warning">
							<b>Entregar</b>
						</button>-->
					<?php endif; ?>
				<?php endif; ?>
			  	<?php if($comprobante->Estado != 4 && $comprobante->ComprobanteTipo_id != 4 && $comprobante->ComprobanteTipo_id != 1):?>

				<?php endif; ?>
			  	
				<?php endif; ?>
				<?php endif; ?>
				<?php	if($comprobante==null): ?>
					<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalcoddsc"><i class="fas fa-tags"></i> Cod. Dscto.</button>
					<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalclie"><i class="fas fa-user"></i> Añadir Paciente</button>
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addLente"><i class="fas fa-search-plus"></i> Añadir Lentes</button>
				<?php endif; ?>
				<div>
			
				</div>
			</div>
			<h1>
				<?php 
					if(!isset($comprobante))
					{
						echo 'Nuevo Comprobante';
					}
					else
					{
						if($comprobante->Correlativo == '')
						{
							echo $comprobante->Tipo->Nombre;	
						}
						else if($comprobante->ComprobanteTipo_id == 2)
						{
							echo $comprobante->Tipo->Nombre . ($comprobante->Serie != '' ? ': #' . $comprobante->Serie . '-' . $comprobante->Correlativo : '');
						}
						else if($comprobante->ComprobanteTipo_id == 3)
						{
							echo $comprobante->Tipo->Nombre . ($comprobante->Serie != '' ? ': #' . $comprobante->Serie . '-' . $comprobante->Correlativo : '');
						}
						else if($comprobante->ComprobanteTipo_id == 5)
						{
							echo $comprobante->Tipo->Nombre . ($comprobante->Serie != '' ? ': #' . $comprobante->Serie . '-' . $comprobante->Correlativo : '');
						}
						else
						{
							echo $comprobante->Tipo->Nombre . ': #' . $comprobante->Correlativo;
						}
						echo '<br/><a href="'.base_url("index.php/ventas/consolidar/".$comprobante->ComprobanteTipo_id."/".$comprobante->id).'" class="btn btn-success"><i class="icon icon-refresh"></i> Convertir</a>';

						if($this->user->Tipo==1){
							echo '<a href="'.base_url("index.php/notas/crearNota/".$comprobante->ComprobanteTipo_id."/".$comprobante->id).'" class="btn btn-warning" style="margin-left:10px;"><i class="icon icon-refresh"></i> Nota</a>';
						}

						if($anamnesis != null){
							echo '<a href="'.base_url("index.php/encuesta/nuevaEncuesta/".$anamnesis->id_cliente."/".$anamnesis->id_anamnesis).'" class="btn btn-danger" style="margin-left:10px;"><i class="icon icon-check"></i> Encuesta</a>';
						}

						if($conformidad_montura != null){
							echo '<a href="#" class="btn btn-info" style="margin-left:10px;" onclick="editarConformidad('.$comprobante->Cliente_id.', '.$comprobante->id_orden_lab.', '.$comprobante->id.');"><i class="icon icon-warning-sign"></i> Conformidad Montura</a>';

							if($conformidad_montura->conformidad_data != null){
								echo '<a href="'.base_url("conformidadmonturas/imprimirConformidad/".$comprobante->id_orden_lab).'" class="btn btn-primary" target="_blank"><i class="icon icon-print"></i></a>';
							}
						}
						
					}
				?>
			</h1>
			<div>
				<?php
				if(isset($comprobante))
				{
					echo '<a href="'.base_url("mantenimiento/Cliente/".$comprobante->Cliente_id).'" class="btn btn-primary" target="_blank"><i class="icon icon-user"></i> Paciente</a>';
					
					if(isset($orden_lab->id_evaluacion)){
						echo '<a href="'.base_url("mantenimiento/ordenLaboratorio/".$orden_lab->id_evaluacion."/".$orden_lab->id_orden).'" class="btn btn-success" style="margin-left: 14px;" target="_blank"><i class="icon icon-search"></i> Orden Laboratorio</a>';
					}
					
				}
				?>
			</div>
		</div>
		<div>	
			<!--<center>
			<h3>Crear Desde Otro Documento</h3>
			<form>
			    <label>Nro. Documento: </label> <input  type="text" name="idCon" id="txt_name" style="width:100px;"><br/>
			    <label>Tipo Documento: </label> <select  name="tipodoc" id="txt_name2" style="width:100px;"><option value="2">Boleta</option><option value="3">Factura</option><option value="1">Cotizacion</option></select>
			    <input type="button" class="btn" value="Enviar" onclick="consolidar();"/>
			</form>
			</center><br/>-->
			
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li><a href="<?php echo base_url('index.php/ventas/comprobantes'); ?>">Comprobantes</a></li>
		  <li class="active"><?php echo $comprobante == null ? "Nuevo Comprobante" : $comprobante->Tipo->Nombre; ?></li>
		</ol>
		
		<div class="row">
			<div class="col-md-12">

				<?php echo form_open('ventas/comprobantecrud', array('class' => 'upd')); ?>
				<input type="hidden" id="codigo_dscto" name="codigo_dscto">
				<?php if($comprobante != null): ?>
					<input type="hidden" name="id" value="<?php echo $comprobante->id; ?>" />
					<?php if($comprobante->Estado == 4): ?>
						<div class="alert alert-warning text-center">Este comprobante se encuentra <b>en modo revisión</b>, actualice la información que crea conveniente y luego <b>cambie el estado</b> para salir del modo revisión.</div>
					<?php endif; ?>
				<?php endif; ?>
				<div class="well well-sm">(*) Campos obligatorios</div>
				<div class="row">
					<div class="col-md-2">
					  <div class="form-group">
					    <label>Comprobante (*)</label>
					    <?php
					    	if(isset($comprobante))
					    	{
					    		echo '<input class="form-control" type="text" disabled="disabled" value="' . $comprobante->Tipo->Nombre . '" />';
					    	}else
					    	{
					    		echo Select('ComprobanteTipo_id', $tipos, 'Nombre', 'Value', 0, true, null, array('id' => 'sltComprobante'));
					    	}
					    ?>
					  </div>
					</div>
					<div class="col-md-4">
					  <div class="form-group">
					    <label>Cliente <span id="spClienteRequerido">(*)</span></label>
					    <?php if(!isset($comprobante)){?>
						    <div class="input-group">
						      <input id="txtCliente" autocomplete="off" name="ClienteNombre" type="text" class="form-control required" placeholder="Nombre del Cliente" value="" maxlenght="100">
						      <span class="input-group-btn">
						        <button id="btnClienteLimpiar" class="btn btn-default" type="button">
						        	<span class="glyphicon glyphicon-remove"></span>
						        </button>
						      </span>
						    </div>
					    <?php }else{ ?>
					    	<?php if($comprobante->Estado == 4) {?>
							    <div class="input-group">
							      <input id="txtCliente" autocomplete="off" name="ClienteNombre" type="text" class="form-control required" placeholder="Nombre del Cliente" value="" maxlenght="100">
							      <span class="input-group-btn">
							        <button id="btnClienteLimpiar" class="btn btn-default" type="button">
							        	<span class="glyphicon glyphicon-remove"></span>
							        </button>
							      </span>
							    </div>
					    	<?php }else{?>
								<input disabled="disabled id="txtCliente" name="ClienteNombre" type="text" class="form-control" placeholder="Nombre del Cliente" value="<?php echo $cliente->Nombre; ?>" maxlenght="100" />
					    	<?php }?>
					    <?php } ?>
    						<input id="hdCliente_id" type="hidden" name="Cliente_id" value="" />
						  </div>
						</div>
						<div class="col-md-2">
						  <div class="form-group">
						    <label><span id="spIdentidad">RUC</span> <span id="spRucRequerido">(*)</span></label>
						    <input id="txtRuc" readonly="readonly" id="txtRuc" autocomplete="off" name="ClienteIdentidad" type="text" class="form-control required" placeholder="RUC" value="<?php echo $comprobante != null ? $comprobante->ClienteIdentidad : null; ?>" maxlenght="11" />
						    <!--<input type="radio" name="clienteflag" value="4" <?php echo $comprobante != null && $comprobante->cliente_flag == "4" ? "checked" : null; ?>> C. Extranjeria <input type="radio" name="clienteflag" value="7" <?php echo $comprobante != null && $comprobante->cliente_flag == "7" ? "checked" : null; ?>> Pasaporte-->
					  </div>
					</div>
					<div class="col-md-2">
					  <div class="form-group">
					    <label>Fecha</label>
					    <?php if(!isset($comprobante)){?>
							<input autocomplete="off" name="FechaEmitido" type="text" class="form-control required datepicker" placeholder="Fecha de Emisión" value="<?php echo date(DATE); ?>" maxlenght="10" />
					    <?php }else{ ?>
							<input <?php echo $comprobante->Estado != 4 ? 'disabled="disabled"' : 'name="FechaEmitido"'; ?> type="text" class="form-control required datepicker" placeholder="Fecha de Emisión" value="<?php echo toDate($comprobante->FechaEmitido); ?>" maxlenght="10" />
					    <?php } ?>
					  </div>
					</div>
					<div class="col-md-2">
					  <div class="form-group">
					    <label>Estado (*)</label>
					    <?php 
						    if($comprobante != NULL)
						    {
							    if($comprobante->Estado == 3)
							    {
							    	echo '<input type="text" style="color:red;" value="Anulado" disabled="disabled" class="form-control" />';
							    }else if($comprobante->Estado == 1)
							    {
							    	echo '<input type="text" style="color:orange;" value="Pendiente" disabled="disabled" class="form-control" />';
							    }
							    else if($comprobante->Estado == 2)
							    {
							    	unset($estados[2]);
									unset($estados[3]);
									if($this->user->Tipo==1){
										echo '<select id="sltEstado" data-estado="2" name="Estado" class="form-control required "><option selected="selected" value="2">Aprobado</option><option value="3">Anulado</option></select>';
									}else{
										echo '<select id="sltEstado" data-estado="2" name="Estado" class="form-control required "><option selected="selected" value="2">Aprobado</option></select>';
									}
						    								    	
							    }else
							    {
							    	unset($estados[2]);
						    		if($this->user->Tipo==1){
										echo '<select id="sltEstado" data-estado="2" name="Estado" class="form-control required "><option selected="selected" value="2">Aprobado</option><option value="3">Anulado</option></select>';
									}else{
										echo '<select id="sltEstado" data-estado="2" name="Estado" class="form-control required "><option selected="selected" value="2">Aprobado</option></select>';
									}					    	
							    }
						    }
						    else
						    {
						    	echo '<input style="color:orange;" type="text" value="Pendiente" disabled="disabled" class="form-control" />';
						    }
					    ?>
					  </div>
					</div>
				</div>
				<div class="col-md-7">
			       <div class="form-group">
				    <label>Dirección <span id="spDireccionRequerido">(*)</span></label>
					<input id="txtDireccion" type="text" autocomplete="off" id="txtDireccion" name="ClienteDireccion" class="form-control" value="<?php echo isset($cliente) ? $cliente->Direccion : ''; ?>" readonly="readonly" />
				  </div>
				</div>
				

				<div class="col-md-3">
					<div class="form-group">
						<label>Medio de Pago</label>
						<select id="sltMedioPago" name="mediopago" class="form-control required" <?php echo isset($comprobante) ? 'disabled' : ''; ?>>
							<option value="0"> -- SELECCIONE -- </option>
							<option value="efectivo" <?php echo isset($comprobante) && $comprobante->mediopago == "Efectivo" ? 'selected' : ''; ?>>Efectivo</option>
							<option value="visa" <?php echo isset($comprobante) && $comprobante->mediopago == "Visa" ? 'selected' : ''; ?>>Visa</option>
							<option value="MC" <?php echo isset($comprobante) && $comprobante->mediopago == "MC" ? 'selected' : ''; ?>>Mastercard</option>
							<option value="estilos" <?php echo isset($comprobante) && $comprobante->mediopago == "Estilos" ? 'selected' : ''; ?>>Estilos</option>
							<option value="deposito" <?= isset($comprobante) && $comprobante->mediopago == "Deposito" ? 'selected' : '' ?>>Depósito</option>
							<option value="pendiente" <?= isset($comprobante) && $comprobante->mediopago == "Pendiente" ? 'selected' : '' ?>>Pendiente de Pago</option>
							<option value="yape" <?php echo isset($comprobante) && $comprobante->mediopago == "Yape" ? 'selected' : ''; ?>>Yape</option>
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
				  <table class="table" id="tablaped">
			  		<thead style="background:#eee;">
				  	<tr>
				  		<th style="width:20px;background:#eee;">#</th>
				  		<th style="width:20px;background:#eee;"></th>
				  		<th>Item</th>
				  		<th style="width:100px;"><?php if(isset($comprobante->ComprobanteTipo_id) && $comprobante->ComprobanteTipo_id == 1){ echo "Cant."; }else{ echo "Cant."; }?></th>
				  		<th style="width:84px;">UND</th>
				  		<th class="text-right" style="width:140px;">P.U (<?php
				  	if(!empty($comprobante->moneda)){
				  		if($comprobante->moneda=="Usd" || $comprobante->moneda=="usd"){
				  			echo "$";
				  		}else{
				  			echo $this->conf->Moneda_id;
				  		} 
				  	}else{echo $this->conf->Moneda_id;}?>)</th>
				  		<th class="text-right" style="width:140px;">P.T (<?php
				  	if(!empty($comprobante->moneda)){
				  		if($comprobante->moneda=="Usd" || $comprobante->moneda=="usd"){
				  			echo "$";
				  		}else{
				  			echo $this->conf->Moneda_id;
				  		} 
				  	}else{echo $this->conf->Moneda_id;}?>)</th>
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
			  					<input autocomplete="off" name="Cantidad[]" type="text" class="form-control input-sm txtCantidad price" value="" placeholder="Cantidad"  maxlength="10"/>
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
		  			<?php }?>
		  			
			  	
			  		</tbody>
			  		<tfoot style="background:#eee;">
			  			<?php	if($comprobante==false){ ?>
				  			<tr>
				  				<td colspan="7">
				  					<button type="button" class="btn btn-primary" id="btnadd"><span class="glyphicon glyphicon-plus"></span> <b>Agregar Fila</b></button>
				  				</td>
				  			</tr>
				  		<?php }?>
					  	<?php if($comprobante == false){?>
			  			<tr>
			  				<td colspan="7">
							  <div class="form-group">
								<label>Comentario</label>
							    <textarea name="Glosa" rows="2" cols="" class="form-control"></textarea>
							  </div>
			  				</td>
			  			</tr>
			  			
						<?php }else if ($comprobante->Glosa != '' && $comprobante->Estado != 4){ ?>
			  			<tr>
			  				<td colspan="7">
							  <div class="form-group">
								<label>Glosa</label>
								<p style="background:#fff;padding:4px;border-radius:4px;"><?php echo $comprobante->Glosa; ?></p>
							  </div>
			  				</td>
			  			</tr>
						<?php }else if($comprobante->Estado == 4){ ?>
			  			<tr>
			  				<td colspan="7">
							  <div class="form-group">
								<label>Glosa</label>
							    <textarea name="Glosa" rows="2" cols="" <?php echo $comprobante->Glosa != '' ? 'readonly="readonly"' : ''; ?> class="form-control"><?php echo $comprobante->Glosa; ?></textarea>
							  </div>
			  				</td>
			  			</tr>
						<?php } ?>	
			  			<tr id="trSubTotal">
			  				<th class="text-right" colspan="6">Sub Total (<?php
				  	if(!empty($comprobante->moneda)){
				  		if($comprobante->moneda=="Usd" || $comprobante->moneda=="usd"){
				  			echo "$";
				  		}else{
				  			echo $this->conf->Moneda_id;
				  		} 
				  	}else{echo $this->conf->Moneda_id;}?>)</th>
			  				<td class="text-right">
			  					<?php if($comprobante == null){ ?>
									<input autocomplete="off" id="txtSubTotal" class="form-control text-right input-sm" value="0.00" readonly="readonly" />
			  					<?php }else if($comprobante->Estado != 4) {?>
			  					<?php 
				  				if($comprobante->moneda=="usd" || $comprobante->moneda=="Usd"){
				  					$subT=$comprobante->SubTotal/$comprobante->tipo_cambio;
				  					echo number_format($subT, 2); 
				  				}else{
				  					echo number_format($comprobante->SubTotal, 2);
				  				}	?>
		  							
			  					<?php }else{ ?>
			  						<input autocomplete="off" id="txtSubTotal" class="form-control text-right input-sm price" value="<?php echo number_format($comprobante->SubTotal, 2); ?>" name="SubTotal"  />
			  					<?php } ?>
							</td>
			  			</tr>
			  			<tr id="trIva">
			  				<th class="text-right" colspan="6">
			  					IGV (%)
								<?php if($comprobante == null){ ?>
									<input id="txtIva" name="Iva" style="width:54px;margin-left:10px;" class="form-control text-right input-sm price pull-right" value="<?php echo $this->conf->Iva; ?>" />
			  					<?php }else if($comprobante->Estado != 4) {?>
		  							<span style="font-weight:normal;">
		  						<?php 
				  				echo number_format($comprobante->Iva, 2);
				  				?></span>
			  					<?php }else{ ?>
			  						<input id="txtIva" name="Iva" style="width:54px;margin-left:10px;" class="form-control text-right input-sm price pull-right" value="<?php echo $this->conf->Iva; ?>" />
			  					<?php } ?>
			  				</th>
			  				<td class="text-right">
								<?php if($comprobante == null){ ?>
									<input autocomplete="off" id="txtIvaSubTotal" readonly="readonly" class="form-control text-right input-sm" value="0.00" />
			  					<?php }else if($comprobante->Estado != 4) {?>
			  					<?php 
				  				if($comprobante->moneda=="usd" || $comprobante->moneda=="Usd"){
				  					$ivaT=$comprobante->IvaTotal/$comprobante->tipo_cambio;
				  					echo number_format($ivaT, 2);
				  				}else{
				  					echo number_format($comprobante->IvaTotal, 2);
				  				}	?>
		  							
			  					<?php }else{ ?>
			  						<input autocomplete="off" id="txtIvaSubTotal" readonly="readonly" class="form-control text-right input-sm" value="<?php echo number_format($comprobante->IvaTotal, 2); ?>" />
			  					<?php } ?>
			  				</td>
			  			</tr>
			  			<tr id="trDsc">
			  				<th class="text-right" colspan="6">
							  	Descuento (%) <?php if(isset($comprobante->Dsc)) echo number_format($comprobante->Dsc, 2); ?>
								<?php if($comprobante == null){ ?>
									<input id="txtDsc" name="Dsc" style="width:54px;margin-left:10px;" class="form-control text-right input-sm price pull-right" value="0" />
			  					<?php }else if($comprobante->Estado != 4) {?>
		  							<span style="font-weight:normal;"></span>
			  					<?php }else{ ?>
			  						<input id="txtDsc" name="Dsc" style="width:54px;margin-left:10px;" class="form-control text-right input-sm price pull-right" value="0" />
			  					<?php } ?>
			  				</th>
			  				<td class="text-right">
								<?php if($comprobante == null){ ?>
									<input autocomplete="off" id="txtDscT" name="totalDsc" class="form-control text-right input-sm" value="0" readonly="readonly"/>
								
			  					<?php }else if($comprobante->Estado != 4) {?>
			  					<?php 
				  				if($comprobante->moneda=="usd" || $comprobante->moneda=="Usd"){
				  					$dscT=$comprobante->totalDsc/$comprobante->tipo_cambio;
				  					echo number_format($dscT, 2);
				  				}else{
				  					echo number_format($comprobante->totalDsc, 2);
				  				}	?>
		  							
			  					<?php }else{ ?>
			  						<input autocomplete="off" id="txtDscT"  name="totalDsc"  class="form-control text-right input-sm" value="0" />
			  					<?php } ?>
			  				</td>
			  			</tr>
			  			<tr id="trDsc2">
			  				<th class="text-right" colspan="6">
							  Descuento Fijo (S/.)
			  				</th>
			  				<td class="text-right">
								<?php if($comprobante == null){ ?>
									<input autocomplete="off" id="txtDscT2" name="totalDsc2" class="form-control text-right input-sm" value="0"/>
			  					<?php }else if($comprobante->Estado != 4) {?>
			  					<?php 
				  				if($comprobante->moneda=="usd" || $comprobante->moneda=="Usd"){
				  					$dscT=$comprobante->totalDsc/$comprobante->tipo_cambio;
				  					echo number_format($dscT, 2);
				  				}else{
				  					echo number_format($comprobante->totalDsc, 2);
				  				}	?>
		  							
			  					<?php }else{ ?>
			  						<input autocomplete="off" id="txtDscT"  name="totalDsc"  class="form-control text-right input-sm" value="0" />
			  					<?php } ?>
			  				</td>
			  			</tr>
			  			
			  			<tr>
			  				<th class="text-right" colspan="6">Total (<?php
				  	if(!empty($comprobante->moneda)){
				  		if($comprobante->moneda=="Usd" || $comprobante->moneda=="usd"){
				  			echo "$";
				  		}else{
				  			echo $this->conf->Moneda_id;
				  		} 
				  	}else{echo $this->conf->Moneda_id;}?>)</th>
			  				<td class="text-right">
								<?php if($comprobante == null){ ?>
									<input autocomplete="off" id="txtTotal" readonly="readonly" class="form-control text-right input-sm" value="0.00" />

			  					<?php }else if($comprobante->Estado != 4) {?>
			  					<?php 
				  				if($comprobante->moneda=="usd" || $comprobante->moneda=="Usd"){
				  					$tot=$comprobante->Total/$comprobante->tipo_cambio;
				  					echo number_format($tot, 2);
				  				}else{
				  					echo number_format($comprobante->Total, 2);
				  				}	?>
		  							
			  					<?php }else{ ?>
			  						<input autocomplete="off" id="txtTotal" readonly="readonly" class="form-control text-right input-sm" value="<?php echo number_format($comprobante->Total, 2); ?>" />
			  					<?php } ?>
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
								<?php if($comprobante == null){ ?>
									<input autocomplete="off" id="ped_adela" name="ped_adela" class="form-control text-right price input-sm" value="0"/>
								
			  					<?php }else if($comprobante->Estado != 4) {

				  					if(isset($comprobante->adelanto)) echo number_format($comprobante->adelanto, 2);
		  							
			  					}else{ ?>
			  						<input autocomplete="off" id="ped_adela"  name="ped_adela"  class="form-control text-right input-sm" value="0" />
			  					<?php } ?>
			  				</td>
			  			</tr>
			  		</tfoot>
				  </table>
	<br/>
	<div style="clear:both;margin-bottom:15px;"></div>
	<?php if($comprobante == null){ ?>
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
	<?php }?>
		<div class="col-md-8 text-right">
			<?php	if($comprobante!=null){ ?>
				<button id="btnGuardar" type="submit" class="submit-ajax-button none">Guardar</button>
			<?php }else{ ?>
			<?php 
				if(!isset($comprobante)){
					echo '<input type="checkbox" name="generar_deuda" value="1" checked onclick="javascript: return false;"> <label style="color:#000; font-size:16px;">Generar Deuda</label><br/><input type="checkbox" name="factura_gratuita" value="1"> <label style="color:#000; font-size:16px;">Factura Gratuita</label><br/><br/>';
				}

			?>
			<button type="submit" class="btn btn-lg btn-success submit-ajax-button cpeconfirm"><span class="glyphicon glyphicon-check"></span> Guardar</button>
			<?php }?>
		</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
	<?php if(isset($comprobante) && ($comprobante->ComprobanteTipo_id == 1 || $comprobante->ComprobanteTipo_id == 4)){?>
		<object data="<?php echo base_url('index.php/ventas/proforma/' . $comprobante->id); ?>" type="application/pdf" width="100%" height="500px"> 
			<p><a href="<?php echo base_url('index.php/ventas/proforma/' . $comprobante->id); ?>">Click para ver PDF.</a></p>  
		</object>
	<?php }?>
	<?php if(!empty($comprobante->link_pdf)){ ?>
		<object data="https://guillentamayo.server5.cpe-facturacioncdperu.com/print/document/<?php echo $comprobante->external_id;?>/ticketguillen" type="application/pdf" width="100%" height="500px"> 
			<p><a href="https://guillentamayo.server5.cpe-facturacioncdperu.com/print/document/<?php echo $comprobante->external_id;?>/ticketguillen">Click para ver PDF.</a></p>  
		</object>
	<?php }?>
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
			<input autocomplete="off" minlength="11" maxlength="11" name="Ruc" type="text" class="form-control" placeholder="Ingrese el RUC" id="rucemp" value="<?php echo $cliente != null ? $cliente->Ruc : null; ?>" />
		</div>-->
		<div class="form-group">
			<label>Nombre/Razon Social (*)</label>
			<input id="Nombre" autocomplete="off" name="Nombre" type="text" class="form-control rsocialn required" placeholder="Nombre del cliente" value="<?php echo $cliente != null ? $cliente->Nombre : null; ?>" />
			<div id="resulcond"></div>
		</div>
		<!--<div class="form-group">
			<label>DNI / CE / Pasaporte</label>
			<input autocomplete="off" maxlength="8" name="Dni" id="dnicli" type="text" class="form-control" placeholder="Ingrese el DNI" value="<?php echo $cliente != null ? $cliente->Dni : null; ?>" />
		</div>-->

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

		<div class="form-group">
			<label>Télefono Principal</label>
			<input autocomplete="off" name="Telefono1" id="Telefono1" type="text" class="form-control" placeholder="Télefono Principal" value="<?php echo $cliente != null ? $cliente->Telefono1 : null; ?>" />
		</div>

		<div class="form-group">
			<label>Trabajo/Ocupación</label>
			<input autocomplete="off" name="trabajo" id="trabajo" type="text" class="form-control" placeholder="Trabajo u ocupación" value="<?php echo $cliente != null ? $cliente->trabajo : null; ?>" />
		</div>
	
		<div class="form-group">
			<label>Dirección</label>
			<textarea name="Direccion" id="direcli" class="form-control" placeholder="Dirección"><?php echo $cliente != null ? $cliente->Direccion : null; ?></textarea>
		</div>
		<div class="col-md-4">
			<div class="form-group">
			<label>Departamento</label>
			<input autocomplete="off" name="Departamento" id="depcli" type="text" class="form-control" placeholder="Departamento" value="<?php echo $cliente != null ? $cliente->Departamento : null; ?>" />
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
			<label>Ciudad</label>
			<input autocomplete="off" name="Ciudad" id="ciucli"  type="text" class="form-control" placeholder="Ciudad" value="<?php echo $cliente != null ? $cliente->Ciudad : null; ?>" />
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
			<label>Distrito</label>
			<input autocomplete="off" name="Distrito" id="discli" type="text" class="form-control" placeholder="Distrito" value="<?php echo $cliente != null ? $cliente->Distrito : null; ?>" />
			</div>
		</div>
			<button type="button" class="btn btn-success btn-block" id="" onclick="addCliente()">Guardar Cliente</button>
      	</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
		</div>
    </div>

  </div>
</div>

<script>
	$("#entregar").on("click", function(){
		AjaxPopupModal('mEntregar', 'Entregar Pedido', 'ventas/ajax/CargarDetalleParaEntregar', { comprobante_id : <?php echo (isset($comprobante->id))?$comprobante->id:'""'; ?>})
	})
</script>
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
	<?php if($comprobante != null){ ?>
	
	$("#generarDoc").on("click", function(e){
		$("#generarDoc").prop("disabled",true);
		$("#generarDoc").attr("readonly", true); 
		$("#generarDoc").html('Enviando...');
		$.ajax({
			type: "GET",
			url: "<?php echo isset($comprobante->id) ? base_url("ventas/enviarSunat/".$comprobante->id):"";?>",
			//data: "archivo="+archi,
			//headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			dataType: "json",
			success: function(data) {
				$("#generarDoc").fadeOut("normal", function() {
				        $(this).remove();
				    });
				if(data.links.pdf != undefined){
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
	<?php }?>

    /** Medios de Pago **/

    $("#sltComprobante").on("change", function() {
        console.log("Working");
		var cliente = $("#hdCliente_id");
		var input = $("#txtCliente");
		var tipoDoc = $("#sltComprobante");
		var medioPago = $("#sltMedioPago");

		cliente.val(null);
		input.attr('data-name', "");
		input.val("");
		$("#txtRuc").val("");
		$("#txtDireccion").val("");
		if (tipoDoc.val() == 4) {
			$("#sltMedioPago option:not([value='deposito'], [value='0'])").remove();
		} else {
			$("#sltMedioPago option:not([value='deposito'], [value='0'])").remove();
			var nuevasOpciones = [
				{ value: "pendiente", text: "Pendiente de Pago" },
				{ value: "efectivo", text: "Efectivo" },
				{ value: "visa", text: "Visa" },
				{ value: "MC", text: "Mastercard" },
				{ value: "estilos", text: "Estilos" },
				{ value: "yape", text: "Yape" }
			];
				// Agregar las nuevas opciones al select
			nuevasOpciones.forEach(function(opcion) {
					var nuevaOpcion = $("<option>", {
					value: opcion.value,
					text: opcion.text,
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

	$("#btnadd").on('click', function(){
		var val = $(".numro:last").html();
		var nval = parseInt(val)+1;
		$('#tablaped').append('<tr><td class="text-right numro" style="background:#eee;padding:2px 4px;">'+nval+'</td><td><button type="button" class="btn btn-default btn-sm btnProductoQuitar" title="Remover este item"><span class="glyphicon glyphicon-remove"></span></button</td><td><input id="txtProducto_'+nval+'" data-id="00" autocomplete="off" name="ProductoNombre[]" type="text" class="form-control input-sm txtProducto ui-autocomplete-input" value="" placeholder="Escriba el nombre de un producto" data-name="Producto adicional"><input name="Producto_id[]"  type="hidden" class="hdProducto_id" value="00" /></td><td><input name="PrecioUnitarioCompra[]"  type="hidden" class="hdPrecioUnitarioCompra" value="0.00" /><input autocomplete="off" name="Cantidad[]" type="text" class="form-control input-sm txtCantidad price" value="0" placeholder="Cantidad"  maxlength="10"/><input name="Tipo[]" type="hidden" class="hdTipo" value="1" /></td><td><input autocomplete="off" name="UnidadMedida_id[]" type="text" class="form-control input-sm txtUnidad" value="UND" placeholder="UND" readonly="readonly" /></td><td><input autocomplete="off" name="PrecioUnitario[]" type="text" class="form-control input-sm price text-right txtPrecioUnitario" value="0.00" placeholder="Precio Unitario" maxlength="10"></td><td><input autocomplete="off" type="text" class="form-control input-sm price text-right txtTotal" value="" placeholder="Total" readonly="readonly" /></td></tr>');
		CalcularComprobante();
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

	function addCliente(){
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

		var doc_len = nro_doc.length;
		var nombre_len = Nombre.length;

		if(tipo_doc == 0){
			errorToast("Debe seleccionar un tipo de documento.");
			return false;
		}

		if(doc_len == 0){
			errorToast("Debe ingresar un número de documento.");
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
	};

	$("#btnaddprfm").on("click", function(e){
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


	function addPago(deuda_id, id_cliente, correlativonum, id_comprobante, tipo_comprobante){
		AjaxPopupModal('mAddPago', 'Agregar Pago', 'cuentacorriente/ajax/agregarPagoCpe', { id_deuda : deuda_id, id_cliente:id_cliente, correlativ:correlativonum, id_comprobante: id_comprobante, tipo_comprobante: tipo_comprobante})
	}

	function editarConformidad(id_cliente, id_orden_lab, id_comprobante){
		AjaxPopupModal('maddConformidadMontura', 'Editar Conformidad de Montura', 'conformidadmonturas/ajax/addConformidadMontura', { id_cliente : id_cliente, id_orden_lab: id_orden_lab, id_comprobante: id_comprobante})
	}
</script>
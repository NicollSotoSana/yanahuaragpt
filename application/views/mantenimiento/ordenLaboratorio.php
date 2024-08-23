<script>
   $(document).ready(function(){
   	BuscarProductos();
      BuscarMateriales();
      //BuscarDescripcion();
      //CalcularPrecio();
      BuscarProveedores();
   });
   $(document).on("click", "input[name='montaje']", function(){
       thisRadio = $(this);
       if (thisRadio.hasClass("imChecked")) {
           thisRadio.removeClass("imChecked");
           thisRadio.prop('checked', false);
       } else { 
           thisRadio.prop('checked', true);
           thisRadio.addClass("imChecked");
       };
   })
   	$(document).ready(function() {
   	  $(window).keydown(function(event){
   	    if(event.keyCode == 13) {
   	      event.preventDefault();
   	      return false;
   	    }
   	  });
   	});
   
   $(document).on('keydown', '#txtProducto', function (e) {
       var key = e.which;
       if(key == 13) {
       	e.preventDefault();
           $.ajax({
   			url: base_url('services/productosporcod'),
   			type: "post",
               dataType: "json",
               data: {criterio: $("#txtProducto").val()},
   			success: function(respuesta) {
   				$("#txtProducto").attr('data-name',respuesta[0].Nombre);
   	            $("#txtProducto").attr('data-id', respuesta[0].id);
   				$("#id_montura").val(respuesta[0].id);
   	        	$("#txtProducto").val(respuesta[0].Nombre);
   				console.log(respuesta[0].id);
   			},
   			error: function() {
   		        console.log("No se ha podido obtener la información");
   		    }
   		});
       }
   });
   
   /*$(document).on('change', '#material_lente,#descripcion_lente', function (e) {
       CalcularPrecio();
   });*/
   
   function BuscarMateriales()
   {
   	var input = $("#material_lente");
   
       input.autocomplete({
           dataType: 'JSON',
           source: function (request, response) {
               jQuery.ajax({
                   url: base_url('services/preciolente'),
                   type: "post",
                   dataType: "json",
                   data: {
                       criterio: request.term
                   },
                   success: function (data) {
                       response($.map(data, function (item) {
                           return {
                               value: item.material,
                               precio: item.precio,
                               laboratorio: item.laboratorio,
                           }
                       }))
                   }
               })
           },
           search  : function(){$(this).addClass('ui-autocomplete-loading');},
           open    : function(){$(this).removeClass('ui-autocomplete-loading');},
           select: function(e, ui){
               $("#precio_lente").val(ui.item.precio);
               $("#descripcion_lente_hide").val(ui.item.value);
               $("#laboratorio_lente").val(ui.item.laboratorio);
   		}
       })
   }
   
   function BuscarProveedores(){
   	var input = $("#txtProveedores");
   
       input.autocomplete({
           dataType: 'JSON',
           source: function (request, response) {
               jQuery.ajax({
                   url: base_url('services/proveedores'),
                   type: "post",
                   dataType: "json",
                   data: {
                       criterio: request.term
                   },
                   success: function (data) {
                       response($.map(data, function (item) {
                           return {
                               id: item.id,
                               value: item.Nombre,
                               identidad: item.Identidad,
                               direccion: item.Direccion
                           }
                       }))
                   }
               })
           },
           search  : function(){$(this).addClass('ui-autocomplete-loading');},
           open    : function(){$(this).removeClass('ui-autocomplete-loading');},
           select: function (e, ui) {
           	$("#idprove").val(ui.item.id);
           	$("#txtProveedores").val(ui.item.value);
           	input.blur();
           }
       })
   }
   function BuscarProductos()
   {
   	var input = $("#txtProducto");
   
       input.autocomplete({
           dataType: 'JSON',
           source: function (request, response) {
               jQuery.ajax({
                   url: base_url('services/productos'),
                   type: "post",
                   dataType: "json",
                   data: {
                       criterio: request.term
                   },
                   success: function (data) {
                       response($.map(data, function (item) {
                           return {
                               id: item.id,
                               value: item.Nombre,
                               und: item.UnidadMedida_id,
                               nombre: item.Nombre,
                               marca: item.Marca,
                               pc: item.PrecioCompra,
                               stock: item.Stock
                           }
                       }))
                   }
               })
           },
           search  : function(){$(this).addClass('ui-autocomplete-loading');},
           open    : function(){$(this).removeClass('ui-autocomplete-loading');},
           select: function (e, ui) {
           	input.blur();
           	$("#producto_id").val(ui.item.id);
           	
               input.attr('data-name', ui.item.value);
               input.attr('data-id', ui.item.id);
   
           	input.val(ui.item.nombre);
           	$("#txtUnidadMedida_id").val(ui.item.und);
           	$("#txtCosto").val(ui.item.costo);
           	$("#txtPrecioCompra").val(ui.item.pc);
               $("#txtStock").val(ui.item.stock);
               $("#id_montura").val(ui.item.id);
               CalcularPrecio();
           	return false;
           }
       })
   
       input.focus(function () {
           $(this).val('');
       });
       input.blur(function () {
           $(this).val($(this).attr('data-name'));
       });
   }
</script>
<?php
   //var_dump($estado_act);
   ?>
<div class="row" id="loa">
   <div class="col-md-12">
      <div class="page-header" style="margin-bottom:30px;">
         <h3><?php echo $id_ord == 0 ? "Nueva Orden Lab." : "Orden Lab. #<b>".$ord_ceros."</b>"; ?></h3>
         <?php
            if($id_ord>0){
            	
            	echo '<div class="col-md-2"><a href="'.base_url("ventas/ticketOrdenLab/".$id_ord).'" class="btn btn-info" target="_blank"><b><i class="icon-print"></i> Descargar PDF</b></a></div>';
            	//echo '<div class="col-md-3"><a href="'.base_url("ventas/comprobanteOrdenLab/".$id_ord).'" class="btn btn-success"><b><i class="icon icon-money"></i> Generar Comprobante</b></a></div>';
            	echo '<div class="col-md-2"><select class="form-control" id="updestado">';
            	foreach($estados as $est){
            		if($est->id_estado==$estado_act->id_estado_orden){
            			$sel = "selected";
            		}else{
            			$sel = "";
            		}
            		echo '<option value="'.$est->id_estado.'" '.$sel.'>'.$est->estado.'</option>';
            	}
            	echo '</select></div>';
            	//echo '<div class="col-md-2"><a href="'.base_url("mantenimiento/ordenlab_pdf/".$id_ord).'" class="btn btn-primary" target="_blank"><i class="icon-dollar"></i> Ingresar Factura Lab.</a></div>';
            }
            
            ?>
            <?php echo $id_ord != 0 ? '<div class="col-md-2" style="font-weight:bold;">Fecha de Emisión:<br/>'.date("d/m/Y", strtotime($fecha_emision)).'</div>' : ''; ?>
      </div>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
         <li><a href="<?php echo base_url('index.php/mantenimiento/clientes'); ?>">Clientes</a></li>
         <li class="active"><?php echo $eval == null ? "Nueva Orden" : $id_eval; ?></li>
      </ol>
   </div>
   <div class="col-md-8">
      <form id="formeva" method="post" action="<?php echo base_url();?>mantenimiento/updOrden">
         <input type="hidden" name="id_eval" class="form-control" value="<?php echo $id_eval;?>">
		 <input type="hidden" name="id_ordenlab" class="form-control" value="<?php echo $id_ord;?>">
         <div class="row">
            <table class="table table-bordered table-hover">
               <thead>
                  <tr style="text-align: center;">
                     <th colspan="9" style="font-size: 2em;color:#000;">Lejos</th>
                  </tr>
                  <tr>
                     <th> </th>
                     <th>ESF</th>
                     <th>CIL</th>
                     <th>EJE</th>
                     <th>ADICIÓN</th>
                     <th>DIP</th>
                     <th>ALT</th>
                     <th>PRISMAS</th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td><strong>O.D.</strong></td>
                     <td><input type="text" name="lejos_refra_od_esf" class="form-control" value="<?php echo isset($eval["lejos_refra_od_esf"]) ? $eval["lejos_refra_od_esf"] : ''; ?>"></td>
                     <td><input type="text" name="lejos_refra_od_cyl" class="form-control" value="<?php echo isset($eval["lejos_refra_od_cyl"]) ? $eval["lejos_refra_od_cyl"] : ''; ?>"></td>
                     <td><input type="text" name="lejos_refra_od_eje" class="form-control" value="<?php echo isset($eval["lejos_refra_od_eje"]) ? $eval["lejos_refra_od_eje"] : ''; ?>"></td>
                     <td><input type="text" name="lejos_refra_od_adicion" class="form-control" value="<?php echo isset($eval["lejos_refra_od_adicion"]) ? $eval["lejos_refra_od_adicion"] : ''; ?>"></td>
                     <td><input type="text" name="lejos_refra_od_dnp" class="form-control" value="<?php echo isset($eval["lejos_refra_od_dnp"]) ? $eval["lejos_refra_od_dnp"] : ''; ?>"></td>
                     <td><input type="text" name="lejos_refra_od_alt" class="form-control" value="<?php echo isset($eval["lejos_refra_od_alt"]) ? $eval["lejos_refra_od_alt"] : ''; ?>"></td>
                     <td><input type="text" name="lejos_refra_od_prismas" class="form-control" value="<?php echo isset($eval["lejos_refra_od_prismas"]) ? $eval["lejos_refra_od_prismas"] : ''; ?>"></td>
                  </tr>
                  <tr>
                     <td><strong>O.I.</strong></td>
                     <td><input type="text" name="lejos_refra_oi_esf" class="form-control" value="<?php echo isset($eval["lejos_refra_oi_esf"]) ? $eval["lejos_refra_oi_esf"] : ''; ?>"></td>
                     <td><input type="text" name="lejos_refra_oi_cyl" class="form-control" value="<?php echo isset($eval["lejos_refra_oi_cyl"]) ? $eval["lejos_refra_oi_cyl"] : ''; ?>"></td>
                     <td><input type="text" name="lejos_refra_oi_eje" class="form-control" value="<?php echo isset($eval["lejos_refra_oi_eje"]) ? $eval["lejos_refra_oi_eje"] : ''; ?>"></td>
                     <td><input type="text" name="lejos_refra_oi_adicion" class="form-control" value="<?php echo isset($eval["lejos_refra_oi_adicion"]) ? $eval["lejos_refra_oi_adicion"] : ''; ?>"></td>
                     <td><input type="text" name="lejos_refra_oi_dnp" class="form-control" value="<?php echo isset($eval["lejos_refra_oi_dnp"]) ? $eval["lejos_refra_oi_dnp"] : ''; ?>"></td>
                     <td><input type="text" name="lejos_refra_oi_alt" class="form-control" value="<?php echo isset($eval["lejos_refra_oi_alt"]) ? $eval["lejos_refra_oi_alt"] : ''; ?>"></td>
                     <td><input type="text" name="lejos_refra_oi_prismas" class="form-control" value="<?php echo isset($eval["lejos_refra_oi_prismas"]) ? $eval["lejos_refra_oi_prismas"] : ''; ?>"></td>
                  </tr>
                  <tr style="text-align: center;">
                     <th colspan="9" style="font-size: 2em;color:#000;">Cerca</th>
                  </tr>
                  <tr>
                     <td><strong>O.D.</strong></td>
                     <td><input type="text" name="cerca_refra_od_esf" class="form-control" value="<?php echo isset($eval["cerca_refra_od_esf"]) ? $eval["cerca_refra_od_esf"] : ''; ?>"></td>
                     <td><input type="text" name="cerca_refra_od_cyl" class="form-control" value="<?php echo isset($eval["cerca_refra_od_cyl"]) ? $eval["cerca_refra_od_cyl"] : ''; ?>"></td>
                     <td><input type="text" name="cerca_refra_od_eje" class="form-control" value="<?php echo isset($eval["cerca_refra_od_eje"]) ? $eval["cerca_refra_od_eje"] : ''; ?>"></td>
                     <td><input type="text" name="cerca_refra_od_adicion" class="form-control" value="<?php echo isset($eval["cerca_refra_od_adicion"]) ? $eval["cerca_refra_od_adicion"] : ''; ?>"></td>
                     <td><input type="text" name="cerca_refra_od_dnp" class="form-control" value="<?php echo isset($eval["cerca_refra_od_dnp"]) ? $eval["cerca_refra_od_dnp"] : ''; ?>"></td>
                     <td><input type="text" name="cerca_refra_od_alt" class="form-control" value="<?php echo isset($eval["cerca_refra_od_alt"]) ? $eval["cerca_refra_od_alt"] : ''; ?>"></td>
                     <td><input type="text" name="cerca_refra_od_prismas" class="form-control" value="<?php echo isset($eval["cerca_refra_od_prismas"]) ? $eval["cerca_refra_od_prismas"] : ''; ?>"></td>
                  </tr>
                  <tr>
                     <td><strong>O.I.</strong></td>
                     <td><input type="text" name="cerca_refra_oi_esf" class="form-control" value="<?php echo isset($eval["cerca_refra_oi_esf"]) ? $eval["cerca_refra_oi_esf"] : ''; ?>"></td>
                     <td><input type="text" name="cerca_refra_oi_cyl" class="form-control" value="<?php echo isset($eval["cerca_refra_oi_cyl"]) ? $eval["cerca_refra_oi_cyl"] : ''; ?>"></td>
                     <td><input type="text" name="cerca_refra_oi_eje" class="form-control" value="<?php echo isset($eval["cerca_refra_oi_eje"]) ? $eval["cerca_refra_oi_eje"] : ''; ?>"></td>
                     <td><input type="text" name="cerca_refra_oi_adicion" class="form-control" value="<?php echo isset($eval["cerca_refra_oi_adicion"]) ? $eval["cerca_refra_oi_adicion"] : ''; ?>"></td>
                     <td><input type="text" name="cerca_refra_oi_dnp" class="form-control" value="<?php echo isset($eval["cerca_refra_oi_dnp"]) ? $eval["cerca_refra_oi_dnp"] : ''; ?>"></td>
                     <td><input type="text" name="cerca_refra_oi_alt" class="form-control" value="<?php echo isset($eval["cerca_refra_oi_alt"]) ? $eval["cerca_refra_oi_alt"] : ''; ?>"></td>
                     <td><input type="text" name="cerca_refra_oi_prismas" class="form-control" value="<?php echo isset($eval["cerca_refra_oi_prismas"]) ? $eval["cerca_refra_oi_prismas"] : ''; ?>"></td>
                  </tr>
               </tbody>
            </table>
        </div>
   	</div>
   	
	<div class="col-md-4">
			<div class="form-group">
				<label>Ángulo Panorámico</label>
				<input type="text" name="angulo_panoramico" class="form-control" value="<?php echo $eval != null && isset($eval["angulo_panoramico"]) ? $eval["angulo_panoramico"] : ''; ?>">
			</div>

			<div class="form-group">
				<label>Ángulo Pantoscópico</label>
				<input type="text" name="angulo_pantoscopico" class="form-control" value="<?php echo $eval != null && isset($eval["angulo_pantoscopico"]) ? $eval["angulo_pantoscopico"] : ''; ?>">
			</div>

			<div class="form-group">
				<label>Distancia Vértica</label>
				<input type="text" name="distancia_vertice" class="form-control" value="<?php echo $eval != null && isset($eval["distancia_vertice"]) ? $eval["distancia_vertice"] : ''; ?>">
			</div>

         <div class="form-group">
				<label>Fecha/Hora de Entrega</label>
				<input type="datetime" name="fecha_entrega" class="form-control" id="fecha_entrega"  value="<?php echo isset($eval["fecha_entrega"]) ? $eval["fecha_entrega"] : ''; ?>">
			</div>

         <div class="form-group">
				<label>Observaciones</label>
				<textarea name="observaciones_lab" id="observaciones_lab" class="form-control" cols="30" rows="2"><?php echo $eval != null && isset($observaciones) ? $observaciones : ''; ?></textarea>
			</div>

			<center><button type="submit" class="btn btn-lg btn-success" style="margin-top:50px;"><b><i class="fa fa-save"></i> Guardar</b></button></center>
		
	</div>
   </form>

   <div class="col-md-12">
      <hr>
      <center>
         <h2>Adicional</h2>
      </center>
      <hr>
   </div>

   <div class="col-md-4">
      <div class="form-group">
         <label for="">Clínica</label>
         <input type="text" class="form-control" value="<?php echo $clinica;?>" readonly>
      </div>
   </div>

   <div class="col-md-4">
      <div class="form-group">
         <label for="">Doctor</label>
         <input type="text" class="form-control" value="<?php echo $doctor;?>" readonly>
      </div>
   </div>

   <div class="col-md-4">
      <div class="form-group">
         <label for="">Empresa Convenio</label>
         <input type="text" class="form-control" value="<?php echo $convenio;?>" readonly>
      </div>
   </div>

	<div class="col-md-12">
		<hr>
		<center>
			<h2>Lente</h2>
		</center>
		<hr>
		<div class="col-md-6">
			<div class="form-group">
				<label style="font-size: 1.4em; color:#000;">Material Lente</label>
				<input type="text" name="material_lente" class="form-control" value="<?php echo isset($eval["material_lente"]) ? $eval["material_lente"] : ''; ?>" id="material_lente" readonly>
				<input type="hidden" name="descripcion_lente_hide" id="descripcion_lente_hide" class="form-control" value="<?php echo isset($eval["descripcion_lente_hide"]) ? $eval["descripcion_lente_hide"] : ''; ?>">
				<input type="hidden" id="laboratorio_lente" name="laboratorio_lente" class="form-control" value="<?php echo isset($eval["laboratorio_lente"]) ? $eval["laboratorio_lente"] : ''; ?>">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label style="font-size: 1.4em; color:#000;">Precio Lente (S/.)</label>
				<input type="text" name="precio_lente" class="form-control" value="<?php echo isset($eval["precio_lente"]) ? $eval["precio_lente"] : ''; ?>" id="precio_lente" readonly>
			</div>
   		</div>
	</div>
  	 <div class="col-md-12">
      <hr>
      <center>
         <h2>Montura</h2>
      </center>
      <hr>
      <div class="col-md-5">
         <div class="form-group">
            <label>Buscar Montura ó Escanear Cod. Barras</label>
            <input type="text" name="montura" class="form-control" id="txtProducto" value="<?php echo ($eval != null && isset($eval["montura"])) ? $eval["montura"]:'';?>" readonly>
            <input type="hidden" name="id_montura" class="form-control" id="id_montura">
         </div>
      </div>
      <div class="col-md-2">
         <center>
            <p style="font-size:2em;">ó</p>
         </center>
      </div>
      <div class="col-md-5">
         <div class="form-group">
            <label>Montura de Paciente</label>
            <input type="text" name="montura_paciente" class="form-control" id="txtProducto2" value="<?php echo ($eval != null && isset($eval["montura_paciente"])) ? $eval["montura_paciente"]:'';?>" readonly>
         </div>
      </div>
      <div class="col-md-5">
         <div class="form-group">
            <label style="font-size: 1.4em; color:#000;">Tipo de Montura: </label><br/>
            <input type="radio" name="tipo_montura" value="Aro Completo" style="margin-left:20px;" <?php echo isset($eval["tipo_montura"]) && $eval["tipo_montura"] == "Aro Completo" ? 'checked' : ''; ?> disabled> <label>Aro Completo</label>
            <input type="radio" name="tipo_montura" value="Semi al Aire" style="margin-left:20px;" <?php echo isset($eval["tipo_montura"]) && $eval["tipo_montura"] == "Semi al Aire" ? 'checked' : ''; ?> disabled> <label>Semi al Aire</label>
            <input type="radio" name="tipo_montura" value="Al Aire" style="margin-left:20px;" <?php echo isset($eval["tipo_montura"]) && $eval["tipo_montura"] == "Al Aire" ? 'checked' : ''; ?> disabled> <label>Al Aire</label>
         </div>
         <div class="form-group">
            <label style="font-size: 1.4em; color:#000;">Montaje Con: </label><br/>
            <input type="radio" name="montaje" value="Bisell Brillante" style="margin-left:20px;" <?php echo isset($eval["montaje"]) && $eval["montaje"] == "Bisell Brillante" ? 'checked' : ''; ?>  checked disabled> <label>Bisell Brillante</label>
            <input type="radio" name="montaje" value="Faceteado" style="margin-left:20px;" <?php echo isset($eval["montaje"]) && $eval["montaje"] == "Faceteado" ? 'checked' : ''; ?> disabled> <label>Faceteado</label>
            <input type="radio" name="montaje" value="Pase de Lunas" style="margin-left:20px;" <?php echo isset($eval["montaje"]) && $eval["montaje"] == "Pase de Lunas" ? 'checked' : ''; ?> disabled> <label>Pase de Lunas</label>
            <input type="radio" name="montaje" value="Reduccion de Diametro" style="margin-left:20px;" <?php echo isset($eval["montaje"]) && $eval["montaje"] == "Reduccion de Diametro" ? 'checked' : ''; ?> disabled> <label>Reduccion de Diametro</label>
         </div>
      </div>
      <div class="col-md-6">
         <!-- aqui info-->
      </div>
   </div>

   <div class="col-md-12">
      <object data="<?php echo base_url("ventas/ticketOrdenLab/".$id_ord); ?>" type="application/pdf" width="100%" height="500px"> 
			<p><a href="<?php echo base_url("ventas/ticketOrdenLab/".$id_ord); ?>">Click para ver PDF.</a></p>  
		</object>
   </div>
</div>
<div id="myModal" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Ingresar Factura</h4>
         </div>
         <div class="modal-body">
            <div class="col-md-12">
               <div class="alert alert-success">
                  <strong>Listo!</strong> Se guardó el estado. Ahora debe agregar el monto facturado por el proveedor.
               </div>
            </div>
            <form id="data" method="post" enctype="multipart/form-data">
               <div class="col-md-4">
                  <div class="form-group">
                     <label>Proveedor</label>
                     <input id="txtProveedores" autocomplete="off" name="Nombre" type="text" class="form-control required" placeholder="Nombre del proveedor" value="" />
                     <input type="hidden" name="idprove" id="idprove" value="">
                     <input type="hidden" name="id_orden" id="id_orden" value="<?php echo isset($id_ord)?$id_ord:'';?>">
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>Total (S/.)</label>
                     <input type="text" class="form-control" name="total">
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>Fecha</label>
                     <input type="date" class="form-control" name="fecha" value="<?php echo date('Y-m-d');?>">
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label>Nro. Comprobante</label>
                     <input type="text" class="form-control" name="factura">
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label>Pago</label>
                     <select name="origen_dinero" id="origen_dinero" class="form-control">
                        <option value="1">Caja Chica</option>
                        <option value="2">Deuda</option>
                     </select>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Descripcion</label>
                     <input type="text" class="form-control" name="material" value="<?php echo isset($eval["material_lente"]) ? $eval["material_lente"] : ''; ?>">
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label>Observaciones</label>
                     <input type="text" class="form-control" name="observaciones">
                  </div>
               </div>
               <div class="col-md-6" style="display:none;">
                  <label>Imagen de Factura</label>
                  <input type="file" name="image">
               </div>
               <div class="col-md-6">
                  <br/>
                  <button class="btn btn-success" type="submit"><strong>Guardar</strong></button>
               </div>
               <div style="clear:both;"></div>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
         </div>
      </div>
   </div>
</div>
<script>
   $(document).ready(function() {
      $("#fecha_entrega").datetimepicker({format: 'DD/MM/YYYY|HH:mm'});
   });
   $("#formeva").submit(function(event){
      	event.preventDefault(); //prevent default action 
      	var post_url = $(this).attr("action"); //get form action url
      	var request_method = $(this).attr("method"); //get form GET/POST method
      	var form_data = $(this).serialize(); //Encode form elements for submission
   
      	var form = $(this).closest("form");
      	var block = $('<div class="block-loading" id="bloquecarga">');
          $("#loa").prepend(block);
       $.ajax({
           url : post_url,
           type: request_method,
           data : form_data,
           dataType: 'JSON',
       }).done(function(response){ //
   
           $("#server-results").html(response);
           $("#bloquecarga").remove();
           if (response.href != undefined) {
                      if (response.href == 'self') window.location.reload(true);
                      else window.location.href = base_url(response.href);
               }
       });
   });
   <?php
      if($id_ord!=0){
      ?>
   $("#updestado").on("change", function(){
   	$.ajax({
   		url: base_url('mantenimiento/orden_lab_updestado'),
   		type: "post",
              dataType: "json",
              data: {estado: $("#updestado").val(), idord:<?php echo "".($id_ord!=0) ? $id_ord:null."";?>},
   	success: function(respuesta) {
   		//console.log(respuesta[0].endurecido);
   		if($("#updestado").val() == 2){
   			$('#myModal').modal('show');
   		}else{
   			alert("Estado Actualizado!");
   		}
   		
   	},
   	error: function() {
   	     console.log("No se ha podido obtener la información");
   	}
   });
   });
   <?php
      }
      ?>
   $("form#data").submit(function(e) {
      e.preventDefault();    
      var formData = new FormData(this);
   
      $.ajax({
          url: "<?php echo base_url();?>mantenimiento/nuevaCompraOrd",
          type: 'POST',
          data: formData,
          dataType: "json",
          success: function (response) {
              //alert(response.href);
              if (response.href != undefined) {
                      if (response.href == 'self') window.location.reload(true);
                      else window.location.href = base_url(response.href);
               }
   
          },
          cache: false,
          contentType: false,
          processData: false
      });
   });
</script>
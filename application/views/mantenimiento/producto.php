<?php
  //var_dump($asignada); 
?>
<script>

$(document).on("change", "#checkcalculo",function(){
  //alert("asd");
  var valorg = parseFloat($("#PrecioCompra").val());

  if($("#checkcalculo").is(':checked')){
    $("#costoOrigen").val(valorg);
    var calcu = valorg + (valorg * 0.22);
    $("#PrecioCompra").val(calcu.toFixed(2));
  }else{
    var prevValor = parseFloat($("#costoOrigen").val());
    $("#PrecioCompra").val(prevValor.toFixed(2));
  }
});

$(document.body).on('keyup', "#Precio", function(event) {
  changeRange();
});

$(document).ready(function(){
    BuscarProductos();
    BuscarMarcas();
    BuscarMedidas();
    BuscarCat();
    
    $("#CostoBase").on("keyup", function(){
      var valorg = parseFloat($("#CostoBase").val());
      $("#costoOrigen").val(valorg);
      var cbase_igv = parseFloat(valorg - (valorg/1.18));
      var cbase_per = parseFloat(valorg * 0.055);
      var costotal = parseFloat(valorg + cbase_igv + cbase_per);

      $("#PrecioCompra").val(costotal.toFixed(2));

      var pventa = parseFloat(costotal * 2.3);

      $("#Precio").val(pventa.toFixed(2));

      changeRange();
        
    });

    $("#CostoBaseSinIgv").on("keyup", function(){
      var val_base = parseFloat($("#CostoBaseSinIgv").val());
      var val_con_igv = parseFloat(val_base*1.18);
      var precio_venta = parseFloat(val_base*2.5).toFixed(2);

      $("#CostoBase").val(val_con_igv);
      $("#PrecioCompra").val(val_con_igv);
      $("#Precio").val(precio_venta);
    });


    //Multiplicamos costo total * 2.5
    $("#PrecioCompra").on("keyup", function(){
      var precio_venta = parseFloat($("#PrecioCompra").val() * 2.5).toFixed(2);

      $("#Precio").val(precio_venta);
    });
    
})

function changeRange(){
  var prec = $("#Precio").val();
  if(parseFloat(prec) <= 150){
    $("#rango").val("ECONOMICA");
  }else if(parseFloat(prec) > 150 && parseFloat(prec) <= 350){
    $("#rango").val("INTERMEDIA");
  }else if(parseFloat(prec) > 350){
    $("#rango").val("CARA");
  }
}

function BuscarMedidas()
{
  var input = $("#txtUnidadMedida_id");

    input.autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: base_url('services/medidas'),
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            value: item.UnidadMedida_id
                        }
                    }))
                }
            })
        },
        search  : function(){$(this).addClass('ui-autocomplete-loading');},
        open    : function(){$(this).removeClass('ui-autocomplete-loading');},
        select: function(e, ui){
            input.blur();
    }
    })
}
function BuscarMarcas()
{
  var input = $("#txtMarca");

    input.autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: base_url('services/marcas'),
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            value: item.Marca
                        }
                    }))
                }
            })
        },
        search  : function(){$(this).addClass('ui-autocomplete-loading');},
        open    : function(){$(this).removeClass('ui-autocomplete-loading');},
        select: function(e, ui){
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
                            nombre: item.NombreSimple,
                            precio: item.Precio
                        }
                    }))
                }
            })
        },
        search  : function(){$(this).addClass('ui-autocomplete-loading');},
        open    : function(){$(this).removeClass('ui-autocomplete-loading');},
        select: function (e, ui) {
          input.blur();
        }
    })
}
function BuscarCat()
{
    var input = $("#categoria_id");

    input.autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: base_url('services/productosCat'),
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            value: item.categoria
                        }
                    }))
                }
            })
        },
        search  : function(){$(this).addClass('ui-autocomplete-loading');},
        open    : function(){$(this).removeClass('ui-autocomplete-loading');},
        select: function (e, ui) {
          input.blur();
        }
    })
}
</script>
<div class="row">
  <div class="col-md-12">
    <div class="page-header">
      <h1><?php echo $producto == null ? "Nuevo Producto" : $producto->Nombre; ?></h1>
      <?php if(isset($producto)): ?>
        <div class="clearfix">
          <a class="btn btn-success" href="<?php echo base_url('index.php/mantenimiento/producto'); ?>">Nuevo Producto</a>
        </div>
      <?php endif; ?>
    </div>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
      <li><a href="<?php echo base_url('index.php/mantenimiento/productos'); ?>">Productos</a></li>
      <li class="active"><?php echo $producto == null ? "Nuevo Producto" : $producto->Nombre; ?></li>
    </ol>
    <div class="row">
        <div class="row">
            <div class="col-md-12">
                <div class="well well-sm">(*) Campos obligatorios</div>
                <?php echo form_open('mantenimiento/productocrud', array('class' => 'upd', 'enctype'=>'multipart/form-data')); ?>
                <?php if($producto != null): ?>
                <input type="hidden" name="id" value="<?php echo $producto->id; ?>" />
                <?php endif; ?>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Nombre (*)</label>
                    <input autocomplete="off" id="txtProducto" name="nombre" type="text" class="form-control" placeholder="Nombre del producto" value="<?php echo $producto != null ? $producto->Nombre : null; ?>" />
                  </div>
                </div>
                  <?php if(!$asignada): ?>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Marca</label>
                    <input id="txtMarca" autocomplete="off" name="Marca" type="text" class="form-control" placeholder="Marca" value="<?php echo $producto != null ? $producto->Marca : null; ?>" />
                    <span class="help-block">Si no desea registrar una marca deje esta casilla en blanco, el sistema la reconocera con el prefijo de <b>S/M</b>.</span>
                  </div>
                </div>
                <div class="col-md-2">
                      <div class="form-group">
                        <label>Código Interno</label>
                        <input autocomplete="off" name="codigo_prod" type="text" class="form-control" placeholder="Codigo" value="<?php echo $producto != null ? $producto->codigo_prod : null; ?>" maxlength="50" />
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                        <label>Código Varilla</label>
                        <input id="codigo_varilla" autocomplete="off" name="codigo_varilla" type="text" class="form-control" placeholder="Código de Varilla" value="<?php echo $producto != null ? $producto->codigo_varilla : null; ?>"/>
                      </div>
                  </div>
                  
                  <?php endif; ?>
                  <?php if($asignada): ?>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Marca</label>
                        <input type="text" class="form-control" readonly="readonly" value="<?php echo $producto != null ? $producto->Marca : null; ?>" />
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label>Código Interno</label>
                        <input autocomplete="off" name="codigo_prod" type="text" class="form-control" placeholder="Codigo" value="<?php echo $producto != null ? $producto->codigo_prod : null; ?>" maxlength="50" />
                      </div>
                  </div>
                      <div class="col-md-2">
                          <div class="form-group">
                            <label>Código Varilla</label>
                            <input type="text" class="form-control" placeholder="Código de Varilla" value="<?php echo $producto != null ? $producto->codigo_varilla : null; ?>" />
                          </div>
                      </div>
                  
                  <?php endif; ?>
                <div style="clear:both;"></div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label style="color: red;">Código SUNAT</label>
                    <input type="text" class="form-control" name="codigo_sunat" value="<?php echo $producto != null ? $producto->codigo_sunat : null; ?>" />
                  </div>
                </div>
                <?php if(HasModule('stock') && $producto == null): ?>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Stock Inicial</label>
                    <input type="text" class="form-control" name="Stock" class="price" value="0.00" />
                    <span class="help-block">Esta cantidad será registrada como <b>stock inicial</b>.</span>
                  </div>
                </div>
                <?php endif; ?>
                <?php if(HasModule('stock') && $producto != null): ?>
                
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Stock Mínimo</label>
                    <input name="StockMinimo" type="text" class="form-control price" value="<?php echo $producto != null ? $producto->StockMinimo : '0.00'; ?>" />
                  </div>
                </div>
                <?php endif; ?>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Costo Base SIN IGV (*)</label>
                    <input autocomplete="off" name="CostoBaseSinIgv"  type="text" class="form-control required price" placeholder="S/." value="<?php echo $producto != null ? $producto->CostoBaseSinIgv : null; ?>" id="CostoBaseSinIgv" />
                    <!--<input type="checkbox" name="checkcalculo" id="checkcalculo"> Adicionar 22%-->
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Costo Base CON IGV (*)</label>
                    <input autocomplete="off" name="CostoBase"  type="text" class="form-control required price" placeholder="S/." value="<?php echo $producto != null ? $producto->CostoBase : null; ?>" id="CostoBase" />
                    <!--<input type="checkbox" name="checkcalculo" id="checkcalculo"> Adicionar 22%-->
                    <input type="hidden" id="costoOrigen">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Costo Total(*)</label>
                    <input autocomplete="off" name="PrecioCompra"  type="text" class="form-control required price" placeholder="S/." value="<?php echo $producto != null ? $producto->PrecioCompra : null; ?>" id="PrecioCompra" />
                    <!--<input type="checkbox" name="checkcalculo" id="checkcalculo"> Adicionar 22%-->
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Precio de Venta (*)</label>
                    <input autocomplete="off" name="Precio" id="Precio" type="text" class="form-control price" placeholder="Precio de Venta" value="<?php echo $producto != null ? $producto->Precio : null; ?>" />
                  </div>
                </div>
                <div style="clear:both;"></div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Categoria</label>
                    <input autocomplete="off" name="categoria"  type="text" class="form-control categoria" placeholder="Categoria" id="categoria_id" value="<?php echo $producto != null ? $producto->categoria : null; ?>" />
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Rango</label>
                    <select name="rango" id="rango" class="form-control">
                      <option value="NINGUNO" <?php echo ($producto != null && $producto->rango == "NINGUNO") ? "selected" : null; ?>>NINGUNO</option>
                      <option value="ECONOMICA" <?php echo ($producto != null && $producto->rango == "ECONOMICA") ? "selected" : null; ?>>ECONOMICA</option>
                      <option value="INTERMEDIA" <?php echo ($producto != null && $producto->rango == "INTERMEDIA") ? "selected" : null; ?>>INTERMEDIA</option>
                      <option value="CARA" <?php echo ($producto != null && $producto->rango == "CARA") ? "selected" : null; ?>>CARA</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Material</label>
                    <input autocomplete="off" name="material"  type="text" class="form-control material" placeholder="Material" value="<?php echo $producto != null ? $producto->material : null; ?>" />
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Tipo de Aro</label>
                    
                    <select name="tipo_aro" id="tipo_aro" class="form-control">
                      <?php 
                      if($producto != null){
                      $datostipo = ["ARO COMPLETO", "SEMI AL AIRE", "AL AIRE"];
                        foreach($datostipo as $dg){
                          if($producto->tipo_aro==$dg){
                            echo '<option value="'.$dg.'" selected>'.$dg.'</option>';
                          }else{
                            echo '<option value="'.$dg.'">'.$dg.'</option>';
                          }
                          
                        }
                      }else{
                        echo '<option value="ARO COMPLETO">ARO COMPLETO</option>
                        <option value="SEMI AL AIRE">SEMI AL AIRE</option>
                        <option value="AL AIRE">AL AIRE</option>';
                      }
                    ?>
                    </select>

                    
                  </div>
                </div>
                
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Forma Montura</label>
                    <select name="forma_montura" id="" class="form-control">
                    <?php 
                      if($producto != null){
                      $datosgen = ["Rectangular", "Cuadrada", "Ovalada", "Agatada", "Browline", "Redonda", "Aviador"];
                        foreach($datosgen as $dg){
                          if($producto->forma_montura==$dg){
                            echo '<option value="'.$dg.'" selected>'.$dg.'</option>';
                          }else{
                            echo '<option value="'.$dg.'">'.$dg.'</option>';
                          }
                          
                        }
                      }else{
                        echo '<option value="Rectangular">Rectangular</option>
                        <option value="Cuadrada">Cuadrada</option>
                        <option value="Ovalada">Ovalada</option>
                        <option value="Agatada">Agatada</option>
                        <option value="Browline">Browline</option>
                        <option value="Redonda">Redonda</option>
                        <option value="Aviador">Aviador</option>';
                      }
                    ?>
                    </select>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label>Tipo Rostro</label>
                    <select name="tipo_rostro" id="" class="form-control">
                    <?php 
                      if($producto != null){
                      $datosgen = ["Redondo", "Cuadrado", "Alargado", "Triangular", "Ovalado"];
                        foreach($datosgen as $dg){
                          if($producto->tipo_rostro==$dg){
                            echo '<option value="'.$dg.'" selected>'.$dg.'</option>';
                          }else{
                            echo '<option value="'.$dg.'">'.$dg.'</option>';
                          }
                          
                        }
                      }else{
                        echo '<option value="Redondo">Redondo</option>
                        <option value="Cuadrado">Cuadrado</option>
                        <option value="Alargado">Alargado</option>
                        <option value="Triangular">Triangular</option>
                        <option value="Ovalado">Ovalado</option>';
                      }
                    ?>
                    </select>
                  </div>
                </div>
                
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Sexo</label>
                    <select name="sexo" id="" class="form-control">
                    <?php 
                      if($producto != null){
                      $datosgen = ["N/A", "NIÑA", "NIÑO", "VARON", "DAMA"];
                        foreach($datosgen as $dg){
                          if($producto->sexo==$dg){
                            echo '<option value="'.$dg.'" selected>'.$dg.'</option>';
                          }else{
                            echo '<option value="'.$dg.'">'.$dg.'</option>';
                          }
                          
                        }
                      }else{
                        echo '<option value="N/A">N/A</option>
                        <option value="NIÑA">NIÑA</option>
                        <option value="NIÑO">NIÑO</option>
                        <option value="VARON">VARON</option>
                        <option value="DAMA">DAMA</option>';
                      }
                    ?>
                      
                    </select>
                  </div>
                </div>

                <div class="col-md-3">
                  <label>Código Proveedor: </label><br/>
                  <select name="codigo_proveedor" id="" class="form-control">
                    <?php
                    foreach($proveedores as $prov){
                      if($producto != null && $producto->codigo_proveedor == $prov->codigo_proveedor){
                        echo '<option value="'.$prov->codigo_proveedor.'" selected>'.$prov->Nombre.'</option>';
                      }else{
                        echo '<option value="'.$prov->codigo_proveedor.'">'.$prov->Nombre.'</option>';
                      }
                    }
                    ?>
                  </select>
                </div>

                <div style="clear:both;"></div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Características Adicionales</label>
                    <select name="caracteristicas_adicionales" id="" class="form-control">
                    <?php 
                      if($producto != null){
                      $datosgen = ["Ninguna", "Polarizada", "Espejada", "Con Plaquetas"];
                        foreach($datosgen as $dg){
                          if($producto->caracteristicas_adicionales==$dg){
                            echo '<option value="'.$dg.'" selected>'.$dg.'</option>';
                          }else{
                            echo '<option value="'.$dg.'">'.$dg.'</option>';
                          }
                          
                        }
                      }else{
                        echo '<option value="Ninguna">Ninguna</option>
                        <option value="Polarizada">Polarizada</option>
                        <option value="Espejada">Espejada</option>
                        <option value="Con Plaquetas">Con Plaquetas</option>';
                      }
                    ?>
                    </select>
                  </div>
                </div>
                

                <div class="col-md-3">
                  <div class="form-group">
                    <label>Descripción</label>
                    <input autocomplete="off" name="descripcion"  type="text" class="form-control" placeholder="Descripción" value="<?php echo $producto != null ? $producto->descripcion : null; ?>" />
                  </div>
                </div>
                
                <?php if(HasModule('stock') && $producto != null): ?>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Stock Actual</label>
                      <div class="input-group">
                        <input type="text" name="Stock" class="form-control" value="<?php echo $producto != null ? $producto->Stock : null; ?>"/>
                      </div>
                    </div>
                  </div>

                   
                <?php endif; ?>
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="">Producto Reto</label><br/>
                    <input type="radio" name="reto" value="1" <?php echo $producto != null && $producto->reto == 1 ? "checked":"";?>> SI
                    <input type="radio" name="reto" value="0" <?php echo $producto != null && $producto->reto == 0 ? "checked":"";?> style="margin-left:20px;"> NO
                  </div> 
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label for="">Sobrelentes</label><br/>
                    <input type="radio" name="sobrelentes" value="1" <?php echo $producto != null && $producto->sobrelentes == 1 ? "checked":"";?>> SI
                    <input type="radio" name="sobrelentes" value="0" <?php echo $producto != null && $producto->sobrelentes == 0 ? "checked":"";?> style="margin-left:20px;"> NO
                  </div>
                </div> 

                <div class="col-md-4" style="display:none;">
                  <label>Imagen de Producto: </label><br/>
                  <?php if($producto != null && $producto->imagen!=null){ ?>
                    <img src="<?php echo base_url(); ?>uploads/<?php echo $producto->imagen; ?>" style="width:200px;">
                  <?php }else{ ?>
                    <h4>Sin imagen</h4>
                  <?php } ?>
                  <input type='file' name='prdimage' />
                </div>

                <div style="clear:both;"></div>
                
                <div class="col-md-12 text-center">
                    <hr>
                    <h3>Talla</h3>
                    <hr>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                      <label for="">Diagonal</label>
                      <input type="text" class="form-control" name="medida_diagonal" value="<?php echo $producto != null ? $producto->medida_diagonal : null; ?>">
                    </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                      <label for="">Puente</label>
                      <input type="text" class="form-control" name="medida_puente" value="<?php echo $producto != null ? $producto->medida_puente : null; ?>">
                    </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                      <label for="">Varilla</label>
                      <input type="text" class="form-control" name="medida_varilla" value="<?php echo $producto != null ? $producto->medida_varilla : null; ?>">
                    </div>
                </div>

                <div class="col-md-12 text-center">
                    <hr>
                </div>

                  <div class="clearfix text-center">
                  <?php if(isset($producto)): ?>
                    <button type="button" class="btn btn-danger submit-ajax-button del" value="<?php echo base_url('index.php/mantenimiento/productoeliminar/' . $producto->id); ?>">Eliminar</button>
                  <?php endif; ?>
                    <button type="submit" class="btn btn-success submit-ajax-button">Guardar</button>
                  </div>
                <?php echo form_close(); ?>
            </div>
        </div>
  </div>
</div>
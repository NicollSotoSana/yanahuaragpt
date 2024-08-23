<form method="post" action="<?php echo base_url("Conformidadmonturas/editConformidad");?>">
    <input type="hidden" name="id_orden_lab" value="<?php echo $id_orden_lab;?>">
    <input type="hidden" name="id_comprobante" value="<?php echo $id_comprobante;?>">
    <?php $datos = json_decode($conformidad->conformidad_data, true); //var_dump($datos);?>
    <div class="row">
        <div class="col-md-3">
            <label for="">Calidad de Armazón</label>
        </div>
        <div class="col-md-2">
            <b>Si</b> <input type="radio" value="1" name="calidad_armazon" <?php echo ($datos["calidad_armazon"] == 1) ? 'checked':'';?>>
        </div>
        <div class="col-md-2">
            <b>No</b> <input type="radio" value="0" name="calidad_armazon" <?php echo ($datos["calidad_armazon"] == 0) ? 'checked':'';?>>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="observaciones_calidad" placeholder="Obervaciones" value="<?php echo $datos["observaciones_calidad"];?>">
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3">
            <label for="">Estado de Varillas</label>
        </div>
        <div class="col-md-2">
            <b>Si</b> <input type="radio" value="1" name="estado_varillas" <?php echo ($datos["estado_varillas"] == 1) ? 'checked':'';?>>
        </div>
        <div class="col-md-2">
            <b>No</b> <input type="radio" value="0" name="estado_varillas" <?php echo ($datos["estado_varillas"] == 0) ? 'checked':'';?>>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="observaciones_estado_varilla" placeholder="Obervaciones" value="<?php echo $datos["observaciones_estado_varilla"];?>">
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3">
            <label for="">Estado de partes móviles</label>
        </div>
        <div class="col-md-2">
            <b>Si</b> <input type="radio" value="1" name="partes_moviles" <?php echo ($datos["partes_moviles"] == 1) ? 'checked':'';?>>
        </div>
        <div class="col-md-2">
            <b>No</b> <input type="radio" value="0" name="partes_moviles" <?php echo ($datos["partes_moviles"] == 0) ? 'checked':'';?>>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="observaciones_partes_moviles"  placeholder="Obervaciones" value="<?php echo $datos["observaciones_partes_moviles"];?>">
        </div>
    </div>

    <hr>
    <div class="row">
        <div class="col-md-3">
            <label for="">Estado de pintado</label>
        </div>
        <div class="col-md-2">
            <b>Si</b> <input type="radio" value="1" name="estado_pintado" <?php echo ($datos["estado_pintado"] == 1) ? 'checked':'';?>>
        </div>
        <div class="col-md-2">
            <b>No</b> <input type="radio" value="0" name="estado_pintado" <?php echo ($datos["estado_pintado"] == 0) ? 'checked':'';?>>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="observaciones_estado_pintado"  placeholder="Obervaciones" value="<?php echo $datos["observaciones_estado_pintado"];?>">
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <label for="">Otros:</label><br/>
            <input type="text" class="form-control" name="otros" value="<?php echo $datos["otros"];?>">
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <center><button class="btn btn-success">Guardar</button></center>
        </div>
    </div>
</form>
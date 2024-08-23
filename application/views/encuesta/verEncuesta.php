<div class="col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><span class="glyphicon glyphicon-hand-right"></span> <b>Respuestas del Cliente</b></h3>
        </div>
        <div class="panel-body">
            <?php
            if($datos == null){
                echo 'Aún no hay respuestas para mostrar.';
            }else{
            ?>
            <table class="table">
                <tbody>
                <?php
	                foreach($datos as $d){
                        if($d["respuesta"]==1){
                            echo '<tr>
                                <td><span style="font-size:1.2em;">'.$d["pregunta"].'</span></td>
                                <td>
                                    <div class="funkyradio">
                                        <div class="funkyradio-success">
                                            <input type="radio" name="pregunta_'.$d["id_pregunta"].'" id="si'.$d["id_pregunta"].'" value="1" checked disabled/>
                                            <label for="si'.$d["id_pregunta"].'" style="margin-top:0;">Si</label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="funkyradio">
                                        <div class="funkyradio-danger">
                                            <input type="radio" name="pregunta_'.$d["id_pregunta"].'" id="no'.$d["id_pregunta"].'" value="0" disabled/>
                                            <label for="no'.$d["id_pregunta"].'" style="margin-top:0;">No</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>';
                        }else if($d["respuesta"]==0){
                            echo '<tr>
                                <td><span style="font-size:1.2em;">'.$d["pregunta"].'</span></td>
                                <td>
                                    <div class="funkyradio">
                                        <div class="funkyradio-success">
                                            <input type="radio" name="pregunta_'.$d["id_pregunta"].'" id="si'.$d["id_pregunta"].'" value="1" disabled/>
                                            <label for="si'.$d["id_pregunta"].'" style="margin-top:0;">Si</label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="funkyradio">
                                        <div class="funkyradio-danger">
                                            <input type="radio" name="pregunta_'.$d["id_pregunta"].'" id="no'.$d["id_pregunta"].'" value="0" checked disabled/>
                                            <label for="no'.$d["id_pregunta"].'" style="margin-top:0;">No</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>';
                        }
                        
                    }
                ?>
                <tr>
                    <td colspan="6">
                        <div class="form-group">
                            <label for="">Sugerencia</label>
                            <textarea name="sugerencia" class="form-control" id="sugerencia"></textarea>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
                <?php }?>
        </div>
    </div>
</div>
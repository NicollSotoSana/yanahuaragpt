<?php //array_debug($indicadores);?>
<div class="row" style="background:#fff; overflow-y:auto;">
    <div class="col-md-12"><h1>Indicadores (<?php echo $mes;?>/<?php echo $anio;?>)</h1></div>
    <div class="col-md-4">
        <table class="table table-bordered">
            <thead>
                <th colspan="4" style="text-align:center">Resumen</th>
            </thead>
            <thead>
                <th>Objetivo<br>&nbsp;</th>
                <th>Total<br>&nbsp;</th>
                <th>Tot. mes<br>&nbsp;</th>
                <th>Porcentaje<br>&nbsp;</th>
            </thead>
            <tbody>
                <tr>
                    <td>Plan Fam.</td>
                    <td><?php echo $objetivos->plan_fam;?></td>
                    <td><?php echo $totales["plan_familiar"];?></td>
                    <td><?php echo number_format(($totales["plan_familiar"]*100)/$objetivos->plan_fam, 2);?>%</td>
                </tr>
                <tr>
                    <td>Dig. Monofocal</td>
                    <td><?php echo $objetivos->lentes_digi;?></td>
                    <td><?php echo $totales["total_digital_monofocal"];?></td>
                    <td><?php echo number_format(($totales["total_digital_monofocal"]*100)/$objetivos->lentes_digi, 2);?>%</td>
                </tr>
                <tr>
                    <td>Marcas Reto</td>
                    <td><?php echo $objetivos->marcas_desc;?></td>
                    <td><?php echo $totales["marcas_reto"];?></td>
                    <td><?php echo number_format(($totales["marcas_reto"]*100)/$objetivos->marcas_desc, 2);?>%</td>
                </tr>
                <tr>
                    <td>Multifocal Top</td>
                    <td><?php echo $objetivos->multifocal_top;?></td>
                    <td><?php echo $totales["total_multifocal_top"];?></td>
                    <td><?php echo number_format(($totales["total_multifocal_top"]*100)/$objetivos->multifocal_top, 2);?>%</td>
                </tr>
                <tr>
                    <td>Liquidos</td>
                    <td><?php echo $objetivos->liquidos;?></td>
                    <td><?php echo $totales["liquidos"];?></td>
                    <td><?php echo number_format(($totales["liquidos"]*100)/$objetivos->liquidos, 2);?>%</td>
                </tr>
                <tr>
                    <td>Lentes Solares</td>
                    <td><?php echo $objetivos->lentes_solares;?></td>
                    <td><?php echo $totales["solares"];?></td>
                    <td><?php echo number_format(($totales["solares"]*100)/$objetivos->lentes_solares, 2);?>%</td>
                </tr>
                <tr>
                    <td>Peeps</td>
                    <td><?php echo $objetivos->peeps;?></td>
                    <td><?php echo $totales["peeps"];?></td>
                    <td><?php echo number_format(($totales["peeps"]*100)/$objetivos->peeps, 2);?>%</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-8">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th colspan="<?php echo $usuarios_total;?>" style="text-align:center;">Realizado</th>
                    <th colspan="<?php echo $usuarios_total;?>" style="text-align:center;">Esperado</th>
                    <th colspan="<?php echo $usuarios_total;?>" style="text-align:center;">Resultado</th>
                </tr>
                <tr>
                <?php
                //var_dump($usuarios_todos);
                    foreach($usuarios_todos as $utodos){
                        echo '<th>'.$utodos->Nombre.'</th>';
                    }
                    foreach($usuarios_todos as $utodos){
                        echo '<th>'.$utodos->Nombre.'</th>';
                    }
                    foreach($usuarios_todos as $utodos){
                        echo '<th>'.$utodos->Nombre.'</th>';
                    }
                ?>
                </tr>
                
            </thead>
            <tbody>
                <tr>
                    <?php
                        foreach($usuarios_todos as $utodos){
                            echo '<td><a href="#" target="_blank">'.$indicadores[$utodos->id]["plan_familiar"].'</a></td>';
                        }
                        foreach($usuarios_todos as $utodos){
                            echo '<td>'.number_format($objetivos->plan_fam/$usuarios_total, 2).'</td>';
                        }
                        foreach($usuarios_todos as $utodos){
                            echo '<td>'.number_format(($indicadores[$utodos->id]["plan_familiar"]*100)/$objetivos->plan_fam, 2).'%</td>';
                        }
                    ?>
                </tr>

                <tr>
                    <?php
                        foreach($usuarios_todos as $utodos){
                            echo '<td><a href="'.base_url("Objetivos/indicadoresUsuario/$utodos->id/plan_familiar/$mes/$anio").'" target="_blank">'.$indicadores[$utodos->id]["total_digital_monofocal"].'</a></td>';
                        }
                        foreach($usuarios_todos as $utodos){
                            echo '<td>'.number_format($objetivos->lentes_digi/$usuarios_total, 2).'</td>';
                        }
                        foreach($usuarios_todos as $utodos){
                            echo '<td>'.number_format(($indicadores[$utodos->id]["total_digital_monofocal"]*100)/$objetivos->lentes_digi, 2).'%</td>';
                        }
                    ?>
                </tr>

                <tr>
                    <?php
                        foreach($usuarios_todos as $utodos){
                            echo '<td><a href="'.base_url("Objetivos/marcas_reto/$utodos->id/plan_familiar/$mes/$anio").'" target="_blank">'.$indicadores[$utodos->id]["marcas_reto"].'</a></td>';
                        }
                        foreach($usuarios_todos as $utodos){
                            echo '<td>'.number_format($objetivos->marcas_desc/$usuarios_total, 2).'</td>';
                        }
                        foreach($usuarios_todos as $utodos){
                            echo '<td>'.number_format(($indicadores[$utodos->id]["marcas_reto"]*100)/$objetivos->marcas_desc, 2).'%</td>';
                        }
                    ?>
                </tr>

                <tr>
                    <?php
                        foreach($usuarios_todos as $utodos){
                            echo '<td><a href="'.base_url("Objetivos/indicadoresUsuario/$utodos->id/multifocal_top/$mes/$anio").'" target="_blank">'.$indicadores[$utodos->id]["total_multifocal_top"].'</a></td>';
                        }
                        foreach($usuarios_todos as $utodos){
                            echo '<td>'.number_format($objetivos->multifocal_top/$usuarios_total, 2).'</td>';
                        }
                        foreach($usuarios_todos as $utodos){
                            echo '<td>'.number_format(($indicadores[$utodos->id]["total_multifocal_top"]*100)/$objetivos->multifocal_top, 2).'%</td>';
                        }
                    ?>
                </tr>

                <tr>
                    <?php
                        foreach($usuarios_todos as $utodos){
                            echo '<td><a href="'.base_url("Objetivos/indicadoresUsuario/$utodos->id/liquidos/$mes/$anio").'" target="_blank">'.$indicadores[$utodos->id]["liquidos"].'</a></td>';
                        }
                        foreach($usuarios_todos as $utodos){
                            echo '<td>'.number_format($objetivos->liquidos/$usuarios_total, 2).'</td>';
                        }
                        foreach($usuarios_todos as $utodos){
                            echo '<td>'.number_format(($indicadores[$utodos->id]["liquidos"]*100)/$objetivos->liquidos, 2).'%</td>';
                        }
                    ?>
                </tr>

                <tr>
                    <?php
                        foreach($usuarios_todos as $utodos){
                            echo '<td><a href="'.base_url("Objetivos/indicadoresUsuario/$utodos->id/solares/$mes/$anio").'" target="_blank">'.$indicadores[$utodos->id]["solares"].'</a></td>';
                        }
                        foreach($usuarios_todos as $utodos){
                            echo '<td>'.number_format($objetivos->lentes_solares/$usuarios_total, 2).'</td>';
                        }
                        foreach($usuarios_todos as $utodos){
                            echo '<td>'.number_format(($indicadores[$utodos->id]["solares"]*100)/$objetivos->lentes_solares, 2).'%</td>';
                        }
                    ?>
                </tr>

                <tr>
                    <?php
                        foreach($usuarios_todos as $utodos){
                            echo '<td><a href="'.base_url("Objetivos/indicadoresUsuario/$utodos->id/peeps/$mes/$anio").'" target="_blank">'.$indicadores[$utodos->id]["peeps"].'</a></td>';
                        }
                        foreach($usuarios_todos as $utodos){
                            echo '<td>'.number_format($objetivos->peeps/$usuarios_total, 2).'</td>';
                        }
                        foreach($usuarios_todos as $utodos){
                            echo '<td>'.number_format(($indicadores[$utodos->id]["peeps"]*100)/$objetivos->peeps, 2).'%</td>';
                        }
                    ?>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <th style="text-align:center;">Usuario</th>
                <th style="text-align:center;">Cant. Ventas</th>
                <th style="text-align:center;">Tot. Protocolos</th>
                <th style="text-align:center;">% Proto. Aprob.</th>
                <th style="text-align:center;">% Satisfac.</th>
                <th style="text-align:center;">Trios</th>
                <th style="text-align:center; width:80px;">Trios</th>
                <th style="text-align:center;">Solar</th>
                <th style="text-align:center; width:80px;">Solar</th>
                <th style="text-align:center;">Multifocal Top</th>
                <th style="text-align:center;">Multifocal Top</th>
                <th style="text-align:center;">Monofocal Digital</th>
                <th style="text-align:center;">Monofocal Digital</th>
            </thead>
            <tbody>
                <?php
                    foreach($usuarios_todos as $ut){
                        //array_debug($indicadores[$ut->id]);
                        echo '<tr>';
                        echo '<td>'.$ut->Nombre.'</td>';
                        echo '<td>'.$indicadores[$ut->id]["total_ventas"].'</td>';
                        echo '<td>'.$indicadores[$ut->id]["tot_protocolos"].'</td>';
                        echo '<td>'.number_format(($indicadores[$ut->id]["tot_protocolos_aprob"]*100)/$indicadores[$ut->id]["tot_protocolos"], 2).' %</td>';
                        echo '<td>'.number_format(($indicadores[$ut->id]["tot_satisfac"]*100)/($indicadores[$ut->id]["tot_protocolos"]*5), 2).' %</td>';
                        echo '<td><a href="'.base_url("Objetivos/indicadoresUsuario/$utodos->id/trios/$mes/$anio").'" target="_blank">'.$indicadores[$ut->id]["total_trios"].'</a></td>';
                        echo '<td>S/. '.number_format($indicadores[$ut->id]["total_trios"]*3, 2).'</td>';
                        echo '<td>'.$indicadores[$ut->id]["solares"].'</td>';
                        echo '<td>S/. '.number_format($indicadores[$ut->id]["solares"]*2, 2).'</td>';
                        echo '<td>'.$indicadores[$ut->id]["total_multifocal_top"].'</td>';
                        echo '<td>S/. '.number_format($indicadores[$ut->id]["total_multifocal_top"]*4, 2).'</td>';
                        echo '<td>'.$indicadores[$ut->id]["total_digital_monofocal"].'</td>';
                        echo '<td>S/. '.number_format($indicadores[$ut->id]["total_digital_monofocal"]*7, 2).'</td>';
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>
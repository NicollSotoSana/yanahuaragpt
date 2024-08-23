<?php
    $total = 0;
    $total_sactisfaccion = 0;
    $total_satisfac = 0;
?>
<html>
    <body>
        <p>
            Buenos días, <?php echo $empresa;?><br/>
            Adjuntamos información sobre el nivel de satisfacción alcanzado en el mes gracias a las encuestas llenadas por los mismos beneficiarios de la firma a la que usted representa.
        </p>
        <table border="1">
            <thead>
                <tr>
                    <td colspan="8" align="center"><b>ENTREGADOS</b></td>
                </tr>
                <tr>
                    <th>Fecha</th>
                    <th>Comprobante</th>
                    <th>Cliente</th>
                    <th>DNI</th>
                    <th>Estado</th>
                    <th>Vendedor</th>
                    <th>Índice de Satisfacción</th>
                    <th>Empresa Convenio</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($datos_entregado as $d){
                        echo '<tr>
                            <td>'.$d["fecha"].'</td>
                            <td>'.$d["comprobante"].'</td>
                            <td>'.$d["cliente"].'</td>
                            <td>'.$d["dni"].'</td>
                            <td>'.$d["estado"].'</td>
                            <td>'.$d["vendedor"].'</td>
                            <td>'.$d["indice_satisfaccion"].'</td>
                            <td>'.$d["empresa_convenio"].'</td>
                        </tr>';

                        $total++;
                        if($d["indice_satisfaccion"] != "N/A"){
                            $total_satisfac++;
                            $total_sactisfaccion += $d["indice_satisfaccion"];
                        }
                        
                    }

                    if($total_satisfac > 0){
                        $promedio = number_format($total_sactisfaccion/$total_satisfac,2);
                    }else{
                        $promedio = 0;
                    }
                ?>
                
            </tbody>
        </table>

        <p style="margin: 15px;">&nbsp;</p>

        <table border="1">
            <thead>
                <tr>
                    <td colspan="8" align="center"><b>PENDIENTE DE ENTREGA</b></td>
                </tr>
                <tr>
                    <th>Fecha</th>
                    <th>Comprobante</th>
                    <th>Cliente</th>
                    <th>DNI</th>
                    <th>Estado</th>
                    <th>Vendedor</th>
                    <th>Índice de Satisfacción</th>
                    <th>Empresa Convenio</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($datos_no_entregado as $d){
                        echo '<tr>
                            <td>'.$d["fecha"].'</td>
                            <td>'.$d["comprobante"].'</td>
                            <td>'.$d["cliente"].'</td>
                            <td>'.$d["dni"].'</td>
                            <td>'.$d["estado"].'</td>
                            <td>'.$d["vendedor"].'</td>
                            <td>'.$d["indice_satisfaccion"].'</td>
                            <td>'.$d["empresa_convenio"].'</td>
                        </tr>';

                        $total++;
                        if($d["indice_satisfaccion"] != "N/A"){
                            $total_satisfac++;
                            $total_sactisfaccion += $d["indice_satisfaccion"];
                        }
                        
                    }

                    if($total_satisfac > 0){
                        $promedio = number_format($total_sactisfaccion/$total_satisfac,2);
                    }else{
                        $promedio = 0;
                    }
                ?>
                
            </tbody>
        </table>
        <p>
            TOTAL PERSONAS ATENDIDAS: <?php echo $total;?><br/>
            PROMEDIO ÍNDICE DE SATISFACCIÓN: <?php echo $promedio;?>/5<br/> 
            Nuestro compromiso es ser transparentes y buscar la mejora continua.
        </p>
        <p>
            Saludos Cordiales.
        </p>
        <hr>
        <p>Si no desea seguir recibiendo correos puede dar <a href="https://sistemas-guillentamayo.com/yanahuara/Sincronizarproductos/retirarCorreo/<?php echo $email;?>">click aquí</a> para ser retirado de nuestra base de datos.</p>
    </body>
</html>

<div class="col-md-12" style="background:#fff;">
<br>
    <table class="table table-bordered" border="1" id="example">
        <thead>
        <th>&nbsp;</th>
            <?php
                $enero = 0;
                $febrero = 0;
                $marzo = 0;
                $abril = 0;
                $mayo = 0;
                $junio = 0;
                $julio = 0;
                $agosto = 0;
                $setiembre = 0;
                $octubre = 0;
                $noviembre = 0;
                $diciembre = 0;

                $enerom = 0;
                $febrerom = 0;
                $marzom = 0;
                $abrilm = 0;
                $mayom = 0;
                $juniom = 0;
                $juliom = 0;
                $agostom = 0;
                $setiembrem = 0;
                $octubrem = 0;
                $noviembrem = 0;
                $diciembrem = 0;

                foreach($usuarios as $us){
                    echo '<th colspan="2">'.$us->Nombre.'</th>';
                }
            ?>
            <?php
                foreach($usuarios as $us){
                    $enero += $indicadores[$us->id]["bien"]["enero_bien"];
                    $enerom += $indicadores[$us->id]["mal"]["enero_mal"];
                    $febrero += $indicadores[$us->id]["bien"]["febrero_bien"];
                    $febrerom += $indicadores[$us->id]["mal"]["febrero_mal"];
                    $marzo += $indicadores[$us->id]["bien"]["marzo_bien"];
                    $marzom += $indicadores[$us->id]["mal"]["marzo_mal"];
                    $abril += $indicadores[$us->id]["bien"]["abril_bien"];
                    $abrilm += $indicadores[$us->id]["mal"]["abril_mal"];
                    $mayo += $indicadores[$us->id]["bien"]["mayo_bien"];
                    $mayom += $indicadores[$us->id]["mal"]["mayo_mal"];
                    $junio += $indicadores[$us->id]["bien"]["junio_bien"];
                    $juniom += $indicadores[$us->id]["mal"]["junio_mal"];
                    $julio += $indicadores[$us->id]["bien"]["julio_bien"];
                    $juliom += $indicadores[$us->id]["mal"]["julio_mal"];
                    $agosto += $indicadores[$us->id]["bien"]["agosto_bien"];
                    $agostom += $indicadores[$us->id]["mal"]["agosto_mal"];
                    $setiembre += $indicadores[$us->id]["bien"]["setiembre_bien"];
                    $setiembrem += $indicadores[$us->id]["mal"]["setiembre_mal"];
                    $octubre += $indicadores[$us->id]["bien"]["octubre_bien"];
                    $octubrem += $indicadores[$us->id]["mal"]["octubre_mal"];
                    $noviembre += $indicadores[$us->id]["bien"]["noviembre_bien"];
                    $noviembrem += $indicadores[$us->id]["mal"]["noviembre_mal"];
                    $diciembre += $indicadores[$us->id]["bien"]["diciembre_bien"];
                    $diciembrem += $indicadores[$us->id]["mal"]["diciembre_mal"];
                }
            ?>

            <th colspan="3">Promedio Mensual</th>
        </thead>
        <thead>
            <th>Mes</th>
            <?php
                foreach($usuarios as $us){
                    echo '<th>Bien</th>';
                    echo '<th>Mal</th>';
                }
                
            ?>
            <th>Bien</th>
            <th>Mal</th>
            <th>%</th>
        </thead>
        <tbody>
            <tr>
                <td>Enero</td>
                <?php
                    foreach($usuarios as $us){

                        echo '<td>'.$indicadores[$us->id]["bien"]["enero_bien"].'</td>';
                        echo '<td>'.$indicadores[$us->id]["mal"]["enero_mal"].'</td>';
                        
                    }
                    echo '<td>'.$enero.'</td>';
                    echo '<td>'.$enerom.'</td>';
                    echo '<td>'.number_format(($enero*100)/($enero+$enerom), 2).' %</td>';
                ?>
            </tr>
            <tr>
                <td>Febrero</td>
                <?php
                    foreach($usuarios as $us){
                        echo '<td>'.$indicadores[$us->id]["bien"]["febrero_bien"].'</td>';
                        echo '<td>'.$indicadores[$us->id]["mal"]["febrero_mal"].'</td>';
                        
                    }
                    echo '<td>'.$febrero.'</td>';
                    echo '<td>'.$febrerom.'</td>';
                    echo '<td>'.number_format(($febrero*100)/($febrero+$febrerom), 2).' %</td>';
                ?>
            </tr>
            <tr>
                <td>Marzo</td>
                <?php
                    foreach($usuarios as $us){
                        echo '<td>'.$indicadores[$us->id]["bien"]["marzo_bien"].'</td>';
                        echo '<td>'.$indicadores[$us->id]["mal"]["marzo_mal"].'</td>';
                    }
                    echo '<td>'.$marzo.'</td>';
                    echo '<td>'.$marzom.'</td>';
                    echo '<td>'.number_format(($marzo*100)/($marzo+$marzom), 2).' %</td>';
                ?>
            </tr>
            <tr>
                <td>Abril</td>
                <?php
                    foreach($usuarios as $us){
                        echo '<td>'.$indicadores[$us->id]["bien"]["abril_bien"].'</td>';
                        echo '<td>'.$indicadores[$us->id]["mal"]["abril_mal"].'</td>';
                    }
                    echo '<td>'.$abril.'</td>';
                    echo '<td>'.$abrilm.'</td>';
                    echo '<td>'.number_format(($abril*100)/($abril+$abrilm), 2).' %</td>';
                ?>
            </tr>
            <tr>
                <td>Mayo</td>
                <?php
                    foreach($usuarios as $us){
                        echo '<td>'.$indicadores[$us->id]["bien"]["mayo_bien"].'</td>';
                        echo '<td>'.$indicadores[$us->id]["mal"]["mayo_mal"].'</td>';
                    }
                    echo '<td>'.$mayo.'</td>';
                    echo '<td>'.$mayom.'</td>';
                    echo '<td>'.number_format(($mayo*100)/($mayo+$mayom), 2).' %</td>';
                ?>
            </tr>
            <tr>
                <td>Junio</td>
                <?php
                    foreach($usuarios as $us){
                        echo '<td>'.$indicadores[$us->id]["bien"]["junio_bien"].'</td>';
                        echo '<td>'.$indicadores[$us->id]["mal"]["junio_mal"].'</td>';
                    }
                    echo '<td>'.$junio.'</td>';
                    echo '<td>'.$juniom.'</td>';
                    echo '<td>'.number_format(($junio*100)/($junio+$juniom), 2).' %</td>';
                ?>
            </tr>
            <tr>
                <td>Julio</td>
                <?php
                    foreach($usuarios as $us){
                        echo '<td>'.$indicadores[$us->id]["bien"]["julio_bien"].'</td>';
                        echo '<td>'.$indicadores[$us->id]["mal"]["julio_mal"].'</td>';
                    }
                    echo '<td>'.$julio.'</td>';
                    echo '<td>'.$juliom.'</td>';
                    echo '<td>'.number_format(($julio*100)/($julio+$juliom), 2).' %</td>';
                ?>
            </tr>
            <tr>
                <td>Agosto</td>
                <?php
                    foreach($usuarios as $us){
                        echo '<td>'.$indicadores[$us->id]["bien"]["agosto_bien"].'</td>';
                        echo '<td>'.$indicadores[$us->id]["mal"]["agosto_mal"].'</td>';
                    }
                    echo '<td>'.$agosto.'</td>';
                    echo '<td>'.$agostom.'</td>';
                    echo '<td>'.number_format(($agosto*100)/($agosto+$agostom), 2).' %</td>';
                ?>
            </tr>
            <tr>
                <td>Setiembre</td>
                <?php
                    foreach($usuarios as $us){
                        echo '<td>'.$indicadores[$us->id]["bien"]["setiembre_bien"].'</td>';
                        echo '<td>'.$indicadores[$us->id]["mal"]["setiembre_mal"].'</td>';
                    }
                    echo '<td>'.$setiembre.'</td>';
                    echo '<td>'.$setiembrem.'</td>';
                    echo '<td>'.number_format(($setiembre*100)/($setiembre+$setiembrem), 2).' %</td>';
                ?>
            </tr>
            <tr>
                <td>Octubre</td>
                <?php
                    foreach($usuarios as $us){
                        echo '<td>'.$indicadores[$us->id]["bien"]["octubre_bien"].'</td>';
                        echo '<td>'.$indicadores[$us->id]["mal"]["octubre_mal"].'</td>';
                    }
                    echo '<td>'.$octubre.'</td>';
                    echo '<td>'.$octubrem.'</td>';
                    echo '<td>'.number_format(($octubre*100)/($octubre+$octubrem), 2).' %</td>';
                ?>
            </tr>
            <tr>
                <td>Noviembre</td>
                <?php
                    foreach($usuarios as $us){
                        echo '<td>'.$indicadores[$us->id]["bien"]["noviembre_bien"].'</td>';
                        echo '<td>'.$indicadores[$us->id]["mal"]["noviembre_mal"].'</td>';
                    }
                    echo '<td>'.$noviembre.'</td>';
                    echo '<td>'.$noviembrem.'</td>';
                    echo '<td>'.number_format(($noviembre*100)/($noviembre+$noviembrem), 2).' %</td>';
                ?>
            </tr>
            <tr>
                <td>Diciembre</td>
                <?php
                    foreach($usuarios as $us){
                        echo '<td>'.$indicadores[$us->id]["bien"]["diciembre_bien"].'</td>';
                        echo '<td>'.$indicadores[$us->id]["mal"]["diciembre_mal"].'</td>';
                    }
                    echo '<td>'.$diciembre.'</td>';
                    echo '<td>'.$diciembrem.'</td>';
                    echo '<td>'.number_format(($diciembre*100)/($diciembre+$diciembrem), 2).' %</td>';
                ?>
            </tr>
        </tbody>
    </table>
</div>
<style>
.dataTables_wrapper .dataTables_filter {
float: right;
text-align: right;
visibility: hidden;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
 
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
    	$('#example').DataTable( {
            "dom": 'Bfrtip',
            "pageLength": 100,
            "order": [[ 1, "desc" ]],
            "buttons": [
            {
                    extend: 'excel',
                    text: 'Excel',
                    className: 'btn btn-success'
            },
            {
                extend: 'pdf',
                text: 'PDF',
                className: 'btn btn-danger'
            }
            ],
		    "language": {
		        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
			}
		});
	});
</script>
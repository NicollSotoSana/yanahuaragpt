<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use Automattic\WooCommerce\Client;
 
class Sincronizarproductos extends CI_Controller 
{
    /*Sincronizar productos Woocommerce */

	public function index(){

        //Categorias a Buscar
        $categorias_copiar = array("MONTURA OFTALMICA", "MONTURA SOLAR", "MONTURA DEPORTIVA", "MONTURA DE SEGURIDAD");

        /* Recorremos productos */

        $productos = $this->db->where_in("categoria", $categorias_copiar)->where("pendiente_upd", "1")->order_by("id", "desc")->limit(4)->get("producto")->result();
        
        $woocommerce = new Client(
			'https://guillentamayo.com/tienda', 
			'ck_03419ee5be6965e5fee5548b0da4ed0394e64117', 
			'cs_19678b4d274b8d504ae276d19d65d73cba48a5db',
			[
				'wp_api' => true,
				'version' => 'wc/v3',
			]
        );
        
        foreach($productos as $p){
            echo "<p>".json_encode($p)."</p>";

            $params = [
                'sku' => trim($p->codigo_prod)
            ];

            $validar = $woocommerce->get('products', $params);

            $categorias_arr = array();
                
            $sexo = trim(strtoupper(strtolower($p->sexo)));
            $categoria = trim(strtoupper(strtolower($p->categoria)));
            $material = trim(strtoupper(strtolower($p->material)));
            $marca = trim(strtoupper(strtolower($p->Marca)));
            $aro = trim(strtoupper(strtolower($p->tipo_aro)));
            
            $g_sexo = $this->getCategoria($sexo);
            $g_categoria = $this->getCategoria($categoria);
            $g_material = $this->getCategoria($material);
            $g_marca = $this->getCategoria($marca);
            $g_aro = $this->getCategoria($aro);

            $t_sexo = ['id' => $this->getEtiqueta($sexo),];
            $t_categoria = ['id' => $this->getEtiqueta($categoria),];
            $t_material = ['id' => $this->getEtiqueta($material),];
            $t_marca = ['id' => $this->getEtiqueta($marca),];
            $t_aro = ['id' => $this->getEtiqueta($aro),];
            
            if($g_sexo){
                $categorias_arr[] = array("id" => $g_sexo);
            }
            if($g_categoria){
                $categorias_arr[] = array("id" => $g_categoria);
            }
            if($g_material){
                $categorias_arr[] = array("id" => $g_material);
            }

            if($g_marca){
                $categorias_arr[] = array("id" => $g_marca);
            }

            if($g_aro){
                $categorias_arr[] = array("id" => $g_aro);
            }

            if($p->reto == 1){
                $categorias_arr[] = array("id" => 168);
                $reto_tag = ['id' => 1195,];
            }else{
                $reto_tag = ['id' => 0,];
            }

            if($validar == null){
                $arr_prods = array(
                    "type" => "simple",
                    "name" => $p->Nombre,
                    "description" => $p->codigo_prod,
                    "sku" => $p->codigo_prod,
                    "regular_price" => $p->Precio,
                    "manage_stock" => true,
                    "stock_quantity" => $p->Stock,
                    "has_variations" => 0,
                    "categories" => $categorias_arr,
                    "short_description" => '<img class="alignnone wp-image-6535" src="https://guillentamayo.com/tienda/wp-content/uploads/2020/12/medidas-monturas-02-300x94-1.png" alt="" width="102" height="32" />  Diagonal aro: '.$p->medida_diagonal.'

                    <img class="alignnone wp-image-6537" src="https://guillentamayo.com/tienda/wp-content/uploads/2020/12/medidas-monturas-03-1-300x110-1.png" alt="" width="101" height="37" />  Puente: '.$p->medida_puente.'
                    
                    <img class="alignnone wp-image-6534" src="https://guillentamayo.com/tienda/wp-content/uploads/2020/12/medidas-monturas-01-300x97-1.png" alt="" width="102" height="33" />  Largo varilla: '.$p->medida_varilla.'',
                    "tags" => [$t_sexo, $t_categoria, $t_material, $t_marca, $reto_tag, $t_aro,],
                );
                
                //echo json_encode($arr_prods);
    
                $wc_product = $woocommerce->post( 'products', $arr_prods );

                if(isset($wc_product->id)){
                    $this->db->where("id", $p->id)->update("producto", array("pendiente_upd" => 0));
                }

                
            }else{
                $data = [
                    'regular_price' => $p->Precio,
                    'stock_quantity'=> $p->Stock,
                    'categories' => $categorias_arr,
                    "tags" => [$t_sexo, $t_categoria, $t_material, $t_marca, $reto_tag, $t_aro,],

                ];
                
                //echo json_encode($categorias_arr);

                $wc_upd = $woocommerce->put('products/'.$validar[0]->id, $data);
                /*echo $wc_upd->id;
                var_dump($wc_upd);*/
                if(isset($wc_upd->id)){
                    $this->db->where("id", $p->id)->update("producto", array("pendiente_upd" => 0));
                }
                
            }
        }
    }
    
    public function addTags(){
        $woocommerce = new Client(
			'https://tienda-virtual.guillentamayo.com', 
			'ck_59073cf75bd029a4a355be9962a06752ea51fa6a', 
			'cs_ff6be1a1f1efb822d31b253260ffedb6d308e945',
			[
				'wp_api' => true,
				'version' => 'wc/v3',
			]
        );

        $categorias_copiar = array("MONTURA OFTALMICA", "MONTURA SOLAR", "MONTURA DEPORTIVA", "MONTURA DE SEGURIDAD");

        $productos = $this->db->where_in("categoria", $categorias_copiar)->where("Stock", 1)->order_by("id", "desc")->get("producto")->result();

        foreach($productos as $p){
            $params = [
                'sku' => trim($p->codigo_prod)
            ];

            $validar = $woocommerce->get('products', $params);


            $sexo = trim(strtoupper($p->sexo));
            $categoria = trim(strtoupper($p->categoria));
            $material = trim(strtoupper($p->material));
            $tipo_aro = trim(strtoupper($p->tipo_aro));
            $marca = trim(strtoupper($p->Marca));

            if(array_key_exists($sexo, $tags_ids)){
                $sexo_tag = ['id' => $tags_ids[$sexo],];
            }else{
                $sexo_tag = ['id' => 0,];
            }
            if(array_key_exists($categoria, $tags_ids)){
                $categoria_tag = ['id' => $tags_ids[$categoria],];
            }else{
                $categoria_tag = ['id' => 0,];
            }
            if(array_key_exists($material, $tags_ids)){
                $material_tag = ['id' => $tags_ids[$material],];
            }else{
                $material_tag = ['id' => 0,];
            }

            if(array_key_exists($tipo_aro, $tags_ids)){
                $aro_tag = ['id' => $tags_ids[$tipo_aro],];
            }else{
                $aro_tag = ['id' => 0,];
            }
    
            if(array_key_exists($marca, $tags_ids)){
                $marca_tag = ['id' => $tags_ids[$marca],];
            }else{
                $marca_tag = ['id' => 0,];
            }
    
            if($p->reto == 1){
                $reto = ['id' => 269,];
            }else{
                $reto = ['id' => 0,];
            }


            $data = ['tags' => [$sexo_tag, $categoria_tag, $material_tag, $marca_tag, $reto, $aro_tag,],];
        
            //echo json_encode($categorias_arr);

            $wc_upd = $woocommerce->put('products/'.$validar[0]->id, $data);
            echo json_encode($wc_upd);
        }
        
    }

    private function getCategoria($nombre){
        $categorias = array(
            "MONTURA DE SEGURIDAD" => 1251,
            "MONTURA DEPORTIVA" => 1206,
            "MONTURA OFTALMICA" => 1173,
            "MONTURA DALTONISMO" => 1269,
            "MONTURA GLAUCOMA" => 1256,
            "MONTURA SOLAR" => 1203,
            "OFERTAS DEL MES" => 1193,
            "NIÑO" => 1177,
            "NIÑA" => 1161,
            "VARÓN" => 1182,
            "VARON" => 1182,
            "DAMA" => 1211,
            "UNISEX" => 1245,
            "ARO COMPLETO" => 1157,
            "AL AIRE" => 1179,
            "SEMI AL AIRE" => 1197,
            "PLAQUETAS" => 1415,
            "SOBRELENTES" => 1416,
            "ALARGADO" => 1417,
            "CUADRADO" => 1418,
            "OVALADO" => 1419,
            "REDONDO" => 1420,
            "TRIANGULAR" => 1421,
            "ESPEJADO" => 1422,
            "POLARIZADO" => 1208,
            "ACTIVA" => 1282,
            "ACTIVE" => 1188,
            "ACTUAL" => 1214,
            "ADIDAS" => 1222,
            "AIR MAG" => 1252,
            "ALPI" => 1229,
            "ANGELINA BONDONE" => 1232,
            "ARGOS" => 1248,
            "ARNETTE" => 1227,
            "BELLAGIO" => 1196,
            "BELLUNO" => 1192,
            "BRUCKEN" => 1244,
            "CALVIN KLEIN" => 1263,
            "CANDIES" => 1218,
            "CAROLINA HERRERA" => 1254,
            "CARRERA" => 1230,
            "CATERPILLAR" => 1219,
            "CENTRO STYLE" => 1191,
            "CONVERSE" => 1221,
            "DONNA KARAN" => 1250,
            "DRAGON" => 1260,
            "CHUFANG" => 1296,
            "EMPORIO ARMANI" => 1216,
            "EUROPTICS" => 1235,
            "EXPRESS" => 1238,
            "EZZIO BRAZZINI" => 1265,
            "FERRETTI" => 1237,
            "GANT" => 1286,
            "GIORDI" => 1283,
            "GO FLEX" => 1240,
            "GUESS" => 1233,
            "HELLO KITTY" => 1171,
            "HENKO" => 1274,
            "HUGO BOSS" => 1239,
            "KENZIA" => 1264,
            "LAPO" => 1271,
            "LEVENT KIDS" => 1275,
            "MISSONI" => 1276,
            "MIYAGI EYEWEAR" => 1236,
            "MONDI" => 1241,
            "MOSCHINO" => 1279,
            "NANO VISTA" => 1159,
            "NAUTICA" => 1225,
            "NEW YORK" => 1176,
            "NIKE" => 1259,
            "NINE WEST" => 1262,
            "OAKLEY" => 1284,
            "OPAL" => 1242,
            "OSIRIS" => 1243,
            "PENTAX" => 1267,
            "PIERRE CARDIN" => 1231,
            "POLAROID" => 1207,
            "PUMA" => 1234,
            "PV OPTICS" => 1281,
            "QUALITY" => 1247,
            "QUEST" => 1261,
            "RAY BAN" => 1201,
            "REEBOK" => 1249,
            "SMART" => 1266,
            "ROXY" => 1212,
            "SPY" => 1277,
            "SPOLETO" => 1220,
            "SWAROSKI" => 1287,
            "TATTOO" => 1181,
            "TONY HAWK" => 1414,
            "URBAN CHAOS" => 1190,
            "VALENTINA FERRUCCI" => 1246,
            "ANDRE MORETI" => 1413,
            "DRWN" => 1401,
            "JOHNSON & JOHNSON" => 1402,
            "KENETH COLE" => 1403,
            "LACOSTE" => 1404,
            "LIZEMBLEM" => 1405,
            "LUCKY BRAND" => 1406,
            "MYTHO" => 1407,
            "Pepe Jeans" => 1408,
            "POLAR SPORTS" => 1409,
            "PV TM" => 1410,
            "TOMMY HILFIGER" => 1411,
            "TOYOKA SEGURIDAD" => 1412,
            "VORTEX" => 1291,
            "XOX" => 1290
        );

        if(array_key_exists($nombre, $categorias)){
            return $categorias[$nombre];
        }else{
            return false;
        }
        
    }

    private function getEtiqueta($etiqueta){
        $tags_ids = array(
            "MONTURA DE SEGURIDAD" => 1268,
            "MONTURA DEPORTIVA" => 1209,
            "MONTURA OFTALMICA" => 1164,
            "MONTURA DALTONISMO" => 1270,
            "MONTURA GLAUCOMA" => 1257,
            "MONTURA SOLAR" => 1204,
            "OFERTAS DEL MES" => 1195,
            "NIÑO" => 1178,
            "NIÑA" => 1166,
            "VARÓN" => 1187,
            "VARON" => 1187,
            "DAMA" => 1213,
            "UNISEX" => 1424,
            "ARO COMPLETO" => 1163,
            "AL AIRE" => 1183,
            "SEMI AL AIRE" => 1199,
            "PLAQUETAS" => 1184,
            "SOBRELENTES" => 1224,
            "ALARGADO" => 1167,
            "CUADRADO" => 1168,
            "OVALADO" => 1169,
            "REDONDO" => 1175,
            "TRIANGULAR" => 1170,
            "ESPEJADO" => 1205,
            "POLARIZADO" => 1210,
            "ACTIVA" => 1312,
            "ACTIVE" => 1313,
            "ACTUAL" => 1314,
            "ADIDAS" => 1315,
            "AIR MAG" => 1311,
            "ALPI" => 1316,
            "ANGELINA BONDONE" => 1317,
            "ARGOS" => 1318,
            "ARNETTE" => 1319,
            "BELLAGIO" => 1320,
            "BELLUNO" => 1321,
            "BRUCKEN" => 1322,
            "CALVIN KLEIN" => 1323,
            "CANDIES" => 1324,
            "CAROLINA HERRERA" => 1325,
            "CARRERA" => 1326,
            "CATERPILLAR" => 1327,
            "CENTRO STYLE" => 1328,
            "CONVERSE" => 1330,
            "DONNA KARAN" => 1331,
            "DRAGON" => 1332,
            "CHUFANG" => 1329,
            "EMPORIO ARMANI" => 1344,
            "EUROPTICS" => 1334,
            "EXPRESS" => 1338,
            "EZZIO BRAZZINI" => 1343,
            "FERRETTI" => 1340,
            "GANT" => 1341,
            "GIORDI" => 1342,
            "GO FLEX" => 1347,
            "GUESS" => 1348,
            "HELLO KITTY" => 1349,
            "HENKO" => 1350,
            "HUGO BOSS" => 1352,
            "KENZIA" => 1353,
            "LAPO" => 1354,
            "LEVENT KIDS" => 1355,
            "MISSONI" => 1356,
            "MIYAGI EYEWEAR" => 1357,
            "MONDI" => 1358,
            "MOSCHINO" => 1359,
            "NANO VISTA" => 1360,
            "Nano" => 1360,
            "NAUTICA" => 1361,
            "NEW YORK" => 1362,
            "NIKE" => 1363,
            "NINE WEST" => 1364,
            "OAKLEY" => 1365,
            "OPAL" => 1345,
            "OSIRIS" => 1346,
            "PENTAX" => 1366,
            "PIERRE CARDIN" => 1367,
            "POLAROID" => 1368,
            "PUMA" => 1369,
            "PV OPTICS" => 1370,
            "QUALITY" => 1371,
            "QUEST" => 1372,
            "RAY BAN" => 1373,
            "REEBOK" => 1374,
            "SMART" => 1376,
            "ROXY" => 1375,
            "SPY" => 1378,
            "SPOLETO" => 1377,
            "SWAROSKI" => 1379,
            "TATTOO" => 1380,
            "TONY HAWK" => 1278,
            "URBAN CHAOS" => 1381,
            "VALENTINA FERRUCCI" => 1385,
            "ANDRE MORETI" => 1388,
            "DRWN" => 1389,
            "JOHNSON & JOHNSON" => 1390,
            "KENETH COLE" => 1391,
            "LACOSTE" => 1392,
            "LIZEMBLEM" => 1393,
            "LUCKY BRAND" => 1394,
            "MYTHO" => 1395,
            "Pepe Jeans" => 1396,
            "POLAR SPORTS" => 1397,
            "PV TM" => 1398,
            "TOMMY HILFIGER" => 1399,
            "TOYOKA SEGURIDAD" => 1400,
            "VORTEX" => 1386,
            "XOX" => 1387
        );

        if(array_key_exists($etiqueta, $tags_ids)){
            return $tags_ids[$etiqueta];
        }else{
            return 0;
        }
        
    }


    public function birthdayMessage(){
        $dia = date("d");

        $mes = date("m");

       // echo $fecha;

        $clientes = $this->db->where("MONTH(fecha_nac)=MONTH(CURDATE())
        AND DAY(fecha_nac)=DAY(CURDATE())")->get("cliente")->result();

        foreach($clientes as $cli){
            echo "enviando...".$cli->Nombre." - ".$cli->Correo;
            $this->sendCorreo($cli->Nombre, $cli->Correo);
        }

    }

    public function sendCorreo($name, $email=null){
		$config['protocol']  = "smtp";
		$config['smtp_host'] = "mail.guillentamayo.com";
		$config['smtp_port'] = "587";
		$config['smtp_user'] = "atencionalcliente@guillentamayo.com";
		$config['smtp_pass'] = "Optica.2023";
		$config['charset']   = "utf-8";
		$config['mailtype']  = "html";
	    $config['crlf'] = "\r\n";
        $config['newline'] = "\r\n";

		if($email!=null && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){
			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");
			$this->email->from("atencionalcliente@guillentamayo.com", "Centro Óptico Guillen Tamayo");
			//$this->email->to(array(''$email'', 'atencionalcliente@guillentamayo.com'));
			$this->email->to(array('jangulo@consultoriadigitalperu.com', 'atencionalcliente@guillentamayo.com'));
			$this->email->subject("Centro Óptico Guillén Tamayo te desea ¡Feliz Cumpleaños!");

			$data = array("nombre" => $name);
			$message = $this->load->view('email/cumpleanios', $data, TRUE);

			$this->email->message($message);
			$this->email->send();  
		}
		
	}

    /**
     * Enviar email a empresas con convenio
     */

    public function calcularIndicadores(){
        $mes = date('m', strtotime("-1 month"));
        $year = date('Y');
        

        $empresas = $this->db->where("id_emp_conv !=", 0)->where("enviar_mail", 1)->get("empresas_convenios")->result();
        foreach($empresas as $emp){
            $retorno_entregado = array();
            $retorno_no_entregado = array();
            $empresa = "";

            $this->db->select('ol.*,a.*, u.Nombre as vendedor, c.Nombre as cliente, c.Dni as nrodoc, ole.estado as estado_orden');
            $this->db->from('orden_lab ol');
            $this->db->join('anamnesis a', 'a.id_anamnesis = ol.id_anamnesis');
            $this->db->join('usuario u', 'u.id = a.id_usuario');
            $this->db->join('cliente c', 'c.id = a.id_cliente');
            $this->db->join('orden_lab_estados ole', 'ole.id_estado = ol.id_estado_orden');
            $this->db->where("a.id_empresa_conv", $emp->id_emp_conv);
            $this->db->where("MONTH(ol.fecha_orden)", $mes);
            $this->db->where("YEAR(ol.fecha_orden)", $year);
            $this->db->where("ol.id_estado_orden !=", "4");
            $datos = $this->db->get()->result();

            //print_r($this->db->last_query());    
            
            //$datos = $this->db->where("MONTH(fecha_orden)", $mes)->get("orden_lab")->result();

            foreach($datos as $d){

                $comprobante = $this->db->select("Serie, Correlativo")->from("comprobante")->where("id_orden_lab", $d->id_orden)->get()->row();
    
                $satisfaccion = $this->db->select("nivel_satisfac")->from("encuestas")->where("id_anamnesis", $d->id_anamnesis)->get()->row();
    
                $indice_satisfac = isset($satisfaccion->nivel_satisfac) ? $satisfaccion->nivel_satisfac."/5":"N/A";

                $empresa = $emp->empresa;
                
                if($d->id_estado_orden == 3){
                    $retorno_entregado[] = array(
                        "fecha" => $d->fecha_orden,
                        "comprobante" => $comprobante->Serie."-".$comprobante->Correlativo,
                        "cliente" => $d->cliente,
                        "dni" => $d->nrodoc,
                        "estado" => $d->estado_orden,
                        "vendedor" => $d->vendedor,
                        "indice_satisfaccion" => $indice_satisfac,
                        "empresa_convenio" => $emp->empresa,
                    );
                }else if($d->id_estado_orden == 1 || $d->id_estado_orden == 2){
                    $retorno_no_entregado[] = array(
                        "fecha" => $d->fecha_orden,
                        "comprobante" => $comprobante->Serie."-".$comprobante->Correlativo,
                        "cliente" => $d->cliente,
                        "dni" => $d->nrodoc,
                        "estado" => $d->estado_orden,
                        "vendedor" => $d->vendedor,
                        "indice_satisfaccion" => $indice_satisfac,
                        "empresa_convenio" => $emp->empresa,
                    );
                }
                
            }
            if(count($retorno_no_entregado)>0 || count($retorno_entregado)>0){
                $this->tstsendCorreoIndicadores($retorno_entregado, $retorno_no_entregado, $emp->email, $empresa);
            }
            
        }
        
    }

    public function sendCorreoIndicadores($data_en, $data_no, $email=null, $empresa=null){
        //$this->load->view('email/indicadores', array("datos" => $data));
        //var_dump($data);
		$config['protocol']  = "smtp";
		$config['smtp_host'] = "mail.guillentamayo.com";
		$config['smtp_port'] = "587";
		$config['smtp_user'] = "atencionalcliente@guillentamayo.com";
		$config['smtp_pass'] = "Optica.2023";
		$config['mailtype']  = "html";
		$config['crlf'] = "\r\n";
        $config['newline'] = "\r\n";

		if($email!=null && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){
			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");
			$this->email->from("atencionalcliente@guillentamayo.com", "Centro Óptico Guillen Tamayo");
			$this->email->to(array($email, 'atencionalcliente@guillentamayo.com'));
            //$this->email->to(array($email));
			$this->email->subject("Indicadores de Satisfacción - Centro Óptico Guillén Tamayo");

			$message = $this->load->view('email/indicadoresEmpresa', array("datos_entregado" => $data_en, "datos_no_entregado" => $data_no, "empresa" => $empresa, "email" => $email), TRUE);

			$this->email->message($message);
			$this->email->send();  
		}
		
	}

    /** RECORDATORIO REVISION ANUAL */

    public function recordatorioAnual(){
        $dia = date("d");

        $mes = date("m");

       // echo $fecha;

        $clientes = $this->db->query("SELECT * FROM evaluaciones e INNER JOIN cliente c ON c.id = e.id_cliente WHERE MONTH(e.proxima_revision) = MONTH(NOW()) AND YEAR(e.proxima_revision) = YEAR(NOW())")->result();

        foreach($clientes as $cli){
            echo "enviando...".$cli->Nombre." - ".$cli->Correo;
            $this->sendRecordatorio($cli->Nombre, $cli->Correo);
        }

    }

    public function sendRecordatorio($name, $email=null){
		$config['protocol']  = "smtp";
		$config['smtp_host'] = "mail.guillentamayo.com";
		$config['smtp_port'] = "587";
		$config['smtp_user'] = "atencionalcliente@guillentamayo.com";
		$config['smtp_pass'] = "Optica.2023";
		$config['mailtype']  = "html";
        $config['crlf'] = "\r\n";
        $config['newline'] = "\r\n";

		if($email!=null && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){
			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");
			$this->email->from("atencionalcliente@guillentamayo.com", "Centro Óptico Guillen Tamayo");
			$this->email->to(array($email, 'atencionalcliente@guillentamayo.com'));
			$this->email->subject("Recordatorio - Centro Óptico Guillén Tamayo");

			$data = array("nombre" => $name);
			$message = $this->load->view('email/recordatorioChequeo', $data, TRUE);

			$this->email->message($message);
			$this->email->send();  
		}
		
	}

    public function retirarCorreo($email){
        $empresas = $this->db->where("email", $email)->update("empresas_convenios", array("enviar_mail" => 0));

        echo '<h4>Su correo ha sido retirado.</h4>';
    }

}
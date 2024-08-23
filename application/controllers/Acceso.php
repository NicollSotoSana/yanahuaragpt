<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acceso extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('usuariomodel', 'um');
	}
	public function index()
	{
		$this->load->view('acceso/index', array(
			'empresas' => $this->um->Empresas()
		));
	}
	public function logout()
	{
		$this->session->unset_userdata('usuario');
		redirect('');
	}
	public function ajax($action)
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

		switch($action)
		{
			case 'Acceder':
				print_r(
					json_encode(
						$this->um->Acceder(
							$this->input->post('Empresa_id'),
							$this->input->post('Usuario'),
							$this->input->post('Contrasena')
						)
					));
				break;
		}
	}
	
	public function resumenDiario($empresa){
		$fecha = date('Y-m-d',strtotime("-1 days"));
		$this->sendSummary($fecha, $empresa);
		$this->sendSummaryVoided($fecha, $empresa);
		//echo $fecha;
	}
	
	public function validateRejected(){
		$fecha = date('Y-m-d',strtotime("-1 days"));
		$empresa = 4;
	//	$fecha = "2022-02-15";

		$this->db->where('Empresa_id', $empresa);
		$cfg = $this->db->get('configuracion')->row();

		$cpe = $this->db->where("fecha_emision", $fecha)->get("comprobante")->result();

		foreach($cpe as $c){

			$data = array(
				"externail_id" => $c->external_id
			);

			$data_json = json_encode($data);
			
			$ruta = $cfg->url_api."api/document_status";
			$authorization = "Authorization: Bearer ".$cfg->token_sunat."";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $ruta);
			curl_setopt(
				$ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization)
			);
			curl_setopt($ch, CURLOPT_POST, true);
			//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$respuesta  = curl_exec($ch);
			curl_close($ch);
	
			$leer_respuesta = json_decode($respuesta, true);

			if($leer_respuesta["success"] == true){
				if($leer_respuesta["status_id"] == "09"){
					$this->db->where("external_id", $c->external_id)->update("comprobante", array("Estado" => 9));
				}
			}

		}

	}


	public function sendSummary($date, $empresa){
		$this->db->where('Empresa_id', $empresa);
		$cfg = $this->db->get('configuracion')->row();
		$data = array(
			"fecha_de_emision_de_documentos" => $date,
			"codigo_tipo_proceso" => 1
		);
		$data_json = json_encode($data);
		
		$ruta = $cfg->url_api."api/summaries";
		$authorization = "Authorization: Bearer ".$cfg->token_sunat."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $ruta);
		curl_setopt(
			$ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization)
		);
		curl_setopt($ch, CURLOPT_POST, true);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$respuesta  = curl_exec($ch);
		curl_close($ch);

		$leer_respuesta = json_decode($respuesta, true);

		$ins = array(
			"external_id" 	  => $leer_respuesta["data"]["external_id"],
			"ticket_sunat"	  => $leer_respuesta["data"]["ticket"],
			"Empresa_id"	  => $empresa
		);

		$this->db->insert("resumenes", $ins);
		$insert_id = $this->db->insert_id();
		if($leer_respuesta["success"] == true){
			$estado = $this->summaryStatus($leer_respuesta["data"]["external_id"], $leer_respuesta["data"]["ticket"], $cfg);
			$leer_respuesta2 = json_decode($estado, true);
			$ins = array(
				"msj_sunat"	  	  => $leer_respuesta2["response"]["description"],
				"fecha_resumen"	  => date("Y-m-d"),
				"json_retorno"	  => $estado,
				"resumen_cdr"	  => $leer_respuesta2["links"]["cdr"]
			);
			$this->db->where("id_resumen", $insert_id)->update("resumenes", $ins);
		}
		
		/*echo $respuesta."<br/>";
		echo $estado;*/
	}

	public function sendSummaryVoided($date, $empresa){
		$this->db->where('Empresa_id', $empresa);
		$cfg = $this->db->get('configuracion')->row();
		$items = array();
		$comps = $this->db->where("Empresa_id", $empresa)->where("ComprobanteTipo_id", "2")->where("Estado", "3")->where("fecha_anulacion", $date)->get("comprobante")->result();
		foreach($comps as $c){
			$items[] = array("external_id" => $c->external_id, "motivo_anulacion" => $c->motivo_anulacion);
		}
		
		if(count($items)>0){
    		$datos = array(
    			"fecha_de_emision_de_documentos" => $date,
    			"codigo_tipo_proceso" => "3",
    			"documentos" => $items
    		);
    
    		$data_json = json_encode($datos);
    		
    		$ruta = $cfg->url_api."api/summaries";
    		$authorization = "Authorization: Bearer ".$cfg->token_sunat."";
    		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, $ruta);
    		curl_setopt(
    			$ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization)
    		);
    		curl_setopt($ch, CURLOPT_POST, true);
    		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    		curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    		$respuesta  = curl_exec($ch);
    		curl_close($ch);
    
    		$leer_respuesta = json_decode($respuesta, true);
    
    		$ins = array(
    			"external_id" 	  => $leer_respuesta["data"]["external_id"],
    			"ticket_sunat"	  => $leer_respuesta["data"]["ticket"],
    			"Empresa_id"	  => $empresa,
    			"tipo_resumen"    => 2
    		);
    
    		$this->db->insert("resumenes", $ins);
    		$insert_id = $this->db->insert_id();
    		if($leer_respuesta["success"] == true){
    			$estado = $this->summaryStatus($leer_respuesta["data"]["external_id"], $leer_respuesta["data"]["ticket"], $cfg);
    			$leer_respuesta2 = json_decode($estado, true);
    			$ins = array(
    				"msj_sunat"	  	  => $leer_respuesta2["response"]["description"],
    				"fecha_resumen"	  => date("Y-m-d"),
    				"json_retorno"	  => $respuesta,
    				"resumen_cdr"	  => $leer_respuesta2["links"]["cdr"]
    			);
    			$this->db->where("id_resumen", $insert_id)->update("resumenes", $ins);
    		}
		}
		/*echo $respuesta;
		echo $estado;*/
	}

	public function summaryStatus($external_id, $ticket, $cfg){
		$data2 = array(
				"external_id" => $external_id,
				"ticket" 	  => $ticket
			);

			$data_json2 = json_encode($data2);
			$ruta = $cfg->url_api."api/summaries/status";
			$authorization = "Authorization: Bearer ".$cfg->token_sunat."";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $ruta);
			curl_setopt(
				$ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization)
			);
			curl_setopt($ch, CURLOPT_POST, true);
			//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json2);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$respuesta2  = curl_exec($ch);
			curl_close($ch);
			return $respuesta2;
	}

	public function consultSunatStatus($empresa){
		$fecha_ini = date('Y-m-d',strtotime("-4 days"));
		$fecha_fin = date('Y-m-d',strtotime("+1 days"));

		$cfg = $this->db->where("Empresa_id", $empresa)->get("configuracion")->row();

		$cpes = $this->db->query("SELECT id, sunat_status, ComprobanteTipo_id, Serie, Correlativo, fecha_emision, ClienteIdentidad, Estado, FechaEmitido, Total, external_id FROM comprobante WHERE (ComprobanteTipo_id = '2' OR ComprobanteTipo_id = '3') AND fecha_emision BETWEEN '".$fecha_ini."' AND '".$fecha_fin."' AND Empresa_id='".$empresa."' AND (sunat_status is null OR sunat_status = '-' OR sunat_recheck = '1')")->result();
		array_debug($cpes);
		foreach($cpes as $c){
			//echo $c->id;
			$tipocpe = $c->ComprobanteTipo_id == "2" ? "03":"01";
			$tipodoccli = strlen($c->ClienteIdentidad) == 11 ? "6":"1";
			$fe = explode("/", $c->FechaEmitido);

			$fecha_sunat = $fe[2].'/'.$fe[1].'/'.$fe[0];

			$status_sunat = $this->sunatStatus($cfg->Ruc, $tipocpe, $c->Serie, $c->Correlativo, $tipodoccli, $c->ClienteIdentidad, $fecha_sunat, $c->Total);

			$resultado = json_decode($status_sunat, true);
			var_dump($resultado);
			
			if($resultado["success"] == true){
				//echo $c->id;
				if($c->ComprobanteTipo_id == "2" && $c->Estado == "3" && $resultado["voided"] == false && $resultado["valid"] == true){

					//Si la BOLETA esta anulada en el sistema pero en SUNAT sigue como válida, procedemos a anularla.
					$datos = array(
						"fecha_de_emision_de_documentos" => $fe[0].'-'.$fe[1].'-'.$fe[2],
						"codigo_tipo_proceso" => "3",
						"documentos" => array(
							array("external_id" => $c->external_id, "motivo_anulacion" => "Error de sistema.")
						)
					);
			
					$data_json = json_encode($datos);
					
					$ruta = $cfg->url_api."api/summaries";
					$authorization = "Authorization: Bearer ".$cfg->token_sunat."";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $ruta);
					curl_setopt(
						$ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization)
					);
					curl_setopt($ch, CURLOPT_POST, true);
					//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$respuesta  = curl_exec($ch);
					curl_close($ch);

					$upd = $this->db->where("id", $c->id)->update("comprobante", array("sunat_status" => $respuesta, "sunat_recheck" => 1));
				
				}else if($c->ComprobanteTipo_id == "3" && $c->Estado == "3" && $resultado["voided"] == false && $resultado["valid"] == true){

					//Si la FACTURA esta anulada en el sistema pero en SUNAT sigue como válida, procedemos a anularla.

					$fe = explode("/", $c->FechaEmitido);

					$fecha_sunat = $fe[2].'/'.$fe[1].'/'.$fe[0];
					
					$datos = array(
						"fecha_de_emision_de_documentos" => $fe[0].'-'.$fe[1].'-'.$fe[2],
						"documentos"	=>	array(
							array(
								"external_id" => "".$c->external_id."",
								"motivo_anulacion" => "Error de redaccion"
							)
						)
					);
					$data_json = json_encode($datos);
					
					$ruta = $cfg->url_api."api/voided";
					$authorization = "Authorization: Bearer ".$cfg->token_sunat."";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $ruta);
					curl_setopt(
						$ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization)
					);
					curl_setopt($ch, CURLOPT_POST, true);
					//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$respuesta  = curl_exec($ch);
					curl_close($ch);

					$upd = $this->db->where("id", $c->id)->update("comprobante", array("sunat_status" => $respuesta, "sunat_recheck" => 1));

				}else if($c->ComprobanteTipo_id == "2" && $c->Estado == "2" && $resultado["voided"] == false && $resultado["valid"] == false){
					//Si en el sistema está la boleta pero no se ha informado a SUNAT procedemos a Enviar la BOLETA

					$data = array(
						"fecha_de_emision_de_documentos" => $c->fecha_emision,
						"codigo_tipo_proceso" => 1
					);
					$data_json = json_encode($data);
					
					$ruta = $cfg->url_api."api/summaries";
					$authorization = "Authorization: Bearer ".$cfg->token_sunat."";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $ruta);
					curl_setopt(
						$ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization)
					);
					curl_setopt($ch, CURLOPT_POST, true);
					//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$respuesta  = curl_exec($ch);
					curl_close($ch);
			
					$leer_respuesta = json_decode($respuesta, true);

					$upd = $this->db->where("id", $c->id)->update("comprobante", array("sunat_status" => $respuesta, "sunat_recheck" => 1));

				}else if($c->ComprobanteTipo_id == "3" && $c->Estado == "2" && $resultado["voided"] == false && $resultado["valid"] == false){
					//Si en el sistema está la factura pero no se ha informado a SUNAT procedemos a Enviar la FACTURA

					$data = array(
						"external_id" => $c->external_id
					);
					$data_json = json_encode($data);
					
					$ruta = $cfg->url_api."api/send";
					$authorization = "Authorization: Bearer ".$cfg->token_sunat."";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $ruta);
					curl_setopt(
						$ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization)
					);
					curl_setopt($ch, CURLOPT_POST, true);
					//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$respuesta  = curl_exec($ch);
					curl_close($ch);
			
					$leer_respuesta = json_decode($respuesta, true);

					$upd = $this->db->where("id", $c->id)->update("comprobante", array("sunat_status" => $respuesta, "sunat_recheck" => 1));

				}else{
					$upd = $this->db->where("id", $c->id)->update("comprobante", array("sunat_status" => $status_sunat, "sunat_recheck" => 0));
				}
			}
		}
	}

	private function sunatStatus($ruc_emisor, $tipo_cpe, $serie_cpe, $nro_cpe, $tipo_doc_receptor, $nro_doc_receptor, $fecha_emision, $importe_total){
		$url = "https://e-factura.sunat.gob.pe/ol-it-wsconsvalidcpe/billValidService?wsdl";
		$users_pool = array(
			array("user" => "20600628837FACTURA2", "passwd" => "Byte2019"),
			array("user" => "20603844883BFACTURA", "passwd" => "Byte2019"),
			array("user" => "10294742498FACTURA2", "passwd" => "Pro2019"),
			array("user" => "20604750831KAROL123", "passwd" => "Karol123")
		);
		$random_user = array_rand($users_pool);
	
		$xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
			xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" 
			xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
			<soapenv:Header>
						<wsse:Security>
							<wsse:UsernameToken>
								<wsse:Username>'.$users_pool[$random_user]["user"].'</wsse:Username>
								<wsse:Password>'.$users_pool[$random_user]["passwd"].'</wsse:Password>
							</wsse:UsernameToken>
						</wsse:Security>
					</soapenv:Header>
			<soapenv:Body>
				<ser:validaCDPcriterios>
					<rucEmisor>'.$ruc_emisor.'</rucEmisor>
					<tipoCDP>'.$tipo_cpe.'</tipoCDP>
					<serieCDP>'.$serie_cpe.'</serieCDP>
					<numeroCDP>'.$nro_cpe.'</numeroCDP>
					<tipoDocIdReceptor>'.$tipo_doc_receptor.'</tipoDocIdReceptor>
					<numeroDocIdReceptor>'.$nro_doc_receptor.'</numeroDocIdReceptor>
					<fechaEmision>'.$fecha_emision.'</fechaEmision>
					<importeTotal>'.$importe_total.'</importeTotal>
					<nroAutorizacion></nroAutorizacion>
				</ser:validaCDPcriterios>
			</soapenv:Body>
		</soapenv:Envelope>';
		
		//echo htmlentities($xml_post_string);

		$headers = array(
			"Content-type: text/xml;charset=\"utf-8\"",
			"Accept: text/xml",
			"Cache-Control: no-cache",
			"Pragma: no-cache",
			"SOAPAction: ",
			"Content-length: " . strlen($xml_post_string),
		); 

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		// converting
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		
		$doc = new DOMDocument();
		$doc->loadXML($response);
		
		$statuscode = $doc->getElementsByTagName('statusCode')->item(0)->nodeValue;
		$respuesta = $doc->getElementsByTagName('statusMessage')->item(0)->nodeValue;
		$respuesta = htmlentities($respuesta);
		//var_dump($response);
		if (strpos( $respuesta, "BAJA" ) !== false){
			return json_encode(array("success" => true, "response" => $respuesta, "status" => $statuscode, "valid" => false, "voided" => true));

		}else if(strpos( $respuesta, "es un comprobante" ) !== false){
			return json_encode(array("success" => true, "response" => $respuesta, "status" => $statuscode, "valid" => true, "voided" => false));

		}else if(strpos( $respuesta, "no existe" ) !== false){
			return json_encode(array("success" => true, "response" => $respuesta, "status" => $statuscode, "valid" => false, "voided" => false));

		}else if(strpos( $respuesta, "ha sido informada" ) !== false){
			return json_encode(array("success" => true, "response" => $respuesta, "status" => $statuscode, "valid" => true, "voided" => false));

		}else if(strpos( $respuesta, "no ha sido informada" ) !== false){
			return json_encode(array("success" => true, "response" => $respuesta, "status" => $statuscode, "valid" => false, "voided" => false));

		}else{
			return json_encode(array("success" => false, "response" => $respuesta));
		}
	}

}
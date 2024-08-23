<?php
class CuentaCorrienteModel extends CI_Model
{
	public function __CONSTRUCT()
	{
		$this->load->dbutil();
		$this->load->helper('file');
	}

	public function getDeudas($id)
	{
		return $this->db->query('SELECT de.fecha, de.id_cliente, de.monto_deuda, de.monto_cancelado, de.comprobante_id, de.id_deuda, co.Correlativo, co.Serie FROM deudas de INNER JOIN comprobante co ON de.comprobante_id = co.id WHERE de.id_cliente = \''.$id.'\' and co.Estado=2')->result();
	}

	public function generarComprobante($datos,$compro=0){
		if(isset($datos["nota"])){
			$this->db->where("Correlativo", $datos["nota_credito"]);
			$getnota = $this->db->get("comprobante")->row();
			$monto_pag=$getnota->Total;
			$nota_crd = $datos["nota"];
			$id_nota = $getnota->id;
		}else{
			$monto_pag=$datos["montopago"];
			$nota_crd = 0;
			$id_nota = 0;
		}

		$this->db->insert('deudas_pagos', array(
			'id_deuda' 	   		=> $datos["deuda_id"],
			'monto_pagado' 		=> $monto_pag,
			'fecha' 	   		=> $datos["fechapago"],
			'nro_notacredito'	=> $nota_crd,
			'id_notacredito'	=> $id_nota,
			'id_empresa'		=> $this->user->Empresa_id,
			'medio_pago'		=> $datos["mediopago"],
			'referencia'		=> $datos['referencia']
		));

		$insert_id = $this->db->insert_id();
		$this->db->where('id_deuda',$datos["deuda_id"]);
		$this->db->set('monto_cancelado', 'monto_cancelado + ' . $monto_pag, FALSE);
		$this->db->update('deudas');
		$Cliente_id = $datos['id_clientePago'] != '' ? $datos['id_clientePago'] : $datos['id_cliente'];
		$cliente = $this->db->where('id', $Cliente_id)->get('cliente')->row();

		if(empty($cliente->Ruc)){
			$identidad = $cliente->Dni;
		}else{
			$identidad = $cliente->Ruc;
		}

		$iva = $datos['comprobanteTipo'] == 3 ||  $datos['comprobanteTipo'] == 2 ? 18 : 0;
		$SubTotal = $datos['comprobanteTipo'] == 3 ||  $datos['comprobanteTipo'] == 2 ? $total / ($iva / 100 + 1) : 0;
		$IvaTotal = $datos['comprobanteTipo'] == 3 ||  $datos['comprobanteTipo'] == 2 ? $total - $SubTotal : 0;

		if($datos["comprobanteTipo"] != 0){
			$cabecera = array(
				'ComprobanteTipo_id' => $datos['comprobanteTipo'],
				'Cliente_id'         => $Cliente_id,
				'ClienteIdentidad'   => $identidad,
				'ClienteNombre'      => $cliente->Nombre,
				'ClienteDireccion'   => $cliente->Direccion,
				'Estado'             => 2,
				'FechaEmitido'       => ToDate(str_replace("-", "/", $datos["fechapago"])),
				'Iva'                => $iva,
				'IvaTotal'           => $IvaTotal,
				'SubTotal'           => $SubTotal,
				'Total'              => $monto_pag,
				'TotalCompra'        => 0,
				'Usuario_id'         => $this->user->id,
				'Glosa'              => "",
				'Ganancia'           => 0,
				'FechaRegistro'      => date('Y/m/d'),
				'Empresa_id'         => $this->user->Empresa_id,
				'Dsc'       		 => 0,
				'totalDsc'       	 => 0,
				'moneda' 			 => "Pen",
				'tipo_cambio' 		 => 0,
				'mediopago'			 => $datos['mediopago']
			);
			
			// Asignamos los correlativo al menudeo
			$cabecera['Serie']       = null;
			$cabecera['Correlativo'] = null;
			if($datos['comprobanteTipo'] != 2 && $datos['comprobanteTipo'] != 3 && $datos['comprobanteTipo'] != 5)
			{
				$t = $this->db->query("SELECT MAX(Correlativo) + 1 Total FROM comprobante WHERE Empresa_id = " . $this->user->Empresa_id . " AND ComprobanteTipo_id = " . $datos['comprobanteTipo'])
							  ->row()->Total;
							  
				$cabecera['Serie']       = null;
				$cabecera['Correlativo'] = str_pad($t == NULL ? 1 : $t, $this->conf->Zeros, '0', STR_PAD_LEFT);
			}

			if($datos['comprobanteTipo'] == 2) 
			{
				$cabecera['Serie'] = $this->conf->SBoleta;
				$secomp = $this->conf->SBoleta;
				

				$t = $this->db->query("SELECT id,Correlativo+1 Total FROM comprobante WHERE Empresa_id = '".$this->user->Empresa_id."' AND ComprobanteTipo_id = " . $datos['comprobanteTipo']." and Correlativo != 'NULL' AND Serie = '".$secomp."' ORDER BY id DESC LIMIT 1")
							  ->row();
				
				$cabecera['Correlativo'] = (!isset($t->Total)) ? 1 : $t->Total;

			}elseif($datos['comprobanteTipo'] == 3){
				$cabecera['Serie'] = $this->conf->SFactura;
				$secomp = $this->conf->SFactura;
				
				$t = $this->db->query("SELECT id,Correlativo+1 Total FROM comprobante WHERE Empresa_id = '".$this->user->Empresa_id."' AND ComprobanteTipo_id = " . $datos['comprobanteTipo']." and Correlativo != 'NULL' AND Serie = '".$secomp."' ORDER BY id DESC LIMIT 1")
							  ->row();
				$cabecera['Correlativo'] = (!isset($t->Total)) ? 1 : $t->Total;
			}
			$compget = $this->db->where("id", $compro)->get("comprobante")->row();
			// Insertamos el comprobante
			$this->db->insert('comprobante', $cabecera);
			$last_id = $this->db->insert_id();

			$detalle = array(
					'tipo'                  => 3, // 1 Producto 2 Servicio
					'Producto_id'           => 0,
					'ProductoNombre'        => $this->getDetalleComprobante($compro),
					'UnidadMedida_id' 	    => "NIU",
					'Cantidad'              => "1",
					'PrecioUnitarioCompra'  => 0,
					'PrecioTotalCompra'     => $monto_pag,
					'PrecioUnitario'        => $monto_pag,
					'PrecioTotal'           => $monto_pag,
					'Ganancia'              => 0,
					'Comprobante_id'		=> $last_id,
				);
			$this->db->insert('comprobantedetalle', $detalle);

			$this->db->where("id_pago", $insert_id);
			$this->db->set("id_comprobante", $last_id);
			$this->db->update("deudas_pagos");
		}

		if($datos['comprobanteTipo'] == 3 || $datos['comprobanteTipo'] == 2){
			$comp = $this->enviarSunat($last_id, 1);
		}
		
		$this->responsemodel->SetResponse(true);
		$this->responsemodel->href   = 'cuentacorriente/usuario/' . $datos['id_cliente'];
		//return $comp;
		return $this->responsemodel;
	}

	public function generarComprobanteCpe($datos,$compro=0){
		if(isset($datos["nota"])){
			$this->db->where("Correlativo", $datos["nota_credito"]);
			$getnota = $this->db->get("comprobante")->row();
			$monto_pag=$getnota->Total;
			$nota_crd = $datos["nota"];
			$id_nota = $getnota->id;
		}else{
			$monto_pag=$datos["montopago"];
			$nota_crd = 0;
			$id_nota = 0;
		}

		$this->db->insert('deudas_pagos', array(
			'id_deuda' 	   		=> $datos["deuda_id"],
			'monto_pagado' 		=> $monto_pag,
			'fecha' 	   		=> $datos["fechapago"],
			'nro_notacredito'	=> $nota_crd,
			'id_notacredito'	=> $id_nota,
			'id_empresa'		=> $this->user->Empresa_id,
			'medio_pago'		=> $datos["mediopago"],
			'referencia'		=> $datos['referencia']
		));

		$insert_id = $this->db->insert_id();
		$this->db->where('id_deuda',$datos["deuda_id"]);
		$this->db->set('monto_cancelado', 'monto_cancelado + ' . $monto_pag, FALSE);
		$this->db->update('deudas');
		$Cliente_id = $datos['id_clientePago'] != '' ? $datos['id_clientePago'] : $datos['id_cliente'];
		$cliente = $this->db->where('id', $Cliente_id)->get('cliente')->row();

		if(empty($cliente->Ruc)){
			$identidad = $cliente->Dni;
		}else{
			$identidad = $cliente->Ruc;
		}

		$iva = $datos['comprobanteTipo'] == 3 ||  $datos['comprobanteTipo'] == 2 ? 18 : 0;
		$SubTotal = $datos['comprobanteTipo'] == 3 ||  $datos['comprobanteTipo'] == 2 ? $total / ($iva / 100 + 1) : 0;
		$IvaTotal = $datos['comprobanteTipo'] == 3 ||  $datos['comprobanteTipo'] == 2 ? $total - $SubTotal : 0;

		if($datos["comprobanteTipo"] != 0){
			$cabecera = array(
				'ComprobanteTipo_id' => $datos['comprobanteTipo'],
				'Cliente_id'         => $Cliente_id,
				'ClienteIdentidad'   => $identidad,
				'ClienteNombre'      => $cliente->Nombre,
				'ClienteDireccion'   => $cliente->Direccion,
				'Estado'             => 2,
				'FechaEmitido'       => ToDate(str_replace("-", "/", $datos["fechapago"])),
				'Iva'                => $iva,
				'IvaTotal'           => $IvaTotal,
				'SubTotal'           => $SubTotal,
				'Total'              => $monto_pag,
				'TotalCompra'        => 0,
				'Usuario_id'         => $this->user->id,
				'Glosa'              => "",
				'Ganancia'           => 0,
				'FechaRegistro'      => date('Y/m/d'),
				'Empresa_id'         => $this->user->Empresa_id,
				'Dsc'       		 => 0,
				'totalDsc'       	 => 0,
				'moneda' 			 => "Pen",
				'tipo_cambio' 		 => 0,
				'mediopago'			 => $datos['mediopago']
			);
			
			// Asignamos los correlativo al menudeo
			$cabecera['Serie']       = null;
			$cabecera['Correlativo'] = null;
			if($datos['comprobanteTipo'] != 2 && $datos['comprobanteTipo'] != 3 && $datos['comprobanteTipo'] != 5)
			{
				$t = $this->db->query("SELECT MAX(Correlativo) + 1 Total FROM comprobante WHERE Empresa_id = " . $this->user->Empresa_id . " AND ComprobanteTipo_id = " . $datos['comprobanteTipo'])
							  ->row()->Total;
							  
				$cabecera['Serie']       = null;
				$cabecera['Correlativo'] = str_pad($t == NULL ? 1 : $t, $this->conf->Zeros, '0', STR_PAD_LEFT);
			}

			if($datos['comprobanteTipo'] == 2) 
			{
				$cabecera['Serie'] = $this->conf->SBoleta;
				$secomp = $this->conf->SBoleta;
				

				$t = $this->db->query("SELECT id,Correlativo+1 Total FROM comprobante WHERE Empresa_id = '".$this->user->Empresa_id."' AND ComprobanteTipo_id = " . $datos['comprobanteTipo']." and Correlativo != 'NULL' AND Serie = '".$secomp."' ORDER BY id DESC LIMIT 1")
							  ->row();
				
				$cabecera['Correlativo'] = (!isset($t->Total)) ? 1 : $t->Total;

			}elseif($datos['comprobanteTipo'] == 3){
				$cabecera['Serie'] = $this->conf->SFactura;
				$secomp = $this->conf->SFactura;
				
				$t = $this->db->query("SELECT id,Correlativo+1 Total FROM comprobante WHERE Empresa_id = '".$this->user->Empresa_id."' AND ComprobanteTipo_id = " . $datos['comprobanteTipo']." and Correlativo != 'NULL' AND Serie = '".$secomp."' ORDER BY id DESC LIMIT 1")
							  ->row();
				$cabecera['Correlativo'] = (!isset($t->Total)) ? 1 : $t->Total;
			}
			$compget = $this->db->where("id", $compro)->get("comprobante")->row();
			// Insertamos el comprobante
			$this->db->insert('comprobante', $cabecera);
			$last_id = $this->db->insert_id();

			$detalle = array(
					'tipo'                  => 3, // 1 Producto 2 Servicio
					'Producto_id'           => 0,
					'ProductoNombre'        => $this->getDetalleComprobante($compro),
					'UnidadMedida_id' 	    => "NIU",
					'Cantidad'              => "1",
					'PrecioUnitarioCompra'  => 0,
					'PrecioTotalCompra'     => $monto_pag,
					'PrecioUnitario'        => $monto_pag,
					'PrecioTotal'           => $monto_pag,
					'Ganancia'              => 0,
					'Comprobante_id'		=> $last_id,
				);
			$this->db->insert('comprobantedetalle', $detalle);

			$this->db->where("id_pago", $insert_id);
			$this->db->set("id_comprobante", $last_id);
			$this->db->update("deudas_pagos");
		}

		if($datos['comprobanteTipo'] == 3 || $datos['comprobanteTipo'] == 2){
			$comp = $this->enviarSunat($last_id, 1);
		}
		
		$this->responsemodel->SetResponse(true);
		$this->responsemodel->href   = 'ventas/comprobante/' . $compro;
		//return $comp;
		return $this->responsemodel;
	}

	public function getDetalle($id){
		$this->db->where("id_deuda", $id);
		$this->db->order_by("id_pago", "desc");
		return $this->db->get("deudas_pagos")->result();
	}

	public function getDetalleComprobante($id){
    	$datos = $this->db->where("Comprobante_Id", $id)->get("comprobantedetalle")->result();
    	$ret = "";
    	foreach($datos as $d){
    		$ret .= $d->ProductoNombre." | ";
    	}
    	return substr($ret, 0, -3);
	}
	
	public function enviarSunat($id,$anu=0){

		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $id);
		$comp = $this->db->get('comprobante')->row();
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$cfg = $this->db->get('configuracion')->row();
		$this->db->where('Comprobante_Id', $id);
		$det = $this->db->get('comprobantedetalle')->result();
		$clie = $this->db->where("id", $comp->Cliente_id)->get('cliente')->row();

		$usucmp = $this->db->where("id",$comp->Usuario_id)->get("usuario")->row();
		if($comp->ComprobanteTipo_id==3){
			$tipocomp = "01";
			$serie = $comp->Serie;
			
			$tipodoc = "6";

			$clienom = $comp->ClienteNombre;
			
		}elseif($comp->ComprobanteTipo_id==2){
			$tipocomp = "03";
			$serie = $comp->Serie;
			//$tipodoc = "1";
			
			$tipodoc = "1";

			$clienom = $comp->ClienteNombre;
		}
		$moneda = ($comp->moneda == "Usd") ? "USD":"PEN";
		$fec = str_replace("/","-",$comp->FechaEmitido);
		$prods = array();
		$totalgratuita = 0;
		$usuario = $this->db->select("Usuario")->where("id", $comp->Usuario_id)->get("usuario")->row()->Usuario;
		foreach($det as $deta){
			$igvfact = $deta->PrecioTotal-($deta->PrecioTotal/1.18);
			$igvitem = $deta->PrecioUnitario-($deta->PrecioUnitario/1.18);
			$udmnube = ($deta->Tipo=="1") ? "NIU":"ZZ";
			$producto = $this->db->select('codigo_prod')->where("id", $deta->Producto_id)->get("producto")->row();
			
			if($comp->gratuita == 1){
				$prods[] = array(
					"unidad_de_medida"          => $udmnube,
					"codigo_interno"            => "",
					"codigo_producto_sunat"		=> "",
					"descripcion"				=> $deta->ProductoNombre,
					"cantidad"					=> $deta->Cantidad,
					"valor_unitario"			=> 0,
					"codigo_tipo_precio" 		=> "02",
					"precio_unitario"			=> 0,
					"codigo_tipo_afectacion_igv"=> "21",
					"total_base_igv"  			=> 0,
					"porcentaje_igv" 			=> 0,
					"total_igv"  				=> 0,
					"total_impuestos"			=> 0,
					"total_valor_item"   		=> 0,
					"total_item"  				=> 0,
				);
				$totalgratuita+=number_format(($deta->PrecioTotal-$igvfact),2, '.', '');
			}else{
				$prods[] = array(
					"unidad_de_medida"          => $udmnube,
					"codigo_interno"            => "",
					"codigo_producto_sunat"		=> "",
					"descripcion"				=> $deta->ProductoNombre,
					"cantidad"					=> $deta->Cantidad,
					"valor_unitario"			=> number_format(($deta->PrecioUnitario-$igvitem),2, '.', ''),
					"codigo_tipo_precio" 		=> "01",
					"precio_unitario"			=> $deta->PrecioUnitario,
					"codigo_tipo_afectacion_igv"=> "10",
					"total_base_igv"  			=> number_format(($deta->PrecioTotal-$igvfact),2, '.', ''),
					"porcentaje_igv" 			=> 18,
					"total_igv"  				=> number_format($igvfact,2, '.', ''),
					"total_impuestos"			=> number_format($igvfact,2, '.', ''),
					"total_valor_item"   		=> number_format(($deta->PrecioTotal-$igvfact),2, '.', ''),
					"total_item"  				=> $deta->PrecioTotal,
				);
			}
			
		}

		if($comp->gratuita==1){
			$subtotalcomp = 0;
			$igvcomptotal = 0;
		}else{
			$subtotalcomp = ($comp->Total/1.18);
			$igvcomptotal = $comp->Total-($comp->Total/1.18);
		}
		

		$data = array(
			"serie_documento" 		=> $serie,
			"numero_documento" 		=> $comp->Correlativo,
			"fecha_de_emision" 		=> date('Y-m-d'),
			"hora_de_emision" 		=> date('h:i:s'),
			"codigo_tipo_operacion" => "0101",
			"codigo_tipo_documento" => $tipocomp,
			"codigo_tipo_moneda" 	=> $moneda,
			"formato_pdf" 			=> "ticketguillen",
			"fecha_de_vencimiento" 	=>  date('Y-m-d'),
			"numero_orden_de_compra"=> "",
			"datos_del_emisor" => array(
				"codigo_pais" => "PE",
    			"ubigeo" => $cfg->ubigeo,
    			"direccion" => $cfg->Direccion,
    			"correo_electronico" => $cfg->correo,
    			"telefono" => $cfg->telefono,
    			"codigo_del_domicilio_fiscal" => "0000"
			),
			"datos_del_cliente_o_receptor" => array(
				"codigo_tipo_documento_identidad" => $tipodoc,
			    "numero_documento" => !empty($comp->ClienteIdentidad) ? $comp->ClienteIdentidad:"-",
			    "apellidos_y_nombres_o_razon_social" => $clienom,
			    "codigo_pais" => "PE",
			    "ubigeo" => "",
			    "direccion" => $comp->ClienteDireccion,
			    "correo_electronico" => isset($clie->Correo) ? $clie->Correo:"",
			    "telefono" => isset($clie->Telefono1) && !empty($clie->Telefono1) ? $clie->Telefono1:""
			),
			"totales" => array(
				"total_descuentos"=> number_format(abs($comp->totalDsc),2, '.', ''),
				"total_exportacion" => 0.00,
			    "total_operaciones_gravadas" => number_format($subtotalcomp,2, '.', ''),
			    "total_operaciones_inafectas" => 0.00,
			    "total_operaciones_exoneradas" => 0.00,
			    "total_operaciones_gratuitas" => 0.00,
			    "total_igv" => number_format($igvcomptotal,2, '.', ''),
			    "total_impuestos" => number_format($igvcomptotal,2, '.', ''),
			    "total_valor" => number_format($subtotalcomp,2, '.', ''),
			    "total_venta" => $comp->Total
			),
			"items" => $prods,
			"informacion_adicional" => $comp->Glosa,
			"acciones" => array("enviar_xml_firmado"=> false, "enviar_email" => true)
		);

		if(!isset($clie->Correo) || empty($clie->Correo)){
			$data["acciones"]["enviar_email"] = false;
		}

		if($comp->gratuita==1){
			$data["totales"]["total_descuentos"] = 0;
			$data["totales"]["total_operaciones_gravadas"] = 0;
			$data["totales"]["total_igv"] = 0;
			$data["totales"]["total_impuestos"] = 0;
			$data["totales"]["total_valor"] = 0;
			$data["totales"]["total_venta"] = 0;
			$data["totales"]["total_operaciones_gratuitas"] = $totalgratuita;

			$data["leyendas"] = array(array("codigo" => 1002, "valor" => "TRANSFERENCIA GRATUITA"));
		}

		$data_json = json_encode($data);
		
		$ruta = $cfg->url_api."api/documents";
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
		
		$this->db->where("id", $id)->update("comprobante", array("retorno_todo" => $respuesta));

		$leer_respuesta = json_decode($respuesta, true);
		if($leer_respuesta["success"] == true){
			$upd_data = array(
				"link_xml"		=> $leer_respuesta["links"]["xml"],
				"link_pdf"		=> $leer_respuesta["links"]["pdf"],
				"link_cdr"		=> !empty($leer_respuesta["links"]["cdr"]) ? $leer_respuesta["links"]["cdr"]:"",
				"msj_sunat"		=> !empty($leer_respuesta["response"]["description"]) ? $leer_respuesta["response"]["description"]:"Comprobante se enviará en resumen diario.",
				"external_id"	=> $leer_respuesta["data"]["external_id"],
				"fecha_emision"	=> date("Y-m-d"),
				"json_enviado" => $data_json
			);
			$this->db->where("id", $id)->update("comprobante", $upd_data);
		}
		return $respuesta;
	}
}
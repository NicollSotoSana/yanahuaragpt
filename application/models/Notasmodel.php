<?php
class NotasModel extends CI_Model
{

	//Emitir notas de credito/debito
	public function emitirNota($data)
	{
		$this->db->trans_start();

		$total  = 0;
		$totalC = 0;
		
		// Detalle
		$items = 0;
		
		foreach($data['Producto_id'] as $id)
		{
			if($id!='') $items++;
		}
		
		if($items == 0)
		{
			$this->responsemodel->message = 'El comprobante debe tener <b>un item</b> por lo menos.';
		}
		else
		{
			for($i = 0; $i < count($data['Producto_id']); $i++)
			{
				if($data['Cantidad'][$i] > 0 && ($data['Producto_id'][$i] != '' || $data['Producto_id'][$i] != "00")) 
				{
					$detalle[] = array(
						'tipo'                  => $data['Tipo'][$i], // 1 Producto 2 Servicio
						'Producto_id'           => $data['Producto_id'][$i],
						'ProductoNombre'        => $data['ProductoNombre'][$i],
						'UnidadMedida_id' 	    => $data['UnidadMedida_id'][$i],
						'Cantidad'              => $data['Cantidad'][$i],
						'PrecioUnitarioCompra'  => $data['PrecioUnitarioCompra'][$i],
						'PrecioTotalCompra'     => $data['PrecioUnitarioCompra'][$i] * $data['Cantidad'][$i],
						'PrecioUnitario'        => $data['PrecioUnitario'][$i],
						'PrecioTotal'           => $data['PrecioUnitario'][$i] * $data['Cantidad'][$i],
						'Ganancia'              => ($data['PrecioUnitario'][$i] * $data['Cantidad'][$i]) - ($data['PrecioUnitarioCompra'][$i] * $data['Cantidad'][$i]),
					);
	
					$total  += $data['PrecioUnitario'][$i] * $data['Cantidad'][$i];
					$totalC += $data['PrecioUnitarioCompra'][$i] * $data['Cantidad'][$i];
				}else if($data['Producto_id'][$i] == "00" && $data['Cantidad'][$i]>0){
					$detalle[] = array(
						'tipo'                  => 1, // 1 Producto 2 Servicio
						'Producto_id'           => 00,
						'ProductoNombre'        => $data['ProductoNombre'][$i],
						'UnidadMedida_id' 	    => isset($data['UnidadMedida_id'][$i]) ? $data['UnidadMedida_id'][$i] : 'UND',
						'Cantidad'              => $data['Cantidad'][$i],
						'PrecioUnitarioCompra'  => $data['PrecioUnitario'][$i],
						'PrecioTotalCompra'     => $data['PrecioUnitario'][$i] * $data['Cantidad'][$i],
						'PrecioUnitario'        => $data['PrecioUnitario'][$i],
						'PrecioTotal'           => $data['PrecioUnitario'][$i] * $data['Cantidad'][$i],
						'Ganancia'              => 0,
					);
	
					$total  += $data['PrecioUnitario'][$i] * $data['Cantidad'][$i];
					$totalC += $data['PrecioUnitario'][$i] * $data['Cantidad'][$i];
				}
			}


			$iva = $data['Iva'];
			$SubTotal = ($total-ABS($data['totalDsc'])) / ($iva / 100 + 1);
			$IvaTotal = ($total-ABS($data['totalDsc'])) - $SubTotal;
			$invent="";

			if($total>=700){
				$detra = 1;
			}else{
				$detra = 0;
			}

			$arr_cuotas = array();
			$total_cuotas = 0;
			if(isset($data["fecha_cuota"])){
				for($i = 0; $i<count($data["fecha_cuota"]); $i++){
					if(!empty($data["fecha_cuota"][$i]) && $data["monto_cuota"][$i]>0){
						$arr_cuotas[] = array(
							"fecha"				 => $data["fecha_cuota"][$i],
							"monto"				 => $data["monto_cuota"][$i],
							"codigo_tipo_moneda" => "PEN",
						);
						$total_cuotas += $data["monto_cuota"][$i];

						if($data["fecha_cuota"][$i] < date("Y-m-d")){
							$this->responsemodel->SetResponse(false);
							$this->responsemodel->message = 'No puede colocar cuotas con fechas anteriores a hoy.';
							return $this->responsemodel;
						}
					}
				}
			}
			
			// Actualizamos el Comprobante
			$cabecera = array(
				'ComprobanteTipo_id' => $data['ComprobanteTipo_id'],
				'Cliente_id'         => $data['Cliente_id'] != '' ? $data['Cliente_id'] : 0,
				'ClienteIdentidad'   => $data['ClienteIdentidad'],
				'ClienteNombre'      => $data['ClienteNombre'],
				'ClienteDireccion'   => $data['ClienteDireccion'],
				'Estado'             => 2,
				'FechaEmitido'       => ToDate($data['FechaEmitido']),
				'Iva'                => $iva,
				'IvaTotal'           => $IvaTotal,
				'SubTotal'           => $SubTotal,
				'Total'              => $total-ABS($data['totalDsc']),
				'TotalCompra'        => $totalC,
				'Usuario_id'         => $this->user->id,
				'Glosa'              => $data['Glosa'],
				'Ganancia'           => $total - $totalC,
				'FechaRegistro'      => date('Y/m/d'),
				'Empresa_id'         => $this->user->Empresa_id,
				'Dsc'       		 => $data['Dsc'],
				'totalDsc'       	 => $data['totalDsc'],
				'moneda' 			 => $data['moneda'],
				'tipo_cambio' 		 => $data['tipoCambio'],
				'comp_detraccion' 	 => $data['comp_detraccion'],
				'comp_detraccion_porcen'=> $data['txtPorDet'],
				'external_id_ref'	 => $data['external_id'],
				'motivo_anulacion'	 => $data['motivo_sustento'],
				'tipo_nota'			 => $data['tiponota'],
				'cuotas'			 => json_encode($arr_cuotas)
			);

			/** Validando total de nota, no debe exceder al total de la factura */
			$orgdoc = $this->db->where('external_id', $data["external_id"])->get('comprobante')->row();

			if($orgdoc->Total<$total){
				$this->responsemodel->SetResponse(false);
				$this->responsemodel->message = 'El total de la nota de crédito <b>no puede exceder</b> al total del comprobante afectado.';
				return $this->responsemodel;
			}

			if($data['ComprobanteTipo_id'] == 1) 
			{
				if($data['orgdoctype'] == 3){
					$cabecera['Serie'] = $this->conf->SNotaCred;
					$secomp = $this->conf->SNotaCred;
				}else{
					$cabecera['Serie'] = $this->conf->SNotaCredBol;
					$secomp = $this->conf->SNotaCredBol;
				}
				

				$t = $this->db->query("SELECT id,Correlativo+1 Total FROM notas WHERE Empresa_id = ".$this->user->Empresa_id." AND ComprobanteTipo_id = " . $data['ComprobanteTipo_id']." and Correlativo != 'NULL' AND Serie = '".$secomp."' ORDER BY id DESC LIMIT 1")
							  ->row();
				
				$cabecera['Correlativo'] = (!isset($t->Total)) ? 1 : $t->Total;

			}

			// Insertamos el comprobante
			$this->db->insert('notas', $cabecera);
			$last_id = $this->db->insert_id();
			

			// Agregamos el detalle
			foreach($detalle as $k => $d) $detalle[$k]['Comprobante_id'] = $last_id;
			$this->db->insert_batch('notasdetalle', $detalle);

			
			$comp = $this->generarNota($last_id, 1);
			
			$this->responsemodel->SetResponse(true);
			$this->responsemodel->href   = 'notas/nota/' . $last_id;
		}
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			log_message('1', __CLASS__ . '->' . __METHOD__);
			$this->responsemodel->SetResponse(false);
			$this->responsemodel->href = null;
		}
		
		return $this->responsemodel;
	}

	public function Obtener($id)
	{
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $id);
		$c = $this->db->get('notas')->row();
		
		$this->db->where('comprobante_Id', $id);
		$c->{'Detalle'} = $this->db->get('notasdetalle')->result();
		
		$c->{'Tipo'} = "Nota de Crédito";
		
		return $c;
	}


	//Emision de notas
	public function generarNota($id,$anu=0){
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $id);
		$comp = $this->db->get('notas')->row();
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$cfg = $this->db->get('configuracion')->row();
		$this->db->where('Comprobante_Id', $id);
		$det = $this->db->get('notasdetalle')->result();
		$this->db->where('id', $comp->Cliente_id);
		$clie = $this->db->select('Correo, Dni, Ruc')->get('cliente')->row();

		$orgdoc = $this->db->where('external_id', $comp->external_id_ref)->get('comprobante')->row();

		$usucmp = $this->db->where("id", $comp->Usuario_id)->get("usuario")->row();

		if(!empty($clie->Ruc) && strlen($clie->Ruc) == 11){
			$tipocomp = "07";
			$serie = $comp->Serie;
			
			$tipodoc = "6";

			$clienom = $comp->ClienteNombre;
		}else if(!empty($clie->Dni) && strlen($clie->Dni) == 8){
			$tipocomp = "07";
			$serie = $comp->Serie;
			
			$tipodoc = "1";

			$clienom = $comp->ClienteNombre;
		}

		$moneda = ($comp->moneda == "Usd") ? "USD":"PEN";
		$fec = str_replace("/","-",$comp->FechaEmitido);
		$prods = array();
		$it = 0;
		$total_sin_dscto = 0;
		foreach($det as $deta){
		    $it++;//incrementamos iteracion
		    
			$igvfact = $deta->PrecioTotal-($deta->PrecioTotal/1.18);
			$igvitem = $deta->PrecioUnitario-($deta->PrecioUnitario/1.18);

			$udmnube = ($deta->Tipo=="1") ? "NIU":"ZZ";
			
			$producto = $this->db->select('codigo_prod')->where("id", $deta->Producto_id)->get("producto")->row();
			
			if(isset($producto->codigo_prod) && !empty($producto->codigo_prod)){
				$codigo_prd = $producto->codigo_prod;
			}else{
				//$codigo_prd = str_shuffle($deta->ProductoNombre);
				srand(time());
				$nro = rand(1, 99999);
				$codigo_prd = time()."-".$nro+$it."-".$deta->id;
			}
			
			$prods[] = array(
				"unidad_de_medida"          => $udmnube,
				"codigo_interno"            => $codigo_prd,
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

			$total_sin_dscto += $deta->PrecioTotal;
		}

		$subtotalcomp = ($comp->Total/1.18);
		$igvcomptotal = $comp->Total-($comp->Total/1.18);

		$fec_em = explode("/", $comp->FechaEmitido);

		$tot_ds = number_format(abs($orgdoc->totalDsc),2, '.', '');

		$data = array(
			"serie_documento" 		=> $serie,
			"numero_documento" 		=> $comp->Correlativo,
			"fecha_de_emision" 		=> $fec_em[0]."-".$fec_em[1]."-".$fec_em[2],
			"hora_de_emision" 		=> date('h:i:s'),
			"codigo_tipo_nota" 		=> $comp->tipo_nota,
			"codigo_tipo_documento" => $tipocomp,
			"motivo_o_sustento_de_nota" => $comp->motivo_anulacion,
			"codigo_tipo_moneda" 	=> $moneda,
			"formato_pdf" 			=> "ticket",
			"numero_orden_de_compra"=> "",
			"documento_afectado" => array("external_id" => $comp->external_id_ref),
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
			    "telefono" => ""
			),
			"totales" => array(
				"total_descuentos"=> $tot_ds,
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
			"acciones" => array("enviar_xml_firmado"=> true, "enviar_email" => false)
		);

		if($tot_ds > 0){
			$base_dscto = number_format(($total_sin_dscto / 1.18), 2, '.', '');
			$total_dscto_f = number_format(abs($orgdoc->totalDsc) / 1.18, 2, '.', '');
			$factor_dscto = number_format($total_dscto_f / $base_dscto, 5, '.', '');

			$data["descuentos"] = array(
				array(
					"codigo" => "02",
					"descripcion" => "Descuentos globales que afectan la base imponible del IGV/IVAP",
					"factor" => $factor_dscto,
					"monto" => $total_dscto_f,
					"base" => $base_dscto,
				)
			);
		}

		/*$arr_cuotas = array();
		$total_cuotas = 0;
		for($i = 0; $i<count($data["fecha_cuota"]); $i++){
			if(!empty($data["fecha_cuota"][$i]) && $data["monto_cuota"][$i]>0){
				$arr_cuotas[] = array(
					"fecha"				 => $data["fecha_cuota"][$i],
					"monto"				 => $data["monto_cuota"][$i],
					"codigo_tipo_moneda" => "PEN",
				);
				$total_cuotas += $data["monto_cuota"][$i];

				if($data["fecha_cuota"][$i] < date("Y-m-d")){
					$this->responsemodel->SetResponse(false);
					$this->responsemodel->message = 'No puede colocar cuotas con fechas anteriores a hoy.';
					return $this->responsemodel;
				}
			}
		}*/

		if($orgdoc->tipo_pago == "2"){
			$data["cuotas"] = json_decode($comp->cuotas, true);
			$data["codigo_condicion_de_pago"] = "02";
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
		$this->db->where("id", $id)->update("notas", array("json_enviado" => $data_json));
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
			$this->db->where("id", $id)->update("notas", $upd_data);
		}elseif($leer_respuesta["success"] == false){

			/* Verificamos si el comprobante se ha emitido */

			$buscpe = $this->buscarcpe($id);

			/* Si no se ha emitido comprobante es probable que SUNAT este caido, intentamos emitirlo sin enviar */

			if($buscpe == false){
				$data["acciones"]["enviar_xml_firmado"] = false;

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
					$this->db->where("id", $id)->update("notas", $upd_data);
				}
			}
		}
		return $respuesta;
	}

	public function buscarcpe($idcomp){
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$cfg = $this->db->get('configuracion')->row();

		$this->db->where("id", $idcomp);
		$comp = $this->db->get('notas')->row();
		$datos = array(
			"serie_cpe" => "".$comp->Serie."",
			"num_cpe"	=>	"".$comp->Correlativo.""
		);
		$data_json = json_encode($datos);
		
		$ruta = $cfg->url_api."api/searchcpe";
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
			$upd_data = array(
				"link_xml"		=> $leer_respuesta["links"]["xml"],
				"link_pdf"		=> $leer_respuesta["links"]["pdf"],
				"link_cdr"		=> !empty($leer_respuesta["links"]["cdr"]) ? $leer_respuesta["links"]["cdr"]:"",
				"msj_sunat"		=> !empty($leer_respuesta["response"]["description"]) ? $leer_respuesta["response"]["description"]:"Comprobante se enviará en resumen diario.",
				"external_id"	=> $leer_respuesta["data"]["external_id"],
				"fecha_emision"	=> date("Y-m-d"),
				"json_enviado" => $data_json
			);
			$this->db->where("id", $idcomp)->update("notas", $upd_data);

			$respuesta = true;
		}else{
			$respuesta = false;
		}

		return $respuesta;
	}


	public function Listar()
	{
		$where = 'c.Empresa_id = ' . $this->user->Empresa_id . ' ';
		$this->filter = isset($_REQUEST['filters']) ? json_decode($_REQUEST['filters']) : null;

		if($this->filter != null)
		{
			foreach($this->filter->{'rules'} as $f)
			{
				if($f->field == 'Serie') $where .= "AND Serie LIKE '" . $f->data . "%' ";
				if($f->field == 'ClienteNombre') $where .= "AND ClienteNombre LIKE '" . $f->data . "%' ";
				if($f->field == 'ComprobanteTipo_id' && $f->data != 't') $where .= "AND ComprobanteTipo_id = '" . $f->data . "' ";
				if($f->field == 'EstadoNombre' && $f->data != 't') $where .= "AND Estado = '" . $f->data . "' ";
				if($f->field == 'FechaEmitido') $where .= "AND FechaEmitido = '" . ToDate($f->data) . "' ";
				if($f->field == 'Iva') $where .= "AND Iva = '" . $f->data . "' ";
				if($f->field == 'SubTotal') $where .= "AND SubTotal = '" . $f->data . "' ";
				if($f->field == 'Total') $where .= "AND Total = '" . $f->data . "' ";
			}
		}

		$this->db->where($where);
		$this->jqgridmodel->Config($this->db->SELECT('COUNT(*) Total FROM notas c')->get()->row()->Total);
		
		$sql = "
			SELECT 
				c.id,
				c.ComprobanteTipo_id,				
				IF (SERIE IS NULL, Correlativo, CONCAT(Serie, '-', Correlativo)) Codigo,
				IF (LENGTH(ClienteNombre) = 0, 'Sin Cliente', ClienteNombre) ClienteNombre,
				c.Estado,
				td.Nombre EstadoNombre,
				c.FechaEmitido,
				c.Iva,
				c.SubTotal,
				c.Total,
				c.Impresion,
				u.Nombre,
				u.Usuario
			FROM notas c
			INNER JOIN tabladato td
			ON c.Estado = td.Value
			AND td.Relacion = 'comprobanteestado'
			INNER JOIN usuario u
			ON c.Usuario_id = u.id
			WHERE $where
			ORDER BY " . $this->jqgridmodel->sord . "
			LIMIT " . $this->jqgridmodel->start . "," . $this->jqgridmodel->limit;

		$this->jqgridmodel->DataSource($this->db->query($sql)->result());
		
		foreach($this->jqgridmodel->rows as $d)
		{
			$d->Total = number_format($d->Total, 2);
		}
			
		return $this->jqgridmodel;
	}
}
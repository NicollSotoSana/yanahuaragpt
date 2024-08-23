<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Insertarpedido extends CI_Controller 
{
    /*Sincronizar productos Woocommerce */

	public function index(){
        echo "Holi =)";
    }
    
    public function Registrarcpe(){
		$data = $_POST;
        $conf = $this->db->where("Empresa_id", $data['Empresa_id'])->get("configuracion")->row();
		$this->db->trans_start();

		$total  = 0;
		$totalC = 0;
		
		// Detalle
		$items = 1;
		if($items == 0)
		{
			$this->responsemodel->message = 'El comprobante debe tener <b>un item</b> por lo menos.';
		}
		else
		{
			foreach($data['items'] as $it)
			{
				$producto_b = $this->db->where("codigo_prod", $it["product_sku"])->get("producto")->row();

				if(isset($producto_b->id)){
					$id_prod = $producto_b->id;
					$pu = $producto_b->PrecioCompra;

					$total  += $pu * $it["quantity"];
					$totalC += $it["total"];
				}else{
					$id_prod = 0;
					$pu = 0;

					$total  += $it["total"];
					$totalC += $pu * $it["quantity"];
				}

				$detalle[] = array(
					'tipo'                  => 1, // 1 Producto 2 Servicio
					'Producto_id'           => $id_prod,
					'ProductoNombre'        => $it["name"],
					'UnidadMedida_id' 	    => "UND",
					'Cantidad'              => $it["quantity"],
					'PrecioUnitarioCompra'  => $pu,
					'PrecioTotalCompra'     => $pu * $it["quantity"],
					'PrecioUnitario'        => $pu,
					'PrecioTotal'           => $it["total"],
					'Ganancia'              => ($it["total"]) - ($pu * $it["quantity"]),
				);
			
			}


			$iva = $conf->Iva;
			$SubTotal = $data['Total'] / ($iva / 100 + 1);
			$IvaTotal = $data['Total'] - $SubTotal;

			// Actualizamos el Comprobante
			$cabecera = array(
				'ComprobanteTipo_id' => $data['ComprobanteTipo_id'],
				'Cliente_id'         => $clie->id,
				'ClienteIdentidad'   => empty($clie->Dni) ? $clie->Ruc:$clie->Dni,
				'ClienteNombre'      => $clie->Nombre,
				'ClienteDireccion'   => $clie->Direccion,
				'Estado'             => 2,
				'FechaEmitido'       => ToDate($data['FechaEmitido']),
				'Iva'                => $iva,
				'IvaTotal'           => $IvaTotal,
				'SubTotal'           => $SubTotal,
				'Ganancia'           => $total - $totalC,
				'Total'              => $data['Total'],
				'TotalCompra'        => $totalC,
				'Usuario_id'         => 1,
				'FechaRegistro'      => date('Y/m/d'),
				'Empresa_id'         => $data['Empresa_id'],
				'Dsc'       		 => 0,
				'totalDsc'       	 => 0,
				'moneda' 			 => $data['Moneda']
			);
			
			// Asignamos los correlativo al menudeo
			$cabecera['Serie']       = null;
			$cabecera['Correlativo'] = null;
			if($data['ComprobanteTipo_id'] != 2 && $data['ComprobanteTipo_id'] != 3 && $data['ComprobanteTipo_id'] != 5)
			{
				$t = $this->db->query("SELECT MAX(Correlativo) + 1 Total FROM comprobante WHERE Empresa_id = '4' AND ComprobanteTipo_id = " . $data['ComprobanteTipo_id'])
							  ->row()->Total;
				if($data['ComprobanteTipo_id'] == 2 && $data['ComprobanteTipo_id'] == 3){
					$cabecera['Serie']       = $conf->Zeros;
					$cabecera['Correlativo'] = str_pad($t == NULL ? 1 : $t, $conf->Zeros, '0', STR_PAD_LEFT);
				}			  
				
			}

			if($data['ComprobanteTipo_id'] == 2) 
			{
				$cabecera['Serie'] = $conf->SBoleta;
				$secomp = $conf->SBoleta;
				

				$t = $this->db->query("SELECT id,Correlativo+1 Total FROM comprobante WHERE Empresa_id = '".$data['Empresa_id']."' AND ComprobanteTipo_id = " . $data['ComprobanteTipo_id']." and Correlativo != 'NULL' AND Serie = '".$secomp."' ORDER BY id DESC LIMIT 1")
							  ->row();
				$cabecera['Correlativo'] = (!isset($t->Total)) ? 1 : $t->Total;
				

			}elseif($data['ComprobanteTipo_id'] == 3){
				$cabecera['Serie'] = $conf->SFactura;
				$secomp = $conf->SFactura;
				
				$t = $this->db->query("SELECT id,Correlativo+1 Total FROM comprobante WHERE Empresa_id = '".$data['Empresa_id']."' AND ComprobanteTipo_id = " . $data['ComprobanteTipo_id']." and Correlativo != 'NULL' AND Serie = '".$secomp."' ORDER BY id DESC LIMIT 1")
							  ->row();
				$cabecera['Correlativo'] = (!isset($t->Total)) ? 1 : $t->Total;
				
				if(isset($t->Total) && $t->Total=="469"){
				    $cabecera['Correlativo'] = 470;
				}else{
				    $cabecera['Correlativo'] = (!isset($t->Total)) ? 1 : $t->Total;
				}
			}

			// Insertamos el comprobante
			$this->db->insert('comprobante', $cabecera);
			$last_id = $this->db->insert_id();

			// Agregamos el detalle
			foreach($detalle as $k => $d) $detalle[$k]['Comprobante_id'] = $last_id;
			$this->db->insert_batch('comprobantedetalle', $detalle);

            foreach($detalle as $d)
            {
                if($d['tipo'] == 1 && $d['Producto_id']!="00") // Solos los que sean productos
                {
                    // Vemos si hay el stock necesario
                    $this->db->where('id', $d['Producto_id']);
                    
                    $this->db->set('stock', 'stock - ' . $d['Cantidad'], FALSE);
                    $this->db->update('producto');

                    // Guardamos en el almacen
                    $this->db->insert('almacen', array(
                        'Tipo'            => 2,
                        'Usuario_id'      => 1,
                        'Producto_id'     => $d['Producto_id'],
                        'ProductoNombre'  => $d['ProductoNombre'],
                        'UnidadMedida_id' => $d['UnidadMedida_id'],
                        'Cantidad'        => $d['Cantidad'],
                        'Fecha'           => date('Y/m/d'),
                        'Empresa_id'      => $data['Empresa_id'],
                        'Comprobante_id'  => $d['Comprobante_id'],
                        'Precio'          => $d['PrecioTotal']
                    ));
                }
            }

			$comp = $this->enviarSunat($last_id, 1);

			return $comp;
		}
    }


    private function enviarSunat($id,$anu=0){

		$this->db->where('Empresa_id', 5);
		$this->db->where('id', $id);
		$comp = $this->db->get('comprobante')->row();
		$this->db->where('Empresa_id', 5);
		$cfg = $this->db->get('configuracion')->row();
		$this->db->where('Comprobante_Id', $id);
		$det = $this->db->get('comprobantedetalle')->result();
		$this->db->where('id', $comp->Cliente_id);
		$clie = $this->db->select('Correo')->get('cliente')->row();

		$usucmp = $this->db->where("id",$comp->Usuario_id)->get("usuario")->row();
		if($comp->ComprobanteTipo_id==3){
			$tipocomp = "01";
			$serie = $comp->Serie;
			
			$tipodoc = "6";

			$clienom = $comp->ClienteNombre;
			
		}elseif($comp->ComprobanteTipo_id==2){
			$tipocomp = "03";
			$serie = $comp->Serie;
			$tipodoc = "1";
				$clienom = $comp->ClienteNombre;
			//}
		}
		$moneda = ($comp->moneda == "Usd") ? "USD":"PEN";
		$fec = str_replace("/","-",$comp->FechaEmitido);
		$prods = array();
		foreach($det as $deta){
			$igvfact = $deta->PrecioTotal-($deta->PrecioTotal/1.18);
			$igvitem = $deta->PrecioUnitario-($deta->PrecioUnitario/1.18);

			$udmnube = ($deta->Tipo=="1") ? "NIU":"ZZ";
			
			$producto = $this->db->select('codigo_prod')->where("id", $deta->Producto_id)->get("producto")->row();
			$prods[] = array(
				"unidad_de_medida"          => $udmnube,
				"codigo_interno"            => $producto->codigo_prod,
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

		$subtotalcomp = ($comp->Total/1.18);
		$igvcomptotal = $comp->Total-($comp->Total/1.18);

		$fec_em = explode("/", $comp->FechaEmitido);

		$data = array(
			"serie_documento" 		=> $serie,
			"numero_documento" 		=> $comp->Correlativo,
			"fecha_de_emision" 		=> $fec_em[0]."-".$fec_em[1]."-".$fec_em[2],
			"hora_de_emision" 		=> date('h:i:s'),
			"codigo_tipo_operacion" => "0101",
			"codigo_tipo_documento" => $tipocomp,
			"codigo_tipo_moneda" 	=> $moneda,
			"formato_pdf" 			=> "proceramica",
			"fecha_de_vencimiento" 	=> $fec_em[0]."-".$fec_em[1]."-".$fec_em[2],
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
			    "telefono" => ""
			),
			"totales" => array(
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
			"acciones" => array("enviar_xml_firmado"=> false, "enviar_email" => false)
		);

		if(!isset($clie->Correo) || empty($clie->Correo)){
			$data["acciones"]["enviar_email"] = false;
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

		$leer_respuesta = json_decode($respuesta, true);
		if(empty($comp->retorno_todo)){
		    $this->db->where("id", $id)->update("comprobante", array("retorno_todo" => $respuesta));
		}
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
					$this->db->where("id", $id)->update("comprobante", $upd_data);
				}
			}
		}
		return $respuesta;
	}
}
<?php

use Automattic\WooCommerce\Client;

class ComprobanteModel extends CI_Model
{
	public function ImpresionPendiente()
	{
		$sql = "
			SELECT COUNT(*) Total FROM comprobante 
			WHERE Empresa_id = " . $this->user->Empresa_id . "
		";
		
		// Cuando cada usuario maneja su propia impresora
		if($this->conf->Impresion == 2)
		{
			$sql .= " AND UsuarioImprimiendo_id  = " . $this->user->id;
		}
		
		return $this->db->query($sql)->row()->Total == 0 ? true : false;
	}
	public function DisponibleParaImpresion($id)
	{
		$this->db->trans_start();
		
		// Verificamos que no se pueda imprimir la impresion de otro
		$sql = "SELECT 
					UsuarioImprimiendo_id,
					(SELECT Nombre FROM usuario WHERE id = UsuarioImprimiendo_id) Usuario
				FROM comprobante 
				WHERE
				id = $id
				AND Impresion = 1 
				AND UsuarioImprimiendo_id != " . $this->user->id;
		
		$row = $this->db->query($sql)->row();
		
		if(is_object($row))
		{
			$this->responsemodel->message = 'Este comprobante tiene una orden de impresión enviada por ' . $row->Usuario . ', para evitar errores usted no podra realizar esta acción.';
		}
		else
		{
			$sql = "
				SELECT 
					UsuarioImprimiendo_id,
					(SELECT Nombre FROM usuario WHERE id = UsuarioImprimiendo_id) Usuario FROM comprobante
				WHERE Impresion = 1
				AND id != $id
				AND Empresa_id = " . $this->user->Empresa_id . "
			";
			
			$row = $this->db->query($sql)->row();
									
			if(is_object($row))
			{
				if($row->UsuarioImprimiendo_id == $this->user->id)
				{
					$this->responsemodel->message = 'Actualmente usted tiene una impresión pendiente de otro comprobante.';
				}
				else
				{
					$this->responsemodel->message = 'Actualmente la Impresora esta siendo usada por ' . $row->Usuario . '.';					
				}
			}
			else
			{
				// Marcamos como preparando impresion
				$this->db->where('Empresa_id', $this->user->Empresa_id);
				$this->db->where('id', $id);
				$this->db->update('comprobante', array(
					'impresion'           => 1,
					'UsuarioImprimiendo_id' => $this->user->id
				));
				
				// Obtenemos la configuracion actual
				$this->db->where('Empresa_id', $this->user->Empresa_id);
				$conf = $this->db->get('configuracion')->row_array();
				
				// Obtenemos el correlativo actual
				$this->db->where('id', $id);
				$this->db->where('Empresa_id', $this->user->Empresa_id);
				$c = $this->db->get('comprobante')->row_array();
			
				if($c['ComprobanteTipo_id'] == 2)
				{
					$c['Serie']       = $conf['SBoleta'];
					$c['Correlativo'] = str_pad($conf['NBoleta'], $this->conf->Zeros, '0', STR_PAD_LEFT);
				}
				if($c['ComprobanteTipo_id'] == 3)
				{
					$c['Serie']       = $conf['SFactura'];
					$c['Correlativo'] = str_pad($conf['NFactura'], $this->conf->Zeros, '0', STR_PAD_LEFT);
				}

				if($c['ComprobanteTipo_id'] == 5)
				{
					$c['Serie']       = $conf['SGR'];
					$c['Correlativo'] = str_pad($conf['NGuia'], $this->conf->Zeros, '0', STR_PAD_LEFT);
				}
				
				if($c['ComprobanteTipo_id'] == 6)
				{
					$c['Serie']       = $conf['SGR'];
					$c['Correlativo'] = str_pad($conf['NOrden'], $this->conf->Zeros, '0', STR_PAD_LEFT);
				}

				$correlativo = $c['Serie'] . '-' . $c['Correlativo'];
							
				$this->responsemodel->response = true;
				$this->responsemodel->result   = $correlativo;
				$this->responsemodel->message  = "¿El correlativo '$correlativo' es igual al del talonario?";
			}			
		}
		
		$this->db->trans_complete();
		 
		if ($this->db->trans_status() === FALSE)
		{
			log_message('1', __CLASS__ . '->' . __METHOD__);
			$this->responsemodel->SetResponse(false);	
		}
		
		return $this->responsemodel;
	}
	public function CorregirCorrelativo($data)
	{
		$this->db->trans_start();
		
		if($data['Razon']=='1')
		{
			if($data['CorrelativoNuevo'][0] != $data['CorrelativoNuevo'][1])
			{
				$this->responsemodel->SetResponse(false);
				$this->responsemodel->message = "Los correlativos ingresados no coinciden. <b>Verifique la confirmación</b>.";
			}
			else
			{
				$correlativo_actual = explode('-', $data['CorrelativoActual']);
				$correlativo_nuevo  = (int)$data['CorrelativoNuevo'][0];
												
				if((int)$correlativo_actual[1] >= (int)$correlativo_nuevo)
				{
					$this->responsemodel->SetResponse(false);
					$this->responsemodel->message = "El <b>correlativo que intenta ingresar</b> debe ser <b>mayor al actual</b>.";					
				}
				else
				{
					// Obtenemos el comprobante actual
					$this->db->where('Empresa_id', $this->user->Empresa_id);
					$this->db->where('id', $data['id']);
					$c = $this->db->get('comprobante')->row_array();
					
					// Actualizamos la configuracion
					$this->db->where('Empresa_id', $this->user->Empresa_id);
					$this->db->update('configuracion', array(
						$c['ComprobanteTipo_id'] == 2 ? 'NBoleta' : 'NFactura' => $correlativo_nuevo
					));
					
					// Creamos comprobantes para revisar
					$i_comienza = (int)$correlativo_actual[1];
					for($i=$i_comienza; $i < $correlativo_nuevo; $i++)
					{
						$this->db->insert('comprobante', array(
							'Serie'        		 => $correlativo_actual[0],
							'Correlativo'        => str_pad($i, $this->conf->Zeros, '0', STR_PAD_LEFT),
							'ComprobanteTipo_id' => $data['Tipo'],
							'Estado'             => 4,
						    'Glosa'              => $data['Glosa'],
							'FechaEmitido'       => date('d/m/Y'),
							'Empresa_id'         => $this->user->Empresa_id,
							'Usuario_id'         => $this->user->id
						));
					}
					
					$this->responsemodel->SetResponse(true);
					$this->responsemodel->href = 'ventas/impresion/' . $data['id'].'/'.$c['Cliente_id'];
				}
			}
		}else
		{
			$this->responsemodel->SetResponse(true);
		}
		
		$this->db->trans_complete();
		 
		if ($this->db->trans_status() === FALSE)
		{
			log_message('1', __CLASS__ . '->' . __METHOD__);
			$this->responsemodel->SetResponse(false);	
		}
		
		return $this->responsemodel;
	}
	public function CancelarImpresion($id)
	{
		$this->db->trans_start();
		
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $id);
		$c = $this->db->get('comprobante')->row();
		
		// Actualizamos el comprobante
		$this->db->where('id', $id);
		$this->db->update('comprobante', array(
											'Impresion' => $c->Correlativo != '' ? 2 : 0,
											'UsuarioImprimiendo_id' => NULL
										));
		
		$this->responsemodel->SetResponse(true);
		$this->responsemodel->function = 'ImprimirDocumento();';
		
		$this->db->trans_complete();
		 
		if ($this->db->trans_status() === FALSE)
		{
			log_message('1', __CLASS__ . '->' . __METHOD__);
			$this->responsemodel->SetResponse(false);	
		}
		
 		return $this->responsemodel;
	}
	public function Imprimir($id, $formato)
	{
		$this->db->trans_start();

		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $id);
		$c = $this->db->get('comprobante')->row_array();
		
		// Obtenes la configuracion actual
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$conf = $this->db->get('configuracion')->row_array();
		
		// Marcamos como Impreso
		if($c['Impresion'] == 1)
		{
			$c['Impresion'] = 2;
			$c['UsuarioImprimiendo_id'] = NULL;
			$c['Estado'] = $c['Estado'] == 3 ? 3 : 2;

			// Estos NO tienen correlativo
			if($c['Correlativo'] == '')
			{
				// Correlativo
				if($c['ComprobanteTipo_id'] == 2)
				{
					$c['Serie']       = $conf['SBoleta'];
					$c['Correlativo'] = str_pad($conf['NBoleta'], $this->conf->Zeros, '0', STR_PAD_LEFT);
					$conf['NBoleta']++;
				}
				if($c['ComprobanteTipo_id'] == 3)
				{
					$c['Serie']       = $conf['SFactura'];
					$c['Correlativo'] = str_pad($conf['NFactura'], $this->conf->Zeros, '0', STR_PAD_LEFT);
					$conf['NFactura']++;
				}
				if($c['ComprobanteTipo_id'] == 5)
				{
					$c['Serie']       = $conf['SGR'];
					$c['Correlativo'] = str_pad($conf['NGuia'], $this->conf->Zeros, '0', STR_PAD_LEFT);
					$conf['NGuia']++;
				}
			}
			
			// Actualizamos el comprobante
			$this->db->where('Empresa_id', $this->user->Empresa_id);
			$this->db->where('id', $id);
			$this->db->update('comprobante', $c);
		}

		// Actualizamos el formato de impresion
		if($c['ComprobanteTipo_id'] == 2) $conf['BoletaFormato']  = str_replace(' background: none repeat scroll 0% 0% transparent;', '', $formato);;
		if($c['ComprobanteTipo_id'] == 3) $conf['FacturaFormato'] = str_replace(' background: none repeat scroll 0% 0% transparent;', '', $formato);;

		if($c['ComprobanteTipo_id'] == 5) $conf['GuiaFormato'] = str_replace(' background: none repeat scroll 0% 0% transparent;', '', $formato);;
		if($c['ComprobanteTipo_id'] == 6) $conf['OrdenFormato'] = str_replace(' background: none repeat scroll 0% 0% transparent;', '', $formato);;	
		if($c['ComprobanteTipo_id'] == 7) $conf['ProformaFormato'] = str_replace(' background: none repeat scroll 0% 0% transparent;', '', $formato);;	
		// Actualizamos la configuracion
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->update('configuracion', $conf);
		
		$this->responsemodel->SetResponse(true);
		
		$this->db->trans_complete();
		 
		if ($this->db->trans_status() === FALSE)
		{
			log_message('1', __CLASS__ . '->' . __METHOD__);
			$this->responsemodel->SetResponse(false);	
		}
		
 		return $this->responsemodel;
	}
	public function Actualizar($data)
	{
		$this->db->trans_start();
		
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $data['id']);
		$c = $this->db->get('comprobante')->row();
		
		if($data['Estado'] == 3) // Si queremos anular
		{
			$devolucion = 0;
			/*if($c->ComprobanteTipo_id=="4"){
				$gdeu = $this->db->where("id_comprobante", $data['id'])->get("deudas_pagos")->row();
				if(isset($gdeu->id_deuda)){
					$this->db->where('id_deuda', $gdeu->id_deuda);
					$this->db->set('monto_cancelado', 'monto_cancelado - ' . $gdeu->monto_pagado, FALSE);
					$this->db->update('deudas');
				}
			}else*/
			if($c->ComprobanteTipo_id=="2" || $c->ComprobanteTipo_id=="3" || $c->ComprobanteTipo_id=="4" || $c->ComprobanteTipo_id=="1"){
				$gdeu = $this->db->where("comprobante_id", $c->id)->get("deudas")->row();
				if(isset($gdeu->id_deuda)){
					$this->db->where('id_deuda', $gdeu->id_deuda);
					$this->db->delete('deudas_pagos');

					$this->db->where('id_deuda', $gdeu->id_deuda);
					$this->db->delete('deudas');
					
				}
			}

			if($c->id_orden_lab != 0 || $c->id_orden_lab != NULL || !empty($c->id_orden_lab)){
				$this->db->where("id_orden", $c->id_orden_lab)->update("orden_lab", array("id_estado_orden" => 4));
			}
			

			// Marcamos si tiene pendiente de anulación
			if(HasModule('stock') && ($c->ComprobanteTipo_id == 2 || $c->ComprobanteTipo_id == 5 || $c->ComprobanteTipo_id == 4))
			{
				if($this->db->query("SELECT COUNT(*) Total FROM comprobantedetalle WHERE Tipo = 1 AND comprobante_id = " . $data['id'])->row()->Total == 0) // No hay productos para devolver
				{
					$devolucion = 1;
				}
			}

			$this->db->where('Empresa_id', $this->user->Empresa_id);
			$this->db->where('id', $data['id']);
			$this->db->update('comprobante', array('Estado' => 3, 'Devolucion' => $devolucion));
			


			$this->responsemodel->SetResponse(true);
			$this->responsemodel->href = 'self';
		}
		else if($c->Estado == 4) // Modo Revision
		{
			// Detalle
			$items = 0;
			
			foreach($data['Producto_id'] as $id)
			{
				if($id!='') $items++;
			}
			
			if($items == 0)
			{
				$this->responsemodel->message = 'El comprobante debe tener un item por lo menos.';
				$this->responsemodel->function = 'ComboEstadoDefault();';
			}
			else
			{
				$total  = 0;
				$totalC = 0;
				
				// Detalle
				$detalle = array();
				for($i = 0; $i < count($data['Producto_id']); $i++)
				{
					if($data['Producto_id'][$i] != '')
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
							'Comprobante_id'        => $c->id
						);
	
						$total  += $data['PrecioUnitario'][$i] * $data['Cantidad'][$i];
						$totalC += $data['PrecioUnitarioCompra'][$i] * $data['Cantidad'][$i];
					}
				}

				$iva      = $c->ComprobanteTipo_id == 3 ? $data['Iva'] : 0;
				$SubTotal = $c->ComprobanteTipo_id == 3 ? $total / ($iva / 100 + 1) : 0;
				$IvaTotal = $c->ComprobanteTipo_id == 3 ? $total - $SubTotal : 0;

				// Actualizamos el Comprobante
				$cabecera = array(
					'Cliente_id'         => $data['Cliente_id'] != '' ? $data['Cliente_id'] : 0,
					'ClienteIdentidad'   => $data['ClienteIdentidad'],
					'ClienteNombre'      => $data['ClienteNombre'],
					'ClienteDireccion'   => $data['ClienteDireccion'],
					'Estado'             => $data['Estado'],
					'FechaEmitido'       => ToDate($data['FechaEmitido']),
					'Iva'                => $iva,
					'IvaTotal'           => $IvaTotal,
					'SubTotal'           => $SubTotal,
					'Total'              => $total-$data['totalDsc'],
					'TotalCompra'        => $totalC,
					'Usuario_id'         => $this->user->id,
					'Glosa'              => $data['Glosa'],
					'Ganancia'           => $total - $totalC,
					'FechaRegistro'      => date('Y/m/d')
				);

				// Actualizamos el comprobante
				$this->db->where('Empresa_id', $this->user->Empresa_id);
				$this->db->where('id', $data['id']);
				$this->db->update('comprobante', $cabecera);

				// Agregamos el detalle
				$this->db->where('comprobante_id', $data['id']);
				$this->db->delete('comprobantedetalle');

				// Registramos el stock
				if(HasModule('stock') && ($c->ComprobanteTipo_id == 2 || $c->ComprobanteTipo_id == 5 || $c->ComprobanteTipo_id == 4))
				{
					foreach($detalle as $d)
					{
						if($d['tipo'] == 1) // Solos los que sean productos
						{
							// Obtenemos el producto
							$this->db->where('id', $d['Producto_id']);
							$p = $this->db->get('producto')->row();

							// Vemos si hay el stock necesario
							$this->db->where('id', $d['Producto_id']);
							if(($p->Stock - $d['Cantidad']) >= 0) 
							{
								$this->db->set('stock', 'stock - ' . $d['Cantidad'], FALSE);
								$this->db->update('producto');
							}						
							else
							{
								$this->db->update('producto', array('Stock' => 0));
							}

							// Guardamos en el almacen
							$this->db->insert('almacen', array(
								'Tipo'            => 2,
								'Usuario_id'      => $this->user->id,
								'Producto_id'     => $d['Producto_id'],
								'ProductoNombre'  => $d['ProductoNombre'],
								'UnidadMedida_id' => $d['UnidadMedida_id'],
								'Cantidad'        => $d['Cantidad'],
								'Fecha'           => date('Y/m/d'),
								'Empresa_id'      => $this->user->Empresa_id,
								'Comprobante_id'  => $d['Comprobante_id'],
								'Precio'          => $d['PrecioTotal']
							));
						}
					}
				}

				foreach($detalle as $k => $d) $detalle[$k]['Comprobante_id'] = $data['id'];
				$this->db->insert_batch('comprobantedetalle', $detalle);

				$this->responsemodel->SetResponse(true);
				$this->responsemodel->href = 'self';
			}
		}
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			log_message('1', __CLASS__ . '->' . __METHOD__);
			$this->responsemodel->SetResponse(false);
			$this->responsemodel->href = null;
			$this->responsemodel->function = 'ComboEstadoDefault();';
		}
		
 		return $this->responsemodel;
	}
	public function Registrar($data)
	{
		//var_dump($data);
		
		$this->db->trans_start();

		$total  = 0;
		$totalC = 0;
		$cnrprd = 1;
		$puprd = 1;
		// Detalle
		$items = 0;
		
		foreach($data['Producto_id'] as $id)
		{
			if($id!='') $items++;
		}

		foreach($data['Cantidad'] as $cnt)
		{
			if($cnt=="0.00") $cnrprd=0;
		}

		for($i=0; $i<count($data['PrecioUnitario']); $i++)
		{
			if($data['PrecioUnitario'][$i]=="0.00" && $data['Cantidad'][$i] > 0) $puprd=0;
		}
		
		if($items == 0)
		{
			$this->responsemodel->message = 'El comprobante debe tener <b>un item</b> por lo menos.';
		}else if($puprd == 0)
		{
			$this->responsemodel->message = 'El comprobante no puede tener <b>items con precio igual a 0</b>.';
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


			$iva = $data['ComprobanteTipo_id'] == 3 ||  $data['ComprobanteTipo_id'] == 2 ? $data['Iva'] : 0;
			$SubTotal = $data['ComprobanteTipo_id'] == 3 ||  $data['ComprobanteTipo_id'] == 2 ? ($total-ABS($data['totalDsc'])) / ($iva / 100 + 1) : 0;
			$IvaTotal = $data['ComprobanteTipo_id'] == 3 ||  $data['ComprobanteTipo_id'] == 2 ? ($total-ABS($data['totalDsc'])) - $SubTotal : 0;
			$invent="";

			if($total>=700){
				$detra = 1;
			}else{
				$detra = 0;
			}
			$arr_cuotas = array();
			$total_cuotas = 0;
			for($i = 0; $i<count($data["fecha_cuota"]); $i++){
				if(!empty($data["fecha_cuota"][$i]) && $data["monto_cuota"][$i]>0){
					$arr_cuotas[] = array(
						"fecha"				 => $data["fecha_cuota"][$i],
						"monto"				 => $data["monto_cuota"][$i],
						"codigo_tipo_moneda" => "PEN",
					);
					$total_cuotas += $data["monto_cuota"][$i];

					if($data["fecha_cuota"][$i] < date("Y-m-d") || $data["fecha_cuota"][$i] == date("Y-m-d")){
						$this->responsemodel->SetResponse(false);
						$this->responsemodel->message = 'No puede colocar cuotas con fecha igual o anterior a hoy.';
						return $this->responsemodel;
					}
				}
			}

			$total_con_dscto = number_format($total-ABS($data['totalDsc']), 2);
			$tot_adela = number_format($data['ped_adela'], 2);

			if($data['tipo_pago'] == '1' && $total_con_dscto != $tot_adela && !isset($data['factura_gratuita']) && $data['factura_gratuita'] != 1){
				$this->responsemodel->SetResponse(false);
				$this->responsemodel->message = 'Para pagos al contado debe ingresar el monto total en el campo <b>Adelanto</b>.';
				return $this->responsemodel;
			}
			
			if($data['tipo_pago'] == '2' && $total_cuotas == 0){
				$this->responsemodel->SetResponse(false);
				$this->responsemodel->message = 'Debe consignar por lo menos 1 cuota.';
				return $this->responsemodel;
			}

			/*if($data['tipo_pago'] == '2' && $total_con_dscto != number_format($total_cuotas, 2)){
				$this->responsemodel->SetResponse(false);
				$this->responsemodel->message = 'La sumatoria de cuotas no coincide con el total del comprobante.';
				return $this->responsemodel;
			}*/
			$cuotas = json_encode($arr_cuotas);
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
				'adelanto'			 => $data['ped_adela'],
				'mediopago'			 => $data['mediopago'],
				'cliente_flag'		 => empty($data['clienteflag']) ? 0: $data['clienteflag'],
				'id_orden_lab'		 => !isset($data['id_orden']) ? 0: $data['id_orden'],
				'id_anamnesis'		 => !isset($data['id_anamnesis']) ? 0: $data['id_anamnesis'],
				'gratuita'	 		 => !isset($data['factura_gratuita']) ? 0: $data['factura_gratuita'],
				'fecha_emision' 	 => date('Y-m-d'),
				'deuda_generada'	 => !isset($data['generar_deuda']) ? 0 : $data['generar_deuda'],
				'nro_operacion'		 => !isset($data['nro_operacion']) ? "": $data['nro_operacion'],
				'tipo_pago'			 => $data['tipo_pago'],
				'cuotas'			 => $cuotas,
			);
			
			// Asignamos los correlativo al menudeo
			$cabecera['Serie']       = null;
			$cabecera['Correlativo'] = null;
			if($data['ComprobanteTipo_id'] != 2 && $data['ComprobanteTipo_id'] != 3 && $data['ComprobanteTipo_id'] != 5)
			{
				$t = $this->db->query("SELECT MAX(Correlativo) + 1 Total FROM comprobante WHERE Empresa_id = " . $this->user->Empresa_id . " AND ComprobanteTipo_id = " . $data['ComprobanteTipo_id'])
							  ->row()->Total;
							  
				$cabecera['Serie']       = null;
				$cabecera['Correlativo'] = str_pad($t == NULL ? 1 : $t, $this->conf->Zeros, '0', STR_PAD_LEFT);
			}

			if($data['ComprobanteTipo_id'] == 2) 
			{
				$cabecera['Serie'] = $this->conf->SBoleta;
				$secomp = $this->conf->SBoleta;
				

				$t = $this->db->query("SELECT id,Correlativo+1 Total FROM comprobante WHERE Empresa_id = '".$this->user->Empresa_id."' AND ComprobanteTipo_id = " . $data['ComprobanteTipo_id']." and Correlativo != 'NULL' AND Serie = '".$secomp."' ORDER BY id DESC LIMIT 1")
							  ->row();
				
				$cabecera['Correlativo'] = (!isset($t->Total)) ? 1 : $t->Total;

			}elseif($data['ComprobanteTipo_id'] == 3){
				$cabecera['Serie'] = $this->conf->SFactura;
				$secomp = $this->conf->SFactura;
				
				$t = $this->db->query("SELECT id,Correlativo+1 Total FROM comprobante WHERE Empresa_id = '".$this->user->Empresa_id."' AND ComprobanteTipo_id = " . $data['ComprobanteTipo_id']." and Correlativo != 'NULL' AND Serie = '".$secomp."' ORDER BY id DESC LIMIT 1")
							  ->row();
				$cabecera['Correlativo'] = (!isset($t->Total)) ? 1 : $t->Total;
			}
			// Insertamos el comprobante
			$this->db->insert('comprobante', $cabecera);
			$last_id = $this->db->insert_id();
			if(isset($data['generar_deuda']) && $data['generar_deuda'] == "1"){
				$this->db->insert('deudas', array(
					'id_cliente'        => $data['Cliente_id'] != '' ? $data['Cliente_id'] : 0,
					'monto_deuda'       => $total-ABS($data['totalDsc']),
					'comprobante_id'	=> $last_id,
					'fecha'  			=> date('Y-m-d'),
					'Empresa_id'		=> $this->user->Empresa_id
				));
				$last_id_deuda = $this->db->insert_id();
				if($data['ped_adela']>0){
					$this->db->insert('deudas_pagos', array(
						'id_deuda' 	   		=> $last_id_deuda,
						'monto_pagado' 		=> $data['ped_adela'],
						'fecha' 	   		=> date("Y-m-d"),
						'id_comprobante'	=> $last_id
					));

					//$insert_id = $this->db->insert_id();
					$this->db->where('id_deuda',$last_id_deuda);
					$this->db->set('monto_cancelado', 'monto_cancelado + ' . $data['ped_adela'], FALSE);
					$this->db->update('deudas');
				}
			}
			// Agregamos el detalle
			foreach($detalle as $k => $d) $detalle[$k]['Comprobante_id'] = $last_id;
			$this->db->insert_batch('comprobantedetalle', $detalle);

			// Registramos el stock
			if(HasModule('stock') && ($data['ComprobanteTipo_id'] == 3 || $data['ComprobanteTipo_id'] == 2 || $data['ComprobanteTipo_id'] == 4))
			{
				foreach($detalle as $d)
				{
					if($d['tipo'] == 1 && $d['Producto_id']!="00") // Solos los que sean productos
					{
						// Obtenemos el producto
						$this->db->where('id', $d['Producto_id']);
						$p = $this->db->get('producto')->row();

						//Verificamos si es UDM real o equivalente

						/*if($p->UnidadMedida_id != $d['UnidadMedida_id']){
							$eq = $p->cant_equivalente;
							$cantifin = ($d['Cantidad']/$eq);
						}else{
							$cantifin = $d['Cantidad'];
						}*/
						$cantifin = $d['Cantidad'];
						// Vemos si hay el stock necesario
						$this->db->where('id', $d['Producto_id']);
						
						/*if(($p->Stock - $cantifin) >= 0) 
						{*/
							$this->db->set('stock', 'stock - ' . $cantifin, FALSE);
							$this->db->update('producto');
						/*}						
						else
						{
							$this->db->update('producto', array('Stock' => 0));
						}*/

						// Guardamos en el almacen
						$stock_actual = $p->Stock - $cantifin;

						// Guardamos en el almacen
						$this->db->insert('almacen', array(
							'Tipo'            => 2,
							'Usuario_id'      => $this->user->id,
							'Producto_id'     => $d['Producto_id'],
							'ProductoNombre'  => $d['ProductoNombre'],
							'UnidadMedida_id' => $d['UnidadMedida_id'],
							'Cantidad'        => $cantifin,
							'Fecha'           => date('Y/m/d'),
							'Empresa_id'      => $this->user->Empresa_id,
							'Comprobante_id'  => $d['Comprobante_id'],
							'Precio'          => $d['PrecioTotal'],
							'stock_actual'	  => $stock_actual
						));


						//var_dump($d['Producto_id']);

						//$updtienda = $this->updTienda($d['Producto_id']);
						$this->db->where("id", $d['Producto_id'])->update("producto", array("pendiente_upd" => 1));
					}
				}
			}

			if(isset($data['id_anamnesis']) && !empty($data['id_anamnesis'])){
				$this->db->where("id_anamnesis", $data['id_anamnesis'])->update("anamnesis", array("estado" => 2, "id_usuario" => $this->user->id));
			}
			
			$this->responsemodel->SetResponse(true);
			$this->responsemodel->href   = 'ventas/comprobante/' . $last_id;
		}
		
		$this->db->trans_complete();
		
		if($data['ComprobanteTipo_id'] == 3 || $data['ComprobanteTipo_id'] == 2){
			$comp = $this->enviarSunat($last_id, 1);
		}

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
		$c = $this->db->get('comprobante')->row();
		
		$this->db->where('comprobante_Id', $id);
		$c->{'Detalle'} = $this->db->get('comprobantedetalle')->result();
		
		$this->db->where('Value', $c->ComprobanteTipo_id);
		$this->db->where('relacion', 'comprobantetipo');
		$c->{'Tipo'} = $this->db->get('tabladato')->row();
		
		return $c;
	}
	public function Obtener_consolid($id, $tip)
	{
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $id);
		$this->db->where('ComprobanteTipo_id', $tip);
		$c = $this->db->get('comprobante')->row();
		
		$this->db->where('Comprobante_Id', $id);
		$c->{'Detalle'} = $this->db->get('comprobantedetalle')->result();
		
		$this->db->where('Value', $c->ComprobanteTipo_id);
		$this->db->where('relacion', 'comprobantetipo');
		$c->{'Tipo'} = $this->db->get('tabladato')->row();
		
		return $c;
	}
	public function ObtenerPrueba($tipo)
	{
		$f = date('Y/m/d');
		// Cabecera
		$c = array(
			'id'                  => 0,
			'Empresa_id'          => $this->conf->Empresa_id,
			'Serie'               => '002',
			'Correlativo'         => '00001',
			'Cliente_id'          => 1,
			'ClienteIdentidad'    => '12345678910',
			'ClienteNombre'       => 'Cliente de Prueba',
			'ClienteDireccion'    => 'Dirección de Prueba',
			'ComprobanteTipo_id'  => $tipo,
			'Estado'              => 2,   
			'FechaRegistro'       => $f,
			'FechaEmitido'        => $f,
			'Iva'                 => 18.00,      
			'IvaTotal'            => 180.00,
			'SubTotal'            => 820.00,
			'Total'               => 1000.00,
			'Impresion'           => 1
		);
		
		for($i = 1; $i <= 4; $i++)
		{
			$c['Detalle'][] = (object)array(
				'Tipo'                 => 1,
				'Comprobante_id'       => 0,
				'Producto_id'          => 0,
				'ProductoNombre'       => 'Item ' . $i,
				'UnidadMedida_id'      => 'UND',
				'PrecioUnitario'       => 200.00,
				'PrecioTotal'          => 200.00,
				'Cantidad'             => 1
			);
		}
		
		return (object)$c;
	}
	public function Eliminar($id)
	{
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $id);
		$this->db->delete('comprobante');

		$this->db->where('comprobante_id', $id);
		$this->db->delete('comprobantedetalle');
		
		$this->responsemodel->SetResponse(true);
		$this->responsemodel->href   = 'ventas';
		
		return $this->responsemodel;
	}
	public function Listar()
	{
		$where = 'c.Empresa_id = ' . $this->user->Empresa_id . ' ';
		$this->filter = isset($_REQUEST['filters']) ? json_decode($_REQUEST['filters']) : null;

		if($this->filter != null)
		{
			foreach($this->filter->{'rules'} as $f)
			{
				if($f->field == 'Codigo') $where .= "AND IF (SERIE IS NULL, Correlativo, CONCAT(Serie, '-', Correlativo)) LIKE '%" . $f->data . "%' ";
				if($f->field == 'ClienteNombre') $where .= "AND ClienteNombre LIKE '%" . $f->data . "%' ";
				if($f->field == 'ComprobanteTipo_id' && $f->data != 't') $where .= "AND ComprobanteTipo_id = '" . $f->data . "' ";
				if($f->field == 'EstadoNombre' && $f->data != 't') $where .= "AND Estado = '" . $f->data . "' ";
				if($f->field == 'FechaEmitido') $where .= "AND FechaEmitido = '" . ToDate($f->data) . "' ";
				if($f->field == 'Iva') $where .= "AND Iva = '" . $f->data . "' ";
				if($f->field == 'SubTotal') $where .= "AND SubTotal = '" . $f->data . "' ";
				if($f->field == 'Total') $where .= "AND Total = '" . $f->data . "' ";
				if($f->field == 'id_orden_lab') $where .= "AND id_orden_lab = '" . $f->data . "' ";
				if($f->field == 'mediopago' && $f->data != 't') $where .= "AND mediopago = '" . $f->data . "' ";
				if($f->field == 'Glosa') $where .= "AND Glosa LIKE '%" . $f->data . "%' ";
			}
		}

		//$this->db->where($where);
		//$this->jqgridmodel->Config($this->db->SELECT('COUNT(*) Total FROM comprobante c')->get()->row()->Total);
		$sql1 = "
			SELECT 
				c.id,
				c.ComprobanteTipo_id,				
				IF (SERIE IS NULL, Correlativo, CONCAT(Serie, '-', Correlativo)) Codigo,
				IF (LENGTH(ClienteNombre) = 0, 'Sin Cliente', ClienteNombre) ClienteNombre,
				c.Estado,
				td.Nombre EstadoNombre,
				ct.Nombre Tipo,
				c.FechaEmitido,
				c.Iva,
				c.SubTotal,
				c.Total,
				u.Nombre,
				u.Usuario,
				c.adelanto,
				c.mediopago,
				c.id_orden_lab,
				IFNULL((deu.monto_deuda - deu.monto_cancelado), 0) as saldo,
				c.Glosa as Glosa
			FROM comprobante c
			LEFT JOIN tabladato ct
			ON c.ComprobanteTipo_id = ct.Value
			AND ct.Relacion = 'comprobantetipo'
			INNER JOIN tabladato td
			ON c.Estado = td.Value
			AND td.Relacion = 'comprobanteestado'
			INNER JOIN usuario u
			ON c.Usuario_id = u.id
			LEFT JOIN deudas deu
			ON deu.comprobante_id = c.id
			WHERE $where";
		$this->jqgridmodel->Config($this->db->query($sql1)->num_rows());

		$sql = "
			SELECT 
				c.id,
				c.ComprobanteTipo_id,				
				IF (SERIE IS NULL, Correlativo, CONCAT(Serie, '-', Correlativo)) Codigo,
				IF (LENGTH(ClienteNombre) = 0, 'Sin Cliente', ClienteNombre) ClienteNombre,
				c.Estado,
				td.Nombre EstadoNombre,
				ct.Nombre Tipo,
				c.FechaEmitido,
				c.Iva,
				c.SubTotal,
				c.Total,
				u.Nombre,
				u.Usuario,
				c.adelanto,
				c.mediopago,
				c.id_orden_lab,
				IFNULL((deu.monto_deuda - deu.monto_cancelado), 0) as saldo,
				c.Glosa as Glosa
			FROM comprobante c
			LEFT JOIN tabladato ct
			ON c.ComprobanteTipo_id = ct.Value
			AND ct.Relacion = 'comprobantetipo'
			INNER JOIN tabladato td
			ON c.Estado = td.Value
			AND td.Relacion = 'comprobanteestado'
			INNER JOIN usuario u
			ON c.Usuario_id = u.id
			LEFT JOIN deudas deu
			ON deu.comprobante_id = c.id
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
	public function Devolver($data)
	{
		$this->db->trans_start();

		$Finalizar = true;

		// Devolvemos productos al almacen
		for($i = 0; $i < count($data['detalle_id']); $i++)
		{
			// Traemos el detalle
			$this->db->where('id', $data['detalle_id'][$i]);
			$d = $this->db->get('comprobantedetalle')->row();

			// Verificamos si la cantidad a devolver es realmente la que disponemos
			if((float)$data['detalle_devuelto'][$i] <= $d->Cantidad)
			{
				// Actualizamos el comprobantedetalle
				$this->db->where('id', $d->id);
				$this->db->update('comprobantedetalle', array('Devuelto' => $data['detalle_devuelto'][$i]));

				// Agregamos la devolucion al almacen
				$this->db->insert('almacen', array(
					'Tipo'            => 3,
					'Usuario_id'      => $this->user->id,
					'Producto_id'     => $d->Producto_id,
					'ProductoNombre'  => $d->ProductoNombre,
					'UnidadMedida_id' => $d->UnidadMedida_id,
					'Cantidad'        => $data['detalle_devuelto'][$i],
					'Fecha'           => date('Y/m/d'),
					'Empresa_id'      => $this->user->Empresa_id,
					'Comprobante_id'  => $data['Comprobante_id']
				));

				// Regresamos el stock
				$this->db->where('id', $d->Producto_id);
				$this->db->set('stock', 'stock + ' . $data['detalle_devuelto'][$i], FALSE);
				$this->db->update('producto');
			}
			else if ((float)$data['detalle_devuelto'][$i] > $d->Cantidad)
			{
				$this->responsemodel->SetResponse(false, 'La cantidad a devolver no puede ser mayor a la que tiene actualmente para el producto "' . $d->ProductoNombre . '"');
				$Finalizar = false;
			}
			else if ((float)$data['detalle_devuelto'][$i] < 0)
			{
				$this->responsemodel->SetResponse(false, 'Usted esta intentado devolver cantidades menores a 0 para el producto "' . $d->ProductoNombre . '"');
				$Finalizar = false;
			}
		}

		if($Finalizar)
		{
			$ndata = $this->anularSunat($data['Comprobante_id'], $data['motivo']);
			
			
			// Marcamos diciendo que ya no hay productos para devolver
			$this->db->where('id', $data['Comprobante_id']);
			$this->db->where('Empresa_id', $this->user->Empresa_id);
			$this->db->update('comprobante', array('devolucion' => '1', 'motivo_anulacion' => $data['motivo'], 'fecha_anulacion' => date("Y-m-d")));
			
			$this->responsemodel->SetResponse(true);
			$this->responsemodel->href   = 'ventas/comprobante/' . $data['Comprobante_id'];
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
	public function ObtenerProductosParaDevolucion($comprobante_id)
	{
		$this->db->where('Comprobante_id', $comprobante_id);
		$this->db->where('Tipo', 1);
		return $this->db->get('comprobantedetalle')->result();
	}
	public function Tipos()
	{
		$this->db->where("relacion", 'comprobantetipo');
		return $this->db->get('tabladato')->result();
	}
	public function Estados()
	{
		$this->db->where("relacion", 'comprobanteestado');
		return $this->db->get('tabladato')->result();
	}

	public function Entregar($data)
	{
		$this->db->trans_start();

		$Finalizar = true;
		
		$total  = 0;
		$totalC = 0;
		$idcomp = 0;
		// Devolvemos productos al almacen
		for($i = 0; $i < count($data['detalle_id']); $i++)
		{
			$idcomp = $data['compid'][$i];

			// Traemos el detalle
			$this->db->where('id', $data['detalle_id'][$i]);
			$d = $this->db->get('comprobantedetalle')->row();

			// Verificamos si la cantidad a entregar es realmente la que disponemos
			if((float)$data['detalle_devuelto'][$i] >= $d->Cantidad)
			{
				
				//Verificamos si es UDM real o equivalente

				$this->db->where('id', $d->Producto_id);
				$p = $this->db->get('producto')->row();

				if($d->UnidadMedida_id != $p->UnidadMedida_id){
					$eq = $p->cant_equivalente;
					$cantifin = ($data['detalle_devuelto'][$i]/$eq);
					$total  += $d->PrecioUnitario * $data['detalle_devuelto'][$i];
				}else{
					$cantifin = $data['detalle_devuelto'][$i];
					$total  += $d->PrecioUnitario * $data['detalle_devuelto'][$i];
				}
				$ptotal = $d->PrecioUnitario * $data['detalle_devuelto'][$i];

				// Actualizamos el comprobantedetalle
				$this->db->where('id', $d->id);
				$this->db->update('comprobantedetalle', array('Entregado' => $data['detalle_devuelto'][$i], 'PrecioTotal' => $ptotal));

				// Agregamos la entrega al almacen
				$this->db->insert('almacen', array(
					'Tipo'            => 2,
					'Usuario_id'      => $this->user->id,
					'Producto_id'     => $d->Producto_id,
					'ProductoNombre'  => $d->ProductoNombre,
					'UnidadMedida_id' => $d->UnidadMedida_id,
					'Cantidad'        => $cantifin,
					'Fecha'           => date('Y/m/d'),
					'Empresa_id'      => $this->user->Empresa_id,
					'Comprobante_id'  => $data['Comprobante_id']
				));

				// Reducimos el stock
				$this->db->where('id', $d->Producto_id);
				$this->db->set('stock', 'stock - ' . $data['detalle_devuelto'][$i], FALSE);
				$this->db->update('producto');



			}
			else if ((float)$data['detalle_devuelto'][$i] < 0)
			{
				$this->responsemodel->SetResponse(false, 'La cantidad no puede ser menor a 0 para el producto "' . $d->ProductoNombre . '"');
				$Finalizar = false;
			}
		}

		$subt = $total / 1.18;
		$igv = $total - $subt;
		$this->db->where('id', $idcomp);
		$this->db->update('comprobante', array('SubTotal' => $subt, 'IvaTotal' =>$igv, 'Total' => $total, 'entregado' => 1));

		$this->db->where('id', $idcomp);
		$comp = $this->db->get('comprobante')->row();
		$adelanto = ($comp->adelanto!="0.00") ? $comp->adelanto : 0;
		$this->db->insert('deudas', array(
			'id_cliente'        => $comp->Cliente_id,
			'monto_deuda'       => $comp->Total-$adelanto,
			'comprobante_id'	=> $idcomp,
			'fecha'  			=> date('Y-m-d')
		));



		if($Finalizar)
		{
			// Marcamos diciendo que ya fueron entregados los productos
			$this->db->where('id', $data['Comprobante_id']);
			$this->db->where('Empresa_id', $this->user->Empresa_id);
			$this->db->update('comprobante', array('devolucion' => '1'));
			
			$this->responsemodel->SetResponse(true);
			$this->responsemodel->href   = 'ventas/comprobante/' . $data['Comprobante_id'];
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

	public function anularSunat($idcomp, $motivo)
	{
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$cfg = $this->db->get('configuracion')->row();
		$this->db->where("id", $idcomp);
		$comp = $this->db->get('comprobante')->row();
		$this->db->where("id_orden", $comp->id_orden_lab)->update("orden_lab", array("id_estado_orden" => 4));

		$ruta_anulacion = "";
		$fecha_de_emision_de_documentos = date("Y-m-d", strtotime($comp->FechaEmitido));
		$documentos_arr[] = array(
			"external_id"      => $comp->external_id,
			"motivo_anulacion" => $motivo
		);
		$inv_voided = array(
			"fecha_de_emision_de_documentos" => $fecha_de_emision_de_documentos,
			"documentos" => $documentos_arr
		);
		$authorization = "Authorization: Bearer " . $cfg->token_sunat . "";
		$flag = 0;
		if ($comp->ComprobanteTipo_id == 3) {
			//Envio Factura
			$ruta_anulacion = "api/voided";
			$ruta_envio_cpe = "api/documents/send";
			$envio_cpe_json = array("external_id" => $comp->external_id);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $cfg->url_api . $ruta_envio_cpe);
			curl_setopt(
				$ch,
				CURLOPT_HTTPHEADER,
				array('Content-Type: application/json', $authorization)
			);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($envio_cpe_json));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_exec($ch);
			curl_close($ch);
			$flag = 1;
		}
		if($comp->ComprobanteTipo_id == 2){
			//Envio Boleta
			$ruta_envio_cpe = "/api/summaries";
			$ruta_anulacion = "/api/summaries";
			$envio_cpe_json = array("fecha_de_emision_de_documentos" => $fecha_de_emision_de_documentos, "codigo_tipo_proceso" => "1");
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $cfg->url_api . $ruta_envio_cpe);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($envio_cpe_json));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_exec($ch);
			curl_close($ch);
			$flag = 1;
		}

		if ($flag === 1) {

			if ($comp->ComprobanteTipo_id == "2") {
				$inv_voided["codigo_tipo_proceso"] = "3";
			}
			$data_json = json_encode($inv_voided);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $cfg->url_api.$ruta_anulacion);
			curl_setopt(
				$ch,
				CURLOPT_HTTPHEADER,
				array('Content-Type: application/json', $authorization)
			);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$respuesta  = curl_exec($ch);
			curl_close($ch);
			$leer_respuesta = json_decode($respuesta, true);
			if(file_exists('log_sync.txt')){
				file_put_contents('log_sync.txt', '\n'. json_encode($inv_voided), FILE_APPEND);
				file_put_contents('log_sync.txt', '\n'. $respuesta, FILE_APPEND);
			}
			//Verificamos anulacion
			
			if ($leer_respuesta["success"] == true) {
				$consulta = array("external_id" => $leer_respuesta["data"]["external_id"], "ticket" => $leer_respuesta["data"]["ticket"]);
				$ruta = $cfg->url_api . "api/voided/status";
				$authorization = "Authorization: Bearer " . $cfg->token_sunat . "";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $ruta);
				curl_setopt(
					$ch,
					CURLOPT_HTTPHEADER,
					array('Content-Type: application/json', $authorization)
				);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($consulta));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$respuesta_ti  = curl_exec($ch);
				curl_close($ch);

				$upd = array(
					"ticket_sunat"			=>	$leer_respuesta["data"]["ticket"],
					"external_id_anulacion"		=>	$leer_respuesta["data"]["external_id"],
					"json_anulacion"	=> $respuesta_ti
				);
				$this->db->where("id", $idcomp)->update("comprobante", $upd);
				//echo $idcomp;
			}
		}
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
		
		$cerca_od = " | CERCA OD ";
		$cerca_oi = " | CERCA OI ";

		$lejos_od = " | LEJOS OD ";
		$lejos_oi = " | LEJOS OI ";
		$str_medida = '';
		if($comp->id_orden_lab != 0){
			$ordenla = $this->db->select("fecha_entrega")->where("id_orden", $comp->id_orden_lab)->get("orden_lab")->row();
			$fecha_entrega_trab = date("d/m/Y", strtotime($ordenla->fecha_entrega))." HORA: ".date("h:i A", strtotime($ordenla->fecha_entrega));

			$eval = $this->clm->ObtenerOrden($comp->id_orden_lab);

			$cerca_od .= isset($eval["cerca_refra_od_esf"]) ? "ESF: ".$eval["cerca_refra_od_esf"] : '';
			$cerca_od .= isset($eval["cerca_refra_od_cyl"]) ? " CYL: ".$eval["cerca_refra_od_cyl"] : '';
			$cerca_od .= isset($eval["cerca_refra_od_eje"]) ? " EJE: ".$eval["cerca_refra_od_eje"] : '';
			$cerca_od .= isset($eval["cerca_refra_od_adicion"]) ? " ADIC: ".$eval["cerca_refra_od_adicion"] : '';
			$cerca_od .= isset($eval["cerca_refra_od_dnp"]) ? " DIP: ".$eval["cerca_refra_od_dnp"] : '';
			$cerca_od .= isset($eval["cerca_refra_od_alt"]) ? " ALT: ".$eval["cerca_refra_od_alt"] : '';
			$cerca_od .= isset($eval["cerca_refra_od_avcc"]) ? " AVCC: ".$eval["cerca_refra_od_avcc"] : '';
			$cerca_od .= isset($eval["cerca_refra_od_prismas"]) ? " PRISM: ".$eval["cerca_refra_od_prismas"] : '';

			$cerca_oi .= isset($eval["cerca_refra_oi_esf"]) ? "ESF: ".$eval["cerca_refra_oi_esf"] : '';
			$cerca_oi .= isset($eval["cerca_refra_oi_cyl"]) ? " CYL: ".$eval["cerca_refra_oi_cyl"] : '';
			$cerca_oi .= isset($eval["cerca_refra_oi_eje"]) ? " EJE: ".$eval["cerca_refra_oi_eje"] : '';
			$cerca_oi .= isset($eval["cerca_refra_oi_adicion"]) ? " ADIC: ".$eval["cerca_refra_oi_adicion"] : '';
			$cerca_oi .= isset($eval["cerca_refra_oi_dnp"]) ? " DIP: ".$eval["cerca_refra_oi_dnp"] : '';
			$cerca_oi .= isset($eval["cerca_refra_oi_alt"]) ? " ALT: ".$eval["cerca_refra_oi_alt"] : '';
			$cerca_oi .= isset($eval["cerca_refra_oi_avcc"]) ? " AVCC: ".$eval["cerca_refra_oi_avcc"] : '';
			$cerca_oi .= isset($eval["cerca_refra_oi_prismas"]) ? " PRISM: ".$eval["cerca_refra_oi_prismas"] : '';

			$lejos_od .= isset($eval["lejos_refra_od_esf"]) ? "ESF: ".$eval["lejos_refra_od_esf"] : '';
			$lejos_od .= isset($eval["lejos_refra_od_cyl"]) ? " CYL: ".$eval["lejos_refra_od_cyl"] : '';
			$lejos_od .= isset($eval["lejos_refra_od_eje"]) ? " EJE: ".$eval["lejos_refra_od_eje"] : '';
			$lejos_od .= isset($eval["lejos_refra_od_adicion"]) ? " ADIC: ".$eval["lejos_refra_od_adicion"] : '';
			$lejos_od .= isset($eval["lejos_refra_od_dnp"]) ? " DIP: ".$eval["lejos_refra_od_dnp"] : '';
			$lejos_od .= isset($eval["lejos_refra_od_alt"]) ? " ALT: ".$eval["lejos_refra_od_alt"] : '';
			$lejos_od .= isset($eval["lejos_refra_od_avcc"]) ? " AVCC: ".$eval["lejos_refra_od_avcc"] : '';
			$lejos_od .= isset($eval["lejos_refra_od_prismas"]) ? " PRISM: ".$eval["lejos_refra_od_prismas"] : '';

			$lejos_oi .= isset($eval["lejos_refra_oi_esf"]) ? "ESF: ".$eval["lejos_refra_oi_esf"] : '';
			$lejos_oi .= isset($eval["lejos_refra_oi_cyl"]) ? " CYL: ".$eval["lejos_refra_oi_cyl"] : '';
			$lejos_oi .= isset($eval["lejos_refra_oi_eje"]) ? " EJE: ".$eval["lejos_refra_oi_eje"] : '';
			$lejos_oi .= isset($eval["lejos_refra_oi_adicion"]) ? " ADIC: ".$eval["lejos_refra_oi_adicion"] : '';
			$lejos_oi .= isset($eval["lejos_refra_oi_dnp"]) ? " DIP: ".$eval["lejos_refra_oi_dnp"] : '';
			$lejos_oi .= isset($eval["lejos_refra_oi_alt"]) ? " ALT: ".$eval["lejos_refra_oi_alt"] : '';
			$lejos_oi .= isset($eval["lejos_refra_oi_avcc"]) ? " AVCC: ".$eval["lejos_refra_oi_avcc"] : '';
			$lejos_oi .= isset($eval["lejos_refra_oi_prismas"]) ? " PRISM: ".$eval["lejos_refra_oi_prismas"] : '';
			
			if($cerca_od != " | CERCA OD "){ $str_medida .= $cerca_od;}
			if($cerca_oi != " | CERCA OI "){ $str_medida .= $cerca_oi;}
			if($lejos_od != " | LEJOS OD "){ $str_medida .= $lejos_od;}
			if($lejos_oi != " | LEJOS OI "){ $str_medida .= $lejos_oi;}

		}else{
			$fecha_entrega_trab = date("d/m/Y");
			$str_medida = '-';
		}

		/*if($comp->adelanto=="0.00"){
			$saldo_trab = "0.00";
		}else{
			$saldo_trab = number_format($comp->Total - $comp->adelanto,2, '.', '');
		}*/

		if($comp->deuda_generada == 1){
			$saldo_trab = number_format($comp->Total - $comp->adelanto,2, '.', '');
		}else{
			$saldo_trab = 0.00;
		}
		

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
			if(isset($clie->tipo_doc) && !empty($clie->tipo_doc)){
				if($clie->tipo_doc == 1){
					$tipodoc = "1";
				}else if($clie->tipo_doc == 2){
					$tipodoc = "4";
				}else if($clie->tipo_doc == 3){
					$tipodoc = "7";
				}
			}else{
				$tipodoc = "1";
			}


			$clienom = $comp->ClienteNombre;
		}
		$moneda = ($comp->moneda == "Usd") ? "USD":"PEN";
		$fec = str_replace("/","-",$comp->FechaEmitido);
		$prods = array();
		$totalgratuita = 0;
		$usuario = $this->db->select("Usuario")->where("id", $comp->Usuario_id)->get("usuario")->row()->Usuario;

		$tipo_montura = "";

		$it = 0;
		$total_sin_dscto = 0;
		foreach($det as $deta){
			$it++;//incrementamos iteracion

			$igvfact = $deta->PrecioTotal-($deta->PrecioTotal/1.18);
			$igvitem = $deta->PrecioUnitario-($deta->PrecioUnitario/1.18);
			$udmnube = ($deta->Tipo=="1") ? "NIU":"ZZ";
			$producto = $this->db->select('codigo_prod, codigo_sunat, categoria')->where("id", $deta->Producto_id)->get("producto")->row();

			$tipo_montura = !empty($producto->categoria) ? $producto->categoria: "";


			if(isset($producto->codigo_prod) && !empty($producto->codigo_prod)){
				$codigo_prd = "GT"."-".$producto->codigo_prod."-".$deta->id;
			}else{
				//$codigo_prd = str_shuffle($deta->ProductoNombre);
				srand(time());
				$nro = rand(1, 99999);
				$codigo_prd = "GT".time()."-".$nro+$it."-".$deta->id;
			}

			if($comp->gratuita == 1){
				$prods[] = array(
					"unidad_de_medida"          => $udmnube,
					"codigo_interno"            => $codigo_prd,
					"codigo_producto_sunat"		=> !empty($producto->codigo_sunat) ? $producto->codigo_sunat:"",
					"descripcion"				=> $deta->ProductoNombre,
					"cantidad"					=> $deta->Cantidad,
					"valor_unitario"			=> 0,
					"codigo_tipo_precio" 		=> "02",
					"precio_unitario"			=> $deta->PrecioUnitario,
					"codigo_tipo_afectacion_igv"=> "21",
					"total_base_igv"  			=> number_format($deta->PrecioTotal,2, '.', ''),
					"porcentaje_igv" 			=> 18,
					"total_igv"  				=> 0,
					"total_impuestos"			=> 0,
					"total_valor_item"   		=> number_format($deta->PrecioTotal,2, '.', ''),
					"total_item"  				=> $deta->PrecioTotal,
				);
				$totalgratuita+=number_format($deta->PrecioTotal,2, '.', '');
			}else{
				$prods[] = array(
					"unidad_de_medida"          => $udmnube,
					"codigo_interno"            => $codigo_prd,
					"codigo_producto_sunat"		=> !empty($producto->codigo_sunat) ? $producto->codigo_sunat:"",
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
			
		}

		$subtotalcomp = ($comp->Total/1.18);
		$igvcomptotal = $comp->Total-($comp->Total/1.18);
		
		$tot_ds = number_format(abs($comp->totalDsc),2, '.', '');

		$fecha_cuota = date('Y-m-d', strtotime("+45 day"));

		$data = array(
			"serie_documento" 		=> $serie,
			"numero_documento" 		=> $comp->Correlativo,
			"fecha_de_emision" 		=> date('Y-m-d'),
			"hora_de_emision" 		=> date('h:i:s'),
			"codigo_tipo_operacion" => "0101",
			"codigo_tipo_documento" => $tipocomp,
			"codigo_tipo_moneda" 	=> $moneda,
			"fecha_de_vencimiento" 	=>  date('Y-m-d'),
			"numero_orden_de_compra"=> "",
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
			"informacion_adicional" => $comp->Glosa." | Adelanto: ".number_format($comp->adelanto,2, '.', '')." | Saldo: ".$saldo_trab." | Fecha de Entrega: ".$fecha_entrega_trab." | Tipo: ".$tipo_montura." | Orden Lab.: ".str_pad($comp->id_orden_lab, 6, '0', STR_PAD_LEFT)." | Vendedor: ".$usuario." | Medida: ".$str_medida,
			"acciones" => array("enviar_xml_firmado"=> false, "enviar_email" => true, "formato_pdf" => "ticketguillen")
		);

		if($comp->tipo_pago == "1"){
			$data["codigo_condicion_de_pago"] = "01";
		}else if($comp->tipo_pago == "2"){
			$datos_cuo = json_decode($comp->cuotas, true);
			$data["codigo_condicion_de_pago"] = "02";
			$data["cuotas"] = $datos_cuo;

			$datos_cuo = json_decode($comp->cuotas, true);

			$ultima = end($datos_cuo);
			$data["fecha_de_vencimiento"] = $ultima["fecha"];
		}

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

		if($tot_ds > 0){
			$base_dscto = number_format(($total_sin_dscto / 1.18), 2, '.', '');
			$total_dscto_f = number_format(abs($comp->totalDsc) / 1.18, 2, '.', '');
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

		$data_json = json_encode($data);
		
		$ruta = $cfg->url_api."api/documents";
		$authorization = "Authorization: Bearer ".$cfg->token_sunat."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $ruta);
		curl_setopt(
			$ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization)
		);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
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

	public function getEstados(){
		return $this->db->get("orden_lab_estados")->result();
	}

	public function getEstado ($id){
		return $this->db->select('id_estado_orden')->where("id_orden", $id)->get("orden_lab")->row();
	}

	public function getOrdenesPendientes(){
		$inicio = date('Y-m-d',strtotime("-1 days"));
		$fin = date('Y-m-d',strtotime("+4 days"));
		//return $this->db->where("id_estado_orden", "1")->or_where("id_estado_orden", "2")->order_by("fecha_entrega", "ASC")->get("orden_lab")->result();
		return $this->db->query("SELECT * FROM orden_lab WHERE (id_estado_orden = '1' OR id_estado_orden = '2' OR id_estado_orden = '5') AND DATE_FORMAT(fecha_entrega, '%Y-%m-%d') BETWEEN '".$inicio."' AND '".$fin."' ORDER BY fecha_entrega ASC")->result();
	}


	public function updTienda($id){

		$p = $this->db->where("id", $id)->get("producto")->row();
		//var_dump($p);
		if($p->codigo_prod){
			$woocommerce = new Client(
				'https://tienda-virtual.guillentamayo.com', 
				'ck_59073cf75bd029a4a355be9962a06752ea51fa6a', 
				'cs_ff6be1a1f1efb822d31b253260ffedb6d308e945',
				[
					'wp_api' => true,
					'version' => 'wc/v3',
				]
			);

			$params = [
                'sku' => trim($p->codigo_prod)
            ];

            $validar = $woocommerce->get('products', $params);

			$data = [
				'regular_price' => $p->Precio,
				'stock_quantity'=> $p->Stock
			];

			if(isset($validar[0]->id)){
				$wc_upd = $woocommerce->put('products/'.$validar[0]->id, $data);

				return $wc_upd;
			}else{
				return false;
			}
			
			
		}
		
		return true;
	}

}
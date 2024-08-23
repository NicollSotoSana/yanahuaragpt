<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reportes extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('usuariomodel', 'um');
		$this->load->model('cajamodel', 'cajam');
		$this->load->model('clientemodel', 'clm');
	}
	
	public function reporteDias($inicio, $fin=0){
		$this->load->library('excel');
    	//si no manda fin le colocamos lo mismo que el inicio para que retorne todo lo del dia
    	if($fin==0) $fin = $inicio;
		$datos = $this->db->query("SELECT *, comp.id idecomp FROM comprobante comp INNER JOIN tabladato tbl ON comp.ComprobanteTipo_id = tbl.Value AND tbl.Relacion = 'comprobantetipo' WHERE DATE(comp.FechaEmitido) BETWEEN '".$inicio."' AND '".$fin."' AND (comp.ComprobanteTipo_id='2' OR comp.ComprobanteTipo_id='3')")->result();
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Tipo');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Serie');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Correl.');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'T. Doc');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Nro. Doc.');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Fecha');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Paciente');
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Detalle');
		$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Tipo Pago');
		$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Subtotal');
		$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'IGV');
		$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Total');
		$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Adelanto');
		$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Saldo');
		$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Doc. Afectado');
		$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Estado');

		$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFont()->setBold(true);

		$cont = 2;
    	foreach ($datos as $d) {
    		if($d->deuda_generada==1){
				$deuda = $this->db->where("comprobante_id", $d->idecomp)->get("deudas")->row();
				
				$saldo = number_format($deuda->monto_deuda - $deuda->monto_cancelado, 2);
    		}else{
    			$saldo= "0.00";
			}

			if(strlen($d->ClienteIdentidad) == 11){
				$tdoc = "RUC";
			}else{
				$tdoc = "DNI";
			}
			
			if($d->Estado==2){
    			$estado = "Aprobado";
    		}else{
    			$estado = "Anulado";
			}
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$cont, $d->Nombre);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$cont, $d->Serie);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$cont, $d->Correlativo);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$cont, $tdoc);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$cont, $d->ClienteIdentidad);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$cont, date("d/m/y", strtotime($d->FechaEmitido)));
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$cont, $d->ClienteNombre);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$cont, htmlentities($this->getDetalle($d->idecomp)));
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$cont, $d->mediopago);
			$objPHPExcel->getActiveSheet()->SetCellValue('J'.$cont, $d->SubTotal);
			$objPHPExcel->getActiveSheet()->SetCellValue('K'.$cont, $d->IvaTotal);
			$objPHPExcel->getActiveSheet()->SetCellValue('L'.$cont, $d->Total);
			$objPHPExcel->getActiveSheet()->SetCellValue('M'.$cont, $d->adelanto);
			$objPHPExcel->getActiveSheet()->SetCellValue('N'.$cont, $saldo);
			$objPHPExcel->getActiveSheet()->SetCellValue('O'.$cont, "-");
			$objPHPExcel->getActiveSheet()->SetCellValue('P'.$cont, $estado);
			
			$cont++;
		}

		$notas = $this->db->query("SELECT * FROM notas WHERE fecha_emision BETWEEN '".$inicio."' AND '".$fin."'")->result();

		foreach ($notas as $n) {
			
			if($n->Estado==2){
    			$estadonota = "Aprobado";
    		}else{
    			$estadonota = "Anulado";
			}

			if(strlen($n->ClienteIdentidad) == 8){
				$tdoc = "DNI";
			}else{
				$tdoc = "RUC";
			}

			$cpe = $this->db->select("Serie, Correlativo")->where("external_id", $n->external_id_ref)->get("comprobante")->row();
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$cont, "Nota de Crédito");
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$cont, $n->Serie);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$cont, $n->Correlativo);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$cont, $tdoc);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$cont, $n->ClienteIdentidad);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$cont, date("d/m/y", strtotime($n->FechaEmitido)));
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$cont, $n->ClienteNombre);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$cont, htmlentities($this->getDetalleNota($n->id)));
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$cont, "-");
			$objPHPExcel->getActiveSheet()->SetCellValue('J'.$cont, -1 * abs($n->SubTotal));
			$objPHPExcel->getActiveSheet()->SetCellValue('K'.$cont, -1 * abs($n->IvaTotal));
			$objPHPExcel->getActiveSheet()->SetCellValue('L'.$cont, -1 * abs($n->Total));
			$objPHPExcel->getActiveSheet()->SetCellValue('M'.$cont, "0.00");
			$objPHPExcel->getActiveSheet()->SetCellValue('N'.$cont, "0.00");
			$objPHPExcel->getActiveSheet()->SetCellValue('O'.$cont, $cpe->Serie."-".$cpe->Correlativo);
			$objPHPExcel->getActiveSheet()->SetCellValue('P'.$cont, $estadonota);
			
			$cont++;
		}

		$filename = "reporte-".$inicio."al".$fin.".xls";
		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output');
    }

    public function getDetalle($id){
    	$datos = $this->db->where("Comprobante_Id", $id)->get("comprobantedetalle")->result();
    	$ret = "";
    	foreach($datos as $d){
    		$ret .= $d->Cantidad." - ".$d->ProductoNombre." || ";
    	}
    	return substr($ret, 0, -3);
	}
	
	public function getDetalleNota($id){
    	$datos = $this->db->where("Comprobante_Id", $id)->get("notasdetalle")->result();
    	$ret = "";
    	foreach($datos as $d){
    		$ret .= $d->Cantidad." - ".$d->ProductoNombre." || ";
    	}
    	return substr($ret, 0, -3);
    }

    public function ordenesLab(){
		$usuarios = $this->db->get("usuario")->result();
		$estados_orden = $this->db->get("orden_lab_estados")->result();
		$laboratorios = $this->db->distinct()->select('laboratorio')->get('precios_lunas')->result();
		if(!$_POST){
			$dactual = date("Y-m-d");
			$dfin = date("Y-m-d",  strtotime($dactual. ' -15 days'));

			$this->db->select('o.id_orden, o.id_evaluacion, o.monto_compra_proyectado, o.monto_compra, o.fecha_orden, c.Nombre as nomcli, u.Nombre as nomusu, o.id_estado_orden, (SELECT meta_value FROM orden_lab_meta om WHERE id_orden = o.id_orden AND meta_key=\'material_lente_hide\') as lente, (SELECT meta_value FROM orden_lab_meta om WHERE id_orden = o.id_orden AND meta_key=\'id_material\') as id_material, (SELECT laboratorio FROM precios_lunas WHERE id_precio = id_material) as laboratorio, (SELECT id FROM comprobante where id_orden_lab = o.id_orden and Estado=\'2\' ORDER BY id DESC LIMIT 1) as idcomprobante, (SELECT c.clinica_nombre from anamnesis a inner join clinicas c on c.id_clinica = a.id_clinica where id_anamnesis=o.id_anamnesis) as clinica, (SELECT d.doctor from anamnesis a inner join doctores d on d.id_doctor = a.id_doctor where id_anamnesis=o.id_anamnesis) as doctor, (SELECT e.empresa from anamnesis a inner join empresas_convenios e on e.id_emp_conv = a.id_empresa_conv where id_anamnesis=o.id_anamnesis) as empresa_convenio');
			$this->db->from('orden_lab o');
			$this->db->join('cliente c', 'o.id_cliente = c.id');
			$this->db->join('usuario u', 'u.id = o.id_usuario');
			$this->db->where('DATE(o.fecha_orden) BETWEEN "'.$dfin.'" AND "'.$dactual.'"', '',false);
			$datos = $this->db->get()->result();

		}else{

			$this->db->select('o.id_orden, o.id_evaluacion, o.monto_compra_proyectado, o.monto_compra, o.fecha_orden, c.Nombre as nomcli, u.Nombre as nomusu, o.id_estado_orden, (SELECT meta_value FROM orden_lab_meta om WHERE id_orden = o.id_orden AND meta_key=\'material_lente_hide\') as lente, (SELECT meta_value FROM orden_lab_meta om WHERE id_orden = o.id_orden AND meta_key=\'id_material\') as id_material, (SELECT laboratorio FROM precios_lunas WHERE id_precio = id_material) as laboratorio, (SELECT id FROM comprobante where id_orden_lab = o.id_orden and Estado=\'2\' ORDER BY id DESC LIMIT 1) as idcomprobante, (SELECT c.clinica_nombre from anamnesis a inner join clinicas c on c.id_clinica = a.id_clinica where id_anamnesis=o.id_anamnesis) as clinica, (SELECT d.doctor from anamnesis a inner join doctores d on d.id_doctor = a.id_doctor where id_anamnesis=o.id_anamnesis) as doctor, (SELECT e.empresa from anamnesis a inner join empresas_convenios e on e.id_emp_conv = a.id_empresa_conv where id_anamnesis=o.id_anamnesis) as empresa_convenio');
			$this->db->from('orden_lab o');
			$this->db->join('cliente c', 'o.id_cliente = c.id');
			$this->db->join('usuario u', 'u.id = o.id_usuario');
			//$this->db->join('comprobante comp', 'comp.id_orden_lab = o.id_orden', 'left');

			if(isset($_POST["estado"]) && $_POST["estado"]!="0"){
				$this->db->join('orden_lab_historial oh', 'o.id_orden = oh.id_orden_lab');
				
				$this->db->where('oh.id_estado = "'.$_POST["estado"].'"');
			}
			
			if(isset($_POST["laboratorio"]) && $_POST["laboratorio"]!="0"){
				$this->db->having('laboratorio', $_POST["laboratorio"]);
			}

			if(isset($_POST["fecha_inicio"]) && $_POST["fecha_inicio"]!="0" && isset($_POST["fecha_fin"]) && $_POST["fecha_fin"]!="0" && isset($_POST["estado"]) && $_POST["estado"]=="0"){

				$this->db->where('DATE(o.fecha_orden) >= "'.$_POST["fecha_inicio"].'" AND DATE(o.fecha_orden) <= "'.$_POST["fecha_fin"].'"', '',false);

			}elseif(isset($_POST["fecha_inicio"]) && $_POST["fecha_inicio"]!="0" && isset($_POST["fecha_fin"]) && $_POST["fecha_fin"]!="0" && isset($_POST["estado"]) && $_POST["estado"]!="0"){

				$this->db->where('DATE(o.fecha_orden) >= "'.$_POST["fecha_inicio"].'" AND DATE(o.fecha_orden) <= "'.$_POST["fecha_fin"].'" AND o.id_estado_orden = "'.$_POST["estado"].'"', '',false);
			}

			if(isset($_POST["usuario"]) && $_POST["usuario"]!="0"){
				$this->db->where('o.id_usuario = "'.$_POST["usuario"].'"');
			}

			$datos = $this->db->get()->result();

		}

        $datospost = (isset($_POST)) ? $_POST:null;
		$this->load->view('header');
		//var_dump($this->db->last_query());
        $this->load->view('reportes/verOrdenesLab', array("datos" => $datos, "usuarios" => $usuarios, "datospost" => $datospost, "estados" => $estados_orden, "laboratorios" => $laboratorios));
        $this->load->view('footer');
	}


	
	//Funcion que lista todas las anamnesis y permite filtrar
	public function doctoresReferentes(){

		$datos = array();
		if(!$_POST){
			$ffin = date("Y-m-d");
			$fini = date("Y-m-d", strtotime($Date. '- 30 days'));
			$doctores = $this->db->get("doctores")->result();
			foreach($doctores as $doc){
				$resu = $this->db->query("SELECT a.id_anamnesis, a.id_clinica, IFNULL(ol.monto_compra, 0) as compra_lentes, ol.id_orden as idorden FROM (anamnesis a) JOIN orden_lab ol ON ol.id_anamnesis = a.id_anamnesis WHERE ol.id_estado_orden != '4' AND DATE(a.fecha_anamnesis) BETWEEN ".$fini." AND ".$ffin." AND a.id_doctor = '".$doc->id_doctor."'")->result();
				//var_dump($res);
				
				$total_venta = 0;
				$total_gasto = 0;
				$total_com_1 = 0;
				$total_com_2 = 0;

				foreach($resu as $res){

					$orden = $this->db->where("id_orden", $res->idorden)->where("meta_key", "precio_venta")->get("orden_lab_meta")->row();

					$get_montura = $this->db->where("id_orden", $res->idorden)->where("meta_key", "id_montura")->get("orden_lab_meta")->row();

					$get_comp = $this->db->select("Dsc")->where("id_orden_lab", $res->idorden)->get("comprobante")->row();

					//var_dump($get_montura);
					
					if(isset($get_montura->meta_value)){
						$montura = $this->db->where("id", $get_montura->meta_value)->get("producto")->row();
						$pc_montura = $montura->PrecioCompra;

						if($get_comp->Dsc != "0.00"){
							$pv_montura = $montura->Precio - ($montura->Precio * ($get_comp->Dsc / 100));
						}else{
							$pv_montura = $montura->Precio;
						}
						
					}else{
						$pc_montura = 0;
						$pv_montura = 0;
					}
					
					if($get_comp->Dsc != "0.00"){
						$pv_lente = $orden->meta_value - ($orden->meta_value * ($get_comp->Dsc / 100));
					}else{
						$pv_lente = $orden->meta_value;
					}

					if($resu->id_clinica == 13){
						$comision_ap = $doc->porcentaje2;
						$comision_linea = number_format((($pv_lente + $pv_montura) - ($res->compra_lentes + $pc_montura)) * ($comision_ap/100), 2);
						$total_com_2 += $comision_linea;
					}else{
						$comision_ap = $doc->porcentaje;
						$comision_linea = number_format((($pv_lente + $pv_montura) - ($res->compra_lentes + $pc_montura)) * ($comision_ap/100), 2);
						$total_com_1 += $comision_linea;
					}

					$total_venta += ($pv_lente + $pv_montura);
					$total_gasto += ($res->compra_lentes + $pc_montura);
				}

				$total_utilidad = $total_venta - $total_gasto;

				$datos[$doc->id_doctor] = array(
					"id_doctor"	  		=> $doc->id_doctor,
					"porcentaje_doc"	=> $doc->porcentaje,
					"porcentaje_doc2"	=> $doc->porcentaje2,
					"nombre_doctor" 	=> $doc->doctor,
					"total_utilidad"	=> $total_utilidad,
					"total_gasto"		=> $total_gasto,
					"total_venta"		=> $total_venta,
					"comision_1"		=> $total_com_1,
					"comision_2"		=> $total_com_2,
				);
			}

		}else{
			$doctores = $this->db->get("doctores")->result();
			foreach($doctores as $doc){
				$wh = "";
				if(isset($_POST["fecha_inicio"]) && $_POST["fecha_inicio"]!="0" && isset($_POST["fecha_fin"]) && $_POST["fecha_fin"]!="0"){
					$wh .= 'AND DATE(a.fecha_anamnesis) BETWEEN "'.$_POST["fecha_inicio"].'" AND "'.$_POST["fecha_fin"].'"';
				}


				$resu1 = $this->db->query("SELECT a.id_anamnesis, a.id_clinica, IFNULL(ol.monto_compra, 0) as compra_lentes, ol.id_orden as idorden FROM (anamnesis a) JOIN orden_lab ol ON ol.id_anamnesis = a.id_anamnesis WHERE ol.id_estado_orden != '4' AND a.id_doctor = '".$doc->id_doctor."' ".$wh."");
				
				$resu = $resu1->result();
				//var_dump($res);
				$total_venta = 0;
				$total_gasto = 0;
				$total_com_1 = 0;
				$total_com_2 = 0;
				foreach($resu as $res){

					$orden = $this->db->where("id_orden", $res->idorden)->where("meta_key", "precio_venta")->get("orden_lab_meta")->row();
					$get_montura = $this->db->where("id_orden", $res->idorden)->where("meta_key", "id_montura")->get("orden_lab_meta")->row();
					$get_comp = $this->db->select("Dsc")->where("id_orden_lab", $res->idorden)->get("comprobante")->row();

					//var_dump($get_comp);
					
					if(isset($get_montura->meta_value)){
						$montura = $this->db->where("id", $get_montura->meta_value)->get("producto")->row();
						$pc_montura = $montura->PrecioCompra;

						if($get_comp->Dsc != "0.00"){
							$pv_montura = $montura->Precio - ($montura->Precio * ($get_comp->Dsc / 100));
						}else{
							$pv_montura = $montura->Precio;
						}

					}else{
						$pc_montura = 0;
						$pv_montura = 0;
					}

					if($get_comp->Dsc != "0.00"){
						$pv_lente = $orden->meta_value - ($orden->meta_value * ($get_comp->Dsc / 100));
					}else{
						$pv_lente = $orden->meta_value;
					}

					//var_dump($res->id_clinica);

					if($res->id_clinica == "13"){
						$comision_ap = $doc->porcentaje2;
						$comision_linea = number_format((($pv_lente + $pv_montura) - ($res->compra_lentes + $pc_montura)) * ($comision_ap/100), 2);
						$total_com_2 += $comision_linea;
					}else{
						$comision_ap = $doc->porcentaje;
						$comision_linea = number_format((($pv_lente + $pv_montura) - ($res->compra_lentes + $pc_montura)) * ($comision_ap/100), 2);
						$total_com_1 += $comision_linea;
					}
					
					$total_venta += ($pv_lente + $pv_montura);
					$total_gasto += ($res->compra_lentes + $pc_montura);
				}

				$total_utilidad = $total_venta - $total_gasto;

				$datos[$doc->id_doctor] = array(
					"id_doctor"	  		=> $doc->id_doctor,
					"porcentaje_doc"	=> $doc->porcentaje,
					"porcentaje_doc2"	=> $doc->porcentaje2,
					"nombre_doctor" 	=> $doc->doctor,
					"total_utilidad"	=> $total_utilidad,
					"total_gasto"		=> $total_gasto,
					"total_venta"		=> $total_venta,
					"comision_1"		=> $total_com_1,
					"comision_2"		=> $total_com_2,
				);
			}

		}

		//print_r($this->db->last_query()); 
		$datospost = (isset($_POST)) ? $_POST:null;
		$this->load->view('header');
		$this->load->view('reportes/doctoresReferentes', array("datos" => $datos, "datospost" => $datospost));
		$this->load->view('footer');
	}

	public function detalleDoctoresReferentes($fecha_inicio, $fecha_fin, $id_doctor){
		$retorno = array();

		$datos = $this->db->query("SELECT doc.doctor, a.id_anamnesis, ol.id_evaluacion, IFNULL(ol.monto_compra, ol.monto_compra_proyectado) as compra_lentes, ol.id_orden as idorden, ol.fecha_orden as fechaol, (SELECT Nombre FROM cliente WHERE id = a.id_cliente) nombrecliente, (SELECT meta_value FROM orden_lab_meta WHERE id_orden = ol.id_orden AND meta_key = 'precio_venta') monto_venta, (SELECT meta_value FROM orden_lab_meta WHERE id_orden = ol.id_orden AND meta_key = 'material_lente') nombre_lente
		FROM (anamnesis a) 
		INNER JOIN orden_lab ol ON ol.id_anamnesis = a.id_anamnesis
		INNER JOIN doctores doc ON a.id_doctor = doc.id_doctor 
		WHERE ol.id_estado_orden != '4' 
		AND a.id_doctor = '".$id_doctor."' 
		AND DATE(a.fecha_anamnesis) BETWEEN '".$fecha_inicio."' 
		AND '".$fecha_fin."'")->result();

		foreach($datos as $da){
			$get_montura = $this->db->where("id_orden", $da->idorden)->where("meta_key", "id_montura")->get("orden_lab_meta")->row();
			
			$get_comp = $this->db->select("Dsc")->where("id_orden_lab", $da->idorden)->get("comprobante")->row();

			//var_dump($get_comp);

			if(isset($get_montura->meta_value)){
				$montura = $this->db->where("id", $get_montura->meta_value)->get("producto")->row();
				$montura_nombre = $montura->Nombre;
				$pc_montura = $montura->PrecioCompra;

				//var_dump($montura);

				if($get_comp->Dsc != "0.00"){
					$pv_montura = $montura->Precio - ($montura->Precio * ($get_comp->Dsc / 100));
				}else{
					$pv_montura = $montura->Precio;
				}

			}else{
				$pc_montura = 0;
				$pv_montura = 0;
				$montura_nombre = "Del Paciente";
			}

			if($get_comp->Dsc != "0.00"){
				$pv_lente = $da->monto_venta - ($da->monto_venta * ($get_comp->Dsc / 100));
			}else{
				$pv_lente = $da->monto_venta;
			}

			$retorno[] = array(
				"doctor"	   		=> $da->doctor,
				"id_anamnesis" 		=> $da->id_anamnesis,
				"id_evaluacion" 	=> $da->id_evaluacion,
				"compra_lentes"		=> $da->compra_lentes,
				"idorden"	   		=> $da->idorden,
				"monto_venta"  		=> $pv_lente,
				"nombre_lente" 		=> $da->nombre_lente,
				"pc_montura"   		=> $pc_montura,
				"pv_montura"   		=> $pv_montura,
				"montura_nombre"	=> $montura_nombre,
				"fecha_ol"			=> $da->fechaol,
				"nombrecliente"		=> $da->nombrecliente
			);
		}

		$this->load->view('header');
		$this->load->view('reportes/doctoresReferentesDetallado', array("datos" => json_decode (json_encode ($retorno), FALSE)));
		$this->load->view('footer');
	}

	public function cajaDiaria(){
		if(isset($_POST["fecha_caja"])){
			$fecha 	= $_POST["fecha_caja"];
		}else{
			$fecha 	= date("Y-m-d");
		}
		
		$usuario 	= $this->user->id;

		/*** Obteniendo Egresos ***/

		//Egresos Caja Chica
		$data["egresosCC"] = $this->db->query("SELECT IFNULL(SUM(monto_egreso), 0.00) as total FROM egresos WHERE origen_dinero='1' and fecha='".$fecha."' and Empresa_id='".$this->user->Empresa_id."'")->row_array();
		//Egresos Cuenta Bancaria
		$data["egresosCB"] = $this->db->query("SELECT IFNULL(SUM(monto_egreso), 0.00) as total FROM egresos WHERE origen_dinero='2' and fecha='".$fecha."' and Empresa_id='".$this->user->Empresa_id."'")->row_array();
		//Pagos de Compras Caja Chica
		$data["egresosComCC"] = $this->db->query("SELECT IFNULL(SUM(monto), 0.00) as total FROM depositos WHERE origen_dinero='1' and fecha='".$fecha."' and Empresa_id='".$this->user->Empresa_id."'")->row_array();
		//Pagos de Compras Cuenta Bancaria
		$data["egresosComCB"] = $this->db->query("SELECT IFNULL(SUM(monto), 0.00) as total FROM depositos WHERE origen_dinero='2' and fecha='".$fecha."' and Empresa_id='".$this->user->Empresa_id."'")->row_array();

		/*** FIN Obteniendo Egresos ***/

		/*** Obteniendo Ingresos ***/
		//Ingresos de comprobantes
		
		$data["ingresosVentasEfe"] 	= $this->cajam->getIngresosCompRep("Efectivo", $fecha);
		$data["ingresosVentasVis"] 	= $this->cajam->getIngresosCompRep("Visa", $fecha);
		$data["ingresosVentasMc"] 	= $this->cajam->getIngresosCompRep("MC", $fecha);
		$data["ingresosVentasEst"] 	= $this->cajam->getIngresosCompRep("Estilos", $fecha);
		$data["ingresosVentasDepo"] = $this->cajam->getIngresosCompRep("Deposito", $fecha);
		$data["ingresosVentasYape"] = $this->cajam->getIngresosCompRep("Yape", $fecha);

		//Ingresos de pagos clientes deudas
		$data["ingresosDeudasEfe"] 	= $this->cajam->getIngresosDeudRep("Efectivo", $fecha);
		$data["ingresosDeudasVis"] 	= $this->cajam->getIngresosDeudRep("Visa", $fecha);
		$data["ingresosDeudasMc"] 	= $this->cajam->getIngresosDeudRep("MC", $fecha);
		$data["ingresosDeudasEst"] 	= $this->cajam->getIngresosDeudRep("Estilos", $fecha);
		$data["ingresosDeudasDepo"] = $this->cajam->getIngresosDeudRep("Deposito", $fecha);
		$data["ingresosDeudasYape"] = $this->cajam->getIngresosDeudRep("Yape", $fecha);

		$data["cajaDelDia"] = $this->cajam->getCajaReporte($fecha);
		//var_dump($ingresosDeudas);
		$data["totalIngresos"] = $data["ingresosVentasEfe"]["total"]+$data["ingresosVentasVis"]["total"]+$data["ingresosVentasMc"]["total"]+$data["ingresosVentasEst"]["total"]+$data["ingresosVentasDepo"]["total"]+$data["ingresosVentasYape"]["total"]+$data["ingresosDeudasEfe"]["total"]+$data["ingresosDeudasVis"]["total"]+$data["ingresosDeudasMc"]["total"]+$data["ingresosDeudasEst"]["total"]+$data["ingresosDeudasDepo"]["total"]+$data["ingresosDeudasYape"]["total"];
		//echo "<h1>".$fecha."</h1>";
		$comprobantes = $this->db->where("fecha_emision", $fecha)->where("Estado", 2)->get("comprobante")->result();

		//medio_pago es null cuando el pago es por el adelanto principal
		$pagosDeudas = $this->db->query("SELECT dp.monto_pagado, dp.fecha, dp.medio_pago, d.comprobante_id, c.Serie serie, c.Correlativo correlativo, c.ClienteNombre, dp.medio_pago FROM deudas_pagos dp INNER JOIN deudas d ON dp.id_deuda = d.id_deuda INNER JOIN comprobante c ON c.id = d.comprobante_id WHERE dp.medio_pago is not null and dp.fecha='".$fecha."'")->result();

		$this->db->select('*');
		$this->db->from('egresos e');
		$this->db->join('usuario u', 'e.id_usuario = u.id');
		$this->db->where('e.fecha', $fecha);
		$egresos = $this->db->get()->result();

		$this->load->view('header');
		$this->load->view('caja/cajaDiaria', array("datos" => $data, "fecha" => $fecha, "comprobantes" => $comprobantes, "egresos" => $egresos, "pagosDeudas" => $pagosDeudas));
		$this->load->view('footer');
	}

	public function getCajaDiariaExcel($fecha){
		$this->load->library('excel');
		$usuario 	= $this->user->id;
		//Egresos Caja Chica
		$data["egresosCC"] = $this->db->query("SELECT IFNULL(SUM(monto_egreso), 0.00) as total FROM egresos WHERE origen_dinero='1' and fecha='".$fecha."' and Empresa_id='".$this->user->Empresa_id."'")->row_array();
		//Egresos Cuenta Bancaria
		$data["egresosCB"] = $this->db->query("SELECT IFNULL(SUM(monto_egreso), 0.00) as total FROM egresos WHERE origen_dinero='2' and fecha='".$fecha."' and Empresa_id='".$this->user->Empresa_id."'")->row_array();
		//Pagos de Compras Caja Chica
		$data["egresosComCC"] = $this->db->query("SELECT IFNULL(SUM(monto), 0.00) as total FROM depositos WHERE origen_dinero='1' and fecha='".$fecha."' and Empresa_id='".$this->user->Empresa_id."'")->row_array();
		//Pagos de Compras Cuenta Bancaria
		$data["egresosComCB"] = $this->db->query("SELECT IFNULL(SUM(monto), 0.00) as total FROM depositos WHERE origen_dinero='2' and fecha='".$fecha."' and Empresa_id='".$this->user->Empresa_id."'")->row_array();
		/*** FIN Obteniendo Egresos ***/

		/*** Obteniendo Ingresos ***/
		//Ingresos de comprobantes
		
		$data["ingresosVentasEfe"] 	= $this->cajam->getIngresosCompRep("Efectivo", $fecha);
		$data["ingresosVentasVis"] 	= $this->cajam->getIngresosCompRep("Visa", $fecha);
		$data["ingresosVentasMc"] 	= $this->cajam->getIngresosCompRep("MC", $fecha);
		$data["ingresosVentasEst"] 	= $this->cajam->getIngresosCompRep("Estilos", $fecha);
		$data["ingresosVentasDepo"] = $this->cajam->getIngresosCompRep("Deposito", $fecha);
		$data["ingresosVentasYape"] = $this->cajam->getIngresosCompRep("Yape", $fecha);

		//Ingresos de pagos clientes deudas
		$data["ingresosDeudasEfe"] 	= $this->cajam->getIngresosDeudRep("Efectivo", $fecha);
		$data["ingresosDeudasVis"] 	= $this->cajam->getIngresosDeudRep("Visa", $fecha);
		$data["ingresosDeudasMc"] 	= $this->cajam->getIngresosDeudRep("MC", $fecha);
		$data["ingresosDeudasEst"] 	= $this->cajam->getIngresosDeudRep("Estilos", $fecha);
		$data["ingresosDeudasDepo"] = $this->cajam->getIngresosDeudRep("Deposito", $fecha);
		$data["ingresosDeudasYape"] = $this->cajam->getIngresosDeudRep("Yape", $fecha);

		$data["cajaDelDia"] = $this->cajam->cajaTodoFec($fecha)->monto_inicial;
		$data["totalIngresos"] = $data["ingresosVentasEfe"]["total"]+$data["ingresosVentasVis"]["total"]+$data["ingresosVentasMc"]["total"]+$data["ingresosVentasEst"]["total"]+$data["ingresosVentasDepo"]["total"]+$data["ingresosVentasYape"]["total"]+$data["ingresosDeudasEfe"]["total"]+$data["ingresosDeudasVis"]["total"]+$data["ingresosDeudasMc"]["total"]+$data["ingresosDeudasEst"]["total"]+$data["ingresosDeudasDepo"]["total"]+$data["ingresosDeudasYape"]["total"];
		$comprobantes = $this->db->where("fecha_emision", $fecha)->where("Estado", 2)->get("comprobante")->result();

		//medio_pago es null cuando el pago es por el adelanto principal
		$pagosDeudas = $this->db->query("SELECT dp.monto_pagado, dp.fecha, dp.medio_pago, d.comprobante_id, c.Serie serie, c.Correlativo correlativo, c.ClienteNombre, dp.medio_pago FROM deudas_pagos dp INNER JOIN deudas d ON dp.id_deuda = d.id_deuda INNER JOIN comprobante c ON c.id = d.comprobante_id WHERE dp.medio_pago is not null and dp.fecha='".$fecha."'")->result();

		$this->db->select('*');
		$this->db->from('egresos e');
		$this->db->join('usuario u', 'e.id_usuario = u.id');
		$this->db->where('e.fecha', $fecha);
		$egresos = $this->db->get()->result();

		$style = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
			'font' => array("size" => 16, "bold" => true)
		);
		$filename="reporte_caja_".date("d-m-y", strtotime($fecha)).".xls";
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Comprobantes');
		$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
		$objPHPExcel->getActiveSheet()->getStyle("A1:F1")->applyFromArray($style);

		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Comprobante');
        $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Cliente');
        $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Adelanto');
        $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Total');
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Medio Pago');
		$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Deuda');

		$rowCount = 3;
		foreach($comprobantes as $comp){
			$deuda = $comp->deuda_generada == 1 ? "SI":"NO";
			$serie = !empty($comp->Serie) ? $comp->Serie."-" : "";
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $serie.$comp->Correlativo);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $comp->ClienteNombre);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $comp->adelanto);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $comp->Total);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $comp->mediopago);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $deuda);
			$rowCount++;
		}

		$rowCount++;
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, "");
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, "");
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, "");
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "");
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, "");
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, "");

		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'Egresos');
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':C'.$rowCount);
		$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":C".$rowCount)->applyFromArray($style);

		$rowCount++;

		foreach($egresos as $e){
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $e->concepto);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $e->monto_egreso);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $e->Nombre);
			$rowCount++;
		}

		$rowCount++;
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, "");
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, "");
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, "");
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "");
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, "");
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, "");

		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'Pagos Deudas');
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':C'.$rowCount);
		$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":C".$rowCount)->applyFromArray($style);

		$rowCount++;

		foreach($pagosDeudas as $pd){
			if($pd->serie == null){
				$cpede = "NP01-".$pd->correlativo;
			}else{
				$cpede = $pd->serie."-".$pd->correlativo;
			}
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $cpede);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $pd->ClienteNombre);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $pd->monto_pagado);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, ucwords($pd->medio_pago));
			$rowCount++;
		}

		$rowCount++;

		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'Egresos/Gastos');
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':B'.$rowCount);
		$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount)->applyFromArray($style);

		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, "");

		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "Ingresos");
		$objPHPExcel->getActiveSheet()->mergeCells('D'.$rowCount.':I'.$rowCount);
		$objPHPExcel->getActiveSheet()->getStyle("D".$rowCount.":I".$rowCount)->applyFromArray($style);

		$rowCount++;

		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, "Concepto");
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, "Caja Chica");

		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, "");

		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "Ventas");
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $data["ingresosVentasEfe"]["total"]);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $data["ingresosVentasVis"]["total"]);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $data["ingresosVentasMc"]["total"]);
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $data["ingresosVentasEst"]["total"]);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $data["ingresosVentasDepo"]["total"]);
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $data["ingresosVentasYape"]["total"]);

		$rowCount++;

		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $data["egresosCC"]["total"]);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $data["egresosCB"]["total"]);

		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, "");

		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "Deudas Clientes");
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $data["ingresosDeudasEfe"]["total"]);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $data["ingresosDeudasVis"]["total"]);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $data["ingresosDeudasMc"]["total"]);
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $data["ingresosDeudasEst"]["total"]);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $data["ingresosDeudasDepo"]["total"]);
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $data["ingresosDeudasYape"]["total"]);

		$rowCount++;

		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $data["egresosComCC"]["total"]);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $data["egresosComCB"]["total"]);

		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, "");

		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "Totales");
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, number_format(($data["ingresosDeudasEfe"]["total"]+$data["ingresosVentasEfe"]["total"]), 2));
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, number_format(($data["ingresosDeudasVis"]["total"]+$data["ingresosVentasVis"]["total"]), 2));
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, number_format(($data["ingresosDeudasMc"]["total"]+$data["ingresosVentasMc"]["total"]), 2));
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, number_format(($data["ingresosDeudasEst"]["total"]+$data["ingresosVentasEst"]["total"]), 2));
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, number_format(($data["ingresosDeudasDepo"]["total"]+$data["ingresosVentasDepo"]["total"]), 2));
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, number_format(($data["ingresosDeudasYape"]["total"]+$data["ingresosVentasYape"]["total"]), 2));

		$rowCount++;

		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, "Total Egresos");
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, number_format($data["egresosCC"]["total"]+$data["egresosComCC"]["total"],2));


		$rowCount++;

		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "Caja Inicial");
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, number_format($data["cajaDelDia"], 2));

		$rowCount++;

		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "Total Ingresos");
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, number_format($data["totalIngresos"]+$data["cajaDelDia"],2));

		$rowCount++;

		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "Ingresos - Egresos");
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, number_format(($data["totalIngresos"]+$data["cajaDelDia"])-($data["egresosCC"]["total"]+$data["egresosComCC"]["total"]),2));

		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output');

	}

	public function clinicasReferentes(){

		if(!$_POST){

			$this->db->select('count(a.id_anamnesis) as total, c.clinica_nombre');
			$this->db->from('anamnesis a');
			$this->db->join('clinicas c', 'a.id_clinica = c.id_clinica');
			$this->db->group_by("a.id_clinica");
			$datos = $this->db->get()->result();
		}else{
			$this->db->select('count(a.id_anamnesis) as total, c.clinica_nombre');
			$this->db->from('anamnesis a');
			$this->db->join('clinicas c', 'a.id_clinica = c.id_clinica');

			if(isset($_POST["fecha_inicio"]) && $_POST["fecha_inicio"]!="0" && isset($_POST["fecha_fin"]) && $_POST["fecha_fin"]!="0"){
				$this->db->where('DATE(a.fecha_anamnesis) BETWEEN "'.$_POST["fecha_inicio"].'" AND "'.$_POST["fecha_fin"].'"', '',false);
			}
			$this->db->group_by("a.id_clinica");
			$datos = $this->db->get()->result();
		}
		//print_r($this->db->last_query()); 
		$datospost = (isset($_POST)) ? $_POST:null;
		$this->load->view('header');
		$this->load->view('reportes/clinicasReferentes', array("datos" => $datos, "datospost" => $datospost));
		$this->load->view('footer');
	}

	public function conveniosReferentes(){

		if(!$_POST){

			$this->db->select('count(a.id_anamnesis) as total, e.empresa');
			$this->db->from('anamnesis a');
			$this->db->join('empresas_convenios e', 'a.id_empresa_conv = e.id_emp_conv');
			$this->db->group_by("a.id_empresa_conv");
			$datos = $this->db->get()->result();
		}else{
			$this->db->select('count(a.id_anamnesis) as total, e.empresa');
			$this->db->from('anamnesis a');
			$this->db->join('empresas_convenios e', 'a.id_empresa_conv = e.id_emp_conv');

			if(isset($_POST["fecha_inicio"]) && $_POST["fecha_inicio"]!="0" && isset($_POST["fecha_fin"]) && $_POST["fecha_fin"]!="0"){
				$this->db->where('DATE(a.fecha_anamnesis) BETWEEN "'.$_POST["fecha_inicio"].'" AND "'.$_POST["fecha_fin"].'"', '',false);
			}
			$this->db->group_by("a.id_empresa_conv");
			$datos = $this->db->get()->result();
		}
		//print_r($this->db->last_query()); 
		$datospost = (isset($_POST)) ? $_POST:null;
		$this->load->view('header');
		$this->load->view('reportes/conveniosReferentes', array("datos" => $datos, "datospost" => $datospost));
		$this->load->view('footer');
	}

	public function reporteSatisfaccion(){
		$clinicas 			= $this->db->get("clinicas")->result();
		$empresas_convenios = $this->db->get("empresas_convenios")->result();
		$doctores 			= $this->db->get("doctores")->result();

		$this->load->view('header');
		$this->load->view('reportes/reporteSatisfaccion', array("clinicas" => $clinicas, "empresas_convenios" => $empresas_convenios, "doctores" => $doctores));
		$this->load->view('footer');
	}

	public function porcentajeSatisfaccion($tipo, $id, $fecha_ini, $fecha_fin){
		
		if($id != 0){
			$datos = $this->getPorcentajeSatisfaccionUni($tipo, $id, $fecha_ini, $fecha_fin);
			$retorno = $datos["datos"];
			$titulo  = $datos["titulo"];
		}else{
			$retorno = $this->getPorcentajeSatisfaccionTodos($tipo, $fecha_ini, $fecha_fin);

		}
		
		$this->load->view('header');
		if($id != 0){
			$this->load->view('reportes/porcentajeSatisfaccion', array("datos" => $retorno, "titulo" => $titulo));
		}else{
			$this->load->view('reportes/porcentajeSatisfaccionTodos', array("datos" => $retorno));
		}
		
		$this->load->view('footer');
		
	}

	public function getPorcentajeSatisfaccionTodos($tipo, $fecha_ini, $fecha_fin){
		// Tipo 1 = Clinica, 2 = Doctor, 3 = Empresa Convenio
		$datos = array();

		if($tipo == 1){
			$clinicas = $this->db->get("clinicas")->result();

			foreach($clinicas as $cli){
				$total 	  = 0;
				$total_mb = 0;
				$total_b  = 0;
				$total_r  = 0;
				$total_m  = 0;
				$total_mm = 0;
				$titulo   = "";

				$this->db->select('a.id_anamnesis, a.id_cliente, a.id_clinica, e.id_encuesta, e.estado, e.nivel_satisfac');
				$this->db->from('anamnesis a');
				$this->db->join('encuestas e', 'e.id_anamnesis = a.id_anamnesis');
				$this->db->where('DATE(fecha_anamnesis) BETWEEN "'.$fecha_ini.'" AND "'.$fecha_fin.'"', '',false);
				$this->db->where("a.id_clinica", $cli->id_clinica);
				$this->db->where("e.estado", 1);
				$anam = $this->db->get()->result();

				$titulo = $this->db->where("id_clinica", $cli->id_clinica)->get("clinicas")->row()->clinica_nombre;
				
				foreach($anam as $an){
					if($an->nivel_satisfac==1){
							$total++;
							$total_mm++;
					}else if($an->nivel_satisfac==2){
							$total++;
							$total_m++;
					}else if($an->nivel_satisfac==3){
							$total++;
							$total_r++;
					}else if($an->nivel_satisfac==4){
							$total++;
							$total_b++;
					}else if($an->nivel_satisfac==5){
							$total++;
							$total_mb++;
					}
				}

				$porcen_mb = number_format(($total_mb*100)/$total, 2);
				$porcen_b  = number_format(($total_b*100)/$total, 2);
				$porcen_r  = number_format(($total_r*100)/$total, 2);
				$porcen_m  = number_format(($total_m*100)/$total, 2);
				$porcen_mm  = number_format(($total_mm*100)/$total, 2);

				$retorno = array(
					"total"	   => number_format($total, 2),
					"total_mb" => number_format($total_mb, 2),
					"total_b"  => number_format($total_b, 2),
					"total_r"  => number_format($total_r, 2),
					"total_m"  => number_format($total_m, 2),
					"total_mm" => number_format($total_mm, 2),
					"porcen_mb"=> $porcen_mb,
					"porcen_b" => $porcen_b,
					"porcen_r" => $porcen_r,
					"porcen_m" => $porcen_m,
					"porcen_mm"=> $porcen_mm,
					"nombre"   => $titulo
				);

				$datos[] = $retorno;
			}

		}elseif($tipo == 2){
			$doctores = $this->db->get("doctores")->result();
			foreach($doctores as $doc){
				$total 	  = 0;
				$total_mb = 0;
				$total_b  = 0;
				$total_r  = 0;
				$total_m  = 0;
				$total_mm = 0;
				$titulo   = "";

				$this->db->select('a.id_anamnesis, a.id_cliente, a.id_clinica, e.id_encuesta, e.estado, e.nivel_satisfac');
				$this->db->from('anamnesis a');
				$this->db->join('encuestas e', 'e.id_anamnesis = a.id_anamnesis');
				$this->db->where('DATE(fecha_anamnesis) BETWEEN "'.$fecha_ini.'" AND "'.$fecha_fin.'"', '',false);
				$this->db->where("a.id_doctor", $doc->id_doctor);
				$this->db->where("e.estado", 1);
				//$this->db->group_by("a.id_clinica");
				$anam = $this->db->get()->result();

				$titulo = $this->db->where("id_doctor", $doc->id_doctor)->get("doctores")->row()->doctor;
				
				foreach($anam as $an){
					if($an->nivel_satisfac==1){
							$total++;
							$total_mm++;
					}else if($an->nivel_satisfac==2){
							$total++;
							$total_m++;
					}else if($an->nivel_satisfac==3){
							$total++;
							$total_r++;
					}else if($an->nivel_satisfac==4){
							$total++;
							$total_b++;
					}else if($an->nivel_satisfac==5){
							$total++;
							$total_mb++;
					}
				}

				$porcen_mb = number_format(($total_mb*100)/$total, 2);
				$porcen_b  = number_format(($total_b*100)/$total, 2);
				$porcen_r  = number_format(($total_r*100)/$total, 2);
				$porcen_m  = number_format(($total_m*100)/$total, 2);
				$porcen_mm  = number_format(($total_mm*100)/$total, 2);

				$retorno = array(
					"total"	   => number_format($total, 2),
					"total_mb" => number_format($total_mb, 2),
					"total_b"  => number_format($total_b, 2),
					"total_r"  => number_format($total_r, 2),
					"total_m"  => number_format($total_m, 2),
					"total_mm" => number_format($total_mm, 2),
					"porcen_mb"=> $porcen_mb,
					"porcen_b" => $porcen_b,
					"porcen_r" => $porcen_r,
					"porcen_m" => $porcen_m,
					"porcen_mm"=> $porcen_mm,
					"nombre"   => $titulo
				);

				$datos[] = $retorno;
			}
		}elseif($tipo == 3){
			$empresas = $this->db->get("empresas_convenios")->result();
			foreach($empresas as $emp){
				$total 	  = 0;
				$total_mb = 0;
				$total_b  = 0;
				$total_r  = 0;
				$total_m  = 0;
				$total_mm = 0;
				$titulo   = "";

				$this->db->select('a.id_anamnesis, a.id_cliente, a.id_clinica, e.id_encuesta, e.estado, e.nivel_satisfac');
				$this->db->from('anamnesis a');
				$this->db->join('encuestas e', 'e.id_anamnesis = a.id_anamnesis');
				$this->db->where('DATE(fecha_anamnesis) BETWEEN "'.$fecha_ini.'" AND "'.$fecha_fin.'"', '',false);
				$this->db->where("a.id_empresa_conv", $emp->id_emp_conv);
				$this->db->where("e.estado", 1);
				$anam = $this->db->get()->result();

				$titulo = $this->db->where("id_emp_conv", $emp->id_emp_conv)->get("empresas_convenios")->row()->empresa;
				
				foreach($anam as $an){
					if($an->nivel_satisfac==1){
							$total++;
							$total_mm++;
					}else if($an->nivel_satisfac==2){
							$total++;
							$total_m++;
					}else if($an->nivel_satisfac==3){
							$total++;
							$total_r++;
					}else if($an->nivel_satisfac==4){
							$total++;
							$total_b++;
					}else if($an->nivel_satisfac==5){
							$total++;
							$total_mb++;
					}
				}

				$porcen_mb = number_format(($total_mb*100)/$total, 2);
				$porcen_b  = number_format(($total_b*100)/$total, 2);
				$porcen_r  = number_format(($total_r*100)/$total, 2);
				$porcen_m  = number_format(($total_m*100)/$total, 2);
				$porcen_mm  = number_format(($total_mm*100)/$total, 2);

				$retorno = array(
					"total"	   => number_format($total, 2),
					"total_mb" => number_format($total_mb, 2),
					"total_b"  => number_format($total_b, 2),
					"total_r"  => number_format($total_r, 2),
					"total_m"  => number_format($total_m, 2),
					"total_mm" => number_format($total_mm, 2),
					"porcen_mb"=> $porcen_mb,
					"porcen_b" => $porcen_b,
					"porcen_r" => $porcen_r,
					"porcen_m" => $porcen_m,
					"porcen_mm"=> $porcen_mm,
					"nombre"   => $titulo
				);

				$datos[] = $retorno;
			}
		}

		return $datos;
	}

	private function getPorcentajeSatisfaccionUni($tipo, $id, $fecha_ini, $fecha_fin){
		// Tipo 1 = Clinica, 2 = Doctor, 3 = Empresa Convenio

		
		if($tipo == 1){
			$this->db->select('a.id_anamnesis, a.id_cliente, a.id_clinica, e.id_encuesta, e.estado, e.nivel_satisfac');
			$this->db->from('anamnesis a');
			$this->db->join('encuestas e', 'e.id_anamnesis = a.id_anamnesis');
			$this->db->where('DATE(fecha_anamnesis) BETWEEN "'.$fecha_ini.'" AND "'.$fecha_fin.'"', '',false);
			$this->db->where("a.id_clinica", $id);
			$this->db->where("e.estado", 1);
			//$this->db->group_by("a.id_clinica");
			$anam = $this->db->get()->result();

			$titulo = $this->db->where("id_clinica", $id)->get("clinicas")->row()->clinica_nombre;

		}elseif($tipo == 2){
			$this->db->select('a.id_anamnesis, a.id_cliente, a.id_clinica, e.id_encuesta, e.estado, e.nivel_satisfac');
			$this->db->from('anamnesis a');
			$this->db->join('encuestas e', 'e.id_anamnesis = a.id_anamnesis');
			$this->db->where('DATE(fecha_anamnesis) BETWEEN "'.$fecha_ini.'" AND "'.$fecha_fin.'"', '',false);
			$this->db->where("a.id_doctor", $id);
			$this->db->where("e.estado", 1);
			//$this->db->group_by("a.id_clinica");
			$anam = $this->db->get()->result();

			$titulo = $this->db->where("id_doctor", $id)->get("doctores")->row()->doctor;

		}elseif($tipo == 3){
			$this->db->select('a.id_anamnesis, a.id_cliente, a.id_clinica, e.id_encuesta, e.estado, e.nivel_satisfac');
			$this->db->from('anamnesis a');
			$this->db->join('encuestas e', 'e.id_anamnesis = a.id_anamnesis');
			$this->db->where('DATE(fecha_anamnesis) BETWEEN "'.$fecha_ini.'" AND "'.$fecha_fin.'"', '',false);
			$this->db->where("a.id_empresa_conv", $id);
			$this->db->where("e.estado", 1);
			//$this->db->group_by("a.id_clinica");
			$anam = $this->db->get()->result();

			$titulo = $this->db->where("id_emp_conv", $id)->get("empresas_convenios")->row()->empresa;

		}

		$total = 0;
		$total_mb = 0;
		$total_b = 0;
		$total_r = 0;
		$total_m = 0;
		$total_mm = 0;
		foreach($anam as $an){
			switch($an->nivel_satisfac){
				case 1:
					$total++;
					$total_mm++;
					break;
				case 2:
					$total++;
					$total_m++;
					break;
				case 3:
					$total++;
					$total_r++;
					break;
				case 4:
					$total++;
					$total_b++;
					break;
				case 5:
					$total++;
					$total_mb++;
					break;
			}
		}

		$porcen_mb = number_format(($total_mb*100)/$total, 2);
		$porcen_b  = number_format(($total_b*100)/$total, 2);
		$porcen_r  = number_format(($total_r*100)/$total, 2);
		$porcen_m  = number_format(($total_m*100)/$total, 2);
		$porcen_mm  = number_format(($total_mm*100)/$total, 2);

		$retorno = array(
			"total"	   => number_format($total, 2),
			"total_mb" => number_format($total_mb, 2),
			"total_b"  => number_format($total_b, 2),
			"total_r"  => number_format($total_r, 2),
			"total_m"  => number_format($total_m, 2),
			"total_mm" => number_format($total_mm, 2),
			"porcen_mb"=> $porcen_mb,
			"porcen_b" => $porcen_b,
			"porcen_r" => $porcen_r,
			"porcen_m" => $porcen_m,
			"porcen_mm"=> $porcen_mm,
		);
		
		return array("datos" => $retorno, "titulo" => $titulo);
		
	}

	public function cuadroGeneral(){
		
		if(isset($_POST) && !empty($_POST)){
			$datospost = $_POST;
			$fecha_inicio = $_POST["fecha_inicio"];
			$fecha_fin = $_POST["fecha_fin"];
			$data = array();

			$comprobantes = $this->db->where("Estado", 2)->where('DATE(fecha_emision) BETWEEN "'.$fecha_inicio.'" AND "'.$fecha_fin.'"', '',false)->order_by("id", "desc")->get("comprobante")->result();

			

			foreach($comprobantes as $comp){
				$detalle_comp = $this->db->where("Comprobante_Id", $comp->id)->get("comprobantedetalle")->result();

				//Flag para determinar si ya ingreso con orden
				$flag_orden = 0;
				//Creamos arreglo de monturas ya consideradas
				$monturas_consideradas = [];

				foreach($detalle_comp as $dcomp){
					$fecha_orden = "";
					$fecha_cpe = $comp->fecha_emision;
					$cpe = "";
					$nombre = "Sin Cliente";
					$doc = "11111111";
					$telefono = "N/A";
					$correo = "N/A";
					$montura = "Del Cliente";
					$color_montura = "N/A";
					$precio_montura = "0.00";
					$material_lentes = "N/A";
					$disenio_lentes = "N/A";
					$tratamiento_lentes = "N/A";
					$fotocroma_lentes = "N/A";
					$fabricacion_lentes = "N/A";
					$medida = "N/A";
					$cantidad_lentes = 0;
					$ganancia_bruta = 0;
					$precio_lentes = 0;
					$proveedor_lentes = "N/A";
					$fecha_entrega = $comp->fecha_emision;
					$fecha_entregado = $comp->fecha_emision;
					$monto_total_venta = $comp->Total;
					$porcen_dscto = $comp->Dsc;
					$estado_trabajo = "N/A";
					$compra_laboratorio = 0;
					$compra_montura = 0;
					$nombre_clinica = "N/A";
					$nombre_doctor = "N/A";
					$nombre_convenio = "N/A";
					$nombre_vendedor = "N/A";
					$tipo_montura = "N/A";
					$nombre_servicio = "";
					$satisfaccion = "N/A";
					

					//Flag que determina si se añade el item al arreglo
					$flag_add = 0;

					//Serie y Correlativo de comprobante
					$serie = !empty($comp->Serie) ? $comp->Serie."-" : "NP01-";
					$cpe = $serie.$comp->Correlativo;

					if($comp->Cliente_id != 1921){
						$cliente = $this->db->select("Nombre, Telefono1, Correo, Dni, Ruc")->where("id", $comp->Cliente_id)->get("cliente")->row();

						$nombre = $cliente->Nombre;
						$telefono = !empty($cliente->Telefono1) ? $cliente->Telefono1 : "N/A";
						$correo = !empty($cliente->Correo) ? $cliente->Correo : "N/A";
						$doc = !empty($cliente->Ruc) ? $cliente->Ruc:$cliente->Dni;
					}

					//Buscamos encuesta asociada
					if($comp->id_anamnesis != "0" && !empty($comp->id_anamnesis)){
						$encuesta = $this->db->select("nivel_satisfac")->where("id_anamnesis", $comp->id_anamnesis)->where("estado", 1)->get("encuestas")->row();

						if(isset($encuesta->nivel_satisfac)){
							if($encuesta->nivel_satisfac == "1"){
								$satisfaccion = "Muy Mala";
							}else if($encuesta->nivel_satisfac == "2"){
								$satisfaccion = "Mala";
							}else if($encuesta->nivel_satisfac == "3"){
								$satisfaccion = "Regular";
							}else if($encuesta->nivel_satisfac == "4"){
								$satisfaccion = "Buena";
							}else if($encuesta->nivel_satisfac == "5"){
								$satisfaccion = "Muy Buena";
							}
						}
					}

					if($comp->id_orden_lab != 0 && $flag_orden == 0){
						$flag_orden = 1;
						$flag_add = 1;
						//Obtenemos el ID de material seleccionado
						$id_material_lente = $this->db->select("meta_value")->where("id_orden", $comp->id_orden_lab)->where("meta_key", "id_material")->get("orden_lab_meta")->row()->meta_value;

						// Obtenemos orden de laboratorio
						$orden = $this->db->select("id_estado_orden, id_anamnesis, fecha_entrega, fecha_orden, monto_compra, monto_compra_proyectado, id_usuario")->where("id_orden", $comp->id_orden_lab)->get("orden_lab")->row();

						//Fecha de orden
						$fecha_orden = $orden->fecha_orden;

						//Fecha de entrega
						$fecha_entrega = $orden->fecha_entrega;

						//Fecha en que fue entregado
						$b_fecha_entregado = $this->db->select("fecha")->where("id_orden_lab", $comp->id_orden_lab)->where("id_estado", 3)->get("orden_lab_historial")->row();

						$fecha_entregado = isset($b_fecha_entregado->fecha) ? $b_fecha_entregado->fecha : '-';

						//Montura de la tienda o del paciente
						$pv_montura_db = $this->db->select("meta_value")->where("id_orden", $comp->id_orden_lab)->where("meta_key", "id_montura")->get("orden_lab_meta")->row();

						if(isset($pv_montura_db->meta_value)){
							$montura_get = $this->db->select("id, Precio, PrecioCompra, Nombre, categoria")->where("id", $pv_montura_db->meta_value)->get("producto")->row();
							// obtenemos precio de montura
							$precio_montura = isset($montura_get->Precio) ? $montura_get->Precio:"0.00";
							
							//Obtenemos Precio Compra
							$compra_montura = isset($montura_get->PrecioCompra) ? $montura_get->PrecioCompra:"0.00";

							$montura = $montura_get->Nombre;

							$tipo_montura = $montura_get->categoria;

							//Agregamos id de montura a nuestro arreglo
							$monturas_consideradas[] = $montura_get->id;
						}
						
						//Obtenemos material de lente
						$material_lentes = $this->db->select("meta_value")->where("id_orden", $comp->id_orden_lab)->where("meta_key", "material_lente2")->get("orden_lab_meta")->row()->meta_value;

						//Obtenemos diseño de lente
						$disenio_lentes = $this->db->select("meta_value")->where("id_orden", $comp->id_orden_lab)->where("meta_key", "disenio_lente")->get("orden_lab_meta")->row()->meta_value;

						//Obtenemos tratamineto
						$tratamiento_lentes = $this->db->select("meta_value")->where("id_orden", $comp->id_orden_lab)->where("meta_key", "tratamiento_lente")->get("orden_lab_meta")->row()->meta_value;

						//Obtenemos fotocromatico
						$fotocroma_lentes = $this->db->select("meta_value")->where("id_orden", $comp->id_orden_lab)->where("meta_key", "fotocroma_lente")->get("orden_lab_meta")->row()->meta_value;

						//Obtenemos fabricacion de lente
						$fabricacion_lentes = $this->db->select("meta_value")->where("id_orden", $comp->id_orden_lab)->where("meta_key", "fabricacion_lente")->get("orden_lab_meta")->row()->meta_value;

						//Obtenemos Medida
						$medida = $this->getMedidaOrden($comp->id_orden_lab);
						
						//Cantidad lentes
						$cantidad_lentes = 2;

						//Obtenemos precio de lentes
						$precio_lentes = $this->db->select("meta_value")->where("id_orden", $comp->id_orden_lab)->where("meta_key", "precio_venta")->get("orden_lab_meta")->row()->meta_value;

						//Obtenemos proveedor de lentes
						$proveedor_lentes = $this->db->select("laboratorio")->where("id_precio", $id_material_lente)->get("precios_lunas")->row()->laboratorio;

						//Obtenemos Estado
						$estado_trabajo = $this->db->select("estado")->where("id_estado", $orden->id_estado_orden)->get("orden_lab_estados")->row()->estado;

						//Total compra laboratorio
						$compra_laboratorio = (!empty($orden->monto_compra)) ? $orden->monto_compra : $orden->monto_compra_proyectado;

						//Obtenemos factura de los lentes (si fuera el caso)
						$comprobante_compra = $this->db->select("guia_factura")->where("id_orden", $comp->id_orden_lab)->get("compras")->row();

						if(isset($comprobante_compra->guia_factura) && !empty($comprobante_compra->guia_factura)) $factura_compra = $comprobante_compra->guia_factura; else $factura_compra = "N/A";

						if($comp->id_anamnesis != 0){
							//Obtener id de convenio, clinica y doctor
							$anam_datos = $this->db->select("id_empresa_conv, id_clinica, id_doctor")->where("id_anamnesis", $comp->id_anamnesis)->get("anamnesis")->row();

							//Obtener clinica
							$nombre_clinica = ($anam_datos->id_clinica == 0) ? "Ninguna" : $this->db->select("clinica_nombre")->where("id_clinica", $anam_datos->id_clinica)->get("clinicas")->row()->clinica_nombre;

							//Obtener doctor
							$nombre_doctor = ($anam_datos->id_doctor == 0) ? "Ninguno" : $this->db->select("doctor")->where("id_doctor", $anam_datos->id_doctor)->get("doctores")->row()->doctor;

							//Obtener convenio
							$nombre_convenio = ($anam_datos->id_empresa_conv == 0) ? "Ninguno" : $this->db->select("empresa")->where("id_emp_conv", $anam_datos->id_empresa_conv)->get("empresas_convenios")->row()->empresa;

							//Vendedor
							$nombre_vendedor = $this->db->where("id", $comp->Usuario_id)->get("usuario")->row()->Nombre;
							//$nombre_vendedor = $this->db->where("id", $comp->Usuario_id)->get("usuario")->row()->Nombre;
						}

					}
					
					//Excluimos monturas solares y oftalmicas de los productos adicionales

					//Buscamos productos adicionales
					//Aplicamos producto diferente de 0 para no mostrar productos fuera de inventario
					if(($dcomp->Tipo == 1 && $flag_orden==0 && $comp->id_orden_lab == 0 && $dcomp->Producto_id != 0) || ($dcomp->Tipo == 1 && $flag_orden==1 && $comp->id_orden_lab != 0 && $dcomp->Producto_id != 0 && !in_array($dcomp->Producto_id, $monturas_consideradas))){
						$flag_add = 1;

						$montura_get = $this->db->select("Precio, PrecioCompra, Nombre, categoria")->where("id", $dcomp->Producto_id)->get("producto")->row();
						// obtenemos precio de montura
						$precio_montura = isset($montura_get->Precio) ? $montura_get->Precio:"0.00";
						
						//Obtenemos Precio Compra
						$compra_montura = isset($montura_get->PrecioCompra) ? $montura_get->PrecioCompra:"0.00";

						//$montura = $montura_get->Nombre;
						$montura = "[Cant. ".$dcomp->Cantidad."] ".$montura_get->Nombre;

						$tipo_montura = $montura_get->categoria;

						$nombre_vendedor = $this->db->where("id", $comp->Usuario_id)->get("usuario")->row()->Nombre;
					}

					//Buscamos servicios adicionales
					if(($dcomp->Tipo == 2 && $flag_orden==0 && $comp->id_orden_lab == 0) || ($dcomp->Tipo == 2 && $flag_orden==1 && $comp->id_orden_lab != 0)){
						$flag_add = 1;

						$servicio = $this->db->where("id", $dcomp->Producto_id)->get("servicio")->row();

						$nombre_servicio = $servicio->Nombre;
						$montura = "N/A";

						$nombre_vendedor = $this->db->where("id", $comp->Usuario_id)->get("usuario")->row()->Nombre;
					}

					$ganancia_bruta = $monto_total_venta - ($compra_montura + $compra_laboratorio);

					if($flag_add == 1){
						$data[] = array(
							"fecha_orden" => $fecha_orden,
							"fecha_cpe" => $fecha_cpe,
							"cpe" => $cpe,
							"nombre" => $nombre,
							"doc" => $doc,
							"telefono" => $telefono,
							"correo" => $correo,
							"montura" => $montura,
							"precio_montura" => $precio_montura,
							"material_lentes" => $material_lentes,
							"disenio_lentes" => $disenio_lentes,
							"tratamiento_lentes" => $tratamiento_lentes,
							"fabricacion_lentes" => $fabricacion_lentes,
							"fotocroma_lentes" 	=> $fotocroma_lentes,
							"medida" 			=> $medida,
							"cantidad_lentes" 	=> $cantidad_lentes,
							"precio_lentes" 	=> $precio_lentes,
							"proveedor_lentes" 	=> $proveedor_lentes,
							"fecha_entrega" 	=> $fecha_entrega,
							"monto_total_venta" => $monto_total_venta,
							"porcen_dscto" 		=> $porcen_dscto,
							"ganancia_bruta" 	=> number_format($ganancia_bruta, 2),
							"estado_trabajo"	=> $estado_trabajo,
							"compra_laboratorio"=> $compra_laboratorio,
							"compra_montura" 	=> $compra_montura,
							"nombre_clinica" 	=> $nombre_clinica,
							"nombre_doctor" 	=> $nombre_doctor,
							"nombre_convenio" 	=> $nombre_convenio,
							"nombre_vendedor" 	=> $nombre_vendedor,
							"comprobante_compra"=> $factura_compra,
							"fecha_entregado" 	=> $fecha_entregado,
							"tipo_montura"  	=> $tipo_montura,
							"nombre_servicio"	=> $nombre_servicio,
							"satisfaccion"		=> $satisfaccion
						);
					}
				}
			}

		}else{
			$datospost = null;
			$data = null;
		}
		
		$this->load->view('header');
		$this->load->view('reportes/cuadroGeneral', array("datospost" => $datospost, "datos" =>json_decode(json_encode($data), FALSE)));
		$this->load->view('footer');

	}

	private function getMedidaOrden($id){
		$cerca_od = " CERCA OD: ";
		$cerca_oi = " CERCA OI: ";
		$lejos_od = " LEJOS OD: ";
		$lejos_oi = " LEJOS OI: ";
		$str_medida = '';

		$eval = $this->clm->ObtenerOrden($id);

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
		
		if($cerca_od != " CERCA OD: "){ $str_medida .= $cerca_od;}
		if($cerca_oi != " CERCA OI: "){ $str_medida .= $cerca_oi;}
		if($lejos_od != " LEJOS OD: "){ $str_medida .= $lejos_od;}
		if($lejos_oi != " LEJOS OI: "){ $str_medida .= $lejos_oi;}

		return $str_medida;
	}



	/****************************************************************/
	/****************************************************************/
	/*						Prueba de vistas						*/
	/****************************************************************/
	/****************************************************************/


	public function serviciosProductos(){
        // Carga la vista para la sección de Servicios/Productos
        $this->load->view('reportes/servicios_productos');
    }

    public function ssoma(){
        // Carga la vista para la sección de SSOMA
        $this->load->view('reportes/ssoma');
    }

	/****************************************************************/
	/****************************************************************/
	/*						Prueba de vistas						*/
	/****************************************************************/
	/****************************************************************/

}
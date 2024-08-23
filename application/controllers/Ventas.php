<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ventas extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('clientemodel', 'clm');
		$this->load->model('monedamodel', 'mm');
		$this->load->model('productomodel', 'pm');
		$this->load->model('usuariomodel', 'um');
		$this->load->model('comprobantemodel', 'cpm');
		$this->load->model('reportemodel', 'rm');
		$this->load->model('cajamodel', 'cajam');
	}
	public function comprobantes()
	{
		// Verificamos si tiene permiso
		if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
				
		$this->load->view('header');
		$this->load->view('ventas/comprobantes', array(
			'tipos'        => $this->cpm->Tipos(),
			'estados'      => $this->cpm->Estados(),
			'pendiente'    => $this->cpm->ImpresionPendiente()    
		));
		$this->load->view('footer');
	}
	public function comprobante($id = 0)
	{
		if($id==0 && $this->cajam->checkCaja()==0){
			redirect('caja/abrirCaja');
		}
		$c = $id != '' ? $this->cpm->Obtener($id) : null;
		$client = $id != '' ? $this->clm->Obtener($c->Cliente_id) : null;
		$disenio = $this->db->distinct()->select("disenio")->get("precios_lunas")->result();
		$deuda = $this->db->where("comprobante_id", $id)->get("deudas")->row();
		$anam = $id != '' && $c->id_anamnesis != 0 ? $this->db->where("id_anamnesis", $c->id_anamnesis)->get("anamnesis")->row():null;
		//var_dump($c->id_orden_lab);
		$conformidad = $id != '' ? $this->db->where('id_orden_lab', $c->id_orden_lab)->get("conformidad_monturas")->row() : null;
		
		$orden_lab = $id != '' && $c->id_orden_lab != 0 ? $this->db->where('id_orden', $c->id_orden_lab)->get("orden_lab")->row() : null;

		$this->load->view('header');
		$this->load->view('ventas/comprobante', array(
			'comprobante'  => $c,
			'cliente'	   => $client,
			'tipos'        => $this->cpm->Tipos(),
			'estados'      => $this->cpm->Estados(),
			'disenio'	   => $disenio,
			'anamnesis'	   => $anam,
			'deuda'	   	   => $deuda,
			'conformidad_montura' => $conformidad,
			'orden_lab'	   => $orden_lab
		));
		$this->load->view('footer');
	}
	public function consolidar($tipo_doc, $id = 0)
	{
		$c = $id != '' ? $this->cpm->Obtener_consolid($id,$tipo_doc) : null;
		$client = $id != '' ? $this->clm->Obtener($c->Cliente_id) : null;
		$disenio = $this->db->distinct()->select("disenio")->get("precios_lunas")->result();
		$this->load->view('header');
		$this->load->view('ventas/consolidar', array(
			'comprobante'  => $c,
			'cliente'	   => $client,
			'tipos'        => $this->cpm->Tipos(),
			'estados'      => $this->cpm->Estados(),
			'disenio'	   => $disenio,
		));
		$this->load->view('footer');
	}

	public function comprobanteOrdenLab($orden_lab, $id_anamnesis, $id = 0)
	{
		if($id==0 && $this->cajam->checkCaja()==0){
			redirect('caja/abrirCaja');
		}
		$ord_lab = $orden_lab != '' ? $this->clm->detalleOrden($orden_lab) : null;
		
		$eval = $this->clm->ObtenerEval($ord_lab[0]->id_evaluacion);
		/*echo "<pre>";
		var_dump($eval);
		echo "</pre>";*/
		$client  = $this->clm->Obtener($ord_lab[0]->id_cliente);
		$ord_lab_met  = $this->clm->ObtenerOrden($orden_lab);
		$prod  = $this->pm->Obtener($ord_lab_met["id_montura"]);
		$disenio = $this->db->distinct()->select("disenio")->get("precios_lunas")->result();
		$this->load->view('header');
		$this->load->view('ventas/consolidarOrd', array(
			'cliente'	   	=> $client,
			'tipos'        	=> $this->cpm->Tipos(),
			'estados'      	=> $this->cpm->Estados(),
			'orden_lab'	   	=> $ord_lab,
			'ord_lab_met'	=> $ord_lab_met,
			'prod'			=> $prod,
			'id_anamnesis'	=> $id_anamnesis,
			'disenio'	   	=> $disenio,
		));
		$this->load->view('footer');
	}

	public function comprobantecrud()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode(isset($_POST['id']) ? $this->cpm->Actualizar(SafeRequestParameters($_POST)) : $this->cpm->Registrar(SafeRequestParameters($_POST))));
	}
	public function proforma($id)
	{

		$c = $this->cpm->Obtener($id);
		$client = $id != '' ? $this->clm->Obtener($c->Cliente_id) : null;
		$config = $this->db->where("Empresa_id", $this->user->Empresa_id)->get("configuracion")->row();

		$fecha_entrega = $c->id_orden_lab != 0 ? $this->db->select("fecha_entrega")->where("id_orden", $c->id_orden_lab)->get("orden_lab")->row()->fecha_entrega:date('Y-m-d');

		$titu = ($c->ComprobanteTipo_id==4) ? "Nota de Pedido":"Cotización";
		$vendedor = $this->db->select("Usuario")->where("id", $c->Usuario_id)->get("usuario")->row()->Usuario;

		$this->db->where('Comprobante_Id', $id);
		$det = $this->db->get('comprobantedetalle')->result();
		$tipo_montura = "";
		foreach($det as $deta){
			$producto = $this->db->select('categoria')->where("id", $deta->Producto_id)->get("producto")->row();
			$tipo_montura = !empty($producto->categoria) ? $producto->categoria: "";
		}

		$cerca_od = " | CERCA OD ";
		$cerca_oi = " | CERCA OI ";

		$lejos_od = " | LEJOS OD ";
		$lejos_oi = " | LEJOS OI ";
		$str_medida = '';
		if($c->id_orden_lab != 0){

			$eval = $this->clm->ObtenerOrden($c->id_orden_lab);

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
			$str_medida = '-';
		}

		//array_debug($client);
		$this->load->library('mpdflib');
		//$html2pdf = new Html2Pdf('P',['80', '500'],'en',true,"UTF-8",array(2, 5, 0, 5));
		$html2pdf = new Mpdf("en",[78,500],"","",5,5,2,2,2,2);

		$htmlsalida=$this->load->view("ventas/notapedido", array("tipo_doc" => $titu, "comp" => $c, "cliente" => $client, "config" => $config, "fecha_entrega" => $fecha_entrega, "vendedor" => $vendedor, "medida" => $str_medida, "tipo_montura" => $tipo_montura),TRUE);

		$html2pdf->writeHTML($htmlsalida);
		$html2pdf->output($titu.'-'.$id.'.pdf', 'I');
	}

	public function ticketOrdenLab($id_orden){
		$idord = str_pad($id_orden, 6, '0', STR_PAD_LEFT);
    	$ord = $id_orden > 0 ? $this->db->where("id_orden", $id_orden)->get("orden_lab")->row() : null;
		$eval = $id_orden > 0 ? $this->clm->ObtenerOrden($id_orden) : null;
		$vendedor = $this->db->where("id", $ord->id_usuario)->get("usuario")->row()->Nombre;
		$anam = $this->db->select("id_clinica, id_doctor")->where("id_anamnesis", $ord->id_anamnesis)->get("anamnesis")->row();

		$doctor = $this->db->where("id_doctor", $anam->id_doctor)->get("doctores")->row();
		$clinicas = $this->db->where("id_clinica", $anam->id_clinica)->get("clinicas")->row();
    	//var_dump($eval);
    	$evdat = $this->clm->ObtenerCliOrdenLab($ord->id_evaluacion);
		$cli = $this->clm->Obtener($evdat["id_cliente"]);
		$cfg = $this->db->where("Empresa_id", $this->user->Empresa_id)->get("configuracion")->row();
		$cerca_od="";
		$cerca_oi="";

		$lejos_od="";
		$lejos_oi="";

		$datosTemplate = array();

		//STRING CERCA

		$cerca_od .= isset($eval["cerca_refra_od_esf"]) ? "ESF: ".$eval["cerca_refra_od_esf"] : '';
		$cerca_od .= isset($eval["cerca_refra_od_cyl"]) ? " CIL: ".$eval["cerca_refra_od_cyl"] : '';
		$cerca_od .= isset($eval["cerca_refra_od_eje"]) ? " EJE: ".$eval["cerca_refra_od_eje"] : '';
		$cerca_od .= isset($eval["cerca_refra_od_adicion"]) ? " ADIC: ".$eval["cerca_refra_od_adicion"] : '';
		$cerca_od .= isset($eval["cerca_refra_od_dnp"]) ? " DIP: ".$eval["cerca_refra_od_dnp"] : '';
		$cerca_od .= isset($eval["cerca_refra_od_alt"]) ? " ALT: ".$eval["cerca_refra_od_alt"] : '';
		$cerca_od .= isset($eval["cerca_refra_od_avcc"]) ? " AVCC: ".$eval["cerca_refra_od_avcc"] : '';
		$cerca_od .= isset($eval["cerca_refra_od_prismas"]) ? " PRISM: ".$eval["cerca_refra_od_prismas"] : '';

		$cerca_oi .= isset($eval["cerca_refra_oi_esf"]) ? "ESF: ".$eval["cerca_refra_oi_esf"] : '';
		$cerca_oi .= isset($eval["cerca_refra_oi_cyl"]) ? " CIL: ".$eval["cerca_refra_oi_cyl"] : '';
		$cerca_oi .= isset($eval["cerca_refra_oi_eje"]) ? " EJE: ".$eval["cerca_refra_oi_eje"] : '';
		$cerca_oi .= isset($eval["cerca_refra_oi_adicion"]) ? " ADIC: ".$eval["cerca_refra_oi_adicion"] : '';
		$cerca_oi .= isset($eval["cerca_refra_oi_dnp"]) ? " DIP: ".$eval["cerca_refra_oi_dnp"] : '';
		$cerca_oi .= isset($eval["cerca_refra_oi_alt"]) ? " ALT: ".$eval["cerca_refra_oi_alt"] : '';
		$cerca_oi .= isset($eval["cerca_refra_oi_avcc"]) ? " AVCC: ".$eval["cerca_refra_oi_avcc"] : '';
		$cerca_oi .= isset($eval["cerca_refra_oi_prismas"]) ? " PRISM: ".$eval["cerca_refra_oi_prismas"] : '';

		//STRING LEJOS

		$lejos_od .= isset($eval["lejos_refra_od_esf"]) ? "ESF: ".$eval["lejos_refra_od_esf"] : '';
		$lejos_od .= isset($eval["lejos_refra_od_cyl"]) ? " CIL: ".$eval["lejos_refra_od_cyl"] : '';
		$lejos_od .= isset($eval["lejos_refra_od_eje"]) ? " EJE: ".$eval["lejos_refra_od_eje"] : '';
		$lejos_od .= isset($eval["lejos_refra_od_adicion"]) ? " ADIC: ".$eval["lejos_refra_od_adicion"] : '';
		$lejos_od .= isset($eval["lejos_refra_od_dnp"]) ? " DIP: ".$eval["lejos_refra_od_dnp"] : '';
		$lejos_od .= isset($eval["lejos_refra_od_alt"]) ? " ALT: ".$eval["lejos_refra_od_alt"] : '';
		$lejos_od .= isset($eval["lejos_refra_od_avcc"]) ? " AVCC: ".$eval["lejos_refra_od_avcc"] : '';
		$lejos_od .= isset($eval["lejos_refra_od_prismas"]) ? " PRISM: ".$eval["lejos_refra_od_prismas"] : '';

		$lejos_oi .= isset($eval["lejos_refra_oi_esf"]) ? "ESF: ".$eval["lejos_refra_oi_esf"] : '';
		$lejos_oi .= isset($eval["lejos_refra_oi_cyl"]) ? " CIL: ".$eval["lejos_refra_oi_cyl"] : '';
		$lejos_oi .= isset($eval["lejos_refra_oi_eje"]) ? " EJE: ".$eval["lejos_refra_oi_eje"] : '';
		$lejos_oi .= isset($eval["lejos_refra_oi_adicion"]) ? " ADIC: ".$eval["lejos_refra_oi_adicion"] : '';
		$lejos_oi .= isset($eval["lejos_refra_oi_dnp"]) ? " DIP: ".$eval["lejos_refra_oi_dnp"] : '';
		$lejos_oi .= isset($eval["lejos_refra_oi_alt"]) ? " ALT: ".$eval["lejos_refra_oi_alt"] : '';
		$lejos_oi .= isset($eval["lejos_refra_oi_avcc"]) ? " AVCC: ".$eval["lejos_refra_oi_avcc"] : '';
		$lejos_oi .= isset($eval["lejos_refra_oi_prismas"]) ? " PRISM: ".$eval["lejos_refra_oi_prismas"] : '';
		
		$datosTemplate["cerca_od"]	  	= $cerca_od;
		$datosTemplate["cerca_oi"]	  	= $cerca_oi;
		$datosTemplate["lejos_od"] 	  	= $lejos_od;
		$datosTemplate["lejos_oi"] 	  	= $lejos_oi;
		$datosTemplate["observaciones"]	= $ord->observaciones;
		$datosTemplate["id_orden"] 	  	= $id_orden;
		$datosTemplate["fecha_orden"] 	= $ord->fecha_orden;
		$datosTemplate["fecha_entrega"] = $ord->fecha_entrega;
		$datosTemplate["cliente"]	  	= $cli->Nombre;
		$datosTemplate["vendedor"]	  	= $vendedor;
		$datosTemplate["material_lente_hide"]	  = isset($eval["material_lente_hide"])?$eval["material_lente_hide"]:"";
		$datosTemplate["montura"]	  	= isset($eval["montura"])?$eval["montura"]:$eval["montura_paciente"];
		$datosTemplate["tipo_montura"]	= isset($eval["tipo_montura"])?$eval["tipo_montura"]:"-";
		$datosTemplate["montaje"]	  	= isset($eval["montaje"])?$eval["montaje"]:"-";
		$datosTemplate["angulo_panoramico"]	  = isset($eval["angulo_panoramico"])?$eval["angulo_panoramico"]:"-";
		$datosTemplate["angulo_pantoscopico"] = isset($eval["angulo_pantoscopico"])?$eval["angulo_pantoscopico"]:"-";
		$datosTemplate["distancia_vertice"]   = isset($eval["distancia_vertice"])?$eval["distancia_vertice"]:"-";

		$datosTemplate["doctor"]	  	= $doctor->doctor;
		$datosTemplate["clinica"]	  	= $clinicas->clinica_nombre;
		
		$conf = $this->db->get("configuracion")->row();

		$datosTemplate["direccion"]	  	= $conf->Direccion;
		
		$this->load->library('mpdflib');

		$html2pdf = new Mpdf("en",[78,297],"","",5,5,2,2,2,2);

		$htmlsalida=$this->load->view("mantenimiento/pdf_ordenLaboratorio", array("datos" => $datosTemplate),TRUE);

		$html2pdf->writeHTML($htmlsalida);
		$html2pdf->output('Orden_Lab_'.$id.'.pdf', 'I');
    }

	public function impresion($id,$idcli)
	{
		$this->load->library('EnLetras', 'el');		
		$this->load->view('ventas/impresion', array(
			'comprobante' => $this->cpm->Obtener($id),
			'EnLetras'    => new EnLetras(),
			'clienteImp'  => $this->clm->Obtener($idcli)
		));
	}
	public function reportes()
	{
		// Verificamos si tiene permiso
		if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		
		$this->load->view('header');
		$this->load->view('ventas/reportes');
		$this->load->view('footer');
	}
	public function enviarSunat($id){
		echo $this->cpm->enviarSunat($id);
	}
	
	public function ajax($action)
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		// Productos
		switch($action)
		{
			case 'CargarComprobantes':
				print_r(json_encode($this->cpm->Listar()));
				break;
			case 'DisponibleParaImprimir':
				print_r(json_encode($this->cpm->DisponibleParaImpresion($this->input->post('id'))));
				break;
			case 'Imprimir':
				print_r(json_encode($this->cpm->Imprimir($this->input->post('id'), $this->input->post('f'))));
				break;
			case 'CancelarImpresion':
				print_r(json_encode($this->cpm->CancelarImpresion($this->input->post('id'))));
				break;
			case 'CorregirCorrelativo':
				print_r(json_encode($this->cpm->CorregirCorrelativo($_POST)));
				break;
			case 'Devolver':
				print_r(json_encode($this->cpm->Devolver($_POST)));
				break;
			case 'Entregar':
				print_r(json_encode($this->cpm->Entregar($_POST)));
				break;
			case 'CargarDetalleParaDevolver':
				$comprobante_id = $this->input->post('comprobante_id');
				echo $this->load->view('ventas/_devolucion', 
					array(
						'comprobante_id' => $comprobante_id,
						'detalle'        => $this->cpm->ObtenerProductosParaDevolucion($comprobante_id)
					), true);
				break;
				break;
			case 'CargarDetalleParaEntregar':
				$comprobante_id = $this->input->post('comprobante_id');
				echo $this->load->view('ventas/_entregarPedido', 
					array(
						'comprobante_id' => $comprobante_id,
						'detalle'        => $this->cpm->ObtenerProductosParaDevolucion($comprobante_id)
					), true);
				break;
				break;
			case 'CorrelativoIncorrecto':
				echo $this->load->view('ventas/_CorrelativoIncorrecto', 
					array(
						'correlativo' => $this->input->post('correlativo'),
						'id'          => $this->input->post('id'),
						'tipo'        => $this->input->post('tipo'),
					), true);
				break;
			case 'SubReporte':
				/* SubReporte para el Reporte de Venta Diario */
				if($this->input->post('tipo') == 'reportediariodetalle')
				{
					$reporte = $this->rm->ReporteDiarioDetalle($this->input->post('fecha'));
					
					echo $this->load->view('ventas/subreportes/reportediariodetalle', array(
						'reporte' => $reporte
					), true);
				}
				break;
			case 'Reporte':
				$reporte = null;
				$titulo  = '';
				
				/* Reporte de Venta Diario */
				if($this->input->post('tipo') == '1')
				{
					$reporte = $this->rm->ReporteDiario($this->input->post('m'), $this->input->post('y'));
					$titulo = 'Reporte Diario';
				}
				/* Reporte de Venta Mensual */
				if($this->input->post('tipo') == '2')
				{
					$reporte = $this->rm->ReporteMensual($this->input->post('y'));
					$titulo = 'Reporte Mensual';
				}
				
				/* Reporte de Venta Anual */
				if($this->input->post('tipo') == '3')
				{
 					$reporte = $this->rm->ReporteAnual();
 					$titulo = 'Reporte Anual';
				}
				
				/* Productos mas vendidos */
				if($this->input->post('tipo') == '4')
				{
 					$reporte = $this->rm->ProductosMasVendidos($this->input->post('m'), $this->input->post('y'));
 					$titulo = 'Top de Productos';
				}
				
				/* Mejores Clientes */
				if($this->input->post('tipo') == '5')
				{
 					$reporte = $this->rm->MejoresClientes($this->input->post('m'), $this->input->post('y'));
 					$titulo = 'Top de Clientes';
				}
				
				/* Analisis de Venta por Estacion */
				if($this->input->post('tipo') == '6')
				{
 					$reporte = $this->rm->ProductosRentablesPorTrimestre($this->input->post('y'));
 					$titulo = 'Rentabilidad de Producto Trimestral';
				}
				
				/* Mejores Empleados */
				if($this->input->post('tipo') == '7')
				{
 					$reporte = $this->rm->MejoresEmpleados($this->input->post('m'), $this->input->post('y'));
 					$titulo = 'Top de Empleados';
				}

				/* Reporte de Caja Diario */
				if($this->input->post('tipo') == '8')
				{
					$reporte = $this->rm->ReporteDiarioCaja($this->input->post('m'), $this->input->post('y'));
					$titulo = 'Reporte Caja Diario';
				}
				
				echo $this->load->view('ventas/_reporte', array(
					'reporte' => $reporte,
					'tipo'    => $this->input->post('tipo'),
					'm'       => $this->input->post('m'),
					'y'       => $this->input->post('y'),
					'titulo'  => $titulo
				), true);
				break;
		}
	}

}
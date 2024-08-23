<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Automattic\WooCommerce\Client;

class Mantenimiento extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('clientemodel', 'clm');
		$this->load->model('monedamodel', 'mm');
		$this->load->model('productomodel', 'pm');
		$this->load->model('serviciomodel', 'sm');
		$this->load->model('usuariomodel', 'um');
		$this->load->model('comprobantemodel', 'cpm');
		$this->load->model('configuracionmodel', 'cfm');
		$this->load->model('proveedormodel', 'pvm');
		$this->load->model('cuentacorrientemodel', 'ccm');
	}

	// PROVEEDORES
	public function proveedores()
	{
		// Verificamos si tiene permiso
		// Verificamos si tiene permiso
		if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		
		$this->load->view('header');
		$this->load->view('mantenimiento/proveedores');
		$this->load->view('footer');		
	}

	public function proveedor($id=0)
	{
		$c = $id > 0 ? $this->pvm->Obtener($id) : null;
		
		$this->load->view('header');
		$this->load->view('mantenimiento/proveedor',
					array(
						'proveedor' => $c,
						'deudas'	=> $this->pvm->getDeudasProveedor($id)
					));
		$this->load->view('footer');		
	}

	public function proveedorcrud()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode(isset($_POST['id']) ? $this->pvm->Actualizar(SafeRequestParameters($_POST)) : $this->pvm->Registrar(SafeRequestParameters($_POST))));		
	}
	public function proveedorliminar($id)
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->pvm->Eliminar($id)));		
	}
	//FIN PROVEEDORES
	public function usuarios()
	{
		// Verificamos si tiene permiso
		if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		
		$this->load->view('header');
		$this->load->view('mantenimiento/usuarios');
		$this->load->view('footer');		
	}
	public function usuario($id=0)
	{
		$u = $id > 0 ? $this->um->Obtener($id) : null;
		
		$this->load->view('header');
		$this->load->view('mantenimiento/usuario',
					array(
						'usuario' => $u,
						'tipos'   => $this->um->Tipos()
					));
		$this->load->view('footer');		
	}
	public function usuariocrud()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		
        if(IS_DEMO == 1)
        {
            print_r(json_encode(array('response' => false, 'message' => 'La <b>versión DEMO</b> no permite guardar los datos de los Usuarios.')));            
        } else {
            print_r(json_encode( isset($_POST['id']) ? $this->um->Actualizar(SafeRequestParameters($_POST)) : $this->um->Registrar(SafeRequestParameters($_POST))) );            
        }
	}
	public function usuarioeliminar($id)
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		
		print_r(json_encode($this->um->Eliminar($id)));
	}
	public function clientes()
	{
		// Verificamos si tiene permiso
		if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		
		$this->load->view('header');
		$this->load->view('mantenimiento/clientes');
		$this->load->view('footer');		
	}
	public function cliente($id=0)
	{
		$c = $id > 0 ? $this->clm->Obtener($id) : null;
		$eva = $id > 0 ? $this->clm->ObtenerEva($id) : null;
		$comps = $id > 0 ? $this->clm->getComprobantesClie($id) : null;
		$deudas  = $id > 0 ? $this->ccm->getDeudas($id) : null;

		$profesiones = $this->db->get("profesiones")->result();
		$rubros_trabajo = $this->db->get("rubros_trabajo")->result();
		$ciudades = $this->db->get("ciudades")->result();
		$distritos = $this->db->get("distritos")->result();

		$this->load->view('header');
		$this->load->view('mantenimiento/cliente',
			array(
				'cliente' => $c,
				'evaluaciones' => $eva,
				'comprobantes' => $comps,
				'deudas'  => $deudas,
				"profesiones" => $profesiones, 
				"rubros_trabajo" => $rubros_trabajo, 
				"ciudades" => $ciudades, 
				"distritos" => $distritos
			)
		);
		$this->load->view('footer');		
	}

	public function excelDeudas($id){
		$cli = $this->clm->Obtener($id);
		$this->db->select('*');    
		$this->db->from('deudas');
		$this->db->join('comprobante', 'deudas.comprobante_id = comprobante.id');
		$this->db->where('deudas.id_cliente', $id);
		$query = $this->db->get()->result();
		$total = 0;
		$saldo = 0;
		$tab = '<table border="1">
		<tr>
			<td style="width:150px;"><img src="http://localhost/guillen/assets/nuevotemp/img/logo3.png" width="150" style="width:150px;"></td>
			<td colspan="3" style="text-align:center;"><h2>CENTRO OPTICO GUILLEN TAMAYO S.R.L.</h2>
			<p>MZA. F LOTE. 1 URB. LOS CEDROS","direccion_completa":"MZA. F LOTE. 1 URB. LOS CEDROS AREQUIPA - AREQUIPA - YANAHUARA<br/><b>E-mail:</b> info@guillentamayo.com</p>
			</td>
		</tr>
		<tr>
			<td><strong>Cliente:</strong></td>
			<td colspan="3">'.$cli->Nombre.'</td>
		</tr>
		<tr style="text-align:center;">
			<td><strong>Comprobante</strong></td>
			<td><strong>Detalle</strong></td>
			<td><strong>Total</strong></td>
			<td><strong>Saldo</strong></td>
		</tr>';

		foreach($query as $q){
			$tab .= '<tr><td>'.$q->Serie.'-'.$q->Correlativo.'</td><td>';
			$deta = $this->db->where('Comprobante_Id', $q->comprobante_id)->get('comprobantedetalle')->result();
			foreach($deta as $de){
				$tab .= $de->ProductoNombre;
			}
			$tab .= '</td><td>'.$q->monto_deuda.'</td><td>'.number_format(($q->monto_deuda-$q->monto_cancelado), 2).'</td></tr>';
			$total += $q->monto_deuda;
			$saldo += ($q->monto_deuda-$q->monto_cancelado);
		}
		$tab .= '<tr><td colspan="2" style="text-align:right;"><span style="float:right; font-weight:bold;">Total Deudas: </span></td><td>'.number_format($total, 2).'</td><td></td></tr>';
		$tab .= '<tr><td colspan="2" style="text-align:right;"><span style="float:right; font-weight:bold;">Total Saldo Pendiente: </span></td><td></td><td>'.number_format($saldo, 2).'</td></tr>';
		$tab .= '</table>';
		header("Content-type: application/vnd.ms-excel; name='excel'; charset=utf-8");
		header("Content-Disposition: filename=cuenta-".$cli->Nombre.".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo utf8_decode($tab);
	}

	public function evaluacion($idcli=0,$ideval=0){
		$c = $idcli > 0 ? $this->clm->Obtener($idcli) : null;
		$eval = $ideval > 0 ? $this->clm->ObtenerEval($ideval) : null;
		$this->load->view('header');
		$this->load->view('mantenimiento/evaluacion', array(
						'cliente' => $c,
						'idclie'  => $idcli,
						'eval'	  => $eval,
						'ideval'  => $ideval
					));
		$this->load->view('footer');	
	}

	public function ordenLaboratorio($ideval=0,$idord=0){
		if($idord==0){
			$eval = $ideval > 0 ? $this->clm->ObtenerEval($ideval) : null;
			$estado_act = 0;
			$fecha_emision = $this->db->select("fecha")->where("id_evaluacion", $ideval)->get("evaluaciones")->row()->fecha;
		}else{
			$eval = $idord > 0 ? $this->clm->ObtenerOrden($idord) : null;
			$estado_act = $this->cpm->getEstado($idord);
			$fecha_emision = $this->db->select("fecha_orden")->where("id_orden", $idord)->get("orden_lab")->row()->fecha_orden;
		}
		$ord_ceros = $idord > 0 ? str_pad($idord, 6, '0', STR_PAD_LEFT):null;
		$observaciones = $this->db->select("observaciones")->where("id_orden", $idord)->get("orden_lab")->row()->observaciones;
		$estados = $this->cpm->getEstados();

		$anam = $this->db->select("id_clinica, id_doctor, id_empresa_conv")->where("id_anamnesis", $eval["id_anamnesis"])->get("anamnesis")->row();

		if(isset($anam->id_clinica) && $anam->id_clinica != 0){
			$clinica = $this->db->select("clinica_nombre")->where("id_clinica", $anam->id_clinica)->get("clinicas")->row()->clinica_nombre;
		}else{
			$clinica = "Ninguna";
		}

		if(isset($anam->id_doctor) && $anam->id_doctor != 0){
			$doctor = $this->db->select("doctor")->where("id_doctor", $anam->id_doctor)->get("doctores")->row()->doctor;
		}else{
			$doctor = "Ninguno";
		}

		if(isset($anam->id_empresa_conv) && $anam->id_empresa_conv != 0){
			$convenio = $this->db->select("empresa")->where("id_emp_conv", $anam->id_empresa_conv)->get("empresas_convenios")->row()->empresa;
		}else{
			$convenio = "Ninguna";
		}

		$this->load->view('header');
		$this->load->view('mantenimiento/ordenLaboratorio', array(
				'eval' 		 	=> $eval,
				'id_eval'	 	=> $ideval,
				'id_ord'	 	=> $idord,
				'ord_ceros'		=> $ord_ceros,
				'estados'	 	=> $estados,
				'estado_act' 	=> $estado_act,
				'observaciones' => $observaciones,
				'clinica'	 	=> $clinica,
				'doctor'	 	=> $doctor,
				'convenio'	 	=> $convenio,
				'fecha_emision' => $fecha_emision
		));

		$this->load->view('footer');	
	}
	public function orden_lab_updestado(){
		$datos = $_POST;
		$this->db->where("id_orden", $datos["idord"]);
		$this->db->update("orden_lab", array("id_estado_orden" => $datos["estado"]));

		//Insertamos en historial
		$this->db->insert("orden_lab_historial", array("id_orden_lab" => $datos["idord"], "id_estado" => $datos["estado"]));
		echo json_encode(array("result"=>"ok"));
	}
	public function guardarEvaluacion(){
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->clm->guardarEvaluacion($_POST)));
	}

	public function guardarOrden(){
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->clm->guardarOrden($_POST)));
	}

	public function clientecrud()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode(isset($_POST['id']) ? $this->clm->Actualizar(SafeRequestParameters($_POST)) : $this->clm->Registrar(SafeRequestParameters($_POST))));		
	}
	public function clienteeliminar($id)
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->clm->Eliminar($id)));		
	}
	public function doctorescli()
	{
		// Verificamos si tiene permiso
		//if(!$this->menumodel->VerificarAcceso()) redirect('inicio');

		$doc = $this->db->get("doctores")->result();
		$cli = $this->db->get("clinicas")->result();
		
		$this->load->view('header');
		$this->load->view('mantenimiento/doctoresclini', array("doctores" => $doc, "clinicas" => $cli));
		$this->load->view('footer');		
	}
	public function productos()
	{
		// Verificamos si tiene permiso
		if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		
		$this->load->view('header');
		$this->load->view('mantenimiento/productos');
		$this->load->view('footer');		
	}
	public function producto($id=0)
	{
		$p = $id > 0 ? $this->pm->Obtener($id) : null;
		$proveedores = $this->db->order_by("Nombre", "ASC")->get("proveedores")->result();
 		$this->load->view('header');
		$this->load->view('mantenimiento/producto', 
							array( 
								'producto' => $p,
								'proveedores' => $proveedores,
								'asignada' => $id > 0 ? $this->pm->HaSidoAsignada($id) : false
								)
							);
		$this->load->view('footer');		
	}
	public function productocrud()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode(isset($_POST['id']) ? $this->pm->Actualizar(SafeRequestParameters($_POST)) : $this->pm->Registrar(SafeRequestParameters($_POST))));		
	}
	public function productoeliminar($id)
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->pm->Eliminar($id)));		
	}
	public function servicios()
	{
		// Verificamos si tiene permiso
		if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		
		$this->load->view('header');
		$this->load->view('mantenimiento/servicios');
		$this->load->view('footer');		
	}
	public function servicio($id=0)
	{
		$s = $id > 0 ? $this->sm->Obtener($id) : null;
		 
 		$this->load->view('header');
		$this->load->view('mantenimiento/servicio', 
							array( 
								'servicio' => $s
								));
		$this->load->view('footer');		
	}
	public function serviciocrud()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode(isset($_POST['id']) ? $this->sm->Actualizar(SafeRequestParameters($_POST)) : $this->sm->Registrar(SafeRequestParameters($_POST))));		
	}
	public function servicioeliminar($id)
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->sm->Eliminar($id)));		
	}
	public function impresora($tipo)
	{
		$this->load->library('EnLetras', 'el');		
		$this->load->view('ventas/impresion', array(
			'comprobante' => $this->cpm->ObtenerPrueba($tipo),
			'EnLetras'    => new EnLetras()
		));
	}
	public function configuracion()
	{
		// Verificamos si tiene permiso
		if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		
		$this->load->view('header');
		$this->load->view('mantenimiento/configuracion', 
							array( 
								'configuracion' => $this->configuracionmodel->Obtener(),
								'monedas'       => $this->mm->Listar()
								));
		$this->load->view('footer');		
	}
	public function ConfiguracionActualizar()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->configuracionmodel->Actualizar(SafeRequestParameters($_POST))));
	}

	public function set_barcode($code)
    {
		//load library
		$this->load->library('zend');
		//load in folder Zend
		$this->zend->load('Zend/Barcode');
		//generate barcode
		$file = Zend_Barcode::draw('code128', 'image', array('text'=>$code), array());
		$store_image = imagepng($file,"codigos_barra/{$code}.png");
    }

    public function ordenlab_pdf($id_orden){
    	$idord = str_pad($id_orden, 6, '0', STR_PAD_LEFT);
    	$ord = $id_orden > 0 ? $this->db->select("id_evaluacion")->where("id_orden", $id_orden)->get("orden_lab")->row() : null;
    	$eval = $id_orden > 0 ? $this->clm->ObtenerOrden($id_orden) : null;
    	$evdat = $this->clm->ObtenerCliOrdenLab($ord->id_evaluacion);
    	$cli = $this->clm->Obtener($evdat["id_cliente"]);

    	//$this->load->view('mantenimiento/pdf_ordenLaboratorio', array("eval" => $eval, "cli" => $cli, "ord_ceros" => $idord, "evdat" => $evdat));
    	$this->load->library('pdf');
		$this->pdf->load_view('mantenimiento/pdf_ordenLaboratorio', array("eval" => $eval, "cli" => $cli, "ord_ceros" => $idord));
		$this->pdf->render();
		$this->pdf->stream("orden_lab_".$idord.".pdf");
    }

    public function receta_pdf($id_evaluacion){
    	$idord = str_pad($id_evaluacion, 6, '0', STR_PAD_LEFT);
    	$eval = $id_evaluacion > 0 ? $this->clm->ObtenerEval($id_evaluacion) : null;
    	$evdat = $this->clm->ObtenerCliOrdenLab($id_evaluacion);
    	$cli = $this->clm->Obtener($evdat["id_cliente"]);
    	//$this->load->view('mantenimiento/pdf_receta', array("eval" => $eval, "cli" => $cli, "ord_ceros" => $idord, "fecha" => $evdat["fecha"]));
    	$this->load->library('pdf');
			$this->pdf->load_view('mantenimiento/pdf_receta', array("eval" => $eval, "cli" => $cli, "ord_ceros" => $idord, "fecha" => $evdat["fecha"]));
			$this->pdf->render();
			$this->pdf->stream("receta_".$id_evaluacion.".pdf");
    }

    public function updalmacen(){
    	$dato = $this->db->get("producto")->result();
    	foreach($dato as $d){
    		$this->db->insert('almacen', array(
				'Tipo'            => 4,
				'Usuario_id'      => 1,
				'Producto_id'     => $d->id,
				'ProductoNombre'  => $d->Nombre,
				'UnidadMedida_id' => $d->UnidadMedida_id,
				'Cantidad'        => $d->Stock,
				'Fecha'           => date('Y/m/d'),
				'Empresa_id'      => $this->user->Empresa_id,
				'Precio'          => $d->PrecioCompra,
				'codigo_prod'	  => $d->codigo_prod
			));
			$this->set_barcode($d->codigo_prod);
    	}
    }

    public function preciosLunas(){
    	$datos = $this->db->where("estado", 1)->get('precios_lunas')->result();
    	$this->load->view('header');
		$this->load->view('mantenimiento/preciosLunas', array("precios"=>$datos));
		$this->load->view('footer');
    }

    public function editarPrecioLuna(){
    	$idp = $this->input->post('id_precio');
    	$datos = $this->db->where("id_precio", $idp)->get("precios_lunas")->row();
    	$this->load->view('mantenimiento/editarPrecio', array("prec" => $datos));
    }

    public function nuevoPrecioLuna(){
    	/*$idp = $this->input->post('id_precio');
    	$datos = $this->db->where("id_precio", $idp)->get("precios_lunas")->row();*/
    	$this->load->view('mantenimiento/nuevoPrecio');
    }

    public function addPrecio(){
    	$datos = $_POST;
    	$this->db->insert("precios_lunas", $datos);
    	$this->session->set_flashdata('correcto', 'Precio Agregado!');
        redirect('/mantenimiento/preciosLunas', 'refresh');
    }

    public function updLuna(){
    	$datos = $_POST;
    	$id = $datos["id_precio"];
    	unset($datos["id_precio"]);
    	$this->db->where("id_precio", $id)->update("precios_lunas", $datos);
    	$this->session->set_flashdata('correcto', 'Precio Actualizado!');
        redirect('/mantenimiento/preciosLunas', 'refresh');
	}

	public function nuevoDoctor(){
    	$this->load->view('mantenimiento/nuevoDoctor');
	}
	
	public function editarDoctor(){
		$id_doctor = $_POST['id_doctor'];
		$datos = $this->db->where("id_doctor", $id_doctor)->get("doctores")->row();
    	$this->load->view('mantenimiento/editarDoctor', array("datos" => $datos));
    }

	public function editarEmpresa(){
		$id_empresa = $_POST['id_empresa'];
		$datos = $this->db->where("id_emp_conv", $id_empresa)->get("empresas_convenios")->row();
    	$this->load->view('mantenimiento/editarEmpresa', array("datos" => $datos));
    }
	
	public function addDoctor(){
    	$datos = $_POST;
    	$this->db->insert("doctores", $datos);
    	$this->session->set_flashdata('correcto', 'Doctor Agregado!');
        redirect('/mantenimiento/doctorescli', 'refresh');
	}

	public function addEmpresa(){
    	$datos = $_POST;
    	$this->db->insert("empresas_convenios", $datos);
    	$this->session->set_flashdata('correcto', 'Empresa Agregada!');
        redirect('/mantenimiento/empresasConvenios', 'refresh');
	}

	public function updDoctor(){
    	$datos = $_POST;
    	$this->db->where("id_doctor", $datos["id_doctor"])->update("doctores", array("doctor" => $datos["doctor"], "porcentaje" => $datos["porcentaje"], "porcentaje2" => $datos["porcentaje2"], "cumpleanios" => $datos["cumpleanios"]));
    	$this->session->set_flashdata('correcto', 'Doctor editado!');
        redirect('/mantenimiento/doctorescli', 'refresh');
	}

	public function updEmpresa(){
    	$datos = $_POST;
    	$this->db->where("id_emp_conv", $datos["id_emp_conv"])->update("empresas_convenios", array("empresa" => $datos["empresa"], "email" => $datos["email"]));
    	$this->session->set_flashdata('correcto', 'Empresa editada!');
        redirect('/mantenimiento/empresasConvenios', 'refresh');
	}
	
	public function nuevaClinica(){
    	$this->load->view('mantenimiento/nuevaClinica');
	}

	public function nuevaEmpresa(){
    	$this->load->view('mantenimiento/nuevaEmpresa');
	}
	
	public function addClinica(){
    	$datos = $_POST;
    	$this->db->insert("clinicas", $datos);
    	$this->session->set_flashdata('correcto', 'Clínica Agregada!');
        redirect('/mantenimiento/doctorescli', 'refresh');
	}

	public function clientecomp(){
		$this->clm->clienteComp($_POST);
		echo json_encode(array("resultado" => "ok"));
		//var_dump($_POST);
	}

    public function generarExcel($tabla, $fechaini=0, $fechafin=0){
    	$cabeceras = $this->db->list_fields($tabla);
    	$datos = $this->db->get($tabla)->result();
    	//var_dump($cabeceras);
    	$sal = "";
    	$sal .= '<table border="1">
    	<tr>';
    		
    	foreach($cabeceras as $cab){
    		$sal .= '<td>'.$cab.'</td>';
    	}
    	$sal .= '</tr>';

    	foreach ($datos as $d) {
    		$sal .= '<tr>';
    		foreach($d as $fil){
    			$sal .= '<td>'.htmlentities($fil).'</td>';
    		}
    		$sal .= '</tr>';
    	}

    	$sal .= '
    	</table>
    	';
    	header("Content-type: application/vnd.ms-excel; name='excel'; charset=utf-8");
		header("Content-Disposition: filename=".$tabla."-".date("d-m-Y").".xls");
		header("Pragma: no-cache");
		header("Expires: 0");

		echo utf8_decode($sal);
    }

	function productosConStock(){
		$this->load->library('excel');

		$datos = $this->db->query("SELECT * FROM producto WHERE Stock > '0'")->result();

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Nombre');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Codigo');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Categoria');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Marca');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Material');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Codigo Varilla');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Descripcion');
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Precio Venta');
		$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Stock');
		$cont = 2;
    	foreach ($datos as $d) {
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$cont, $d->Nombre);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$cont, $d->codigo_prod);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$cont, $d->categoria);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$cont, $d->Marca);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$cont, $d->material);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$cont, $d->codigo_varilla);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$cont, $d->descripcion);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$cont, $d->Precio);
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$cont, $d->Stock);
			
			$cont++;
		}

		$filename = "productos_al_".date('d-m-y').".xls";
		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output');
	}

    public function getCodBarras(){
    	$prods = $this->db->get("producto")->result();
    	$this->load->view("codbarra", array("prods"=>$prods));
    }

    //Agregando nueva compra desde orden de lab.
    public function nuevaCompraOrd()
	{
		//if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		$FConfig = 	array(
			'upload_path'   => './uploads',
			'allowed_types' => 'gif|jpg|png|jpeg',
			'max_size'      => '8000',
			//'overwrite'     => true,
			'encrypt_name'  => true
		);
		$this->upload->initialize($FConfig);
		if(isset($_FILES['image'])){
			if($this->upload->do_upload('image')){
				$upd = array('upload_data' => $this->upload->data());
				$imgfin = $upd["upload_data"]["file_name"];
				//$this->load->view('upload_success',$data);
			}else{
				$upd = array('error' => $this->upload->display_errors());
				//$this->load->view('file_view', $error);
				$imgfin = "";
			}
		}else{
			unset($data['image']);
		}

		$datos = array(
			"id_proveedor"		=> $_POST["idprove"],
			"monto"				=> $_POST["total"],
			"fecha"				=> $_POST["fecha"],
			"guia_factura"		=> $_POST["factura"],
			"observaciones"		=> $_POST["observaciones"],
			"imagen"			=> $imgfin,
			"Empresa_id"		=> $this->user->Empresa_id,
			"igv_total"			=> $_POST["total"]-($_POST["total"]/1.18),
			"igv"				=> "18.00",
			"id_orden"			=> $_POST["id_orden"],
			"origen_compra"		=> $_POST["origen_dinero"],
		);

		$this->db->insert("compras", $datos);
		$insert_id = $this->db->insert_id();

		$dataco = array(
			"id_compra"		=> $insert_id,
			"producto"		=> $_POST["material"],
			"cantidad"		=> 1,
			"udm"			=> "UND",
			"precio_total"	=> $_POST["total"],
			"precio_compra"	=> $_POST["total"],
			"igv"			=> "18.00",
			"Empresa_id"	=> $this->user->Empresa_id,
		);

		$this->db->insert("compras_detalle", $dataco);

		//Si fue pago de caja chica se genera el egreso/deposito

		if($_POST["origen_dinero"] == 1){
			$datos_eg = array(
				"monto_egreso" => $_POST["total"],
				"concepto" => "Pago compra #".$insert_id,
				"id_usuario" => $this->user->id,
				"Empresa_id" => $this->user->Empresa_id,
				"fecha" => $_POST["fecha"],
				"origen_dinero" => $_POST["origen_dinero"],
				"id_compra" => $insert_id,
			);
			$this->db->insert("egresos", $datos_eg);

			$this->db->where('id_compra', $insert_id);
			$this->db->set('monto_cancelado', 'monto_cancelado+' . $_POST['total'], false);
			$this->db->update('compras');
		}

		$this->db->where("id_orden", $_POST["id_orden"])->update("orden_lab", array("monto_compra" => $_POST["total"], "id_compra" => $insert_id, "origen_dinero" => $_POST["origen_dinero"]));
		$this->responsemodel->SetResponse(true);
		$this->responsemodel->href   = 'almacen/nuevaCompra/0/' . $insert_id;
		echo json_encode($this->responsemodel);
	}

	public function updOrden(){
		$id_orden = $_POST["id_ordenlab"];
		$id_eval = $_POST["id_eval"];
		$fece = explode("|", $_POST["fecha_entrega"]);
		$fecha_e = explode("/", $fece[0]);
		$fecha_entrega = date("Y-m-d H:i:s", strtotime($fecha_e[2]."-".$fecha_e[1]."-".$fecha_e[0]." ".$fece[1].":00"));
		$this->db->where("id_orden", $id_orden)->update("orden_lab", array("observaciones" => $_POST["observaciones_lab"], "fecha_entrega" => $fecha_entrega));
		unset($_POST["id_ordenlab"]);
		unset($_POST["id_eval"]);
		unset($_POST["observaciones_lab"]);
		$data = $_POST;
		foreach($data as $key => $value){
			//echo $key." - ".$value."<br>";
			$upd = array(
				"meta_key"		=>	$key,
				"meta_value"	=>	$value
			);

			$bus = $this->db->where("id_orden", $id_orden)->where("meta_key", $key)->get("orden_lab_meta")->num_rows();

			if($bus>0){
				$this->db->where("id_orden", $id_orden)->where("meta_key", $key)->update("orden_lab_meta", $upd);
			}else{
				$datinsermeta = array("meta_key" =>	$key, "meta_value" => $value, "id_orden" => $id_orden);
				$this->db->insert("orden_lab_meta", $datinsermeta);
			}
		}

		$this->responsemodel->SetResponse(true);
		echo json_encode($this->responsemodel);
	}

	public function getProveedores(){
		$proveedores = $this->db->get("proveedores")->result();
		echo json_encode($proveedores);
	}

	public function empresasConvenios()
	{

		$empresas = $this->db->get("empresas_convenios")->result();
		
		$this->load->view('header');
		$this->load->view('mantenimiento/empresasconvenios', array("empresas" => $empresas));
		$this->load->view('footer');		
	}

	public function addProdCompra(){

		$datos = array(
			"Nombre" => $_POST["Nombre"],
			"Marca" => $_POST["Marca"],
			"codigo_prod" => $_POST["codigo_prod"],
			"codigo_varilla" => $_POST["codigo_varilla"],
			"Stock" => $_POST["Stock"],
			"StockMinimo" => $_POST["StockMinimo"],
			"CostoBase" => $_POST["CostoBase"],
			"PrecioCompra" => $_POST["PrecioCompra"],
			"Precio" => $_POST["Precio"],
			"categoria" => $_POST["categoria"],
			"rango" => $_POST["rango"],
			"material" => $_POST["material"],
			"tipo_aro" => $_POST["tipo_aro"],
			"sexo" => $_POST["sexo"],
			"descripcion" => $_POST["descripcion"],
			"codigo_proveedor" => $_POST["codigo_proveedor"],
		);

		$nuevoprod = $this->db->insert("producto", $datos);

		if($nuevoprod){
			$insert_id = $this->db->insert_id();

			$this->db->where("id", $insert_id)->update("producto", array("codigo_prod" => $insert_id."-".$_POST['codigo_proveedor'], "Nombre" => $_POST['Marca']." ".$insert_id."-".$_POST['codigo_proveedor']));

			$producto = $this->db->where("id", $insert_id)->get("producto")->row();

			$registrarTienda = $this->registrarTienda($insert_id);

			echo json_encode(array("success" => true, "id_producto" => $insert_id, "producto" => $producto));
		}else{
			echo json_encode(array("success" => false));
		}
	}

	public function registrarTienda($id){
		$categorias = array(
            "MONTURA OFTALMICA"    => 58,
            "MONTURA SOLAR"        => 179,
            "MONTURA DEPORTIVA"    => 60,
            "MONTURA DE SEGURIDAD" => 61,
            "NIÑA"                 => 62,
            "NIÑO"                 => 63,
            "DAMA"                 => 64,
            "VARON"                => 65,
            "UNISEX"               => 66,
            "ACETATO"              => 67,
            "METAL"                => 68,
            "GOMA"                 => 69,
            "TITANIO"              => 71,
            "CAREY"                => 72
		);
		
		$tags_ids = array(
            "MONTURA LECTORES" => 266,
            "MONTURA DEPORTIVA" => 267,
            "MONTURA DE SEGURIDAD" => 268,
            "OFERTAS DEL MES" => 269,
            "NIÑO" => 270,
            "NIÑA" => 271,
            "VARÓN" => 272,
            "DAMA" => 273,
            "ACETATO" => 274,
            "METAL" => 275,
            "CAREY" => 276,
            "GOMA" => 277,
            "TR-90" => 278,
            "PLASTICO" => 279,
            "ACETATO CON FIBRA DE CARBONO" => 280,
            "ARO COMPLETO" => 281,
            "AL AIRE" => 282,
            "SEMI AL AIRE" => 283,
            "MONTURA OFTALMICA" => 265,
            "MONTURA SOLAR" => 263
        );

        $marcas_array = array(
            "TR-90" => 80,
            "Ultem" => 78,
            "Activa" => 219,
            "Active" => 236,
            "Actual" => 240,
            "Adidas" => 190,
            "AirMag" => 216,
            "Alpi" => 207,
            "Andre Moratti" => 213,
            "Angelina Bondone" => 237,
            "Argos Luxury" => 200,
            "Arnette" => 196,
            "Bellagio" => 238,
            "Belluno" => 239,
            "Boss" => 224,
            "Brucken" => 249,
            "Calvin Klein" => 191,
            "Candie´s" => 228,
            "Carolina Herrera" => 184,
            "Carrera" => 225,
            "Caterpillar" => 217,
            "Centro Style" => 243,
            "Chufang" => 252,
            "Converse" => 202,
            "Dolce Gabana" => 229,
            "Donna Karan" => 192,
            "Dragon" => 199,
            "Drwn" => 230,
            "Emporio Armani" => 223,
            "Europtics" => 224,
            "Express" => 186,
            "Ezio" => 194,
            "Ezio Brazzini" => 195,
            "Ferretti" => 206,
            "Gant" => 221,
            "Giordi" => 215,
            "Go Flex" => 176,
            "Goma" => 69,
            "Guess" => 185,
            "Hello Kitty" => 232,
            "Henko" => 189,
            "Kenneth Cole Reaction" => 227,
            "Kenzia" => 231,
            "Lapo" => 210,
            "Missoni" => 193,
            "Miyagi" => 198,
            "Mondi" => 235,
            "Moschino" => 183,
            "Nano Vista" => 244,
            "Nautica" => 209,
            "Sili Flex" => 250,
            "New York" => 241,
            "Nike" => 253,
            "Nine West" => 197,
            "Oakley" => 201,
            "Opal" => 248,
            "Osiris" => 188,
            "Pentax" => 246,
            "Pierre Cardin" => 182,
            "Polaroid" => 245,
            "Puma" => 204,
            "Pv Optics" => 233,
            "Quality" => 212,
            "Quest" => 187,
            "Ray Ban" => 220,
            "Reebok" => 203,
            "Roxy" => 180,
            "Smart" => 226,
            "Spoleto" => 175,
            "Spy" => 247,
            "Swarovski" => 178,
            "Tatto" => 181,
            "Tony Hawk" => 214,
            "Urban Chaos" => 222,
            "V-Ferrucci" => 218,
            "Vortex" => 174,
            "Xox" => 254,
		);


		$p = $this->db->where("id", $id)->get("producto")->row();

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

			$categorias_arr = array();
                
			$sexo = trim($p->sexo);
			$categoria = trim($p->categoria);
			$material = trim($p->material);
			$marca = trim(ucwords(strtolower($p->Marca)));
			$tipo_aro = trim(strtoupper($p->tipo_aro));

			$categorias_arr = array();
			
			if(array_key_exists($sexo, $categorias)){
				$categorias_arr[] = array("id" => $categorias[$sexo]);
			}
			if(array_key_exists($categoria, $categorias)){
				$categorias_arr[] = array("id" => $categorias[$categoria]);
			}
			if(array_key_exists($material, $categorias)){
				$categorias_arr[] = array("id" => $categorias[$material]);
			}

			if(array_key_exists($marca, $marcas_array)){
				$categorias_arr[] = array("id" => $marcas_array[$marca]);
			}

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
                $reto_tag = ['id' => 269,];
            }else{
                $reto_tag = ['id' => 0,];
            }

			if($p->reto == 1){
				$categorias_arr[] = array("id" => 168);
			}

			$arr_prods = array(
				"type" => "simple",
				"name" => $p->Nombre,
				"description" => $p->codigo_prod,
				"sku" => $p->codigo_prod,
				"regular_price" => $p->Precio,
				"short_description" => '<img class="alignnone wp-image-6535 " src="https://tienda-virtual.guillentamayo.com/wp-content/uploads/2020/07/medidas-monturas-02-300x94.png" alt="" width="102" height="32" />  Diagonal aro: 0

				<img class="alignnone wp-image-6537" src="https://tienda-virtual.guillentamayo.com/wp-content/uploads/2020/07/medidas-monturas-03-1-300x110.png" alt="" width="101" height="37" />  Puente: 0
				
				<img class="alignnone wp-image-6534 " src="https://tienda-virtual.guillentamayo.com/wp-content/uploads/2020/07/medidas-monturas-01-300x97.png" alt="" width="102" height="33" />  Largo varilla: 0',
				"manage_stock" => true,
				"stock_quantity" => $p->Stock,
				"has_variations" => 0,
				"categories" => $categorias_arr,
				"tags" => [$sexo_tag, $categoria_tag, $material_tag, $marca_tag, $reto_tag, $aro_tag,],
			);
			
			//echo json_encode($arr_prods);

			$wc_product = $woocommerce->post( 'products', $arr_prods );

			return $wc_product;
		}
		
		return true;
	}


	public function Ajax($action)
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		// Productos
		switch($action)
		{
			case 'CargarUsuarios':
				print_r(json_encode($this->um->Listar()));
				break;
			case 'CargarProductos':
				print_r(json_encode($this->pm->Listar()));
				break;
			case 'CargarProveedores':
				print_r(json_encode($this->pvm->Listar()));
				break;
			case 'CargarClientes':
				print_r(json_encode($this->clm->Listar()));
				break;
			case 'CargarServicios':
				print_r(json_encode($this->sm->Listar()));
				break;
			case 'GuardarConfiguracionImpresora':
				print_r(json_encode($this->cfm->GuardarConfiguracionImpresora($this->input->post('f'), $this->input->post('tipo'))));
				break;
		}
	}
}
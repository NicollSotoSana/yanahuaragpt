<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Caja extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('usuariomodel', 'um');
		$this->load->model('cajamodel', 'cajam');
	}
	public function index()
	{
		$this->load->view('acceso/index', array(
			'empresas' => $this->um->Empresas()
		));
	}

	public function abrirCaja(){
		if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		$flag=0;
		$caja_diaria = 0;
		$total_caja = null;
		$todo_caja = $this->cajam->cajaTodo();

		$fec_ayer = date("Y-m-d", strtotime("-1 day"));
		$caja_ayer = $this->cajam->cajaTodoFec($fec_ayer);

		$total_caja_ayer = isset($caja_ayer->total_efectivo) && !empty($caja_ayer->total_efectivo) ? $caja_ayer->total_efectivo : "0.00";

		$totales_caja = array();

		if($this->cajam->checkCaja()>0){
			$flag = 1;
			$caja_dia = $this->cajam->getCaja();
			$datacaja["ingresosVentasEfe"] = $this->cajam->getIngresosComp("Efectivo");
			$datacaja["ingresosVentasVisa"] = $this->cajam->getIngresosComp("Visa");
			$datacaja["ingresosVentasMc"] = $this->cajam->getIngresosComp("MC");
			$datacaja["ingresosVentasEst"] = $this->cajam->getIngresosComp("Estilos");
			$datacaja["ingresosVentasDepo"] = $this->cajam->getIngresosComp("Deposito");
			$datacaja["ingresosVentasYape"] = $this->cajam->getIngresosComp("Yape");

			//Ingresos de pagos clientes deudas
			$datacaja["ingresosDeudasEfe"] = $this->cajam->getIngresosDeud("Efectivo");
			$datacaja["ingresosDeudasVisa"] = $this->cajam->getIngresosDeud("Visa");
			$datacaja["ingresosDeudasMc"] = $this->cajam->getIngresosDeud("MC");
			$datacaja["ingresosDeudasEst"] = $this->cajam->getIngresosDeud("Estilos");
			$datacaja["ingresosDeudasDepo"] = $this->cajam->getIngresosDeud("Deposito");
			$datacaja["ingresosDeudasYape"] = $this->cajam->getIngresosDeud("Yape");
			$datacaja["inicio"] = $caja_dia;

			//$total_caja_ayer = isset($caja_ayer->total_efectivo) && !empty($caja_ayer->total_efectivo) ? $caja_ayer->total_efectivo : "0.00";
		}

		if($todo_caja->estado == 2){
			$flag = 2;
		}


		$this->load->view('header');
		$this->load->view('caja/abrirCaja', array("flag"=>$flag, "caja_dia" => $caja_dia, "caja" => $datacaja, "caja_ayer" => $total_caja_ayer));
		$this->load->view('footer');
	}

	//Funcion que actualiza monto de caja inicial
	public function actualizarCaja(){
		$monto = $_POST["monto"];

		$this->db->where("DATE(fecha_hora_apertura) = '".date('Y-m-d')."'")->update("caja", array("monto_inicial" => $monto, "updated_at" => date("Y-m-d h:i:s")));

		echo json_encode(array("result" => "ok"));
	}

	//
	//Funcion que actualiza monto de caja inicial
	public function cerrarCaja(){
		//var_dump($_POST);
		$total_efectivo = $_POST["total_efectivo"];
		$total_visa = $_POST["total_visa"];
		$total_mc = $_POST["total_mc"];
		$total_estilos = $_POST["total_estilos"];
		$total_deposito = $_POST["total_deposito"];
		$total_yape = $_POST["total_yape"];

		$this->db->where("DATE(fecha_hora_apertura) = '".date('Y-m-d')."'")->update("caja", array("total_efectivo" => $total_efectivo, "total_visa" => $total_visa, "total_master" => $total_mc, "total_estilos" => $total_estilos, "total_deposito" => $total_deposito, "total_yape" => $total_yape, "fecha_hora_cierre" => date("Y-m-d h:i:s"), "estado" => 2));

		echo json_encode(array("result" => "ok"));
	}

	public function guardarCaja(){
		$this->cajam->guardarCaja($_POST);
		$this->session->set_flashdata('correcto', 'Caja aperturada correctamente!');
        redirect('inicio', 'refresh');
	}

	public function checkCaja(){
		$check = $this->cajam->checkcaja();
		if($check!=1){
			redirect("ventas/abrirCaja");
		}
	}

	public function cuadrarCaja(){
		$fecha 		= date("Y-m-d");
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
		
		$data["ingresosVentasEfe"] = $this->cajam->getIngresosComp("Efectivo");
		$data["ingresosVentasVis"] = $this->cajam->getIngresosComp("Visa");
		$data["ingresosVentasMc"] = $this->cajam->getIngresosComp("MC");
		$data["ingresosVentasEst"] = $this->cajam->getIngresosComp("Estilos");
		$data["ingresosVentasDepo"] = $this->cajam->getIngresosComp("Deposito");
		$data["ingresosVentasYape"] = $this->cajam->getIngresosComp("Yape");

		//Ingresos de pagos clientes deudas
		$data["ingresosDeudasEfe"] = $this->cajam->getIngresosDeud("Efectivo");
		$data["ingresosDeudasVis"] = $this->cajam->getIngresosDeud("Visa");
		$data["ingresosDeudasMc"] = $this->cajam->getIngresosDeud("MC");
		$data["ingresosDeudasEst"] = $this->cajam->getIngresosDeud("Estilos");
		$data["ingresosDeudasDepo"] = $this->cajam->getIngresosDeud("Deposito");
		$data["ingresosDeudasYape"] = $this->cajam->getIngresosDeud("Yape");

		$data["cajaDelDia"] = $this->cajam->getCaja();
		//var_dump($ingresosDeudas);
		$data["totalIngresos"] = $data["ingresosVentasEfe"]["total"]+$data["ingresosVentasVis"]["total"]+$data["ingresosVentasMc"]["total"]+$data["ingresosVentasEst"]["total"]+$data["ingresosVentasDepo"]["total"]+$data["ingresosVentasYape"]["total"]+$data["ingresosDeudasEfe"]["total"]+$data["ingresosDeudasVis"]["total"]+$data["ingresosDeudasMc"]["total"]+$data["ingresosDeudasEst"]["total"]+$data["ingresosDeudasDepo"]["total"]+$data["ingresosDeudasYape"]["total"];
		$this->load->view('header');
		$this->load->view('caja/cuadreCaja', array("datos"=>$data));
		$this->load->view('footer');
	}

	

	/*public function cerrarCaja($data){
		$id_eval = $data["id_eval"];
		$this->db->insert("orden_lab", array("id_evaluacion" => $id_eval, "id_estado_orden" => 1));
		$idorden = $this->db->insert_id();
		foreach($data as $k => $v){
			$ins = array(
				"meta_key"		=>	$k,
				"meta_value"	=>	$v,
				"id_orden"	=>  $idorden
			);
			$this->db->insert("orden_lab_meta", $ins);
		}

		$this->responsemodel->SetResponse(true);
		$this->responsemodel->href = 'mantenimiento/ordenLaboratorio/'.$id_eval.'/'.$idorden;
		return $this->responsemodel;
	}*/

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
	
	
	
	
	

}
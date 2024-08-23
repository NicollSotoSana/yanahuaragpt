<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reportemodel', 'rm');
		$this->load->model('almacenmodel', 'am');
		$this->load->model('comprobantemodel', 'cpm');
		$this->load->model('clientemodel', 'clm');
		$this->load->model('cajamodel', 'cajam');
	}
	public function index()
	{
		$this->load->view('header');
		$datos = $this->cpm->getOrdenesPendientes();
		$ret = array();
		foreach($datos as $d){
			$evdat = $this->clm->ObtenerCliOrdenLab($d->id_evaluacion);
			$cli = $this->clm->Obtener($evdat["id_cliente"]);
			$vendedor = $this->db->select("Usuario")->where("id", $d->id_usuario)->get("usuario")->row()->Usuario;

			$ret[] = array("id_estado_orden" => $d->id_estado_orden, "id_orden" => $d->id_orden, "nombre" => $cli->Nombre, "id_cli" => $cli->id, "id_evaluacion" => $d->id_evaluacion, "fecha_entrega" => $d->fecha_entrega, "fecha_orden" => $d->fecha_orden, "vendedor" => $vendedor);

		}


		// Datos caja diaria
		$cajadatos = $this->cajaDatos();

		$this->load->view('inicio/index', array(
			'resumen'           => $this->rm->ReporteResumenBasico(),
			//'ProductosSinStock' => $this->am->ProductosPorAgotarse(),
			'ordenes'			=> $ret,
			'cumples'			=> $this->clm->getCumple(),
			'nearEvals'			=> $this->clm->getNearEvals(),
			//'caja'				=> $this->cajam->checkCaja() !=1 ? 0:$this->getCaja(),
			'caja'				=> $cajadatos,
			'isOpen'			=> $this->cajam->checkCaja(),
		));
		$this->load->view('footer');
	}

	public function getCaja(){
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
		return $data;
	}
	
	public function cajaDatos(){
		
		$fecha 	= date("Y-m-d");
		
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
		$data["ingresosVentasYape"] 	= $this->cajam->getIngresosCompRep("Yape", $fecha);

		//Ingresos de pagos clientes deudas
		$data["ingresosDeudasEfe"] 	= $this->cajam->getIngresosDeudRep("Efectivo", $fecha);
		$data["ingresosDeudasVis"] 	= $this->cajam->getIngresosDeudRep("Visa", $fecha);
		$data["ingresosDeudasMc"] 	= $this->cajam->getIngresosDeudRep("MC", $fecha);
		$data["ingresosDeudasEst"] 	= $this->cajam->getIngresosDeudRep("Estilos", $fecha);
		$data["ingresosDeudasDepo"] = $this->cajam->getIngresosDeudRep("Deposito", $fecha);
		$data["ingresosDeudasYape"] 	= $this->cajam->getIngresosDeudRep("Yape", $fecha);

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

		return array("datos" => $data, "fecha" => $fecha, "comprobantes" => $comprobantes, "egresos" => $egresos, "pagosDeudas" => $pagosDeudas);
	}
}
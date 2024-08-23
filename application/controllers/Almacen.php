<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Almacen extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('almacenmodel', 'am');
		$this->load->model('productomodel', 'pm');
	}

	/*public function fixFechas(){
		$alm = $this->db->get("almacen")->result();
		foreach($alm as $a){
			$fa = explode("/", $a->Fecha);
			echo $a->Fecha."<br/>";
			if($a->Tipo == 1){
				$fecha = $fa[0]."-".$fa[1]."-".$fa[2]." 00:00";
			}else{
				$fecha = $fa[0]."-".$fa[1]."-".$fa[2]." 16:00";
			}
			
			$this->db->where("id", $a->id)->update("almacen", array("fecha_movimiento" => $fecha));
		}
	}*/

	public function fixFechas(){
		$alm = $this->db->get("almacen")->result();
		foreach($alm as $a){
			
			echo $a->Fecha."<br/>";
			if($a->Tipo == 1){
				if(isset($a->id_compra)){
					$compra = $this->db->where("id_compra", $a->id_compra)->get("compras")->row();
					//$fa = explode("/", $a->Fecha);
					$fecha = $compra->fecha." 00:00";
				}else{
					$fa = explode("/", $a->Fecha);
					$fecha = $fa[0]."-".$fa[1]."-".$fa[2]." 00:00";
				}
				
			}elseif($a->Tipo == 2){
				if(isset($a->Comprobante_id)){
					$comprobante = $this->db->where("id", $a->Comprobante_id)->get("comprobante")->row();
					$fa = explode("/", $comprobante->FechaEmitido);
					$fecha = $fa[0]."-".$fa[1]."-".$fa[2]." 16:00";
				}else{
					$fa = explode("/", $a->Fecha);
					$fecha = $fa[0]."-".$fa[1]."-".$fa[2]." 16:00";
				}
				
			}
			
			$this->db->where("id", $a->id)->update("almacen", array("fecha_movimiento" => $fecha));
		}
	}
	
	public function Index()
	{
		// Verificamos si tiene permiso
		if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		
		$this->load->view('header');
		$this->load->view('almacen/index', 
			array(
				'tipos' => $this->am->Tipos()
			)
		);
		$this->load->view('footer');		
	}

	public function Kardex()
	{
		$this->load->view('header');
		$this->load->view('almacen/kardex');
		$this->load->view('footer');		
	}

	public function Entrada($id = 0)
	{
		$datos = array();

		if($id !=0)
			$datos['producto'] = $this->pm->Obtener($id);

		$this->load->view('header');
		$this->load->view('almacen/entrada', $datos);
		$this->load->view('footer');		
	}

	public function verCompras(){
		$this->load->view('header');
		$this->load->view('almacen/verCompras');
		$this->load->view('footer');	
	}

	public function verEgresos(){
		$this->load->view('header');
		$this->load->view('almacen/verEgresos');
		$this->load->view('footer');	
	}

	public function nuevaCompra($ordenlab=0, $id = 0)
	{
		$datos = array();

		if($id !=0){
			/*$c = $this->am->getCompra($id);
			$p = $this->am->getProveedor($c[0]->id_proveedor);*/
			$c = $this->am->getCompraOrd($id);
			$p = $this->am->getProveedor($c[0]->id_proveedor);
		}else{
			$c = null;
			$p = null;
		}

		$this->load->view('header');
		$this->load->view('almacen/nuevaCompra', array('comprobante' => $c, 'proveedor' => $p, 'orden_lab' => $ordenlab));
		$this->load->view('footer');		
	}

	

	public function guardarcompra()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		
		print_r(json_encode($this->am->GuardarCompra($_POST)));
	}

	public function EntradaCrud()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		
		print_r(json_encode($this->am->Entrada($_POST)));
	}

	public function Ajustar()
	{
		$this->load->view('header');
		$this->load->view('almacen/entrada');
		$this->load->view('footer');		
	}

	public function AjustarCrud()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		
		print_r(json_encode($this->am->Ajustar($_POST)));
	}

	public function eliminarCompra(){
		$idc = $this->input->post('compra_id');
		
		print_r(json_encode($this->am->eliminarCompra($idc)));
	}

	public function editarCompra(){
		$idc = $this->input->post('compra_id');
		$guiafact = $this->input->post('guiafact');
		print_r(json_encode($this->am->editarCompra($idc,$guiafact)));
	}


	public function Ajax($action)
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		// Productos
		switch($action)
		{
			case 'CargarAlmacen':
				print_r(json_encode($this->am->Listar()));
				break;
			case 'eliminarCompra':
				$ncompra = $this->input->post('ncompra');
				echo $this->load->view('almacen/eliminarCompra', array(
					'ncompra' => $ncompra),true);
				break;
			case 'cargarCompras':
				print_r(json_encode($this->am->cargarCompras()));
				break;
			case 'editarCompra':
				$ncompra = $this->input->post('ncompra');
				$act = $this->input->post('act');
				echo $this->load->view('almacen/editarCompra', array(
					'ncompra' => $ncompra, 'act' => $act),true);
				break;
			case 'CargarKardex':
				$alm = $this->am->Kardex($this->input->post('f1'), $this->input->post('f2'));
				$ini_valorado = $this->db->query("SELECT Precio FROM almacen WHERE id < '".$alm[0]->id."' ORDER BY id DESC LIMIT 1")->row();
				//var_dump($ini_valorado);
				echo $this->load->view('almacen/_kardex', array('kardex' => $alm, 'inicial_valorado' => $ini_valorado),true);
				break;
		}
	}
}

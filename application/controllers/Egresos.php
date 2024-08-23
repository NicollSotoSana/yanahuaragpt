<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Egresos extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('usuariomodel', 'um');
		$this->load->model('egresosmodel', 'em');
	}
	public function index()
	{
		if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		$this->load->view('header');
		$this->load->view('egresos/index');
		$this->load->view('footer');
	}

	public function updEgreso(){
		$monto = $this->input->post("monto");
		$concepto = $this->input->post("concepto");
		$org = $this->input->post("origen_dinero");
		$ide = $this->input->post("ide");
		$fec = $this->input->post("fecha");
		$data = array(
			"monto_egreso"  => $monto,
			"concepto" 		=> $concepto,
			"id_usuario"	=> $this->user->id,
			"Empresa_id"	=> $this->user->Empresa_id,
			"fecha"			=> $fec,
			"origen_dinero" => $org,
		);
		$this->db->where("id_egreso", $ide)->update("egresos", $data);
		$this->session->set_flashdata('correcto', 'Egreso guardado!');
		redirect('/almacen/verCompras', 'refresh');
	}

	public function addEgreso(){
		$monto = $this->input->post("monto");
		$concepto = $this->input->post("concepto");
		$org = $this->input->post("origen_dinero");
		$fec = $this->input->post("fecha");
		$data = array(
			"monto_egreso"  => $monto,
			"concepto" 		=> $concepto,
			"id_usuario"	=> $this->user->id,
			"Empresa_id"	=> $this->user->Empresa_id,
			"fecha"			=> $fec,
			"origen_dinero" => $org,
		);
		$this->db->insert("egresos", $data);
		$this->session->set_flashdata('correcto', 'Egreso guardado!');
        redirect('/almacen/verCompras', 'refresh');
	}

	public function editEgreso($id){
		if($this->user->Tipo==1){
			$datos = $this->em->getEgreso($id);
			$this->load->view("egresos/editEgreso", array("egre"=>$datos));
		}else{
			echo "No tiene permisos para acceder a esta área";
		}
		
	}

	public function reporteCompras($inicio, $fin){
		header("Content-type: application/vnd.ms-excel; name='excel'; charset=utf-8");
		header("Content-Disposition: filename=compras-".$inicio."-to-".$fin.".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		$sql = "SELECT * 
			FROM compras c 
			INNER JOIN proveedores p 
			ON p.id = c.id_proveedor
			WHERE c.fecha BETWEEN '$inicio' and '$fin'";
		$resul = $this->db->query($sql)->result();
		$data = '<table border="1"><tr><td><strong>Fecha</strong></td><td><strong>Monto Total</strong></td><td><strong>Monto Cancelado</strong></td><td><strong>Proveedor</strong></td></tr>';
		foreach($resul as $r){
			$data .= '<tr><td>'.$r->fecha.'</td><td>'.$r->monto.'</td><td>'.$r->monto_cancelado.'</td><td>'.$r->Nombre.'</td></tr>';
		}
		$data .= '</table>';

		echo $data;
	}

	public function reporteEgresos($inicio, $fin){
		header("Content-type: application/vnd.ms-excel; name='excel'; charset=utf-8");
		header("Content-Disposition: filename=egresos-".$inicio."-to-".$fin.".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		$sql = "SELECT * 
			FROM egresos 
			WHERE fecha BETWEEN '$inicio' and '$fin'";
		$resul = $this->db->query($sql)->result();
		$data = '<table border="1"><tr><td><strong>Fecha</strong></td><td><strong>Monto</strong></td><td><strong>Descripcion</strong></td><td><strong>Origen</strong></td></tr>';
		foreach($resul as $r){
			$data .= '<tr><td>'.$r->fecha.'</td><td>'.$r->monto_egreso.'</td><td>'.$r->concepto.'</td>';
			if($r->origen_dinero==1){
				$data .= '<td>Caja Chica</td>';
			}elseif($r->origen_dinero==2){
				$data .= '<td>Cuenta Empresa</td>';
			}elseif($r->origen_dinero==3){
				$data .= '<td>Hafid</td>';
			}elseif($r->origen_dinero==4){
				$data .= '<td>Claudio</td>';
			}
			$data .= '</tr>';
		}
		$data .= '</table>';

		echo $data;
	}

	public function ajax($action){
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		// Ajax Depositos
		switch($action)
		{
			case 'cargarPagos':
				$pagos = $this->dm->getDetallePagos($this->input->post('ncompra'));
				echo $this->load->view('almacen/detallePagos', 
						array(
							'pagos'   => 	$pagos
						),true);
				break;

			case 'cargarEgresos':
				print_r(json_encode($this->em->Listar()));
				break;

			case 'agregarEgreso':
				echo $this->load->view('egresos/nuevoEgreso', '', true);
				break;

		}
	}
}
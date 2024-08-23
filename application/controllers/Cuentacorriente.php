<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CuentaCorriente extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('clientemodel', 'cm');
		$this->load->model('cuentacorrientemodel', 'ccm');
		$this->load->model('comprobantemodel', 'cpm');
	}

	function usuario($id){
		$this->load->view('header');

		$cliente = $this->cm->Obtener($id);
		$deudas  = $this->ccm->getDeudas($id);

		$this->load->view('cuentascorrientes/cuentausuario', array(
			'cliente' => $cliente,
			'deudas'  => $deudas

		));
		$this->load->view('footer');	
	}

	public function generarComprobante(){
		$cpm = $this->db->where("id_deuda", $_POST['deuda_id'])->get("deudas")->row();
		print_r(json_encode($this->ccm->generarComprobante($_POST, $cpm->comprobante_id)));
	}

	public function generarComprobanteCpe(){
		$cpm = $this->db->where("id_deuda", $_POST['deuda_id'])->get("deudas")->row();
		print_r(json_encode($this->ccm->generarComprobanteCpe($_POST, $cpm->comprobante_id)));
	}

	public function ajax($action)
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		switch($action)
		{
			case 'agregarPago':
				$id_cliente = $this->input->post('id_cliente');
				echo $this->load->view('cuentascorrientes/agregarpago', 
				array(
					'deuda_id'   => $this->input->post('id_deuda'),
					'id_cliente' => $id_cliente,
					'cliente'	 => $this->cm->Obtener($id_cliente),
					'correlativ' => $this->input->post('correlativ'),
				),true);
				break;
			case 'agregarPagoCpe':
				$id_cliente = $this->input->post('id_cliente');
				echo $this->load->view('cuentascorrientes/agregarpagocpe', 
				array(
					'deuda_id'   => $this->input->post('id_deuda'),
					'id_cliente' => $id_cliente,
					'cliente'	 => $this->cm->Obtener($id_cliente),
					'correlativ' => $this->input->post('correlativ'),
					'id_comprobante' => $this->input->post('id_comprobante'),
					'tipo_comprobante' => $this->input->post('tipo_comprobante'),
				),true);
				break;
			case 'verDetalle':
				$pagos = $this->ccm->getDetalle($this->input->post('id_deuda'));
				echo $this->load->view('cuentascorrientes/verdetalle', 
						array(
							'deudas'   => 	$pagos
						),
							 true);
				break;
		}
	}
}
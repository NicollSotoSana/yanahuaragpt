<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Depositos extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('usuariomodel', 'um');
		$this->load->model('depositosmodel', 'dm');
	}
	public function index()
	{
		$this->load->view('header');
		$this->load->view('depositos/index');
		$this->load->view('footer');
	}

	public function ajax($action){
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		// Productos
		switch($action)
		{
			case 'cargarPagos':
				$pagos = $this->dm->getDetallePagos($this->input->post('ncompra'));
				echo $this->load->view('almacen/detallePagos', 
						array(
							'pagos'   => 	$pagos
						),true);
				break;

			case 'cargarDepositos':
				print_r(json_encode($this->dm->Listar()));
				break;

			case 'agregarDep':
				if($this->input->post('npago')){
					$npago = $this->input->post('npago');
				}else{
					$npago = "";
				}
				echo $this->load->view('depositos/nuevodeposito', array(
					'npago' => $npago,
				),
							 true);
				break;
			case 'guardar':
				print_r(json_encode($this->dm->Guardar($_POST)));
				break;

		}
	}

}
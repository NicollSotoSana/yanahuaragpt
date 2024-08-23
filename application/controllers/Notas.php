<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notas extends CI_Controller 
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
		$this->load->model('notasmodel', 'notam');
	}

	public function index(){
		// Verificamos si tiene permiso
		//if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
				
		$this->load->view('header');
		$this->load->view('notas/index', array(
			'estados'      => $this->cpm->Estados(),
			'pendiente'    => $this->cpm->ImpresionPendiente()    
		));
		$this->load->view('footer');
	
	}

	public function nota($id = 0)
	{
		$c = $id != '' ? $this->notam->Obtener($id) : null;
		$client = $id != '' ? $this->clm->Obtener($c->Cliente_id) : null;

		$this->load->view('header');
		$this->load->view('notas/vernotas', array(
			'comprobante'  => $c,
			'cliente'	   => $client,
			'tipos'        => $this->cpm->Tipos(),
			'estados'      => $this->cpm->Estados(),
		));
		$this->load->view('footer');
	}

	//Emision de notas de credito
	public function notascrud()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode(isset($_POST['id']) ? $this->notam->Actualizar(SafeRequestParameters($_POST)) : $this->notam->emitirNota(SafeRequestParameters($_POST))));
	}

	public function enviarSunat($id){
		echo $this->notam->generarNota($id);
	}

	public function crearNota($tipo_doc, $id = 0)
	{
		$ex = $this->db->select("external_id")->where("id", $id)->get("comprobante")->row();
		$c = $id != '' ? $this->cpm->Obtener_consolid($id,$tipo_doc) : null;
		$client = $id != '' ? $this->clm->Obtener($c->Cliente_id) : null;
		$this->load->view('header');
		$this->load->view('notas/crearnotas', array(
			'comprobante'  => $c,
			'cliente'	   => $client,
			'tipos'        => $this->cpm->Tipos(),
			'estados'      => $this->cpm->Estados(),
			'tipo_doc'     => $tipo_doc,
			'external'	   => $ex->external_id
		));
		$this->load->view('footer');
	}

	public function ajax($action)
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		// Productos
		switch($action)
		{
			case 'CargarNotas':
				print_r(json_encode($this->notam->Listar()));
				break;
		}
	}

}
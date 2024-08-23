<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asistencia extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('asistenciamodel', 'am');
	}
	public function index()
	{
		$this->load->view('header');
		$this->load->view('asistencia/index', array(
			'tipos' => $this->am->getTipos()
		));
		$this->load->view('footer');
	}
	public function saveAsistencia(){
		$this->am->saveAsistencia($_POST);
		echo json_encode(array("result" => "ok"));
	}
	public function logout()
	{
		$this->session->unset_userdata('usuario');
		redirect('');
	}

}
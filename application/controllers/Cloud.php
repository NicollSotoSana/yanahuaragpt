<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cloud extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('usuariomodel', 'um');
	}
	public function index()
	{
		$this->load->view('header');
		$this->load->view('cloud/index');
		$this->load->view('footer');
	}
}
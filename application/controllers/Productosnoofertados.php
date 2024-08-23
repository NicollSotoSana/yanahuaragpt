<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productosnoofertados extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('clientemodel', 'clm');
		$this->load->model('monedamodel', 'mm');
	}

	public function index()
	{
		// Verificamos si tiene permiso
		//if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		$this->db->select('*');    
		$this->db->from('productos_no_ofertados');
		$this->db->join('usuario', 'usuario.id = productos_no_ofertados.id_usuario');
		$productos = $this->db->get()->result();
		
		$this->load->view('header');
		$this->load->view('productosnoofertados/index', array("productos" => $productos));
		$this->load->view('footer');		
	}

	public function nuevoProducto(){
    	$this->load->view('productosnoofertados/nuevoProducto');
	}

	public function addProducto(){
    	$datos = array(
			"producto_solicitado" => $_POST["producto"],
			"id_usuario" => $this->user->id,
			"fecha" => date("Y-m-d")
		);
    	$this->db->insert("productos_no_ofertados", $datos);
    	$this->session->set_flashdata('correcto', 'Doctor Agregado!');
        redirect('/Productosnoofertados', 'refresh');
	}

	public function editarProducto(){
		$id_producto = $_POST['id_producto'];
		$datos = $this->db->where("id_prod_no_ofertado", $id_producto)->get("productos_no_ofertados")->row();
    	$this->load->view('Productosnoofertados/editarProducto', array("datos" => $datos));
    }

}
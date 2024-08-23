<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('clientemodel', 'clm');
		$this->load->model('productomodel', 'pm');
		$this->load->model('serviciomodel', 'sm');
		$this->load->model('proveedormodel', 'pvm');
		$this->load->model('configuracionmodel', 'cfm');
	}
	public function validarCupon($codigo){
		$val = $this->db->where("codigo_cupon", $codigo)->get("cupones");
		$retorno = array();
		if($val->num_rows() > 0){
			$datos = $val->row();
			$retorno["success"]	   = true;
			$retorno["cupon"] 	   = $datos->codigo_cupon;
			$retorno["fecha_vcto"] = $datos->fecha_vcto;
			$dateTimestamp1 = strtotime(date('Y-m-d')); 
			$dateTimestamp2 = strtotime($datos->fecha_vcto);

			if($dateTimestamp1 <= $dateTimestamp2){
				$retorno["valido"] = 1;
			}else{
				$retorno["valido"] = 0;
			}

			if($datos->estado == 1){
				$retorno["usado"] = 0;
			}else{
				$retorno["usado"] = 1;
			}
		}else{
			$retorno["success"] = false;
		}

		echo json_encode($retorno);

	}
	public function proveedores()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->pvm->Buscar($this->input->post('criterio'), $this->input->post('tipo'))));		
	}
	public function clientes()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->clm->Buscar($this->input->post('criterio'), $this->input->post('tipo'))));		
	}
	public function productosyservicios()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->pm->Buscar($this->input->post('criterio'), true)));		
	}
	public function productosyserviciosstock()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->pm->BuscarStock($this->input->post('criterio'), true)));		
	}
    public function productos()
    {
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
        $resultado = $this->pm->BuscarStock($this->input->post('criterio'));
        print_r(json_encode($resultado));
    }
	public function productosporcod()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->pm->BuscarCod($this->input->post('criterio'))));		
	}
	public function preciolente()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->pm->PrecioLente($this->input->post('criterio'))));	
	}
	public function materiales()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->pm->Materiales($this->input->post('criterio'), $this->input->post('descrip'))));		
	}
	public function descripcion()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->pm->Descripcion($this->input->post('criterio'))));		
	}
	public function productosCat()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->pm->BuscarCat($this->input->post('criterio'))));		
	}
	public function marcas()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->pm->Marcas($this->input->post('criterio'))));		
	}
	public function medidas()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->pm->Medidas($this->input->post('criterio'))));		
	}
	public function servicios()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->sm->Marcas($this->input->post('criterio'))));		
	}
	public function recetas()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->clm->recetas($this->input->post('criterio'), $this->input->post('tipo'))));		
	}
	public function getReniec($dni){

        $urlRuc = "https://apiperu.dev/api/dni/";
        $token = "5bc645b85387b6d8938b00723e5e5f249e3a5d5cc52bba0f3034579773164627";

        $url = $urlRuc.$dni."?api_token=".$token;
        $json = file_get_contents($url);
        $datos = json_decode($json,true);

        if($datos["success"] == true){
            $retorno = array("success" => $datos["success"], 
            "result" => array(
               "Nombres" => $datos["data"]["nombres"], 
               "Apellidos" => $datos["data"]["apellido_paterno"]." ".$datos["data"]["apellido_materno"], "Direccion" => $datos["data"]["direccion_completa"]
            ));
        }else{
            $retorno = array("success" => $datos["success"]);
        }
        
        echo json_encode($retorno);
	}
	public function getSunatData($rucaconsultar){
	    $token = "5bc645b85387b6d8938b00723e5e5f249e3a5d5cc52bba0f3034579773164627";
		$ruta = "https://apiperu.dev/api/ruc/".$rucaconsultar."?api_token=".$token."";
		
		$curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $ruta,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYPEER => false
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $ret = "cURL Error #:" . $err;
        } else {
            $leer_respuesta = json_decode($response, true);
            $leer_respuesta["data"]["success"] = $leer_respuesta["success"];
            $ret = $leer_respuesta["data"];
            
        }
        
        echo json_encode($ret);
    }


	/*
	$str = cadena de busqueda
	$tipo = 1 busqueda por dni, 2 busqueda por nombre
	*/
	public function buscarCliente(){
		$str = $this->input->post('dni', TRUE);
		$tipo = $this->input->post('tipo', TRUE);
		$con = $this->db->where("Dni", $str)->get("cliente")->row();

		if($con){
			$datos = $con;
		}else{
			$datos = array("success" => false);
		}
		echo json_encode($datos);
	}

	/*
	Funcion para buscar ultima anamnesis del paciente
	$id = id de cliente
	*/
	public function buscarAnamnesis(){
		$id = $this->input->post('idcliente', TRUE);
		//$fecha_bus = ;

		$datos = $this->db->where("id_cliente", $id)->where("fecha_anamnesis >= DATE_SUB(NOW(),INTERVAL 1 MONTH)")->limit(1)->order_by('fecha_anamnesis',"DESC")->get("anamnesis")->row();

		echo json_encode($datos);
	}

	/*
	$tipo = tipo de busqueda
	$str = cadena a buscar
	*/
	public function buscarLentes(){
		$tipo = $this->input->post('tipo', TRUE);
		$wh = $this->input->post('wh', TRUE);
		$val = $this->input->post('val', TRUE);
		$aw = $this->input->post('aw');

		$datos = $this->db->query('SELECT DISTINCT '.$tipo.' FROM precios_lunas WHERE '.$wh.' = \''.$val.'\' '.$aw.' AND estado = \'1\'')->result();

		echo json_encode($datos);
	}

	public function buscarPrecioLente(){
		$disenio = $this->input->post('disenio');
		$fabricacion = $this->input->post('fabricacion');
		$material = $this->input->post('material');
		$serie = $this->input->post('serie');
		$tratamiento_lente = $this->input->post('tratamiento_lente');
		$nombre = $this->input->post('nombre');
		$fotocromatico = $this->input->post('fotocromatico');
		$color_fotocromatico = $this->input->post('color_fotocromatico');

		$datos = $this->db->query("SELECT id_precio, precio, precio_compra, nombre_propio_fin as material_lente, nombre_lab_fin as nombre_lab FROM precios_lunas WHERE disenio = '".$disenio."' AND fabricacion = '".$fabricacion."' AND material = '".$material."' AND serie = '".$serie."' AND tratamiento = '".$tratamiento_lente."' AND nombre = '".$nombre."' AND fotocromatico = '".$fotocromatico."' AND color_fotocromatico = '".$color_fotocromatico."' AND estado='1'")->row();

		if($datos->id_precio && $datos->precio){
			echo json_encode(array("success" => true, "id_precio" => $datos->id_precio, "precio_compra" => $datos->precio_compra, "precio" => $datos->precio, "material_lente" => $datos->material_lente, "nombre_lab" => $datos->nombre_lab));
		}else{
			echo json_encode(array("success" => false));
		}
	}
}
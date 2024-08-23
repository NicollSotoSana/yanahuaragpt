<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class procesocliente extends CI_Controller 
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
	}
	public function index($id=null)
	{
		// Verificamos si tiene permiso
		//if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		$diseniosLunas = $this->db->query("SELECT DISTINCT disenio FROM precios_lunas")->result();
		$clinicas = $this->db->get("clinicas")->result();
		$doctores = $this->db->get("doctores")->result();
		$empresas_convenios = $this->db->get("empresas_convenios")->result();
		$profesiones = $this->db->get("profesiones")->result();
		$rubros_trabajo = $this->db->get("rubros_trabajo")->result();
		
		$ciudades = $this->db->get("ciudades")->result();
		$distritos = $this->db->get("distritos")->result();

		$this->load->view('header');
		$this->load->view('anamnesis/index', array("disenio" => $diseniosLunas, "clinicas" => $clinicas, "doctores" => $doctores, "convenios" => $empresas_convenios, "profesiones" => $profesiones, "rubros_trabajo" => $rubros_trabajo, "ciudades" => $ciudades, "distritos" => $distritos));
		$this->load->view('footer');		
	}

	public function anmanesisPaciente($doc=null, $tipo=null)
	{
		// Verificamos si tiene permiso
		//if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		$diseniosLunas = $this->db->query("SELECT DISTINCT disenio FROM precios_lunas")->result();
		$clinicas = $this->db->get("clinicas")->result();
		$doctores = $this->db->get("doctores")->result();
		$empresas_convenios = $this->db->get("empresas_convenios")->result();
		$profesiones = $this->db->get("profesiones")->result();
		$rubros_trabajo = $this->db->get("rubros_trabajo")->result();
		
		$ciudades = $this->db->get("ciudades")->result();
		$distritos = $this->db->get("distritos")->result();

		$this->load->view('header');
		$this->load->view('anamnesis/anamnesis_paciente', array("disenio" => $diseniosLunas, "clinicas" => $clinicas, "doctores" => $doctores, "convenios" => $empresas_convenios, "profesiones" => $profesiones, "rubros_trabajo" => $rubros_trabajo, "ciudades" => $ciudades, "distritos" => $distritos, "nro_doc" => $doc, "tipo_doc" => $tipo));
		$this->load->view('footer');		
	}

	public function anamnesis()
	{
		// Verificamos si tiene permiso
		//if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		$diseniosLunas = $this->db->query("SELECT DISTINCT disenio FROM precios_lunas")->result();
		$clinicas = $this->db->get("clinicas")->result();
		$doctores = $this->db->get("doctores")->result();
		$profesiones = $this->db->get("profesiones")->result();
		$rubros_trabajo = $this->db->get("rubros_trabajo")->result();
		$ciudades = $this->db->get("ciudades")->result();
		$distritos = $this->db->get("distritos")->result();
		$this->load->view('header');
		$this->load->view('anamnesis/index', array("disenio" => $diseniosLunas, "clinicas" => $clinicas, "doctores" => $doctores, "profesiones" => $profesiones, "rubros_trabajo" => $rubros_trabajo, "ciudades" => $ciudades, "distritos" => $distritos));
		$this->load->view('footer');		
	}

	public function veranamnesis($idanamnesis = null, $idevaluacion = null)
	{
		// Verificamos si tiene permiso
		//if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		if($idanamnesis!=null){
			$diseniosLunas = $this->db->query("SELECT DISTINCT disenio FROM precios_lunas")->result();
			$anamnesis = $this->db->where("id_anamnesis", $idanamnesis)->get("anamnesis")->row();
			
			$paciente = $this->db->where("id", $anamnesis->id_cliente)->get("cliente")->row();

			$this->load->view('header');
			$this->load->view('anamnesis/veranamnesis', array("anamnesis" => $anamnesis, "paciente" => $paciente, "disenio" => $diseniosLunas));
			$this->load->view('footer');
		}		
	}

	//Funcion que lista todas las anamnesis y permite filtrar
	public function allAnamnesis(){
		$usuarios = $this->db->get("usuario")->result();

		if(!$_POST){

			$this->db->select('a.fecha_anamnesis as fecha, c.Nombre as NombreCliente, u.Nombre as NombreUsuario, a.estado, a.id_cliente as idcli');
			$this->db->from('anamnesis a');
			$this->db->join('cliente c', 'a.id_cliente = c.id');
			$this->db->join('usuario u', 'u.id = a.id_usuario');
			$datos = $this->db->get()->result();
		}else{
			$this->db->select('a.fecha_anamnesis as fecha, c.Nombre as NombreCliente, u.Nombre as NombreUsuario, a.estado, a.id_cliente as idcli');
			$this->db->from('anamnesis a');
			$this->db->join('cliente c', 'a.id_cliente = c.id');
			$this->db->join('usuario u', 'u.id = a.id_usuario');
			if(isset($_POST["usuario"]) && $_POST["usuario"]!="0"){
				$this->db->where("a.id_usuario", $_POST["usuario"]);
			}
			if(isset($_POST["estado"]) && $_POST["estado"]!="0"){
				$this->db->where("a.estado", $_POST["estado"]);
			}
			if(isset($_POST["fecha_inicio"]) && $_POST["fecha_inicio"]!="0" && isset($_POST["fecha_fin"]) && $_POST["fecha_fin"]!="0"){
				$this->db->where('DATE(a.fecha_anamnesis) BETWEEN "'.$_POST["fecha_inicio"].'" AND "'.$_POST["fecha_fin"].'"', '',false);
			}
			$datos = $this->db->get()->result();
		}
		//print_r($this->db->last_query()); 
		$datospost = (isset($_POST)) ? $_POST:null;
		$this->load->view('header');
		$this->load->view('objetivos/verAnamnesis', array("datos" => $datos, "usuarios" => $usuarios, "datospost" => $datospost));
		$this->load->view('footer');
	}

	//Función que guarda todas las anamnesis
	public function addanamnesis(){
		if(empty($_POST["id_cliente"])){
			$datains = array(
				"Dni" => $_POST["dni"],
				"Nombre" => $_POST["nombre"],
				"Direccion" => $_POST["direccion"],
				"Correo" => $_POST["correo_electronico"],
				"fecha_nac" => $_POST["fecha_nacimiento"],
				"Telefono1" => $_POST["telefono"],
				"trabajo" => $_POST["trabajo"],
				"Empresa_id" => $this->user->Empresa_id,
				"id_rubro_trabajo" => $_POST["id_rubro_trabajo"],
				"id_profesion" => $_POST["id_profesion"],
				"id_ciudad" => $_POST["id_ciudad"],
				"id_distrito" => $_POST["id_distrito"],
				"sexo" => $_POST["sexo"],
				"tipo_doc" => $_POST["tipo_doc"],
			);

			$nuevocli = $this->db->insert("cliente", $datains);
			if(!$nuevocli){
				echo json_encode(array("result" => "false", "message" => "Error al guardar cliente, comuníquese con el administrador."));
				return false;
			}
			$idclie = $this->db->insert_id();
			
		}else{
			$datains = array(
				"Dni" => $_POST["dni"],
				"Nombre" => $_POST["nombre"],
				"Direccion" => $_POST["direccion"],
				"Correo" => $_POST["correo_electronico"],
				"fecha_nac" => $_POST["fecha_nacimiento"],
				"Telefono1" => $_POST["telefono"],
				"trabajo" => $_POST["trabajo"],
				"id_rubro_trabajo" => $_POST["id_rubro_trabajo"],
				"id_profesion" => $_POST["id_profesion"],
				"id_ciudad" => $_POST["id_ciudad"],
				"id_distrito" => $_POST["id_distrito"],
				"sexo" => $_POST["sexo"],
				"tipo_doc" => $_POST["tipo_doc"],
			);
			$this->db->where("id", $_POST["id_cliente"])->update("cliente", $datains);
			$idclie = $_POST["id_cliente"];
		}

		$anamdata = array(
			"id_cliente" => $idclie,
			"Empresa_id" => $this->user->Empresa_id,
			"id_usuario" => $this->user->id,
			"antecedentes_padre" => $_POST["antecedentes_padre"],
			"antecedentes_madre" => $_POST["antecedentes_madre"],
			"ojo_seco" => isset($_POST["ojo_seco"]) ? $_POST["ojo_seco"]:"0",
			"estrabismo" => isset($_POST["estrabismo"]) ? $_POST["estrabismo"]:"0",
			"fatiga_visual" => isset($_POST["fatiga_visual"]) ? $_POST["fatiga_visual"]:"0",
			"dolor_cabeza" => isset($_POST["dolor_cabeza"]) ? $_POST["dolor_cabeza"]:"0",
			"diabetes" => isset($_POST["diabetes"]) ? $_POST["diabetes"]:"0",
			"ojos_rojos" => isset($_POST["ojos_rojos"]) ? $_POST["ojos_rojos"]:"0",
			"leganas" => isset($_POST["leganas"]) ? $_POST["leganas"]:"0",
			"mareos" => isset($_POST["mareos"]) ? $_POST["mareos"]:"0",
			"daltonismo" => isset($_POST["daltonismo"]) ? $_POST["daltonismo"]:"0",
			"degeneracion_macular" => isset($_POST["degeneracion_macular"]) ? $_POST["degeneracion_macular"]:"0",
			"reumatismo" => isset($_POST["reumatismo"]) ? $_POST["reumatismo"]:"0",
			"mecanismos" => isset($_POST["mecanismos"]) ? $_POST["mecanismos"]:"0",
			"frecuencia" => isset($_POST["frecuencia"]) ? $_POST["frecuencia"]:"0",
			"id_clinica" => isset($_POST["id_clinica"]) ? $_POST["id_clinica"]:"0",
			"id_doctor" => isset($_POST["id_doctor"]) ? $_POST["id_doctor"]:"0",
			"id_empresa_conv" => isset($_POST["id_empresa_conv"]) ? $_POST["id_empresa_conv"]:"0",
			"id_rubro_trabajo" => isset($_POST["id_rubro_trabajo"]) ? $_POST["id_rubro_trabajo"]:"0",
			"id_profesion" => isset($_POST["id_profesion"]) ? $_POST["id_profesion"]:"0",
		);

		$nuevanamnesis = $this->db->insert("anamnesis", $anamdata);
		if(!$nuevanamnesis){
			echo json_encode(array("result" => "false", "message" => "Error al guardar anamnesis, comuníquese con el administrador."));
			return false;
		}
		$idanamnesis = $this->db->insert_id();
		echo json_encode(array("result" => "ok", "id_anamnesis" => $idanamnesis, "id_cliente" => $idclie));
	}

	public function guardarEvaluacion(){
		/*echo "<pre>";
		var_dump($_POST);
		echo "</pre>";*/
		$idcli = $_POST["id_clie"];
		$idanam = $_POST["id_anamnesis"];
		$prox = date('Y-m-d', strtotime('+1 years'));
		$this->db->insert("evaluaciones", array("id_cliente" => $idcli, "proxima_revision" => $prox, "id_empresa" => $this->user->Empresa_id, "id_usuario" => $this->user->id, "id_anamnesis" => $idanam));
		$ideval = $this->db->insert_id();
		$data = $_POST["data"];
		foreach($data as $d){
			if(!empty($d["value"])){
				$ins = array(
					"meta_key"		=>	$d["name"],
					"meta_value"	=>	$d["value"],
					"id_evaluacion"	=>  $ideval
				);
				$this->db->insert("evaluaciones_meta", $ins);
			}

		}
		echo json_encode(array("result" => "ok", "id_evaluacion" => $ideval));
	}

	public function guardarOrdenlab(){
		/*echo "<pre>";
		var_dump($_POST);
		echo "</pre>";*/
		$id_eval = $_POST["id_eval"];
		$nro_orden = $_POST["nro_orden"];

		$fece = explode("|", $_POST["fecha_entrega"]);
		$fecha_e = explode("/", $fece[0]);
		$fecha_entrega = date("Y-m-d H:i:s", strtotime($fecha_e[2]."-".$fecha_e[1]."-".$fecha_e[0]." ".$fece[1].":00"));
		$proyectado = $_POST["precio_compra"];
		$id_cli = $_POST["id_clie"];
		$this->db->insert("orden_lab", array("id_evaluacion" => $id_eval, "nro_orden" => $nro_orden, "Empresa_id" => $this->user->Empresa_id, "id_usuario" => $this->user->id, "fecha_entrega" => $fecha_entrega, "monto_compra_proyectado" => $proyectado, "id_cliente" => $id_cli, "id_anamnesis" => $_POST["id_anamnesis"], "observaciones" => $_POST["observaciones"]));
		$idord = $this->db->insert_id();
		$data = $_POST["data"];

		$eval = $this->db->where("id_evaluacion", $id_eval)->get("evaluaciones_meta")->result();

		foreach($eval as $e){
			$ins1 = array(
					"meta_key"		=>	$e->meta_key,
					"meta_value"	=>	$e->meta_value,
					"id_orden"		=>  $idord
				);
				$this->db->insert("orden_lab_meta", $ins1);
		}

		foreach($data as $d){
			if(!empty($d["value"])){
				$ins = array(
					"meta_key"		=>	$d["name"],
					"meta_value"	=>	$d["value"],
					"id_orden"		=>  $idord
				);
				$this->db->insert("orden_lab_meta", $ins);
			}

		}

		if($data[2]["name"] == "montura_paciente" && isset($data[2]["value"])){
			$datos_conformidad = array(
				"datos_montura" => $data[2]["value"],
				"id_orden_lab"  => $idord,
				"id_cliente"	=> $id_cli,
				"id_usuario"	=> $this->user->id
			);
			$this->db->insert("conformidad_monturas", $datos_conformidad);
		}

		echo json_encode(array("result" => "ok", "id_orden" => $idord));
	}

	public function buscarUltReceta(){
		$id_cliente = $_POST["idcliente"];

		$retorno = array();

		if($id_cliente != null){
			$datos = $this->db->where("id_cliente", $id_cliente)->order_by('fecha', 'DESC')->get("evaluaciones")->row();
			$datos->fecha = date("d/m/Y", strtotime($datos->fecha));
			$retorno["datos_eval"] = $datos;

			$meta = $this->db->where("id_evaluacion", $datos->id_evaluacion)->get("evaluaciones_meta")->result();

			$retorno["meta_eval"] = $meta;

			echo json_encode($retorno);
		}

		return false;
	}
}
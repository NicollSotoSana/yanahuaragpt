<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Encuesta extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('clientemodel', 'climodel');
		$this->load->model('usuariomodel', 'um');
		$this->load->model('cajamodel', 'cajam');
	}
	public function nuevaEncuesta($idcli = null, $anamnesis=0)
	{	
		$anamcount = $this->db->where("id_anamnesis", $anamnesis)->get("encuestas");
		if($anamcount->num_rows() == 0){ 
			$step 	 = 1;
		}else{
			$step 	 = 2;
			if($anamcount->row()->estado == 1){
				redirect('/');
			}
		}
		
		$cliente = $idcli != null ? $this->climodel->Obtener($idcli):null;
		$preguntas = $this->getQuest($step);
		$this->load->view('header');
		$this->load->view('encuesta/index', array("preguntas" => $preguntas, "cliente" => $idcli, "anamnesis" => $anamnesis, "step" => $step));
		$this->load->view('footer');
	}

	private function getQuest($step=1){
		$retorno = array();
		if($step==1){
			$cats = $this->db->where("id_categoria_encuesta !=", 4)->get("encuestas_categorias")->result();
		}else{
			$cats = $this->db->where("id_categoria_encuesta", 4)->get("encuestas_categorias")->result();
		}
		
		foreach($cats as $c){
			$pregs = $this->db->where("id_categoria", $c->id_categoria_encuesta)->get("encuestas_preguntas")->result();
			$preguntas = array();

			foreach($pregs as $p){
				$preguntas[] = array("id_pregunta" => $p->id_pregunta, "pregunta" => $p->pregunta);
			}

			$retorno[] = array(
				"id_categoria" => $c->id_categoria_encuesta, 
				"nombre_cat" => $c->categoria,
				"preguntas"	 => $preguntas
			);
		}

		return $retorno;
	}

	public function saveAnswers(){
		$id_cliente = $_POST["id_cli"];
		$id_anam = $_POST["id_anam"];
		$step = $_POST["step"];
		unset($_POST["id_cli"]);
		unset($_POST["id_anam"]);
		unset($_POST["step"]);

		$tsi = 0;
		$tno = 0;

		if($step == 1){
			$da = array("id_cliente" => $id_cliente, "id_anamnesis" => $id_anam, "fecha" => date('Y-m-d'), "Empresa_id" => $this->user->Empresa_id, "id_usuario" => $this->user->id);
			$this->db->insert("encuestas", $da);

			$idenc = $this->db->insert_id();
		}else{
			$idenc = $this->db->where("id_cliente", $id_cliente)->where("id_anamnesis", $id_anam)->get("encuestas")->row()->id_encuesta;
			
		}
		

		foreach($_POST as $key => $value){
			
			if($key!="pregunta_sat" && $key!="sugerencia"){
				if($value == 1){ $tsi++;}elseif($value == 0){ $tno++;}

				$par = explode("_", $key);
				$inse = array("id_encuesta" => $idenc, "id_pregunta" => $par[1], "respuesta" => $value, "fecha" => date('Y-m-d'));
				$this->db->insert("encuestas_respuestas", $inse);
			}
		}

		if($step == 1){
			//$this->db->where("id_encuesta", $idenc)->update("encuestas", array("cant_si" => $tsi, "cant_no" => $tno));
			$this->db->set('cant_si', 'cant_si+'.$tsi, FALSE);
			$this->db->set('cant_no', 'cant_no+'.$tno, FALSE);
			$this->db->where('id_encuesta', $idenc);
			$this->db->update('encuestas');
		}else{
			//$this->db->where("id_encuesta", $idenc)->update("encuestas", array("cant_si" => $tsi, "cant_no" => $tno, "estado" => 1));
			$total_satis =  isset($_POST["pregunta_sat"]) ? $_POST["pregunta_sat"]:0;
			$sugerencia  =  isset($_POST["sugerencia"]) ? $_POST["sugerencia"]:"";
			$this->db->set('cant_si', 'cant_si+'.$tsi, FALSE);
			$this->db->set('cant_no', 'cant_no+'.$tno, FALSE);
			$this->db->set('nivel_satisfac', $total_satis);
			$this->db->set('sugerencia', $sugerencia);
			$this->db->set('estado', 1);
			$this->db->where('id_encuesta', $idenc);
			$this->db->update('encuestas');

			/* Enviamos Email con codigo dscto. */
			$cliename = $this->db->select('Nombre, Correo')->where("id", $id_cliente)->get("cliente")->row();
			$cod = strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 8));
			
			$checkcod = $this->db->where("codigo_cupon", $cod)->get("cupones")->num_rows();

			if($checkcod == 0){
				$this->sendEmail($cliename->Nombre, $cod, $cliename->Correo);
			}else{
				$cod = strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 8));
				$this->sendEmail($cliename->Nombre, $cod, $cliename->Correo);
			}

			$vcto = date('Y-m-d', strtotime('+1 month'));
			$this->db->insert("cupones", array("id_cliente" => $id_cliente, "codigo_cupon" => $cod, "estado" => 1, "fecha_vcto" => $vcto, "id_encuesta" => $idenc));

			$this->db->where("id_anamnesis", $id_anam)->update("orden_lab", array("id_estado_orden" => 3));

			$orden_lab = $this->db->select('id_orden')->where("id_anamnesis", $id_anam)->get("orden_lab")->row();

			$this->db->insert("orden_lab_historial", array("id_estado" => 3, "id_orden_lab" => $orden_lab->id_orden));
		}

		$this->load->view('header');
		$this->load->view('encuesta/finEncuesta');
		$this->load->view('footer');
	}

	public function sendEmail($name, $codigo, $email=null){
		$config['protocol'] = "smtp";
		$config['smtp_host'] = "mail.guillentamayo.com";
		$config['smtp_port'] = "587";
		$config['smtp_user'] = "atencionalcliente@guillentamayo.com";
		$config['smtp_pass'] = "Optica.2023";
		$config['mailtype'] = "html";

		if($email!=null && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){
			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");
			$this->email->from("atencionalcliente@guillentamayo.com", "Centro Óptico Guillen Tamayo");
			$this->email->to(array($email, 'atencionalcliente@guillentamayo.com'));
			$this->email->subject("Cupón 20% Dcto.");

			$data = array("nombre" => $name, "codigo" => $codigo);
			$message = $this->load->view('email/cupon',$data,TRUE);

			$this->email->message($message);
			$this->email->send();  
		}
		
	}

	public function verEncuesta($id_cliente, $id_anamnesis){
		$data = array();
		$enc = $this->db->where("id_cliente", $id_cliente)->where("id_anamnesis", $id_anamnesis)->get("encuestas")->row();
		if(isset($enc->id_encuesta)){
			$resp = $this->db->where("id_encuesta", $enc->id_encuesta)->get("encuestas_respuestas")->result();
			$i = 0;
			foreach($resp as $r){
				$preg = $this->db->where("id_pregunta", $r->id_pregunta)->get("encuestas_preguntas")->row();
				$data[$i]["id_pregunta"] = $preg->id_pregunta;
				$data[$i]["pregunta"] = $preg->pregunta;
				$data[$i]["respuesta"] = $r->respuesta;
				$i++;
			}
		}else{
			$data = null;
		}


		$this->load->view('header');
		$this->load->view('encuesta/verEncuesta', array("datos" => $data));
		$this->load->view('footer');
	}
}
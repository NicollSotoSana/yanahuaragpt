<?php
class Asistenciamodel extends CI_Model
{
	public function getTipos(){
		return $this->db->get("asistencia_tipo")->result();
	}
	public function saveAsistencia($datos){
		$data = array(
			"id_usuario" => $this->user->id,
			"dni"		 => $datos["dni"],
			"id_tipo"	 => $datos["tipo"]
		);
		$this->db->insert("asistencias", $data);
	}
}
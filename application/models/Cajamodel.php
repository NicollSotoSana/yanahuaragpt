<?php
class Cajamodel extends CI_Model
{
	public function guardarCaja($datos){
		$ins = array(
			"id_usuario" 			=> $this->user->id,
			"id_empresa" 			=> $this->user->Empresa_id,
			"monto_inicial" 		=> $datos["monto_inicial"],
			"monto_cb" 				=> $datos["monto_cb"],
			"fecha_hora_apertura"	=> date('Y-m-d H:i:s')
		);
		$this->db->insert("caja", $ins);

	}

	public function getIngresosComp($mediopago){
		$fecha2 		= date("Y/m/d");
		//return $this->db->query("SELECT IFNULL(SUM(CASE WHEN adelanto = '0.00' THEN Total ELSE adelanto END), 0.00) as total FROM comprobante WHERE FechaEmitido='".$fecha2."' and Empresa_id='".$this->user->Empresa_id."' and mediopago='".$mediopago."' and (ComprobanteTipo_id='3' || ComprobanteTipo_id='2' || ComprobanteTipo_id='4') AND Estado='2' AND gratuita='0'")->row_array();

        return $this->db->query("SELECT IFNULL(SUM(CASE WHEN deuda_generada = '1' THEN adelanto ELSE Total END), 0.00) as total FROM comprobante WHERE FechaEmitido='".$fecha2."' and Empresa_id='".$this->user->Empresa_id."' and mediopago='".$mediopago."' and (ComprobanteTipo_id='3' || ComprobanteTipo_id='2' || ComprobanteTipo_id='4') AND Estado='2' AND gratuita='0'")->row_array();
	}

	public function getIngresosCompRep($mediopago, $fecha2){
		$fecha = date("Y/m/d", strtotime($fecha2));
        return $this->db->query("SELECT IFNULL(SUM(CASE WHEN deuda_generada = '1' THEN adelanto ELSE Total END), 0.00) as total FROM comprobante WHERE FechaEmitido='".$fecha."' and Empresa_id='".$this->user->Empresa_id."' and mediopago='".$mediopago."' and (ComprobanteTipo_id='3' || ComprobanteTipo_id='2' || ComprobanteTipo_id='4') AND Estado='2' AND gratuita='0'")->row_array();
	}

	public function getIngresosDeud($mediopago){
		$fecha2 		= date("Y/m/d");
		return $this->db->query("SELECT IFNULL(SUM(monto_pagado), 0.00) as total FROM deudas_pagos WHERE fecha='".$fecha2."' and id_empresa='".$this->user->Empresa_id."' and medio_pago='".$mediopago."'")->row_array();
	}

	public function getIngresosDeudRep($mediopago, $fecha2){
		$fecha = date("Y/m/d", strtotime($fecha2));
		return $this->db->query("SELECT IFNULL(SUM(monto_pagado), 0.00) as total FROM deudas_pagos WHERE fecha='".$fecha."' and id_empresa='".$this->user->Empresa_id."' and medio_pago='".$mediopago."'")->row_array();
	}

	public function checkCaja(){
		return $this->db->where("DATE(fecha_hora_apertura)", date('Y-m-d'))->get("caja")->num_rows();
	}

	public function getCaja(){
		return $this->db->where("DATE(fecha_hora_apertura)", date('Y-m-d'))->get("caja")->row()->monto_inicial;
	}

	public function getCajaReporte($fecha){
		return $this->db->where("DATE(fecha_hora_apertura)", $fecha)->get("caja")->row()->monto_inicial;
	}

	public function cajaTodo(){
		return $this->db->where("DATE(fecha_hora_apertura)", date('Y-m-d'))->get("caja")->row();
	}

	public function cajaTodoFec($fecha){
		return $this->db->where("DATE(fecha_hora_apertura)", $fecha)->get("caja")->row();
	}
}
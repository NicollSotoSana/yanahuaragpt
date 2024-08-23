<?php
class DepositosModel extends CI_Model
{
	public function Listar()
	{
		$where  = "Empresa_id = " . $this->user->Empresa_id . ' ';;
		
		$this->filter = isset($_REQUEST['filters']) ? json_decode($_REQUEST['filters']) : null;

		if($this->filter != null)
		{
			foreach($this->filter->{'rules'} as $f)
			{
				if($f->field == 'Nombre') $where .= "AND Nombre LIKE '" . $f->data . "%' ";
				if($f->field == 'Identidad') $where .= "AND Identidad LIKE '" . $f->data . "%' ";
			}
		}

		$this->db->where($where);
		$this->jqgridmodel->Config($this->db->SELECT('COUNT(*) Total FROM depositos')->get()->row()->Total);
		
		$sql = "
			SELECT * 
			FROM depositos
			WHERE $where
			ORDER BY " . $this->jqgridmodel->sord . "
			LIMIT " . $this->jqgridmodel->start . "," . $this->jqgridmodel->limit;

		$this->db->where($where);
		$this->jqgridmodel->DataSource($this->db->query($sql)->result());
			
		return $this->jqgridmodel;
	}

	public function getDetallePagos($id){
		$this->db->where("id_compra", $id);
		return $this->db->get("depositos")->result();
	}

	public function Guardar($data){
		$datos = array(
			"banco"		    =>  $data["banco"],
			"monto"		    =>  $data["monto"],
			"descripcion"   =>  $data["descripcion"],
			"fecha"		    =>  $data["fecha"],
			"Empresa_id"    =>  $this->user->Empresa_id,
			"id_compra"     =>  (isset($data["id_compra"])) ? $data["id_compra"]:null,
			"id_usuario"    =>  $this->user->id,
			"nro_operacion" =>  (isset($data["operacion"])) ? $data["operacion"]:null,
			"origen_dinero" =>	$data["origen_dinero"]
		);

		if(isset($data["id_compra"])){
			$this->db->where('id_compra', $data["id_compra"]);
			$this->db->set('monto_cancelado', 'monto_cancelado+' . $data['monto'], false);
			$this->db->update('compras');
		}

		$this->db->insert('depositos', $datos);
		$this->responsemodel->SetResponse(true);
		if(!isset($data["id_compra"])){
			$this->responsemodel->href   = 'depositos/';
		}else{
			$this->responsemodel->href   = 'almacen/nuevaCompra/0/'.$data["id_compra"];
		}
		
		//return 0;
		return $this->responsemodel;
	}
}
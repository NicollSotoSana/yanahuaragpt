<?php
class Clientemodel extends CI_Model
{
	public function Actualizar($data)
	{
 		$this->db->trans_start();
 		
 		$data['Correo'] = strtolower($data['Correo']);
 		
		$id = $data['id'];
		
		$validacion = true;
		if($data['Ruc'] != '')
		{
			if(!isRuc($data['Ruc']))
			{
				$validacion = false;
				$this->responsemodel->message = 'El RUC ingresado no es válido.';					
			}
			else if($data['Direccion'] == '')
			{
				$validacion = false;
				$this->responsemodel->message = 'Un cliente con RUC debe tener obligatoramiente una dirección.';						
			}
			else if($this->db->query("SELECT COUNT(*) Total FROM cliente WHERE Empresa_id = " . $this->user->Empresa_id . " AND id != $id AND Ruc = '" . $data['Ruc'] . "'")->row()->Total > 0)
			{
				/*$validacion = false;
				$this->responsemodel->message = 'Ya existe un cliente con este RUC.';*/
			}
		}
		if($data['Dni'] != '')
		{
			if(!isDni($data['Dni']))
			{
				$validacion = false;
				$this->responsemodel->message = 'El DNI ingresado no es válido.';					
			}
			else if ($this->db->query("SELECT COUNT(*) Total FROM cliente WHERE Empresa_id = " . $this->user->Empresa_id . " AND id != $id AND Dni = '" . $data['Dni'] . "'")->row()->Total > 0)
			{
				/*$validacion = false;
				$this->responsemodel->message = 'Ya existe un cliente con este DNI.';*/					
			}
		}			
		if($validacion)
		{

			$this->db->where('id', $data['id']);
			$this->db->update('cliente', $data);
			
			$this->responsemodel->SetResponse(true);				
		}
		
		$this->db->trans_complete();
		 
		if ($this->db->trans_status() === FALSE)
		{
			log_message('1', __CLASS__ . '->' . __METHOD__);
			$this->responsemodel->SetResponse(false);	
		}
				
 		return $this->responsemodel;
	}
	public function Registrar($data)
	{
		$this->db->trans_start();
		
		$data['Correo'] = strtolower($data['Correo']);
		
		$validacion = true;
		if($data['Ruc'] != '')
		{
			if(!isRuc($data['Ruc']))
			{
				$validacion = false;
				$this->responsemodel->message = 'El RUC ingresado no es válido.';					
			}
			else if($data['Direccion'] == '')
			{
				$validacion = false;
				$this->responsemodel->message = 'Un cliente con RUC debe tener obligatoramiente una dirección.';						
			}
			else if($this->db->query("SELECT COUNT(*) Total FROM cliente WHERE Empresa_id = " . $this->user->Empresa_id . " AND Ruc = '" . $data['Ruc'] . "'")->row()->Total > 0)
			{
				$validacion = false;
				$this->responsemodel->message = 'Ya existe un cliente con este RUC.';
			}
		}
		if($data['Dni'] != '')
		{
			if(!isDni($data['Dni']))
			{
				$validacion = false;
				$this->responsemodel->message = 'El DNI ingresado no es válido.';					
			}
			else if ($this->db->query("SELECT COUNT(*) Total FROM cliente WHERE Empresa_id = " . $this->user->Empresa_id . " AND Dni = '" . $data['Dni'] . "'")->row()->Total > 0)
			{
				$validacion = false;
				$this->responsemodel->message = 'Ya existe un cliente con este DNI.';				
			}
		}
		if($validacion)
		{
			$data['Empresa_id'] = $this->user->Empresa_id;
			$this->db->insert('cliente', $data);
			
			$this->responsemodel->SetResponse(true);
			$this->responsemodel->href   = 'mantenimiento/cliente/' . $this->db->insert_id();				
		}
		
		$this->db->trans_complete();
		 
		if ($this->db->trans_status() === FALSE)
		{
			log_message('1', __CLASS__ . '->' . __METHOD__);
			$this->responsemodel->SetResponse(false);	
		}
		
		return $this->responsemodel;
	}
	public function upd_Comp($datos)
    {
    	$this->db->trans_start();
    	if(!empty($datos['Ruc'])){

    		$idec = $datos['Ruc'];

    	}else{
    		$idec = $datos['Dni'];
    	}
        $dataos = array(
            'ClienteIdentidad' => $idec,
            'ClienteNombre' => $datos['Nombre'],
            'ClienteDireccion' => $datos['Direccion']
        );
        $this->db->where('Cliente_id', $datos['id']);
		$this->db->update('comprobante', $dataos); 

        $this->db->trans_complete();
        
		return 0;
    }
	public function Obtener($id)
	{
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $id);
		return $this->db->get('cliente')->row();
	}
	public function Eliminar($id)
	{
		if($this->db->query("SELECT COUNT(*) Total FROM comprobante WHERE cliente_id = $id")->row()->Total > 0)
		{
			$this->responsemodel->SetResponse(false, 'Este <b>registro</b> no puede ser eliminado.');
		}
		else
		{
			$this->db->where('Empresa_id', $this->user->Empresa_id);
			$this->db->where('id', $id);
			$this->db->delete('cliente');
			
			$this->responsemodel->SetResponse(true);
			$this->responsemodel->href = 'mantenimiento/clientes/';
		}
		
		return $this->responsemodel;
	}
	public function Listar()
	{
		$where  = "cliente.Empresa_id = " . $this->user->Empresa_id . ' ';
		
		$this->filter = isset($_REQUEST['filters']) ? json_decode($_REQUEST['filters']) : null;

		if($this->filter != null)
		{
			foreach($this->filter->{'rules'} as $f)
			{
				if($f->field == 'Nombre') $where .= "AND Nombre LIKE '%" . $f->data . "%' ";
				if($f->field == 'Identidad') $where .= "AND (Dni = '" . $f->data . "' OR Ruc = '" . $f->data . "') ";
				if($f->field == 'deuda'){
					/*if($f->data==0){
						$where .= "AND (deuda='null' or (monto_deuda-monto_cancelado)='0')";
					}elseif($f->data==1){
						$where .= "AND (monto_deuda-monto_cancelado)>1";
					}*/
					if($f->data==1){
						$where .= "AND (monto_deuda-monto_cancelado)>1";
					}
				}
			}
			
		}

		$config = "
		SELECT *,cliente.id as idcli,
		IF(Ruc = '', Dni, Ruc) AS Identidad,
		de.Empresa_id as empid,
		cliente.Empresa_id as cliempid,
		IFNULL(SUM(de.monto_deuda)-SUM(de.monto_cancelado), 0) as deuda FROM cliente
		LEFT JOIN deudas de ON
		de.id_cliente = cliente.id 
		LEFT JOIN comprobante cmp ON
		cmp.id = de.comprobante_id
		WHERE $where 
		GROUP BY idcli";

		$this->jqgridmodel->Config($this->db->query($config)->num_rows());

		/*$this->db->where($where);
		$this->jqgridmodel->Config($this->db->query('SELECT COUNT(*) Total FROM cliente')->row()->Total);*/
		
		$sql = "
		SELECT 
			*,cliente.id as idcli,
			IF(Ruc = '', Dni, Ruc) AS Identidad,
			de.Empresa_id as empid,
			cliente.Empresa_id as cliempid,
			IFNULL(SUM(de.monto_deuda)-SUM(de.monto_cancelado), 0) as deuda
		FROM cliente
		LEFT JOIN deudas de ON
		de.id_cliente = cliente.id 
		LEFT JOIN comprobante cmp ON
		cmp.id = de.comprobante_id
		WHERE $where 
		GROUP BY idcli
		ORDER BY " . $this->jqgridmodel->sord . "
		LIMIT " . $this->jqgridmodel->start . "," . $this->jqgridmodel->limit;

		$this->db->where($where);
		$this->jqgridmodel->DataSource($this->db->query($sql)->result());
			
		return $this->jqgridmodel;
	}
	public function Buscar($criterio, $tipo=0)
	{
		// 1 Tiene Ruc 2 Solo Dni
		//$select = "*, IF(Ruc = '', Dni, Ruc) AS Identidad";
		$select = "*, IFNULL(Ruc, Dni) AS Identidad";

		if($tipo == '3') $select = '*, Ruc Identidad';
		if($tipo == '2') $select = '*, Dni Identidad';
		if($tipo == '4') $select = '*, Dni Identidad';
		
		$sql = "
			SELECT $select FROM cliente
			WHERE Nombre LIKE '%$criterio%'
			AND Empresa_id = " . $this->user->Empresa_id . "
			ORDER BY Nombre
			LIMIT 10
		";		

		return $this->db->query($sql)->result();
	}
	public function recetas($criterio)
	{
		$sql = "
			SELECT DISTINCT meta_value FROM evaluaciones_meta
			WHERE meta_value LIKE '%$criterio%'
			AND meta_key = 'receta'
			ORDER BY meta_value
			LIMIT 10
		";		

		return $this->db->query($sql)->result();
	}
	/* Evaluaciones */

	public function guardarEvaluacion($data){

		$idcli = $data["id_clie"];
		$prox = date('Y-m-d', strtotime('+1 years'));
		$this->db->insert("evaluaciones", array("id_cliente" => $idcli, "proxima_revision" => $prox, "id_empresa" => $this->user->Empresa_id));
		$ideval = $this->db->insert_id();
		foreach($data as $k => $v){
			$ins = array(
				"meta_key"		=>	$k,
				"meta_value"	=>	$v,
				"id_evaluacion"	=>  $ideval
			);
			$this->db->insert("evaluaciones_meta", $ins);
		}
		$this->responsemodel->SetResponse(true);
		$this->responsemodel->href = 'mantenimiento/evaluacion/'.$idcli.'/'.$ideval;
		return $this->responsemodel;
	}

	public function ObtenerEva($id)
	{
		/*$this->db->where('id_cliente', $id);
		return $this->db->get('evaluaciones')->result_array();*/
		$this->db->select('eva.id_evaluacion ideval, ol.id_orden idorden, eva.fecha fecha, eva.id_cliente idcli, eva.id_anamnesis id_anamnesis');
		$this->db->from('evaluaciones eva');
		$this->db->join('orden_lab ol', 'ol.id_evaluacion = eva.id_evaluacion', 'left');
		$this->db->where('eva.id_cliente', $id);
		$this->db->order_by("fecha", "desc");
		return $this->db->get()->result_array();
	}

	public function ObtenerCliOrdenLab($id){
		return $this->db->where("id_evaluacion", $id)->get("evaluaciones")->row_array();
	}


	/* Obtiene datos de evaluacion y retorna un array */
	
	public function ObtenerEval($ideval){
		$this->db->where('id_evaluacion', $ideval);
		$dat = $this->db->get('evaluaciones_meta')->result();
		$salida = array();
		foreach($dat as $va) {
			$salida[$va->meta_key] = $va->meta_value;
		}

		return $salida;
	}

	/* Orden de Laboratorio */

	public function guardarOrden($data){

		$id_eval = $data["id_eval"];
		$this->db->insert("orden_lab", array("id_evaluacion" => $id_eval, "id_estado_orden" => 1));
		$idorden = $this->db->insert_id();
		foreach($data as $k => $v){
			$ins = array(
				"meta_key"		=>	$k,
				"meta_value"	=>	$v,
				"id_orden"	=>  $idorden
			);
			$this->db->insert("orden_lab_meta", $ins);
		}

		$this->responsemodel->SetResponse(true);
		$this->responsemodel->href = 'mantenimiento/ordenLaboratorio/'.$id_eval.'/'.$idorden;
		return $this->responsemodel;
	}

	public function ObtenerOrden($idord){
		$this->db->where('id_orden', $idord);
		$dat = $this->db->get('orden_lab_meta')->result();
		$salida = array();
		foreach($dat as $va) {
			$salida[$va->meta_key] = $va->meta_value;
		}

		return $salida;
	}

	public function detalleOrden($idord){
		$this->db->where('id_orden', $idord);
		return $this->db->get('orden_lab')->result();
	}

	public function getCumple(){
		return $this->db->query("SELECT * FROM cliente WHERE MONTH(fecha_nac) = MONTH(NOW())")->result();
	}

	public function getNearEvals(){
		return $this->db->query("SELECT * FROM evaluaciones e INNER JOIN cliente c ON c.id = e.id_cliente WHERE MONTH(e.proxima_revision) = MONTH(NOW()) AND YEAR(e.proxima_revision) = YEAR(NOW())")->result();
	}

	public function getComprobantesClie($id){
		return $this->db->query("SELECT *, comp.id as idecmp, tbl.Nombre as tipocmp, tbl2.Nombre as estadocmp FROM comprobante comp INNER JOIN tabladato tbl ON comp.ComprobanteTipo_id = tbl.Value AND tbl.Relacion = 'comprobantetipo' INNER JOIN tabladato tbl2 ON comp.Estado = tbl2.Value AND tbl2.Relacion = 'comprobanteestado' WHERE comp.Cliente_id = '".$id."'")->result();
	}

	public function clienteComp($data){
		$dataruc = null;
		$datadni = null;

		if($data['tipo_doc'] == 1 || $data['tipo_doc'] == 2 || $data['tipo_doc'] == 3){
			$datadni = $data['nro_doc'];
			$cant = $this->db->where("Dni", $datadni)->get("cliente")->num_rows();

		}else if($data['tipo_doc'] == 4){
			$dataruc = $data['nro_doc'];
			$cant = $this->db->where("Ruc", $dataruc)->get("cliente")->num_rows();
		}

		/*if(!empty($data['Ruc'])){
			$cant = $this->db->where("Ruc", $data['Ruc'])->get("cliente")->num_rows();
		}

		if(!empty($data['Dni'])){
			$cant = $this->db->where("Dni", $data['Dni'])->get("cliente")->num_rows();
		}*/
		
		if($cant == 0){

			$datos = array(
				'Nombre' => str_replace('&', htmlentities("&"),$data['Nombre']),
				'Ruc'=> $dataruc,
				'Dni'=> $datadni,
				'Telefono1'=> $data['Telefono1'],
				'Direccion'=> $data['Direccion'],
				'distrito'=> $data['Distrito'],
				'provincia'=> $data['Ciudad'],
				'departamento'=> $data['Departamento'],
				'trabajo'=> $data['trabajo'],
				'Empresa_id'=>$this->user->Empresa_id,
				'tipo_doc' => $data['tipo_doc']
			);

			$this->db->insert('cliente', $datos);
		}
		
	}
}
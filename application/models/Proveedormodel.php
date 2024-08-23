<?php
class ProveedorModel extends CI_Model
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
			else if($this->db->query("SELECT COUNT(*) Total FROM proveedores WHERE Empresa_id = " . $this->user->Empresa_id . " AND id != $id AND Ruc = '" . $data['Ruc'] . "'")->row()->Total > 0)
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
			else if ($this->db->query("SELECT COUNT(*) Total FROM proveedores WHERE Empresa_id = " . $this->user->Empresa_id . " AND id != $id AND Dni = '" . $data['Dni'] . "'")->row()->Total > 0)
			{
				$validacion = false;
				$this->responsemodel->message = 'Ya existe un cliente con este DNI.';					
			}
		}			
		if($validacion)
		{
			$this->db->where('id', $data['id']);
			$this->db->update('proveedores', $data);
	
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
				$this->responsemodel->message = 'Un proveedor con RUC debe tener obligatoramiente una dirección.';						
			}else if($this->db->query("SELECT COUNT(*) Total FROM proveedores WHERE Empresa_id = " . $this->user->Empresa_id . " AND Ruc = '" . $data['Ruc'] . "'")->row()->Total > 0)
			{
				$validacion = false;
				$this->responsemodel->message = 'Ya existe un proveedor con este RUC.';
			}
		}
		if($data['Dni'] != '')
		{
			if(!isDni($data['Dni']))
			{
				$validacion = false;
				$this->responsemodel->message = 'El DNI ingresado no es válido.';					
			}
			else if($this->db->query("SELECT COUNT(*) Total FROM proveedores WHERE Empresa_id = " . $this->user->Empresa_id . " AND Dni = '" . $data['Dni'] . "'")->row()->Total > 0)
			{
				$validacion = false;
				$this->responsemodel->message = 'Ya existe un proveedor con este DNI.';					
			}
		}
		if($validacion)
		{
			$data['Empresa_id'] = $this->user->Empresa_id;
			$this->db->insert('proveedores', $data);
			
			$this->responsemodel->SetResponse(true);
			$this->responsemodel->href   = 'mantenimiento/proveedor/' . $this->db->insert_id();				
		}
		
		$this->db->trans_complete();
		 
		if ($this->db->trans_status() === FALSE)
		{
			log_message('1', __CLASS__ . '->' . __METHOD__);
			$this->responsemodel->SetResponse(false);	
		}
		
		return $this->responsemodel;
	}
	public function Obtener($id)
	{
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $id);
		return $this->db->get('proveedores')->row();
	}
	public function Eliminar($id)
	{
		if($this->db->query("SELECT COUNT(*) Total FROM compras WHERE id_proveedor = $id")->row()->Total > 0)
		{
			$this->responsemodel->SetResponse(false, 'Este <b>registro</b> no puede ser eliminado.');
		}
		else
		{
			$this->db->where('Empresa_id', $this->user->Empresa_id);
			$this->db->where('id', $id);
			$this->db->delete('proveedores');
			
			$this->responsemodel->SetResponse(true);
			$this->responsemodel->href = 'mantenimiento/proveedores/';
		}
		
		return $this->responsemodel;
	}
	public function Listar()
	{
		$where  = "p.Empresa_id = " . $this->user->Empresa_id . ' ';;
		
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
		$this->jqgridmodel->Config($this->db->SELECT('COUNT(*) Total FROM proveedores p')->get()->row()->Total);
		
		$sql = "
			SELECT 
				*,(SELECT SUM(c.monto)-SUM(c.monto_cancelado) FROM compras c WHERE c.id_proveedor = p.id GROUP BY c.id_proveedor) as deuda_sum,
				IF(Ruc = '', Dni, Ruc) AS Identidad
			FROM proveedores p
			WHERE $where
			ORDER BY " . $this->jqgridmodel->sord . "
			LIMIT " . $this->jqgridmodel->start . "," . $this->jqgridmodel->limit;

		$this->db->where($where);
		$this->jqgridmodel->DataSource($this->db->query($sql)->result());
			
		return $this->jqgridmodel;
	}
	public function Buscar($criterio, $tipo=0)
	{
		// 1 Tiene Ruc 2 Solo Dni
		$select = "*, IF(Ruc = '', Dni, Ruc) AS Identidad";
		
		if($tipo == '3') $select = '*, Ruc Identidad';
		if($tipo == '2') $select = '*, Dni Identidad';
		
		$sql = "
			SELECT $select FROM proveedores
			WHERE Nombre LIKE '%$criterio%'
			AND Empresa_id = " . $this->user->Empresa_id . "
			ORDER BY Nombre
			LIMIT 10
		";		

		return $this->db->query($sql)->result();
	}
	public function getDeudasProveedor($id){
		return $this->db->where('id_proveedor', $id)->get('compras')->result();
	}
}
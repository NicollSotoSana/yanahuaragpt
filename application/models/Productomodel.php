<?php

use Automattic\WooCommerce\Client;

class ProductoModel extends CI_Model
{
	public function HaSidoAsignada($id)
	{
		$sql = "
			SELECT COUNT(*) Total FROM comprobantedetalle WHERE producto_id = $id AND Tipo = 1
			UNION 
			SELECT COUNT(*) Total FROM almacen WHERE producto_id = $id			
		";

		return ($this->db->query($sql)->row()->Total > 0) ? true : false;
	}
	public function Actualizar($data)
	{
		$id = $data['id'];
		$FConfig = 	array(
			'upload_path'   => './uploads',
			'allowed_types' => 'gif|jpg|png|jpeg',
			'max_size'      => '4000',
			//'overwrite'     => true,
			'encrypt_name'  => true
		);
		$this->upload->initialize($FConfig);
		if(isset($_FILES['prdimage'])){
			if($this->upload->do_upload('prdimage')){
				$upd = array('upload_data' => $this->upload->data());
				$data["imagen"] = $upd["upload_data"]["file_name"];
				//$this->load->view('upload_success',$data);
			}else{
				$upd = array('error' => $this->upload->display_errors());
				//$this->load->view('file_view', $error);
			}
		}else{
			unset($data['prdimage']);
		}       

		//var_dump($upd);
		if(empty($data['codigo_prod'])){
			$data['codigo_prod'] = $this->productCode($data['nombre']);
		}
		if($this->HaSidoAsignada($id) && isset($data['Marca']))
		{
			$this->db->where('id', $data['id']);
			$this->db->where('Empresa_id', $this->user->Empresa_id);
			$p = $this->db->get('producto')->row();

			if($data['Marca'] != $p->Marca)
			{
				$this->responsemodel->SetResponse(false, 'No se puede cambiar la <b>Marca</b> a este producto porque ya ha sido asignada a otro registro.');
				return $this->responsemodel;
			}
			if($data['UnidadMedida_id'] != $p->UnidadMedida_id)
			{
				$this->responsemodel->SetResponse(false, 'No se puede cambiar la <b>Unidad de Medida</b> a este producto porque ya ha sido asignada a otro registro.');
				return $this->responsemodel;
			}
		}
		
		$this->db->where('id', $data['id']);
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->update('producto', $data);

		//$tienda = $this->updTienda($data['id']);

		//$this->set_barcode($data['codigo_prod']);
		$this->responsemodel->href = 'self';
		$this->responsemodel->SetResponse(true);
 		return $this->responsemodel;
	}
	public function Registrar($data)
	{
		
		/*$this->set_barcode($data['codigo_prod']);
		$FConfig = 	array(
			'upload_path'   => './uploads',
			'allowed_types' => 'gif|jpg|png|jpeg',
			'max_size'      => '4000',
			//'overwrite'     => true,
			'encrypt_name'  => true
		);
		$this->upload->initialize($FConfig);
		if(isset($_FILES['prdimage'])){
			if($this->upload->do_upload('prdimage'))
			{
				$upd = array('upload_data' => $this->upload->data());
				//$this->load->view('upload_success',$data);
				$data["imagen"] = $upd["upload_data"]["file_name"];
			}else{
				$upd = array('error' => $this->upload->display_errors());
				//$this->load->view('file_view', $error);
			}
		}else{
			unset($data['prdimage']);
		}*/

		//var_dump($data);
		//eliminamos campo de imagen
		unset($data['prdimage']);

		if(empty($data['Marca'])) $data['Marca'] = 'S/M';
		if(HasModule('stock'))
		{
			if(empty($data['Stock'])) $data['Stock'] = '0.00';
		}

		/*if(empty($data['codigo_prod'])){
			$data['codigo_prod'] = $this->productCode($data['nombre']);
		}*/

		//0001(orden)-10(mes)-20(año)-lap (iniciales marca)
		$anio = date("Y");
		$mes = date("m");

		$ultimo_prd = $this->db->where("YEAR(created_at)", $anio)->where("MONTH(created_at)", $mes)->get("producto")->num_rows();

		$ultimo_fin = $ultimo_prd + 1;

		$orden = str_pad($ultimo_fin, 4, '0', STR_PAD_LEFT);

		$iniciales = substr($data['Marca'], 0, 3);

		$codigo_prod = $orden."-".date("m")."-".date("Y")."-".$iniciales;
		
		$data['Empresa_id'] = $this->user->Empresa_id;
		$this->db->insert('producto', $data);
		$ids = $this->db->insert_id();

		///$this->db->where("id", $ids)->update("producto", array("codigo_prod" => $ids."-".$data['codigo_proveedor'], "Nombre" => $data['Marca']." ".$ids."-".$data['codigo_proveedor']));
		
		$this->db->where("id", $ids)->update("producto", array("codigo_prod" => $codigo_prod, "Nombre" => $data['Marca']." ".$codigo_prod));
		$this->db->insert('almacen', array(
			'Tipo'            => 4,
			'Usuario_id'      => $this->user->id,
			'Producto_id'     => $ids,
			'ProductoNombre'  => $data['nombre'],
			'UnidadMedida_id' => "UND",
			'Cantidad'        => $data['Stock'],
			'Fecha'           => date('Y/m/d'),
			'Empresa_id'      => $this->user->Empresa_id,
			'Precio'          => $data['PrecioCompra'],
			'codigo_prod'	  => $data['codigo_prod']
		));

		//$tienda = $this->registrarTienda($ids);
		
		//var_dump($tienda);
		$this->responsemodel->SetResponse(true);
		$this->responsemodel->href = 'mantenimiento/producto/'.$ids;
		
		return $this->responsemodel;
	}
	
	public function Obtener($id)
	{
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $id);
		$this->db->select("*, CONCAT('[', Marca, '] - ',Nombre) NombreCompleto", false);
		return $this->db->get('producto')->row();
	}
	public function Eliminar($id)
	{
		if($this->HaSidoAsignada($id))
		{
			$this->responsemodel->SetResponse(false, 'Este <b>registro</b> no puede ser eliminado.');
		}
		else
		{
			$this->db->where('Empresa_id', $this->user->Empresa_id);
			$this->db->where('id', $id);
			$this->db->delete('producto');
			
			$this->responsemodel->SetResponse(true);
			$this->responsemodel->href   = 'mantenimiento/productos/';
		}
	
		return $this->responsemodel;
	}
	public function Listar()
	{
		$where = 'Empresa_id = ' . $this->user->Empresa_id . ' ';;
		$this->filter = isset($_REQUEST['filters']) ? json_decode($_REQUEST['filters']) : null;

		if($this->filter != null)
		{
			foreach($this->filter->{'rules'} as $f)
			{
				if($f->field == 'id') $where .= "AND id = '" . $f->data . "' ";
				if($f->field == 'Nombre') $where .= "AND Nombre LIKE '%" . $f->data . "%' ";
				if($f->field == 'Marca')  $where .= "AND Marca LIKE '%" . $f->data . "%' ";
				if($f->field == 'UnidadMedida_id' && $f->data != 't')  $where .= "AND UnidadMedida_id = '" . $f->data . "' ";
				if($f->field == 'codigo_prod' && $f->data != 't')  $where .= "AND codigo_prod = '" . $f->data . "' ";
				if($f->field == 'codigo_varilla' && $f->data != 't')  $where .= "AND codigo_varilla = '" . $f->data . "' ";
			}
		}

		$this->db->where($where);
		$this->jqgridmodel->Config($this->db->SELECT('COUNT(*) Total FROM producto')->get()->row()->Total);
		
		$this->db->order_by($this->jqgridmodel->sord);
		$this->db->where($where);
		
		$productos = $this->db->get(
				'producto', 
				$this->jqgridmodel->limit, 
				$this->jqgridmodel->start)->result();
				
		foreach($productos as $p)
		{
			$p->{'MargenGanancia'} = MargenDeGanancia($p->Precio, $p->PrecioCompra);
		}
		
		$this->jqgridmodel->DataSource($productos);
			
		return $this->jqgridmodel;
	}

    public function Buscar($criterio, $servicios = false)
	{
		if(!$servicios)
		{
			$sql = "
				SELECT 
					*, CONCAT('[', Marca, '] - ',Nombre) Nombre,
					Nombre NombreSimple
				FROM producto
				WHERE Nombre LIKE '%$criterio%' OR codigo_prod LIKE '%$criterio%' OR codigo_varilla LIKE '%$criterio%'
				AND Empresa_id = " . $this->user->Empresa_id . "
				ORDER BY Nombre
				LIMIT 10
			";			
		}else
		{
			$sql = "
				SELECT * FROM (
						SELECT 
							id,UnidadMedida_id,codigo_prod,
							PrecioCompra,Precio,Marca, CostoBase,
							CONCAT('[', Marca, '] - ',Nombre) Nombre,
							Stock, 1 Tipo
						FROM producto
						WHERE Empresa_id = " . $this->user->Empresa_id . "
						UNION
						SELECT 
							id,UnidadMedida_id,codigo_prod,
							PrecioCompra,Precio,'' Marca, 'CostoBase',
							CONCAT('', Nombre) Nombre,
							0 Stock, 2 Tipo
						FROM servicio
						WHERE Empresa_id = " . $this->user->Empresa_id . "
				) alias
				WHERE Nombre LIKE '%$criterio%' OR codigo_prod LIKE '%$criterio%'
				ORDER BY Nombre
				LIMIT 10
			";
		}
		return $this->db->query($sql)->result();
	}
	
	public function BuscarStock($criterio, $servicios = false)
	{
		try{
			if (!$servicios) {
				$sql = <<<SQL
				SELECT 
					*, CONCAT('[', Marca, '] - ',Nombre) Nombre,
					Nombre NombreSimple
				FROM producto
				WHERE (Nombre LIKE '%$criterio%' OR codigo_prod LIKE '%$criterio%' OR codigo_varilla LIKE '%$criterio%')
				AND Stock > 0
				AND Empresa_id = {$this->user->Empresa_id}
				ORDER BY Nombre
				LIMIT 10
				SQL;
			} else {
				$sql = <<<SQL
				SELECT * FROM (
					SELECT 
						id, UnidadMedida_id, codigo_prod,
						PrecioCompra, Precio, Marca, CostoBase,
						CONCAT('[', Marca, '] - ',Nombre) Nombre,
						Stock, 1 Tipo
					FROM producto
					WHERE Empresa_id = {$this->user->Empresa_id}
					AND Stock > 0
					UNION
					SELECT 
						id, UnidadMedida_id, codigo_prod,
						PrecioCompra, Precio, '' Marca, 'CostoBase',
						CONCAT('', Nombre) Nombre,
						0 Stock, 2 Tipo
					FROM servicio
					WHERE Empresa_id = {$this->user->Empresa_id}
				) alias
				WHERE Nombre LIKE '%$criterio%' OR codigo_prod LIKE '%$criterio%'
				ORDER BY Nombre
				LIMIT 10
				SQL;
			}

			return $this->db->query($sql)->result();
		}catch(Exception $e){
			return [$e->getMessage()];
		}
	}

	public function BuscarCat($criterio)
	{
			$sql = "SELECT DISTINCT(categoria) FROM producto WHERE Empresa_id = " . $this->user->Empresa_id . " and categoria LIKE '%$criterio%' ORDER BY categoria LIMIT 10";
		
		return $this->db->query($sql)->result();
	}

	public function BuscarCod($criterio)
	{
			$sql = "SELECT * FROM producto WHERE Empresa_id = " . $this->user->Empresa_id . " and codigo_prod = '".$criterio."'";
		
		return $this->db->query($sql)->result();
	}
	public function Materiales($criterio, $descrip)
	{
		$this->db->where("descripcion", $descrip);
		$this->db->like("material", $criterio);
		$this->db->limit(5);
		return $this->db->get("precios_lunas")->result();
		
		//return $this->db->query($sql)->result();
	}
	public function PrecioLente($material)
	{
		$sql = "SELECT * FROM precios_lunas WHERE material LIKE '%".$material."%' LIMIT 5";
		
		return $this->db->query($sql)->result();
	}
	public function Descripcion($criterio)
	{
		$this->db->like("descripcion", $criterio);
		$this->db->limit(5);
		return $this->db->get("precios_lunas")->result();
		
		//return $this->db->query($sql)->result();
	}
	public function Marcas($criterio)
	{
		$sql = "
			SELECT Distinct Marca 
			FROM producto
			WHERE Marca LIKE '%$criterio%'
			AND Empresa_id = " . $this->user->Empresa_id . "
			ORDER BY Marca
			LIMIT 10
		";
		return $this->db->query($sql)->result();
	}
	public function Medidas($criterio)
	{
		$sql = "
			SELECT Distinct UnidadMedida_id 
			FROM producto
			WHERE UnidadMedida_id LIKE '%$criterio%'
			AND Empresa_id = " . $this->user->Empresa_id . "
			ORDER BY UnidadMedida_id
			LIMIT 10
		";
		return $this->db->query($sql)->result();
	}

	//AGREGARFAB

	public function BuscarProd($criterio)
	{
			$sql = "SELECT DISTINCT cod_prod FROM producto WHERE Empresa_id = " . $this->user->Empresa_id . " and id = '$criterio'";
		
		return $this->db->query($sql)->result();
	}

	public function set_barcode($code)
    {
		//load library
		$this->load->library('zend');
		//load in folder Zend
		$this->zend->load('Zend/Barcode');
		//generate barcode
		$file = Zend_Barcode::draw('code128', 'image', array('text'=>$code), array());
		$store_image = imagepng($file,"codigos_barra/{$code}.png");
    }

    public function productCode($productName){
		$productCode = '';
		mt_srand(time());
		$randnum = mt_rand(0, 500);
		$y = explode(' ',$productName);
		foreach($y AS $k){
			$productCode .= strtoupper(substr($k,0,1));
		}
		return $productCode."".$randnum;
	}

}
<?php

use Automattic\WooCommerce\Client;

class Almacenmodel extends CI_Model
{
	public function Listar()
	{
		$where = 'a.Empresa_id = ' . $this->user->Empresa_id;
		$this->filter = isset($_REQUEST['filters']) ? json_decode($_REQUEST['filters']) : null;

		if($this->filter != null)
		{
			foreach($this->filter->{'rules'} as $f)
			{
				if($f->field == 'Tipo' && $f->data != 't') $where .= " AND a.Tipo = " . $f->data . " ";
				if($f->field == 'ProductoNombre') $where .= " AND a.ProductoNombre LIKE '%" . $f->data . "%' ";
			}
		}

		$this->db->where($where);
		$this->jqgridmodel->Config($this->db->SELECT('COUNT(*) Total FROM almacen a')->get()->row()->Total);
		
		$sql = "
			SELECT 
				a.*, 
				u.Nombre UsuarioNombre
			FROM almacen a
			INNER JOIN usuario u
			ON a.Usuario_id = u.id
			WHERE $where 
			ORDER BY " . $this->jqgridmodel->sord . "
			LIMIT " . $this->jqgridmodel->start . "," . $this->jqgridmodel->limit;

		$this->db->where($where);
		$this->jqgridmodel->DataSource($this->db->query($sql)->result());
			
		return $this->jqgridmodel;
	}
	public function Entrada($data)
	{
		$this->db->trans_start();

		$data['Tipo']       = 1;
		$data['Fecha']      = date('Y/m/d');
		$data['Empresa_id'] = $this->user->Empresa_id;
		$data['Usuario_id'] = $this->user->id;

		$last_id = $this->db->insert_id();

		$this->db->where("id", $data['Producto_id']);
		$this->db->set('stock', 'stock+' . $data['Cantidad'], false);
		$this->db->set('PrecioCompra', $data['Precio']);
		$this->db->update('producto');

		$this->db->insert("almacen", $data);

		$this->responsemodel->SetResponse(true);
		$this->responsemodel->href   = 'almacen/index';

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			log_message('1', __CLASS__ . '->' . __METHOD__);
			$this->responsemodel->SetResponse(false);
		}
		
		return $this->responsemodel;
	}

	public function eliminarCompra($idc){
		$this->db->where('id_compra', $idc);
		$this->db->delete('compras');
		$alm = $this->db->where("id_compra", $idc)->get("almacen")->result();
		foreach($alm as $a){
			$this->db->where("id", $a->Producto_id);
			$this->db->set('stock', 'stock-' . $a->Cantidad, false);
			$this->db->update('producto');
		}
		$this->db->where('id_compra', $idc);
		$this->db->delete('almacen');

		$this->db->where('id_compra', $idc);
		$this->db->delete('depositos');

		$this->db->where('id_compra', $idc);
		$this->db->delete('egresos');


		$this->responsemodel->SetResponse(true);
		$this->responsemodel->href   = 'almacen/verCompras';
		return $this->responsemodel;
	}

	public function GuardarCompra($data){
		$fec = explode("/", $data["FechaEmitido"]);
		$fechafin = $fec[2]."-".$fec[1]."-".$fec[0];
		$this->db->insert('compras', array(
			'id_proveedor'	=>	$data["Cliente_id"],
			'monto'			=>	$data["totalC"],
			'guia_factura'	=>	$data["guiafact"],
			'fecha'			=>  $fechafin,
			'Empresa_id'	=>  $this->user->Empresa_id,
			'Usuario_id'	=>  $this->user->id,
			'igv'			=>  $data["igv"],
			'igv_total' 	=>  $data["igv_total"],
			'id_orden' 		=>  $data["orden_lab"],
			'origen_compra' =>  $data["origen_compra"],
			'observaciones' =>  $data["observaciones"]
		));
		$idcomp = $this->db->insert_id();
		for($i = 0; $i < count($data['Producto_id']); $i++){
			if($data['ProductoNombre'][$i] != '' && !empty($data['ProductoNombre'][$i])){
				$this->db->where("id", $data['Producto_id'][$i]);
				$this->db->set('stock', 'stock+' . $data['Cantidad'][$i], false);
				$this->db->set('PrecioCompra', $data['PrecioUnitario'][$i]);
				$this->db->set('pendiente_upd', 1);
				$this->db->update('producto');

				$this->db->where("id", $data['Producto_id'][$i]);
				$sprodu = $this->db->get("producto")->row();

				$this->db->insert('almacen', array(
					"Tipo"				=> 1,
					"Fecha"				=> $data["FechaEmitido"],
					"Empresa_id"		=> $this->user->Empresa_id,
					"Usuario_id"		=> $this->user->id,
					"Producto_id"		=> $data['Producto_id'][$i],
					"ProductoNombre"	=> $data['ProductoNombre'][$i],
					"UnidadMedida_id"	=> $data['UnidadMedida_id'][$i],
					"Cantidad"			=> $data['Cantidad'][$i],
					"Precio"			=> $data['PrecioUnitario'][$i],
					"proveedor_nombre"	=> $data['ClienteNombre'],
					"proveedor_id"		=> $data['Cliente_id'],
					"guia_factura"		=> $data['guiafact'],
					"id_compra"			=> $idcomp,
					"stock_actual"		=> $sprodu->Stock,
					"fecha_movimiento" 	=> $fec[2].'-'.$fec[1].'-'.$fec[0].' 00:00'
				));

				$this->db->insert('compras_detalle', array(
					"Empresa_id"		=> $this->user->Empresa_id,
					"producto"			=> $data['ProductoNombre'][$i],
					"udm"				=> $data['UnidadMedida_id'][$i],
					"cantidad"			=> $data['Cantidad'][$i],
					"precio_compra"		=> $data['PrecioUnitario'][$i],
					"precio_total"		=> $data['PrecioUnitario'][$i]*$data['Cantidad'][$i],
					"igv"				=> $data['igv'],
					"id_compra"			=> $idcomp,
				));

				//$updtienda = $this->updTienda($data['Producto_id'][$i]);

			}
		}

		if(!empty($data["orden_lab"])){
			$this->db->where("id_orden", $data["orden_lab"]);
			$this->db->update("orden_lab", array("id_compra" => $idcomp, "monto_compra" => $data["totalC"]));
		}

		$this->responsemodel->SetResponse(true);
		$this->responsemodel->href   = 'almacen/nuevaCompra/0/' . $idcomp;
		return $this->responsemodel;
	}

	public function Ajustar($data)
	{
		$this->db->where('id', $data['Producto_id']);
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->update('producto', array( 'Stock' => $data['Cantidad']));

		$this->responsemodel->SetResponse(true);
		$this->responsemodel->href   = 'almacen/index';
		
		return $this->responsemodel;
	}

	public function Kardex($f1, $f2)
	{
		$where = 'a.Empresa_id = ' . $this->user->Empresa_id;

		if($f2 == '')
		{
			$where .= " AND Fecha = '" . ToDate($f1) . "' ";
		}
		else
		{
			$where .= " AND CAST(Fecha as DATE) BETWEEN CAST('" . ToDate($f1) . "' AS DATE) AND CAST('" . ToDate($f2) . "' AS DATE) ";
		}

		return $this->db->query("
			SELECT 
				a.id, a.Tipo, a.Producto_id, a.ProductoNombre, a.UnidadMedida_id, a.Comprobante_id,
				SUM(a.Cantidad) Cantidad, 
				IF(Tipo = 2, 
					SUM(a.Precio), 
					(SELECT Precio FROM almacen WHERE Producto_id = a.Producto_id ORDER BY id DESC LIMIT 1)) Precio,
				p.Stock
			FROM almacen a
			LEFT JOIN producto p
			ON a.Producto_id = p.id
			LEFT JOIN comprobante c
			ON a.Comprobante_id = c.id
			WHERE Tipo IN (1,2)
			AND $where
			AND (CASE WHEN Tipo = 2 THEN c.Estado = 2 AND c.Correlativo IS NOT NULL ELSE TRUE END)
			GROUP BY Tipo, Producto_id, UnidadMedida_id
			ORDER BY Nombre DESC
		")->result();		
	}

	public function ProductosPorAgotarse()
	{
		$this->db->where('StockMinimo >= Stock');
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->order_by("RAND(), Stock");

		return $this->db->get('producto', 15)->result();
	}

	public function Tipos()
	{
		$this->db->where('relacion', 'almacentipo');
		return $this->db->get('tabladato')->result();
	}

	public function getCompra($id){
		return $this->db->query("select * from compras c INNER JOIN almacen a ON a.id_compra = c.id_compra WHERE a.id_compra='".$id."'")->result();
	}

	public function getProveedor($id){
		return $this->db->query("select * from compras c INNER JOIN proveedores p ON p.id = c.id_proveedor WHERE p.id = '".$id."'")->row();
	}

	public function cargarCompras(){
		$where  = "c.Empresa_id = " . $this->user->Empresa_id . ' ';;
		
		$this->filter = isset($_REQUEST['filters']) ? json_decode($_REQUEST['filters']) : null;

		if($this->filter != null)
		{
			foreach($this->filter->{'rules'} as $f)
			{
				if($f->field == 'Nombre') $where .= "AND Nombre LIKE '%" . $f->data . "%' ";
				if($f->field == 'guia_factura') $where .= "AND guia_factura LIKE '%" . $f->data . "%' ";
				if($f->field == 'estado_deuda' && $f->data != "t") $where .= " HAVING estado_deuda = '" . $f->data . "' ";
			}
		}

		/*$this->db->where($where);
		$this->jqgridmodel->Config($this->db->SELECT('COUNT(*) Total FROM compras c INNER JOIN proveedores p ON p.id = c.id_proveedor')->get()->row()->Total);*/

		$sql1 = "SELECT *, IF(c.monto > c.monto_cancelado, \"1\", \"0\") as estado_deuda, (c.monto - c.monto_cancelado) as saldo 
			FROM compras c 
			INNER JOIN proveedores p 
			ON p.id = c.id_proveedor
			WHERE $where";

		$this->jqgridmodel->Config($this->db->query($sql1)->num_rows());
		
		$sql = "SELECT *, IF(c.monto > c.monto_cancelado, \"1\", \"0\") as estado_deuda, (c.monto - c.monto_cancelado) as saldo  
			FROM compras c 
			INNER JOIN proveedores p 
			ON p.id = c.id_proveedor
			WHERE $where
			ORDER BY " . $this->jqgridmodel->sord . "
			LIMIT " . $this->jqgridmodel->start . "," . $this->jqgridmodel->limit;

		$this->db->where($where);
		$this->jqgridmodel->DataSource($this->db->query($sql)->result());
			
		return $this->jqgridmodel;
	}

	public function getCompraOrd($id){
		return $this->db->query("select * from compras c INNER JOIN compras_detalle det ON c.id_compra = det.id_compra WHERE c.id_compra='".$id."'")->result();
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

	public function updTienda($id){

		$p = $this->db->where("id", $id)->get("producto")->row();

		if($p->codigo_prod){
			$woocommerce = new Client(
				'https://tienda-virtual.guillentamayo.com', 
				'ck_59073cf75bd029a4a355be9962a06752ea51fa6a', 
				'cs_ff6be1a1f1efb822d31b253260ffedb6d308e945',
				[
					'wp_api' => true,
					'version' => 'wc/v3',
				]
			);

			$params = [
                'sku' => trim($p->codigo_prod)
            ];

            $validar = $woocommerce->get('products', $params);

			$data = [
				'regular_price' => $p->Precio,
				'stock_quantity'=> $p->Stock
			];

			if($validar != null){
				$wc_upd = $woocommerce->put('products/'.$validar[0]->id, $data);
			}
			return $wc_upd;
		}
		
		return true;
	}
}
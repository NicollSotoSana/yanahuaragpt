<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kardex extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('usuariomodel', 'um');
		$this->load->model('cajamodel', 'cajam');
	}

	public function kardexProducto()
	{
		$productos = $this->db->get("producto")->result();

		$this->load->view('header');
		$this->load->view('kardex/kardexProducto', array("productos" => $productos));
		$this->load->view('footer');
	}

	public function searchProd(){
		$datos = array();

		$criterio = $_GET["search"];

		$sql = "
				SELECT 
					id, Nombre as text, Nombre as term
				FROM producto
				WHERE Nombre LIKE '%$criterio%' OR codigo_prod LIKE '%$criterio%' OR codigo_varilla LIKE '%$criterio%'
				AND Empresa_id = " . $this->user->Empresa_id . "
				ORDER BY Nombre
				LIMIT 10
		";
		
		$prods = $this->db->query($sql)->result();
		echo json_encode($prods);
	}

	public function getKardex($id_prod){
		//$datos = $this->db->where("Producto_id", $id_prod)->get("almacen")->result();
		$this->db->select('*');
		$this->db->from('almacen');
		$this->db->join('producto', 'producto.id = almacen.Producto_id');
		$this->db->where("Producto_id", $id_prod);
		$datos = $this->db->get()->result();

		echo json_encode($datos);
	}

	public function kardexValorizado()
	{
		$this->load->view('header');
		if(isset($_POST["mes"]) && isset($_POST["anio"])){
			$data = array();
			$productos = $this->db->order_by('Nombre', 'ASC')->get("producto")->result();

			foreach($productos as $p){
				$prod_sales = $this->db->query("SELECT c.id, cd.ProductoNombre, SUM(cd.Cantidad) as total FROM comprobantedetalle cd INNER JOIN comprobante c ON c.id = cd.Comprobante_Id WHERE MONTH(c.fecha_emision) = '".$_POST["mes"]."' AND YEAR(c.fecha_emision) = '".$_POST["anio"]."' AND c.Estado ='2' AND cd.Producto_id = '".$p->id."' AND cd.Producto_id != '0' GROUP BY cd.Producto_id")->row();

				$total_cant = isset($prod_sales->total) ? $prod_sales->total:0;
				$valor_ventas = number_format($p->Precio * $total_cant, 2, '.', '');
				$costo_producto = number_format($p->PrecioCompra * $total_cant, 2, '.', '');
				$unidad_valorizada = number_format($valor_ventas - $costo_producto, 2, '.', '');

				$data[] = array(
					"id" => $p->id,
					"nombre" => isset($prod_sales->ProductoNombre) ? $prod_sales->ProductoNombre:$p->Nombre,
					"cantidad" => number_format($total_cant, 2),
					"marca" => $p->Marca,
					"categoria" => $p->categoria,
					"udm" => $p->UnidadMedida_id,
					"precio_unit_compra" => $p->PrecioCompra,
					"precio_unit_venta" => $p->Precio,
					"valor_ventas" => $valor_ventas,
					"costo_producto" => $costo_producto,
					"unidad_valorizada" => $unidad_valorizada,
				);
			}

			$this->load->view('kardex/kardexValorizado', array("datos" => $data, "mes" => $_POST["mes"], "anio" => $_POST["anio"]));

		}else{
			$this->load->view('kardex/kardexValorizado');
		}
		
		$this->load->view('footer');
	}

	public function getKardexValorizado(){
		$fec_ini = $_POST["fecha_inicio"];
		$fec_fin = $_POST["fecha_fin"];

		$this->db->select('*');
		$this->db->from('almacen');
		$this->db->join('producto', 'producto.id = almacen.Producto_id');
		$this->db->where("DATE(fecha_movimiento) >='$fec_ini'");
    	$this->db->where("DATE(fecha_movimiento) <='$fec_fin'");
		$datos = $this->db->get()->result();

		echo json_encode($datos);
	}
	
	public function getCompras($mes, $anio, $id){
		$this->load->library('excel');

		$conf = $this->db->get("configuracion")->row();

		$producto = $this->db->where("id", $id)->get("producto")->row();

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'FORMATO 13.1: "REGISTRO DE INVENTARIO PERMANENTE VALORIZADO - DETALLE DEL INVENTARIO VALORIZADO"');

		$objPHPExcel->getActiveSheet()->mergeCells('A2:H2');

		$objPHPExcel->getActiveSheet()->getStyle("A2:B2")->getFont()->setBold(true);

		$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'PERIODO');
		$objPHPExcel->getActiveSheet()->getStyle("A3")->getFont()->setBold(true);

		$objPHPExcel->getActiveSheet()->SetCellValue('B3', $anio);
		$objPHPExcel->getActiveSheet()->SetCellValue('C3', "MES:");
		$objPHPExcel->getActiveSheet()->getStyle("C3")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->SetCellValue('D3', $mes);

		$objPHPExcel->getActiveSheet()->SetCellValue('A4', "RUC:");
		$objPHPExcel->getActiveSheet()->getStyle("A4")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->SetCellValue('B4', $conf->Ruc);

		$objPHPExcel->getActiveSheet()->SetCellValue('A5', "APELLIDOS Y NOMBRES, DENOMINACIÓN O RAZÓN SOCIAL::");
		$objPHPExcel->getActiveSheet()->getStyle("A5")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->SetCellValue('B5', $conf->RazonSocial);

		$objPHPExcel->getActiveSheet()->SetCellValue('A6', "ESTABLECIMIENTO (1):");
		$objPHPExcel->getActiveSheet()->getStyle("A6")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->SetCellValue('B6', "");

		$objPHPExcel->getActiveSheet()->SetCellValue('A7', "CÓDIGO DE LA EXISTENCIA:");
		$objPHPExcel->getActiveSheet()->getStyle("A7")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->SetCellValue('B7', $id);

		$objPHPExcel->getActiveSheet()->SetCellValue('A8', "TIPO (TABLA 5):");
		$objPHPExcel->getActiveSheet()->getStyle("A8")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->SetCellValue('B8', "01");

		$objPHPExcel->getActiveSheet()->SetCellValue('A9', "DESCRIPCIÓN:");
		$objPHPExcel->getActiveSheet()->getStyle("A9")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->SetCellValue('B9', $producto->Nombre);

		$objPHPExcel->getActiveSheet()->SetCellValue('A10', "CÓDIGO DE LA UNIDAD DE MEDIDA (TABLA 6):");
		$objPHPExcel->getActiveSheet()->getStyle("A10")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->SetCellValue('B10', "07 - UNIDADES");

		$objPHPExcel->getActiveSheet()->SetCellValue('A11', "MÉTODO DE VALUACIÓN:");
		$objPHPExcel->getActiveSheet()->getStyle("A11")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->SetCellValue('B11', "COSTO PROMEDIO");

		$objPHPExcel->getActiveSheet()->SetCellValue('A13', 'DOCUMENTO DE TRASLADO, COMPROBANTE DE PAGO, DOCUMENTO DE TRASLADO, COMPROBANTE DE PAGO, DOCUMENTO INTERNO O SIMILAR');

		$objPHPExcel->getActiveSheet()->mergeCells('A13:D13');

		$objPHPExcel->getActiveSheet()->getStyle("A13:D13")->getFont()->setBold(true);

		$objPHPExcel->getActiveSheet()->SetCellValue('A14', 'FECHA');
		$objPHPExcel->getActiveSheet()->getStyle("A14")->getFont()->setBold(true);

		$objPHPExcel->getActiveSheet()->SetCellValue('B14', 'TIPO (TABLA 10)');
		$objPHPExcel->getActiveSheet()->getStyle("B14")->getFont()->setBold(true);

		$objPHPExcel->getActiveSheet()->SetCellValue('C14', 'SERIE');
		$objPHPExcel->getActiveSheet()->getStyle("C14")->getFont()->setBold(true);

		$objPHPExcel->getActiveSheet()->SetCellValue('D14', 'NÚMERO');
		$objPHPExcel->getActiveSheet()->getStyle("D14")->getFont()->setBold(true);

		$objPHPExcel->getActiveSheet()->SetCellValue('E13', 'TIPO DE OPERACIÓN (TABLA 12)');
		$objPHPExcel->getActiveSheet()->getStyle("E13")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('E13:E14');

		$objPHPExcel->getActiveSheet()->SetCellValue('F13', 'ENTRADAS');
		$objPHPExcel->getActiveSheet()->getStyle("F13")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('F13:H13');

		$objPHPExcel->getActiveSheet()->SetCellValue('F14', 'CANTIDAD');
		$objPHPExcel->getActiveSheet()->getStyle("F14")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->SetCellValue('G14', 'COSTO UNITARIO');
		$objPHPExcel->getActiveSheet()->getStyle("G14")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->SetCellValue('H14', 'COSTO TOTAL');
		$objPHPExcel->getActiveSheet()->getStyle("H14")->getFont()->setBold(true);

		$objPHPExcel->getActiveSheet()->SetCellValue('I13', 'SALIDAS');
		$objPHPExcel->getActiveSheet()->getStyle("I13")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('I13:K13');

		$objPHPExcel->getActiveSheet()->SetCellValue('I14', 'CANTIDAD');
		$objPHPExcel->getActiveSheet()->getStyle("I14")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->SetCellValue('J14', 'COSTO UNITARIO');
		$objPHPExcel->getActiveSheet()->getStyle("J14")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->SetCellValue('K14', 'COSTO TOTAL');
		$objPHPExcel->getActiveSheet()->getStyle("K14")->getFont()->setBold(true);

		$objPHPExcel->getActiveSheet()->SetCellValue('L13', 'SALIDAS');
		$objPHPExcel->getActiveSheet()->getStyle("L13")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('L13:N13');

		$objPHPExcel->getActiveSheet()->SetCellValue('L14', 'CANTIDAD');
		$objPHPExcel->getActiveSheet()->getStyle("L14")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->SetCellValue('M14', 'COSTO UNITARIO');
		$objPHPExcel->getActiveSheet()->getStyle("M14")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->SetCellValue('N14', 'COSTO TOTAL');
		$objPHPExcel->getActiveSheet()->getStyle("N14")->getFont()->setBold(true);

		$cont = 15;

		$movimientos = $this->db->where("Producto_id", $id)->where("MONTH(fecha_movimiento)", $mes)->where("YEAR(fecha_movimiento)", $anio)->get("almacen")->result();
    	
		foreach ($movimientos as $m) {

			if($m->Tipo == "1"){
				$fact = explode("-", $m->guia_factura);
				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$cont, $m->fecha_movimiento);
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$cont, "01");
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.$cont, $fact[0]);
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$cont, $fact[1]);
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$cont, "COMPRA");
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$cont, $m->Cantidad);
				$objPHPExcel->getActiveSheet()->SetCellValue('G'.$cont, $m->Precio);
				$objPHPExcel->getActiveSheet()->SetCellValue('H'.$cont, $m->Cantidad * $m->Precio);
				$objPHPExcel->getActiveSheet()->SetCellValue('L'.$cont, $m->Cantidad);
				$objPHPExcel->getActiveSheet()->SetCellValue('M'.$cont, $m->Precio);
				$objPHPExcel->getActiveSheet()->SetCellValue('N'.$m->Cantidad * $m->Precio);

			}else if($m->Tipo == "2"){

				$comp = $this->db->where("id", $m->Comprobante_id)->get("comprobante")->row();


				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$cont, $m->fecha_movimiento);
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$cont, "03");
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.$cont, $comp->Correlativo);
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$cont, $comp->Serie);
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$cont, "VENTA");
				$objPHPExcel->getActiveSheet()->SetCellValue('I'.$cont, $m->Cantidad);
				$objPHPExcel->getActiveSheet()->SetCellValue('J'.$cont, $m->Precio);
				$objPHPExcel->getActiveSheet()->SetCellValue('K'.$cont, $m->Cantidad * $m->Precio);
				$objPHPExcel->getActiveSheet()->SetCellValue('L'.$cont, $m->Cantidad);
				$objPHPExcel->getActiveSheet()->SetCellValue('M'.$cont, $m->Precio);
				$objPHPExcel->getActiveSheet()->SetCellValue('N'.$m->Cantidad * $m->Precio);
			}
			
			
			
			$cont++;
		}

		//var_dump($this->db->last_query());

		$filename = "Kardex_valorizado_sunat_".$id.".xls";
		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output');
	}

}
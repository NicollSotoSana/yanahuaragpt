<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Objetivos extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('usuariomodel', 'um');
		$this->load->model('egresosmodel', 'em');
	}
	public function index()
	{
		if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		$this->load->view('header');
		$this->load->view('objetivos/index');
		$this->load->view('footer');
	}

	public function definirObjetivos(){
		$locales = $this->db->get("empresa")->result();
		$this->load->view('header');
		$this->load->view('objetivos/definir', array("locales" => $locales));
		$this->load->view('footer');
	}

	public function verAvance()
	{
		//if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		$this->load->view('header');
		$this->load->view('objetivos/verAvance');
		$this->load->view('footer');
	}

	public function verRespetoProtocolo()
	{
		//if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		$this->load->view('header');
		$this->load->view('objetivos/verRespeto');
		$this->load->view('footer');
	}

	public function guardarObjetivos(){
		$id_empresa 	= $this->input->post('id_empresa');
		$mes 			= $this->input->post('mes');
		$anio 			= $this->input->post('anio');
		$plan_fam 		= $this->input->post('plan_fam');
		$lentes_digi 	= $this->input->post('lentes_digi');
		$marcas_desc 	= $this->input->post('marcas_desc');
		$multifocal_top = $this->input->post('multifocal_top');
		$liquidos 		= $this->input->post('liquidos');
		$lentes_solares = $this->input->post('lentes_solares');
		$peeps = $this->input->post('peeps');

		$verificar 		= $this->db->where("mes", $mes)->where("anio", $anio)->where("Empresa_id", $id_empresa)->get("objetivos_mes");

		if($verificar->num_rows() == 0){
			$datos = array(
				"Empresa_id"	=>	$id_empresa,
				"mes"			=>	$mes,
				"anio"			=>	$anio,
				"plan_fam"		=>	$plan_fam,
				"lentes_digi"	=>	$lentes_digi,
				"marcas_desc"	=>	$marcas_desc,
				"multifocal_top"=>	$multifocal_top,
				"liquidos"		=>	$liquidos,
				"lentes_solares"=>	$lentes_solares,
				"peeps"			=>	$peeps,
			);

			$this->db->insert("objetivos_mes", $datos);
		}else{
			$datos = array(
				"Empresa_id"	=>	$id_empresa,
				"mes"			=>	$mes,
				"anio"			=>	$anio,
				"plan_fam"		=>	$plan_fam,
				"lentes_digi"	=>	$lentes_digi,
				"marcas_desc"	=>	$marcas_desc,
				"multifocal_top"=>	$multifocal_top,
				"liquidos"		=>	$liquidos,
				"lentes_solares"=>	$lentes_solares,
				"peeps"			=>	$peeps,
			);

			$this->db->where("id_objetivo", $verificar->row()->id_objetivo)->update("objetivos_mes", $datos);
		}
		redirect("/objetivos/definirObjetivos");
	}

	public function getObjetivosVi($mes, $anio){
		echo json_encode($this->db->where("mes", $mes)->where("anio", $anio)->get("objetivos_mes")->row());
	}

	public function verIndicadores($mes, $anio){
		$objetivos = $this->db->where("mes", $mes)->where("anio", $anio)->get("objetivos_mes")->row();
		$usuarios = $this->db->get("usuario");
		$indicadores = array();
		$totales = array("plan_familiar" => 0, "total_digital_monofocal" => 0, "marcas_reto" => 0, "total_multifocal_top" => 0, "liquidos" => 0, "solares" => 0, "peeps" => 0, "total_trios" => 0);
		$total_usuarios = 0;
		$usuarios_list = array();

		foreach($usuarios->result() as $us){
			//Revisamos si tiene ordenes de laboratorio
			$total_ordenes = $this->db->query("SELECT COUNT(*) as total FROM orden_lab WHERE id_usuario = '".$us->id."' AND (MONTH(fecha_orden) = '".$mes."' AND YEAR(fecha_orden) = '".$anio."')")->row();
			if($total_ordenes->total != "0"){
				$total_usuarios++;

				$usuarios_list[] = $us->id;

				$indicadores[$us->id]["nombre"] = $us->Nombre;
				$indicadores[$us->id]["id_usuario"] = $us->id;

				//Total Protocolos
				$tot_protocolos = $this->db->query("SELECT COUNT(id_encuesta) as total FROM encuestas WHERE id_usuario = '".$us->id."' AND estado='1' AND MONTH(fecha)='".$mes."' AND  YEAR(fecha)='".$anio."'")->row();
				$indicadores[$us->id]["tot_protocolos"] = number_format($tot_protocolos->total, 2);

				//Total Protocolos Aprobados
				$tot_protocolos_aprob = $this->db->query("SELECT COUNT(id_encuesta) as total FROM encuestas WHERE id_usuario = '".$us->id."' AND estado='1' AND MONTH(fecha)='".$mes."' AND  YEAR(fecha)='".$anio."' AND cant_no<3")->row();
				$indicadores[$us->id]["tot_protocolos_aprob"] = number_format($tot_protocolos_aprob->total, 2);

				//Total Satisfacción
				$tot_satisfac = $this->db->query("SELECT SUM(nivel_satisfac) as total FROM encuestas WHERE id_usuario = '".$us->id."' AND estado='1' AND MONTH(fecha)='".$mes."' AND  YEAR(fecha)='".$anio."'")->row();
				$indicadores[$us->id]["tot_satisfac"] = number_format($tot_satisfac->total, 2);

				//Total Ventas
				$tot_ventas = $this->db->query("SELECT COUNT(id) as total FROM comprobante WHERE Usuario_id = '".$us->id."' AND Estado='2' AND id_orden_lab != '0' AND MONTH(fecha_emision)='".$mes."' AND  YEAR(fecha_emision)='".$anio."'")->row();
				$indicadores[$us->id]["total_ventas"] = number_format($tot_ventas->total, 2);

				//Plan Familiar
				$query_plan = $this->db->query("SELECT IFNULL(sum(Cantidad), 0) as total from comprobante c INNER JOIN comprobantedetalle cd ON c.id = cd.Comprobante_Id where cd.Producto_id= '11' AND cd.Tipo='2' AND c.Usuario_id='".$us->id."' AND MONTH(c.fecha_emision)='".$mes."' AND  YEAR(c.fecha_emision)='".$anio."' AND c.Estado = '2'")->row();
				$plan_familiar = $query_plan->total;
				$indicadores[$us->id]["plan_familiar"] = number_format($plan_familiar, 2);
				$totales["plan_familiar"] += number_format($plan_familiar, 2);

				//Lentes digitales Monofocales

				$dig_monofocal = $this->db->query("SELECT (SELECT meta_value FROM orden_lab_meta WHERE meta_key='id_material' AND id_orden = orden_lab.id_orden) as idluna, (SELECT IF(COUNT(id_precio)=0, 0, 1) FROM precios_lunas WHERE id_precio = idluna AND disenio LIKE 'MONOFOCAL' AND fabricacion = 'DIGITAL') as total FROM orden_lab INNER JOIN comprobante ON comprobante.id_orden_lab = orden_lab.id_orden WHERE id_usuario = '".$us->id."' AND id_estado_orden != '4' AND MONTH(fecha_orden)='".$mes."' AND YEAR(fecha_orden)='".$anio."' AND comprobante.Estado = '2'")->result();

				$total_digital_monofocal = 0;
				
				foreach($dig_monofocal as $dm){
					$total_digital_monofocal += $dm->total;
				}
				
				$indicadores[$us->id]["total_digital_monofocal"] = number_format($total_digital_monofocal, 2);
				$totales["total_digital_monofocal"] += number_format($total_digital_monofocal, 2);

				//Marcas Reto

				$query_reto = $this->db->query("SELECT IFNULL(sum(Cantidad), 0) as total from comprobante c INNER JOIN comprobantedetalle cd ON c.id = cd.Comprobante_Id INNER JOIN producto p ON cd.Producto_id = p.id where p.reto = '1' AND c.Usuario_id='".$us->id."' AND MONTH(c.fecha_emision)='".$mes."' AND  YEAR(c.fecha_emision)='".$anio."' AND c.Estado = '2' AND cd.Tipo = '1'")->row();
				$marcas_reto = $query_reto->total;
				$indicadores[$us->id]["marcas_reto"] = number_format($marcas_reto, 2);
				$totales["marcas_reto"] += number_format($marcas_reto, 2);

				//Multifocal TOP

				$top_multifocal = $this->db->query("SELECT (SELECT meta_value FROM orden_lab_meta WHERE meta_key='id_material' AND id_orden = orden_lab.id_orden) as idluna, (SELECT IF(COUNT(id_precio)=0, 0, 1) FROM precios_lunas WHERE id_precio = idluna AND disenio = 'MULTIFOCAL' AND (material != 'CRISTAL 1.55' AND material != 'CRISTAL 1.7')) as total FROM orden_lab INNER JOIN comprobante c ON c.id_orden_lab = orden_lab.id_orden WHERE id_usuario = '".$us->id."' AND id_estado_orden != '4' AND MONTH(fecha_orden)='".$mes."' AND YEAR(fecha_orden)='".$anio."' and c.Estado = '2'")->result();

				$total_multifocal_top = 0;
				
				foreach($top_multifocal as $tm){
					$total_multifocal_top += $tm->total;
				}

				$indicadores[$us->id]["total_multifocal_top"] = number_format($total_multifocal_top, 2);
				$totales["total_multifocal_top"] += number_format($total_multifocal_top, 2);
				
				//Liquidos

				$query_liq = $this->db->query("SELECT IFNULL(sum(Cantidad), 0) as total from comprobante c INNER JOIN comprobantedetalle cd ON c.id = cd.Comprobante_Id where (cd.Producto_id = '2228' OR cd.Producto_id = '2978') AND c.Usuario_id='".$us->id."' AND MONTH(c.fecha_emision)='".$mes."' AND YEAR(c.fecha_emision)='".$anio."' AND c.Estado = '2'")->row();
				$liquidos = $query_liq->total;

				$indicadores[$us->id]["liquidos"] = number_format($liquidos,2);
				$totales["liquidos"] += number_format($liquidos, 2);

				//Peeps

				$query_peep = $this->db->query("SELECT IFNULL(sum(Cantidad), 0) as total from comprobante c INNER JOIN comprobantedetalle cd ON c.id = cd.Comprobante_Id where cd.Producto_id = '540' AND c.Usuario_id='".$us->id."' AND MONTH(c.fecha_emision)='".$mes."' AND YEAR(c.fecha_emision)='".$anio."' AND c.Estado = '2'")->row();
				$peeps = $query_peep->total;

				$indicadores[$us->id]["peeps"] = number_format($peeps,2);
				$totales["peeps"] += number_format($peeps, 2);

				//Solares

				$query_solares = $this->db->query("SELECT IFNULL(sum(Cantidad), 0) as total from comprobante c INNER JOIN comprobantedetalle cd ON c.id = cd.Comprobante_Id INNER JOIN producto p ON cd.Producto_id = p.id where p.categoria = 'MONTURA SOLAR' AND c.Usuario_id='".$us->id."' AND MONTH(c.fecha_emision)='".$mes."' AND YEAR(c.fecha_emision)='".$anio."' AND c.Estado = '2'")->row();
				$solares = $query_solares->total;

				$indicadores[$us->id]["solares"] = number_format($solares,2);
				$totales["solares"] += number_format($solares, 2);

				//Obtener Trios
				$total_trios = 0;
				$pri_query = $this->db->query("SELECT c.id, c.id_orden_lab from comprobante c INNER JOIN comprobantedetalle cd ON c.id = cd.Comprobante_Id INNER JOIN producto p ON cd.Producto_id = p.id INNER JOIN orden_lab ol ON ol.id_orden = c.id_orden_lab WHERE p.categoria = 'MONTURA OFTALMICA' AND c.Usuario_id='".$us->id."' AND MONTH(c.fecha_emision)='".$mes."' AND YEAR(c.fecha_emision)='".$anio."' AND c.Estado = '2' AND ol.id_estado_orden != '4' AND cd.Tipo = '1'")->result();

				$cuenta = 0;
				$total_notrios = 0;
				$contabilizados = array();
				foreach($pri_query as $pq){
					if($pq->id_orden_lab != "0" && !in_array($pq->id, $contabilizados)){
						$bus_precio = $this->db->where("id_orden", $pq->id_orden_lab)->where("meta_key", "id_material")->get("orden_lab_meta")->row();
						//echo $bus_precio->meta_value;
						if(isset($bus_precio->meta_value)){
							$final = $this->db->query("SELECT es_trio, tratamiento, material, fabricacion FROM precios_lunas WHERE id_precio = '".$bus_precio->meta_value."'")->row();
							if($final->es_trio == '1'){
								$total_trios++;
								$contabilizados[] = $pq->id;
							}
						}
					}
					
				}
				/*echo '<h1>Total: '.$cuenta.'</h1>';
				echo '<h1 style="color: red; font-weight:bold;">Total trios: '.$total_trios.'</h1>';
				echo '<h1>Total no trios: '.$total_notrios.'</h1>';*/

				$indicadores[$us->id]["total_trios"] = number_format($total_trios, 2);
				$totales["total_trios"] += number_format($total_trios, 2);

				
			}else{
				$total_ventas = $this->db->query("SELECT COUNT(*) as total FROM comprobante WHERE Usuario_id = '".$us->id."' AND (MONTH(fecha_emision) = '".$mes."' AND YEAR(fecha_emision) = '".$anio."')")->row();

				if($total_ventas->total != "0"){
					$total_usuarios++;

					$usuarios_list[] = $us->id;

					$indicadores[$us->id]["nombre"] = $us->Nombre;
					$indicadores[$us->id]["id_usuario"] = $us->id;

					$indicadores[$us->id]["tot_protocolos"] = number_format(0, 2);
					$indicadores[$us->id]["tot_protocolos_aprob"] = number_format(0, 2);
					$indicadores[$us->id]["total_digital_monofocal"] = number_format(0, 2);
					$totales["total_digital_monofocal"] += number_format(0, 2);

					$query_solares = $this->db->query("SELECT IFNULL(sum(Cantidad), 0) as total from comprobante c INNER JOIN comprobantedetalle cd ON c.id = cd.Comprobante_Id INNER JOIN producto p ON cd.Producto_id = p.id where p.categoria = 'MONTURA SOLAR' AND c.Usuario_id='".$us->id."' AND MONTH(c.fecha_emision)='".$mes."' AND YEAR(c.fecha_emision)='".$anio."' AND c.Estado = '2'")->row();
					$solares = $query_solares->total;

					$indicadores[$us->id]["solares"] = number_format($solares,2);
					$totales["solares"] += number_format($solares, 2);
				}
			}
		}

		$usuarios_mostrar = $this->db->where_in('id', $usuarios_list)->get("usuario")->result();

		echo $this->load->view("objetivos/indicadores", array("objetivos" => $objetivos, "indicadores" => $indicadores, "totales" => $totales, "usuarios_total" => $total_usuarios, "usuarios_todos" => $usuarios_mostrar, "mes" => $mes, "anio" => $anio), true);
		//$this->load->view('footer');
	}


	public function respetoProtocolo($anio){
		$usuarios = $this->db->where("Tipo", 2)->or_where("Tipo", 5)->get("usuario");
		$total_usuarios = $usuarios->num_rows();

		$datos_retorno = array();
	
		foreach($usuarios->result() as $us){
			
			$datos_bien = $this->db->query("SELECT 
				ROUND(IFNULL(SUM(IF(month = '01', total, 0)),0),2) AS 'enero_bien',
				ROUND(IFNULL(SUM(IF(month = '02', total, 0)),0),2) AS 'febrero_bien',
				ROUND(IFNULL(SUM(IF(month = '03', total, 0)),0),2) AS 'marzo_bien',
				ROUND(IFNULL(SUM(IF(month = '04', total, 0)),0),2) AS 'abril_bien',
				ROUND(IFNULL(SUM(IF(month = '05', total, 0)),0),2) AS 'mayo_bien',
				ROUND(IFNULL(SUM(IF(month = '06', total, 0)),0),2) AS 'junio_bien',
				ROUND(IFNULL(SUM(IF(month = '07', total, 0)),0),2) AS 'julio_bien',
				ROUND(IFNULL(SUM(IF(month = '08', total, 0)),0),2) AS 'agosto_bien',
				ROUND(IFNULL(SUM(IF(month = '09', total, 0)),0),2) AS 'setiembre_bien',
				ROUND(IFNULL(SUM(IF(month = '10', total, 0)),0),2) AS 'octubre_bien',
				ROUND(IFNULL(SUM(IF(month = '11', total, 0)),0),2) AS 'noviembre_bien',
				ROUND(IFNULL(SUM(IF(month = '12', total, 0)),0),2) AS 'diciembre_bien'
				FROM (
					SELECT MONTH(fecha) AS month, count(id_encuesta) as total
					FROM encuestas WHERE estado = 1 AND YEAR(fecha) = ".$anio." AND id_usuario = '".$us->id."' AND cant_no < 3 GROUP BY MONTH(fecha)
				) as sub")->row_array();

			$datos_mal = $this->db->query("SELECT 
				ROUND(IFNULL(SUM(IF(month = '01', total, 0)),0),2) AS 'enero_mal',
				ROUND(IFNULL(SUM(IF(month = '02', total, 0)),0),2) AS 'febrero_mal',
				ROUND(IFNULL(SUM(IF(month = '03', total, 0)),0),2) AS 'marzo_mal',
				ROUND(IFNULL(SUM(IF(month = '04', total, 0)),0),2) AS 'abril_mal',
				ROUND(IFNULL(SUM(IF(month = '05', total, 0)),0),2) AS 'mayo_mal',
				ROUND(IFNULL(SUM(IF(month = '06', total, 0)),0),2) AS 'junio_mal',
				ROUND(IFNULL(SUM(IF(month = '07', total, 0)),0),2) AS 'julio_mal',
				ROUND(IFNULL(SUM(IF(month = '08', total, 0)),0),2) AS 'agosto_mal',
				ROUND(IFNULL(SUM(IF(month = '09', total, 0)),0),2) AS 'setiembre_mal',
				ROUND(IFNULL(SUM(IF(month = '10', total, 0)),0),2) AS 'octubre_mal',
				ROUND(IFNULL(SUM(IF(month = '11', total, 0)),0),2) AS 'noviembre_mal',
				ROUND(IFNULL(SUM(IF(month = '12', total, 0)),0),2) AS 'diciembre_mal'
			FROM (
				SELECT MONTH(fecha) AS month, IFNULL(count(id_encuesta),0) as total
				FROM encuestas WHERE estado = 1 AND YEAR(fecha) = ".$anio." AND id_usuario = '".$us->id."' AND cant_no >= 3 GROUP BY MONTH(fecha)
			) as sub")->row_array();

			$datos_retorno[$us->id]["Nombre"] = $us->Nombre;
			
			$datos_retorno[$us->id]["bien"] = $datos_bien;
			$datos_retorno[$us->id]["mal"] = $datos_mal;
			//$datos_retorno[$us->id] = $datos_mal;
		}

		echo $this->load->view("objetivos/respetoProtocolo", array("usuarios" => $usuarios->result(), "total_usuarios" => $total_usuarios, "indicadores" => $datos_retorno), true);
	}
	
	public function indicadoresUsuario($id_usuario, $tipo, $mes, $anio){
		$datos = $this->getIndicadoresUsuario($id_usuario, $tipo, $mes, $anio);

		$this->load->view('header');
		$this->load->view('objetivos/objetivosDetallado', array("datos" => $datos["datos"], "mes" => $mes, "anio" => $anio, "titulo" => $datos["titulo"]));
		$this->load->view('footer');
	}


	private function getIndicadoresUsuario($id_usuario, $tipo, $mes, $anio){
		
		if($tipo == "dig_monofocal"){
			$dig_monofocal = $this->db->query("SELECT 
			(SELECT meta_value FROM orden_lab_meta WHERE meta_key='id_material' AND id_orden = orden_lab.id_orden) as idluna, 
			(SELECT IF(COUNT(id_precio)=0, 0, 1) FROM precios_lunas WHERE id_precio = idluna AND disenio LIKE 'MONOFOCAL' AND fabricacion = 'DIGITAL') as total,
			comprobante.id,
			comprobante.Correlativo,
			comprobante.Serie,
			comprobante.id_orden_lab,
			comprobante.ClienteNombre,
			comprobante.fecha_emision
			FROM orden_lab 
			INNER JOIN comprobante ON comprobante.id_orden_lab = orden_lab.id_orden 
			WHERE id_usuario = '".$id_usuario."' 
			AND id_estado_orden != '4' 
			AND MONTH(fecha_orden)='".$mes."' 
			AND YEAR(fecha_orden)='".$anio."' 
			AND comprobante.Estado = '2'")->result();

			return ["datos" => $dig_monofocal, "titulo" => "Digital Monofocal"];
			
		}else if($tipo == "marcas_reto"){
			$query_reto = $this->db->query("SELECT c.id, c.Serie, c.Correlativo, c.id_orden_lab, c.ClienteNombre, c.fecha_emision from comprobante c INNER JOIN comprobantedetalle cd ON c.id = cd.Comprobante_Id INNER JOIN producto p ON cd.Producto_id = p.id where p.reto = '1' AND c.Usuario_id='".$id_usuario."' AND MONTH(c.fecha_emision)='".$mes."' AND  YEAR(c.fecha_emision)='".$anio."' AND c.Estado = '2' AND cd.Tipo = '1'")->result();

			return ["datos" => $query_reto, "titulo" => "Marcas Reto"];

		}else if($tipo == "multifocal_top"){
			$top_multifocal = $this->db->query("SELECT 
			(SELECT meta_value FROM orden_lab_meta WHERE meta_key='id_material' AND id_orden = orden_lab.id_orden) as idluna, 
			(SELECT IF(COUNT(id_precio)=0, 0, 1) FROM precios_lunas WHERE id_precio = idluna AND disenio = 'MULTIFOCAL' AND (material != 'CRISTAL 1.55' AND material != 'CRISTAL 1.7')) as total, 
			c.id,
			c.Serie,
			c.Correlativo,
			c.id_orden_lab, 
			c.ClienteNombre, 
			c.fecha_emision
			FROM orden_lab INNER JOIN comprobante c ON c.id_orden_lab = orden_lab.id_orden 
			WHERE id_usuario = '".$id_usuario."' AND id_estado_orden != '4' AND MONTH(fecha_orden)='".$mes."' AND YEAR(fecha_orden)='".$anio."' and c.Estado = '2'")->result();

			return ["datos" => $top_multifocal, "titulo" => "Multifocal Top"];

		}else if($tipo == "liquidos"){
			$query_liq = $this->db->query("SELECT c.id, c.Serie, c.Correlativo, c.id_orden_lab, c.ClienteNombre, c.fecha_emision from comprobante c INNER JOIN comprobantedetalle cd ON c.id = cd.Comprobante_Id where (cd.Producto_id = '547' OR cd.Producto_id = '1824') AND c.Usuario_id='".$id_usuario."' AND MONTH(c.fecha_emision)='".$mes."' AND YEAR(c.fecha_emision)='".$anio."' AND c.Estado = '2'")->result();

			return ["datos" => $query_liq, "titulo" => "Líquidos"];

		}else if($tipo == "peeps"){

			$query_peep = $this->db->query("SELECT c.id, c.Serie, c.Correlativo, c.id_orden_lab, c.ClienteNombre, c.fecha_emision from comprobante c INNER JOIN comprobantedetalle cd ON c.id = cd.Comprobante_Id where cd.Producto_id = '540' AND c.Usuario_id='".$id_usuario."' AND MONTH(c.fecha_emision)='".$mes."' AND YEAR(c.fecha_emision)='".$anio."' AND c.Estado = '2'")->result();

			return ["datos" => $query_peep, "titulo" => "Peeps"];

		}else if($tipo == "solares"){

			$query_solares = $this->db->query("SELECT c.id, c.Serie, c.Correlativo, c.id_orden_lab, c.ClienteNombre, c.fecha_emision from comprobante c INNER JOIN comprobantedetalle cd ON c.id = cd.Comprobante_Id INNER JOIN producto p ON cd.Producto_id = p.id where p.categoria = 'MONTURA SOLAR' AND c.Usuario_id='".$id_usuario."' AND MONTH(c.fecha_emision)='".$mes."' AND YEAR(c.fecha_emision)='".$anio."' AND c.Estado = '2'")->result();
			
			return ["datos" => $query_solares, "titulo" => "Solares"];

		}else if($tipo == "trios"){

			$pri_query = $this->db->query("SELECT c.id, c.id_orden_lab, c.Serie, c.Correlativo, c.id_orden_lab, c.ClienteNombre, c.fecha_emision from comprobante c INNER JOIN comprobantedetalle cd ON c.id = cd.Comprobante_Id INNER JOIN producto p ON cd.Producto_id = p.id INNER JOIN orden_lab ol ON ol.id_orden = c.id_orden_lab WHERE p.categoria = 'MONTURA OFTALMICA' AND c.Usuario_id='".$id_usuario."' AND MONTH(c.fecha_emision)='".$mes."' AND YEAR(c.fecha_emision)='".$anio."' AND c.Estado = '2' AND ol.id_estado_orden != '4' AND cd.Tipo = '1'")->result();

			$cuenta = 0;
			$total_notrios = 0;
			$contabilizados = array();
			$retorno = array();
			foreach($pri_query as $pq){
				if($pq->id_orden_lab != "0" && !in_array($pq->id, $contabilizados)){
					$bus_precio = $this->db->where("id_orden", $pq->id_orden_lab)->where("meta_key", "id_material")->get("orden_lab_meta")->row();
					//echo $bus_precio->meta_value;
					if(isset($bus_precio->meta_value)){
						$final = $this->db->query("SELECT es_trio, tratamiento, material, fabricacion FROM precios_lunas WHERE id_precio = '".$bus_precio->meta_value."'")->row();
						if($final->es_trio == '1'){
							$retorno[] = $pq;
						}
					}
				}
				
			}
			return ["datos" => $retorno, "titulo" => "Trios"];
		}

	}
}
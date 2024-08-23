<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Conformidadmonturas extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('usuariomodel', 'um');
		$this->load->model('cajamodel', 'cajam');
	}
	public function index()
	{
		$this->load->view('acceso/index', array(
			'empresas' => $this->um->Empresas()
		));
	}

	public function editConformidad(){
		$datos = json_encode($_POST);
		$this->db->where("id_orden_lab", $_POST["id_orden_lab"])->update("conformidad_monturas", array("conformidad_data" => $datos, "id_comprobante" => $_POST["id_comprobante"]));

		redirect('ventas/comprobante/'.$_POST["id_comprobante"]);
	}

	public function imprimirConformidad($id_orden_lab){
		$this->load->library('mpdflib');
		//$html2pdf = new Html2Pdf('P',['80', '500'],'en',true,"UTF-8",array(2, 5, 0, 5));
		$html2pdf = new Mpdf("en",[78,500],"","",5,5,2,2,2,2);

		$orden_lab = $this->db->where("id_orden", $id_orden_lab)->get("orden_lab")->row();

		$conformidad = $this->db->where("id_orden_lab", $id_orden_lab)->get("conformidad_monturas")->row();

		$cliente = $this->db->where("id", $conformidad->id_cliente)->get("cliente")->row();


		//var_dump($conformidad1);

		$conformidad_data = json_decode($conformidad->conformidad_data, true);

		$htmlsalida = $this->load->view("conformidadmonturas/ticket", array("orden_lab" => $orden_lab, "conformidad" => $conformidad_data, "conf_full" => $conformidad, "cliente" => $cliente), TRUE);

		$html2pdf->writeHTML($htmlsalida);
		$html2pdf->output('Conformidad_montura-'.$id_orden_lab.'.pdf', 'I');
	}
	
	public function ajax($action)
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

		switch($action)
		{
			case 'addConformidadMontura':
				$id_cliente = $this->input->post('id_cliente');
				$id_orden_lab = $this->input->post('id_orden_lab');
				$id_comprobante = $this->input->post('id_comprobante');
				$conformidad = $this->db->where("id_orden_lab", $id_orden_lab)->get("conformidad_monturas")->row();
				echo $this->load->view('conformidadmonturas/nuevaconformidad', 
					array(
						'id_cliente'      => $id_cliente,
						'id_orden_lab'    => $id_orden_lab,
						'id_comprobante'  => $id_comprobante,
						'conformidad'	  => $conformidad
					), true);
				break;
				break;
		}
	}
	
	
	
	
	

}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mpdflib {
	public function __construct() {
		
		//require_once APPPATH.'libraries/fpdf/fpdf-1.7.php';
		
		
		//require_once(APPPATH.'libraries/html2pdf/html2pdf.class.php');
		require_once(APPPATH.'libraries/mpdf/mpdf.php');
	}

	
}
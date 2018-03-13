<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Procesos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('format');
		$this->load->database();
	}


	public function lectura_db_fe(){


						$this->load->model('facturaelectronica');
						//$codproceso = $this->facturaelectronica->guarda_doc_proc();
						$codproceso = 'sjX9NBbZ9M';
						$this->facturaelectronica->crea_dte_db($codproceso);
		        



	}


	public function lectura_csv_fe_manual(){

			$archivo = "./facturacion_electronica/csv/procesados/FACT_PROC_2016084114.CSV";
			$this->load->model('facturaelectronica');
			$codproceso = $this->facturaelectronica->guarda_csv($archivo);
			$this->facturaelectronica->crea_dte_csv($codproceso);


	}



	public function envio_programado_sii(){
		set_time_limit(0);
		$this->load->model('facturaelectronica');
		$facturas = $this->facturaelectronica->get_factura_no_enviada();

		

		foreach ($facturas as $factura) {
			$idfactura = $factura->idfactura;
			$factura = $this->facturaelectronica->datos_dte($idfactura);
			$config = $this->facturaelectronica->genera_config();
			include $this->facturaelectronica->ruta_libredte();


			$token = \sasco\LibreDTE\Sii\Autenticacion::getToken($config['firma']);
			if (!$token) {
			    foreach (\sasco\LibreDTE\Log::readAll() as $error){
			    	$result['error'] = true;

			    }
			    $result['message'] = "Error de conexión con SII";		   
			   	echo json_encode($result);
			    exit;
			}

			$Firma = new \sasco\LibreDTE\FirmaElectronica($config['firma']); //lectura de certificado digital
			$rut = $Firma->getId(); 
			$rut_consultante = explode("-",$rut);
			$RutEnvia = $rut_consultante[0]."-".$rut_consultante[1];

			//$xml = $factura->dte;
			$archivo = "./facturacion_electronica/dte/".$factura->path_dte.$factura->archivo_dte;
		 	if(file_exists($archivo)){
		 		$xml = file_get_contents($archivo);
		 	}else{
		 		$xml = $factura->dte;
		 	}


			$EnvioDte = new \sasco\LibreDTE\Sii\EnvioDte();
			$EnvioDte->loadXML($xml);
			$Documentos = $EnvioDte->getDocumentos();	

			$DTE = $Documentos[0];
			$RutEmisor = $DTE->getEmisor(); 

			// enviar DTE
			$result_envio = \sasco\LibreDTE\Sii::enviar($RutEnvia, $RutEmisor, $xml, $token);

			// si hubo algún error al enviar al servidor mostrar
			if ($result_envio===false) {
			    foreach (\sasco\LibreDTE\Log::readAll() as $error){
			        $result['error'] = true;
			    }
			    $result['message'] = "Error de envío de DTE";		   
			   	echo json_encode($result);
			    exit;
			}

			// Mostrar resultado del envío
			if ($result_envio->STATUS!='0') {
			    foreach (\sasco\LibreDTE\Log::readAll() as $error){
					$result['error'] = true;
			    }
			    $result['message'] = "Error de envío de DTE";		   
			   	echo json_encode($result);
			    exit;
			}


			$track_id = 0;
			$track_id = (int)$result_envio->TRACKID;
		    $this->db->where('id', $factura->id);
			$this->db->update('folios_caf',array('trackid' => $track_id)); 

			$datos_empresa_factura = $this->facturaelectronica->get_empresa_factura($idfactura);
			
			if($track_id != 0 && $datos_empresa_factura->e_mail != ''){ //existe track id, se envía correo
				$this->facturaelectronica->envio_mail_dte($idfactura);
			}

			echo "idfactura: " .$factura->id." -- folio : ".$factura->folio." -- trackid : ". $track_id . "<br>";
			ob_flush(); 

			$result['success'] = true;
			$result['message'] = $track_id != 0 ? "DTE enviado correctamente" : "Error en env&iacute;o de DTE";
			$result['trackid'] = $track_id;
			echo json_encode($result);
			
		}

	}		

	public function get_contribuyentes(){

		set_time_limit(0);
		$this->load->model('facturaelectronica');
		$this->facturaelectronica->get_contribuyentes();
	}	

	
}










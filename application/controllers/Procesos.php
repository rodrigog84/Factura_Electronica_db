<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Procesos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('format');
		$this->load->database();
	}


	public function lectura_db_fe($idempresa){


						$this->load->model('facturaelectronica');
						$codproceso = $this->facturaelectronica->guarda_doc_proc($idempresa);
						var_dump($codproceso);
						//$codproceso = 'WQR1NTeBJo';
						$this->facturaelectronica->crea_dte_db($codproceso,$idempresa);
		        



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
			//echo "<pre>";

			$config = $this->facturaelectronica->genera_config($factura->idempresa);
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



public function prueba_email($tipo_email,$idempresa){

		$this->load->model('facturaelectronica');
		//$email_data = $this->facturaelectronica->get_email();
		$email_data = $this->facturaelectronica->get_email($idempresa);

		if(count($email_data) > 0){
			if($tipo_email == 'intercambio'){
				$email = $email_data->email_intercambio;
				$pass = $email_data->pass_intercambio;
				$tserver = $email_data->tserver_intercambio;
				$port = $email_data->port_intercambio;
				$host = $email_data->host_intercambio;
			}else if($tipo_email == 'contacto'){
				$email = $email_data->email_contacto;
				$pass = $email_data->pass_contacto;
				$tserver = $email_data->tserver_contacto;
				$port = $email_data->port_contacto;
				$host = $email_data->host_contacto;

			}

			$this->load->library('email');

			$config['protocol']    = $tserver;
			$config['smtp_host']    = $host;
			$config['smtp_port']    = $port;
			$config['smtp_timeout'] = '7';
			$config['smtp_user']    = $email;
			$config['smtp_pass']    = $pass;
			$config['charset']    = 'utf-8';
			$config['newline']    = "\r\n";
			$config['mailtype'] = 'html'; // or html
			$config['validation'] = TRUE; // bool whether to validate email or not      			

			//var_dump($config);
			$this->email->initialize($config);		  		

		    $this->email->from($email, 'Prueba');
		    $this->email->to('rodrigog.84@gmail.com');

		    //$this->email->bcc(array('rodrigo.gonzalez@info-sys.cl','cesar.moraga@info-sys.cl','sergio.arriagada@info-sys.cl','rene.gonzalez@info-sys.cl')); 
		    $this->email->subject('Prueba de Envío');
		    $this->email->message("Este es un mensaje de prueba");	
		    try {
		      //$this->email->send();
		    	var_dump($this->email->send());
			  $result['success'] = true;
			  $result['message'] = "Email enviado correctamente. Favor verificar casilla de correos";


		    } catch (Exception $e) {
		      echo $e->getMessage() . '<br />';
		      echo $e->getCode() . '<br />';
		      echo $e->getFile() . '<br />';
		      echo $e->getTraceAsString() . '<br />';		    	
			  $result['success'] = false;
			  $result['message'] = "Error en envío de email. ". $e->getMessage();		    	
		    }		
					        
		 }else{

			$result['success'] = false;
			$result['message'] = "Error en envío de email. No existe cuenta de correo configurada";
		 }	


		 echo json_encode($result);
	}


public function lectura_mail($idempresa){

		set_time_limit(4000); 
		 
		// Connect to gmail
		$this->load->model('facturaelectronica');
		$email_data = $this->facturaelectronica->get_email($idempresa);
		//echo "<pre>";
		//var_dump($email_data);// exit;
		if(count($email_data) > 0){



				//$imapPath = '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX';
			$imapPath = '{outlook.office365.com:993/imap/ssl/novalidate-cert}INBOX';
				$username = $email_data->email_contacto;
				$password = $email_data->pass_contacto;
				// try to connect 
				$inbox = imap_open($imapPath,$username,$password) or die('Cannot connect to Outlook: ' . imap_last_error());


			   // $emails = imap_search($inbox,'SUBJECT "Envio de DTEs"  SINCE "01-08-2017" UNSEEN' );
			    $date = date ( "j F Y", strToTime ( "-20 days" ) );
			   // echo $date; exit;
			     $emails = imap_search($inbox,'SUBJECT "Envio de DTEs" SINCE "' . $date . '" ' );
			   //  $emails = imap_search($inbox,'ALL' );
			     //var_dump($emails); exit;

				$output = '';
				$array_dtes = array();
				$num_dtes = 0;

				 //	echo count($emails); exit;
				foreach($emails as $mail) {
				    
				    $headerInfo = imap_headerinfo($inbox,$mail);
				    //echo "<pre>";
				    //print_r($headerInfo);
				    $output .= $headerInfo->subject.'<br/>';
				    $output .= $headerInfo->toaddress.'<br/>';
				    $output .= $headerInfo->date.'<br/>';
				    $output .= $headerInfo->fromaddress.'<br/>';
				    $output .= $headerInfo->reply_toaddress.'<br/>';
				    $proveedor_nombre = $headerInfo->from[0]->personal;
				    $proveedor_mail = $headerInfo->from[0]->mailbox."@".$headerInfo->from[0]->host;
				    //var_dump($proveedor_nombre);
				    //var_dump($proveedor_mail); exit;
				    
				    $emailStructure = imap_fetchstructure($inbox,$mail);
				    //print_r($emailStructure); 
					if (isset($emailStructure->parts) && count($emailStructure->parts)) {
						
						// loop through all attachments
							for ($i = 0; $i < count($emailStructure->parts); $i++) {

								// set up an empty attachment
								$attachments[$i] = array(
									'is_attachment' => FALSE,
									'filename'      => '',
									'name'          => '',
									'attachment'    => ''
								);


								if ($emailStructure->parts[$i]->ifdparameters) {
									foreach ($emailStructure->parts[$i]->dparameters as $object) {
										// if this attachment is a file, mark the attachment and filename
										if (strtolower($object->attribute) == 'filename') {
											$attachments[$i]['is_attachment'] = TRUE;
											$attachments[$i]['filename']      = $object->value;
										}
									}
								}


								// if this attachment has ifparameters, then proceed as above
								if ($emailStructure->parts[$i]->ifparameters) {
									foreach ($emailStructure->parts[$i]->parameters as $object) {
										if (strtolower($object->attribute) == 'name') {
											$attachments[$i]['is_attachment'] = TRUE;
											$attachments[$i]['name']          = $object->value;
										}
									}
								}


								// if we found a valid attachment for this 'part' of the email, process the attachment
								if ($attachments[$i]['is_attachment']) {
									// get the content of the attachment
									$attachments[$i]['attachment'] = imap_fetchbody($inbox, $mail, $i+1);

									// check if this is base64 encoding
									if ($emailStructure->parts[$i]->encoding == 3) { // 3 = BASE64
										$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
									}
									// otherwise, check if this is "quoted-printable" format
									elseif ($emailStructure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
										$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
									}
								}


								//print_r($attachments);
								foreach ($attachments as $attachment) {
									if($attachment['is_attachment'] == 1 && substr($attachment['filename'],-3) == 'xml'){
										$array_dtes[$num_dtes]['filename'] = $attachment['filename'];
										$array_dtes[$num_dtes]['content'] = $attachment['attachment'];
										$array_dtes[$num_dtes]['proveedor_mail'] = $proveedor_mail;
										$array_dtes[$num_dtes]['proveedor_nombre'] = $proveedor_nombre;
										$num_dtes++;
									}
								}



								//	echo "<br><br>";

							}

					}

				}
				//print_r($array_dtes); exit;
				//exit;
				foreach ($array_dtes as $dte) {
					$codproceso = $this->facturaelectronica->dte_compra($dte,$idempresa);
				}		
				 
				// colse the connection
				imap_expunge($inbox);
				imap_close($inbox);

		}


	}

	
}










<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth Model
*
* Version: 2.5.2
*
* Author:  Ben Edmunds
* 		   ben.edmunds@gmail.com
*	  	   @benedmunds
*
* Added Awesomeness: Phil Sturgeon
*
* Location: http://github.com/benedmunds/CodeIgniter-Ion-Auth
*
* Created:  10.01.2009
*
* Last Change: 3.22.13
*
* Changelog:
* * 3-22-13 - Additional entropy added - 52aa456eef8b60ad6754b31fbdcc77bb
*
* Description:  Modified auth system based on redux_auth with extensive customization.  This is basically what Redux Auth 2 should be.
* Original Author name has been kept but that does not mean that the method has not been modified.
*
* Requirements: PHP5 or above
*
*/

class Facturaelectronica extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('cookie');
		$this->load->helper('date');
	}



	public function ruta_libredte(){
		$base_path = __DIR__;
		$base_path = str_replace("\\", "/", $base_path);
		$path = $base_path . "/../libraries/inc.php";		
		return $path;
	}

	public function genera_config(){
		$config = [
		    'firma' => [
		        'file' => $this->ruta_certificado(),
		        'pass' => $this->busca_parametro_fe('cert_password'),
		    ],
		];

		return $config;
	}


	public function ruta_certificado($extension = 'p12'){
		$base_path = __DIR__;
		$base_path = str_replace("\\", "/", $base_path);
		$path = $base_path . "/../../facturacion_electronica/certificado/certificado_" .  $this->session->userdata('empresaid') ."." .$extension;		
		//echo $path; exit;
		return $path;
	}

	 public function busca_parametro_fe($parametro){
		$this->db->select('valor ')
		  ->from('param_fe')
		  ->where('nombre',$parametro);
		$query = $this->db->get();
		$parametro = $query->row();	
		return $parametro->valor;
	 }	


	 public function set_parametro_fe($parametro,$valor){
		  $this->db->where('nombre',$parametro);
		  $this->db->update('param_fe',array('valor' => $valor));
		return 1;
	 }		 


	 public function put_trackid($idfactura,$trackid){
		  $this->db->where('idfactura',$idfactura);
		  $this->db->update('folios_caf',array('trackid' => $trackid));
		return 1;
	 }		 

	 public function contribuyentes_autorizados($start = null,$limit = null){

	 	//$tabla_contribuyentes = $this->busca_parametro_fe('tabla_contribuyentes');
	 	$tabla_contribuyentes = 'contribuyentes_autorizados';

	 	$countAll = $this->db->count_all_results($tabla_contribuyentes);

		$data = $this->db->select("rut, dv, concat(rut,'-',dv) as rut_contribuyente, razon_social, nro_resolucion, format(fec_resolucion,'dd/MM/yyyy','en-US') as fec_resolucion, mail, url",false)
		  ->from($tabla_contribuyentes)
		  ->order_by('razon_social');

		$data = is_null($start) || is_null($limit) ? $data : $data->limit($limit,$start);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return array('total' => $countAll, 'data' => $query->result());

	 }

	 public function log_libros($start = null,$limit = null){

	 	$countAll = $this->db->count_all_results('log_libros');
		$data = $this->db->select('id, mes, anno, tipo_libro, archivo, date_format(created_at,"%d/%m/%Y") as fecha_creacion',false)
		  ->from('log_libros')
		  ->order_by('anno','desc')
		  ->order_by('mes','desc');

		$data = is_null($start) || is_null($limit) ? $data : $data->limit($limit,$start);
		$query = $this->db->get();
		return array('total' => $countAll, 'data' => $query->result());

	 }

	public function get_empresa(){
		$this->db->select('rut, dv, razon_social, giro, cod_actividad, dir_origen, comuna_origen, fec_resolucion, nro_resolucion, logo, idregion, idcomuna, telefono, mail ')
		  ->from('empresa')
		  ->where('idempresa',$this->session->userdata('empresaid'))
		  ->limit(1);
		$query = $this->db->get();
		return $query->row();
	 }


	public function datos_dte_periodo($mes,$anno){
		$this->db->select('f.folio, f.path_dte, f.archivo_dte, f.dte, f.pdf, f.pdf_cedible, f.trackid, c.tipo_caf, tc.nombre as tipo_doc ')
		  ->from('folios_caf f')
		  ->join('caf c','f.idcaf = c.id')
		  ->join('tipo_caf tc','c.tipo_caf = tc.id')
		  ->join('factura_clientes fc','f.idfactura = fc.id','left')
		  //->where('left(fc.fecha_factura,7)',$anno."-".$mes);
		  ->where('left(f.updated_at,7)',$anno."-".$mes) //AUN TENEMOS FACTURAS QUE NO SE EMITEN POR EL SISTEMA
		  ->where('f.estado','O');
		$query = $this->db->get();
		return $query->result();
	}



	public function datos_dte_proveedores_periodo($mes,$anno){
		$this->db->select('d.id, d.idproveedor, d.dte, d.envios_recibos, d.recepcion_dte, d.resultado_dte ')
		  ->from('dte_proveedores d')
		  ->where('left(d.fecha_documento,7)',$anno."-".$mes);
		$query = $this->db->get();
		return $query->result();
	}


	public function valida_existe_libro($mes,$anno,$tipo){
		$this->db->select('id, mes, anno, tipo_libro ')
		  ->from('log_libros')
		  ->where('mes',$mes)
		  ->where('anno',$anno)
		  ->where('tipo_libro',$tipo);
		$query = $this->db->get();
		return count($query->result()) > 0 ? true : false;
	}


	public function put_log_libros($mes,$anno,$tipo,$archivo){

			$array_insert = array(
						'mes' => $mes,
						'anno' => $anno,
						'tipo_libro' => $tipo,
						'archivo' => $archivo
						);

		$this->db->insert('log_libros',$array_insert); 
		return true;
	}



	public function get_empresa_factura($id_factura){

		$tabla_contribuyentes = $this->busca_parametro_fe('tabla_contribuyentes');

		$this->db->select('c.nombres as nombre_cliente, c.rut as rut_cliente, c.direccion, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, c.fono, e.nombre as giro, isnull(ca.mail,c.e_mail) as e_mail',false)
		  ->from('factura_clientes acc')
		  ->join('clientes c','acc.id_cliente = c.id','left')
		  ->join('cod_activ_econ e','c.id_giro = e.id','left')
		  ->join('comuna m','c.id_comuna = m.id','left')		  
		  ->join('ciudad s','c.id_ciudad = s.id','left')	
		  ->join($tabla_contribuyentes . ' ca','c.rut = concat(ca.rut,ca.dv)','left')
		  ->where('acc.id',$id_factura)
		  ->limit(1);
		$query = $this->db->get();
		return $query->row();
	 }	 


	public function get_detalle_factura($id_factura){
		$this->db->select('p.nombre, f.precio, f.cantidad, f.descuento , f.iva, f.totalproducto')
		  ->from('detalle_factura_cliente f')
		  ->join('productos p','f.id_producto = p.id')
		  ->where('f.id_factura',$id_factura);
		$query = $this->db->get();
		return $query->result();
	 }

	public function get_detalle_factura_glosa($id_factura){
		$this->db->select('f.glosa, f.cantidad, f.neto, f.iva, f.total ')
		  ->from('detalle_factura_glosa f')
		  ->where('f.id_factura',$id_factura);
		$query = $this->db->get();
		return $query->result();
	 }	 


	public function get_content_caf_folio($folio,$tipo_documento,$idempresa){
		$this->db->select('f.estado, c.archivo, c.caf_content ')
		  ->from('caf c')
		  ->join('folios_caf f','f.idcaf = c.id')
		  ->where('f.folio',$folio)
		  ->where('c.tipo_caf',$tipo_documento)
		  ->where('c.idempresa',$idempresa)
		  ->limit(1);
		  $query = $this->db->get();
		  $caf = $query->row();					  
		  return $caf;
	 }	 

	public function datos_dte($idfactura){

		$this->db->select('f.id, f.folio, f.path_dte, f.archivo_dte, f.dte, f.pdf, f.pdf_cedible, f.trackid, c.tipo_caf, tc.nombre as tipo_doc, cae.nombre as giro ')
		  ->from('folios_caf f')
		  ->join('caf c','f.idcaf = c.id')
		  ->join('tipo_caf tc','c.tipo_caf = tc.id')
		  ->join('factura_clientes fc','f.idfactura = fc.id','left')
		  ->join('clientes cl','fc.id_cliente = cl.id','left')
		  ->join('cod_activ_econ cae','cl.id_giro = cae.id','left')

		  ->where('f.idfactura',$idfactura)
		  ->limit(1);
		$query = $this->db->get();
		return $query->row();
	}	



	public function get_libro_by_id($idlibro){
		$this->db->select('id, mes, anno, tipo_libro, archivo, created_at ')
		  ->from('log_libros')
		  ->where('id',$idlibro);
		$query = $this->db->get();
		return $query->row();
	}	

	public function datos_dte_by_trackid($trackid){
		$this->db->select('f.id, f.folio, f.path_dte, f.archivo_dte, f.dte, f.pdf, f.pdf_cedible, f.trackid, c.tipo_caf, tc.nombre as tipo_doc ')
		  ->from('folios_caf f')
		  ->join('caf c','f.idcaf = c.id')
		  ->join('tipo_caf tc','c.tipo_caf = tc.id')
		  ->where('f.trackid',$trackid)
		  ->limit(1);
		$query = $this->db->get();
		return $query->row();
	}	



	public function datos_dte_provee($iddte){
		$this->db->select('d.id, p.nombres as proveedor, p.e_mail, d.path_dte, d.arch_rec_dte, d.arch_res_dte, d.arch_env_rec, date_format(d.fecha_documento,"%d/%m/%Y") as fecha_documento , date_format(d.created_at,"%d/%m/%Y") as fecha_creacion ',false)
		  ->from('dte_proveedores d')
		  ->join('proveedores p','d.idproveedor = p.id')
		  ->where('d.id',$iddte)
		  ->order_by('d.id','desc');
		$query = $this->db->get();
		return $query->row();
	}


	 public function exportFePDF($idfactura,$tipo_consulta,$cedible = null){

	 	include $this->ruta_libredte();
	 	if($tipo_consulta == 'id'){
	 		$factura = $this->datos_dte($idfactura);
	 	}else if($tipo_consulta == 'trackid'){
	 		$factura = $this->datos_dte_by_trackid($idfactura);
	 	}

	 	$nombre_pdf = is_null($cedible) ? $factura->pdf : $factura->pdf_cedible;

	 	//file_exists 
	 	$crea_archivo = true;
	 	if($nombre_pdf != ''){
			$base_path = __DIR__;
			$base_path = str_replace("\\", "/", $base_path);
			$file = $base_path . "/../../facturacion_electronica/pdf/".$factura->path_dte.$nombre_pdf;	
	 		if(file_exists($file)){
	 			$crea_archivo = false;
	 		}
	 	}

	 	if($crea_archivo){
			// sin límite de tiempo para generar documentos
			set_time_limit(0);
		 	// archivo XML de EnvioDTE que se generará
		 	$archivo = "./facturacion_electronica/dte/".$factura->path_dte.$factura->archivo_dte;
		 	if(file_exists($archivo)){
		 		$content_xml = file_get_contents($archivo);
		 	}else{
		 		$content_xml = $factura->dte;
		 	}

		 	// Cargar EnvioDTE y extraer arreglo con datos de carátula y DTEs
		 	$EnvioDte = new \sasco\LibreDTE\Sii\EnvioDte();
		 	$EnvioDte->loadXML($content_xml);
			$Caratula = $EnvioDte->getCaratula();
			$Documentos = $EnvioDte->getDocumentos();	 	

			if(!file_exists('./facturacion_electronica/pdf/'.$factura->path_dte)){
				mkdir('./facturacion_electronica/pdf/'.$factura->path_dte,0777,true);
			}		

			$base_path = __DIR__;
			$base_path = str_replace("\\", "/", $base_path);
			$path_pdf = $base_path . "/../../facturacion_electronica/pdf/".$factura->path_dte;				


			$empresa = $this->get_empresa();
			foreach ($Documentos as $DTE) {
			    if (!$DTE->getDatos())
			        die('No se pudieron obtener los datos del DTE');
			    $pdf = new \sasco\LibreDTE\Sii\PDF\Dte(false); // =false hoja carta, =true papel contínuo (false por defecto si no se pasa)
			    $pdf->setFooterText();
			    $pdf->setLogo('./facturacion_electronica/images/logo_empresa.png'); // debe ser PNG!
			    $pdf->setGiroCliente($factura->giro); 
			    $pdf->setGiroEmisor($empresa->giro); 
			    $pdf->setResolucion(['FchResol'=>$Caratula['FchResol'], 'NroResol'=>$Caratula['NroResol']]);
			    /*if(!is_null($cedible)){
			    	$pdf->setCedible(true);
			    }*/
			    $pdf->agregar($DTE->getDatos(), $DTE->getTED());
			    if($factura->tipo_caf == 33 || $factura->tipo_caf == 34 ||  $factura->tipo_caf == 46 || $factura->tipo_caf == 52){
				    $pdf->setCedible(true);
				    $pdf->agregar($DTE->getDatos(), $DTE->getTED());			    	
			    }


			    //$pdf->Output('facturacion_electronica/pdf/'.$factura->path_dte.'dte_'.$Caratula['RutEmisor'].'_'.$DTE->getID().'.pdf', 'FI');
			    $archivo = 'dte_'.$Caratula['RutEmisor'].'_'.$DTE->getID();
			    $nombre_archivo = $archivo.".pdf";
			    //$tipo_generacion = is_null($cedible) ? 'FI' : 'F';
			    $tipo_generacion = 'FI';
			    $pdf->Output($path_pdf.$nombre_archivo, $tipo_generacion);
			    $nombre_campo = is_null($cedible) ? 'pdf' : 'pdf_cedible';

			    $this->db->where('idfactura', $idfactura);
				$this->db->update('folios_caf',array($nombre_campo => $nombre_archivo)); 		    

			}		

		}else{

			$filename = $nombre_pdf; /* Note: Always use .pdf at the end. */

			header('Content-type: application/pdf');
			header('Content-Disposition: inline; filename="' . $filename . '"');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: ' . filesize($file));
			header('Accept-Ranges: bytes');

			@readfile($file);


		}
	}
	 


	public function get_contribuyentes(){

		
		//$this->db->trans_start();
		header('Content-type: text/plain; charset=ISO-8859-1');

		$config = $this->genera_config();
		include $this->ruta_libredte();
		// solicitar datos
		$datos = \sasco\LibreDTE\Sii::getContribuyentes(
		    new \sasco\LibreDTE\FirmaElectronica($config['firma']),
		    \sasco\LibreDTE\Sii::PRODUCCION
		);

		var_dump($datos); exit;
		$tabla_contribuyentes = $this->busca_parametro_fe('tabla_contribuyentes');
		$tabla_inserta = $tabla_contribuyentes == 'contribuyentes_autorizados_1' ? 'contribuyentes_autorizados_2' : 'contribuyentes_autorizados_1';

		
		foreach ($datos as $dato) {

			$array_rut = explode("-",$dato[0]);
			$array_insert = array(
								'rut' => $array_rut[0],
								'dv' => $array_rut[1],
								'razon_social' => $dato[1],
								'nro_resolucion' => $dato[2],
								'fec_resolucion' => formato_fecha($dato[3],'d-m-Y','Y-m-d'),
								'mail' => $dato[4],
								'url' => $dato[5]
							);

			$this->db->insert($tabla_inserta,$array_insert); 


		}


		$array_insert = array(
						'nombre_archivo' => null,
						'ruta' => null,
						);

		$this->db->insert('log_cargas_bases_contribuyentes',$array_insert); 


		$this->db->select('count(*) as cantidad')
			  ->from($tabla_inserta);
		$query = $this->db->get();
		if(isset($query->row()->cantidad)){
			if($query->row()->cantidad > 0){
				$this->set_parametro_fe('tabla_contribuyentes',$tabla_inserta);
				$this->db->query('truncate '. $tabla_contribuyentes);				
			}

		}


		//$this->db->trans_complete(); 		

	}	




	public function carga_contribuyentes($path_base,$archivo){

		$this->db->trans_start();
		$this->db->query('truncate contribuyentes_autorizados'); 

		$base_path = __DIR__;
		$base_path = str_replace("\\", "/", $base_path);
		
		$file = $base_path . "/../../facturacion_electronica/base_contribuyentes/".$path_base.$archivo;				



		$this->db->query('LOAD DATA LOW_PRIORITY LOCAL INFILE "' . $file . '" REPLACE INTO TABLE contribuyentes_autorizados FIELDS TERMINATED BY ";" LINES TERMINATED BY "\n" IGNORE 1 LINES (rut,razon_social,nro_resolucion,fec_resolucion,mail,url);'); 

		$tabla_contribuyentes = $this->busca_parametro_fe('tabla_contribuyentes');
		$tabla_inserta = $tabla_contribuyentes == 'contribuyentes_autorizados_1' ? 'contribuyentes_autorizados_2' : 'contribuyentes_autorizados_1';

		$this->db->query("insert into " . $tabla_inserta . " (rut,dv,razon_social,nro_resolucion,fec_resolucion,mail,url)
						select SUBSTRING_INDEX(rut, '-', 1) as rut, SUBSTRING_INDEX(rut, '-', -1) as dv, razon_social, nro_resolucion, concat(SUBSTRING(fec_resolucion,7,4),'-',SUBSTRING(fec_resolucion,4,2),'-',SUBSTRING(fec_resolucion,1,2)) as fec_resolucion, mail, url  from contribuyentes_autorizados");

		$array_insert = array(
						'nombre_archivo' => $archivo,
						'ruta' => $path_base,
						);

		$this->db->insert('log_cargas_bases_contribuyentes',$array_insert); 


		$this->set_parametro_fe('tabla_contribuyentes',$tabla_inserta);

		$this->db->query('truncate '. $tabla_contribuyentes);

		$this->db->trans_complete(); 		

	 }	 


	 public function registro_email($data){

		$this->db->select('id')
		  ->from('email_fe')
		  ->where('idempresa',$this->session->userdata('empresaid'));
		$query = $this->db->get();
		$email = $query->row();	 		

        	if(count($email) > 0){ //actualizar
        		$this->db->where('idempresa',$this->session->userdata('empresaid'));
        		$this->db->update('email_fe',$data);
        	}else{ //insertar
        		$data['created_at'] = date("Y-m-d H:i:s");
        		$data['idempresa'] = $this->session->userdata('empresaid');
				$this->db->insert('email_fe',$data);
        	}	 	
        return true;
	 }

	public function get_email($idempresa = null){
		$email_data = $this->db->select('email_contacto, pass_contacto, tserver_contacto, port_contacto, host_contacto, email_intercambio, pass_intercambio, tserver_intercambio, port_intercambio, host_intercambio ')
		  ->from('email_fe');

		 $email_data = is_null($idempresa) ? $email_data->where('idempresa',$this->session->userdata('empresaid')) : $email_data->where('idempresa',$idempresa);
		$query = $this->db->get();
		return $query->row();
	 }


	public function get_provee_by_id($idcompra){
		$this->db->select('l.id, l.path, l.filename, l.rutemisor, l.dvemisor, l.fecemision, l.procesado, c.razon_social, c.mail, l.fecenvio, l.created_at, l.envios_recibos, l.recepcion_dte, l.resultado_dte, l.arch_env_rec, l.arch_rec_dte, l.arch_res_dte, estado_res_dte ',false)
		  ->from('lectura_dte_email l')
		  ->join('contribuyentes_autorizados_1 c','l.rutemisor = c.rut','left')
		  ->where('l.id',$idcompra);
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		return $query->row();
	 }





	public function envia_email_acuse_recibo($idfactura,$email_respuesta){

			$resumen_dte = $this->facturaelectronica->reporte_provee($idfactura);
			//$factura = $this->datos_dte($idfactura);
			//echo "<pre>";
			//print_r($resumen_dte);
		
			//$nombre_dte = $factura->archivo_dte_cliente != '' ? $factura->archivo_dte_cliente : $factura->archivo_dte;
			//$nombre_dte = $factura->archivo_dte;

			$empresa = $this->get_empresa();
			$datos_empresa_factura = $this->get_empresa_factura($idfactura);

			$messageBody  = 'Envío de Acuse de Recibo<br><br>';
	        $messageBody .= '<b>Datos Emisor:</b><br>';
	        $messageBody .= $empresa->razon_social.'<br>';
	        $messageBody .= 'RUT:'.$empresa->rut.'-'.$empresa->dv .'<br><br>';

	        //$messageBody .= '<a href="'. base_url() .'facturas/exportFePDF_mail/'.$track_id.'" >Ver Factura</a><br><br>';

	       // $messageBody .= 'Este correo adjunta Documentos Tributarios Electrónicos (DTE) para el receptor electrónico indicado. Por favor responda con un acuse de recibo (RespuestaDTE) conforme al modelo de intercambio de Factura Electrónica del SII.<br><br>';
	        $messageBody .= 'Facturación Electrónica Infosys SPA.';


	        $email_data = $this->facturaelectronica->get_email();
	      //  print_r(count($email_data)); exit;
		    if(count($email_data) > 0 ){ //MAIL SE ENVÍA SÓLO EN CASO QUE TENGAMOS REGISTRADOS EMAIL DE ORIGEN Y DESTINO
		    	$this->load->library('email');
				$config['protocol']    = $email_data->tserver_intercambio;
				$config['smtp_host']    = $email_data->host_intercambio;
				$config['smtp_port']    = $email_data->port_intercambio;
				$config['smtp_timeout'] = '7';
				$config['smtp_user']    = $email_data->email_intercambio;
				$config['smtp_pass']    = $email_data->pass_intercambio;
				$config['charset']    = 'utf-8';
				$config['newline']    = "\r\n";
				$config['mailtype'] = 'html'; // or html
				$config['validation'] = TRUE; // bool whether to validate email or not      			


		        $this->email->initialize($config);		  		
				
			    $this->email->from($email_data->email_intercambio, 'Factura Electrónica '. NOMBRE_EMPRESA);
			    $this->email->to($email_respuesta);

			    #$this->email->bcc(array('rodrigo.gonzalez@info-sys.cl','cesar.moraga@info-sys.cl','sergio.arriagada@info-sys.cl','rene.gonzalez@info-sys.cl')); 
			    $this->email->subject('Envio de Acuse de Recibo  '.$empresa->rut.'-'.$empresa->dv);
			    $this->email->message($messageBody);

			    //$this->email->attach('./facturacion_electronica/dte/'.$path.$nombre_dte);
				//$ruta =  $factura->archivo_dte_cliente != '' ? 'dte_cliente' : 'dte';

				if($resumen_dte->envios_recibos != '1'){
					$this->email->attach('./facturacion_electronica/acuse_recibo/' . $resumen_dte->path .$resumen_dte->arch_env_rec);			
				}
 			    

				$this->email->attach('./facturacion_electronica/acuse_recibo/' . $resumen_dte->path .$resumen_dte->arch_rec_dte);	

				$this->email->attach('./facturacion_electronica/acuse_recibo/' . $resumen_dte->path .$resumen_dte->arch_res_dte);	
 					/*$this->email->send();
			      var_dump($this->email->print_debugger());*/

			    try {
			      $this->email->send();
					$this->db->where('id',$idfactura);
					$this->db->update('lectura_dte_email',array('fecenvio' => date('Ymd H:i:s'))); 			      
			     // var_dump($this->email->print_debugger());
			      	        //exit;
			    } catch (Exception $e) {
			      echo $e->getMessage() . '<br />';
			      echo $e->getCode() . '<br />';
			      echo $e->getFile() . '<br />';
			      echo $e->getTraceAsString() . '<br />';
			      echo "no";

			    }
			    return true;

			}else{

				return false;
			}

	}



	public function reporte_provee($idfactura = null){

	
		$data_provee = $this->db->select("folio, caf.nombre as tipo_documento, l.id, c.razon_social, l.path, l.filename, concat(l.rutemisor,'-',l.dvemisor) rutemisor, c.mail, l.fecemision, l.fecenvio, l.fecgeneraacuse,  l.created_at, l.procesado, l.content, l.proveenombre, l.proveemail, l.envios_recibos, l.path, l.arch_env_rec, l.arch_rec_dte, l.arch_res_dte, monto_afecto, monto_exento, monto_neto, iva, monto_total, fecvenc as fec_pago_vencimiento",false)
		  ->from('lectura_dte_email l')
		  ->join('contribuyentes_autorizados_1 c','l.rutemisor = c.rut','left')
		  ->join('tipo_caf caf','l.tipodoc = caf.id','left')
		  ->where('idempresa',$this->session->userdata('empresaid'))
		  ->order_by('l.id');

		//$data_provee = !$limit ? $data_provee : $data_provee->limit($limit,$start);
		$user_data = is_null($idfactura) ? $data_provee : $data_provee->where('l.id',$idfactura);  
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		//$result = $query->result();
		//var_dump($result); exit;
		// return array('cantidad' => $result_cantidad,'data' => $result);
		return is_null($idfactura) ? $query->result() :  $query->row();
	}





	public function facturas_venta($idfactura = null){

	
		/*$data_provee = $this->db->select("l.id, c.razon_social, l.path, l.filename, concat(l.rutemisor,'-',l.dvemisor) rutemisor, c.mail, l.fecemision, l.fecenvio, l.fecgeneraacuse,  l.created_at, l.procesado, l.content, l.proveenombre, l.proveemail, l.envios_recibos, l.path, l.arch_env_rec, l.arch_rec_dte, l.arch_res_dte",false)
		  ->from('lectura_dte_email l')
		  ->join('contribuyentes_autorizados_1 c','l.rutemisor = c.rut','left')
		  ->order_by('l.id')
		  ->limit(3);

		//$data_provee = !$limit ? $data_provee : $data_provee->limit($limit,$start);
		$user_data = is_null($idfactura) ? $data_provee : $data_provee->where('l.id',$idfactura);  
		$query = $this->db->get();
		return is_null($idfactura) ? $query->result() :  $query->row();
		*/

		$data_provee = $this->db->select("f.id
										,f.num_factura
										,td.descripcion as tipo_docto
										,convert(varchar,f.fecha_factura,103) as fecha_factura
										,convert(varchar,f.fecha_venc,103) as fecha_venc
										,c.rut
										,c.nombres as razon_social
										,f.totalfactura",false)
								  ->from('factura_clientes f')
								  ->join('clientes c','f.id_cliente = c.id','left')
								  ->join('tipo_documento td','f.tipo_documento = td.id','left')
								  ->where('f.idempresa',$this->session->userdata('empresaid'))
								  ->order_by('f.fecha_factura','desc')
								  ->order_by('f.num_factura','desc');

		//$data_provee = !$limit ? $data_provee : $data_provee->limit($limit,$start);
		$user_data = is_null($idfactura) ? $data_provee : $data_provee->where('f.id',$idfactura);  
		$query = $this->db->get();
		return is_null($idfactura) ? $query->result() :  $query->row();		
	}

	public function lectura_dte_provee($idfactura = null){

		$datos_factura = $this->reporte_provee($idfactura);
		//print_r($datos_factura); exit;
		$xml_archivo = './facturacion_electronica/dte_provee_tmp/'.$datos_factura->path.'/'.$datos_factura->filename;

		$xml_content = "";
		if(file_exists($xml_archivo)){
			$xml_content = file_get_contents($xml_archivo);
		}else{
			$xml_content = $datos_factura->content;
		}

		// EL RECEPTOR ESPERADO DEBE SER LA EMPRESA QUE ESTÁ USANDO EL SISTEMA
		$empresa = $this->facturaelectronica->get_empresa();
		$RutReceptor_esperado = $empresa->rut.'-'.$empresa->dv;
		//$RutReceptor_esperado = '1-9';
		$RutEmisor_esperado = $datos_factura->rutemisor;

		$config = $this->genera_config();
		include_once $this->ruta_libredte();	
		$EnvioDte = new \sasco\LibreDTE\Sii\EnvioDte();
		$EnvioDte->loadXML($xml_content);


		//obtenemos una sugerencia de estado del DTE
		$estado = $EnvioDte->getEstadoValidacion(['RutReceptor'=>$RutReceptor_esperado]);
		$RecepEnvGlosa = \sasco\LibreDTE\Sii\RespuestaEnvio::$estados['envio'][$estado];

		$documentos = $EnvioDte->getDocumentos();

		//ahora realizamos un analisis por documentos
		$i = 0;
		$RecepcionDTE = [];
		foreach ($documentos as $DTE) {
		    $estado = $DTE->getEstadoValidacion(['RUTEmisor'=>$RutEmisor_esperado, 'RUTRecep'=>$RutReceptor_esperado]);
		    $RecepcionDTE[] = [
		        'TipoDTE' => $DTE->getTipo(),
		        'Folio' => $DTE->getFolio(),
		        'FchEmis' => $DTE->getFechaEmision(),
		        'RUTEmisor' => $DTE->getEmisor(),
		        'RUTRecep' => $DTE->getReceptor(),
		        'MntTotal' => $DTE->getMontoTotal(),
		        'EstadoRecepDTE' => $estado,
		        'RecepDTEGlosa' => \sasco\LibreDTE\Sii\RespuestaEnvio::$estados['documento'][$estado],
		    ];
		}

		$array_resumen = array('idfactura' => $idfactura,
								'estado' => $estado,
							   'estado_Glosa' => $RecepEnvGlosa,
							   'resumen_documentos' => $RecepcionDTE
							);
		return $array_resumen;
	}



	public function envia_acuse_recibo($array_acuse){
		$this->db->trans_start();



		$array_insert_acuse = array(
							  'idlectura' => $array_acuse['idfactura'],
							  'estado_envio' => $array_acuse['estado_envio'],
							  'genera_mercaderias' => $array_acuse['mercaderias'] ? 1 : 0	
							  );

		$this->db->insert('fe_dte_acuse',$array_insert_acuse);

		$idacuse = $this->db->insert_id();

		foreach ($array_acuse['detalle_dte'] as $detalle_dte) {
			$array_insert_detalle_acuse = array(
								  'iddte' => $idacuse,
								  'tipodte' => $detalle_dte['TipoDTE'],
								  'folio' => $detalle_dte['Folio'],
								  'estado_dte' => $detalle_dte['Estado'],
								  );

			$this->db->insert('fe_dte_detalle_acuse',$array_insert_detalle_acuse);
		}



		$datos_factura = $this->reporte_provee($array_acuse['idfactura']);
		$xml_archivo = './facturacion_electronica/dte_provee_tmp/'.$datos_factura->path.'/'.$datos_factura->filename;

		$xml_content = "";
		if(file_exists($xml_archivo)){
			$xml_content = file_get_contents($xml_archivo);
		}else{
			$xml_content = $datos_factura->content;
		}

		// EL RECEPTOR ESPERADO DEBE SER LA EMPRESA QUE ESTÁ USANDO EL SISTEMA
		$empresa = $this->facturaelectronica->get_empresa();
		$RutReceptor_esperado = $empresa->rut.'-'.$empresa->dv;
		//$RutReceptor_esperado = '1-9';
		$RutEmisor_esperado = $datos_factura->rutemisor;
		$archivo_recibido = $datos_factura->filename;

		$result_recepcion = $this->recepciondte($xml_content,$RutEmisor_esperado,$RutReceptor_esperado,$archivo_recibido,$array_acuse);


		$error = false;
		if(!$result_recepcion){
					$error = true;
					$message = "Error en creación de Recepcion DTE.  Verifique formato y cargue nuevamente";

		}else{
			$xml_recepcion_dte = $result_recepcion;

			if(!$error){
				$result_resultado = $this->resultadodte($xml_content,$RutEmisor_esperado,$RutReceptor_esperado,$archivo_recibido);

				if(!$result_resultado){
					$error = true;
					$message = "Error en creación de Resultado DTE.  Verifique formato y cargue nuevamente";

				}else{
					$xml_resultado_dte = $result_resultado;

					if(!$error){
						if($array_acuse['mercaderias']){
							$result_envio_recibos = $this->envio_recibosdte($xml_content,$RutEmisor_esperado,$RutReceptor_esperado,$archivo_recibido);
						}else{

							$result_envio_recibos =  true;	
						}
						if(!$result_envio_recibos){
							$error = true;
							$message = "Error en creación de Envio de Recibo.  Verifique formato y cargue nuevamente";

						}else{
							$xml_envio_recibosdte = $result_envio_recibos;
						}

					}


				}

			}


		}



// COMENZAR A ALMACENAR
		if(!$error){

			$nombre_recepcion_dte = "RecepcionDTE_".$array_acuse['idfactura']."_".date("His").".xml"; // nombre archivo
			$nombre_resultado_dte = "ResultadoDTE_".$array_acuse['idfactura']."_".date("His").".xml"; // nombre archivo
			$nombre_envio_recibo = "EnvioRecibo_".$array_acuse['idfactura']."_".date("His").".xml"; // nombre archivo
			//$path_acuse = date('Ym').'/'; // ruta guardado
			if(!file_exists('./facturacion_electronica/acuse_recibo/'.$datos_factura->path)){
				mkdir('./facturacion_electronica/acuse_recibo/'.$datos_factura->path,0777,true);
			}		

			//archivo recepcion		
			$f_archivo_recepcion_dte = fopen('./facturacion_electronica/acuse_recibo/'.$datos_factura->path.$nombre_recepcion_dte,'w');
			fwrite($f_archivo_recepcion_dte,$xml_recepcion_dte);
			fclose($f_archivo_recepcion_dte);


			//archivo resultado		
			$f_archivo_resultado_dte = fopen('./facturacion_electronica/acuse_recibo/'.$datos_factura->path.$nombre_resultado_dte,'w');
			fwrite($f_archivo_resultado_dte,$xml_resultado_dte);
			fclose($f_archivo_resultado_dte);

			//archivo envio recibo	
			$f_archivo_envio_recibo = fopen('./facturacion_electronica/acuse_recibo/'.$datos_factura->path.$nombre_envio_recibo,'w');
			fwrite($f_archivo_envio_recibo,$xml_envio_recibosdte);
			fclose($f_archivo_envio_recibo);

			// Obtiene fecha de emisión de documento
			$EnvioDte = new \sasco\LibreDTE\Sii\EnvioDte();
			$EnvioDte->loadXML($xml_content);
			$Documentos = $EnvioDte->getDocumentos();
			$Documento = $Documentos[0];
			$fec_documento = $Documento->getFechaEmision();

			$array_insert = array(
							'envios_recibos' => iconv('','UTF-8//IGNORE',$xml_envio_recibosdte),
							'recepcion_dte' => iconv('','UTF-8//IGNORE',$xml_recepcion_dte),
							'resultado_dte' => iconv('','UTF-8//IGNORE',$xml_resultado_dte),
							'arch_env_rec' => $nombre_envio_recibo,
							'arch_rec_dte' => $nombre_recepcion_dte,
							'arch_res_dte' => $nombre_resultado_dte,
							'fecgeneraacuse' => date('Ymd H:i:s')	
							);

			$this->db->where('id',$array_acuse['idfactura']);
			$this->db->update('lectura_dte_email',$array_insert); 
		}else{


			return -1;
		}
		//falta generar los archivos
		//falta guardar en base de datos que los archivos estan generados
		//falta enviar por correo




		$this->db->trans_complete();

	}	


	public function exportFePDFCompra($idcompra){

		$empresa = $this->facturaelectronica->get_empresa();

		$dte = $this->reporte_provee($idcompra);
		echo "<pre>";
		//print_r($dte);
		$path_archivo = "./facturacion_electronica/dte_provee_tmp/".$dte->path;
		//echo $path_archivo; exit;
    	$xml_content = file_get_contents($path_archivo.$dte->filename);

    	//echo htmlentities($xml_content); exit;
	 	include $this->ruta_libredte();
		$EnvioDte = new \sasco\LibreDTE\Sii\EnvioDte();
		$EnvioDte->loadXML($xml_content);
		$Caratula = $EnvioDte->getCaratula();
		$Documentos = $EnvioDte->getDocumentos();

		//print_r($Documentos); exit;
		$path_pdf = './facturacion_electronica/pdf_compra/';
		if(!file_exists($path_pdf.$dte->path)){
			mkdir($path_pdf.$dte->path,0777,true);
		}	

		$base_path = __DIR__;
		$base_path = str_replace("\\", "/", $base_path);
		$path_pdf = $base_path . "/../../facturacion_electronica/pdf_compra/".$dte->path;				

		// directorio temporal para guardar los PDF
			// procesar cada DTEs e ir agregándolo al PDF
		foreach ($Documentos as $DTE) {
			//print_r($DTE); exit;
		    if (!$DTE->getDatos())
		        die('No se pudieron obtener los datos del DTE');
		    $pdf = new \sasco\LibreDTE\Sii\PDF\Dte(false); // =false hoja carta, =true papel contínuo (false por defecto si no se pasa)
		    $pdf->setFooterText();
		    $pdf->setLogo('./facturacion_electronica/images/' . $empresa->logo); // debe ser PNG!
		    $pdf->setResolucion(['FchResol'=>$Caratula['FchResol'], 'NroResol'=>$Caratula['NroResol']]);

		    //$pdf->setCedible(true);
		    $pdf->agregar($DTE->getDatos(), $DTE->getTED());
		    //echo $dir.'/dte_'.$Caratula['RutEmisor'].'_'.$DTE->getID().'.pdf'; exit;
		    $archivo = 'dte_'.$Caratula['RutEmisor'].'_'.$DTE->getID();
		    $nombre_archivo = $archivo.".pdf";		    
		    $pdf->Output($path_pdf.$nombre_archivo, 'FI');
		}

		//$data_archivo = basename($path_archivo.$dte->filename);
		//print_r($xml_content); 	 	
	}



	public function recepciondte($xml_content,$RutEmisor_esperado,$RutReceptor_esperado,$archivo_recibido,$array_acuse = null){

		header('Content-type: text/plain; charset=ISO-8859-1');
	 	
		$config = $this->genera_config();
		include_once $this->ruta_libredte();	
		//generación de 
		$EnvioDte = new \sasco\LibreDTE\Sii\EnvioDte();
		$EnvioDte->loadXML($xml_content);
		$Caratula = $EnvioDte->getCaratula();
		$Documentos = $EnvioDte->getDocumentos();	


		// caratula
		$caratula = [
		    'RutResponde' => $RutReceptor_esperado,
		    'RutRecibe' => $Caratula['RutEmisor'],
		    'IdRespuesta' => 1,
		    //'NmbContacto' => '',
		    //'MailContacto' => '',
		];


		// procesar cada DTE
		$RecepcionDTE = [];
		foreach ($Documentos as $DTE) {


			if(is_null($array_acuse)){

				$estado = $DTE->getEstadoValidacion(['RUTEmisor'=>$RutEmisor_esperado, 'RUTRecep'=>$RutReceptor_esperado]);
			}else{

				$tipoDTE = $DTE->getTipo();
				$folio = $DTE->getFolio();
				$estado = false;
				foreach ($array_acuse['detalle_dte'] as $detalle_dte) {

					if($detalle_dte['TipoDTE'] == $tipoDTE && $detalle_dte['Folio'] == $folio){

						$estado = $detalle_dte['Estado'];
					}


				}

				if(!$estado){
					$estado = $DTE->getEstadoValidacion(['RUTEmisor'=>$RutEmisor_esperado, 'RUTRecep'=>$RutReceptor_esperado]);
				}
			}


		    $RecepcionDTE[] = [
		        'TipoDTE' => $DTE->getTipo(),
		        'Folio' => $DTE->getFolio(),
		        'FchEmis' => $DTE->getFechaEmision(),
		        'RUTEmisor' => $DTE->getEmisor(),
		        'RUTRecep' => $DTE->getReceptor(),
		        'MntTotal' => $DTE->getMontoTotal(),
		        'EstadoRecepDTE' => $estado,
		        'RecepDTEGlosa' => \sasco\LibreDTE\Sii\RespuestaEnvio::$estados['documento'][$estado],
		    ];
		}


		// armar respuesta de envío
		$estado_dte = is_null($array_acuse) ? $EnvioDte->getEstadoValidacion(['RutReceptor'=>$RutReceptor_esperado]) : $array_acuse['estado_envio'];


		$RespuestaEnvio = new \sasco\LibreDTE\Sii\RespuestaEnvio();
		$RespuestaEnvio->agregarRespuestaEnvio([
		    'NmbEnvio' => basename($archivo_recibido),
		    'CodEnvio' => 1,
		    'EnvioDTEID' => $EnvioDte->getID(),
		    'Digest' => $EnvioDte->getDigest(),
		    'RutEmisor' => $EnvioDte->getEmisor(),
		    'RutReceptor' => $EnvioDte->getReceptor(),
		    'EstadoRecepEnv' => $estado_dte,
		    'RecepEnvGlosa' => \sasco\LibreDTE\Sii\RespuestaEnvio::$estados['envio'][$estado_dte],
		    'NroDTE' => count($RecepcionDTE),
		    'RecepcionDTE' => $RecepcionDTE,
		]);		

		//$this->load->model('facturaelectronica');
		$config = $this->genera_config();
		/*echo "<pre>";
		echo $estado_dte;
		print_r($RecepcionDTE);
		print_r($config);
		print_r($caratula);*/


		//$config = $this->genera_config();

		// asignar carátula y Firma
		$RespuestaEnvio->setCaratula($caratula);
		$RespuestaEnvio->setFirma(new \sasco\LibreDTE\FirmaElectronica($config['firma']));

		// generar XML
		$xml = $RespuestaEnvio->generar();

		// validar schema del XML que se generó
		if ($RespuestaEnvio->schemaValidate()) {
		    // mostrar XML al usuario, deberá ser guardado y subido al SII en:
		    // https://www4.sii.cl/pfeInternet
		    return $xml;
		}else{
			return false;
		}

		
	 }


	 public function resultadodte($xml_content,$RutEmisor_esperado,$RutReceptor_esperado,$archivo_recibido){

		header('Content-type: text/plain; charset=ISO-8859-1');
	 	//include $this->ruta_libredte();

		// Cargar EnvioDTE y extraer arreglo con datos de carátula y DTEs
		$EnvioDte = new \sasco\LibreDTE\Sii\EnvioDte();
		$EnvioDte->loadXML($xml_content);
		$Caratula = $EnvioDte->getCaratula();
		$Documentos = $EnvioDte->getDocumentos();


		// caratula
		$caratula = [
		    'RutResponde' => $RutReceptor_esperado,
		    'RutRecibe' => $Caratula['RutEmisor'],
		    'IdRespuesta' => 1,
		    //'NmbContacto' => '',
		    //'MailContacto' => '',
		];


		// objeto para la respuesta
		$RespuestaEnvio = new \sasco\LibreDTE\Sii\RespuestaEnvio();



		// procesar cada DTE
		$i = 1;
		foreach ($Documentos as $DTE) {
		    $estado = !$DTE->getEstadoValidacion(['RUTEmisor'=>$RutEmisor_esperado, 'RUTRecep'=>$RutReceptor_esperado]) ? 0 : 2;
		    $RespuestaEnvio->agregarRespuestaDocumento([
		        'TipoDTE' => $DTE->getTipo(),
		        'Folio' => $DTE->getFolio(),
		        'FchEmis' => $DTE->getFechaEmision(),
		        'RUTEmisor' => $DTE->getEmisor(),
		        'RUTRecep' => $DTE->getReceptor(),
		        'MntTotal' => $DTE->getMontoTotal(),
		        'CodEnvio' => $i++,
		        'EstadoDTE' => $estado,
		        'EstadoDTEGlosa' => \sasco\LibreDTE\Sii\RespuestaEnvio::$estados['respuesta_documento'][$estado],
		    ]);
		}

		$this->load->model('facturaelectronica');
		$config = $this->facturaelectronica->genera_config();

		//$config = $this->genera_config();

		// asignar carátula y Firma
		$RespuestaEnvio->setCaratula($caratula);
		$RespuestaEnvio->setFirma(new \sasco\LibreDTE\FirmaElectronica($config['firma']));		

		// generar XML
		$xml = $RespuestaEnvio->generar();

		// validar schema del XML que se generó
		if ($RespuestaEnvio->schemaValidate()) {
		    // mostrar XML al usuario, deberá ser guardado y subido al SII en:
		    // https://www4.sii.cl/pfeInternet
		    return $xml;
		}else{
			return false;
		}		
	
	 }


	 public function envio_recibosdte($xml_content,$RutEmisor_esperado,$RutReceptor_esperado,$archivo_recibido){

	 	$RutResponde = $RutReceptor_esperado;
		header('Content-type: text/plain; charset=ISO-8859-1');
	 	//include $this->ruta_libredte();


		// Cargar EnvioDTE y extraer arreglo con datos de carátula y DTEs
		$EnvioDte = new \sasco\LibreDTE\Sii\EnvioDte();
		$EnvioDte->loadXML($xml_content);
		$Caratula = $EnvioDte->getCaratula();
		$Documentos = $EnvioDte->getDocumentos();


		// caratula
		$caratula = [
		    'RutResponde' => $RutResponde,
		    'RutRecibe' => $Caratula['RutEmisor'],
		    //'NmbContacto' => '',
		    //'MailContacto' => '',
		];

		$this->load->model('facturaelectronica');
		$config = $this->facturaelectronica->genera_config();
		//$config = $this->genera_config();
		// objeto EnvioRecibo, asignar carátula y Firma
		$EnvioRecibos = new \sasco\LibreDTE\Sii\EnvioRecibos();
		$EnvioRecibos->setCaratula($caratula);
		$EnvioRecibos->setFirma(new \sasco\LibreDTE\FirmaElectronica($config['firma']));
		$Firma = new \sasco\LibreDTE\FirmaElectronica($config['firma']);
		$RutFirma = $Firma->getId();

		// procesar cada DTE
		foreach ($Documentos as $DTE) {
		    $EnvioRecibos->agregar([
		        'TipoDoc' => $DTE->getTipo(),
		        'Folio' => $DTE->getFolio(),
		        'FchEmis' => $DTE->getFechaEmision(),
		        'RUTEmisor' => $DTE->getEmisor(),
		        'RUTRecep' => $DTE->getReceptor(),
		        'MntTotal' => $DTE->getMontoTotal(),
		        'Recinto' => 'Oficina central',
		        'RutFirma' => $RutFirma,
		    ]);
		}

		// generar XML
		$xml = $EnvioRecibos->generar();


		// validar schema del XML que se generó
		if ($EnvioRecibos->schemaValidate()) {
		    // mostrar XML al usuario, deberá ser guardado y subido al SII en:
		    // https://www4.sii.cl/pfeInternet
		    return $xml;
		}else{
			return false;
		}		
	
	 }

	 public function dte_compra($dte,$idempresa){
	 	echo $dte['filename']."<br>";
	 	$this->db->select('filename')
	 			->from('lectura_dte_email')
	 			->where('filename',$dte['filename']);

		$query = $this->db->get();
		if(count($query->result()) == 0){

			$config = $this->genera_config();
			include_once $this->ruta_libredte();	
			$EnvioDte = new \sasco\LibreDTE\Sii\EnvioDte();
			$EnvioDte->loadXML($dte['content']);

			$empresa = $this->facturaelectronica->get_empresa();

			$receptor_factura = $EnvioDte->getReceptor();

			$array_receptor_factura = explode("-",$receptor_factura);

			$documentos = $EnvioDte->getDocumentos();
			//$documento = $documentos[0];
			//var_dump($documento->getDatos()); exit;
			//print_r($documento->getResumen()); exit;
			//echo $empresa->rut . " - " . $array_receptor_factura[0]; exit;
			foreach ($documentos as $DTE) {
				if($empresa->rut == $array_receptor_factura[0]){ // validamos que sea una factura de la empresa

					$rut_emisor = $EnvioDte->getEmisor();
				//	echo "<pre>";
					$folio = $DTE->getFolio();
					$array_datos = $DTE->getDatos();
				//	var_dump($array_datos['Encabezado']['IdDoc']['FchVenc']);
					$monto_total = $DTE->getMontoTotal();


					$resumen = $DTE->getResumen();
					$array_rut_emisor = explode("-",$rut_emisor);


					$path = date('Ym').'/'; // ruta guardado
					$array_insert = array(
										  'path' => $path,
										  'filename' => $dte['filename'],
										  'content' => iconv('','UTF-8//IGNORE',$dte['content']),
										  'rutemisor' => $array_rut_emisor[0],
										  'dvemisor' => $array_rut_emisor[1],
										  'fecemision' => $EnvioDte->getFechaEmisionFinal(),
										  'proveenombre' => $dte['proveedor_nombre'],
										  'proveemail' => $dte['proveedor_mail'],
										  'folio' => $folio,
										  'monto_total' => $monto_total,
										  'monto_neto' => $resumen['MntNeto'],
										  'monto_exento' => $resumen['MntExe'],
										  'iva' => $resumen['MntIVA'],
										  'monto_afecto' => $resumen['MntIVA'] == 0 ? 0 : $resumen['MntNeto'] ,
										  'fecvenc' => $array_datos['Encabezado']['IdDoc']['FchVenc'],
										  'tipodoc' => $array_datos['Encabezado']['IdDoc']['TipoDTE'],
										  'idempresa' => $idempresa
										  );

					$this->db->insert('lectura_dte_email',$array_insert);


					


				}

				if(!file_exists('./facturacion_electronica/dte_provee_tmp/' . $path . '/')){
					mkdir('./facturacion_electronica/dte_provee_tmp/' . $path . '/',0777,true);
				}				
				$f_archivo = fopen('./facturacion_electronica/dte_provee_tmp/' . $path . '/' .$dte['filename'],'w');
				fwrite($f_archivo,$dte['content']);
				fclose($f_archivo);					
			}

		
		}  

	 }

	public function envio_mail_dte($idfactura){


			$factura = $this->datos_dte($idfactura);
			$track_id = $factura->trackid;
			$path = $factura->path_dte;
			$nombre_dte = $factura->archivo_dte;

			$empresa = $this->get_empresa();
			$datos_empresa_factura = $this->get_empresa_factura($idfactura);

			$messageBody  = 'Envío de DTE<br><br>';
	        $messageBody .= '<b>Datos Emisor:</b><br>';
	        $messageBody .= $empresa->razon_social.'<br>';
	        $messageBody .= 'RUT:'.$empresa->rut.'-'.$empresa->dv .'<br><br>';

	        $messageBody .= '<b>Datos Receptor:</b><br>';
	        $messageBody .= $datos_empresa_factura->nombre_cliente.'<br>';
	        $messageBody .= 'RUT:'.substr($datos_empresa_factura->rut_cliente,0,strlen($datos_empresa_factura->rut_cliente) - 1)."-".substr($datos_empresa_factura->rut_cliente,-1) .'<br><br>';			        

	        $messageBody .= '<a href="'. base_url() .'facturas/exportFePDF_mail/'.$track_id.'" >Ver Factura</a><br><br>';

	        $messageBody .= 'Este correo adjunta Documentos Tributarios Electrónicos (DTE) para el receptor electrónico indicado. Por favor responda con un acuse de recibo (RespuestaDTE) conforme al modelo de intercambio de Factura Electrónica del SII.<br><br>';
	        $messageBody .= 'Facturación Electrónica Infosys SPA.';


	        $email_data = $this->facturaelectronica->get_email();
		    if(count($email_data) > 0 && !is_null($datos_empresa_factura->e_mail)){ //MAIL SE ENVÍA SÓLO EN CASO QUE TENGAMOS REGISTRADOS EMAIL DE ORIGEN Y DESTINO
		    	$this->load->library('email');
				$config['protocol']    = $email_data->tserver_intercambio;
				$config['smtp_host']    = $email_data->host_intercambio;
				$config['smtp_port']    = $email_data->port_intercambio;
				$config['smtp_timeout'] = '7';
				$config['smtp_user']    = $email_data->email_intercambio;
				$config['smtp_pass']    = $email_data->pass_intercambio;
				$config['charset']    = 'utf-8';
				$config['newline']    = "\r\n";
				$config['mailtype'] = 'html'; // or html
				$config['validation'] = TRUE; // bool whether to validate email or not      			


		        $this->email->initialize($config);		  		
				
			    $this->email->from($email_data->email_intercambio, 'Factura Electrónica '. NOMBRE_EMPRESA);
			    $this->email->to($datos_empresa_factura->e_mail);

			    #$this->email->bcc(array('rodrigo.gonzalez@info-sys.cl','cesar.moraga@info-sys.cl','sergio.arriagada@info-sys.cl','rene.gonzalez@info-sys.cl')); 
			    $this->email->subject('Envio de DTE ' .$track_id . '_'.$empresa->rut.'-'.$empresa->dv."_".substr($datos_empresa_factura->rut_cliente,0,strlen($datos_empresa_factura->rut_cliente) - 1)."-".substr($datos_empresa_factura->rut_cliente,-1));
			    $this->email->message($messageBody);

			    $this->email->attach('./facturacion_electronica/dte/'.$path.$nombre_dte);

			    try {
			      $this->email->send();
			      //var_dump($this->email->print_debugger());
			      	        //exit;
			    } catch (Exception $e) {
			      echo $e->getMessage() . '<br />';
			      echo $e->getCode() . '<br />';
			      echo $e->getFile() . '<br />';
			      echo $e->getTraceAsString() . '<br />';
			      echo "no";

			    }
			    return true;

			}else{

				return false;
			}

	}



	public function guarda_doc_proc($idempresa){




	   	$int_db = $this->load->database('CONS_Integraciones',true);


	   	//sólo los pendientes
        $int_db->select('ID, XML')
        		->from('HUB')
        		->where('estado',0);



        $query_int = $int_db->get();
        echo "<pre>";
        $array_documentos = $query_int->result();
        var_dump($array_documentos);
        $codproceso = randomstring_mm(10);


        foreach ($array_documentos as $documento) {
        	//print_r($documento);

			$xml = new SimpleXMLElement($documento->XML);

			print_r($xml->DTE->Documento);

			if(isset($xml->DTE->Documento)){


				if(isset($xml->DTE->Documento->Encabezado)){

					$encabezado = $xml->DTE->Documento->Encabezado;

					//print_r($encabezado);
					$referencia = isset($xml->DTE->Documento->Referencia) ? $xml->DTE->Documento->Referencia : '';




					

					$error = false;
					if(!isset($xml->DTE->Documento->Detalle)){
						$this->actualiza_estado_db($documento->ID,2,'No se encuentra detalle documento');
						$error =  true;
					}else{

						$detalle = $xml->DTE->Documento->Detalle;
					}					



					if(($encabezado->IdDoc->TipoDTE == 56 || $encabezado->IdDoc->TipoDTE == 61) && $referencia == ''){
						$this->actualiza_estado_db($documento->ID,2,'Tipo de Documento Necesita referencia');
						$error = true;
					}


					if($referencia != ''){

						$folio_ref = is_numeric((int)$referencia->FolioRef) ? (int)$referencia->FolioRef : 0;
						$tipocaf_ref = is_numeric((int)$referencia->TpoDocRef) ? (int)$referencia->TpoDocRef : 0;
					}else{
						$folio_ref = 0;
						$tipocaf_ref = 0;
					}


					
					if(!isset($encabezado->IdDoc)){
						$this->actualiza_estado_db($documento->ID,2,'No se encuentra información de IdDoc');
						$error =  true;
					}

					if(!isset($encabezado->Emisor)){
						$this->actualiza_estado_db($documento->ID,2,'No se encuentra información de Emisor');
						$error =  true;
					}

					if(!isset($encabezado->Receptor)){
						$this->actualiza_estado_db($documento->ID,2,'No se encuentra información de Receptor');
						$error =  true;
					}

					if(!isset($encabezado->Totales)){
						$this->actualiza_estado_db($documento->ID,2,'No se encuentra información de Totales');
						$error =  true;
					}										


					if(!$error){

					        $array_rut = explode("-",$encabezado->Emisor->RUTEmisor);
					        //print_R($array_rut);


					        //var_dump((int)$encabezado->IdDoc->Folio);
					        //var_dump(is_numeric((int)$encabezado->IdDoc->Folio));
					        $array_data = array(
					        					'tipocaf' => $encabezado->IdDoc->TipoDTE,
					        					'folio' => is_numeric((int)$encabezado->IdDoc->Folio) ? (int)$encabezado->IdDoc->Folio : 0,
					        					'fechafactura' => $encabezado->IdDoc->FchEmis,
					        					'referencia' => isset($folio_ref) ? $folio_ref : 0,
					        					'tipocaf_referencia' => isset($tipocaf_ref) ? $tipocaf_ref : 0,
					        					'condicion' => '',
					        					'rut' => $array_rut[0],
					        					'dv' => isset($array_rut[1]) ? $array_rut[1] : '0',
					        					'razonsocial' => $encabezado->Emisor->RznSoc,
					        					'giro' => $encabezado->Emisor->GiroEmis,
					        					'direccion' => $encabezado->Emisor->DirOrigen,
					        					'comuna' => $encabezado->Emisor->CmnaOrigen,
					        					'ciudad' => $encabezado->Emisor->CiudadOrigen,
					        					'cuenta' => '',
					        					'neto' => $encabezado->Totales->MntExe > 0 ? $encabezado->Totales->MntExe : $encabezado->Totales->MntNeto,
					        					'iva' => isset($encabezado->Totales->IVA) ? $encabezado->Totales->IVA : 0,
					        					'total' => $encabezado->Totales->MntTotal,
					        					//'codigo' => '0',
					        					//'cantidad' => '0',
					        					//'unidad' => '0',
					        					//'nombre' => '0',
					        					//'preciounit' => '0',
					        					//'totaldetalle' => '0',
					        					'codigoproceso' => $codproceso,
					        					'idempresa' => $idempresa
					        			);

					        $this->db->insert('guarda_doc_proc',$array_data);
					        $id_docto = $this->db->insert_id();

					        $array_data_detalle = array(
					        					'iddoc' => $id_docto,
					        					'codigo' => $detalle->CdgItem->VlrCodigo,
					        					'cantidad' => $detalle->QtyItem,
					        					'unidad' => $detalle->UnmdItem,
					        					'nombre' => $detalle->NmbItem,
					        					'preciounit' => $detalle->PrcItem,
					        					'totaldetalle' => $detalle->MontoItem
					        			);					   

					        $this->db->insert('guarda_detalle_doc_proc',$array_data_detalle);     

					        //tiene que ser un ciclo
					        /*foreach ($detalle as $detalle_docto) {
					        	# code...
					        }*/
					       // print_r($array_data);




					        $this->actualiza_estado_db($documento->ID,1,'Guardado OK');



					}


				}else{
					$this->actualiza_estado_db($documento->ID,2,'No se encuentra información de Encabezado');

				}



			}else{

				$this->actualiza_estado_db($documento->ID,2,'No se encuentra información de Documento');

			}



        }

		return $codproceso;

	}


	public funcTion actualiza_estado_db($id,$estado,$detalle_estado){



	   	$int_db = $this->load->database('CONS_Integraciones',true);
		$array_result = array(
							'estado' => $estado,
							'detalle_estado' => $detalle_estado,
							'fecha_procesa' => date('Ymd H:i:s')

						);
		$int_db->where('ID',$id);
		$int_db->update('HUB',$array_result);



	}


	public function estado_tipo_documento($tipo_documento){
		$this->db->select('f.id ')
						  ->from('folios_caf f')
						  ->join('caf c','f.idcaf = c.id')
						  ->where('c.tipo_caf',$tipo_documento)
						  ->where('c.idempresa',$this->session->userdata('empresaid'))
						  ->where("f.estado = 'P'");
		$query = $this->db->get();
		$folios_existentes = $query->result();				

       	return count($folios_existentes);
	 }


	public function get_facturas($idfactura = null){
		$this->db->select('f.id, td.descripcion, f.num_factura, c.nombres, f.fecha_factura, f.neto, f.iva, f.totalfactura')
						  ->from('factura_clientes f')
						  ->join('tipo_documento td','f.tipo_documento = td.id')
						  ->join('clientes c','f.id_cliente = c.id');
						  
		$query = $this->db->get();

       	return $query->result();
	 }


	public function crea_dte($idfactura,$tipo = 'sii'){

		$data_factura = $this->get_factura($idfactura);
		$tipodocumento = $data_factura->tipo_documento;
		$numfactura = $data_factura->num_factura;
		$fecemision = $data_factura->fecha_factura;



		if($tipodocumento == 101){
			$tipo_caf = 33;
		}else if($tipodocumento == 103){
			$tipo_caf = 34;
		}else if($tipodocumento == 105){
			$tipo_caf = 52;
		}else if($tipodocumento == 102){
			$tipo_caf = 61;
		}		


		header('Content-type: text/plain; charset=ISO-8859-1');
		$this->load->model('facturaelectronica');
		$config = $this->genera_config();
		include $this->ruta_libredte();

		$empresa = $this->get_empresa();
		$datos_empresa_factura = $this->get_empresa_factura($idfactura);



		//$detalle_factura = $this->get_detalle_factura($idfactura);
		$detalle_factura = $data_factura->forma == 1 ? $this->get_detalle_factura_glosa($idfactura) : $this->get_detalle_factura($idfactura);

		$lista_detalle = array();
		$i = 0;
		foreach ($detalle_factura as $detalle) {

			$lista_detalle[$i]['NmbItem'] = $data_factura->forma == 1 ? $detalle->glosa : $detalle->nombre;
			$lista_detalle[$i]['QtyItem'] = $detalle->cantidad;

			$lista_detalle[$i]['PrcItem'] = floor($detalle->neto/$detalle->cantidad);
			/*if($data_factura->forma == 1){
				$lista_detalle[$i]['PrcItem'] = $tipo_caf == 33 || $tipo_caf == 52 ? floor($detalle->neto) : floor($detalle->total);
			}else{
				$lista_detalle[$i]['PrcItem'] = $tipo_caf == 33 ? floor(($detalle->totalproducto - $detalle->iva)/$detalle->cantidad) : round($detalle->precio,3);
			}*/


			$lista_detalle[$i]['MontoItem'] = $detalle->neto;
			/*if($tipo_caf == 33 && $data_factura->forma != 1){
				$lista_detalle[$i]['MontoItem'] = ($detalle->totalproducto - $detalle->iva);
			}*/				
		

			/*if($data_factura->forma != 1){
				if($detalle->descuento != 0){
					$porc_descto = round(($detalle->descuento/($detalle->cantidad*$lista_detalle[$i]['PrcItem'])*100),0);
					$lista_detalle[$i]['DescuentoPct'] = $porc_descto;		
					//$lista_detalle[$i]['PrcItem'] =- $lista_detalle[$i]['PrcItem']*$porc_descto;

				}
			}*/

			$i++;
		}

		if($tipo_caf == 61){
			$tipo_nota_credito = 1;
			$numfactura_asoc = $data_factura->id_factura;
			$glosa = $tipo_nota_credito == 1 ? 'Anula factura '. $numfactura_asoc : 'Correccion factura '. $numfactura_asoc;
			// datos
			$factura = [
			    'Encabezado' => [
			        'IdDoc' => [
			            'TipoDTE' => $tipo_caf,
			            'Folio' => $numfactura,
			            'FchEmis' => $fecemision
			        ],
			        'Emisor' => [
			            'RUTEmisor' => $empresa->rut.'-'.$empresa->dv,
			            'RznSoc' => substr($empresa->razon_social,0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES
			            'GiroEmis' => substr($empresa->giro,0,80), //LARGO DE GIRO DEL EMISOR NO PUEDE SER SUPERIOR A 80 CARACTERES
			            'Acteco' => $empresa->cod_actividad,
			            'DirOrigen' => substr($empresa->dir_origen,0,70), //LARGO DE DIRECCION DE ORIGEN NO PUEDE SER SUPERIOR A 70 CARACTERES
			            'CmnaOrigen' => substr($empresa->comuna_origen,0,20), //LARGO DE COMUNA DE ORIGEN NO PUEDE SER SUPERIOR A 20 CARACTERES
			        ],
			        'Receptor' => [
			            'RUTRecep' => substr($datos_empresa_factura->rut_cliente,0,strlen($datos_empresa_factura->rut_cliente) - 1)."-".substr($datos_empresa_factura->rut_cliente,-1),
			            'RznSocRecep' => substr($datos_empresa_factura->nombre_cliente,0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES
			            'GiroRecep' => substr($datos_empresa_factura->giro,0,40),  //LARGO DEL GIRO NO PUEDE SER SUPERIOR A 40 CARACTERES
			            'DirRecep' => substr($datos_empresa_factura->direccion,0,70), //LARGO DE DIRECCION NO PUEDE SER SUPERIOR A 70 CARACTERES
			            'CmnaRecep' => substr($datos_empresa_factura->nombre_comuna,0,20), //LARGO DE COMUNA NO PUEDE SER SUPERIOR A 20 CARACTERES
			        ],
			    ],
				'Detalle' => $lista_detalle,
		        'Referencia' => [
		            'TpoDocRef' => 33,
		            'FolioRef' => $numfactura_asoc,
		            'CodRef' => $tipo_nota_credito,
		            'RazonRef' => $glosa,
		        ]				
			];


		}else{
			// datos
			$factura = [
			    'Encabezado' => [
			        'IdDoc' => [
			            'TipoDTE' => $tipo_caf,
			            'Folio' => $numfactura,
			            'FchEmis' => $fecemision
			        ],
			        'Emisor' => [
			            'RUTEmisor' => $empresa->rut.'-'.$empresa->dv,
			            'RznSoc' => substr($empresa->razon_social,0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES
			            'GiroEmis' => substr($empresa->giro,0,80), //LARGO DE GIRO DEL EMISOR NO PUEDE SER SUPERIOR A 80 CARACTERES
			            'Acteco' => $empresa->cod_actividad,
			            'DirOrigen' => substr($empresa->dir_origen,0,70), //LARGO DE DIRECCION DE ORIGEN NO PUEDE SER SUPERIOR A 70 CARACTERES
			            'CmnaOrigen' => substr($empresa->comuna_origen,0,20), //LARGO DE COMUNA DE ORIGEN NO PUEDE SER SUPERIOR A 20 CARACTERES
			        ],
			        'Receptor' => [
			            'RUTRecep' => substr($datos_empresa_factura->rut_cliente,0,strlen($datos_empresa_factura->rut_cliente) - 1)."-".substr($datos_empresa_factura->rut_cliente,-1),
			            'RznSocRecep' => substr($datos_empresa_factura->nombre_cliente,0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES
			            'GiroRecep' => substr($datos_empresa_factura->giro,0,40),  //LARGO DEL GIRO NO PUEDE SER SUPERIOR A 40 CARACTERES
			            'DirRecep' => substr($datos_empresa_factura->direccion,0,70), //LARGO DE DIRECCION NO PUEDE SER SUPERIOR A 70 CARACTERES
			            'CmnaRecep' => substr($datos_empresa_factura->nombre_comuna,0,20), //LARGO DE COMUNA NO PUEDE SER SUPERIOR A 20 CARACTERES
			        ],
			    ],
				'Detalle' => $lista_detalle
			];

		}


		//FchResol y NroResol deben cambiar con los datos reales de producción
		$caratula = [
		    //'RutEnvia' => '11222333-4', // se obtiene de la firma
		    'RutReceptor' => '60803000-K',
		    'FchResol' => $empresa->fec_resolucion,
		    'NroResol' => $empresa->nro_resolucion
		];


		$Firma = new sasco\LibreDTE\FirmaElectronica($config['firma']); //lectura de certificado digital		
		$caf = $this->facturaelectronica->get_content_caf_folio($numfactura,$tipo_caf);
		$Folios = new sasco\LibreDTE\Sii\Folios($caf->caf_content);

		$DTE = new \sasco\LibreDTE\Sii\Dte($factura);


		$DTE->timbrar($Folios);
		//var_dump($Firma); exit;
		$DTE->firmar($Firma);		

		// generar sobre con el envío del DTE y enviar al SII
		$EnvioDTE = new \sasco\LibreDTE\Sii\EnvioDte();

		$EnvioDTE->agregar($DTE);
		$EnvioDTE->setFirma($Firma);
		$EnvioDTE->setCaratula($caratula);
		$EnvioDTE->generar();		
		if ($EnvioDTE->schemaValidate()) { // REVISAR PORQUÉ SE CAE CON ESTA VALIDACION
			
			$track_id = 0;
		    $xml_dte = $EnvioDTE->generar();

		    $dte = $this->crea_archivo_dte($xml_dte,$idfactura,$tipo_caf,$tipo);

		    $campos['dte'] = $tipo == 'cliente' ? 'dte_cliente' : 'dte';
		    $campos['archivo_dte'] = $tipo == 'cliente' ? 'archivo_dte_cliente' : 'archivo_dte';


			$this->db->query("update f
				 set dte = '" . iconv('','UTF-8//IGNORE',$dte['xml_dte']) . "',
				 	 estado = 'O',
				 	 idfactura = '" . $idfactura . "',
				 	 path_dte = '" . $dte['path'] . "',
				 	 archivo_dte = '" . $dte['nombre_dte'] . "',
				 	 trackid = '" . $track_id . "'
				 from folios_caf f
				 inner join caf c on f.idcaf = c.id
				 where f.folio = '" .$numfactura . "'
				 and c.tipo_caf = '" . $tipo_caf ."'"
				 ); 


		  		/*if($track_id != 0 && $datos_empresa_factura->e_mail != ''){ //existe track id, se envía correo
				$this->envio_mail_dte($idfactura);
			}*/

		}

		return $this->datos_dte($idfactura);

	}	 


	public function crea_dte_db($codproceso,$idempresa){

		$this->db->select('distinct tipocaf, folio ',false)
			->from('guarda_doc_proc')
			->where('codigoproceso',$codproceso)
			->where('folio <> 0');

		$query = $this->db->get();
		$data_doctos = $query->result();


		$config = $this->genera_config();
		include $this->ruta_libredte();
		$empresa = $this->get_empresa();
		foreach ($data_doctos as $docto) {


			//header('Content-type: text/plain; charset=ISO-8859-1');
			

			$this->db->select('f.tipocaf, f.folio, f.referencia, f.tipocaf_referencia, f.fechafactura, f.condicion, f.rut, f.dv, f.razonsocial, f.giro, f.direccion, f.comuna, f.ciudad, f.cuenta, f.neto, f.iva, f.total, d.codigo, d.cantidad, d.unidad, d.nombre, d.preciounit, d.totaldetalle ')
		  			->from('guarda_doc_proc f')
		  			->join('guarda_detalle_doc_proc d','f.id = d.iddoc')
		  			->where('tipocaf',$docto->tipocaf)
		  			->where('folio',$docto->folio)
		  			->where('f.idempresa',$idempresa);
			$query = $this->db->get();
			$data_csv = $query->result();


			/*$this->db->select('min(fc.folio) as folio ',false)
		  			->from('folios_caf fc')
		  			->join('caf c','fc.idcaf = c.id')
		  			->where('c.idempresa',$idempresa)
		  			->where('fc.estado','P')
		  			->where('c.tipo_caf',$docto->tipocaf);
			$query = $this->db->get();
			$data_folio = $query->row();

			$docto->folio = $data_folio->folio;*/

			/*if($docto->tipocaf == 33){
				$docto->folio = 1;
			}else if($docto->tipocaf == 34){
				$docto->folio = 23;
			}else if($docto->tipocaf == 61){
				$docto->folio = 2;
			}*/


			//$datos_folio = $this->get_content_caf_folio($docto->folio,$docto->tipocaf);
			$datos_folio = $this->get_content_caf_folio($docto->folio,$docto->tipocaf,$idempresa);


			if(count($datos_folio) > 0){
				//SÓLO SE CARGA AQUELLOS FOLIOS QUE EXISTEN Y ESTÁN PENDIENTES O TOMADOS
				if($datos_folio->estado == 'P' || $datos_folio->estado == 'T'){

				$tipodocumento = caftotd($docto->tipocaf);

				$this->db->select('id')
			  			->from('clientes')
			  			->where('rut',$data_csv[0]->rut.$data_csv[0]->dv);
				$query = $this->db->get();
				$data_cliente = $query->row();

				if(count($data_cliente) > 0){
					$idcliente = $data_cliente->id;
				}else{ // SI NO EXISTE CLIENTE, SE CREA
						$array_data_cliente = array(
												'rut' => $data_csv[0]->rut.$data_csv[0]->dv,
												'nombres' => $data_csv[0]->razonsocial,
												'direccion' => $data_csv[0]->direccion,
											);

						$this->db->insert('clientes', $array_data_cliente);	
						$idcliente = $this->db->insert_id();											

				}


				if($docto->tipocaf == 61 || $docto->tipocaf == 56){
					$numfactura = isset($this->get_content_caf_folio($data_csv[0]->referencia,33,$idempresa)->idfactura) ? $this->get_content_caf_folio($data_csv[0]->referencia,33,$idempresa)->idfactura : $data_csv[0]->referencia;  //referencia siempre es una factura electronica
				}else{
					$numfactura = 0;
				}


				$factura_cliente = array(
					'tipo_documento' => $tipodocumento,
			        'id_cliente' => $idcliente,
			        'num_factura' => $docto->folio,
			        'id_vendedor' => 1,
			        'sub_total' => $data_csv[0]->neto,
			        'neto' => $data_csv[0]->neto,
			        'iva' => $data_csv[0]->iva,
			        'totalfactura' => $data_csv[0]->total,
			        'fecha_factura' => $data_csv[0]->fechafactura,
			        'fecha_venc' => $data_csv[0]->fechafactura,
			        'id_factura' => $numfactura,
			        'id_sucursal' => 0,
			        'id_cond_venta' => 0,
			        'descuento' => 0,
			        'id_factura' => 0,
			        'observacion' => '',
			        'id_observa' => 0,
			        'id_despacho' => 0,
			        'estado' => 0,
			        'forma' => 1,
			        'idempresa' => $idempresa

				);

				$this->db->insert('factura_clientes', $factura_cliente); 
				$idfactura = $this->db->insert_id();


				$datos_empresa_factura = $this->get_empresa_factura($idfactura);
				//$detalle_factura = $this->get_detalle_factura_glosa($idfactura);

				$i = 0;
				$lista_detalle = array();			
				foreach ($data_csv as $regcsv) {
					$factura_clientes_item = array(
				        'id_factura' => $idfactura,
				        'id_producto' => 0,
				        'id_guia' => 0,
				        'num_guia' => 0,
				        'cantidad' => $regcsv->cantidad,
				        'kilos' => 0,
				        'precio' => 0,
				        'glosa' => $regcsv->nombre,
				        'neto' => $regcsv->totaldetalle,
				        'iva' => $docto->tipocaf == 34 ?  0 : $regcsv->totaldetalle*0.19,
				        'total' => $regcsv->tipocaf == 34 ? $regcsv->totaldetalle : $regcsv->totaldetalle*1.19
					);

					$this->db->insert('detalle_factura_glosa', $factura_clientes_item);

					$lista_detalle[$i]['NmbItem'] = $regcsv->nombre;
					$lista_detalle[$i]['QtyItem'] = $regcsv->cantidad;
					$lista_detalle[$i]['UnmdItem'] = substr($regcsv->unidad,0,3);
					if($regcsv->preciounit != 0){
						$lista_detalle[$i]['PrcItem'] = $regcsv->preciounit;
						$lista_detalle[$i]['MontoItem'] = $regcsv->totaldetalle;
					}

					$i++;							
				}// FIN REGCSV
				//echo $tipodocumento."<br>";

				if($tipodocumento == 101 || $tipodocumento == 102 || $tipodocumento == 103 || $tipodocumento == 104|| $tipodocumento == 107){  // SI ES FACTURA ELECTRONICA O NOTA DE CRÉDITO O FACTURA EXENTA ELECTRONICA O NOTA DE DEBITO O FACTURA DE COMPRA

							$tipo_caf = $docto->tipocaf;

							if($tipo_caf == 61){

								$tipo_nota_credito = 1;
            					$glosa = 'Anula factura  '. $data_csv[0]->referencia;								


								$factura = [
								    'Encabezado' => [
								        'IdDoc' => [
								            'TipoDTE' => $docto->tipocaf,
								            'Folio' => $docto->folio,
								            'FchEmis' => $data_csv[0]->fechafactura
								        ],
								        'Emisor' => [
								            'RUTEmisor' => $empresa->rut.'-'.$empresa->dv,
								            'RznSoc' => substr($empresa->razon_social,0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES,
								            'GiroEmis' => substr($empresa->giro,0,80), //LARGO DE GIRO DEL EMISOR NO PUEDE SER SUPERIOR A 80 CARACTERES
								            //'Acteco' => $empresa->cod_actividad,
								            'Acteco' => 1,
								            'DirOrigen' => substr($empresa->dir_origen,0,70), //LARGO DE DIRECCION DE ORIGEN NO PUEDE SER SUPERIOR A 70 CARACTERES
								            'CmnaOrigen' => substr($empresa->comuna_origen,0,20), //LARGO DE COMUNA DE ORIGEN NO PUEDE SER SUPERIOR A 20 CARACTERES
								        ],
								        'Receptor' => [
								            'RUTRecep' => $data_csv[0]->rut."-".$data_csv[0]->dv,
								            'RznSocRecep' => substr($data_csv[0]->razonsocial,0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES
								            'GiroRecep' => substr($data_csv[0]->giro,0,40),  //LARGO DEL GIRO NO PUEDE SER SUPERIOR A 40 CARACTERES
								            'DirRecep' => substr($data_csv[0]->direccion,0,70), //LARGO DE DIRECCION NO PUEDE SER SUPERIOR A 70 CARACTERES
								            'CmnaRecep' => substr($data_csv[0]->comuna,0,20), //LARGO DE COMUNA NO PUEDE SER SUPERIOR A 20 CARACTERES
								        ],
							            'Totales' => [
							                // estos valores serán calculados automáticamente
							                'MntNeto' => 0,
							                'TasaIVA' => \sasco\LibreDTE\Sii::getIVA(),
							                'IVA' => 0,
							                'MntTotal' => 0,
							            ],			        
								    ],
									'Detalle' => $lista_detalle,
					                'Referencia' => [
					                    'TpoDocRef' => $data_csv[0]->referencia > 100000 ? 30 : 33,
					                    'FolioRef' => $data_csv[0]->referencia,
					                    'CodRef' => $tipo_nota_credito,
					                    'RazonRef' => $glosa,
					                ] 									
								];


							}else if($tipo_caf == 56){


								$tipo_nota_credito = 2;
            					$glosa = 'Correccion factura  '. $data_csv[0]->referencia;								


								$factura = [
								    'Encabezado' => [
								        'IdDoc' => [
								            'TipoDTE' => $docto->tipocaf,
								            'Folio' => $docto->folio,
								            'FchEmis' => $data_csv[0]->fechafactura
								        ],
								        'Emisor' => [
								            'RUTEmisor' => $empresa->rut.'-'.$empresa->dv,
								            'RznSoc' => substr($empresa->razon_social,0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES,
								            'GiroEmis' => substr($empresa->giro,0,80), //LARGO DE GIRO DEL EMISOR NO PUEDE SER SUPERIOR A 80 CARACTERES
								            //'Acteco' => $empresa->cod_actividad,
								            'Acteco' => 1,
								            'DirOrigen' => substr($empresa->dir_origen,0,70), //LARGO DE DIRECCION DE ORIGEN NO PUEDE SER SUPERIOR A 70 CARACTERES
								            'CmnaOrigen' => substr($empresa->comuna_origen,0,20), //LARGO DE COMUNA DE ORIGEN NO PUEDE SER SUPERIOR A 20 CARACTERES
								        ],
								        'Receptor' => [
								            'RUTRecep' => $data_csv[0]->rut."-".$data_csv[0]->dv,
								            'RznSocRecep' => substr($data_csv[0]->razonsocial,0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES
								            'GiroRecep' => substr($data_csv[0]->giro,0,40),  //LARGO DEL GIRO NO PUEDE SER SUPERIOR A 40 CARACTERES
								            'DirRecep' => substr($data_csv[0]->direccion,0,70), //LARGO DE DIRECCION NO PUEDE SER SUPERIOR A 70 CARACTERES
								            'CmnaRecep' => substr($data_csv[0]->comuna,0,20), //LARGO DE COMUNA NO PUEDE SER SUPERIOR A 20 CARACTERES
								        ],
							            'Totales' => [
							                // estos valores serán calculados automáticamente
							                'MntNeto' => 0,
							                'TasaIVA' => \sasco\LibreDTE\Sii::getIVA(),
							                'IVA' => 0,
							                'MntTotal' => 0,
							            ],			        
								    ],
									'Detalle' => $lista_detalle,
					                'Referencia' => [
					                    'TpoDocRef' => $data_csv[0]->referencia > 100000 ? 30 : 33,
					                    'FolioRef' => $data_csv[0]->referencia,
					                    'CodRef' => $tipo_nota_credito,
					                    'RazonRef' => $glosa,
					                ] 									
								];

							}else{
								$factura = [
								    'Encabezado' => [
								        'IdDoc' => [
								            'TipoDTE' => $docto->tipocaf,
								            'Folio' => $docto->folio,
								            'FchEmis' => $data_csv[0]->fechafactura
								        ],
								        'Emisor' => [
								            'RUTEmisor' => $empresa->rut.'-'.$empresa->dv,
								            'RznSoc' => substr($empresa->razon_social,0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES,
								            'GiroEmis' => substr($empresa->giro,0,80), //LARGO DE GIRO DEL EMISOR NO PUEDE SER SUPERIOR A 80 CARACTERES
								            //'Acteco' => $empresa->cod_actividad,
								             'Acteco' => 1,
								            'DirOrigen' => substr($empresa->dir_origen,0,70), //LARGO DE DIRECCION DE ORIGEN NO PUEDE SER SUPERIOR A 70 CARACTERES
								            'CmnaOrigen' => substr($empresa->comuna_origen,0,20), //LARGO DE COMUNA DE ORIGEN NO PUEDE SER SUPERIOR A 20 CARACTERES
								        ],
								        'Receptor' => [
								            'RUTRecep' => $data_csv[0]->rut."-".$data_csv[0]->dv,
								            'RznSocRecep' => substr($data_csv[0]->razonsocial,0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES
								            'GiroRecep' => substr($data_csv[0]->giro,0,40),  //LARGO DEL GIRO NO PUEDE SER SUPERIOR A 40 CARACTERES
								            'DirRecep' => substr($data_csv[0]->direccion,0,70), //LARGO DE DIRECCION NO PUEDE SER SUPERIOR A 70 CARACTERES
								            'CmnaRecep' => substr($data_csv[0]->comuna,0,20), //LARGO DE COMUNA NO PUEDE SER SUPERIOR A 20 CARACTERES
								        ],
					                    /*'Totales' => [
					                        // estos valores serán calculados automáticamente
					                        'MntNeto' => $data_csv[0]->neto,
					                        'TasaIVA' => \sasco\LibreDTE\Sii::getIVA(),
					                        'IVA' => $data_csv[0]->iva,
					                        'MntTotal' => $data_csv[0]->total,
					                    ],*/ 									        
								    ],
									'Detalle' => $lista_detalle
								];

							}


							//FchResol y NroResol deben cambiar con los datos reales de producción
							$caratula = [
							    //'RutEnvia' => '11222333-4', // se obtiene de la firma
							    'RutReceptor' => '60803000-K',
							    'FchResol' => $empresa->fec_resolucion,
							    'NroResol' => $empresa->nro_resolucion
							];			

							//FchResol y NroResol deben cambiar con los datos reales de producción
							$caratula_cliente = [
							    //'RutEnvia' => '11222333-4', // se obtiene de la firma
							    'RutReceptor' => $data_csv[0]->rut."-".$data_csv[0]->dv,
							    'FchResol' => $empresa->fec_resolucion,
							    'NroResol' => $empresa->nro_resolucion
							];													
										// Objetos de Firma y Folios
							$Firma = new sasco\LibreDTE\FirmaElectronica($config['firma']); //lectura de certificado digital		

							$caf = $this->get_content_caf_folio($docto->folio,$tipo_caf,$idempresa);
							
							$Folios = new sasco\LibreDTE\Sii\Folios($caf->caf_content);
								
							$DTE = new \sasco\LibreDTE\Sii\Dte($factura);

							$DTE->timbrar($Folios);
							$DTE->firmar($Firma);		


							// generar sobre con el envío del DTE y enviar al SII
							$EnvioDTE = new \sasco\LibreDTE\Sii\EnvioDte();
							$EnvioDTE->agregar($DTE);
							$EnvioDTE->setFirma($Firma);
							$EnvioDTE->setCaratula($caratula);
							$xml_dte = $EnvioDTE->generar();
							echo "<pre>";
							//echo htmlentities($xml_dte);
							//var_dump($EnvioDTE->schemaValidate());
							if ($EnvioDTE->schemaValidate()) { // REVISAR PORQUÉ SE CAE CON ESTA VALIDACION
								
								$track_id = 0;
							    $xml_dte = $EnvioDTE->generar();

							    #GENERACIÓN DTE CLIENTE
								$EnvioDTE_CLI = new \sasco\LibreDTE\Sii\EnvioDte();
								$EnvioDTE_CLI->agregar($DTE);
								$EnvioDTE_CLI->setFirma($Firma);
								$EnvioDTE_CLI->setCaratula($caratula_cliente);
								$xml_dte_cliente = $EnvioDTE_CLI->generar();	

							    //$track_id = $EnvioDTE->enviar();
							    $tipo_envio = $this->busca_parametro_fe('envio_sii'); //ver si está configurado para envío manual o automático

							    $dte = $this->facturaelectronica->crea_archivo_dte($xml_dte,$idfactura,$tipo_caf,'sii');
							    $dte_cliente = $this->facturaelectronica->crea_archivo_dte($xml_dte_cliente,$idfactura,$tipo_caf,'cliente');

							   /* if($tipo_envio == 'automatico'){
								    $track_id = $EnvioDTE->enviar();
							    }*/


							$this->db->query("update f
								 set dte = '" . iconv('','UTF-8//IGNORE',$dte['xml_dte']) . "',
								 	 estado = 'O',
								 	 idfactura = '" . $idfactura . "',
								 	 path_dte = '" . $dte['path'] . "',
								 	 archivo_dte = '" . $dte['nombre_dte'] . "',
								 	 trackid = '" . $track_id . "'
								 from folios_caf f
								 inner join caf c on f.idcaf = c.id
								 where f.folio = '" .$docto->folio . "'
								 and c.tipo_caf = '" . $tipo_caf ."'"
								 ); 
							
							/*$this->db->query("update f
								 set dte = '" . iconv('','UTF-8//IGNORE',$dte['xml_dte']) . "',
								 	dte_cliente = '" . iconv('','UTF-8//IGNORE',$dte['xml_dte']) . "',
								 	 estado = 'O',
								 	 idfactura = '" . $idfactura . "',
								 	 path_dte = '" . $dte['path'] . "',
								 	 archivo_dte = '" . $dte['nombre_dte'] . "',
								 	 archivo_dte_cliente = '" . $dte['nombre_dte'] . "',
								 	 trackid = '" . $track_id . "'
								 from folios_caf f
								 inner join caf c on f.idcaf = c.id
								 where f.folio = '" .$numfactura . "'
								 and c.tipo_caf = '" . $tipo_caf ."'"
								 ); 
							
							*/



							   /* $this->db->where('f.folio', $docto->folio);
							    $this->db->where('c.tipo_caf', $tipo_caf);
								$this->db->update('folios_caf f inner join caf c on f.idcaf = c.id',array('dte' => $dte['xml_dte'],
																						  'dte_cliente' => $dte_cliente['xml_dte'],
																						  'estado' => 'O',
																						  'idfactura' => $idfactura,
																						  'path_dte' => $dte['path'],
																						  'archivo_dte' => $dte['nombre_dte'],
																						  'archivo_dte_cliente' => $dte_cliente['nombre_dte'],
																						  'trackid' => $track_id
																						  )); */
								/*if($track_id != 0 && $datos_empresa_factura->e_mail != ''){ //existe track id, se envía correo
									$this->envio_mail_dte($idfactura);
								}
								*/



							}

						} //FIN CREACION FACTURA
					} // FIN  if($datos_folio->estado == 'P' || $datos_folio->estado == 'T'){

				} // FIN if(count($datos_folio) > 0){
							

		}




	}



	public function get_factura($id_factura){

		$this->db->select('fc.tipo_documento, fc.num_factura, fc.fecha_factura, fc.sub_total, fc.descuento, fc.neto, fc.iva, fc.totalfactura, fc.forma, fc.id_factura')
		  ->from('factura_clientes fc')
		  ->where('fc.id',$id_factura)
		  ->limit(1);
		$query = $this->db->get();
		return $query->row();
	 }	 




public function crea_archivo_dte($xml,$idfactura,$tipo_caf,$tipo_dte){

				$datos_factura = $this->get_factura($idfactura);
				$datos_empresa_factura = $this->get_empresa_factura($idfactura);
				$rutCliente = substr($datos_empresa_factura->rut_cliente,0,strlen($datos_empresa_factura->rut_cliente) - 1)."-".substr($datos_empresa_factura->rut_cliente,-1);

			    $xml_dte = $tipo_dte == 'sii' ? $xml : str_replace("60803000-K",$rutCliente,$xml);

				$file_name = $tipo_dte == 'sii' ? "SII_" : "CLI_";
				$nombre_dte = $datos_factura->num_factura."_". $tipo_caf ."_".$idfactura."_".$file_name.date("His").".xml"; // nombre archivo
				$ruta = $tipo_dte == 'sii' ? 'dte' : 'dte_cliente';
				$path = date('Ym').'/'; // ruta guardado
				if(!file_exists('./facturacion_electronica/' . $ruta . '/'.$path)){
					mkdir('./facturacion_electronica/' . $ruta . '/'.$path,0777,true);
				}				
				$f_archivo = fopen('./facturacion_electronica/' . $ruta .'/'.$path.$nombre_dte,'w');
				fwrite($f_archivo,$xml_dte);
				fclose($f_archivo);

				return array('xml_dte' => $xml_dte,
							 'nombre_dte' => $nombre_dte,
							 'path' => $path);

	 }	 



}

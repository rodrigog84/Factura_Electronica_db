<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Facturaselectronicas extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->database();
		$this->load->model('facturaelectronica');
	}

	
	public function empresas(){


        $content = array(
                    'menu' => 'Configuraciones',
                    'title' => 'Configuraciones',
                    'subtitle' => 'Registro Empresa');


		$empresa = $this->facturaelectronica->get_empresa();
		$existe = count($empresa) > 0 ? true : false;

        if($existe){



        	$form['rut'] = number_format($empresa->rut,0,".",".")."-".$empresa->dv;
        	$form['razon_social'] = $empresa->razon_social;
        	$form['giro'] = $empresa->giro;
        	$form['cod_actividad'] = $empresa->cod_actividad;
        	$form['direccion'] = $empresa->dir_origen;
        	$form['comuna'] = $empresa->comuna_origen;
            $form['idregion'] = $empresa->idregion;
            $form['idcomuna'] = $empresa->idcomuna;
            $form['telefono'] = $empresa->telefono;
            $form['mail'] = $empresa->mail;
            
        	$form['fec_resolucion'] = substr($empresa->fec_resolucion,8,2)."/".substr($empresa->fec_resolucion,5,2)."/".substr($empresa->fec_resolucion,0,4);
        	$form['nro_resolucion'] = $empresa->nro_resolucion;
        	$form['logo'] = base_url() . "facturacion_electronica/images/" . $empresa->logo;

        }else{

        	$form['rut'] = "";
        	$form['razon_social'] = "";
        	$form['giro'] = "";
        	$form['cod_actividad'] = "";
        	$form['direccion'] = "";
        	$form['comuna'] = "";
        	$form['fec_resolucion'] = "";
        	$form['nro_resolucion'] = "";
            $form['idregion'] = 0;
            $form['idcomuna'] = 0;    
            $form['telefono'] = "";
            $form['mail'] = "";        
        	$form['logo'] = base_url() . "facturacion_electronica/images/sinimagen.jpg";
        }

        //echo "<pre>";
        //print_r($form); exit;
        $this->load->model('admin');
        $regiones = $this->admin->get_regiones();
        $vars['formValidation'] = true;
        $vars['jqueryRut'] = true;
        $vars['mask'] = true;
        $vars['inputmask'] = true;
        $vars['content_menu'] = $content;   
        $vars['regiones'] = $regiones;
        $vars['datosform'] = $form;
		$template = "template";
		$vars['content_view'] = 'facturaelectronica/empresas';
		$this->load->view($template,$vars);	
	}

	public function put_empresa(){
				//print_r($this->input->post(NULL,true)); exit;
		$this->load->model('facturaelectronica');


        $region = $this->input->post('region');
        $comuna = $this->input->post('comuna');
        $fec_resolucion = $this->input->post('fec_resolucion');

        if($fec_resolucion !=null){
                $date = DateTime::createFromFormat('d/m/Y', $fec_resolucion);
                $fec_resolucion = $date->format('Ymd');
            }else{
                $fec_resolucion = null;
                //$seguro_cesantia =0;
            }



		$empresa = $this->facturaelectronica->get_empresa();
		$tipo_caf = $this->input->post('tipoCaf');
        $config['upload_path'] = "./facturacion_electronica/images/"	;
        $config['file_name'] = 'logo_empresa';
        $config['allowed_types'] = "*";
        $config['max_size'] = "10240";
        $config['overwrite'] = TRUE;


        $this->load->library('upload', $config);

        $error = false;
        $carga = false;
        if (!$this->upload->do_upload("logo") && is_null($empresa->logo)) { // si no hay descarga y no tiene archivo cargado
            print_r($this->upload->data()); 
            print_r($this->upload->display_errors());
            $error = true;
            $message = "Error en subir archivo.  Intente nuevamente";
        }else{
        	
        	//$empresa = $this->facturaelectronica->get_empresa();
    		$rut = $this->input->post('rut');
    		$array_rut = explode("-",$rut);
    		//$fecha_resolucion = $this->input->post('fec_resolucion');
    		//$fec_resolucion = substr($fecha_resolucion,6,4)."-".substr($fecha_resolucion,3,2)."-".substr($fecha_resolucion,0,2);
    		//$fec_resolucion = $this->input->post('fec_resolucion');
    		$data_empresa = array(
    					'rut' => str_replace(".","",$array_rut[0]),
    					'dv' => $array_rut[1],
    					'razon_social' => $this->input->post('razon_social'),
    					'giro' => $this->input->post('giro'),
    					'cod_actividad' => $this->input->post('cod_actividad'),
    					'dir_origen' => $this->input->post('direccion'),
    					//'comuna_origen' => $this->input->post('comuna'),
                        'comuna_origen' => '',
    					'fec_resolucion' => $fec_resolucion,
    					'nro_resolucion' => $this->input->post('nro_resolucion'),
                        'idregion' => $region,
                        'idcomuna' => $comuna,
                        'telefono' => $this->input->post('telefono'),                        
                        'mail' => $this->input->post('mail'),  
    					'logo' => 'logo_empresa.png'
    			);
        	if(count($empresa) > 0){ //actualizar
        		$this->db->where('id',1);
        		$this->db->update('empresa',$data_empresa);

        	}else{ //insertar


	        	$carga = true;
				$this->db->insert('empresa',$data_empresa);

        	}






        }



		if($error && $carga){
			unlink($config['upload_path'].$config['file_name'].$data_file_upload['file_ext']);
		}

		redirect('facturaselectronicas/empresas');	
   		/*$resp['success'] = true;
   		$resp['message'] = $error ? $message : "Carga realizada correctamente";
   		echo json_encode($resp);*/
	 }	




    public function certificado(){


        $content = array(
                    'menu' => 'Configuraciones',
                    'title' => 'Configuraciones',
                    'subtitle' => 'Carga certificado Digital');

        $this->load->model('facturaelectronica');
        $existe = file_exists($this->facturaelectronica->ruta_certificado()) ? true: false;

        if(!$existe){
            $existe = file_exists($this->facturaelectronica->ruta_certificado('pfx')) ? true: false;            
        }


        if($existe){
                $vars['message'] = "Certificado ya Cargado. Subir uno nuevo reemplazará el existente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';     
        }else{
                $vars['message'] = "Certificado No Cargado. Debe cargar uno para generar documentos electr&oacute;nicos";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';

        }

        $vars['formValidation'] = true;
        $vars['content_menu'] = $content;   
        $template = "template";
        $vars['content_view'] = 'facturaelectronica/certificado';
        $this->load->view($template,$vars); 

     }



	public function cargacertificado(){
		//print_r($_FILES);
		//print_r($this->input->post(NULL,true)); exit;

		$password = $this->input->post('password');

        $password_encrypt = md5($password.SALT);
        $config['upload_path'] = "./facturacion_electronica/certificado/"	;

        $config['file_name'] = "certificado";
        $config['allowed_types'] = "*";
        $config['max_size'] = "10240";
        $config['overwrite'] = TRUE;
        //$config['max_width'] = "2000";
        //$config['max_height'] = "2000";

        $this->load->library('upload', $config);
       // $this->upload->do_upload("certificado");


        if (!$this->upload->do_upload("certificado")) {
            //*** ocurrio un error
            print_r($this->upload->data()); 
            //print_r($this->upload->display_errors());
            //redirect('accounts/add_cuenta/2');
            //return;
        }else{
			/*$this->db->where('nombre', 'cert_password');
			$this->db->update('param_fe',array('valor' => $password)); 

			$this->db->where('nombre', 'cert_password_encrypt'); //veremos si se puede usar la password encriptada
			$this->db->update('param_fe',array('valor' => $password_encrypt)); 
            echo $this->db->last_query(); exit;*/

        }

        $this->db->where('nombre', 'cert_password');
        $this->db->update('param_fe',array('valor' => $password)); 

        $this->db->where('nombre', 'cert_password_encrypt'); //veremos si se puede usar la password encriptada
        $this->db->update('param_fe',array('valor' => $password_encrypt));         
   		$dataupload = $this->upload->data();

        $vars['content_menu'] = $content;   
		

   		//redirect('');
        redirect('facturaselectronicas/certificado');   
   		//$resp['success'] = true;
   		//echo json_encode($resp);
	 }


    public function cargar_folio(){


        $content = array(
                    'menu' => 'Configuraciones',
                    'title' => 'Configuraciones',
                    'subtitle' => 'Carga de CAF');


        $message_si = " folios disponibles";
        $message_no = "No existen folios disponibles";


        $cant_33 = $this->facturaelectronica->estado_tipo_documento(33);
        $cant_34 = $this->facturaelectronica->estado_tipo_documento(34);
        $cant_56 = $this->facturaelectronica->estado_tipo_documento(56);
        $cant_61 = $this->facturaelectronica->estado_tipo_documento(61);


        $array_folios[33]['message'] = $cant_33 > 0 ? $cant_33.$message_si : $message_no;
        $array_folios[34]['message'] = $cant_34 > 0 ? $cant_34.$message_si : $message_no;
        $array_folios[56]['message'] = $cant_56 > 0 ? $cant_56.$message_si : $message_no;
        $array_folios[61]['message'] = $cant_61 > 0 ? $cant_61.$message_si : $message_no;

        $array_folios[33]['style'] = $cant_33 > 0 ? 'text-success' : 'text-warning';
        $array_folios[34]['style'] = $cant_34 > 0 ? 'text-success' : 'text-warning';
        $array_folios[56]['style'] = $cant_56 > 0 ? 'text-success' : 'text-warning';
        $array_folios[61]['style'] = $cant_61 > 0 ? 'text-success' : 'text-warning';

        $template = "template";
        $vars['content_menu'] = $content;   
        $vars['datos_folios'] = $array_folios;
        $vars['content_view'] = 'facturaelectronica/cargar_folio';
        $this->load->view($template,$vars); 
    }     




    public function exportFePDF($idfactura,$cedible = null){

        $this->load->model('facturaelectronica');
        $this->facturaelectronica->exportFePDF($idfactura,'id',$cedible);       

    }



    public function ver_dte($idfactura,$tipo = 'sii'){

        $ruta = $tipo == 'cliente' ? 'dte_cliente' : 'dte';
        $this->load->model('facturaelectronica');
        $dte = $this->facturaelectronica->datos_dte($idfactura);
        if(empty($dte)){
        //if($dte->path_dte == ''){
            $dte = $this->facturaelectronica->crea_dte($idfactura,$tipo);
        }else{

            if($dte->{$ruta} == ''){
                $dte = $this->facturaelectronica->crea_dte($idfactura,$tipo);
            }
        }



        $nombre_archivo = $tipo == 'cliente' ? $dte->archivo_dte_cliente : $dte->archivo_dte;
        $path_archivo = "./facturacion_electronica/" . $ruta . "/".$dte->path_dte;
        

        if(!file_exists($path_archivo.$nombre_archivo)){
            $dte = $this->facturaelectronica->crea_dte($idfactura,$tipo);
            $nombre_archivo = $tipo == 'cliente' ? $dte->archivo_dte_cliente : $dte->archivo_dte;
            $path_archivo = "./facturacion_electronica/" . $ruta . "/".$dte->path_dte;            
        }

        $data_archivo = basename($path_archivo.$nombre_archivo);

        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename=' . $data_archivo);
        header('Content-Length: ' . filesize($path_archivo.$nombre_archivo));
        readfile($path_archivo.$nombre_archivo);                
     }

    public function cargacaf(){
        //print_r($_FILES);
        //print_r($this->input->post(NULL,true)); exit;

        $tipo_caf = $this->input->post('tipoCaf');
        $config['upload_path'] = "./facturacion_electronica/caf/"   ;
        $config['file_name'] = $tipo_caf."_".date("Ymdhis");
        $config['allowed_types'] = "*";
        $config['max_size'] = "10240";
        $config['overwrite'] = TRUE;

        //$config['max_width'] = "2000";
        //$config['max_height'] = "2000";
        $this->load->library('upload', $config);
       // $this->upload->do_upload("certificado");

        $error = false;
        $carga = false;
        if (!$this->upload->do_upload("caf")) {
            print_r($this->upload->data()); 
            print_r($this->upload->display_errors());
            $error = true;
            $message = "Error en subir archivo.  Intente nuevamente";
        }else{
            $data_file_upload = $this->upload->data();
            $carga = true;
            try {
                $xml_content = file_get_contents($config['upload_path'].$config['file_name'].$data_file_upload['file_ext']);
                $xml = new SimpleXMLElement($xml_content);
            } catch (Exception $e) {
                $error = true;
                $message = "Error al cargar XML.  Verifique formato y cargue nuevamente";
            }


            if(!$error){ //Ya cargó.  Leemos si el archivo es del tipo que elegimos anteriormente
                
                $tipo_caf_subido = $xml->CAF->DA->TD; 
                if($tipo_caf_subido != $tipo_caf){
                    $error = true;
                    $message = "CAF cargado no corresponde al seleccionado previamente.  Verifique archivo y cargue nuevamente";
                }
            }



            // VALIDAR EL RUT DE EMPRESA DEL CAF
            if(!$error){

                $this->db->select('valor ')
                  ->from('param_fe')
                  ->where('nombre','rut_empresa');
                $query = $this->db->get();
                $parametro = $query->row(); 

                $rut_parametro = $parametro->valor;

                $rut_caf = $xml->CAF->DA->RE; 

                if($rut_parametro != $rut_caf){
                    $error = true;
                    $message = "CAF cargado no corresponde a empresa registrada.  Verifique archivo y cargue nuevamente";
                }                       
            }


            if(!$error){ //Ya cargó y el archivo es correcto
                $folio_desde = $xml->CAF->DA->RNG->D; 
                $folio_hasta = $xml->CAF->DA->RNG->H; 

                //VALIDAMOS SI LOS FOLIOS YA ESTÁN CARGADOS.  SI YA ESTÁN CARGADOS, DAREMOS ERROR INDICANDO QUE CAF YA EXISTE
                $this->db->select('f.id ')
                                  ->from('folios_caf f')
                                  ->join('caf c','f.idcaf = c.id')
                                  ->where('c.tipo_caf',$tipo_caf)
                                  ->where('f.folio between ' . $folio_desde . ' and ' . $folio_hasta);

                $query = $this->db->get();
                $folios_existentes = $query->result();              

                if(count($folios_existentes) > 0){
                    $error = true;
                    $message = "CAF cargado contiene folios ya existentes.  Verifique archivo y cargue nuevamente";
                }else{

                    // SE CREA LOG DE CARGA DE FOLIOS
                    $data_array = array(
                        'tipo_caf' => $tipo_caf,
                        'fd' => $folio_desde,
                        'fh' => $folio_hasta,                   
                        'archivo' => $config['file_name'].".xml",
                        'caf_content' => $xml_content,
                        );
                    $this->db->insert('caf',$data_array); 
                    $idcaf = $this->db->insert_id();

                    // SE CREA DETALLE DE FOLIOS

                    for($folio_carga = (int)$folio_desde; $folio_carga <= (int)$folio_hasta; $folio_carga++){
                        $data_folio = array(
                            'folio' => $folio_carga,
                            'idcaf' => $idcaf,
                            'dte' => '',
                            'path_dte' => '',
                            'archivo_dte' => '',
                            'pdf' => '',
                            'pdf_cedible' => '',
                            'trackid' => '',
                            'idfactura' => 0,
                            'created_at' => date("Y-m-d H:i:s")
                            );
                        $this->db->insert('folios_caf',$data_folio);
                    }
                }





            }


        }



        if($error && $carga){
            unlink($config['upload_path'].$config['file_name'].$data_file_upload['file_ext']);
        }
        redirect('facturaselectronicas/cargar_folio');  

        /*$resp['success'] = true;
        $resp['message'] = $error ? $message : "Carga realizada correctamente";
        echo json_encode($resp);*/
     }


    public function factura_proveedor(){


    $resultid = $this->session->flashdata('factura_proveedor_result');
    //echo $resultid; exit;
    if($resultid == 1){
                $vars['message'] = "Acuse de recibo generado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';     
     }else if($resultid == 2){
                $vars['message'] = "Acuse de recibo enviado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';     
     }


        $content = array(
                    'menu' => 'Configuraciones',
                    'title' => 'Configuraciones',
                    'subtitle' => 'Carga DTE Compras');

        

        $this->load->model('facturaelectronica');
        $datos_factura = $this->facturaelectronica->reporte_provee();


        //var_dump($datos_factura); exit;

        $template = "template";
        $vars['content_menu'] = $content;   
        $vars['content_view'] = 'facturaelectronica/factura_proveedor';
        $vars['gritter'] = true;
        $vars['datos_factura'] = $datos_factura;   

        $this->load->view($template,$vars); 
    }   



    public function reporte_factura_proveedor(){


    $resultid = $this->session->flashdata('factura_proveedor_result');
    //echo $resultid; exit;
    if($resultid == 1){
                $vars['message'] = "Acuse de recibo generado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';     
     }else if($resultid == 2){
                $vars['message'] = "Acuse de recibo enviado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';     
     }


            $title_libro = 'detalle_espacios_comunes';
            $title_report = 'Detalle Cuentas Espacios Comunes';

            $this->load->library('PHPExcel');
            $this->phpexcel->setActiveSheetIndex(0);
            $sheet = $this->phpexcel->getActiveSheet();
            $sheet->setTitle($title_libro);


            $sheet->getColumnDimension('A')->setWidth(5);

            $sheet->mergeCells('B2:D2');
            $sheet->setCellValue('B2', $title_report);
            $sheet->getColumnDimension('B')->setWidth(20);
            $sheet->setCellValue('B3', 'Nombre Comunidad');
            $sheet->setCellValue('C3',html_entity_decode('a'));
            $sheet->mergeCells('C3:D3');
            $sheet->setCellValue('B4', 'Rut Comunidad');
            $sheet->setCellValue('C4','15773067-3');          
            $sheet->mergeCells('C4:D4');
            $sheet->setCellValue('B5', 'Direccion Comunidad');
            $sheet->setCellValue('C5','111');                       
            $sheet->mergeCells('C5:D5');
            $sheet->setCellValue('B6', 'Fecha emision Reporte');
            $sheet->setCellValue('C6',date('d/m/Y') );
            $sheet->mergeCells('C6:D6');
            
            $sheet->getStyle("B2:B6")->getFont()->setBold(true);
            $sheet->getStyle("B2:D6")->getFont()->setSize(10);



            header("Content-Type: application/vnd.ms-excel");
            $nombreArchivo = $title_libro;
            header("Content-Disposition: attachment; filename=\"$nombreArchivo.xls\"");
            header("Cache-Control: max-age=0");
            // Genera Excel
            $writer = new PHPExcel_Writer_Excel5($this->phpexcel); //objeto de PHPExcel, para escribir en el excel
            //$writer = new PHPExcel_Writer_Excel2007($this->phpexcel); //objeto de PHPExcel, para escribir en el excel
            // Escribir
            //$writer->setIncludeCharts(TRUE);          
            $writer->save('php://output');
            exit;           



    }   


    public function ver_pdf_compra($idcompra){
        $this->load->model('facturaelectronica');

        $this->facturaelectronica->exportFePDFCompra($idcompra);

        


        
     }



    public function envio_respuesta($idfactura) {

        $content = array(
                    'menu' => 'Configuraciones',
                    'title' => 'Configuraciones',
                    'subtitle' => 'Env&iacute;o Respuesta Intercambio');

        

        $this->load->model('facturaelectronica');
        $datos_factura = $this->facturaelectronica->reporte_provee($idfactura);
        $resumen_dte = $this->facturaelectronica->lectura_dte_provee($idfactura);
       // var_dump($datos_factura); exit;

        $vars['icheck'] = true;
        
        $template = "template";
        $vars['content_menu'] = $content;   
        $vars['content_view'] = 'facturaelectronica/envio_respuesta';

        $vars['datos_factura'] = $datos_factura;   
        $vars['resumen_dte'] = $resumen_dte;   

        $this->load->view($template,$vars); 



    }


    public function envio_email_acuse($idfactura) {

        $content = array(
                    'menu' => 'Configuraciones',
                    'title' => 'Configuraciones',
                    'subtitle' => 'Env&iacute;o Email Respuesta');

        

        $this->load->model('facturaelectronica');
        $datos_factura = $this->facturaelectronica->reporte_provee($idfactura);
        $resumen_dte = $this->facturaelectronica->lectura_dte_provee($idfactura);
        //var_dump($datos_factura); exit;

        $vars['icheck'] = true;
        
        $template = "template";
        $vars['content_menu'] = $content;   
        $vars['content_view'] = 'facturaelectronica/envio_email_respuesta';

        $vars['datos_factura'] = $datos_factura;   
        $vars['resumen_dte'] = $resumen_dte;   
        $vars['formValidation'] = true;

        $this->load->view($template,$vars); 



    }



    public function envio_email_acuse_recibo(){


        $email_respuesta = $this->input->post('responder');
        $idfactura = $this->input->post('idfactura');

          

        $datos_factura = $this->facturaelectronica->envia_email_acuse_recibo($idfactura,$email_respuesta);
        $this->session->set_flashdata('factura_proveedor_result', 2);
        redirect('facturaselectronicas/factura_proveedor');   




    }


    public function envio_acuse_recibo(){


        $array_post = $this->input->post(NULL,true);

        $array_dte_enviados = array();
        $i = 0;
        foreach($array_post as $elem => $value_elem){
            $arr_el = explode("-",$elem);
            if($arr_el[0] == 'estado_documento'){
                $array_dte_enviados[$i]['TipoDTE'] = $arr_el[1];
                $array_dte_enviados[$i]['Folio'] = $arr_el[2];
                $array_dte_enviados[$i]['Estado'] = $value_elem;
                $i++;
            }
        }        

        $array_acuse = array('idfactura' => $array_post['idfactura'],
                             'estado_envio' => $array_post['estado_envio'],
                             'mercaderias' => isset($array_post['mercaderias']) ? true : false,
                             'detalle_dte' => $array_dte_enviados
                              );
        print_r($array_acuse);
        $datos_factura = $this->facturaelectronica->envia_acuse_recibo($array_acuse);
        $this->session->set_flashdata('factura_proveedor_result', 1);
        redirect('facturaselectronicas/factura_proveedor');   

    }

    public function cargar_contribuyente(){


        $content = array(
                    'menu' => 'Configuraciones',
                    'title' => 'Configuraciones',
                    'subtitle' => 'Carga Base Contribuyentes');


        $template = "template";
        $vars['content_view'] = 'facturaelectronica/cargar_contribuyente';
        $this->load->view($template,$vars); 
    }     



    public function confi_email(){

        $content = array(
                    'menu' => 'Configuraciones',
                    'title' => 'Configuraciones',
                    'subtitle' => 'Configuraci&oacute;n Email');


        $email = $this->facturaelectronica->get_email();
        $existe = count($email) > 0 ? true : false;

        if($existe){

            $form['email_contacto'] = $email->email_contacto;
            $form['pass_contacto'] = $email->pass_contacto;
            $form['tserver_contacto'] = $email->tserver_contacto;
            $form['port_contacto'] = $email->port_contacto;
            $form['host_contacto'] = $email->host_contacto;
            $form['email_intercambio'] = $email->email_intercambio;
            $form['pass_intercambio'] = $email->pass_intercambio;
            $form['tserver_intercambio'] = $email->tserver_intercambio;
            $form['port_intercambio'] = $email->port_intercambio;
            $form['host_intercambio'] = $email->host_intercambio;

        }else{

            $form['email_contacto'] = "";
            $form['pass_contacto'] = "";
            $form['tserver_contacto'] = "";
            $form['port_contacto'] = "";
            $form['host_contacto'] = "";
            $form['email_intercambio'] = "";
            $form['pass_intercambio'] = "";
            $form['tserver_intercambio'] = "";
            $form['port_intercambio'] = "";
            $form['host_intercambio'] = "";


        }


        $vars['formValidation'] = true;
        $vars['content_menu'] = $content;   
        $template = "template";
        $vars['datosform'] = $form;
        $vars['content_view'] = 'facturaelectronica/confi_email';
        $this->load->view($template,$vars); 
    }     



    public function registro_email(){
        $data = array(
                    'email_contacto' => $this->input->post('email_contacto'),
                    'pass_contacto' => $this->input->post('pass_contacto'),
                    'tserver_contacto' => $this->input->post('tipoServer_contacto'),
                    'port_contacto' => $this->input->post('port_contacto'),
                    'host_contacto' => $this->input->post('host_contacto'),
                    'email_intercambio' => $this->input->post('email_intercambio'),
                    'pass_intercambio' => $this->input->post('pass_intercambio'),
                    'tserver_intercambio' => $this->input->post('tipoServer_intercambio'),
                    'port_intercambio' => $this->input->post('port_intercambio'),
                    'host_intercambio' => $this->input->post('host_intercambio'),
            );
        $this->facturaelectronica->registro_email($data);

        redirect('facturaselectronicas/confi_email');  

    }   




    public function exportPDF($idfactura){

        $numero = $this->input->get('numfactura');
        $cabecera = $this->db->get_where('factura_clientes', array('id' => $idfactura));    
        $tipodocumento = 1;
        foreach($cabecera->result() as $v){  
                $tipodocumento = $v->tipo_documento; 
        }


        $this->load->model('facturaelectronica');
        $this->facturaelectronica->exportFePDF($idfactura,'id');


    }


    public function docto_venta(){


    $resultid = $this->session->flashdata('factura_proveedor_result');



        $content = array(
                    'menu' => 'Facturaci&oacute;n',
                    'title' => 'Facturaci&oacute;n',
                    'subtitle' => 'Documentos Ventas');

        

        $this->load->model('facturaelectronica');
        $datos_factura = $this->facturaelectronica->facturas_venta();


        //var_dump($datos_factura); exit;

        $template = "template";
        $vars['content_menu'] = $content;   
        $vars['content_view'] = 'facturaelectronica/docto_venta';
        $vars['gritter'] = true;
        $vars['datos_factura'] = $datos_factura;   

        $this->load->view($template,$vars); 
    }   



}

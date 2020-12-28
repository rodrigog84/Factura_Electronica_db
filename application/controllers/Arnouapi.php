<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;


require str_replace("\\", "/", __DIR__) . "/../libraries/RestController.php";	
require str_replace("\\", "/", __DIR__) . "/../libraries/Format.php";	

class Arnouapi extends RestController {

	function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->helper('format');
    }

    public function users_get()
    {
        // Users from a data store e.g. database
        $users = [
            ['id' => 0, 'name' => 'John', 'email' => 'john@example.com'],
            ['id' => 1, 'name' => 'Jim', 'email' => 'jim@example.com'],
        ];

        $id = $this->get( 'id' );

        if ( $id === null )
        {
            // Check if the users data store contains users
            if ( $users )
            {
                // Set the response and exit
                $this->response( $users, 200 );
            }
            else
            {
                // Set the response and exit
                $this->response( [
                    'status' => false,
                    'message' => 'No users were found'
                ], 404 );
            }
        }
        else
        {
            if ( array_key_exists( $id, $users ) )
            {
                $this->response( $users[$id], 200 );
            }
            else
            {
                $this->response( [
                    'status' => false,
                    'message' => 'No such user found'
                ], 404 );
            }
        }
    }


	/*public function dte_get()
    {
        // Users from a data store e.g. database
        $users = [
            ['id' => 0, 'name' => 'John', 'email' => 'john@example.com'],
            ['id' => 1, 'name' => 'Jim', 'email' => 'jim@example.com'],
        ];

        $id = $this->get( 'id' );

        if ( $id === null )
        {
            // Check if the users data store contains users
            if ( $users )
            {
                // Set the response and exit
                $this->response( $users, 200 );
            }
            else
            {
                // Set the response and exit
                $this->response( [
                    'status' => false,
                    'message' => 'No users were found'
                ], 404 );
            }
        }
        else
        {
            if ( array_key_exists( $id, $users ) )
            {
                $this->response( $users[$id], 200 );
            }
            else
            {
                $this->response( [
                    'status' => false,
                    'message' => 'No such user found'
                ], 404 );
            }
        }
    }*/


public function dte_post()
    {
        // Users from a data store e.g. database
      //  print_r($_POST); 
       $result =  array(
            'dte' => $this->input->post('dte'),
        );

      // echo $result['dte'];
       $dte_array = json_decode($result['dte']);
      // var_dump($dte_array);

       $cod_empresa = $dte_array->Datos_Emisor->Codigo_Empresa;
       $rut_empresa = $dte_array->Datos_Emisor->Rut_Empresa;

        $this->load->model('facturaelectronica');
        $valida_empresa = $this->facturaelectronica->valida_empresa($rut_empresa,$cod_empresa);
       // $valida_empresa = false;

        if(!$valida_empresa){

        $response = array(
                                     'result' => 'Error al Validar Empresa',
                                      'status' => 'failure'

                                );


        }else{


                $id_empresa = $valida_empresa;

                $empresa = $this->facturaelectronica->get_empresa($id_empresa);


                $tipo_caf = $dte_array->Datos_Factura->Iddoc->TipoDTE;
               // echo $tipo_caf."  ".$id_empresa;
               // print_r($this->facturaelectronica->folio_documento_electronico($tipo_caf,$id_empresa)); exit;
                $folio = $this->facturaelectronica->folio_documento_electronico($tipo_caf,$id_empresa);  // ir a buscar folio

                if($folio == 0){
                                $response = array(
                                     'result' => 'Error al crear documento.  No existen folios disponibles',
                                      'status' => 'failure'

                                );

                }else{


                            $fecha_emision = $dte_array->Datos_Factura->Iddoc->Fecha_Emision;
                            $vendedor = $dte_array->Datos_Factura->Iddoc->Vendedor;
                            $cond_pago = $dte_array->Datos_Factura->Iddoc->Condicion_Pago;

                            $rut_cliente = $dte_array->Datos_Factura->Receptor->Rut_Receptor;
                            $raz_soc_cliente = $dte_array->Datos_Factura->Receptor->Razon_Social;
                            $giro_cliente = $dte_array->Datos_Factura->Receptor->Giro;
                            $cod_act_econ_cli = $dte_array->Datos_Factura->Receptor->Cod_Actividad_Economica;
                            $dir_cliente = $dte_array->Datos_Factura->Receptor->Direccion;
                            $com_cliente = $dte_array->Datos_Factura->Receptor->Comuna;
                            $ciu_cliente = $dte_array->Datos_Factura->Receptor->Ciudad;

                            $array_rut_cliente = explode('-',$rut_cliente);



                            $referencia = 0;

                            $neto = $dte_array->Datos_Factura->Totales->MntNeto;
                            $iva = $dte_array->Datos_Factura->Totales->Iva;
                            $total = $dte_array->Datos_Factura->Totales->MntTotal;


                            $factura_cliente = array(
                                'tipo_documento' => caftotd($tipo_caf),
                                'id_cliente' => 0,
                                'rut_cliente' => $array_rut_cliente[0],
                                'dv_cliente' => $array_rut_cliente[1],
                                'raz_soc_cliente' => $raz_soc_cliente,
                                'giro_cliente' => $giro_cliente,
                                'cod_act_econ_cli' => $cod_act_econ_cli,
                                'dir_cliente' => $dir_cliente,
                                'com_cliente' => $com_cliente,
                                'ciu_cliente' => $ciu_cliente,
                                'num_factura' => $folio,
                                'id_vendedor' => 1,
                                'sub_total' => $neto,
                                'neto' => $neto,
                                'iva' => $iva,
                                'totalfactura' => $total,
                                'fecha_factura' => $fecha_emision,
                                'fecha_venc' => $fecha_emision,
                                'id_factura' => $referencia,
                                'id_sucursal' => 0,
                                'id_cond_venta' => 0,
                                'descuento' => 0,
                                'id_factura' => 0,
                                'observacion' => '',
                                'id_observa' => 0,
                                'id_despacho' => 0,
                                'estado' => 0,
                                'forma' => 1,
                                'idempresa' => $id_empresa,
                                'vendedor' => $vendedor,
                                'condicion_pago' => $cond_pago
                            );

                            $this->db->insert('factura_clientes', $factura_cliente); 
                            $idfactura = $this->db->insert_id();                
                           // echo $idfactura;

                           
                           // $neto = 0;
                           // $iva = 0;
                           // $total = 0;
                             $i = 0;
                            foreach ($dte_array->Datos_Factura->Detalle as $detalle) {
                               // print_r($detalle);
                               $codigo = $detalle->Codigo;
                                $cantidad = $detalle->Cantidad;
                                $nombre = $detalle->Nombre;
                                $unidad = $detalle->Unidad;
                                $precioUnitario = $detalle->Precio_Unitario;
                                $neto = $detalle->Neto;
                                $iva = $detalle->Iva;
                                $total = $detalle->Total;


                                $detalle_factura_cliente = array(
                                                                'id_factura' => $idfactura,
                                                                'num_factura' => $folio,
                                                                'id_producto' => 0,
                                                                'fecha' => $fecha_emision,
                                                                'codigo' => $codigo,
                                                                'cantidad' => $cantidad,
                                                                'nombre_producto' => $nombre,
                                                                'unidad' => $unidad,
                                                                'precio' => $precioUnitario,
                                                                'neto' => $neto,
                                                                'iva' => $iva,
                                                                'descuento' => 0,
                                                                'id_despacho' => 0,
                                                                'totalproducto' => $total
                                                            );
                                $this->db->insert('detalle_factura_cliente', $detalle_factura_cliente);

                                $lista_detalle[$i]['NmbItem'] = $nombre;
                                $lista_detalle[$i]['QtyItem'] = $cantidad;
                                $lista_detalle[$i]['CdgItem'] = $codigo;
                                $lista_detalle[$i]['UnmdItem'] = $unidad;
                                    //$lista_detalle[$i]['PrcItem'] = $detalle->precio;
                                    //$lista_detalle[$i]['PrcItem'] = round((($detalle->precio*$detalle->cantidad)/1.19)/$detalle->cantidad,0);
                                    //$total = $detalle->precio*$detalle->cantidad;
                                    //$neto = round($total/1.19,2);
                                $lista_detalle[$i]['PrcItem'] = $precioUnitario;                     
                                $i++;



                            }



                            foreach ($dte_array->Datos_Factura->Referencia as $referencia) {
                               // print_r($detalle);
                               $nrolinref = $referencia->NroLinRef;
                                $tpodocref = $referencia->TpoDocRef;
                                $folioref = $referencia->FolioRef;
                                


                                $detalle_referencias = array(
                                                                'idfactura' => $idfactura,
                                                                'nrolinref' => $nrolinref,
                                                                'tpodocref' => $tpodocref,
                                                                'folioref' => $folioref
                                                            );

                                $this->db->insert('referencias', $detalle_referencias);

                            }
                            

                            header('Content-type: text/plain; charset=ISO-8859-1');
                            $config = $this->facturaelectronica->genera_config($id_empresa);
                            include $this->facturaelectronica->ruta_libredte();


                            if($tipo_caf == 39){
                                      $factura = [
                                        // CASO 1
                                            'Encabezado' => [
                                                'IdDoc' => [
                                                   'TipoDTE' => $tipo_caf,
                                                    'Folio' => $folio,
                                                    'FchEmis' => substr($fecha_emision,0,10),
                                                ],
                                                 'Emisor' => [
                                                      'RUTEmisor' => $empresa->rut.'-'.$empresa->dv,
                                                      'RznSoc' => substr(permite_alfanumerico($empresa->razon_social),0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES,
                                                      'GiroEmis' => substr(permite_alfanumerico($empresa->giro),0,80), //LARGO DE GIRO DEL EMISOR NO PUEDE SER SUPERIOR A 80 CARACTERES
                                                      'Acteco' => $empresa->cod_actividad,
                                                      'DirOrigen' =>  substr(permite_alfanumerico($empresa->dir_origen),0,70), //LARGO DE DIRECCION DE ORIGEN NO PUEDE SER SUPERIOR A 70 CARACTERES
                                                      'CmnaOrigen' => substr(permite_alfanumerico($empresa->comuna_origen),0,20), //LARGO DE COMUNA DE ORIGEN NO PUEDE SER SUPERIOR A 20 CARACTERES
                                                  ],
                                               'Receptor' => [
                                                        'RUTRecep' => $rut_cliente,
                                                        'RznSocRecep' =>  substr(permite_alfanumerico($raz_soc_cliente),0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES
                                                        'GiroRecep' => substr(permite_alfanumerico($giro_cliente),0,40),  //LARGO DEL GIRO NO PUEDE SER SUPERIOR A 40 CARACTERES
                                                        'DirRecep' => substr($dir_cliente,0,70), //LARGO DE DIRECCION NO PUEDE SER SUPERIOR A 70 CARACTERES
                                                        'CmnaRecep' => substr($com_cliente,0,20), //LARGO DE COMUNA NO PUEDE SER SUPERIOR A 20 CARACTERES
                                                    ],
                                            ],
                                            'Detalle' => $lista_detalle,
                                    ];



                            }else{

                                    $factura = [
                                            'Encabezado' => [
                                                'IdDoc' => [
                                                    'TipoDTE' => $tipo_caf,
                                                    'Folio' => $folio,
                                                    'FchEmis' => substr($fechafactura,0,10)
                                                    // 'TpoTranVenta' => 4
                                                ],
                                                'Emisor' => [
                                                    'RUTEmisor' => $empresa->rut.'-'.$empresa->dv,
                                                    'RznSoc' => substr(permite_alfanumerico($empresa->razon_social),0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES,
                                                    'GiroEmis' => substr(permite_alfanumerico($empresa->giro),0,80), //LARGO DE GIRO DEL EMISOR NO PUEDE SER SUPERIOR A 80 CARACTERES
                                                    'Acteco' => $empresa->cod_actividad,
                                                    'DirOrigen' =>  substr(permite_alfanumerico($empresa->dir_origen),0,70), //LARGO DE DIRECCION DE ORIGEN NO PUEDE SER SUPERIOR A 70 CARACTERES
                                                    'CmnaOrigen' => substr(permite_alfanumerico($empresa->comuna_origen),0,20), //LARGO DE COMUNA DE ORIGEN NO PUEDE SER SUPERIOR A 20 CARACTERES
                                                ],
                                                'Receptor' => [
                                                    'RUTRecep' => $rutCliente,
                                                    'RznSocRecep' =>  substr(permite_alfanumerico($datos_empresa_factura->nombre_cliente),0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES
                                                    'GiroRecep' => substr(permite_alfanumerico($datos_empresa_factura->giro),0,40),  //LARGO DEL GIRO NO PUEDE SER SUPERIOR A 40 CARACTERES
                                                    'DirRecep' => substr($dir_cliente,0,70), //LARGO DE DIRECCION NO PUEDE SER SUPERIOR A 70 CARACTERES
                                                    'CmnaRecep' => substr($nombre_comuna,0,20), //LARGO DE COMUNA NO PUEDE SER SUPERIOR A 20 CARACTERES
                                                ],
                                              'Totales' => [
                                                  // estos valores serán calculados automáticamente
                                                  'MntNeto' => isset($datos_factura->neto) ? $datos_factura->neto : 0,
                                                  //'TasaIVA' => \sasco\LibreDTE\Sii::getIVA(),
                                                  'IVA' => isset($datos_factura->iva) ? $datos_factura->iva : 0,
                                                  'MntTotal' => isset($datos_factura->totalfactura) ? $datos_factura->totalfactura : 0,
                                              ],                        
                                            ],
                                              'Detalle' => $lista_detalle,
                                              'Referencia' => $referencia
                                        ];


                            }


                            $caratula = [
                                  //'RutEnvia' => '11222333-4', // se obtiene de la firma
                                  'RutReceptor' => '60803000-K',
                                  'FchResol' => $empresa->fec_resolucion,
                                  'NroResol' => $empresa->nro_resolucion
                              ];

                            //  print_r($caratula); 

                              $caratula_cliente = [
                                  //'RutEnvia' => '11222333-4', // se obtiene de la firma
                                  'RutReceptor' => $rut_cliente,
                                  'FchResol' => $empresa->fec_resolucion,
                                  'NroResol' => $empresa->nro_resolucion
                              ];  


                                                            
                              //exit;
                              // Objetos de Firma y Folios
                              $Firma = new sasco\LibreDTE\FirmaElectronica($config['firma']); //lectura de certificado digital            

                              $caf = $this->facturaelectronica->get_content_caf_folio($folio,$tipo_caf,$id_empresa);
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
                           //  echo $xml_dte;
                         //    var_dump($EnvioDTE->schemaValidate());  exit;
            /*
              foreach (sasco\LibreDTE\Log::readAll() as $error)
                      echo $error,"\n";                  
                              

                              exit;*/


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
                                  //$tipo_envio = $this->facturaelectronica->busca_parametro_fe('envio_sii'); //ver si está configurado para envío manual o automático
                                $tipo_envio = 'manual';

                                $dte = $this->facturaelectronica->crea_archivo_dte($xml_dte,$idfactura,$tipo_caf,'sii');
                                $dte_cliente = $this->facturaelectronica->crea_archivo_dte($xml_dte_cliente,$idfactura,$tipo_caf,'cliente');

                                $sql_update_folio = "update     f
                                                    set         dte = '" . $dte['xml_dte'] . "'
                                                                ,dte_cliente = '" . $dte_cliente['xml_dte'] . "'
                                                                ,estado = 'O'
                                                                ,idfactura = " . $idfactura . "
                                                                ,path_dte = '" . $dte['path'] . "'
                                                                ,archivo_dte = '" . $dte['nombre_dte'] . "'
                                                                ,archivo_dte_cliente = '" . $dte['nombre_dte'] . "'
                                                                ,trackid = " . $track_id . "
                                                     from       folios_caf f
                                                     inner join caf c on f.idcaf = c.id
                                                     where      f.folio = ". $folio . "
                                                     and        c.tipo_caf = " . $tipo_caf;
                                         $this->db->query($sql_update_folio);


                                      /*   $this->db->where('f.folio', $folio);
                                          $this->db->where('c.tipo_caf', $tipo_caf);
                                          $this->db->join('caf c','f.idcaf = c.id');
                                                                      
                                          $this->db->update('f',array('dte' => $dte['xml_dte'],
                                                                              'dte_cliente' =>'aa',// $dte_cliente['xml_dte'],
                                                                              'estado' => 'O',
                                                                              'idfactura' => $idfactura,
                                                                              'path_dte' => $dte['path'],
                                                                              'archivo_dte' => $dte['nombre_dte'],
                                                                              'archivo_dte_cliente' => $dte_cliente['nombre_dte'],
                                                                              'trackid' => $track_id
                                                                              ));
                                          $this->db->from('folios_caf f'); */ 

                                          /*$this->db->where('f.folio', $folio);
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
                                        //  $this->facturaelectronica->envio_mail_dte($idfactura);
                                    }*/




                              }


                            $response = array(
                                                // 'url_pdf' => 'http://www.arnou.cl/Infosys_web/core/facturacion_electronica/pdf/202011/dte_96516320-4_T33F35349.pdf',
                                                  //'url_xml' => 'http://www.arnou.cl/Infosys_web/core/facturacion_electronica/dte/202011/18363_52_1492_SII_100205.xml',
                                                  'url_pdf' => base_url().'facturaselectronicas/exportPDF/' . $idfactura,
                                                  'url_xml' => base_url().'facturaselectronicas/ver_dte/' . $idfactura,
                                                  'result' => 'Documento creado correctamente',
                                                  'status' => 'success'
                                                  ,'tipo_caf' => $tipo_caf
                                                 /* ,'folio' => $folio
                                                  ,'iva' => $iva
                                                  ,'total' => $total
                                                  ,'id_empresa' => $id_empresa
                                                  ,'idfactura' => $idfactura*/

                                            );



                }
              



                

        }


     
       /***** recopilacion de datos *****/


      // $dte_array = $result['dte'];
       // print_r($dte_array);  exit;
       // echo "a";
       /* foreach ($dte_array as $key => $value) {
            print_r($key);
            # code...
        }*/
      //  var_dump($dte_array); 
       // $rut_empresa = $dte_array->Datos_Emisor->Rut_Empresa;
        //echo $rut_empresa;
        
        $this->response($response);
        exit;

       //$texto = array('valor' => 'Prueba Texto');
		
        /*  $this->load->model('facturaelectronica');
       $response = $this->facturaelectronica->genera_documento_electronico($result['dte']);

       

        if($response === FALSE)
        {
        	$response['status'] = 'failed';
           // $this->response(array('status' => 'failed'));
        }
         
        else//
        {
        	$response['status'] = 'success';
          //  $this->response(array('status' => 'success'));
        }*/
         //$this->response($response);
    }


 /* public function dte_put()
    {
        // Users from a data store e.g. database
        //print_r($_POST); 
       $result =  array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email')
        );



       $texto = array('valor' => $result['name']." -- ". $result['email']);
		
          $this->load->model('facturaelectronica');
         $this->facturaelectronica->prueba_api($texto);


        if($result === FALSE)
        {
            $this->response(array('status' => 'failed'));
        }
         
        else
        {
            $this->response(array('status' => 'success'));
        }
    }*/

}

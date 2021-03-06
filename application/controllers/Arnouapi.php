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



public function folio_post()
{

        //print_r($_POST); 
           $result =  array(
            'folio' => $this->input->post('folio'),
            );

            $this->load->model('facturaelectronica');
            $this->facturaelectronica->guarda_json_api($result['folio'],'FOLIO'); 


            //var_dump($result['folio']);

            $dte_array = json_decode($result['folio']);
            $error = false;
            //echo "paso 1";
           // var_dump($dte_array);
           if(!isset($dte_array->Datos_Solicitante->Codigo_Empresa)){
                $response = array(
                     'result' => 'Error - No se encuentra código Empresa',
                      'code' => '101',
                      'status' => 'failure'

                );   

                $error = true;         
           }


            if(!isset($dte_array->Datos_Solicitante->Rut_Empresa)){
                $response = array(
                     'result' => 'Error - No se encuentra Rut Empresa',
                      'code' => '102',
                      'status' => 'failure'

                );   

                $error = true;         
             }


             //var_dump($dte_array); exit;
                if(!isset($dte_array->TipoFolio)){
                $response = array(
                     'result' => 'Error - No se indica tipo de folio a consultar',
                      'code' => '103',
                      'status' => 'failure'

                );   

                $error = true;         
             }


            if(!$error){


               $cod_empresa = $dte_array->Datos_Solicitante->Codigo_Empresa;
               $rut_empresa = $dte_array->Datos_Solicitante->Rut_Empresa;
               $tipo_caf = $dte_array->TipoFolio;

               
                $valida_empresa = $this->facturaelectronica->valida_empresa($rut_empresa,$cod_empresa);
                if(!$valida_empresa){

                $response = array(
                                             'result' => 'Error al Validar Empresa',
                                              'code' => '104',
                                              'status' => 'failure'

                                        );


                }else{

                            $id_empresa = $valida_empresa;
                           $folio = $this->facturaelectronica->folio_documento_electronico($tipo_caf,$id_empresa);

                           if($folio == 0){
                                        $response = array(
                                             'result' => 'No existen folios disponibles',
                                              'code' => '105',
                                              'status' => 'failure'

                                        );

                           }else{

                                                        $response = array(

                                                                      'folio' => $folio,
                                                                      'result' => 'Folio consultado correctamente',
                                                                      'status' => 'success',
                                                                      'code' => 100,
                                                                     
                                                                );                                                       
                           }

                            
                }


            }

     $this->response($response);
        exit;
}


public function dte_post()
    {
        // Users from a data store e.g. database
      //  print_r($_POST); 
       $result =  array(
            'dte' => $this->input->post('dte'),
        );

       $envio = implode(",",$_POST);
       //echo $result['dte'];

       $this->load->model('facturaelectronica');
       $this->facturaelectronica->guarda_json_api($result['dte']);    
       $this->facturaelectronica->guarda_json_api_2($envio);    
       // http://jsonviewer.stack.hu/    -- pagina para ver formato
       $dte_array = json_decode($result['dte']);

      //var_dump($dte_array); 

       $error = false;
       if(!isset($dte_array->Datos_Emisor->Codigo_Empresa)){
            $response = array(
                 'result' => 'Error - No se encuentra código Empresa',
                  'code' => '101',
                  'status' => 'failure'

            );   

            $error = true;         
       }


       if(!isset($dte_array->Datos_Emisor->Rut_Empresa)){
          //var_dump($dte_array->Datos_Emisor->Rut_Empresa);
            $response = array(
                 'result' => 'Error - No se encuentra Rut Empresa',
                  'code' => '102',
                  'status' => 'failure'

            );   

            $error = true;         
       }



       if(!$error){


               $cod_empresa = $dte_array->Datos_Emisor->Codigo_Empresa;
               $rut_empresa = $dte_array->Datos_Emisor->Rut_Empresa;

               
                $valida_empresa = $this->facturaelectronica->valida_empresa($rut_empresa,$cod_empresa);
               // $valida_empresa = false;

                if(!$valida_empresa){

                $response = array(
                                             'result' => 'Error al Validar Empresa',
                                              'code' => '103',
                                              'status' => 'failure'

                                        );


                }else{


                        $id_empresa = $valida_empresa;

                        $empresa = $this->facturaelectronica->get_empresa($id_empresa);
                        //print_r($empresa);

                        if(!isset($dte_array->Datos_Factura->Iddoc->TipoDTE)){
                            $response = array(
                                 'result' => 'Error - No se encuentra Tipo DTE',
                                  'code' => '104',
                                  'status' => 'failure'

                            );   

                            $error = true;         
                        }else{



                            $tipo_caf = $dte_array->Datos_Factura->Iddoc->TipoDTE;
                            $folio = $dte_array->Datos_Factura->Iddoc->Folio;
                           // echo $tipo_caf."  ".$id_empresa;
                           // print_r($this->facturaelectronica->folio_documento_electronico($tipo_caf,$id_empresa)); exit;
                            $result_folio = $this->facturaelectronica->valida_folio_documento_electronico($tipo_caf,$id_empresa,$folio);  // ir a buscar folio

                            if($result_folio == 2){
                                            $response = array(
                                                 'result' => 'Error al crear documento.  Folio ya ocupado',
                                                 'code' => '105',
                                                  'status' => 'failure'

                                            );

                            }else if($result_folio == -1){

                                            $response = array(
                                                 'result' => 'Error al crear documento.  Folio no se encuentra disponible',
                                                 'code' => '106',
                                                  'status' => 'failure'

                                            );
                            }else{

                                        if(!isset($dte_array->Datos_Factura->Iddoc->Fecha_Emision)){
                                            $result_dte = 'Error - No se encuentra Fecha Emision';
                                            $cod_error = 107; 
                                            $error = true;         
                                        }

                                        if(!isset($dte_array->Datos_Factura->Receptor->Rut_Receptor)){
                                            $result_dte = 'Error - No se encuentra Rut Receptor';
                                            $cod_error = 108; 
                                            $error = true;         
                                        }

                                        if(!isset($dte_array->Datos_Factura->Receptor->Razon_Social)){
                                            $result_dte = 'Error - No se encuentra Razón Social Receptor';
                                            $cod_error = 109; 
                                            $error = true;         
                                        }

                                        if(!isset($dte_array->Datos_Factura->Receptor->Giro)){
                                            $result_dte = 'Error - No se encuentra Giro Receptor';
                                            $cod_error = 110; 
                                            $error = true;         
                                        }

                                        if(!isset($dte_array->Datos_Factura->Receptor->Direccion)){
                                            $result_dte = 'Error - No se encuentra Dirección Receptor';
                                            $cod_error = 111; 
                                            $error = true;         
                                        }

                                        if(!isset($dte_array->Datos_Factura->Receptor->Comuna)){
                                            $result_dte = 'Error - No se encuentra Comuna Receptor';
                                            $cod_error = 112; 
                                            $error = true;         
                                        }

                                        if(!isset($dte_array->Datos_Factura->Receptor->Ciudad)){
                                            $result_dte = 'Error - No se encuentra Ciudad Receptor';
                                            $cod_error = 113; 
                                            $error = true;         
                                        }

                                        if(!isset($dte_array->Datos_Factura->Totales->MntNeto)){
                                            $result_dte = 'Error - No se encuentra Monto Neto del documento';
                                            $cod_error = 114; 
                                            $error = true;         
                                        }

                                        if(!isset($dte_array->Datos_Factura->Totales->Iva)){
                                            $result_dte = 'Error - No se encuentra Iva del documento';
                                            $cod_error = 115; 
                                            $error = true;         
                                        }

                                        if(!isset($dte_array->Datos_Factura->Totales->MntTotal)){
                                            $result_dte = 'Error - No se encuentra Monto Total del documento';
                                            $cod_error = 116; 
                                            $error = true;         
                                        }



                                        if($error){

                                            $response = array(
                                                 'result' => $result_dte,
                                                 'code' => $cod_error,
                                                  'status' => 'failure'

                                            );                                        
                                        }else{


                                                $fecha_emision = $dte_array->Datos_Factura->Iddoc->Fecha_Emision;
                                                $vendedor = isset($dte_array->Datos_Factura->Iddoc->Vendedor) ? isset($dte_array->Datos_Factura->Iddoc->Vendedor) : '';
                                                $cond_pago = isset($dte_array->Datos_Factura->Iddoc->Condicion_Pago) ? $dte_array->Datos_Factura->Iddoc->Condicion_Pago : '';

                                                $rut_cliente = $dte_array->Datos_Factura->Receptor->Rut_Receptor;
                                                $raz_soc_cliente = $dte_array->Datos_Factura->Receptor->Razon_Social;
                                                $giro_cliente = $dte_array->Datos_Factura->Receptor->Giro;
                                                $cod_act_econ_cli = isset($dte_array->Datos_Factura->Receptor->Cod_Actividad_Economica) ? $dte_array->Datos_Factura->Receptor->Cod_Actividad_Economica : '';
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

                                                 $i = 0;
                                                if(isset($dte_array->Datos_Factura->Detalle)){
                                                  foreach ($dte_array->Datos_Factura->Detalle as $detalle) {
                                                     // print_r($detalle);
                                                     $fila = $i + 1;
 
                                                      if(!isset($detalle->Codigo)){
                                                          $result_dte = 'Error - No se encuentra Código de Producto '. $fila;
                                                          $cod_error = 118; 
                                                          $error = true;         
                                                      }else{
                                                        $codigo = $detalle->Codigo;
                                                      }


                                                      if(!isset($detalle->Cantidad)){
                                                          $result_dte = 'Error - No se encuentra Cantidad de Producto '. $fila;
                                                          $cod_error = 119; 
                                                          $error = true;         
                                                      }else{
                                                          $cantidad = $detalle->Cantidad;
                                                      }

                                                      if(!isset($detalle->Nombre)){
                                                          $result_dte = 'Error - No se encuentra Nombre de Producto '. $fila;
                                                          $cod_error = 120; 
                                                          $error = true;         
                                                      }else{
                                                          $nombre = $detalle->Nombre;
                                                      }

                                                      if(!isset($detalle->Unidad)){
                                                          $result_dte = 'Error - No se encuentra Tipo de Unidad de Producto '. $fila;
                                                          $cod_error = 121; 
                                                          $error = true;         
                                                      }else{
                                                          $unidad = $detalle->Unidad;
                                                      }

                                                      if(!isset($detalle->Precio_Unitario)){
                                                          $result_dte = 'Error - No se encuentra precio unitario de Producto '. $fila;
                                                          $cod_error = 122; 
                                                          $error = true;         
                                                      }else{
                                                           $precioUnitario = $detalle->Precio_Unitario;
                                                      }


                                                      if(!isset($detalle->Neto)){
                                                          $result_dte = 'Error - No se encuentra valor neto de Producto '. $fila;
                                                          $cod_error = 123; 
                                                          $error = true;         
                                                      }else{
                                                          $neto = $detalle->Neto;
                                                      }


                                                      if(!isset($detalle->Iva)){
                                                          $result_dte = 'Error - No se encuentra Iva de Producto '. $fila;
                                                          $cod_error = 124; 
                                                          $error = true;         
                                                      }else{
                                                        $iva = $detalle->Iva;
                                                      }


                                                      if(!isset($detalle->Total)){
                                                          $result_dte = 'Error - No se encuentra Total de Producto '. $fila;
                                                          $cod_error = 125; 
                                                          $error = true;         
                                                      }else{
                                                        $total = $detalle->Total;
                                                      }


                                                      if($error){
                                                        break;

                                                      }else{
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

                                                      }
                                                                         
                                                      $i++;



                                                  }

                                                }else{

                                                     $result_dte = 'Error - No se encuentra Detalle de Productos';
                                                      $cod_error = 117; 
                                                      $error = true;  

                                                }
                                               


                                                if(!$error){
                                                   if(isset($dte_array->Datos_Factura->Referencia)){
                                                      foreach ($dte_array->Datos_Factura->Referencia as $referencia) {
                                                         // print_r($detalle);

                                                        if(!isset($referencia->NroLinRef)){
                                                                $result_dte = 'Error - No se encuentra Linea Referencia';
                                                                $cod_error = 126; 
                                                                $error = true;         
                                                        }else{
                                                              $nrolinref = $referencia->NroLinRef;
                                                        }

                                                        if(!isset($referencia->TpoDocRef)){
                                                                $result_dte = 'Error - No se encuentra Tipo Documento Referencia';
                                                                $cod_error = 127; 
                                                                $error = true;         
                                                        }else{
                                                              $tpodocref = $referencia->TpoDocRef;
                                                        }


                                                        if(!isset($referencia->FolioRef)){
                                                                $result_dte = 'Error - No se encuentra Folio Referencia';
                                                                $cod_error = 128; 
                                                                $error = true;         
                                                        }else{
                                                              $folioref = $referencia->FolioRef;
                                                        }


                                                         
                                                          
                                                          
                                                          if($error){
                                                              break;

                                                          }else{


                                                              $detalle_referencias = array(
                                                                                              'idfactura' => $idfactura,
                                                                                              'nrolinref' => $nrolinref,
                                                                                              'tpodocref' => $tpodocref,
                                                                                              'folioref' => $folioref
                                                                                          );

                                                              $this->db->insert('referencias', $detalle_referencias);
                                                          }

                                                      }   
                                                    }                                          
                                                }
                                               



                                                if($error){

                                                    $response = array(
                                                         'result' => $result_dte,
                                                         'code' => $cod_error,
                                                          'status' => 'failure'

                                                    );                                        
                                                }else{




                                                                                                       
                                                    // generar factura

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



                                                      }

                                                    $url_pdf = $this->facturaelectronica->generaFePDF($idfactura,'id',$id_empresa);
                                                    $url_xml = URL_DESCARGA_DTE . $dte['path'] . $dte['nombre_dte'];


                                                    $response = array(
                                                                        // 'url_pdf' => 'http://www.arnou.cl/Infosys_web/core/facturacion_electronica/pdf/202011/dte_96516320-4_T33F35349.pdf',
                                                                          //'url_xml' => 'http://www.arnou.cl/Infosys_web/core/facturacion_electronica/dte/202011/18363_52_1492_SII_100205.xml',
                                                                          'url_pdf' => $url_pdf,
                                                                          'url_xml' => $url_xml,
                                                                          'result' => 'Documento creado correctamente',
                                                                          'status' => 'success',
                                                                          'code' => 100,
                                                                          'tipo_caf' => $tipo_caf
                                                                         /* ,'folio' => $folio
                                                                          ,'iva' => $iva
                                                                          ,'total' => $total
                                                                          ,'id_empresa' => $id_empresa
                                                                          ,'idfactura' => $idfactura*/

                                                                    );                                                      

                                                } // fin else

                                        }




                            }



                        



                        }
                          



                            

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

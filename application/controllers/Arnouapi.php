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


	public function dte_get()
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


public function dte_post()
    {
        // Users from a data store e.g. database
      //  print_r($_POST); 
       $result =  array(
            'dte' => $this->input->post('dte'),
        );

       $dte_array = json_decode($result['dte']);
      // print_r($result['dte']);

       $texto = array('valor' => 'Prueba Texto');
		
          $this->load->model('facturaelectronica');
         $this->facturaelectronica->prueba_api($texto);

        $response['url_pdf'] = 'http://www.arnou.cl/Infosys_web/core/facturacion_electronica/pdf/202011/dte_96516320-4_T33F35349.pdf';
        $response['url_xml'] = 'http://www.arnou.cl/Infosys_web/core/facturacion_electronica/dte/202011/18363_52_1492_SII_100205.xml';


        if($result === FALSE)
        {
        	$response['status'] = 'failed';
           // $this->response(array('status' => 'failed'));
        }
         
        else
        {
        	$response['status'] = 'success';
          //  $this->response(array('status' => 'success'));
        }
         $this->response($response);
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

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Movimientos extends CI_Controller {

	
	function __construct(){
	  parent::__construct();
	  $this->load->library('ion_auth');
      $this->load->library('form_validation');
      $this->load->helper('format');
      $this->load->model('configuracion');
      if (!$this->ion_auth->logged_in()){
      	 $this->session->set_userdata('uri_array',$this->uri->rsegment_array());
         redirect('auth/login', 'refresh');
      }else{
      		if(!$this->session->userdata('menu_list')){
      			$this->session->set_userdata('menu_list',json_decode($this->ion_auth_model->get_menu($this->session->userdata('user_id'))));
      		}

      		/*if($this->router->fetch_class()."/".$this->router->fetch_method() != "main/dashboard"){
      			redirect('main/dashboard');	      			
      		}*/
      }
      
   }


	public function index()
	{

		$this->load->model('ion_auth_model');
		redirect('main/dashboard');	
	}

	

	public function ventas(){
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			
			
			$content = array(
						'menu' => 'Movimientos',
						'title' => 'Movimientos',
						'subtitle' => 'Documentos');

			$this->load->model('facturaelectronica');

			$ventas = $this->facturaelectronica->get_facturas();

			$vars['content_menu'] = $content;	
			$vars['ventas'] = $ventas;				
			$vars['content_view'] = 'movimientos/ventas';
			$vars['formValidation'] = true;
			$vars['datatable'] = true;
			$vars['gritter'] = true;

			$template = "template";
			

			

			$this->load->view($template,$vars);	

		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}	


	}	

}

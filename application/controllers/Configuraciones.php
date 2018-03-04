<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuraciones extends CI_Controller {

	
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

      		if($this->router->fetch_class()."/".$this->router->fetch_method() != "main/dashboard" && !$this->session->userdata('comunidadid') && ($this->session->userdata('level') == 1)){
      			redirect('main/dashboard');	      			
      		}
      }
      
   }


	public function index()
	{

		$this->load->model('ion_auth_model');
		redirect('main/dashboard');	
	}

	

	public function hab_descto(){
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			
			$resultid = $this->session->flashdata('haber_descuento_result');
			if($resultid == 1){
				$vars['message'] = "Haber/Descuento Agregado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';		
				$vars['mantencion_personal'] = 'active';				
				$vars['leyes_sociales'] = '';		
				$vars['apv'] = '';		
				$vars['salud'] = '';		
				$vars['otros'] = '';	
			}

			$haberes_descuentos = $this->configuracion->get_haberes_descuentos(); 


			$content = array(
						'menu' => 'Configuraciones',
						'title' => 'Configuraciones',
						'subtitle' => 'Creaci&oacute;n Haberes / Descuentos');

			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'configuraciones/hab_descto';
			$vars['datatable'] = true;
			$vars['mask'] = true;
			$vars['gritter'] = true;

			$vars['haberes_descuentos'] = $haberes_descuentos;
			
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


public function add_haber_descuento(){
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			
			$resultid = $this->session->flashdata('personal_result');
			if($resultid == 1){
				$vars['message'] = "Trabajador Agregado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';		
				$vars['mantencion_personal'] = 'active';				
				$vars['leyes_sociales'] = '';		
				$vars['apv'] = '';		
				$vars['salud'] = '';		
				$vars['otros'] = '';	
			}

			$content = array(
						'menu' => 'Configuraciones',
						'title' => 'Configuraciones',
						'subtitle' => 'Creaci&oacute;n Haberes / Descuentos');

			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'configuraciones/add_haber_descuento';
			$vars['formValidation'] = true;
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


public function submit_haber_descuento(){
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$tipo = $this->input->post('tipo');
			$codigo = $this->input->post('codigo');
			$descripcion = $this->input->post('descripcion');
			$tipocalculo = $this->input->post('tipocalculo');
			$formacalculo = $this->input->post('formacalculo');

			$datos = array();
			$datos['tipo'] = $tipo;
			$datos['codigo'] = $codigo;
			$datos['nombre'] = $descripcion;
			$datos['tipocalculo'] = $tipocalculo;
			$datos['formacalculo'] = $formacalculo;


			$datos['imponible'] = $this->input->post('imponible') == '' ? 0 : 1;
			$datos['reajustable'] = $this->input->post('reajustable') == '' ? 0 : 1;
			$datos['provision'] = $this->input->post('provision') == '' ? 0 : 1;
			$datos['embargable'] = $this->input->post('embargable') == '' ? 0 : 1;
			$datos['gratifica'] = $this->input->post('gratificacion') == '' ? 0 : 1;
			$datos['insoluto'] = $this->input->post('insoluto') == '' ? 0 : 1;
			$datos['retjudicial'] = $this->input->post('ret_judicial') == '' ? 0 : 1;
			$datos['tributable'] = $this->input->post('tributable') == '' ? 0 : 1;
			$datos['jornada'] = $this->input->post('jornada') == '' ? 0 : 1;
			$datos['finiquito'] = $this->input->post('finiquito') == '' ? 0 : 1;
			$datos['contable'] = $this->input->post('contable') == '' ? 0 : 1;
			$datos['sobregiro'] = $this->input->post('sobregiro') == '' ? 0 : 1;
			$datos['liqminimo'] = $this->input->post('liqminimo') == '' ? 0 : 1;
			$datos['editable'] = 1;
			$datos['visible'] = 1;
			$datos['valido'] = 1;


			$haberes_descuentos = $this->configuracion->add_haberes_descuentos($datos); 

			$this->session->set_flashdata('haber_descuento_result', 1);
			redirect('configuraciones/hab_descto');	


		}else{
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}		


	}		
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	
	function __construct(){
	  parent::__construct();
	  $this->load->library('ion_auth');
      $this->load->library('form_validation');
      $this->load->helper('format');
      
      if (!$this->ion_auth->logged_in()){
      	 $this->session->set_userdata('uri_array',$this->uri->rsegment_array());
         redirect('auth/login', 'refresh');
      }else{
      		if(!$this->session->userdata('menu_list')){
      			$this->session->set_userdata('menu_list',json_decode($this->ion_auth_model->get_menu($this->session->userdata('user_id'))));
      		}
      		if($this->router->fetch_class()."/".$this->router->fetch_method() != "main/dashboard" && !$this->session->userdata('comunidadid') && ($this->session->userdata('level') == 1 || $this->session->userdata('level') == 3)){
				      		redirect('main/dashboard');      			
      		}
      }
      
   }


	public function index()
	{

		$this->load->model('ion_auth_model');
		redirect('main/dashboard');	
	}


	//TODOS TIENEN ACCESO AL DASHBOARD
	public function dashboard($unidad_id = '')
	{

		$this->load->model('ion_auth_model');	
		$this->load->model('admin');


		$content = array(
					'menu' => 'Dashboard',
					'title' => 'Dashboard',
					'subtitle' => 'Panel de Control');


		
		$vars['content_menu'] = $content;				
		

		$vars['content_view'] = 'dashboard';
		$template = "template";

		if($this->session->userdata('level') == 2){
			// SI YA SELECCIONO COMUNIDAD, NO ES NECESARIO ELEGIR NUEVAMENTE
			$unidad_id = $unidad_id == '' && $this->session->userdata('empresaid') ? $this->session->userdata('empresaid') : $unidad_id;

			$empresas_asignadas = $unidad_id != '' ? $this->admin->empresas_asignadas($this->session->userdata('user_id'),$this->session->userdata('level'),$unidad_id) : $this->admin->empresas_asignadas($this->session->userdata('user_id'),$this->session->userdata('level'));

			$num_empresas = count($this->admin->empresas_asignadas($this->session->userdata('user_id'),$this->session->userdata('level')));

			if(count($empresas_asignadas) > 1){ // EN CASO DE TENER MÁS DE UNA COMUNIDAD LO ENVÍA A LA PÁGINA DE SELECCIÓN
				$content = array(
							'menu' => 'Selecci&oacute;n Empresa',
							'title' => 'Empresas',
							'subtitle' => 'Selecci&oacute;n de Empresa');

				$vars['content_menu'] = $content;
				$vars['empresas'] = $empresas_asignadas;
				$vars['content_view'] = 'admins/asigna_empresa';
				$template = "template_lock";
				//$this->load->view('template_lock',$vars);	
			}else if(count($empresas_asignadas) == 1){ 			
				$this->session->set_userdata('empresaid',$empresas_asignadas->id_empresa);
				$this->session->set_userdata('empresanombre',$empresas_asignadas->nombre);


			}else{
				redirect('auth/logout');	
			}

		}


		

		/*** SI YA SE HABIA SELECCIONADO UN MODULO, REDIRECCIONA ****/
  		/*if(count($this->session->userdata('uri_array')) > 0){
  			$uri_array = $this->session->userdata('uri_array');
  			$url = $uri_array[1].'/'.$uri_array[2];
  			for($i = 3;$i <= count($uri_array); $i++){
  				$url .= "/".$uri_array[$i];
  			}
  			$this->session->unset_userdata('uri_array');
  			redirect($url);

  		}		*/		
		$this->load->view($template,$vars);			
		

	}



	public function destroy_data_session(){
		$this->session->unset_userdata('empresaid');
		$this->session->unset_userdata('empresanombre');
		//$this->session->unset_userdata('preloader');
		redirect('main/dashboard');
	}

		
}

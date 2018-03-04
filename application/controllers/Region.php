<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estudios extends CI_Controller {

	
	function __construct(){
	  parent::__construct();
	  $this->load->library('ion_auth');
      $this->load->library('form_validation');
      $this->load->helper('format');
      $this->load->model('admin');
      if (!$this->ion_auth->logged_in()){
      	 $this->session->set_userdata('uri_array',$this->uri->rsegment_array());
         redirect('auth/login', 'refresh');
      }else{
      		if(!$this->session->userdata('menu_list')){
      			$this->session->set_userdata('menu_list',json_decode($this->ion_auth_model->get_menu($this->session->userdata('user_id'))));
      		}

      		if($this->router->fetch_class()."/".$this->router->fetch_method() != "main/dashboard" && !$this->session->userdata('comunidadid') && ($this->session->userdata('level') == 2)){
      			redirect('main/dashboard');	      			
      		}
      }
      
   }


	public function index()
	{

		$this->load->model('ion_auth_model');
		redirect('main/dashboard');	
	}

	
	public function estudio()
	{	

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('estudio_result');
			if($resultid == 1){
				$vars['message'] = "Estudios Agregada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al agregar Estudios. Estudios ya existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Estudios Editada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 4){
				$vars['message'] = "Error al eliminar Estudios. Estudios no existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';				
			}elseif($resultid == 5){
				$vars['message'] = "Estudios Eliminada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';								
			}


			

			$estudio = $this->admin->get_estudios();

			$content = array(
						'menu' => 'Remuneraciones',
						'title' => 'Remuneraciones',
						'subtitle' => 'Administraci&oacute;n de Estudios');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'admins/estudio';
			$vars['estudios'] = $estudio;
			$vars['dataTables'] = true;
			
			
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


	public function add_estudios($idestudios = 0)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('remuneracion');
			$estudios = $this->remuneracion->get_estudios($idestudios);

			$content = array(
						'menu' => 'Remuneraciones',
						'title' => 'Remuneraciones',
						'subtitle' => 'Administraci&oacute;n de Estudios');

			$datos_form = array(
							'idestudios' => count($estudios) == 0 ? 0 : $estudios->id,
							'nombre' => count($estudios) == 0 ? '' : $estudios->nombre,
							'idempresa' => count($estudios) == 0 ? '' : $estudios->idempresa,
							'codigo' => count($estudios) == 0 ? 0 : $estudios->codigo
							);
			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'remuneraciones/add_estudios';
			$vars['titulo'] = $idestudios == '' ? "Agregar Estudios" : "Editar Estudios";
			$vars['datos_form'] = $datos_form;
			$vars['formValidation'] = true;
			$vars['mask'] = true;
			$vars['icheck'] = true;		
			
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



	public function submit_estudios(){
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$nombre = $this->input->post('nombre');	
			$idempresa = $this->input->post('idempresa');	
			$codigo = $this->input->post('codigo');	
			$idestudios = $this->input->post('idestudios');

			$array_datos = array(
								'nombre' => $nombre,
								'idempresa' => $porc,
								'codigo' => $exregimen,
								'idestudios' => $idestudios);


			$result = $this->admin->add_estudios($array_datos);

			if($result == -1){
				$this->session->set_flashdata('estudio_result', 2);	
			}else{
				if($idestudios == 0){
					$this->session->set_flashdata('estudio_result', 1);	
				}else{
					$this->session->set_flashdata('estudio_result', 3);	
				}
			}

			
			redirect('Estudios/estudio');	


		}else{
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}		


	}


	public function delete_estudios($idestudios = 0)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$result = $this->admin->delete_estudio($idestudios);
			var_dump($result);
			if($result == -1){
				$this->session->set_flashdata('estudio_result', 4);	
			}else{
				$this->session->set_flashdata('estudio_result', 5);	
				
			}

			redirect('Estudios/estudio');	

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

	public function get_estudio($idestudios = null){


		$datos = $this->admin->get_estudios($idestudios);

		//print_r($datos);
		echo json_encode($datos);
	}

}
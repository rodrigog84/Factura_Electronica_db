<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins extends CI_Controller {

	
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

      		/*if($this->router->fetch_class()."/".$this->router->fetch_method() != "main/dashboard" && !$this->session->userdata('comunidadid')){
      			redirect('main/dashboard');	      			
      		}*/
      }
      
   }


	public function index()
	{

		$this->load->model('ion_auth_model');
		redirect('main/dashboard');	
	}


	public function get_comunas($idregion){

		$this->load->model('admin');
		$comunas = $this->admin->get_comunas_by_region($idregion);
		//$arrayComunas = array();
		//$arrayComunas[''] = "Seleccione Comuna";
		//foreach ($comunas as $comuna) {
		//	$arrayComunas[$comuna->idcomuna] = $comuna->nombre;
		//}
		echo json_encode($comunas);
		//echo form_dropdown('comuna',$arrayComunas ,'',"class='form-control' id='comuna'"); 

	}	

	public function submit_personal_afp(){
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$array_elem = $this->input->post(NULL,true);
			$array_trabajadores = array();
			foreach($array_elem as $elem => $value_elem){
				$arr_el = explode("_",$elem);
				if($arr_el[0] == 'afp' || $arr_el[0] == 'cotadic'  || $arr_el[0] == 'tipcotvol'  || $arr_el[0] == 'cotvol'){
					$array_trabajadores[$arr_el[1]][$arr_el[0]] = $value_elem;
				}
			}

			$this->load->model('remuneracion');
			$this->remuneracion->update_personal_leyes_sociales($array_trabajadores);

			$this->session->set_flashdata('personal_result', 3);
			redirect('remuneraciones/personal');	


		}else{
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}		


	}	




	public function afp()
	{	

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('afp_result');
			if($resultid == 1){
				$vars['message'] = "AFP Agregada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al agregar AFP. AFP ya existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "AFP Editada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 4){
				$vars['message'] = "Error al eliminar AFP. AFP no existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';				
			}elseif($resultid == 5){
				$vars['message'] = "AFP Eliminada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';								
			}


			

			$afps = $this->admin->get_afp();

			$content = array(
						'menu' => 'Remuneraciones',
						'title' => 'Remuneraciones',
						'subtitle' => 'Administraci&oacute;n de Afp');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'admins/afp';
			$vars['afps'] = $afps;
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


    public function isapre()
	{	

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('isapre_result');
			if($resultid == 1){
				$vars['message'] = "Isapre Agregada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al agregar Isapre. Isapre ya existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Isapre Editada correctamente";
				$vars['classmessage'] = 'success';
				$vars['ico n'] = 'fa-check';				
			}elseif($resultid == 4){
				$vars['message'] = "Error al eliminar Isapre. Isapre no existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';				
			}elseif($resultid == 5){
				$vars['message'] = "Isapre Eliminada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';								
			}


			

			$isapres = $this->admin->get_isapre();

			$content = array(
						'menu' => 'Remuneraciones',
						'title' => 'Remuneraciones',
						'subtitle' => 'Administraci&oacute;n de Isapre');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'admins/isapre';
			$vars['isapres'] = $isapres;
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


	public function add_afp($idafp = 0)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('remuneracion');
			$afp = $this->remuneracion->get_afp($idafp);

			$content = array(
						'menu' => 'Remuneraciones',
						'title' => 'Remuneraciones',
						'subtitle' => 'Administraci&oacute;n de Afp');

			$datos_form = array(
							'idafp' => count($afp) == 0 ? 0 : $afp->id,
							'nombre' => count($afp) == 0 ? '' : $afp->nombre,
							'porc' => count($afp) == 0 ? '' : $afp->porc,
							'exregimen' => count($afp) == 0 ? 0 : $afp->exregimen
							);
			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'remuneraciones/add_afp';
			$vars['titulo'] = $idafp == '' ? "Agregar Afp" : "Editar Afp";
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



	public function submit_afp(){
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$nombre = $this->input->post('nombre');	
			$porc = $this->input->post('porc');	
			$exregimen = $this->input->post('exregimen') == 'on' ? 1 : 0;	
			$idafp = $this->input->post('idafp');

			$array_datos = array(
								'nombre' => $nombre,
								'porc' => $porc,
								'exregimen' => $exregimen,
								'idafp' => $idafp);


			$result = $this->admin->add_afp($array_datos);

			if($result == -1){
				$this->session->set_flashdata('afp_result', 2);	
			}else{
				if($idafp == 0){
					$this->session->set_flashdata('afp_result', 1);	
				}else{
					$this->session->set_flashdata('afp_result', 3);	
				}
			}

			
			redirect('admins/afp');	


		}else{
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}		


	}

    public function submit_isapre(){
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$nombre = $this->input->post('nombre');	
			$codprevired = $this->input->post('codprevired');	
			$idisapre = $this->input->post('idisapre');

			$array_datos = array(
								'nombre' => $nombre,
								'codprevired' => $codprevired,
								'idisapre' => $idisapre);


			$result = $this->admin->add_isapre($array_datos);

			if($result == -1){
				$this->session->set_flashdata('isapre_result', 2);	
			}else{
				if($idisapre == 0){
					$this->session->set_flashdata('isapre_result', 1);	
				}else{
					$this->session->set_flashdata('isapre_result', 3);	
				}
			}

			
			redirect('admins/isapre');	


		}else{
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}		


	}

	public function delete_afp($idafp = 0)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$result = $this->admin->delete_afp($idafp);
			var_dump($result);
			if($result == -1){
				$this->session->set_flashdata('afp_result', 4);	
			}else{
				$this->session->set_flashdata('afp_result', 5);	
				
			}

			redirect('admins/afp');	

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

	public function get_afp($idafp = null){


		$datos = $this->admin->get_afp($idafp);

		//print_r($datos);
		echo json_encode($datos);
	}



	public function impto_unico()
	{
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('impuesto_result');
			if($resultid == 1){
				$vars['message'] = "Impuesto &Uacute;nico actualizado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';		
			}

			$tabla_impuesto = $this->admin->get_tabla_impuesto(); 

			$content = array(
						'menu' => 'Remuneraciones',
						'title' => 'Remuneraciones',
						'subtitle' => 'Impuesto &Uacute;nico');

			$vars['formValidation'] = true;
			$vars['mask'] = true;			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'admins/impto_unico';
			$vars['tabla_impuesto'] = $tabla_impuesto;
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

	public function submit_impuesto_unico()
			{


				if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

					$array_datos = $this->input->post(NULL,true);
					$array_impuesto = array();
					foreach ($array_datos as $key => $dato) {
						$array_elem = explode("_",$key);
						$id_impuesto = $array_elem[1];
						$tipo_valor = $array_elem[0];
						$array_impuesto[$id_impuesto][$tipo_valor] = $dato;
					}

					$this->admin->edit_tabla_impuesto($array_impuesto);
					$this->session->set_flashdata('impuesto_result', 1);
					redirect('admins/impto_unico');				

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


	public function asig_familiar()
	{
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('asig_familiar_result');
			if($resultid == 1){
				$vars['message'] = "Asignaci&oacute;n Familiar actualizada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';		
			}

			$tabla_asig_familiar = $this->admin->get_tabla_asig_familiar(); 

			$content = array(
						'menu' => 'Remuneraciones',
						'title' => 'Remuneraciones',
						'subtitle' => 'Asignaci&oacute;n Familiar');


			$vars['formValidation'] = true;
			$vars['mask'] = true;			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'admins/asig_familiar';
			$vars['tabla_asig_familiar'] = $tabla_asig_familiar;
			
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


			public function submit_asignacion_familiar()
			{


				if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

					$array_datos = $this->input->post(NULL,true);
					$array_asig_familiar = array();
					foreach ($array_datos as $key => $dato) {
						$array_elem = explode("_",$key);
						$id_asig_familiar = $array_elem[1];
						$tipo_valor = $array_elem[0];
						$array_asig_familiar[$id_asig_familiar][$tipo_valor] = $dato;
					}

					$this->admin->edit_tabla_asig_familiar($array_asig_familiar);
					$this->session->set_flashdata('asig_familiar_result', 1);
					redirect('admins/asig_familiar');				

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



	public function feriados()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$resultid = $this->session->flashdata('feriado_result');
			if($resultid == 1){
				$vars['message'] = "Feriado Agregado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al agregar Feriado. Feriado ya existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Feriado Editado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 4){
				$vars['message'] = "Error al eliminar Feriado. Feriado no existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';				
			}elseif($resultid == 5){
				$vars['message'] = "Feriado Eliminado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';								
			}


			//$this->load->model('remuneracion');

			$feriados = $this->admin->get_feriado();


			$content = array(
						'menu' => 'Remuneraciones',
						'title' => 'Remuneraciones',
						'subtitle' => 'Administraci&oacute;n de Feriados');
			
			
			$vars['content_menu'] = $content;			
			$vars['datetimepicker'] = true;	
			$vars['content_view'] = 'admins/feriados';
			$vars['feriados'] = $feriados;
			$vars['dataTables'] = true;
			
			
			$template = "template";
			

			$this->load->view($template,$vars);	

		}else{
			$content = array(
				'menu' => 'Error 403',
				'title' => 'Error 403',
				'subtitle' => '403 error'
			);


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}


	public function submit_feriado(){
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$fecha = $this->input->post('fecha');	
			$idferiado = $this->input->post('idferiado');

			$array_datos = array(
								'fecha' => formato_fecha($fecha,'d/m/Y','Y-m-d'),
								'idferiado' => $idferiado);

			$result = $this->admin->add_feriado($array_datos);

			if($result == -1){
				$this->session->set_flashdata('feriado_result', 2);	
			}else{
				if($idferiado == 0){
					$this->session->set_flashdata('feriado_result', 1);	
				}else{
					$this->session->set_flashdata('feriado_result', 3);	
				}
			}

			
			redirect('admins/feriados');	


		}else{
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}		


	}



	public function delete_feriado($idferiado = 0)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$result = $this->admin->delete_feriado($idferiado);
			if($result == -1){
				$this->session->set_flashdata('feriado_result', 4);	
			}else{
				$this->session->set_flashdata('feriado_result', 5);	
				
			}

			redirect('admins/feriados');	

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


	public function get_feriado($idferiado = null){


		$datos = $this->admin->get_feriado($idferiado);

		//print_r($datos);
		echo json_encode($datos);
	}


	public function parametros($resultid = '')
	{
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('parametros_result');
			if($resultid == 1){
				$vars['message'] = "Parametros Generales actualizados correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}

			$parametros_generales = $this->admin->get_parametros_generales(); 
			$content = array(
						'menu' => 'Remuneraciones',
						'title' => 'Remuneraciones',
						'subtitle' => 'Par&aacutemetros Generales');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'admins/parametros';
			$vars['formValidation'] = true;
			$vars['mask'] = true;
			$vars['gritter'] = true;
			
			$vars['parametros_generales'] = $parametros_generales;
			
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



public function submit_parametros_generales()
	{


		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

       		$parametros = array(
       						'uf' => str_replace(",",".",str_replace(".","",$this->input->post('uf'))),
       						'utm' => str_replace(",",".",str_replace(".","",$this->input->post('utm'))),
       						'sueldominimo' => str_replace(".","",$this->input->post('sueldominimo')),
       						'tasasis' => $this->input->post('tasasis'),
       						'topeimponible' => str_replace(",",".",str_replace(".","",$this->input->post('topeimponible'))),
       						'topeimponibleips' => str_replace(",",".",str_replace(".","",$this->input->post('topeimponibleips'))),
       						'topeimponibleafc' => str_replace(",",".",str_replace(".","",$this->input->post('topeimponibleafc')))       						
			       			);
			$this->admin->edit_parametros_generales($parametros);
			
			$this->session->set_flashdata('parametros_result',1);
			redirect('admins/parametros');				

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


	public function admin_users($resultid = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('add_user_result');

			if($resultid == 1){
				$vars['message'] = "Usuario Agregado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al agregar Usuario. Usuario ya existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Usuario Editado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';		
			}elseif($resultid == 4){
				$vars['message'] = "Error al eliminar Usuario. Usuario no existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';				
			}elseif($resultid == 5){
				$vars['message'] = "Usuario Eliminado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';								
			}elseif($resultid == 6){
				$vars['message'] = "Datos de accesos enviados correctamente a usuarios seleccionados";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';		

			}


			$this->load->model('admin');

			$users = $this->admin->get_users();

			//var_dump($users); exit;

			$content = array(
						'menu' => 'Administraci&oacute;n',
						'title' => 'Administraci&oacute;n',
						'subtitle' => 'Administraci&oacute;n de Usuarios');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'admins/admin_users';
			$vars['users'] = $users;
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



//create a new user
	public function add_user($iduser = 0)
	{
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('admin');
			//$user = $this->admin->get_user_by_id($iduser);
			$user = $this->admin->get_users($iduser);
			$empresas = $this->admin->get_empresas();	

			$idempresa = '';
			$array_empresas = array();
			if(count($user) > 0){
				if(isset($user->level)){
					if($user->level == 2){
						$lista_empresas = $this->admin->empresas_asignadas($user->id,$user->level);
						if(count($lista_empresas) == 1){
							array_push($array_empresas,$lista_empresas->id_empresa);
						}else if(count($lista_empresas) > 1){
							foreach ($lista_empresas as $empresa) {
								array_push($array_empresas,$empresa->id_empresa);
							}
							//$comunidad = $comunidad[0];
							$idempresa = $empresa->id_empresa;
						}


					}
				}

			}


			$perfiles = $this->admin->get_perfiles();
			$content = array(
						'menu' => 'Administraci&oacute;n',
						'title' => 'Administraci&oacute;n',
						'subtitle' => 'Administraci&oacute;n de Usuarios');


			$datos_form = array(
							'iduser' => count($user) == 0 ? 0 : $user->id,
							'nombre' => count($user) == 0 ? '' : $user->first_name,
							'apellido' => count($user) == 0 ? '' : $user->last_name,
							'email' => count($user) == 0 ? '' : $user->email,
							'perfil' => count($user) == 0 ? '' : $user->level,
							'idempresa' => $idempresa,
							);
			
			$vars['content_menu'] = $content;				
			$vars['empresas'] = $empresas;

			$vars['listado_empresas'] = $array_empresas;
			$vars['perfiles'] = $perfiles;
			$vars['content_view'] = 'admins/add_user';
			$vars['titulo'] = $iduser == '' ? "Agregar Usuario" : "Editar Usuario";
			$vars['datos_form'] = $datos_form;
			$vars['formValidation'] = true;
			
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



	public function validate_password_user($data = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$result = $this->ion_auth->hash_password_db($this->session->userdata('user_id'),$this->input->post('password_actual'));

			if(!$result){
				$data['result'] = "error";
				$data['fields']['password_actual'] = "Clave actual es incorrecta";	
			}else{
				$data['result'] = "ok";
			}

			echo json_encode($data);

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


public function submit_clave()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			// agregar guardado de cuenta asociado al monto


			$password = $this->input->post('password_nueva');
			$userid = $this->ion_auth->update_password($this->session->userdata('user_id'), $password); 


			$this->session->set_flashdata('cambio_clave_result',1);
			redirect('admins/cambio_clave');	

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



public function cambio_clave($resultid = '')
	{	
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$resultid = $this->session->flashdata('cambio_clave_result');


			if($resultid == 1){
				$vars['message'] = "Clave actualizada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}

			$content = array(
						'menu' => 'Mi Cuenta',
						'title' => 'Mi Cuenta',
						'subtitle' => 'Cambio de clave');


			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'admins/cambio_clave';
			$vars['formValidation'] = true;
			$vars['gritter'] = true;
			//var_dump($vars); exit;
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

	public function validate_email_user($data = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$email = $this->input->post('email');
			$iduser = $this->input->post('iduser');

			$this->load->model('admin');
			$existe = $this->admin->valida_existe_mail($email,$iduser);
			if($existe){
				$data['result'] = "error";
				$data['fields']['email'] = "Email ya est&aacute; asociado a otro usuario.    Favor contactar con el administrador";	
			}else{
				$data['result'] = "ok";
			}

			echo json_encode($data);

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



public function submit_user()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			// agregar guardado de cuenta asociado al monto


			$nombre = $this->input->post('nombre');
			$apellido = $this->input->post('apellido');
			$email = $this->input->post('email');
			$perfil = $this->input->post('perfil');
			//$idcomunidad = $this->input->post('comunidad');
			//$idpropiedad = $this->input->post('propiedad');
			$password = $this->input->post('password');
			$iduser = $this->input->post('iduser');

			$array_elem = $this->input->post(NULL,true);

			$array_empresas = array();
			foreach($array_elem as $elem => $value_elem){
				$arr_el = explode("-",$elem);
				if($perfil == 2){ // Administrador comunidad (asociar condominios)
					if($arr_el[0] == 'comunidad'){
						if($value_elem != 0){
							array_push($array_empresas, $value_elem);						
						}
					}

				}
			}

			// en caso de crear un usuario asociado a un mail ya existente, se obtiene el id de ese usuario
			$this->load->model('admin');
			$existe_mail = $this->admin->valida_existe_mail_user($email);

			if(!$existe_mail){
				$iduser = $iduser;
				$activa_antiguo = false;
			}else{
				$iduser = $existe_mail->id;
				$activa_antiguo = true;
			}

	
			$additional_data = array(
							'first_name' => $nombre,
							'last_name'  => $apellido,
							'company'    => '',
							'phone'      => '',
						);

			//$this->load->model('admin');
			$userid = $iduser == 0 ? $this->ion_auth->register($email, $password, $email, $additional_data) : $this->ion_auth->update($iduser, $additional_data); // creacion/actualizacion de usuario

			$userid = $iduser == 0 ? $userid : $iduser;

			$result = $this->ion_auth->update_level($userid,$perfil); //actualiza perfil

			//en caso de crear un usuario asociado a un mail ya existente, aparte de activarlo, se debe actualizar password
			if($activa_antiguo){
				$this->ion_auth->update_password($iduser, $password); 
				$this->ion_auth->activate($iduser);
			}

			// envio de mail
			if($iduser == 0 || $activa_antiguo){
				//$this->load->model('admin');
				//$this->admin->mail_creacion_usuario($userid,$password);			
			}
			
			if($perfil == 2){ // asocia comunidad

						$this->ion_auth->asocia_empresa($userid,$array_empresas);

			}

			if(!$userid){
				$this->session->set_flashdata('add_user_result',2);
				redirect('admins/admin_users');	
			}else{
				if($iduser == 0){
					$this->session->set_flashdata('add_user_result',1);
					redirect('admins/admin_users');	
				}else{
					$this->session->set_flashdata('add_user_result',3);
					redirect('admins/admin_users');	
				}
			}

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

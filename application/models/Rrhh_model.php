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

class Rrhh_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->config('ion_auth', TRUE);
		$this->load->helper('cookie');
		$this->load->helper('date');
		$this->lang->load('ion_auth');
		$this->load->helper('format');
	}

	public function get_centro_costo(){
		$this->db->select('id_centro_costo, id_empresa, nombre')
						  ->from('rem_centro_costo')
						  ->where('id_empresa', $this->session->userdata('empresaid'))
		                  ->order_by('nombre','asc');
		 $query = $this->db->get();
		 return $query->result();

	}

	public function get_centro_costo_periodo_abierto($idperiodo = null){
		$data_periodo = $this->db->select('cc.nombre, pr.id_periodo, pr.id_empresa, pr.id_centro_costo')
						  ->from('rem_periodo_remuneracion pr')
						  ->join('rem_centro_costo as cc','pr.id_centro_costo = cc.id_centro_costo')
						  ->where('pr.id_empresa', $this->session->userdata('empresaid'))
		                  ->where('pr.aprueba is null')
		                  ->where('pr.cierre is not null')
		                  ->order_by('pr.id_centro_costo','desc');

		$data_periodo = is_null($idperiodo)	? $data_periodo : $data_periodo->where('pr.id_periodo',$idperiodo);

		$query = $this->db->get();
		return $query->result() ;
	}	

	Public function get_centro_costo_no_calculado($mes,$anno){
		$data_periodo = "select id_centro_costo, nombre 
						from rem_centro_costo 
						where id_empresa =".$this->session->userdata('empresaid')." 
						and valido is not null 
						and id_centro_costo not in (select pr.id_centro_costo  
													from rem_periodo_remuneracion as pr
													join rem_periodo as p on pr.id_periodo = p.id_periodo
													where p.mes =".$mes." 
													and p.anno = ".$anno."
													and pr.id_empresa =".$this->session->userdata('empresaid')."
													and cierre is not null)"; 
    	
    	$query= $this->db->query($data_periodo);
   		return $query->result();
    	
	}



	public function get_centro_costo_pendiente($idperiodo =null){

		$data_periodo = "select id_centro_costo, nombre 
						from rem_centro_costo
						where id_empresa =".$this->session->userdata('empresaid')." 
						and valido is not null
						and id_centro_costo not in (select id_centro_costo 
													from rem_periodo_remuneracion
													where id_periodo =".$idperiodo."
													and id_empresa =".$this->session->userdata('empresaid')." 
													and cierre is not null)";

        $query= $this->db->query($data_periodo);
   		if(count($query->result()) == 0){
			return 0;
		}else{

   		return $query->result();
   	}

	}

	public function get_periodos_remuneracion_abiertos($idperiodo = null){
		$data_periodo = $this->db->select('p.id_periodo, p.mes, p.anno, pr.cierre, pr.aprueba, pr.anticipo')
						  ->from('rem_periodo as p')
						  ->join('rem_periodo_remuneracion as pr','p.id_periodo = pr.id_periodo')
						  ->where('pr.id_empresa', $this->session->userdata('empresaid'))
		                  ->where('pr.aprueba is null')
		                  ->where('pr.cierre is not null')
		                  ->group_by('p.id_periodo, p.mes,p.anno, pr.cierre, pr.aprueba, pr.anticipo')
		                  ->order_by('p.anno','desc')
		                  ->order_by('p.mes','desc');

		$data_periodo = is_null($idperiodo)	? $data_periodo : $data_periodo->where('pr.id_periodo',$idperiodo);

		$query = $this->db->get();
		return is_null($idperiodo) ? $query->result() : $query->row();
	}	


public function verificar_personal($rut){

		$this->db->trans_start();

		$this->db->select('p.id_personal, p.active')
						  ->from('rem_personal as p')
		                  ->where('p.rut', $rut)
		                  ->where('p.id_empresa', $this->session->userdata('empresaid'))
		                  ->where('p.active = 1');		
		$query = $this->db->get();
		$datos = $query->row();
		if(count($datos) == 0){
			return 0;
		}
		else{
			return 1;
		

		}
		$this->db->trans_complete();
}


public function desactivar_personal($rut){

		$this->db->trans_start();

		$this->db->select('p.id_personal, p.active')
						  ->from('rem_personal as p')
		                  ->where('p.rut', $rut)
		                  ->where('p.id_empresa', $this->session->userdata('empresaid'))
		                  ->where('p.active = 1');		
		$query = $this->db->get();
		$datos = $query->row();
		if(count($datos) == 0){
			return 0;
		}else{
			$this->db->where('rut', $rut);
			$this->db->where('id_empresa', $this->session->userdata('empresaid'));
			$this->db->update('rem_personal',array('active' => '0')); 
			$this->db->trans_complete();
			return 1;

		}



}

public function activar_personal($rut){

		$this->db->trans_start();

		$this->db->select('p.id_personal, p.active')
						  ->from('rem_personal as p')
		                  ->where('p.rut', $rut)
		                  ->where('p.id_empresa', $this->session->userdata('empresaid'))
		                  ->where('p.active = 0');		
		$query = $this->db->get();
		$datos = $query->row();
		if(count($datos) == 0){
			return 0;
		}else{
			$this->db->where('rut', $rut);
			$this->db->where('id_empresa', $this->session->userdata('empresaid'));
			$this->db->update('rem_personal',array('active' => '1')); 
			$this->db->trans_complete();
			return 1;

		}
}


public function edit_personal($array_datos,$idtrabajador){


		$this->db->trans_start();

		$this->db->select('p.id_personal, p.active')
						  ->from('rem_personal as p')
		                  ->where('p.rut', $array_datos['rut'])
		                  ->where('p.id_empresa', $this->session->userdata('empresaid'));		
		$query = $this->db->get();
		$datos = $query->row();
		if(count($datos) == 1){ // nuevo trabajador no existe
				$this->db->where('rut', $array_datos['rut']);
				$this->db->where('id_empresa', $this->session->userdata('empresaid'));
				$this->db->update('rem_personal',$array_datos); 
				$this->db->trans_complete();
				return 1;
			}

}



public function add_personal($array_datos,$idtrabajador){


		$this->db->trans_start();

		$this->db->select('p.id_personal, p.active')
						  ->from('rem_personal as p')
		                  ->where('p.rut', $array_datos['rut'])
		                  ->where('p.id_empresa', $this->session->userdata('empresaid'));		
		$query = $this->db->get();
		$datos = $query->row();
		if(count($datos) == 0){ // nuevo trabajador no existe
			if($idtrabajador == 0){
				$array_datos['updated_at'] = date('Y-m-d H:i:s');
				$array_datos['created_at'] = date('Y-m-d H:i:s');
				$this->db->insert('rem_personal', $array_datos);
				$idpersonal = $this->db->insert_id();


				$this->db->trans_complete();
				return 1;
			}else{
				$this->db->select('p.id, p.active')
								  ->from('gc_personal as p')
				                  ->where('p.id', $idtrabajador)
				                  ->where('p.idcomunidad', $this->session->userdata('empresaid'));	
				$query = $this->db->get();
				$trabajador = $query->row();
				$cambio_estado = false;
				if($trabajador->active == 1 && $array_datos['active'] == 0){
					$cambio_estado = true;
					$mensaje = "Desactivación Trabajador";
				}else if($trabajador->active == 0 && $array_datos['active'] == 1){
					$cambio_estado = true;
					$mensaje = "Activación Trabajador";					
				}else{
					$cambio_estado = false;
				}


				unset($array_datos['rut']); 
				unset($array_datos['dv']);
				$this->db->where('id', $idtrabajador);
				$this->db->where('idcomunidad', $this->session->userdata('empresaid'));		
				$this->db->update('gc_personal',$array_datos); 




				$this->db->delete('gc_bonos_personal', array('idpersonal' => $idtrabajador)); 
				foreach ($array_bonos as $bono) {
					$bono['idpersonal'] = $idtrabajador;
					$this->db->insert('gc_bonos_personal', $bono);

				}


				if($cambio_estado){
					$this->cambio_estado($idtrabajador,$mensaje,$array_datos['active']);
				}

				$this->db->trans_complete();
				return 1;
			}
		}else{ // ya existe trabajador

			if($idtrabajador != 0){

				unset($array_datos['rut']);
				unset($array_datos['dv']);
				$this->db->where('id', $idtrabajador);
				$this->db->where('idcomunidad', $this->session->userdata('empresaid'));		
				$this->db->update('gc_personal',$array_datos); 		

				$this->db->delete('gc_bonos_personal', array('idpersonal' => $idtrabajador)); 
				foreach ($array_bonos as $bono) {
					$bono['idpersonal'] = $idtrabajador;
					$this->db->insert('gc_bonos_personal', $bono);

				}

				$this->db->trans_complete();
				return 1;
			}else{
				$this->db->trans_complete();
				return -1;	
			}
			
		}

	}	



	public function get_datos_remuneracion_by_periodo($idperiodo,$idtrabajador = null){

		$personal_data = $this->db->select('r.id_remuneracion, r.idpersonal, r.id_periodo, r.diastrabajo, r.horasdescuento, r.montodescuento, r.horasextras50, r.montohorasextras50, r.horasextras100, r.montohorasextras100, r.anticipo, r.aguinaldo, r.sueldobase, r.gratificacion, r.movilizacion, r.sueldonoimponible, r.totalleyessociales, r.otrosdescuentos')
						  ->from('rem_remuneracion r')
						  ->join('rem_personal pe','r.idpersonal = pe.id_personal')
						  ->where('pe.id_empresa',$this->session->userdata('empresaid'))
						  ->where('pe.active = 1')
						  ->where('r.id_periodo',$idperiodo)						  
		                  ->order_by('pe.nombre');
		$personal_data = is_null($idtrabajador) ? $personal_data : $personal_data->where('r.idpersonal',$idtrabajador);  		                  
		$query = $this->db->get();
		$datos = is_null($idtrabajador) ? $query->result() : $query->row();
		return $datos;
	}	




	public function get_datos_remuneracion($mes,$anno,$idtrabajador = null){

		$personal_data = $this->db->select('r.idpersonal, r.id_periodo, r.diastrabajo, r.horasdescuento, r.montodescuento, r.valorhorasextras50, r.horasextras50, r.montohorasextras50, r.valorhorasextras100, r.horasextras100, r.montohorasextras100, r.anticipo, r.aguinaldo, r.sueldobase, r.gratificacion, r.movilizacion, r.valorhora')
						  ->from('rem_remuneracion r')
						  ->join('rem_personal pe','r.idpersonal = pe.id_personal')
						  ->join('rem_periodo p','r.id_periodo = p.id_periodo')
						  ->where('pe.id_empresa',$this->session->userdata('empresaid'))
						  ->where('pe.active = 1')
						  ->where('p.mes',$mes)
						  ->where('p.anno',$anno)
		                  ->order_by('pe.nombre');
		$personal_data = is_null($idtrabajador) ? $personal_data : $personal_data->where('r.idpersonal',$idtrabajador);  		                  
		$query = $this->db->get();
		$datos = is_null($idtrabajador) ? $query->result() : $query->row();
		return $datos;
	}	



	public function get_estado_periodo($mes,$anno){

		$this->db->select('pr.anticipo, pr.cierre')
						  ->from('rem_periodo_remuneracion as pr')
						  ->join('rem_periodo as p','pr.id_periodo = p.id_periodo')
		                  ->where('p.mes', $mes)
		                  ->where('p.anno', $anno)
		                  ->where('pr.id_empresa', $this->session->userdata('empresaid'))
		                  ->order_by('pr.cierre');
		                  //->where('pr.cierre is null');
		$query = $this->db->get();
		$datos_periodo = $query->row();
		if(count($datos_periodo) == 0){
			return 2;
		}else{
			
			if(is_null($datos_periodo->cierre)){
				return is_null($datos_periodo->anticipo) ? 1 : 3;  #EL 3 aplica sólo en cálculo de anticipo
			}else{
				return 0;
			}
		}
	}


public function save_asistencia($array_trabajadores,$mes,$anno){


		$this->db->trans_start();

		// evaluar si existe periodo
		$this->db->select('p.id_periodo')
						  ->from('rem_periodo as p')
		                  ->where('p.mes', $mes)
		                  ->where('p.anno', $anno);
		$query = $this->db->get();
		$datos_periodo = $query->row();
		$idperiodo = 0;
		if(count($datos_periodo) == 0){ // si no existe periodo, se crea
				$data = array(
			      	'mes' => $mes,
			      	'anno' =>  $anno
				);
				$this->db->insert('rem_periodo', $data);
				$idperiodo = $this->db->insert_id();
		}else{
				$idperiodo = $datos_periodo->id_periodo;
		}


		// evaluar si existe periodo remuneraciones
		$this->db->select('r.id_periodo')
						  ->from('rem_periodo_remuneracion as r')
		                  ->where('r.id_periodo', $idperiodo)
		                  ->where('r.id_empresa', $this->session->userdata('empresaid'));
		$query = $this->db->get();
		$datos_periodo_remuneracion = $query->row();
		if(count($datos_periodo_remuneracion) == 0){ // si no existe periodo, se crea
				$data = array(
			      	'id_periodo' => $idperiodo,
			      	'id_empresa' => $this->session->userdata('empresaid')
				);
				$this->db->insert('rem_periodo_remuneracion', $data);
		}




		foreach ($array_trabajadores as $idtrabajador => $info_trabajador) {

			$this->db->select('r.id_periodo')
							  ->from('rem_remuneracion as r')
			                  ->where('r.idpersonal', $idtrabajador)
			                  ->where('r.id_periodo', $idperiodo);
			$query = $this->db->get();
			$datos_remuneracion = $query->row();
			if(count($datos_remuneracion) == 0){ // si no existe periodo, se crea

					$data = array(
				      	'idpersonal' => $idtrabajador,
				      	'id_periodo' => $idperiodo,
				      	'diastrabajo' => $info_trabajador,
				      	'id_empresa' => $this->session->userdata('empresaid'),
				      	'created_at' => date("Ymd H:i:s")

					);
					$this->db->insert('rem_remuneracion', $data);
			}else{
					$data = array(
				      	'diastrabajo' => $info_trabajador
					);				
					$this->db->where('idpersonal', $idtrabajador);
					$this->db->where('id_periodo', $idperiodo);
					$this->db->update('rem_remuneracion',$data); 

			}
		}

		$this->db->trans_complete();
		return 1;
	}	



public function save_horas_extraordinarias($array_trabajadores,$mes,$anno){

		$this->db->trans_start();

		// evaluar si existe periodo
		$this->db->select('p.id_periodo')
						  ->from('rem_periodo as p')
		                  ->where('p.mes', $mes)
		                  ->where('p.anno', $anno);
		$query = $this->db->get();
		$datos_periodo = $query->row();
		$idperiodo = 0;
		if(count($datos_periodo) == 0){ // si no existe periodo, se crea
				$data = array(
			      	'mes' => $mes,
			      	'anno' =>  $anno
				);
				$this->db->insert('rem_periodo', $data);
				$idperiodo = $this->db->insert_id();
		}else{
				$idperiodo = $datos_periodo->id_periodo;
		}



		// evaluar si existe periodo remuneraciones
		$this->db->select('r.id_periodo')
						  ->from('rem_periodo_remuneracion as r')
		                  ->where('r.id_periodo', $idperiodo)
		                  ->where('r.id_empresa', $this->session->userdata('empresaid'));
		$query = $this->db->get();
		$datos_periodo_remuneracion = $query->row();
		if(count($datos_periodo_remuneracion) == 0){ // si no existe periodo, se crea
				$data = array(
			      	'id_periodo' => $idperiodo,
			      	'id_empresa' => $this->session->userdata('empresaid')
				);
				$this->db->insert('rem_periodo_remuneracion', $data);
		}



		foreach ($array_trabajadores as $idtrabajador => $info_trabajador) {

			$this->db->select('r.id_periodo')
							  ->from('rem_remuneracion as r')
			                  ->where('r.idpersonal', $idtrabajador)
			                  ->where('r.id_periodo', $idperiodo);
			$query = $this->db->get();
			$datos_remuneracion = $query->row();
			if(count($datos_remuneracion) == 0){ // si no existe periodo, se crea

					$data = array(
						'idpersonal' => $idtrabajador,
				      	'id_periodo' => $idperiodo,
				      	'horasextras50' => $info_trabajador['horas50'],
				      	'montohorasextras50' => $info_trabajador['monto50'],
				      	'horasextras100' => $info_trabajador['horas100'],
				      	'montohorasextras100' => $info_trabajador['monto100'],				      	
				      	'id_empresa' => $this->session->userdata('empresaid'),
				      	'created_at' => date("Ymd H:i:s")

					);
					$this->db->insert('rem_remuneracion', $data);
			}else{
					$data = array(
				      	'horasextras50' => $info_trabajador['horas50'],
				      	'montohorasextras50' => $info_trabajador['monto50'],
				      	'horasextras100' => $info_trabajador['horas100'],
				      	'montohorasextras100' => $info_trabajador['monto100']	
					);				
					$this->db->where('idpersonal', $idtrabajador);
					$this->db->where('id_periodo', $idperiodo);
					$this->db->update('rem_remuneracion',$data); 

			}			

			
		}

		$this->db->trans_complete();
		return 1;
	}

	public function save_horas_extraordinarias_masiva($array_trabajadores,$mes,$anno){

		$this->db->trans_start();

		// evaluar si existe periodo
		$this->db->select('p.id_periodo')
						  ->from('rem_periodo as p')
		                  ->where('p.mes', $mes)
		                  ->where('p.anno', $anno);
		$query = $this->db->get();
		$datos_periodo = $query->row();
		$idperiodo = 0;
		if(count($datos_periodo) == 0){ // si no existe periodo, se crea
				$data = array(
			      	'mes' => $mes,
			      	'anno' =>  $anno
				);
				$this->db->insert('rem_periodo', $data);
				$idperiodo = $this->db->insert_id();
		}else{
				$idperiodo = $datos_periodo->id_periodo;
		}



		// evaluar si existe periodo remuneraciones
		$this->db->select('r.id_periodo')
						  ->from('rem_periodo_remuneracion as r')
		                  ->where('r.id_periodo', $idperiodo)
		                  ->where('r.id_empresa', $this->session->userdata('empresaid'));
		$query = $this->db->get();
		$datos_periodo_remuneracion = $query->row();
		if(count($datos_periodo_remuneracion) == 0){ // si no existe periodo, se crea
				$data = array(
			      	'id_periodo' => $idperiodo,
			      	'id_empresa' => $this->session->userdata('empresaid')
				);
				$this->db->insert('rem_periodo_remuneracion', $data);
		}



		foreach ($array_trabajadores as $info_trabajador) {

			$this->db->select('r.id_periodo')
							  ->from('rem_remuneracion as r')
			                  ->where('r.idpersonal', $info_trabajador['idtrabajador'])
			                  ->where('r.id_periodo', $idperiodo);
			$query = $this->db->get();
			$datos_remuneracion = $query->row();
			if(count($datos_remuneracion) == 0){ // si no existe periodo, se crea

					$data = array(
						'idpersonal' => $info_trabajador['idtrabajador'],
				      	'id_periodo' => $idperiodo,
				      	'horasextras50' => $info_trabajador['horas2'],
				      	'montohorasextras50' => $info_trabajador['montohorasextras50'],
				      	'horasextras100' => $info_trabajador['horas1'],
				      	'montohorasextras100' => $info_trabajador['montohorasextras100'],				      	
				      	'id_empresa' => $this->session->userdata('empresaid'),
				      	'created_at' => date("Ymd H:i:s")

					);
					$this->db->insert('rem_remuneracion', $data);
			}else{
					$data = array(
				      	'horasextras50' => $info_trabajador['horas50'],
				      	'montohorasextras50' => $info_trabajador['monto50'],
				      	'horasextras100' => $info_trabajador['horas100'],
				      	'montohorasextras100' => $info_trabajador['monto100']	
					);				
					$this->db->where('idpersonal', $info_trabajador['idtrabajador']);
					$this->db->where('id_periodo', $idperiodo);
					$this->db->update('rem_remuneracion',$data); 

			}			

			
		}

		$this->db->trans_complete();
		return 1;
	}



	public function save_anticipo($array_trabajadores,$mes,$anno){

		$this->db->trans_start();

		// evaluar si existe periodo
		$this->db->select('p.id_periodo')
						  ->from('rem_periodo as p')
		                  ->where('p.mes', $mes)
		                  ->where('p.anno', $anno);
		$query = $this->db->get();
		$datos_periodo = $query->row();
		$idperiodo = 0;
		if(count($datos_periodo) == 0){ // si no existe periodo, se crea
				$data = array(
			      	'mes' => $mes,
			      	'anno' =>  $anno
				);
				$this->db->insert('rem_periodo', $data);
				$idperiodo = $this->db->insert_id();
		}else{
				$idperiodo = $datos_periodo->id_periodo;
		}


		// evaluar si existe periodo remuneraciones
		$this->db->select('r.id_periodo')
						  ->from('rem_periodo_remuneracion as r')
		                  ->where('r.id_periodo', $idperiodo)
		                  ->where('r.id_empresa', $this->session->userdata('empresaid'));
		$query = $this->db->get();
		$datos_periodo_remuneracion = $query->row();
		if(count($datos_periodo_remuneracion) == 0){ // si no existe periodo, se crea
				$data = array(
			      	'id_periodo' => $idperiodo,
			      	'id_empresa' => $this->session->userdata('empresaid')
				);
				$this->db->insert('rem_periodo_remuneracion', $data);
		}




		foreach ($array_trabajadores as $idtrabajador => $info_trabajador) {

			$this->db->select('r.id_periodo')
							  ->from('rem_remuneracion as r')
			                  ->where('r.idpersonal', $idtrabajador)
			                  ->where('r.id_periodo', $idperiodo);
			$query = $this->db->get();
			$datos_remuneracion = $query->row();
			if(count($datos_remuneracion) == 0){ // si no existe periodo, se crea
					$data = array(
				      	'idpersonal' => $idtrabajador,
				      	'id_periodo' => $idperiodo,
				      	'anticipo' => $info_trabajador['anticipo'],
				      	'aguinaldo' => $info_trabajador['aguinaldo'],
				      	'id_empresa' => $this->session->userdata('empresaid'),
				      	'created_at' => date("Ymd H:i:s")

					);
					$this->db->insert('rem_remuneracion', $data);
			}else{
					$data = array(
				      	'anticipo' => $info_trabajador['anticipo'],
				      	'aguinaldo' => $info_trabajador['aguinaldo'],
					);				
					$this->db->where('idpersonal', $idtrabajador);
					$this->db->where('id_periodo', $idperiodo);
					$this->db->update('rem_remuneracion',$data); 

			}
		}

		$this->db->trans_complete();
		return 1;
	}

	public function get_personal_datos($rut){


		$array_campos = array(
				'id_personal', 
				'id_empresa', 
				'concat(cast(rut as varchar),\'-\',dv) as rut', 
				'dv', 
				'nombre', 
				'apaterno', 
				'amaterno', 
				'fecnacimiento', 
				'sexo', 
				'idecivil', 
				'nacionalidad', 
				'direccion', 
				'idregion', 
				'idcomuna', 
				'fono', 
				'email', 
				'fecingreso', 
				'fecingreso as fecingreso_sformat',
				'idcargo', 
				'tipocontrato', 
				'parttime', 
				'segcesantia', 
				'pensionado', 
				'diastrabajo', 
				'horasdiarias', 
				'horassemanales', 
				'sueldobase', 
				'tipogratificacion', 
				'gratificacion', 
				'asigfamiliar', 
				'cargassimples', 
				'cargasinvalidas', 
				'cargasmaternales', 
				'cargasretroactivas', 
				'idasigfamiliar',
				'movilizacion', 
				'colacion', 
				'idafp', 
				'adicafp', 
				'tipoahorrovol', 
				'ahorrovol', 
				'tipocotapv', 
				'cotapv', 
				'idisapre', 
				'valorpactado',
				'idnacionalidad',
				/*'COALESCE((select sum(monto) as monto from rem_bonos_personal where idpersonal = p.id and fijo = 1 and imponible = 1),0) as bonos_fijos',*/
				'0 as bonos_fijos',
				'DATEDIFF(YY,fecafc,getdate()) as annos_afc,
				DATEDIFF(MM,fecinicvacaciones,getdate()) as meses_vac,
				fecinicvacaciones,
				saldoinicvacaciones,
				diasvactomados,
				diasprogresivos,
				diasprogtomados,
				saldoinicvacprog,
				idcentrocosto'
			);
		
		$personal_data = $this->db->select($array_campos)
						  ->from('rem_personal p')
						  ->where('p.id_empresa',$this->session->userdata('empresaid'))
						  ->where('p.active is not null')
						 // ->where_in('idcentrocosto',$centro_costo)
		                  ->order_by('p.nombre');
		$personal_data = is_null($rut) ? $personal_data : $personal_data->where('p.rut',$rut);
		//$personal_data = !$centro_costo  ? $personal_data : $personal_data->where_in('idcentrocosto',$centro_costo);

		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		$datos =  $query->result();
		return $datos;
	}

	public function get_personal($idtrabajador = null,$centro_costo =false){


		$array_campos = array(
				'id_personal', 
				'id_empresa', 
				'rut', 
				'dv', 
				'nombre', 
				'apaterno', 
				'amaterno', 
				'fecnacimiento', 
				'sexo', 
				'idecivil', 
				'nacionalidad', 
				'direccion', 
				'idregion', 
				'idcomuna', 
				'fono', 
				'email', 
				'fecingreso', 
				'fecingreso as fecingreso_sformat',
				'idcargo', 
				'tipocontrato', 
				'parttime', 
				'segcesantia', 
				'pensionado', 
				'diastrabajo', 
				'horasdiarias', 
				'horassemanales', 
				'sueldobase', 
				'tipogratificacion', 
				'gratificacion', 
				'asigfamiliar', 
				'cargassimples', 
				'cargasinvalidas', 
				'cargasmaternales', 
				'cargasretroactivas', 
				'idasigfamiliar',
				'movilizacion', 
				'colacion', 
				'idafp', 
				'adicafp', 
				'tipoahorrovol', 
				'ahorrovol', 
				'tipocotapv', 
				'cotapv', 
				'idisapre', 
				'valorpactado',
				/*'COALESCE((select sum(monto) as monto from rem_bonos_personal where idpersonal = p.id and fijo = 1 and imponible = 1),0) as bonos_fijos',*/
				'0 as bonos_fijos',
				'DATEDIFF(YY,fecafc,getdate()) as annos_afc,
				DATEDIFF(MM,fecinicvacaciones,getdate()) as meses_vac,
				fecinicvacaciones,
				saldoinicvacaciones,
				diasvactomados,
				diasprogresivos,
				diasprogtomados,
				saldoinicvacprog,
				idcentrocosto'
			);
		
		$personal_data = $this->db->select($array_campos)
						  ->from('rem_personal p')
						  ->where('p.id_empresa',$this->session->userdata('empresaid'))
						  ->where('p.active = 1')
						 // ->where_in('idcentrocosto',$centro_costo)
		                  ->order_by('p.nombre');
		$personal_data = is_null($idtrabajador) ? $personal_data : $personal_data->where('p.id_personal',$idtrabajador);
		$personal_data = !$centro_costo  ? $personal_data : $personal_data->where_in('idcentrocosto',$centro_costo);

		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		$datos = is_null($idtrabajador) ? $query->result() : $query->row();
		return $datos;
	}
	
	public function get_personal_paso($idtrabajador = null,$centro_costo =false){


		$array_campos = array(
				'id_personal', 
				'id_empresa', 
				'rut', 
				'dv', 
				'nombre', 
				'apaterno', 
				'amaterno', 
				'fecnacimiento', 
				'sexo', 
				'idecivil', 
				'nacionalidad', 
				'direccion', 
				'idregion', 
				'idcomuna', 
				'fono', 
				'email', 
				'fecingreso', 
				'fecingreso as fecingreso_sformat',
				'idcargo', 
				'tipocontrato', 
				'parttime', 
				'segcesantia', 
				'pensionado', 
				'diastrabajo', 
				'horasdiarias', 
				'horassemanales', 
				'sueldobase', 
				'tipogratificacion', 
				'gratificacion', 
				'asigfamiliar', 
				'cargassimples', 
				'cargasinvalidas', 
				'cargasmaternales', 
				'cargasretroactivas', 
				'idasigfamiliar',
				'movilizacion', 
				'colacion', 
				'idafp', 
				'adicafp', 
				'tipoahorrovol', 
				'ahorrovol', 
				'tipocotapv', 
				'cotapv', 
				'idisapre', 
				'valorpactado',
				/*'COALESCE((select sum(monto) as monto from rem_bonos_personal where idpersonal = p.id and fijo = 1 and imponible = 1),0) as bonos_fijos',*/
				'0 as bonos_fijos',
				'DATEDIFF(YY,fecafc,getdate()) as annos_afc,
				DATEDIFF(MM,fecinicvacaciones,getdate()) as meses_vac,
				fecinicvacaciones,
				saldoinicvacaciones,
				diasvactomados,
				diasprogresivos,
				diasprogtomados,
				saldoinicvacprog,
				idcentrocosto'
			);
		
		$personal_data = $this->db->select($array_campos)
						  ->from('rem_personal p')
						  ->where('p.id_empresa',$this->session->userdata('empresaid'))
						  ->where('p.active = 1')
						 // ->where_in('idcentrocosto',$centro_costo)
		                  ->order_by('p.nombre');
		$personal_data = is_null($idtrabajador) ? $personal_data : $personal_data->where('p.id',$idtrabajador);
		$personal_data = !$centro_costo  ? $personal_data : $personal_data->where_in('idcentrocosto',$centro_costo);

		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		$datos = is_null($idtrabajador) ? $query->result() : $query->row();
		return $datos;
	}	


	public function set_datos_iniciales_periodo_rem($mes,$anno,$centro_costo){

		$this->db->trans_start();
				// evaluar si existe periodo
		$this->db->select('p.id_periodo')
						  ->from('rem_periodo as p')
		                  ->where('p.mes', $mes)
		                  ->where('p.anno', $anno);
		$query = $this->db->get();
		$datos_periodo = $query->row();
		$idperiodo = 0;
		if(count($datos_periodo) == 0){ // si no existe periodo, se crea
				$data = array(
			      	'mes' => $mes,
			      	'anno' =>  $anno
				);
				$this->db->insert('rem_periodo', $data);
				$idperiodo = $this->db->insert_id();
		}else{
				$idperiodo = $datos_periodo->id_periodo;
		}


		// evaluar si existe periodo remuneraciones
		$this->db->select('r.id_periodo')
						  ->from('rem_periodo_remuneracion as r')
		                  ->where('r.id_periodo', $idperiodo)
		                  ->where('id_centro_costo',$centro_costo)
		                  ->where('r.id_empresa', $this->session->userdata('empresaid'));
		$query = $this->db->get();
		$datos_periodo_remuneracion = $query->row();
		if(count($datos_periodo_remuneracion) == 0){ // si no existe periodo, se crea
				$data = array(
			      	'id_periodo' => $idperiodo,
			      	'id_empresa' => $this->session->userdata('empresaid'),
			      	'id_centro_costo' => $centro_costo
				);
				$this->db->insert('rem_periodo_remuneracion', $data);
		}


		##CUALQUIER DATO CARGADO LO BORRA
		/*$this->db->where('id_empresa', $this->session->userdata('empresaid'));
		$this->db->where('idperiodo', $idperiodo);
		$this->db->delete('rem_remuneracion');*/


		$personal = $this->get_personal(null,$centro_costo); 
		foreach ($personal as $trabajador) {

			$this->db->select('r.id_periodo')
							  ->from('rem_remuneracion as r')
			                  ->where('r.idpersonal', $trabajador->id_personal)
			                  ->where('r.id_periodo', $idperiodo)
			                  ->where('r.id_empresa', $this->session->userdata('empresaid'));
			$query = $this->db->get();
			$datos_remuneracion = $query->row();
			if(count($datos_remuneracion) == 0){ // si no existe periodo, se crea

					$data = array(
				      	'idpersonal' => $trabajador->id_personal,
				      	'id_periodo' => $idperiodo,
				      	'id_empresa' => $this->session->userdata('empresaid'),
				      	'idcentrocosto' => $trabajador->idcentrocosto,
				      	'created_at' => date("Y-m-d H:i:s")

					);
					$this->db->insert('rem_remuneracion', $data);
			}/*else{
					$data = array(
				      	'diastrabajo' => $info_trabajador
					);				
					$this->db->where('idpersonal', $idtrabajador);
					$this->db->where('idperiodo', $idperiodo);
					$this->db->update('gc_remuneracion',$data); 

			}*/
		}

		$this->db->trans_complete();
		return $idperiodo;
	}


	public function get_periodos($empresaid,$idperiodo = null){

		$periodo_data = $this->db->select('p.id_periodo, p.mes, p.anno, pr.anticipo, pr.cierre, pr.aprueba, pr.cierre,  (select count(*) from rem_remuneracion r inner join rem_personal pe on r.idpersonal = pe.id_personal where r.id_periodo = p.id_periodo and pe.id_empresa = ' . $empresaid . ' and r.active = 1) as numtrabajadores, (select sum(sueldoimponible) from rem_remuneracion r inner join rem_personal pe on r.idpersonal = pe.id_personal where r.id_periodo = p.id_periodo and pe.id_empresa = ' . $empresaid . ' and r.active = 1) as sueldoimponible ', false)
						  ->from('rem_periodo as p')
						  ->join('rem_periodo_remuneracion as pr','p.id_periodo = pr.id_periodo')
		                  ->where('pr.id_empresa', $empresaid)
		                  ->order_by('p.updated_at desc');
		$comunidades_data = is_null($idperiodo) ? $periodo_data : $periodo_data->where('pr.id_periodo',$idperiodo);
		$query = $this->db->get();
		$datos = is_null($idperiodo) ? $query->result() : $query->row();				                  
		return $datos;

	}	


	public function aprobar_remuneracion($idperiodo,$centro_costo){

		$this->db->where('id_periodo', $idperiodo);
		//$this->db->where('id_centro_costo', $centro_costo);
		$this->db->where('id_empresa', $this->session->userdata('empresaid'));
		$this->db->update('rem_periodo_remuneracion',array('aprueba' => date("Ymd H:i:s"))); 
		return 1;
	}

	public function selector($select){

		$this->db->where('id_empresa', $select);
		return $select();
	}



	public function get_remuneraciones_reversa($idperiodo,$centro_costo){
		$periodo_data = $this->db->select('r.id_remuneracion')
						  ->from('rem_remuneracion as r')
						  ->join('rem_periodo_remuneracion as pr','r.id_periodo = pr.id_periodo and pr.id_empresa = ' . $this->session->userdata('empresaid'))
		                  ->where('r.id_empresa', $this->session->userdata('empresaid'))
		                  ->where('r.id_periodo', $idperiodo)
		                  ->where('r.idcentrocosto',$centro_costo)
		                  ->where('pr.cierre is not null')
		                  ->where('pr.aprueba is null')
		                  ->order_by('r.id_remuneracion asc');
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		return $query->result();


	}


	public function rechazar_remuneracion($idperiodo,$centro_costo){


		$this->db->trans_start();
		#obtengo remuneraciones del periodo para la comunidad (me aseguro que sea un periodo ya calculado y no aprobado)
		$remuneraciones = $this->get_remuneraciones_reversa($idperiodo,$centro_costo);

		//echo "<pre>";
		//print_r($remuneraciones); exit;
		if(count($remuneraciones) > 0){ // SÓLO REALIZA REVERSA EN CASO DE QUE EL PERÍODO CORRESPONDA

			foreach ($remuneraciones as $remuneracion) {
				#elimino los bonos cargados a la remuneracion
				$this->db->delete('rem_bonos_remuneracion', array('idremuneracion' => $remuneracion->id_remuneracion)); 

				$this->db->delete('rem_haber_descuento_remuneracion', array('idremuneracion' => $remuneracion->id_remuneracion)); 

				#devuelvo los valores de las cargas retroactivas
				$this->db->query("update p
								  set 
								  p.asigfamiliar = r.montocargaretroactiva,
								  p.cargasretroactivas = r.cargasretroactivas
								  from	rem_personal p
								  inner join rem_remuneracion r on p.id_personal = r.idpersonal
								  where r.id_remuneracion = " . $remuneracion->id_remuneracion);		



			}

			#quitamos la marca de remuneracion calculada (permite volver a calcular)
			$this->db->where('id_periodo', $idperiodo);
			$this->db->where('id_empresa', $this->session->userdata('empresaid'));
			$this->db->where('id_centro_costo', $centro_costo);
			$this->db->update('rem_periodo_remuneracion',array('cierre' => null)); 
		}


		$this->db->trans_complete();

		return 1;
	}	

	public function update_caja_mutual($array_datos){


		$this->db->where('id_empresa', $this->session->userdata('empresaid'));
		$this->db->update('rem_empresa',$array_datos); 
		return 1;
	}	


	public function get_haberes_descuentos($idtrabajador,$imponible = null,$tipo = null){

			if(!is_null($imponible)){
					$campo_imponible = $imponible == true ? 1 : 0;	
			}		
		

			//OBTIENE LOS HABERES DEL TRABAJADOR
			$haberes_data = $this->db->select('d.monto, h.imponible, h.nombre')
							  ->from('rem_haber_descuento_personal_tmp d')
							  ->join('rem_conf_haber_descuento as h','d.idconfhd = h.id')
			                  ->where('d.id_personal',$idtrabajador);
			

			$haberes_data = is_null($imponible) ? $haberes_data : $haberes_data->where('h.imponible',$campo_imponible);  	
			$haberes_data = is_null($tipo) ? $haberes_data : $haberes_data->where('h.tipo',$tipo);  	

			$query = $this->db->get();
			//echo $this->db->last_query(); exit;
			return $query->result();		
	}


	public function get_bonos_by_remuneracion($idremuneracion,$imponible = null){

		if(!is_null($imponible)){
			$campo_imponible = $imponible == true ? 1 : 0;	
		}
		
		$bonos_data = $this->db->select('id, descripcion, imponible, monto')
						  ->from('gc_bonos_remuneracion')
						  ->where('idremuneracion',$idremuneracion)
		                  ->order_by('id');

		$bonos_data = is_null($imponible) ? $bonos_data : $bonos_data->where('imponible',$campo_imponible);  		                  
		$query = $this->db->get();
		return $query->result();
	}	


	public function calcular_remuneraciones($idperiodo,$centro_costo){

		$this->db->trans_start();

		$periodo =  $this->get_periodos($this->session->userdata('empresaid'),$idperiodo);
		$this->load->model('admin');
		//$periodo = $this->admin->get_periodo_by_id($idperiodo);
		$empresa = $this->admin->get_empresas($this->session->userdata('empresaid')); 

		$tabla_impuesto = $this->admin->get_tabla_impuesto();
		


		$parametros = $this->admin->get_parametros_generales();
		$monto_total_sueldos = 0;
		$tope_legal_gratificacion = ($parametros->sueldominimo*4.75)/12;



		$array_pago_afp = array();
		$array_pago_isapre = array();
		$array_descuentos = array();
		$array_prestamos = array();
		$dia_mes =  $periodo->mes == 2 ? 28 : 30;
		$suma_aporte_patronal = 0;
		$suma_asig_familiar = 0;
		$suma_ips = 0;
		$suma_impuesto = 0;
		$tope_imponible = (int)($parametros->uf*$parametros->topeimponible);

		$this->db->query('update r 
							set r.active = 0
							from rem_remuneracion r 
						    inner join rem_personal p on r.idpersonal = p.id_personal
                            where p.id_empresa = ' . $this->session->userdata('empresaid') . ' and r.id_periodo = ' . $idperiodo );

		$personal = $this->get_personal(null,$centro_costo); 

		foreach ($personal as $trabajador) { // calculo de sueldos por cada trabajador
			$datos_remuneracion = $this->get_datos_remuneracion_by_periodo($idperiodo,$trabajador->id_personal);

			$datos_bonos = array();
			//$datos_bonos = $this->get_bonos($trabajador->id); // se modifica esto porque aún no existen bonos
			$bonos_imponibles = 0;
			$bonos_no_imponibles = 0;

			//OBTIENE LOS HABERES DEL TRABAJADOR
			$datos_hd = $this->get_haberes_descuentos($trabajador->id_personal,null,'HABER');			

			$diastrabajo = $trabajador->parttime == 1 ? $trabajador->diastrabajo : 30;
			$sueldo_base_mes = round(($trabajador->sueldobase/$diastrabajo)*$datos_remuneracion->diastrabajo,0);


			$movilizacion_mes = round(($trabajador->movilizacion/$diastrabajo)*$datos_remuneracion->diastrabajo,0);
			$colacion_mes = round(($trabajador->colacion/$diastrabajo)*$datos_remuneracion->diastrabajo,0);

			

			foreach ($datos_hd as $bono) {

					
					if($bono->imponible == 1){
						$bonos_imponibles += $bono->monto;

					}else{
						$bonos_no_imponibles += $bono->monto;
					}				
					$data_bono = array(
								'idremuneracion' => $datos_remuneracion->id_remuneracion,
								'descripcion' => $bono->nombre,
								'imponible' => $bono->imponible,
								'monto' => $bono->monto
								);
					$this->db->insert('rem_haber_descuento_remuneracion', $data_bono);
			}			

			foreach ($datos_bonos as $bono) {
				$tiene_bono = false;
				if($bono->fijo == 1){ // se suma siempre
					$tiene_bono = true;
				}else{ // validar si corresponde al período
					$array_fecha_bono = explode("/",$bono->fecha);
					$mes_bono = (int)$array_fecha_bono[1];
					$anno_bono = $array_fecha_bono[2];
					$tiene_bono = $mes_bono == $periodo->mes && $anno_bono == $periodo->anno ? true : false; // el bono corresponde al periodo que estamos calculando.  Entonces si aplica el bono
				}

				if($tiene_bono){
					
					$valor_bono = $bono->proporcional == 1 ? round(($bono->monto/$diastrabajo)*$datos_remuneracion->diastrabajo,0) : $bono->monto;
					if($bono->imponible == 1){
						$bonos_imponibles += $valor_bono;

					}else{
						$bonos_no_imponibles += $valor_bono;
					}				
					$data_bono = array(
								'idremuneracion' => $datos_remuneracion->id,
								'descripcion' => $bono->descripcion,
								'imponible' => $bono->imponible,
								'monto' => $valor_bono
								);
					$this->db->insert('rem_bonos_remuneracion', $data_bono);
				}
			}

			$datos_afp = $this->admin->get_afp($trabajador->idafp);
			//$valor_hora = $trabajador->parttime == 1 ? ((($trabajador->sueldobase + $trabajador->bonos_fijos)/$trabajador->diastrabajo)/$trabajador->horasdiarias) : ((($trabajador->sueldobase + $trabajador->bonos_fijos)/30)*7)/45;
			$valor_hora = $trabajador->parttime == 1 ? ((($trabajador->sueldobase)/$trabajador->diastrabajo)/$trabajador->horasdiarias) : ((($trabajador->sueldobase)/30)*7)/45;
			$valor_hora = round($valor_hora,0);
			//calculo total haberes
			$valor_hora50 =  round($valor_hora*1.5,0);
			$valor_hora100 = round($valor_hora*2,0);
			$monto_horas50 = $datos_remuneracion->horasextras50*$valor_hora50;
			$monto_horas100 = $datos_remuneracion->horasextras100*$valor_hora100;






			$porc_com_afp = $datos_afp->porc > 0 ? $datos_afp->porc - 10 : 0;
			$porc_cot_oblig = $datos_afp->exregimen == 2 ? 0 : 0.1;
			

			//$gratificacion = $trabajador->sueldobase*0.25;


			//Calculo asignación familiar
			$num_cargas_simples = $trabajador->cargassimples;
			$num_cargas_maternales = $trabajador->cargasmaternales;

			$num_cargas = $num_cargas_simples + $num_cargas_maternales;
			$monto_ingresos = $trabajador->sueldobase + $trabajador->bonos_fijos;

			$asig_familiar = $trabajador->asigfamiliar;

			if(!is_null($trabajador->idasigfamiliar)){ //BUSCA MONTO DE ASIGNACION FAMILIAR EN BASE A TRAMO SELECCIONADO
				$tramo_asig_familiar = $this->admin->get_tabla_asig_familiar($trabajador->idasigfamiliar);
				$asig_familiar += $tramo_asig_familiar->monto*$num_cargas;
			}


			/*$tramo_asig_familiar = $this->get_tabla_asig_familiar($trabajador->idasigfamiliar);
			foreach ($tabla_asig_familiar as $rango_asig_familiar) {

				if($monto_ingresos >= $rango_asig_familiar->desde && $monto_ingresos <= $rango_asig_familiar->hasta){
					
					$asig_familiar += $rango_asig_familiar->monto*$num_cargas;

					break;
				}
			}*/

			$suma_asig_familiar += $asig_familiar;


			#AGUINALDO INGRESADO EN LÍQUIDO.  SE NECESITA ALMACENAR EL BRUTO
			$aguinaldo_bruto = round($datos_remuneracion->aguinaldo*1.25,0);


			$gratificacion = 0;
			if($trabajador->tipogratificacion == 'SG'){
				$gratificacion = 0;
			}else if($trabajador->tipogratificacion == 'MF'){
				$gratificacion = $trabajador->gratificacion;
			}else if($trabajador->tipogratificacion == 'TL'){
				$monto_calculo_gratificacion = $sueldo_base_mes +  $bonos_imponibles + $monto_horas50 + $monto_horas100;
				//$gratificacion_esperada = round($sueldo_base_mes/4,0);

				$gratificacion_esperada = round($monto_calculo_gratificacion/4,0);


				$gratificacion = $gratificacion_esperada > $tope_legal_gratificacion ? $tope_legal_gratificacion : $gratificacion_esperada;
			}


			$total_haberes = $sueldo_base_mes + $gratificacion + $movilizacion_mes + $colacion_mes + $bonos_imponibles + $bonos_no_imponibles + $monto_horas50 + $monto_horas100 + $aguinaldo_bruto + $asig_familiar;
			$sueldo_imponible = $sueldo_base_mes + $gratificacion + $bonos_imponibles + $monto_horas50 + $monto_horas100 + $aguinaldo_bruto;

			$sueldo_no_imponible = $total_haberes - $sueldo_imponible;



			#CALCULA SUELDO SOBRE EL CUAL SE CALCULARÁN LAS IMPOSICIONES, CONSIDERANDO EL TOPE LEGAL
			$sueldo_imponible_imposiciones = $sueldo_imponible > $tope_imponible ? $tope_imponible : $sueldo_imponible;

			$cot_obligatoria = round($sueldo_imponible_imposiciones*$porc_cot_oblig,0);
			$comision_afp = round($sueldo_imponible_imposiciones*($porc_com_afp/100),0);
			$adic_afp = round($sueldo_imponible*($trabajador->adicafp/100),0);


			// SOLO SE PAGA POR 11 AÑOS
			$segcesantia = $trabajador->tipocontrato == 'I' && $trabajador->segcesantia == 1 && $trabajador->annos_afc <= 11 ? round($sueldo_imponible*0.006,0) : 0;


			$cot_salud_oblig = $trabajador->idisapre != 1 ? round($sueldo_imponible_imposiciones*0.07,0) : 0;

			if($trabajador->idisapre == 1){ //FONASA
				$salud_total = round($sueldo_imponible_imposiciones*0.07,0);
				$cot_fonasa = $trabajador->idisapre == 1 ? round($sueldo_imponible_imposiciones*0.064,0) : 0;
				$cot_inp = $trabajador->idisapre == 1 ? round($sueldo_imponible_imposiciones*0.006,0) : 0;				

				$dif_salud = $salud_total - ($cot_fonasa + $cot_inp);
				$cot_fonasa += $dif_salud; 

			}else{
				$cot_fonasa = 0;
				$cot_inp = 0;
			}




			if($trabajador->idisapre == 1){
				$adic_isapre = 0;
				$cot_adic_isapre = 0; // tributable
				$adic_salud = 0;					
			}else{
				$dif_isapre = round($trabajador->valorpactado*$parametros->uf,0) - $cot_salud_oblig;
				$adic_isapre = $dif_isapre > 0 ? $dif_isapre : 0;

				if($adic_isapre > 0){
					$tope_salud_tributable = round(($parametros->topeimponible*0.07)*$parametros->uf,0);
					$sobre_tope = ($cot_salud_oblig + $adic_isapre) - $tope_salud_tributable;
					if($sobre_tope > 0){ // nos pasamos del tope
						$cot_adic_isapre = $adic_isapre - $sobre_tope; // tributable
						$adic_salud = $sobre_tope;
					}else{
						$cot_adic_isapre = 0; // tributable
						$adic_salud = $adic_isapre;
					}

				}else{
						$cot_adic_isapre = 0; // tributable
						$adic_salud = 0;					
				}
			}

			$ahorrovol = 0;
			if($trabajador->tipoahorrovol == 'pesos'){
				$ahorrovol = $trabajador->ahorrovol;	
			}else if($trabajador->tipoahorrovol == 'porcentaje'){
				$ahorrovol = round($sueldo_imponible*($trabajador->ahorrovol/100),0);	
			}

			$cotapv = 0;
			//echo $trabajador->cotapv." - ". $parametros->uf . " -  ". $trabajador->tipocotapv."<br>";
			//print_r($parametros);
			//echo $parametros->uf; exit;
			if($trabajador->tipocotapv == 'pesos'){
				$cotapv = $trabajador->cotapv;	
			}else if($trabajador->tipocotapv == 'porcentaje'){
				$cotapv = round($sueldo_imponible*($trabajador->cotapv/100),0);	
			}else if($trabajador->tipocotapv == 'uf'){
				$cotapv = round($trabajador->cotapv*$parametros->uf,0);
			}


			$descuentos = round($valor_hora*$datos_remuneracion->horasdescuento,0);


			

			$base_tributaria = $sueldo_imponible - $cot_obligatoria - $comision_afp - $adic_afp - $segcesantia - $cot_salud_oblig - $cot_adic_isapre - $cot_fonasa - $cot_inp;

			$impuesto = 0;
			foreach ($tabla_impuesto as $rango) {
				//echo $base_tributaria." - ".$rango->desde." - ".$rango->hasta." - ".$rebaja."<br>";
				$desde = $rango->desde*$parametros->utm;
				$hasta = $rango->hasta*$parametros->utm;
				$rebaja = $rango->rebaja*$parametros->utm;


				$rango_desde = round(($desde/$diastrabajo)*$datos_remuneracion->diastrabajo,0);
				$rango_hasta = round(($hasta/$diastrabajo)*$datos_remuneracion->diastrabajo,0);
				$rango_rebaja = round(($rebaja/$diastrabajo)*$datos_remuneracion->diastrabajo,0);
				//if($base_tributaria >= $rango->desde && $base_tributaria <= $rango->hasta){
				if($base_tributaria >= $rango_desde && $base_tributaria <= $rango_hasta){
					
					//$impuesto = round($base_tributaria*$rango->factor - $rango->rebaja,0);
					$impuesto = round($base_tributaria*$rango->factor - $rango_rebaja,0);

					break;
				}
			}

			//exit;

			$datos_d = $this->get_haberes_descuentos($trabajador->id_personal,null,'DESCUENTO');			


			//$datos_descuentos = $this->get_descuento($idperiodo,'D',$trabajador->id);
			$datos_descuentos = array();
			$monto_descuento = 0;


			foreach ($datos_d as $info_descuento) {

					$monto_descuento += $info_descuento->monto;
					$data_bono = array(
								'idremuneracion' => $datos_remuneracion->id_remuneracion,
								'descripcion' => $info_descuento->nombre,
								'imponible' => $info_descuento->imponible,
								'monto' => $info_descuento->monto
								);
					$this->db->insert('rem_haber_descuento_remuneracion', $data_bono);
			}



			foreach ($datos_descuentos as $info_descuento) {
				$monto_descuento += $info_descuento->monto;
				if(!array_key_exists($info_descuento->tipodescuento,$array_descuentos)){
					$array_descuentos[$info_descuento->tipodescuento] = 0;
				}
				$array_descuentos[$info_descuento->tipodescuento] += $info_descuento->monto; // suma montos por tipo de descuento
			}


			//$datos_prestamos = $this->get_descuento($idperiodo,'P',$trabajador->id);
			$datos_prestamos = array();
			$monto_prestamos = 0;
			foreach ($datos_prestamos as $info_prestamos) {
				$monto_prestamos += $info_prestamos->monto;
				if(!array_key_exists($info_prestamos->tipodescuento,$array_prestamos)){
					$array_prestamos[$info_prestamos->tipodescuento] = 0;
				}
				$array_prestamos[$info_prestamos->tipodescuento] += $info_prestamos->monto; // suma montos por tipo de descuento				
			}



			$total_descuentos = $cot_obligatoria + $comision_afp + $adic_afp + $segcesantia + $cot_salud_oblig + $cot_fonasa + $cot_inp + $adic_isapre + $impuesto + $ahorrovol + $cotapv + $datos_remuneracion->anticipo + $descuentos + $monto_descuento + $monto_prestamos + $datos_remuneracion->aguinaldo;
			$total_leyes_sociales = $cot_obligatoria + $comision_afp + $adic_afp + $segcesantia + $cot_salud_oblig + $cot_fonasa + $cot_inp + $adic_isapre + $impuesto + $ahorrovol + $cotapv;
			$otros_descuentos = $total_descuentos - $total_leyes_sociales;			

			$sueldo_liquido = $total_haberes - $total_descuentos;

			if($trabajador->pensionado == 1){
				$seginvalidez = 0;
			}else{
				if($datos_remuneracion->diastrabajo < 30){

					$sueldo_calculo_sis = $trabajador->sueldobase + $aguinaldo_bruto + $bonos_imponibles;
				}else{
					$sueldo_calculo_sis = $sueldo_imponible;
				}

				$seginvalidez = round($sueldo_calculo_sis*($parametros->tasasis/100),0);

			}
			#$seginvalidez = $trabajador->pensionado == 1 ? 0 : round($sueldo_imponible*($parametros->tasasis/100),0);
			#SI TRABAJADOR TIENE LICENCIA MEDIDA, ENTONCES SE CALCULA POR SUELDO IMPONIBLE PROPORCIONAL A DIAS TRABAJADOS
			#Y POR DIAS NO TRABAJADOS, EL PROPORCIONAL AL SUELDO IMPONIBLE ANTEIOR.  SI NO EXISTE, EN BASE AL CONTRATO

			#1.- VERIFICAR SI TIENE LICENCIA EN EL PERÍODO
			$movimientos = $this->get_lista_movimientos($trabajador->id_personal,null,$idperiodo,3);
			//$movimientos = array();
			$tiene_licencia = count($movimientos) > 0 ? true : false;

			//ocupo esta query para sacar el ultimo sueldo imponible, sino tomar suedo base según contrato.
			/*select r.sueldoimponible from gc_remuneracion r
inner join gc_periodo p on r.id_periodo = p.id
where idpersonal = 41 and diastrabajo > 0
order by p.anno desc, p.mes desc
limit 1		*/	
			$aportesegcesantia = 0;
			if($trabajador->segcesantia == 1){
				if($trabajador->annos_afc <= 11){
					$aportesegcesantia = $trabajador->tipocontrato == 'F' ? round($sueldo_imponible*0.03,0) : round($sueldo_imponible*0.024,0);
				}else{
					$aportesegcesantia = $trabajador->tipocontrato == 'F' ? round($sueldo_imponible*0.002,0) : round($sueldo_imponible*0.008,0);
				}
			}else{
				$aportesegcesantia = 0;	
			}	
			//echo $aportesegcesantia; exit;

			if($tiene_licencia && $datos_remuneracion->diastrabajo < 30){ // SI TIENE LICENCIA SE DEBE SUMAR AL SEGURO LOS DÍAS NO TRABAJADOS POR EL PROPORCIONAL 
				$imponibles_no_trabajo = round((($trabajador->sueldobase + $aguinaldo_bruto + $bonos_imponibles + $gratificacion)/$diastrabajo)*(30-$datos_remuneracion->diastrabajo),0);
				if($trabajador->segcesantia == 1){
					if($trabajador->annos_afc <= 11){
						
						$aportesegcesantia += $trabajador->tipocontrato == 'F' ? round($imponibles_no_trabajo*0.03,0) : round($imponibles_no_trabajo*0.024,0);
					}else{
						$aportesegcesantia += $trabajador->tipocontrato == 'F' ? round($imponibles_no_trabajo*0.002,0) : round($imponibles_no_trabajo*0.008,0);
					}
				}else{
					$aportesegcesantia = 0;	
				}	

			}

							

			$aportepatronal = is_null($empresa->idmutual) ? 0 : round($sueldo_imponible*($empresa->porcmutual/100),0);
			$suma_aporte_patronal += $aportepatronal;
			$suma_impuesto += $impuesto;

			$data_remuneracion = array(
					'ufperiodo' => $parametros->uf,
					'sueldobase' => $sueldo_base_mes,
					'valorhora' => $valor_hora,
					'montodescuento' => $descuentos,
					'tipogratificacion' => $trabajador->tipogratificacion,
					'gratificacion' => $gratificacion,
					'movilizacion' => $movilizacion_mes,
					'colacion' => $colacion_mes,
					'bonosimponibles' => $bonos_imponibles,
					'bonosnoimponibles' => $bonos_no_imponibles,
					'valorhorasextras50' => $valor_hora50,
					'montohorasextras50' => $monto_horas50,
					'valorhorasextras100' => $valor_hora100,
					'montohorasextras100' => $monto_horas100,
					'aguinaldobruto' => $aguinaldo_bruto,
					'cargasretroactivas' => $trabajador->cargasretroactivas,
					'montocargaretroactiva' => $trabajador->asigfamiliar,
					'asigfamiliar' => $asig_familiar,
					'totalhaberes' => $total_haberes,
					'sueldoimponible' => $sueldo_imponible,
					'sueldonoimponible' => $sueldo_no_imponible,
					'sueldoimponibleimposiciones' => $sueldo_imponible_imposiciones,
					'cotizacionobligatoria' => $cot_obligatoria,
					'comisionafp' => $comision_afp,
					'porccomafp' => $porc_com_afp,
					'porcadicafp' => $trabajador->adicafp,					
					'adicafp' => $adic_afp,
					'segcesantia' => $segcesantia,					
					'cotizacionsalud' => $cot_salud_oblig,
					'fonasa' => $cot_fonasa,
					'inp' => $cot_inp,
					'valorpactado' => $trabajador->valorpactado,
					'adicisapre' => $adic_isapre,
					'cotadicisapre' => $cot_adic_isapre,
					'adicsalud' => $adic_salud,
					'basetributaria' => $base_tributaria,				
					'impuesto' => $impuesto,
					'tipoahorrovol' => $trabajador->tipoahorrovol,
					'ahorrovol' => $trabajador->ahorrovol,
					'montoahorrovol' => $ahorrovol,
					'tipocotapv' => $trabajador->tipocotapv,					
					'cotapv' => $trabajador->cotapv,					
					'montocotapv' => $cotapv,					
					'descuentos' => $monto_descuento,	
					'prestamos' => $monto_prestamos,
					'totalleyessociales' => $total_leyes_sociales,
					'otrosdescuentos' => $otros_descuentos,
					'totaldescuentos' => $total_descuentos,
					'sueldoliquido' => $sueldo_liquido,
					'seginvalidez' => $seginvalidez,
					'aportesegcesantia' => $aportesegcesantia,
					'aportepatronal' => $aportepatronal,
					'idcentrocosto' => $trabajador->idcentrocosto,
					'pdf_content' => null,				
					'active' => 1
				);

			$this->db->where('idpersonal', $datos_remuneracion->idpersonal);
			$this->db->where('id_periodo', $datos_remuneracion->id_periodo);
			$this->db->where('id_empresa', $this->session->userdata('empresaid'));
			$this->db->update('rem_remuneracion',$data_remuneracion); 	

			// VUELVE A CERO LA ASIGNACION FAMILIAR POR CARGAS RETROACTIVAS
			$this->db->where('id_personal', $trabajador->id_personal);
			$this->db->update('rem_personal',array('asigfamiliar' => 0,
												'cargasretroactivas' => 0)); 	


			// AGREGA CUENTA CON SUELDO LIQUIDO
			//$cuenta_sueldo = $sueldo_liquido - $datos_remuneracion->aguinaldo;
			$cuenta_sueldo = $sueldo_liquido;



       		//calculamos los montos destinados a afp
		}

 		// CERRAR PERIODO
		$this->db->where('id_periodo', $idperiodo);
		$this->db->where('id_empresa', $this->session->userdata('empresaid'));
		$this->db->where_in('id_centro_costo', $centro_costo);
		$this->db->update('rem_periodo_remuneracion',array('cierre' => date("Y-m-d H:i:s"))); 

		$this->db->trans_complete();
		return 1;
	}	


	public function get_periodos_cerrados($empresaid,$idperiodo = null,$idcentrocosto = null){
		$sql_centro_costo = is_null($idcentrocosto) ? '' : 'and pe.idcentrocosto = ' . $idcentrocosto;
		$sql_centro_costo_rem = is_null($idcentrocosto) ? '' : 'and r.idcentrocosto = ' . $idcentrocosto;


		$periodo_data = $this->db->select('p.id_periodo, p.mes, p.anno, pr.cierre, pr.aprueba, pr.cierre as cierre,  (select count(*) from rem_remuneracion r inner join rem_personal pe on r.idpersonal = pe.id_personal where r.id_periodo = p.id_periodo and pe.id_empresa = ' . $empresaid . ' and r.active = 1 ' . $sql_centro_costo_rem . ') as numtrabajadores, (select sum(sueldoimponible) from rem_remuneracion r inner join rem_personal pe on r.idpersonal = pe.id_personal where r.id_periodo = p.id_periodo and pe.id_empresa = ' . $empresaid . ' and r.active = 1 ' . $sql_centro_costo . ') as sueldoimponible, (select sum(sueldoliquido) from rem_remuneracion r inner join rem_personal pe on r.idpersonal = pe.id_personal where r.id_periodo = p.id_periodo and pe.id_empresa = ' . $empresaid . ' and r.active = 1 ' . $sql_centro_costo . ') as sueldoliquido ', false)
						  ->from('rem_periodo as p')
						  ->join('rem_periodo_remuneracion as pr','p.id_periodo = pr.id_periodo')
		                  ->where('pr.id_empresa', $empresaid)
		                  ->where('pr.cierre is not null')
		                  ->group_by('p.id_periodo, p.mes, p.anno, pr.cierre, pr.aprueba, pr.cierre')
		                  ->order_by('p.anno desc')
		                  ->order_by('p.mes desc');
		$periodo_data = is_null($idperiodo) ? $periodo_data : $periodo_data->where('pr.id_periodo',$idperiodo);
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		$datos = is_null($idperiodo) ? $query->result() : $query->row();				                  
		return $datos;

	}




	public function get_remuneraciones_by_periodo($idperiodo,$sinsueldo = null,$idcentrocosto = null){
		
		$periodo_data = $this->db->select('r.id_remuneracion, r.id_periodo, pe.id_personal as idtrabajador, p.mes, p.anno, pe.nombre, pe.apaterno, pe.amaterno, pe.sexo, pe.nacionalidad, pe.fecingreso as fecingreso, pe.rut, pe.dv, i.nombre as prev_salud, pe.idisapre, pe.valorpactado, c.nombre as cargo, a.id_afp as idafp, a.nombre as afp, a.porc, r.sueldobase, r.gratificacion, r.bonosimponibles, r.valorhorasextras50, r.montohorasextras50, r.valorhorasextras100, r.montohorasextras100, r.aguinaldo, r.aguinaldobruto, r.diastrabajo, r.totalhaberes, r.totaldescuentos, r.sueldoliquido, r.horasextras50, r.horasextras100, r.horasdescuento, pe.cargassimples, pe.cargasinvalidas, pe.cargasmaternales, pe.cargasretroactivas, r.sueldoimponible, r.movilizacion, r.colacion, r.bonosnoimponibles, r.asigfamiliar, r.totalhaberes, r.cotizacionobligatoria, r.comisionafp, r.adicafp, r.segcesantia, r.cotizacionsalud, r.fonasa, r.inp, r.adicisapre, r.cotadicisapre, r.adicsalud, r.impuesto, r.montoahorrovol, r.montocotapv, r.anticipo, r.montodescuento, pr.cierre, r.sueldonoimponible, r.totalleyessociales, r.otrosdescuentos, r.montocargaretroactiva, r.seginvalidez, pe.idasigfamiliar, r.valorpactado as valorpactadoperiodo, ap.id_apv as idapv, pe.nrocontratoapv, pe.formapagoapv, pe.depconvapv, co.idmutual, r.aportepatronal, co.idcaja, pe.segcesantia as afilsegcesantia, r.aportesegcesantia, r.sueldoimponibleimposiciones')
						  ->from('rem_periodo as p')
						  ->join('rem_remuneracion as r','r.id_periodo = p.id_periodo')
						  ->join('rem_personal as pe','pe.id_personal = r.idpersonal')
						  ->join('rem_empresa as co','pe.id_empresa = co.id_empresa')
						  ->join('rem_periodo_remuneracion as pr','r.id_periodo = pr.id_periodo and r.idcentrocosto = pr.id_centro_costo')
						  ->join('rem_isapre as i','pe.idisapre = i.id_isapre')
						  ->join('rem_cargos as c','pe.idcargo = c.id_cargos')
						  ->join('rem_afp as a','pe.idafp = a.id_afp')
						  ->join('rem_apv as ap','pe.instapv = ap.id_apv','left')						  
		                  ->where('pe.id_empresa', $this->session->userdata('empresaid'))
		                  ->where('pr.id_empresa', $this->session->userdata('empresaid'))
		                  ->where('r.id_periodo', $idperiodo)
		                  //->where('pe.idcentrocosto',1)
		                  ->where('r.active = 1')
		                  //->where('r.sueldoliquido <> 0')  //valida que se haya creado sueldo
		                  ->order_by('pe.nombre asc');

		$periodo_data = is_null($sinsueldo) ? $periodo_data->where('r.sueldoliquido <> 0') : $periodo_data;	
		$periodo_data = is_null($idcentrocosto) ? $periodo_data : $periodo_data->where('pe.idcentrocosto',$idcentrocosto);		                  
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		return $query->result();


	}	


	public function libro($datos_remuneracion){

			$this->load->library('PHPExcel');
	  	    $this->phpexcel->setActiveSheetIndex(0);
	        $sheet = $this->phpexcel->getActiveSheet();
	        $sheet->setTitle("libro_remuneraciones");




			$this->load->model('admin');
			$datos_empresa = $this->admin->datos_empresa($this->session->userdata('empresaid'));
			//echo "<pre>";
			//print_r($this->session->all_userdata());
			//print_r($datos_empresa); exit;

			/********* COMIENZA A CREAR EXCEL *******/
	        // DATOS INICIALES
			$sheet->getColumnDimension('A')->setWidth(5);


	        $sheet->mergeCells('B2:D2');
	        $sheet->setCellValue('B2', 'Libro Remuneraciones');
	        $sheet->getColumnDimension('B')->setWidth(20);
	        $sheet->setCellValue('B3', 'Nombre Empresa');
	        $sheet->setCellValue('C3',html_entity_decode($this->session->userdata('empresanombre')));
	        $sheet->mergeCells('C3:D3');
	        $sheet->setCellValue('B4', 'Rut Empresa');
	        $sheet->setCellValue('C4',number_format($datos_empresa->rut,0,".",".") . '-' .$datos_empresa->dv);	        
	        $sheet->mergeCells('C4:D4');
	        $sheet->setCellValue('B5', 'Direccion Empresa');
	        $sheet->setCellValue('C5',$datos_empresa->direccion.", ".$datos_empresa->comuna);	        	        
	        $sheet->mergeCells('C5:D5');
	        $sheet->setCellValue('B6', 'Fecha emision Reporte');
	        $sheet->setCellValue('C6',date('d/m/Y') );
	        $sheet->mergeCells('C6:D6');
	        
 
			$sheet->getStyle("B2:B6")->getFont()->setBold(true);
			$sheet->getStyle("B2:D6")->getFont()->setSize(10);    	

			//D7E4BC


			/****************** TABLA INICIAL ****************/

			/*************************todos los bordes internos *************************************/
			$sheet->getStyle("B2:D6")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


			/*************************bordes cuadro principal (externo) *************************************/
			$sheet->getStyle("B2:D2")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:D2")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
			$sheet->getStyle("B6:D6")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:B6")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:B6")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
			$sheet->getStyle("D2:D6")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		
			/**********************************************************************************************************/			        
				
			/***** COLOR TABLA ****************/
			$sheet->getStyle("B2:D2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$sheet->getStyle("B2:D2")->getFill()->getStartColor()->setRGB('FA8D72');

			$sheet->getStyle("B2:B6")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$sheet->getStyle("B2:B6")->getFill()->getStartColor()->setRGB('FA8D72');			


			$i = 8;



			//ENCABEZADO REPORTE

			 $sheet->getColumnDimension('B')->setWidth(10);
			 $sheet->setCellValue('B'.$i, '#');
			 $sheet->getColumnDimension('C')->setWidth(15);
			 $sheet->setCellValue('C'.$i, 'Rut');
			 $sheet->getColumnDimension('D')->setWidth(35);
			 $sheet->setCellValue('D'.$i, 'Nombre');
			 $sheet->getColumnDimension('E')->setWidth(15);
			 $sheet->setCellValue('E'.$i, 'Fecha Ingreso');
			 $sheet->getColumnDimension('F')->setWidth(15);			
			 $sheet->setCellValue('F'.$i, 'Sueldo Base');
			 $sheet->getColumnDimension('G')->setWidth(15);			
			 $sheet->setCellValue('G'.$i, 'Gratificación');	
			 $sheet->getColumnDimension('H')->setWidth(15);			
			 $sheet->setCellValue('H'.$i, 'Movilización');	
			 $sheet->getColumnDimension('I')->setWidth(15);			
			 $sheet->setCellValue('I'.$i, 'Colación');		
			 $sheet->getColumnDimension('J')->setWidth(15);			
			 $sheet->setCellValue('J'.$i, 'Bonos Imponibles');				 			 			 		 
			 $sheet->getColumnDimension('K')->setWidth(15);			
			 $sheet->setCellValue('K'.$i, 'Bonos No Imponibles');	
			 $sheet->getColumnDimension('L')->setWidth(15);			
			 $sheet->setCellValue('L'.$i, 'Horas Extras 50%');	
			 $sheet->getColumnDimension('M')->setWidth(15);			
			 $sheet->setCellValue('M'.$i, 'Horas Extras 100%');	
			 $sheet->getColumnDimension('N')->setWidth(15);			
			 $sheet->setCellValue('N'.$i, 'Aguinaldo');	
			 $sheet->getColumnDimension('O')->setWidth(15);			
			 $sheet->setCellValue('O'.$i, 'Asignación Familiar');		
			 $sheet->getColumnDimension('P')->setWidth(15);			
			 $sheet->setCellValue('P'.$i, 'Total Haberes');				 			 
			 $sheet->getColumnDimension('Q')->setWidth(15);			
			 $sheet->setCellValue('Q'.$i, 'Cotización Obligatoria');				 			 
			 $sheet->getColumnDimension('R')->setWidth(15);			
			 $sheet->setCellValue('R'.$i, 'Comisión AFP');				 			 
			 $sheet->getColumnDimension('S')->setWidth(15);			
			 $sheet->setCellValue('S'.$i, 'Adicional AFP');				 			 
			 $sheet->getColumnDimension('T')->setWidth(15);			
			 $sheet->setCellValue('T'.$i, 'Ahorro Voluntario');	
			 $sheet->getColumnDimension('U')->setWidth(15);			
			 $sheet->setCellValue('U'.$i, 'APV');	
			 $sheet->getColumnDimension('V')->setWidth(15);			
			 $sheet->setCellValue('V'.$i, 'Cotización Salud Obligatoria');	
			 $sheet->getColumnDimension('W')->setWidth(15);			
			 $sheet->setCellValue('W'.$i, 'Cotización Adicional Isapre');	
			 $sheet->getColumnDimension('X')->setWidth(15);			
			 $sheet->setCellValue('X'.$i, 'Adicional Salud');	
			 $sheet->getColumnDimension('Y')->setWidth(15);			
			 $sheet->setCellValue('Y'.$i, 'Fonasa');	
			 $sheet->getColumnDimension('Z')->setWidth(15);			
			 $sheet->setCellValue('Z'.$i, 'Seguro Cesantía');	
			 $sheet->getColumnDimension('AA')->setWidth(15);			
			 $sheet->setCellValue('AA'.$i, 'Impuesto');	
			 $sheet->getColumnDimension('AB')->setWidth(15);			
			 $sheet->setCellValue('AB'.$i, 'Total Leyes Sociales');	
			 $sheet->getColumnDimension('AC')->setWidth(15);			
			 $sheet->setCellValue('AC'.$i, 'Anticipo');	
			 $sheet->getColumnDimension('AD')->setWidth(15);			
			 $sheet->setCellValue('AD'.$i, 'Descuento por Aguinaldo');	
			 $sheet->getColumnDimension('AE')->setWidth(15);			
			 $sheet->setCellValue('AE'.$i, 'Horas Descuento');	
			 $sheet->getColumnDimension('AF')->setWidth(15);			
			 $sheet->setCellValue('AF'.$i, 'Otros Descuentos');	
			 $sheet->getColumnDimension('AG')->setWidth(15);			
			 $sheet->setCellValue('AG'.$i, 'Préstamos');	
			 $sheet->getColumnDimension('AH')->setWidth(15);			
			 $sheet->setCellValue('AH'.$i, 'Total Otros Descuentos');				 			 			 			 		
			 $sheet->getColumnDimension('AI')->setWidth(15);			
			 $sheet->setCellValue('AI'.$i, 'Aporte Seguro Cesantía');	 
			 $sheet->getColumnDimension('AJ')->setWidth(15);			
			 $sheet->setCellValue('AJ'.$i, 'Aporte SIS');	 
			 $sheet->getColumnDimension('AK')->setWidth(15);			
			 $sheet->setCellValue('AK'.$i, 'Mutual de Seguridad');	 
			 $sheet->getColumnDimension('AL')->setWidth(15);			
			 $sheet->setCellValue('AL'.$i, 'Total Aportes Patronales');	 



			 $columnaFinal = 37;
			 $mergeTotal = 38;
			 $columnaTotales = 37;
			 $sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setBold(true);
			 $i++;
			$filaInicio = $i-1; 
			
			//$sheet->getStyle("B7:I7")->getFont()->setSize(11);  
			$linea = 1;
            foreach ($datos_remuneracion as $remuneracion) {

            	//$datos_bonos_imponibles = $this->get_bonos_by_remuneracion($remuneracion->id,true);
            	$datos_bonos_imponibles = array();
            	$bonos_imponibles = 0;
            	foreach ($datos_bonos_imponibles as $bono_imponible) {
            		$bonos_imponibles += $bono_imponible->monto;
            	}


            	//$datos_bonos_no_imponibles = $this->get_bonos_by_remuneracion($remuneracion->id,false);
            	$datos_bonos_no_imponibles = array();
            	$bonos_no_imponibles = 0;
            	foreach ($datos_bonos_no_imponibles as $bono_no_imponible) {
            		$bonos_no_imponibles += $bono_no_imponible->monto;
            	}

				//$datos_descuentos = $this->get_descuento($remuneracion->idperiodo,'D',$remuneracion->idtrabajador);
				$datos_descuentos = array();
				$monto_descuento = 0;
            	foreach ($datos_descuentos as $dato_descuento) {
            		$monto_descuento += $dato_descuento->monto;
            	}           	

            	//$datos_prestamos = $this->get_descuento($remuneracion->idperiodo,'P',$remuneracion->idtrabajador);
            	$datos_prestamos = array();
            	$monto_prestamo = 0;
            	foreach ($datos_prestamos as $dato_prestamo) {
            		$monto_prestamo += $dato_prestamo->monto;
            	}   

            	$sheet->setCellValue("B".$i,$linea);
            	$sheet->setCellValue("C".$i,$remuneracion->rut."-".$remuneracion->dv);
            	$sheet->setCellValue("D".$i,$remuneracion->nombre." ".$remuneracion->apaterno." ".$remuneracion->amaterno);
            	$sheet->setCellValue("E".$i,$remuneracion->fecingreso);
            	$sheet->setCellValue("F".$i,$remuneracion->sueldobase);
            	$sheet->getStyle('F'.$i)->getNumberFormat()->setFormatCode('#,##0');
            	$sheet->setCellValue("G".$i,$remuneracion->gratificacion);
            	$sheet->getStyle('G'.$i)->getNumberFormat()->setFormatCode('#,##0');
            	$sheet->setCellValue("H".$i,$remuneracion->movilizacion);
            	$sheet->getStyle('H'.$i)->getNumberFormat()->setFormatCode('#,##0');
            	$sheet->setCellValue("I".$i,$remuneracion->colacion);
            	$sheet->getStyle('I'.$i)->getNumberFormat()->setFormatCode('#,##0');
            	$sheet->setCellValue("J".$i,$bonos_imponibles);
            	$sheet->getStyle('J'.$i)->getNumberFormat()->setFormatCode('#,##0');
            	$sheet->setCellValue("K".$i,$bonos_no_imponibles);
            	$sheet->getStyle('K'.$i)->getNumberFormat()->setFormatCode('#,##0');
            	$sheet->setCellValue("L".$i,$remuneracion->montohorasextras50);
            	$sheet->getStyle('L'.$i)->getNumberFormat()->setFormatCode('#,##0');
            	$sheet->setCellValue("M".$i,$remuneracion->montohorasextras100);
            	$sheet->getStyle('M'.$i)->getNumberFormat()->setFormatCode('#,##0');
            	$sheet->setCellValue("N".$i,$remuneracion->aguinaldobruto);
            	$sheet->getStyle('N'.$i)->getNumberFormat()->setFormatCode('#,##0');
            	$sheet->setCellValue("O".$i,$remuneracion->asigfamiliar);
            	$sheet->getStyle('O'.$i)->getNumberFormat()->setFormatCode('#,##0');      
            	$sheet->setCellValue("P".$i,$remuneracion->totalhaberes);
            	$sheet->getStyle('P'.$i)->getNumberFormat()->setFormatCode('#,##0');
            	$sheet->setCellValue("Q".$i,$remuneracion->cotizacionobligatoria);
            	$sheet->getStyle('Q'.$i)->getNumberFormat()->setFormatCode('#,##0'); 
            	$sheet->setCellValue("R".$i,$remuneracion->comisionafp);
            	$sheet->getStyle('R'.$i)->getNumberFormat()->setFormatCode('#,##0'); 
            	$sheet->setCellValue("S".$i,$remuneracion->adicafp);
            	$sheet->getStyle('S'.$i)->getNumberFormat()->setFormatCode('#,##0'); 
            	$sheet->setCellValue("T".$i,$remuneracion->montoahorrovol);
            	$sheet->getStyle('T'.$i)->getNumberFormat()->setFormatCode('#,##0'); 
            	$sheet->setCellValue("U".$i,$remuneracion->montocotapv);
            	$sheet->getStyle('U'.$i)->getNumberFormat()->setFormatCode('#,##0'); 
            	$sheet->setCellValue("V".$i,$remuneracion->cotizacionsalud);
            	$sheet->getStyle('V'.$i)->getNumberFormat()->setFormatCode('#,##0'); 
            	$sheet->setCellValue("W".$i,$remuneracion->cotadicisapre);
            	$sheet->getStyle('W'.$i)->getNumberFormat()->setFormatCode('#,##0'); 
            	$sheet->setCellValue("X".$i,$remuneracion->adicsalud);
            	$sheet->getStyle('X'.$i)->getNumberFormat()->setFormatCode('#,##0'); 
            	$sheet->setCellValue("Y".$i,$remuneracion->fonasa + $remuneracion->inp);
            	$sheet->getStyle('Y'.$i)->getNumberFormat()->setFormatCode('#,##0'); 
            	$sheet->setCellValue("Z".$i,$remuneracion->segcesantia);
            	$sheet->getStyle('Z'.$i)->getNumberFormat()->setFormatCode('#,##0'); 
            	$sheet->setCellValue("AA".$i,$remuneracion->impuesto);
            	$sheet->getStyle('AA'.$i)->getNumberFormat()->setFormatCode('#,##0'); 
            	$sheet->setCellValue("AB".$i,$remuneracion->totalleyessociales);
            	$sheet->getStyle('AB'.$i)->getNumberFormat()->setFormatCode('#,##0'); 
            	$sheet->setCellValue("AC".$i,$remuneracion->anticipo);
            	$sheet->getStyle('AC'.$i)->getNumberFormat()->setFormatCode('#,##0'); 
            	$sheet->setCellValue("AD".$i,$remuneracion->aguinaldo);
            	$sheet->getStyle('AD'.$i)->getNumberFormat()->setFormatCode('#,##0'); 
            	$sheet->setCellValue("AE".$i,$remuneracion->montodescuento);
            	$sheet->getStyle('AE'.$i)->getNumberFormat()->setFormatCode('#,##0'); 
            	$sheet->setCellValue("AF".$i,$monto_descuento);
            	$sheet->getStyle('AF'.$i)->getNumberFormat()->setFormatCode('#,##0'); 
            	$sheet->setCellValue("AG".$i,$monto_prestamo);
            	$sheet->getStyle('AG'.$i)->getNumberFormat()->setFormatCode('#,##0'); 
            	$sheet->setCellValue("AH".$i,$remuneracion->otrosdescuentos);
            	$sheet->getStyle('AH'.$i)->getNumberFormat()->setFormatCode('#,##0');             	            	            	
            	$sheet->setCellValue("AI".$i,$remuneracion->aportesegcesantia);
            	$sheet->getStyle('AI'.$i)->getNumberFormat()->setFormatCode('#,##0');  
            	$sheet->setCellValue("AJ".$i,$remuneracion->seginvalidez);
            	$sheet->getStyle('AJ'.$i)->getNumberFormat()->setFormatCode('#,##0');  
            	$sheet->setCellValue("AK".$i,$remuneracion->aportepatronal);
            	$sheet->getStyle('AK'.$i)->getNumberFormat()->setFormatCode('#,##0');  
            	$sheet->setCellValue("AL".$i,$remuneracion->aportesegcesantia + $remuneracion->seginvalidez + $remuneracion->aportepatronal);
            	$sheet->getStyle('AL'.$i)->getNumberFormat()->setFormatCode('#,##0');              	            	            	

	 			if($i % 2 != 0){
	 				//echo "consulta 4: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
					$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('F7F9FD');	  				
	 			}	            	
            	$i++;
            	$linea++;
              }
             $i--;



			         	
			$sheet->getStyle("B" . $filaInicio . ":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setSize(10);

			/*************************todos los bordes internos *************************************/
			$sheet->getStyle("B".$filaInicio.":".ordenLetrasExcel($columnaFinal).$i)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


			/*************************bordes cuadro principal (externo) *************************************/
					for($j=1;$j<=$columnaFinal;$j++){ //borde superior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					}
			
					for($j=1;$j<=$columnaFinal;$j++){ //borde inferior
						$sheet->getStyle(ordenLetrasExcel($j).$i)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					}
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde izquierdo
						$sheet->getStyle("B".$n)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					}
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle(ordenLetrasExcel($columnaFinal).$n)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					}
			
			/**********************************************************************************************************/			        
				

			/***************************** Segundo borde superior********************************************************/
			
					for($j=1;$j<=$columnaFinal;$j++){ //borde inferior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					}
			
			/******************************************************************************************************/
			

		/***************************** Penultimo borde izquierdo ********************************************************/
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle("B".$n)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					}
			
		/******************************************************************************************************/			



		/***************************** Penultimo borde derecho ********************************************************/
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle(ordenLetrasExcel($columnaFinal).$n)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					}
			
		/******************************************************************************************************/			

			/***************************** Color fila superior********************************************************/
			
					for($j=1;$j<=$columnaFinal;$j++){ //color fondo inferior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getFill()->getStartColor()->setRGB('E8EDFF');
					}
			
			/******************************************************************************************************/


		/***************************** Color primera columna ********************************************************/
						$sheet->getStyle("B".$filaInicio.":B".$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
						$sheet->getStyle("B".$filaInicio.":B".$i)->getFill()->getStartColor()->setRGB('E8EDFF');
			
			/******************************************************************************************************/


		/***************************** Color montos ********************************************************/

						$sheet->getStyle("P".$filaInicio.":P".$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
						$sheet->getStyle("P".$filaInicio.":P".$i)->getFill()->getStartColor()->setRGB('E8EDFF');
	
						$sheet->getStyle("AB".$filaInicio.":AB".$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
						$sheet->getStyle("AB".$filaInicio.":AB".$i)->getFill()->getStartColor()->setRGB('E8EDFF');	

						$sheet->getStyle("AH".$filaInicio.":AH".$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
						$sheet->getStyle("AH".$filaInicio.":AH".$i)->getFill()->getStartColor()->setRGB('E8EDFF');									
						$sheet->getStyle("AL".$filaInicio.":AL".$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
						$sheet->getStyle("AL".$filaInicio.":AL".$i)->getFill()->getStartColor()->setRGB('E8EDFF');											
			/******************************************************************************************************/


			$sheet->setSelectedCells('E1'); //celda seleccionada



	        header("Content-Type: application/vnd.ms-excel");
	        $nombreArchivo = 'libro_remuneraciones';
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



public function get_remuneraciones_by_id($idremuneracion){
		$periodo_data = $this->db->select('r.id_remuneracion, r.id_periodo, pe.id_personal as idtrabajador, p.mes, p.anno, pe.nombre, pe.apaterno, pe.amaterno, pe.fecingreso as fecingreso, pe.rut, pe.dv, i.nombre as prev_salud, pe.idisapre, pe.valorpactado, c.nombre as cargo, a.nombre as afp, a.porc, r.sueldobase, r.gratificacion, r.bonosimponibles, r.valorhorasextras50, r.montohorasextras50, r.valorhorasextras100, r.montohorasextras100, r.aguinaldo, r.aguinaldobruto, r.diastrabajo, r.totalhaberes, r.totaldescuentos, r.sueldoliquido, r.horasextras50, r.horasextras100, r.horasdescuento, pe.cargassimples, pe.cargasinvalidas, pe.cargasmaternales, pe.cargasretroactivas, r.sueldoimponible, r.movilizacion, r.colacion, r.bonosnoimponibles, r.asigfamiliar, r.totalhaberes, r.cotizacionobligatoria, r.comisionafp, r.adicafp, r.segcesantia, r.cotizacionsalud, r.fonasa, r.inp, r.adicisapre, r.cotadicisapre, r.adicsalud, r.impuesto, r.montoahorrovol, r.montocotapv, r.anticipo, r.montodescuento, pr.cierre, r.sueldonoimponible, r.totalleyessociales, r.otrosdescuentos, r.descuentos, r.prestamos, pr.id_periodo, pr.cierre, pr.aprueba')
						  ->from('rem_periodo as p')
						  ->join('rem_remuneracion as r','r.id_periodo = p.id_periodo')
						  ->join('rem_personal as pe','pe.id_personal = r.idpersonal')
						  ->join('rem_periodo_remuneracion as pr','r.id_periodo = pr.id_periodo and pr.id_empresa = ' . $this->session->userdata('empresaid'))
						  ->join('rem_isapre as i','pe.idisapre = i.id_isapre')
						  ->join('rem_cargos as c','pe.idcargo = c.id_cargos')
						  ->join('rem_afp as a','pe.idafp = a.id_afp')
		                  ->where('pe.id_empresa', $this->session->userdata('empresaid'))
		                  ->where('r.id_remuneracion', $idremuneracion);

		$query = $this->db->get();
		return $query->row();


	}	



	private function get_pdf_content($idremuneracion){

		$this->db->select('pdf_content ')
						  ->from('rem_remuneracion ')
						  ->where('id_remuneracion',$idremuneracion);
		$query = $this->db->get();
		return $query->row();
	}



	public function liquidacion($datos_remuneracion){

			$this->load->model('admin');
			$datos_empresa = $this->admin->datos_empresa($this->session->userdata('empresaid'));
			$content = $this->get_pdf_content($datos_remuneracion->id_remuneracion);
			
			$datos_periodo = $this->admin->get_periodo_by_id($datos_remuneracion->id_periodo);





			if($content->pdf_content == ''){ // EN CASO QUE POR ALGUN MOTIVO FALLARA LA EJECUCION INICIAL, SE CREA AHORA
				$this->generar_contenido_comprobante($datos_remuneracion);
				$content = $this->get_pdf_content($datos_remuneracion->id_remuneracion);
			}

			//Variable para PDF 

			

		
			$this->load->library("mpdf");
			$this->mpdf->mPDF(
				'',    // mode - default ''
				'',    // format - A4, for example, default ''
				8,     // font size - default 0
				'',    // default font family
				10,    // margin_left
				5,    // margin right
				16,    // margin top
				16,    // margin bottom
				9,     // margin header
				9,     // margin footer
				'L'    // L - landscape, P - portrait
				);
			  
			//echo $html; exit;
			$this->mpdf->SetTitle('Is RRHH - Liquidación de Sueldos');
			$this->mpdf->SetHeader('Empresa '. $datos_empresa->nombre . ' - ' .$datos_empresa->comuna . ' - RUT: ' .number_format($datos_empresa->rut,0,".",".") . '-' .$datos_empresa->dv);
			$this->mpdf->WriteHTML($content->pdf_content);

			if(is_null($datos_periodo->aprueba)){
				$this->mpdf->SetWatermarkText('BORRADOR');
				$this->mpdf->watermark_font = 'DejaVuSansCondensed';
				$this->mpdf->showWatermarkText = true;
			}




			//$this->mpdf->SetFooter('Para más información visite: http://www.tugastocomun.cl');


			// SE ALMACENA EL ARCHIVO
			$nombre_archivo = date("Y")."_".date("m")."_".date("d")."_sueldos_".$datos_remuneracion->id.".pdf";
			$this->mpdf->Output($nombre_archivo, "I");
			
	}	


public function generar_contenido_comprobante($datos_remuneracion){


			$html = '<html>
					<head>
					<style type="text/css">
					.rounded {
					 border:0.1mm solid #220044;
					 background-color: #FAFAFA;
					 background-clip: border-box;
					 padding: 1em;
						}

					.recto {
					 border:0.1mm solid #000000;
					 background-color: #FAFAFA;
					 background-clip: border-box;
					 padding: 1em;
						}


					.tableClass { 
						background-color: #ffffff; 
						border-collapse: collapse;
						font-family: DejaVuSansCondensed;
						font-size: 9pt; 
						line-height: 1.2;
						margin-top: 2pt; 
						margin-bottom: 5pt; 
						width: 70%;
						topntail: 0.02cm solid #ffffff; 
					}

					.theadClass { 
						vertical-align: bottom; 
					}

					.tdClass { 
						padding-left: 4mm; 
						vertical-align: top; 
						text-align:left;
						padding-right: 4mm; 
						padding-top: 0.5mm; 
						padding-bottom: 0.5mm;
						border-top: 1px solid #FFFFFF; 
					}

					.tdClassCenter { 
						padding-left: 4mm; 
						vertical-align: top; 
						text-align:center;
						padding-right: 4mm; 
						padding-top: 0.5mm; 
						padding-bottom: 0.5mm;
						border-top: 1px solid #FFFFFF; 
					}					

					.tdClassNumber { 
						text-align:right;
					}

					.headerRow td, .headerRow th { 
						background-gradient: linear #E6B4AA #ffffff 0 1 0 0.2; 
						padding: 1mm; 
						text-align: left;
					}	

					.header4 { 
						font-weight: ; 
						font-size: 13pt; 
						color: #080636;
						font-family: DejaVuSansCondensed, sans-serif; 
						margin-top: 10pt; 
						margin-bottom: 7pt;
						text-align: center;
						margin-collapse:collapse; page-break-after:avoid; }										
					</style>
			</head>
					<body>';


			$monto_prevision = $datos_remuneracion->idisapre == 1 ? ' 7% ' : $datos_remuneracion->valorpactado . ' UF ';
			$html .= '
						<p><h4 class="header4">Liquidaci&oacute;n de Remuneraciones ' . date2string($datos_remuneracion->mes,$datos_remuneracion->anno) . '<!--br><br><img src="img/logo4_1_80p_color.png" width="100px"--></h4></p>
						<hr>
						<br>
						<div class="recto">
						<table class="" width="100%"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th width="100%" colspan="4"><p>Datos Trabajador</p></th>
						</tr>
						</thead>
						<tbody>
						<tr>
						<td class="tdClass" ><b><i>Nombre:</i></b></td>
						<td class="tdClass" >' . $datos_remuneracion->nombre. ' ' . $datos_remuneracion->apaterno . ' ' . $datos_remuneracion->amaterno . '</td>
						<td class="tdClass" ><b><i>Fecha Contrato:</i></b></td>
						<td class="tdClass" >' . $datos_remuneracion->fecingreso . '</td>						
						</tr>
						<tr>
						<td class="tdClass" ><b><i>Rut:</i></b></td>
						<td class="tdClass" >' . number_format($datos_remuneracion->rut,0,".","."). '-' . $datos_remuneracion->dv . '</td>
						<td class="tdClass" ><b><i>Previsi&oacute;n Salud:</i></b></td>
						<td class="tdClass" >' . $datos_remuneracion->prev_salud . ' ' . $monto_prevision . ' </td>						
						</tr>
						<tr>
						<td class="tdClass" ><b><i>Cargo:</i></b></td>
						<td class="tdClass" >' . $datos_remuneracion->cargo . '</td>
						<td class="tdClass" ><b><i>AFP:</i></b></td>
						<td class="tdClass" >' . $datos_remuneracion->afp . ' ' . $datos_remuneracion->porc . '% </td>						
						</tr>
						</tbody>
						</table>
						</div>
						<br>
						<div class="recto">
						<table class="" width="100%"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th width="100%" colspan="4"><p>Datos Complementarios</p></th>
						</tr>
						</thead>
						<tbody>
						<tr>
						<td class="tdClass" ><b><i>Nro. d&iacute;as trabajados:</i></b></td>
						<td class="tdClass" >' . $datos_remuneracion->diastrabajo . '</td>
						<td class="tdClass" ><b><i>Horas Extras 50%:</i></b></td>
						<td class="tdClass" >' . round($datos_remuneracion->horasextras50,1) . ' </td>						
						</tr>
						<tr>
						<td class="tdClass" ><b><i>Horas Descuento:</i></b></td>
						<td class="tdClass" >' . $datos_remuneracion->horasdescuento . ' </td>						
						<td class="tdClass" ><b><i>Horas Extras 100%:</i></b></td>
						<td class="tdClass" >' . round($datos_remuneracion->horasextras100,1) . ' </td>						
						</tr>
						</tbody>
						</table>
						</div>
						<br>
						<div class="recto">
						<table class="" width="100%"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th width="70%" ><p>Detalle Haberes</p></th>
						<th width="30%" ><p>&nbsp;</p></th>
						</tr>
						</thead>
						<tbody>';

						

						if($datos_remuneracion->sueldobase > 0){
							$html .= '<tr>
									<td class="tdClass" >Sueldo Base</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->sueldobase,0,".",".") . '</td>
									</tr>';
						}

						if($datos_remuneracion->gratificacion > 0){
							$html .= '<tr>
									<td class="tdClass" >Gratificaci&oacute;n Legal</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->gratificacion,0,".",".") . '</td>
									</tr>';
						}						


						//$datos_bonos_imponibles = $this->get_bonos_by_remuneracion($datos_remuneracion->id,true);

						$datos_hd = $this->get_haberes_descuentos($datos_remuneracion->idtrabajador,true,'HABER');

						foreach ($datos_hd as $bono_imponible) {

							$html .= '<tr>
									<td class="tdClass" >' . $bono_imponible->nombre . '</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($bono_imponible->monto,0,".",".") . '</td>
									</tr>';

						}

						//$datos_bonos_imponibles = array();

						/*foreach ($datos_bonos_imponibles as $bono_imponible) {

							$html .= '<tr>
									<td class="tdClass" >' . $bono_imponible->descripcion . '</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($bono_imponible->monto,0,".",".") . '</td>
									</tr>';

						}*/

						/*if($datos_remuneracion->bonosimponibles > 0){
							$html .= '<tr>
									<td class="tdClass" >Bonos Imponibles</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->bonosimponibles,0,".",".") . '</td>
									</tr>';
						}*/												
						
						if($datos_remuneracion->montohorasextras50 > 0){
							$html .= '<tr>
									<td class="tdClass" >Horas Extras (50%)</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->montohorasextras50,0,".",".") . '</td>
									</tr>';
						}																		

						if($datos_remuneracion->montohorasextras100 > 0){
							$html .= '<tr>
									<td class="tdClass" >Horas Extras (100%)</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->montohorasextras100,0,".",".") . '</td>
									</tr>';
						}																								

						if($datos_remuneracion->aguinaldo > 0){
							$html .= '<tr>
									<td class="tdClass" >Aguinaldo</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->aguinaldobruto,0,".",".") . '</td>
									</tr>';
						}	

						if($datos_remuneracion->sueldoimponible > 0){
							$html .= '<tr>
									<td class="tdClass" ><b>Total Imponible</b></td>
									<td class="tdClass tdClassNumber" ><b>$ ' . number_format($datos_remuneracion->sueldoimponible,0,".",".") . '</b></td>
									</tr>';
						}

				$html .= '<tr>
						<td class="tdClass">&nbsp;</td>
						<td class="tdClass">&nbsp;</td>
						</tr>';

						if($datos_remuneracion->movilizacion > 0){
							$html .= '<tr>
									<td class="tdClass" >Movilizaci&oacute;n</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->movilizacion,0,".",".") . '</td>
									</tr>';
						}

						if($datos_remuneracion->colacion > 0){
							$html .= '<tr>
									<td class="tdClass" >Colaci&oacute;n</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->colacion,0,".",".") . '</td>
									</tr>';
						}


						if($datos_remuneracion->asigfamiliar > 0){
							$html .= '<tr>
									<td class="tdClass" >Asignaci&oacute;n Familiar</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->asigfamiliar,0,".",".") . '</td>
									</tr>';
						}

						/*if($datos_remuneracion->bonosnoimponibles > 0){
							$html .= '<tr>
									<td class="tdClass" >Bonos No Imponibles</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->bonosnoimponibles,0,".",".") . '</td>
									</tr>';
						}*/

						//$datos_bonos_no_imponibles = $this->get_bonos_by_remuneracion($datos_remuneracion->id,false);


						$datos_hd = $this->get_haberes_descuentos($datos_remuneracion->idtrabajador,false,'HABER');

						foreach ($datos_hd as $bono_no_imponible) {

							$html .= '<tr>
									<td class="tdClass" >' . $bono_no_imponible->nombre . '</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($bono_no_imponible->monto,0,".",".") . '</td>
									</tr>';

						}


						/*$datos_bonos_no_imponibles = array();

						foreach ($datos_bonos_no_imponibles as $bono_no_imponible) {

							$html .= '<tr>
									<td class="tdClass" >' . $bono_no_imponible->descripcion . '</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($bono_no_imponible->monto,0,".",".") . '</td>
									</tr>';

						}	*/											

						if($datos_remuneracion->sueldonoimponible > 0){
							$html .= '<tr>
									<td class="tdClass" ><b>Total No Imponible</b></td>
									<td class="tdClass tdClassNumber" ><b>$ ' . number_format($datos_remuneracion->sueldonoimponible,0,".",".") . '</b></td>
									</tr>';
						}


						if($datos_remuneracion->totalhaberes > 0){
							$html .= '<tr>
									<td class="tdClass" ><b>Total Haberes</b></td>
									<td class="tdClass tdClassNumber" ><b>$ ' . number_format($datos_remuneracion->totalhaberes,0,".",".") . '</b></td>
									</tr>';
						}

				$html.=	'</tbody>
						</table>
						</div>
						<br>
						<div class="recto">
						<table class="" width="100%"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th width="70%" ><p>Detalle Descuentos</p></th>
						<th width="30%" ><p>&nbsp;</p></th>
						</tr>
						</thead>
						<tbody>';

						if($datos_remuneracion->cotizacionobligatoria > 0){
							$html .= '<tr>
									<td class="tdClass" >Cotizaci&oacute;n AFP Obligatoria</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->cotizacionobligatoria,0,".",".") . '</td>
									</tr>';
						}

						if($datos_remuneracion->comisionafp > 0){
							$html .= '<tr>
									<td class="tdClass" >Comisi&oacute;n AFP</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->comisionafp,0,".",".") . '</td>
									</tr>';
						}						

						if($datos_remuneracion->adicafp > 0){
							$html .= '<tr>
									<td class="tdClass" >Adicional AFP</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->adicafp,0,".",".") . '</td>
									</tr>';
						}						


						if($datos_remuneracion->montoahorrovol > 0){
							$html .= '<tr>
									<td class="tdClass" >Ahorro Voluntario</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->montoahorrovol,0,".",".") . '</td>
									</tr>';
						}


						if($datos_remuneracion->montocotapv > 0){
							$html .= '<tr>
									<td class="tdClass" >APV</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->montocotapv,0,".",".") . '</td>
									</tr>';
						}

						if($datos_remuneracion->cotizacionsalud > 0){
							$html .= '<tr>
									<td class="tdClass" >Cotizaci&oacute;n Salud Obligatoria</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->cotizacionsalud,0,".",".") . '</td>
									</tr>';
						}																		

						if($datos_remuneracion->cotadicisapre > 0){
							$html .= '<tr>
									<td class="tdClass" >Cotizaci&oacute;n Adicional Isapre</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->cotadicisapre,0,".",".") . '</td>
									</tr>';
						}																														


						if($datos_remuneracion->adicsalud > 0){
							$html .= '<tr>
									<td class="tdClass" >Adicional Salud</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->adicsalud,0,".",".") . '</td>
									</tr>';
						}																								


						if(($datos_remuneracion->fonasa + $datos_remuneracion->inp) > 0){
							$html .= '<tr>
									<td class="tdClass" >Fonasa</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->fonasa+$datos_remuneracion->inp,0,".",".") . '</td>
									</tr>';


						}


						if($datos_remuneracion->segcesantia > 0){
							$html .= '<tr>
									<td class="tdClass" >Seguro de Cesant&iacute;a</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->segcesantia,0,".",".") . '</td>
									</tr>';
						}	


						if($datos_remuneracion->impuesto > 0){
							$html .= '<tr>
									<td class="tdClass" >Impuesto</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->impuesto,0,".",".") . '</td>
									</tr>';
						}



						if($datos_remuneracion->totalleyessociales > 0){
							$html .= '<tr>
									<td class="tdClass" ><b>Total Leyes Sociales</b></td>
									<td class="tdClass tdClassNumber" ><b>$ ' . number_format($datos_remuneracion->totalleyessociales,0,".",".") . '</b></td>
									</tr>';
						}

						$html .= '<tr>
								<td class="tdClass">&nbsp;</td>
								<td class="tdClass">&nbsp;</td>
								</tr>';						





						if($datos_remuneracion->anticipo > 0){
							$html .= '<tr>
									<td class="tdClass" >Anticipo</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->anticipo,0,".",".") . '</td>
									</tr>';
						}


						if($datos_remuneracion->aguinaldo > 0){
							$html .= '<tr>
									<td class="tdClass" >Descuento por Aguinaldo</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->aguinaldo,0,".",".") . '</td>
									</tr>';
						}							


						if($datos_remuneracion->montodescuento > 0){
							$html .= '<tr>
									<td class="tdClass" >Horas Descuento</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->montodescuento,0,".",".") . '</td>
									</tr>';
						}

						//$datos_descuentos = $this->get_descuento($datos_remuneracion->idperiodo,'D',$datos_remuneracion->idtrabajador);


						$datos_d = $this->get_haberes_descuentos($datos_remuneracion->idtrabajador,null,'DESCUENTO');

						foreach ($datos_d as $info_descuento) {

							$html .= '<tr>
									<td class="tdClass" >' . $info_descuento->nombre . '</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($info_descuento->monto,0,".",".") . '</td>
									</tr>';

						}

						/*$datos_descuentos = array();



						foreach ($datos_descuentos as $info_descuento) {

							$html .= '<tr>
									<td class="tdClass" >' . $info_descuento->descripcion . '</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($info_descuento->monto,0,".",".") . '</td>
									</tr>';

						}*/


						//$datos_prestamos = $this->get_descuento($datos_remuneracion->idperiodo,'P',$datos_remuneracion->idtrabajador);

						$datos_prestamos = array();

						foreach ($datos_prestamos as $info_prestamos) {

							$html .= '<tr>
									<td class="tdClass" >' . $info_prestamos->descripcion . '</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($info_prestamos->monto,0,".",".") . '</td>
									</tr>';

						}


						if($datos_remuneracion->otrosdescuentos > 0){
							$html .= '<tr>
									<td class="tdClass" ><b>Total Otros Descuentos</b></td>
									<td class="tdClass tdClassNumber" ><b>$ ' . number_format($datos_remuneracion->otrosdescuentos,0,".",".") . '</b></td>
									</tr>';
						}

						if($datos_remuneracion->totaldescuentos > 0){
							$html .= '<tr>
									<td class="tdClass" ><b>Total Descuentos</b></td>
									<td class="tdClass tdClassNumber" ><b>$ ' . number_format($datos_remuneracion->totaldescuentos,0,".",".") . '</b></td>
									</tr>';
						}

				$html.=	'</tbody>
						</table>
						</div>
						<br>
						<div class="recto">
						<table class="" width="100%"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th width="70%" ><p>L&iacute;quido a Pagar (Total Haberes - Total Descuentos)</p></th>
						<th width="30%" class="tdClassNumber" style="text-align: right;"><b>$ ' . number_format($datos_remuneracion->sueldoliquido,0,".",".") . '</b></th>
						</tr>
						</thead>
						</table>
						</div>
						<hr>
						<p style="text-align:left;font-size: 12px;" ><b>Son: '.valorEnLetras($datos_remuneracion->sueldoliquido).'</b></p>
						<br>
						<table width="100%" border="0">
							<tr>
								<td width="10%">&nbsp;</td>
								<td width="20%" style="border-bottom:1pt solid black;">&nbsp;</td>
								<td width="40%">&nbsp;</td>
								<td width="20%" style="border-bottom:1pt solid black;">&nbsp;</td>
								<td width="10%">&nbsp;</td>
							</tr>
							<tr>
								<td width="10%">&nbsp;</td>
								<td width="20%" style="text-align:center">Firma Trabajador</td>
								<td width="40%">&nbsp;</td>
								<td width="20%" style="text-align:center">Firma Empleador</td>
								<td width="10%">&nbsp;</td>
							</tr>							
						</table>

		';

			$html .=	"</body>
						</html>";

							//echo $html; exit;

						//echo "<pre>";
						//print_r($datos_remuneracion);
				
				$this->db->where('id_remuneracion',$datos_remuneracion->id_remuneracion);
				$this->db->update('rem_remuneracion', array('pdf_content' => $html));			
				//echo $this->db->last_query();

	}	



public function get_lista_movimientos($idpersonal = null,$idmovimiento = null,$idperiodo = null,$tipomovimiento = null){


		#SI BUSCO POR PERIODO
		if(!is_null($idperiodo)){
			$datos_periodo = $this->get_periodos($this->session->userdata('empresaid'),$idperiodo);
			$mes = $datos_periodo->mes;
			$anno = $datos_periodo->anno;
		}

		$movimiento_data = $this->db->select('lm.id, lm.idmovimiento, mp.nombre as movimiento, lm.fecmovimiento, lm.fechastamovimiento, lm.comentario, mp.rango, mp.codprevired')
						  ->from('rem_lista_movimiento_personal lm')
						  ->join('rem_movimientos_personal mp','lm.idmovimiento = mp.id')
						  ->join('rem_personal p','lm.idpersonal = p.id_personal')
						  ->where('lm.idpersonal',$idpersonal)
						  ->where('lm.active',1)
						  ->where('p.id_empresa',$this->session->userdata('empresaid'))
		                  ->order_by('lm.fecmovimiento','asc');

		$movimiento_data = is_null($idmovimiento) ? $movimiento_data : $movimiento_data->where('lm.id',$idmovimiento);  
		$movimiento_data = is_null($tipomovimiento) ? $movimiento_data : $movimiento_data->where('mp.id',$tipomovimiento);  

		
		#SI BUSCO POR PERIODO
		if(!is_null($idperiodo)){
			$movimiento_data = $movimiento_data->where('month(lm.fecmovimiento)',$mes);
			$movimiento_data = $movimiento_data->where('year(lm.fecmovimiento)',$anno);
		}


		$query = $this->db->get();
		return is_null($idmovimiento) ? $query->result() : $query->row();
	}

	public function get_movimiento($idmovimiento = null){

		$movimiento_data = $this->db->select('id, nombre, rango, codprevired')
						  ->from('rem_movimientos_personal')
						  ->where('active = 1')
		                  ->order_by('codprevired','asc');
		$movimiento_data = is_null($idmovimiento) ? $movimiento_data : $movimiento_data->where('id',$idmovimiento);  		                  
		$query = $this->db->get();
		$datos = is_null($idmovimiento) ? $query->result() : $query->row();
		return $datos;
	}



	public function add_movimiento_personal($array_datos){

		$this->db->trans_start();

		# YA SEA PARA EDITAR O AGREGAR, EL PERIODO AGREGADO NO PUEDE SER DE UN PERIODO CERRADO
		$mes = substr($array_datos['fecmovimiento'],5,2);
		$anno = substr($array_datos['fecmovimiento'],0,4);
		$this->load->model('admin');
		$periodo = $this->admin->get_periodo_by_mes($mes,$anno);
		if(!is_null($periodo)){
			$idperiodo = $periodo->id_periodo;
			$periodo_cerrado = $this->get_periodos_cerrados($this->session->userdata('empresaid'),$idperiodo);

			if(!is_null($periodo_cerrado)){
				$this->db->trans_complete();
				return 5;
			}

		}

		$mes_hasta = substr($array_datos['fechastamovimiento'],5,2);
		$anno_hasta = substr($array_datos['fechastamovimiento'],0,4);


		if($anno.$mes != $anno_hasta.$mes_hasta){
			return 6;
		}

		


		if($array_datos['idmovimiento'] == 0){


			// validar si movimiento corresponde a un período ya cerrado

			$array_movimiento = array(
							'idpersonal' => $array_datos['idpersonal'],
							'idmovimiento' => $array_datos['movimientos'],
							'comentario' => $array_datos['comentarios'],
							//'fecmovimiento' => formato_fecha($array_datos['fecmovimiento'],'d/m/Y','Y-m-d'),
							'fecmovimiento' => str_replace("-","",$array_datos['fecmovimiento']),
							'fechastamovimiento' => str_replace("-","",$array_datos['fechastamovimiento']),
							'active' => 1,
							'created_at' => $array_datos['created_at'],
							'updated_at' => $array_datos['created_at']
							);
			$this->db->insert('rem_lista_movimiento_personal',$array_movimiento);


			$this->db->trans_complete();
			return 1;

		}else{

			$movimiento_realizado = $this->get_lista_movimientos($array_datos['idpersonal'],$array_datos['idmovimiento']);

			if(is_null($movimiento_realizado)){
				$this->db->trans_complete();
				return 3;
			}else{

				// validar si movimiento corresponde a un período ya cerrado
				$mes = substr($movimiento_realizado->fecmovimiento,5,2);
				$anno = substr($movimiento_realizado->fecmovimiento,0,4);

				$periodo = $this->admin->get_periodo_by_mes($mes,$anno);

				if(!is_null($periodo)){
					$idperiodo = $periodo->id;
					$periodo_cerrado = $this->get_periodos_cerrados($this->session->userdata('empresaid'),$idperiodo);

					if(!is_null($periodo_cerrado)){
						$this->db->trans_complete();
						return 4;
					}

				}



							
				$array_movimiento = array(
								'idpersonal' => $array_datos['idpersonal'],
								'idmovimiento' => $array_datos['movimientos'],
								'comentario' => $array_datos['comentarios'],
								'fecmovimiento' => str_replace("-","",$array_datos['fecmovimiento']),
								'fechastamovimiento' => str_replace("-","",$array_datos['fechastamovimiento'])
								);

				$this->db->where('id',$array_datos['idmovimiento']);
				$this->db->where('idpersonal',$array_datos['idpersonal']);				
				$this->db->update('rem_lista_movimiento_personal',$array_movimiento);
				$this->db->trans_complete();
				return 2;				

			}



		}
		return 1;
	}


public function delete_movimiento_personal($idpersonal,$idmovimiento){
		$this->db->trans_start();

		$movimiento = $this->get_lista_movimientos($idpersonal,$idmovimiento);

		if(is_null($movimiento)){ // movimiento no existe
			$this->db->trans_complete();
			return 2;

		}

		// validar si movimiento corresponde a un período ya cerrado
		$mes = substr($movimiento->fecmovimiento,5,2);
		$anno = substr($movimiento->fecmovimiento,0,4);

		$this->load->model('admin');
		$periodo = $this->admin->get_periodo_by_mes($mes,$anno);

		if(!is_null($periodo)){
			$idperiodo = $periodo->id;
			$periodo_cerrado = $this->get_periodos_cerrados($this->session->userdata('comunidadid'),$idperiodo);

			if(!is_null($periodo_cerrado)){
				$this->db->trans_complete();
				return 3;
			}

		}

		
		$this->db->where('id', $idmovimiento);
		$this->db->where('idpersonal', $idpersonal);
		$this->db->update('rem_lista_movimiento_personal',array('active' => '0')); 

		$this->db->trans_complete();

		return 1;

	}	



public function previred($datos_remuneracion){

			$nombre_archivo = $this->session->userdata('empresaid')."_previred_".date("Ymd").".txt";
			$path_archivo = "./uploads/tmp/";
			$file = fopen($path_archivo.$nombre_archivo, "w");
			$this->load->model('admin');

			foreach ($datos_remuneracion as $remuneracion) {



				$idperiodo = $remuneracion->id_periodo;
				$idtrabajador = $remuneracion->idtrabajador;


				$movimientos_personal = $this->get_lista_movimientos($idtrabajador,null,$idperiodo); 
				$cod_mov_personal = "00";
				$array_lineas_trabajador = array();
				$i = 0;
				foreach ($movimientos_personal as $movimiento_personal) {
					if(count($array_lineas_trabajador) == 0){
						$array_lineas_trabajador[$i]['tipo_linea'] = "00";
						$array_lineas_trabajador[$i]['codprevired'] = str_pad($movimiento_personal->codprevired,2,"0",STR_PAD_LEFT);
						$array_lineas_trabajador[$i]['fechadesde'] = formato_fecha($movimiento_personal->fecmovimiento,'Y-m-d','d-m-Y');
						$array_lineas_trabajador[$i]['fechahasta'] = formato_fecha($movimiento_personal->fechastamovimiento,'Y-m-d','d-m-Y');
					}else{
						$array_lineas_trabajador[$i]['tipo_linea'] = "01";
						$array_lineas_trabajador[$i]['codprevired'] = str_pad($movimiento_personal->codprevired,2,"0",STR_PAD_LEFT);
						$array_lineas_trabajador[$i]['fechadesde'] = formato_fecha($movimiento_personal->fecmovimiento,'Y-m-d','d-m-Y');
						$array_lineas_trabajador[$i]['fechahasta'] = formato_fecha($movimiento_personal->fechastamovimiento,'Y-m-d','d-m-Y');			
					}

					$i++;
				}

				if(count($array_lineas_trabajador) == 0){
						$array_lineas_trabajador[0]['tipo_linea'] = "00";
						$array_lineas_trabajador[0]['codprevired'] = "00";
						$array_lineas_trabajador[$i]['fechadesde'] = "00-00-0000";
						$array_lineas_trabajador[$i]['fechahasta'] = "00-00-0000";						
				}

				/*$rut = str_pad($remuneracion->rut,11,"0",STR_PAD_LEFT);
				$dv = $remuneracion->dv;
				$apaterno = str_pad(substr($remuneracion->apaterno,0,30),30," ",STR_PAD_RIGHT);
				$amaterno = str_pad(substr($remuneracion->amaterno,0,30),30," ",STR_PAD_RIGHT);
				$nombres = str_pad(substr($remuneracion->nombre,0,30),30," ",STR_PAD_RIGHT);*/
				$asigfamiliar = $remuneracion->asigfamiliar - $remuneracion->montocargaretroactiva;

				$dato_afp = $this->admin->get_afp($remuneracion->idafp);

				$codprev_apv = is_null($remuneracion->idapv) ? 0 : $this->get_apv($remuneracion->idapv)->codprevired;
				$codprev_mutual = is_null($remuneracion->idmutual) ? 0 : $this->admin->get_mutual_seguridad($remuneracion->idmutual)->codprevired;
				$codprev_ccaf = is_null($remuneracion->idcaja) ? 0 : $this->admin->get_cajas_compensacion($remuneracion->idcaja)->codprevired;

				if($dato_afp->exregimen == 0){
					$reg_previsional = "AFP";
					$tipo_trabajador = 0;
				}else if($dato_afp->exregimen == 1){
					$reg_previsional = "INP";
					$tipo_trabajador = 0;
				}else if($dato_afp->exregimen == 2){
					$reg_previsional = "SIP";
					$tipo_trabajador = 2;
				}else{
					$reg_previsional = "   ";
					$tipo_trabajador = 0;
				}


				$dato_isapre = $this->admin->get_isapre($remuneracion->idisapre);



				$tramo_asig_familiar = is_null($remuneracion->idasigfamiliar) ? "D" : $this->get_tabla_asig_familiar($remuneracion->idasigfamiliar)->tramo;
				$formapagoapv = is_null($remuneracion->formapagoapv) ? "0" : $remuneracion->formapagoapv;
				$sueldoimponible_afp = ($remuneracion->cotizacionobligatoria+$remuneracion->comisionafp+$remuneracion->seginvalidez) > 0 ? $remuneracion->sueldoimponibleimposiciones : 0;
				$sueldoimponible_fonasa = ($remuneracion->fonasa+$remuneracion->inp) > 0 ? $remuneracion->sueldoimponibleimposiciones : 0;
				$sueldoimponible_isapre = $remuneracion->cotizacionsalud > 0 ? $remuneracion->sueldoimponibleimposiciones : 0;
				$sueldoimponible_mutual = $codprev_mutual != 0 ? $remuneracion->sueldoimponible : 0;
				$sueldoimponible_ccaf = $codprev_ccaf != 0 ? $remuneracion->sueldoimponible : 0;
				$sueldoimponible_segcesantia = $remuneracion->afilsegcesantia == 1 ? $remuneracion->sueldoimponible : 0;
				$cotccaffon = $codprev_ccaf == 0 ? 0 : $remuneracion->inp;
				$aportepatronal = $codprev_mutual == 0 ? 0 : $remuneracion->aportepatronal;
				$asigfamiliar_ccaf = $codprev_ccaf != 0 ? $asigfamiliar : 0;



				$cotizacion_fonasa = $codprev_ccaf == 0 ? $remuneracion->fonasa+$remuneracion->inp : $remuneracion->fonasa;

				$monto_prestamos = 0;
				/*$prestamos = $this->get_descuento($remuneracion->idperiodo,'P',$remuneracion->idtrabajador);
				foreach ($prestamos as $prestamo) {
					$monto_prestamos += $prestamo->tipodescuento == 2 ? $prestamo->monto : 0;
				}*/


				foreach ($array_lineas_trabajador as $linea_trabajador) {


					$diastrabajo = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->diastrabajo : 0;
					$tramo_asig_familiar = $linea_trabajador['tipo_linea'] == "00" ? $tramo_asig_familiar : " ";
					$cargassimples = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->cargassimples : 0;
					$cargasmaternales = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->cargasmaternales : 0;
					$cargasinvalidas = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->cargasinvalidas : 0;
					$$asigfamiliar  = $linea_trabajador['tipo_linea'] == "00" ? $asigfamiliar : 0;
					$montocargaretroactiva = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->montocargaretroactiva : 0;
					$solicitud_trabajador_joven = $linea_trabajador['tipo_linea'] == "00" ? "N" : " ";

					$sueldoimponible_afp  = $linea_trabajador['tipo_linea'] == "00" ? $sueldoimponible_afp : 0;
					$cot_obligatoria_afp  = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->cotizacionobligatoria+$remuneracion->comisionafp : 0;
					$seginvalidez = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->seginvalidez : 0;
					$montoahorrovol = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->montoahorrovol : 0;
					$fecdesdeafp = $linea_trabajador['tipo_linea'] == "00" ? "00-00-0000" : "          ";
					$fechastaafp = $linea_trabajador['tipo_linea'] == "00" ? "00-00-0000" : "          ";


					$dv_afiliado_voluntario = $linea_trabajador['tipo_linea'] == "00" ? "0" : " ";
					$fecdesdeafilvol = $linea_trabajador['tipo_linea'] == "00" ? "00-00-0000" : "          ";
					$fechastaafilvol = $linea_trabajador['tipo_linea'] == "00" ? "00-00-0000" : "          ";
					$cotizacion_fonasa = $linea_trabajador['tipo_linea'] == "00" ? $cotizacion_fonasa : 0;

					$moneda_plan_pactado = $linea_trabajador['tipo_linea'] == "00" ? "2" : "0";
					$valorpactadoperiodo = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->valorpactadoperiodo : 0;
					$cotizacionsalud = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->cotizacionsalud : 0;
					$adicisapre = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->adicisapre : 0;

					$monto_prestamos = $linea_trabajador['tipo_linea'] == "00" ? $monto_prestamos : 0;
					$cotccaffon = $linea_trabajador['tipo_linea'] == "00" ? $cotccaffon : 0;
					$asigfamiliar_ccaf = $linea_trabajador['tipo_linea'] == "00" ? $asigfamiliar_ccaf : 0;

					$aportepatronal = $linea_trabajador['tipo_linea'] == "00" ? $aportepatronal : 0;

					$sueldoimponible_segcesantia = $linea_trabajador['tipo_linea'] == "00" ? $sueldoimponible_segcesantia : 0;
					$segcesantia = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->segcesantia : 0;
					$aportesegcesantia = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->aportesegcesantia : 0;

					

					

					// DATOS DEL TRABAJADOR
					$linea  = str_pad($remuneracion->rut,11,"0",STR_PAD_LEFT); // rut
					$linea .= $remuneracion->dv; // dv
					$linea .= str_pad(substr(sanear_string($remuneracion->apaterno),0,30),30," ",STR_PAD_RIGHT); //apaterno
					$linea .= str_pad(substr(sanear_string($remuneracion->amaterno),0,30),30," ",STR_PAD_RIGHT); //amaterno
					$linea .= str_pad(substr(sanear_string($remuneracion->nombre),0,30),30," ",STR_PAD_RIGHT); //nombre
					$linea .= $remuneracion->sexo; //sexo
					$linea .= $remuneracion->nacionalidad == "C" ? 0 : 1; //nacionalidad
					$linea .= "01"; //tipo pago
					$linea .= str_pad($remuneracion->mes,2,"0",STR_PAD_LEFT).$remuneracion->anno; //periodo desde
					$linea .= str_pad($remuneracion->mes,2,"0",STR_PAD_LEFT).$remuneracion->anno; //periodo hasta
					$linea .= $reg_previsional; //regimen previsional 
					$linea .= $tipo_trabajador; //tipo trabajador (ver que pasa con pensionados activos y pensionados y cotiza)
					$linea .= str_pad($diastrabajo,2,"0",STR_PAD_LEFT); //dias trabajados
					$linea .= $linea_trabajador['tipo_linea']; //tipo de linea ***** VER PARA MOVIMIENTOS DEL PERSONAL
					$linea .= $linea_trabajador['codprevired']; //Código Movimiento de Personal
					$linea .= $linea_trabajador['fechadesde']; //Fecha Desde Código Movimiento de Personal 
					$linea .= $linea_trabajador['fechahasta']; //Fecha Hasta Código Movimiento de Personal 
					$linea .= $tramo_asig_familiar; //Tramo asignacion familiar 
					$linea .= str_pad($cargassimples,2,"0",STR_PAD_LEFT); //cargas simples
					$linea .= substr($cargasmaternales,-1); //cargas maternales
					$linea .= substr($cargasinvalidas,-1); //cargas inválidas
					$linea .= str_pad(substr($asigfamiliar,-6),6,"0",STR_PAD_LEFT); //monto asignacion familiar
					$linea .= str_pad(substr($montocargaretroactiva,-6),6,"0",STR_PAD_LEFT); //monto asignacion retroactiva
					$linea .= "000000"; //monto reintegro de cargas familiares 
					$linea .= $solicitud_trabajador_joven; //Solicitud Trabajador Joven 


					// DATOS AFP
					$linea .= str_pad($dato_afp->codprevired,2,"0",STR_PAD_LEFT); //cod afp
					$linea .= str_pad($sueldoimponible_afp,8,"0",STR_PAD_LEFT); //sueldo imponible
					$linea .= str_pad($cot_obligatoria_afp,8,"0",STR_PAD_LEFT); //cotizacion
					$linea .= str_pad($seginvalidez,8,"0",STR_PAD_LEFT); //seguro invalidez
					$linea .= str_pad($montoahorrovol,8,"0",STR_PAD_LEFT); //monto ahorro voluntario
					$linea .= "00000000"; //Renta imponible sustituta 
					$linea .= "00,00"; //tasa pactada  
					$linea .= "000000000"; //aporte indemnizacion  
					$linea .= "00"; //nro. periodos  
					$linea .= $fecdesdeafp; //periodo desde   
					$linea .= $fechastaafp; //periodo hasta  
					$linea .= "                                        "; //puesto de trabajo pesado  
					$linea .= "00,00"; //cotizacion trabajo pesado  
					$linea .= "000000"; //monto cotizacion trabajo pesado  


					//Datos Ahorro Previsional Voluntario Individual (PENDIENTE HASTA IMPLEMENTAR OPCIONES DE APV)
					//$linea .= str_pad($dato_afp->codprevired,3,"0",STR_PAD_LEFT); //cod institucion APVI (se asume que es la misma de la APV??)
					$linea .= str_pad($codprev_apv,3,"0",STR_PAD_LEFT); //cod institucion APVI 
					$linea .= str_pad(substr($remuneracion->nrocontratoapv,-20),20,"0",STR_PAD_LEFT); //nro contrato apvi  
					$linea .= $formapagoapv; //forma de pago apv 
					$linea .= str_pad(substr($remuneracion->montocotapv,-8),8,"0",STR_PAD_LEFT); //monto cotizacion apvi
					//$linea .= "00000000"; //monto cotizacion apvi
					$linea .= str_pad(substr($remuneracion->depconvapv,-8),8,"0",STR_PAD_LEFT);; //Cotización Depósitos Convenidos  *****************


					//Datos Ahorro Previsional Voluntario Colectivo
					$linea .= "000"; //Código Institución Autorizada APVC  
					$linea .= "                    "; //nro contrato APVC  
					$linea .= "0"; //forma de pago apvc  
					$linea .= "00000000"; //Cotización Trabajador APVC  
					$linea .= "00000000"; //Cotización Empleador APVC  


					//Datos Afiliado Voluntario
					$linea .= "00000000000"; // RUT Afiliado Voluntario 
					$linea .= $dv_afiliado_voluntario; // DV Afiliado Voluntario 
					$linea .= "                              "; //Apellido Paterno 
					$linea .= "                              "; //Apellido Materno 
					$linea .= "                              "; //Nombres 
					$linea .= "00"; // Código Movimiento de Personal 
					$linea .= $fecdesdeafilvol; //Fecha desde   
					$linea .= $fechastaafilvol; //Fecha hasta 
					$linea .= "00"; // Código de la AFP 
					$linea .= "00000000"; //Monto Capitalización Voluntaria  
					$linea .= "00000000"; //Monto Ahorro Voluntario 
					$linea .= "00"; // Número de periodos de cotización 


					//Datos IPS - ISL - FONASA  (FALTA ANALIZAR DE AQUI HACIA ABAJO)
					$linea .= "0000"; // Código EX-Caja Régimen 
					$linea .= "00,00"; //Tasa Cotización Ex-Caja Previsión  
					$linea .= str_pad($sueldoimponible_fonasa,8,"0",STR_PAD_LEFT); //Renta Imponible IPS ******REVISAR, al parecer hay un tope
					$linea .= "00000000"; //Cotización Obligatoria IPS 
					$linea .= "00000000"; //Renta Imponible Desahucio 
					$linea .= "0000"; // Código Ex-Caja Régimen Desahucio 
					$linea .= "00,00"; //Tasa Cotización Desahucio Ex-Cajas de Previsión 
					$linea .= "00000000"; //Cotización Desahucio 
					$linea .= str_pad($cotizacion_fonasa,8,"0",STR_PAD_LEFT); //Cotización Fonasa 
					//$linea .= str_pad($remuneracion->fonasa,8,"0",STR_PAD_LEFT); //Cotización Fonasa 
					$linea .= "00000000"; //Cotización Acc. Trabajo (ISL) *****************
					$linea .= "00000000"; //Bonificación Ley 15.386 
					$linea .= "00000000"; //Descuento por cargas familiares de ISL 
					$linea .= "00000000"; //Bonos Gobierno 


					//Datos Salud
					$linea .= str_pad($dato_isapre->codprevired,2,"0",STR_PAD_LEFT); // Código Institución de Salud 
					$linea .= "                "; // Número del FUN (REVISAR SI SON BLANCOS O VACÍOS)
					$linea .= str_pad($sueldoimponible_isapre,8,"0",STR_PAD_LEFT); //Renta Imponible Isapre 
					$linea .= $moneda_plan_pactado; //Moneda del plan pactado Isapre 
					$linea .= str_pad(str_replace(".",",",$valorpactadoperiodo),8,"0",STR_PAD_LEFT); //Cotización Pactada 
					$linea .= str_pad($cotizacionsalud,8,"0",STR_PAD_LEFT); //Cotización Obligatoria Isapre 
					$linea .= str_pad($adicisapre,8,"0",STR_PAD_LEFT); //Cotización Adicional Voluntaria 
					$linea .= "00000000"; //Monto Garantía Explícita de Salud GES (Uso Futuro) 



					//Datos Caja de Compensación (AQUI VOY)


					$linea .= str_pad($codprev_ccaf,2,"0",STR_PAD_LEFT);; // Código CCAF 
					$linea .= str_pad($sueldoimponible_ccaf,8,"0",STR_PAD_LEFT); //Renta Imponible CCAF 
					$linea .= str_pad($monto_prestamos,8,"0",STR_PAD_LEFT); //Créditos Personales CCAF 
					$linea .= "00000000"; //Descuento Dental CCAF *****************
					$linea .= "00000000"; //Descuentos por Leasing (Programa Ahorro) *****************
					$linea .= "00000000"; //Descuentos por seguro de vida CCAF*****************
					$linea .= "00000000"; //Otros descuentos CCAF *****************
					$linea .= str_pad($cotccaffon,8,"0",STR_PAD_LEFT); //Cotización a CCAF de no afiliados a Isapres
					$linea .= str_pad($asigfamiliar_ccaf,8,"0",STR_PAD_LEFT); //Descuento Cargas Familiares CCAF 
					$linea .= "00000000"; //Otros descuentos CCAF 1 (Uso Futuro) *****************
					$linea .= "00000000"; //Otros descuentos CCAF 2 (Uso Futuro) *****************
					$linea .= "00000000"; //Bonos Gobierno (Uso Futuro) *****************
					$linea .= "                    "; //Código de Sucursal (Uso Futuro) (VER SI ES BLANCO O CEROS) *****************



					//Datos Mutualidad
					$linea .= str_pad($codprev_mutual,2,"0",STR_PAD_LEFT);; // Código Mutualidad
					$linea .= str_pad($sueldoimponible_mutual,8,"0",STR_PAD_LEFT); //Renta Imponible Mutual 
					$linea .= str_pad($aportepatronal,8,"0",STR_PAD_LEFT);; //Cotización Accidente del Trabajo (MUTUAL) 
					$linea .= "000"; // Código Mutualidad (VER QUE PASA EN LINEAS ADICIONALES POR MOV PERSONAL) *****************

					//Datos Administradora de Seguro de Cesantía

					$linea .= str_pad($sueldoimponible_segcesantia,8,"0",STR_PAD_LEFT); //Renta Imponible Seguro Cesantía (Informar Renta Total Imponible) 
					$linea .= str_pad($segcesantia,8,"0",STR_PAD_LEFT); //Aporte Trabajador Seguro Cesantía 
					$linea .= str_pad($aportesegcesantia,8,"0",STR_PAD_LEFT); //Aporte Empleador Seguro Cesantía 

					//Datos Pagador de Subsidios
					$linea .= "00000000000"; //Rut Pagadora Subsidio
					$linea .= " "; //Rut Pagadora Subsidio 


					//Otros Datos de la Empresa
					$linea .= "                    "; //Centro de Costos, Sucursal, Agencia, Obra, Región 




					$linea .= "\r\n";
					//$linea = $rut.$dv.$apaterno.$amaterno.$nombres."\r\n";
					fputs($file,$linea);

				}

			}

			
			fclose($file);

			$data_archivo = basename($path_archivo.$nombre_archivo);
			header('Content-Type: text/plain');
			header('Content-Disposition: attachment; filename=' . $data_archivo);
			header('Content-Length: ' . filesize($path_archivo.$nombre_archivo));
			readfile($path_archivo.$nombre_archivo);		


			unlink($path_archivo.$nombre_archivo);
	}		
}
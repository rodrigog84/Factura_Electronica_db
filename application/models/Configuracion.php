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

class Configuracion extends CI_Model
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

	public function get_haberes_descuentos($idhaberdescto = null,$tipo = null){

		$habdescto_data = $this->db->select('d.id, d.codigo, d.tipo, d.nombre, d.editable, d.visible')
						  ->from('rem_conf_haber_descuento d')
						  ->join('rem_conf_haber_descuento_empresa de','d.id = de.idconfhd and de.idempresa = ' . $this->session->userdata('empresaid'),'left')
						  ->where('valido = 1')
						  ->where('(editable = 0 or de.idempresa = ' . $this->session->userdata('empresaid') . ')')
						  ->order_by('nombre');
		$habdescto_data = is_null($idhaberdescto) ? $habdescto_data : $habdescto_data->where('id',$idhaberdescto);  		
		$habdescto_data = is_null($tipo) ? $habdescto_data : $habdescto_data->where('d.tipo',$tipo);  		                  
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		$datos = is_null($idhaberdescto) ? $query->result() : $query->row();
		return $datos;

	}


	public function add_haberes_descuentos($datos){

			$this->db->insert('rem_conf_haber_descuento',$datos);
			$idhaberdescto = $this->db->insert_id();


			$array_hdemp = array('idconfhd' => $idhaberdescto,
								 'idempresa' => $this->session->userdata('empresaid'));
			$this->db->insert('rem_conf_haber_descuento_empresa',$array_hdemp);

	}
}




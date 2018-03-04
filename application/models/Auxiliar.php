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

class Auxiliar extends CI_Model
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

	public function get_dias_progresivos($idpersonal = null,$iddia = null){
		$cargos_data = $this->db->select('id , idpersonal, fechainicio, dias')
						  ->from('rem_dias_progresivos')
						  ->where('idpersonal',$idpersonal)
						  ->where('active',1)
		                  ->order_by('fechainicio');
		
		$cargos_data = is_null($iddia) ? $cargos_data : $cargos_data->where('id',$iddia);  	
		$query = $this->db->get();
		return  is_null($iddia) ? $query->result() : $query->row();
	}	


	public function get_cartola_vacaciones($idpersonal = null,$idcartola = null){
		$cargos_data = $this->db->select('c.id , c.idpersonal, c.fecinicio, c.fecfin, c.dias, c.comentarios, c.created_at')
						  ->from('rem_cartola_vacaciones c')
						  ->join('rem_personal p','c.idpersonal = p.id_personal')
						  ->where('p.id_empresa',$this->session->userdata('empresaid'))
						  ->where('c.idpersonal',$idpersonal)
						  ->where('c.active = 1')
		                  ->order_by('c.fecinicio');
		$cargos_data = is_null($idcartola) ? $cargos_data : $cargos_data->where('c.id',$idcartola);  	
		$query = $this->db->get();
		return  is_null($idcartola) ? $query->result() : $query->row();
	}	



public function generar_contenido_comprobante_solicitud($idpersonal,$idcartola){


			$cartola = $this->get_cartola_vacaciones($idpersonal,$idcartola);
			$this->load->model('rrhh_model');
			$personal = $this->rrhh_model->get_personal($idpersonal);

			$this->load->model('admin');
			$empresa = $this->admin->get_empresas($this->session->userdata('empresaid'));


			//$logo = $comunidad->logo == '' || is_null($comunidad->logo) ? 'img/logo4_1_80p_color.png' : 'uploads/logos/'. $this->session->userdata('comunidadid') . '/' . $comunidad->logo;

			//$logo = "images/logos/logo-ecomac-1.png";
			$logo = "";

			//$firma = $comunidad->firma == '' || is_null($comunidad->firma) ? '&nbsp;' : '<img src="uploads/firmas/'. $this->session->userdata('comunidadid') . '/' . $comunidad->firma . '" width="150px"> ';				
			$firma = "&nbsp;";


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
						background-color: #e3ece4; 
						border-collapse: collapse;
						font-family: DejaVuSansCondensed;
						font-size: 9pt; 
						line-height: 1.2;
						margin-top: 2pt; 
						margin-bottom: 5pt; 
						width: 70%;
						topntail: 0.02cm solid #495b4a; 
					}

					.theadClass { 
						font-weight: bold; 
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
						font-size: 11pt; 
						color: #080636;
						font-family: DejaVuSansCondensed, sans-serif; 
						margin-top: 10pt; 
						margin-bottom: 7pt;
						text-align: center;
						margin-collapse:collapse; page-break-after:avoid; }	

						punteada { 
    						border: 1px dashed #278e79; 
  						}									
					</style>
			</head>
					<body>';


			$contenido_comprobante = '
						<p><h4 class="header4"><br>Comprobante de Vacaciones<br><br><img src="' . $logo . '" width="100px"></h4></p>
						<hr>
						<br>
						<div class="recto">
							<h4><b>Fecha:</b> ' . substr($cartola->created_at,8,2) . '/' . substr($cartola->created_at,5,2) . '/' . substr($cartola->created_at,0,4) . '</h4><br>
							<p align="justify">En cumplimiento a las disposiciones legales vigentes se deja constancia que a contar de las fechas que se indican el (la)
								trabajador (a): ' . $personal->nombre . ' ' . $personal->apaterno . ' ' . $personal->amaterno . ', cédula de Identidad ' . number_format($personal->rut,0,".",".") . '-' . $personal->dv . ',  hará uso de: ' . $cartola->dias . ' días hábiles de feriado anual con remuneración íntegra. Esto se hará efectivo entre los días <b>' . substr($cartola->fecinicio,8,2) . ' de ' . month2string(substr($cartola->fecinicio,5,2)) . ' de ' . substr($cartola->fecinicio,0,4) . '</b> y <b>' . substr($cartola->fecfin,8,2) . ' de ' . month2string(substr($cartola->fecfin,5,2)) . ' de ' . substr($cartola->fecfin,0,4) . '</b> inclusive.</p>
						</div>
		';


						if($firma == '&nbsp;'){
							$contenido_comprobante .= '<br><hr><br>
						<br>
						<br>
						<br>';
						}else{
							$contenido_comprobante .= '<br><hr>';
						}

				$contenido_comprobante .='
						<table width="100%" border="0">
							<tr>
								<td width="10%">&nbsp;</td>
								<td width="20%" style="border-bottom:1pt solid black;">&nbsp;</td>
								<td width="40%">&nbsp;</td>
								<td width="20%" style="border-bottom:1pt solid black;">' . $firma . '</td>
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



				$html .= $contenido_comprobante;
				$html .= '<br>
						  <p align="left" style="font-size:8px">COPIA EMPLEADOR</p>
						<hr class="punteada" />';
				$html .= $contenido_comprobante;
				$html .= '<br>
						  <p align="left" style="font-size:8px">COPIA TRABAJADOR</p>';



			$html .=	"</body>
						</html>";

						//echo $html; exit;
				
				//$this->db->where('id',$idegreso);
				//$this->db->update('gc_listado_pagos', array('pdf_content' => $html));			
				return $html;

								

	}	


	public function comprobante_solicitud($idpersonal,$idcartola){

			$this->load->model('admin');
			$datos_empresa = $this->admin->datos_empresa($this->session->userdata('empresaid'));

			$content = $this->generar_contenido_comprobante_solicitud($idpersonal,$idcartola);


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
			$this->mpdf->SetTitle('Is RRHH - Comprobante Solicitud Vacaciones');
			$this->mpdf->SetHeader('Empresa '. $datos_empresa->nombre . ' - ' .$datos_empresa->comuna . ' - RUT: ' .number_format($datos_empresa->rut,0,".",".") . '-' .$datos_empresa->dv);
			$this->mpdf->WriteHTML($content);
			$this->mpdf->SetFooter('Para más información visite: http://www.info-sys.cl');


			// SE ALMACENA EL ARCHIVO
			$nombre_archivo = date("Y")."_".date("m")."_".date("d")."_vacaciones_".$idpersonal.".pdf";
			$this->mpdf->Output($nombre_archivo, "I");
			
	}	


public function delete_vacaciones($idpersonal,$idcartola){

		$this->db->trans_start();
		$cartola = $this->get_cartola_vacaciones($idpersonal,$idcartola);


		if(is_null($cartola)){
			$this->db->trans_complete();
			return false;

		}else{

			$this->db->where('id', $idcartola);
			$this->db->where('idpersonal', $idpersonal);		
			$this->db->update('rem_cartola_vacaciones',array('active' => '0')); 

			$this->db->query('update rem_personal set diasvactomados = diasvactomados - ' . $cartola->dias . ' where id_personal = ' . $idpersonal);


			$this->db->trans_complete();
			return true;
		}

	}		

	public function add_dia_progresivo($array_datos){

		$this->db->trans_start();

		if($array_datos['idcartola'] == 0){

			$this->db->query("update rem_personal set diasprogresivos = diasprogresivos + " . $array_datos['dias'] . " where id_personal = " . $array_datos['idpersonal']);
			/*$personal = $this->get_personal($array_datos['idpersonal']);
			$diasprogresivos =  $personal->diasprogresivos;*/

			$array_dia_progresivo = array(
							'idpersonal' => $array_datos['idpersonal'],
							'fechainicio' => $array_datos['periodo'],
							'dias' => $array_datos['dias'],
							'active' => 1,
							'created_at' => str_replace("-","",$array_datos['created_at']),
							'updated_at' => str_replace("-","",$array_datos['created_at'])
							);
			$this->db->insert('rem_dias_progresivos',$array_dia_progresivo);
		}else{

			$cartola = $this->get_dias_progresivos($array_datos['idpersonal'],$array_datos['idcartola']);

			if(is_null($cartola)){
				$this->db->trans_complete();
				return 2;
			}else{

				$diff_dias = $array_datos['dias'] - $cartola->dias;
				/*if($diff_dias > $num_dias){
					$this->db->trans_complete();
					return false;
				}*/

				$array_update = array( 'fechainicio' => $array_datos['periodo'],
						  'idpersonal' => $array_datos['idpersonal'],
						  'dias' => $array_datos['dias'],
						  );

				$this->db->where('id',$array_datos['idcartola']);
				$this->db->where('idpersonal',$array_datos['idpersonal']);
				$this->db->update('rem_dias_progresivos',$array_update);

				$this->load->model('rrhh_model');
				$personal = $this->rrhh_model->get_personal($array_datos['idpersonal']);
				$dias_vacaciones = dias_vacaciones($personal->fecinicvacaciones,$personal->saldoinicvacaciones);
				$dias_progresivos = $this->get_dias_progresivos($array_datos['idpersonal']);
				$num_dias_progresivos = num_dias_progresivos($personal->fecinicvacaciones,$personal->saldoinicvacprog,$dias_progresivos);
				$saldo_vacaciones = $dias_vacaciones + $num_dias_progresivos - $personal->diasvactomados;


				if($saldo_vacaciones < 0){

					$array_update['dias'] = $cartola->dias;
					$this->db->where('id',$array_datos['idcartola']);
					$this->db->where('idpersonal',$array_datos['idpersonal']);	
					$this->db->update('rem_dias_progresivos',$array_update); 
					$this->db->trans_complete();
					return 3;
				}	

				$this->db->query('update rem_personal set diasprogresivos = diasprogresivos + ' . $diff_dias . ' where id_personal = ' . $array_datos['idpersonal']);				
			}


		}

		$this->db->trans_complete();
		return 1;
	}



	public function delete_dias_progresivos($idpersonal,$idcartola){

		$this->db->trans_start();
		$cartola = $this->get_dias_progresivos($idpersonal,$idcartola);


		if(is_null($cartola)){
			$this->db->trans_complete();
			return 2;

		}else{






			$this->db->where('id', $idcartola);
			$this->db->where('idpersonal', $idpersonal);		
			$this->db->update('rem_dias_progresivos',array('active' => '0')); 

			$this->load->model('rrhh_model');
			$personal = $this->rrhh_model->get_personal($idpersonal);
			$dias_vacaciones = dias_vacaciones($personal->fecinicvacaciones,$personal->saldoinicvacaciones);
			$dias_progresivos = $this->get_dias_progresivos($idpersonal);
			$num_dias_progresivos = num_dias_progresivos($personal->fecinicvacaciones,$personal->saldoinicvacprog,$dias_progresivos);
			$saldo_vacaciones = $dias_vacaciones + $num_dias_progresivos - $personal->diasvactomados;


			if($saldo_vacaciones < 0){
				$this->db->where('id', $idcartola);
				$this->db->where('idpersonal', $idpersonal);		
				$this->db->update('rem_dias_progresivos',array('active' => '1')); 
				$this->db->trans_complete();
				return 3;
			}			

			$this->db->query('update rem_personal set diasprogresivos = diasprogresivos - ' . $cartola->dias . ' where id_personal = ' . $idpersonal);


			$this->db->trans_complete();
			return 1;
		}

	}	



public function solicita_vacaciones($array_datos){

		$this->db->trans_start();

		$this->load->model('rrhh_model');
		$personal = $this->rrhh_model->get_personal($array_datos['idpersonal']);
		$dias_vacaciones = dias_vacaciones($personal->fecinicvacaciones,$personal->saldoinicvacaciones);


		$dias_progresivos = $this->get_dias_progresivos($array_datos['idpersonal']);
		$num_dias_progresivos = num_dias_progresivos($personal->fecinicvacaciones,$personal->saldoinicvacprog,$dias_progresivos);

		$saldo_vacaciones = $dias_vacaciones + $num_dias_progresivos - $personal->diasvactomados;


		$num_dias = (int)$saldo_vacaciones;
		if($array_datos['idcartola'] == 0){
			
			if($array_datos['dias'] > $num_dias){
				$this->db->trans_complete();
				return false;
			}

			$array_insert = array('idpersonal' => $array_datos['idpersonal'],
						  'fecinicio' => str_replace("-","",$array_datos['fecinicio']),
						  'fecfin' => str_replace("-","",$array_datos['fecfin']),
						  'dias' => $array_datos['dias'],
						  'comentarios' => $array_datos['comentarios'],
						  'active' => 1,
						  'created_at' => str_replace("-","",$array_datos['created_at']),
						  'update_at' => str_replace("-","",$array_datos['created_at'])
						  );

			#CREA CARTOLAS
			$this->db->insert('rem_cartola_vacaciones', $array_insert);

			$this->db->query('update rem_personal set diasvactomados = diasvactomados + ' . $array_datos['dias'] . ' where id_personal = ' . $array_datos['idpersonal']);
			
		}else{

			$cartola = $this->get_cartola_vacaciones($array_datos['idpersonal'],$array_datos['idcartola']);

			if(is_null($cartola)){
				$this->db->trans_complete();
				return false;
			}else{

				$diff_dias = $array_datos['dias'] - $cartola->dias;
				if($diff_dias > $num_dias){
					$this->db->trans_complete();
					return false;
				}

				$array_update = array( 'fecinicio' => $array_datos['fecinicio'],
						  'fecfin' => $array_datos['fecfin'],
						  'dias' => $array_datos['dias'],
						  'comentarios' => $array_datos['comentarios']
						  );

				$this->db->where('id',$array_datos['idcartola']);
				$this->db->where('idpersonal',$array_datos['idpersonal']);
				$this->db->update('rem_cartola_vacaciones',$array_update);


				$this->db->query('update rem_personal set diasvactomados = diasvactomados + ' . $diff_dias . ' where id_personal = ' . $array_datos['idpersonal']);				
			}



		}

		$this->db->trans_complete();
		return true;

	}	



}




<!DOCTYPE HTML>
<html>
<head>
<title>IS RR.HH</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Augment Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
 <!-- Bootstrap Core CSS -->
<link href="<?php echo base_url();?>css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="<?php echo base_url();?>css/style.css" rel='stylesheet' type='text/css' />
<link href="<?php echo base_url();?>css/style_grid.css" rel="stylesheet" type="text/css" media="all" />

<

<!-- Graph CSS -->
<link href="<?php echo base_url();?>css/font-awesome.css" rel="stylesheet"> 
<!-- jQuery -->
<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'>
<!-- lined-icons -->
<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
<!-- //lined-icons -->
<script src="<?php echo base_url();?>js/jquery-1.10.2.min.js"></script>
<script src="<?php echo base_url();?>js/amcharts.js"></script>	
<script src="<?php echo base_url();?>js/serial.js"></script>	
<script src="<?php echo base_url();?>js/light.js"></script>	
<script src="<?php echo base_url();?>js/radar.js"></script>	

<!--select-min-->

<!--<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap-select.min.css">-->

<!-- Latest compiled and minified JavaScript -->
<script src="<?php echo base_url();?>js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/i18n/defaults-*.min.js"></script>





<link href="<?php echo base_url();?>css/barChart.css" rel='stylesheet' type='text/css' />
<link href="<?php echo base_url();?>css/fabochart.css" rel='stylesheet' type='text/css' />
<!--clock init-->
<script src="<?php echo base_url();?>js/css3clock.js"></script>
<!--Easy Pie Chart-->
<!--skycons-icons-->
<script src="<?php echo base_url();?>js/skycons.js"></script>

<script src="<?php echo base_url();?>js/jquery.easydropdown.js"></script>






<!--//skycons-icons-->
<!--//file-->
<script src="<?php echo base_url();?>js/fileinput.js"></script>
<!--//file-->
</head> 
<body>
	<div class="page-container">
		<div class="left-content">
	   		<div class="inner-content">
				<div class="header-section">
					<div class="top_menu">
						<div class="main-search">
							<form>
								<input type="text" value="Search" onFocus="this.value = '';" onBlur="if (this.value == '') {this.value = 'Buscar';}" class="text"/>
								<input type="submit" value="">
							</form>
							<div class="close"><img src="images/cross.png" /></div>
						</div>
						<div class="srch"><button></button></div>
						<script type="text/javascript">
							$('.main-search').hide();
							$('button').click(function (){
								$('.main-search').show();
								$('.main-search text').focus();
							});
							$('.close').click(function(){
								('.main-search').hide();
							});
						</script>

						<div class="profile_details_left">
							<ul class="nofitications-dropdown">
								
								<li class="dropdown note">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-power-off" aria-hidden="true"></i> <span class="badge"></span></a>
									<ul class="dropdown-menu two first">
										<li>
											<div class="notification_header">
												<h3>Cerrar Sesión</h3> 
											</div>
										</li>
										<li>
											<a href="<?php echo base_url();?>login/salir">
												<div class="user_img">
													<img src="images/cerrar.png" alt="cerrar">
												</div>
												<div class="notification_desc">
												
													<p><span>Cerrar Sesión</span></p>
												</div>
												<div class="clearfix"></div>	
											</a>
										</li>
									</ul>
								</li>
										
								
							
							</div>
							<div class="clearfix"></div>	
							<!--//profile_details-->
						</div>
						<!--//menu-right-->
					<div class="clearfix"></div>
				</div>
					<!-- //header-ends -->
						<div class="outter-wp">
							<?php $this->load->view($content_view); ?>									
						</div>
									<!--/charts-inner-->
									</div>
										<!--//outer-wp-->
									</div>
									 <!--footer section start-->
										<footer>
										   <p>&copy 2017 IS RR.HH | Diseñado por  <a href="https://consisa.cl/" target="_blank">Grupo Consisa.</a></p>
										</footer>
									<!--footer section end-->
								</div>
							</div>
				<!--//content-inner-->

<!-- //Modal apertura de caja -->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="myModal1">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Apertura de Caja</h4>
      </div>

      <div class="modal-body">
      	<input type="text" name="caja" class="form-control" id="caja" placeholder="Caja:">
      	<br>
      	<input type="text" name="cajero" class="form-control" id="cajero" placeholder="Cajero:">
      	<br>
		<input type="text" name="efectivo" class="form-control" id="efectivo" placeholder="Efectivo:">
		<br>
		<input type="text" name="cheque" class="form-control" id="cheque" placeholder="Cheques:">
		<br>
		<input type="text" name="otros" class="form-control" id="otros" placeholder="Otros:">
		<br>
		<button type = "button" class = "btn btn-primary" id="comando">Ingresar</button>
		<button type = "button" class = "btn btn-primary" data-dismiss="modal"  id="comando">Cancelar</button>
      </div>
    </div>
  </div>
</div>
<!-- //Modal apertura de caja -->

<!-- //Modal Parametros -->
<div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="myModal13">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Parámetros</h4>
      </div>

      <div class="modal-body">
      	<table class="table">
    <thead> 
		<tr> 
			<th>Sueldo Minimo:</th> 
			<th>Valor UF:</th>
			
		</tr> 
	</thead>
	<tbody>
		<td><input class="form-control miles" name="sueldominimo" id="sueldominimo" placeholder="Ingrese Sueldo Mínimo" value="270.000" data-fv-field="sueldominimo" autocomplete="off" type="text"></td>
		
		<td><input class="form-control miles_decimales" name="uf" id="uf" placeholder="Ingrese Valor UF" value="26.604,10" data-fv-field="uf" autocomplete="off" type="text"></td>
			
	</tbody>

</table>

<table class="table">
    <thead> 
		<tr> 
			<th>Tope Imponible (UF):</th> 
			<th>Tasa Seguro de Invalidez (SIS):</th>
			
		</tr> 
	</thead>
	<tbody>
		<td><input class="form-control miles_decimales" name="topeimponible" id="topeimponible" placeholder="Ingrese Tope Imponible" value="75,70" autocomplete="off" type="text"></td>

		<td><input class="form-control" name="tasasis" id="tasasis" placeholder="Ingrese Valor SIS" value="1.41" data-fv-field="tasasis" type="text"></td>
			
	</tbody>
</table>

	
		<br>		
		<button type = "button" class = "btn btn-info" id="comando">Guardar</button>
		<button type = "button" class = "btn btn-danger" data-dismiss="modal"  id="comando">Cancelar</button>
      </div>
    </div>
  </div>
</div>
<!-- //Modal Parametros -->

<!-- //Modal carga masiva hab/des -->
<div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="myModal2">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Carga Masiva de Hab/Des</h4>
      </div>

      <div class="modal-body">
      	<table class="table">
      		<thead> 
				<tr> 
					<th>Empresa:</th> 
					<th>Tipo Hab/Des:</th>
				</tr> 
			</thead>
			<tbody>
				<td>
					<input type="text" name="empresa" class="form-control" id="empresa" placeholder="Empresa">
				</td>
				<td>
					<select name="selector1" id="selector1" class="form-control1">
						<option>Seleccionar</option>
						<option>Dolore, ab unde modi est!</option>
						<option>Illum, fuga minus sit eaque.</option>
						<option>Consequatur ducimus maiores voluptatum minima.</option>
					</select>
				</td>
			</tbody>
      	</table>
      	<table class="table">
      		<thead> 
				<tr> 
					<th>Código:</th> 
					
				</tr> 
			</thead>
			<tbody>
				<td>
					<select name="selector1" id="selector1" class="form-control1">
						<option>Seleccionar</option>
						<option>Dolore, ab unde modi est!</option>
						<option>Illum, fuga minus sit eaque.</option>
						<option>Consequatur ducimus maiores voluptatum minima.</option>
					</select>
				</td>
			</tbody>
      	</table>
      	 	<table class="table">
      		<thead> 
				<tr> 
					<th>Buscar:</th> 
					
				</tr> 
			</thead>
			<tbody>
				
				<td>
					<div class="fileinput fileinput-new" data-provides="fileinput">
    					<span class="btn btn-default btn-file">
    						<span>Examinar</span>
    						<input type="file" />
    					</span>
    					<span class="fileinput-filename"></span>
    					<span class="fileinput-new">Ningún archivo elegido</span>
    				</div>
				</td>
			</tbody>
      	</table>
      	
		<br>
		<button type = "button" class = "btn btn-info" id="comando">Ingresar</button>
		<button type = "button" class = "btn btn-danger" data-dismiss="modal"  id="comando">Cancelar</button>
      </div>
    </div>
  </div>
</div>
<!-- //Modal carga masiva hab/des -->

<!-- //Modal Parametros -->
<div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="myModal13">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Parámetros</h4>
      </div>

      <div class="modal-body">
      	<table class="table">
    <thead> 
		<tr> 
			<th>Sueldo Minimo:</th> 
			<th>Valor UF:</th>
			
		</tr> 
	</thead>
	<tbody>
		<td><input class="form-control miles" name="sueldominimo" id="sueldominimo" placeholder="Ingrese Sueldo Mínimo" value="270.000" data-fv-field="sueldominimo" autocomplete="off" type="text"></td>
		
		<td><input class="form-control miles_decimales" name="uf" id="uf" placeholder="Ingrese Valor UF" value="26.604,10" data-fv-field="uf" autocomplete="off" type="text"></td>
			
	</tbody>

</table>

<table class="table">
    <thead> 
		<tr> 
			<th>Tope Imponible (UF):</th> 
			<th>Tasa Seguro de Invalidez (SIS):</th>
			
		</tr> 
	</thead>
	<tbody>
		<td><input class="form-control miles_decimales" name="topeimponible" id="topeimponible" placeholder="Ingrese Tope Imponible" value="75,70" autocomplete="off" type="text"></td>

		<td><input class="form-control" name="tasasis" id="tasasis" placeholder="Ingrese Valor SIS" value="1.41" data-fv-field="tasasis" type="text"></td>
			
	</tbody>
</table>

	
		<br>		
		<button type = "button" class = "btn btn-info" id="comando">Guardar</button>
		<button type = "button" class = "btn btn-danger" data-dismiss="modal"  id="comando">Cancelar</button>
      </div>
    </div>
  </div>
</div>
<!-- //Modal Parametros -->
			<!--/sidebar-menu-->
				<div class="sidebar-menu">
					<header class="logo">
						<a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a> <a href="inicio.html"> <span id="logo"> 	<h1>IS  RRHH</h1></span> 
						<!--<img id="logo" src="" alt="Logo"/>--> 
				  		</a> 
					</header>
					<div style="border-top:1px solid rgba(69, 74, 84, 0.7)"></div>

			<!--/down-->
					<div class="down">	
						<p>Nombre de la Empresa</p>
						<ul>
							<li><a class="tooltips" href="inicio.html"><span>Salir</span><i class="lnr lnr-power-switch"></i></a></li>
						</ul>
					</div>
					
			<!--/down-->
			
							   <!--//down-->
                            <div class="menu">
									<ul id="menu" >
										<li><a href="inicio.html"><i class="fa fa-tachometer"></i> <span>Menú</span></a></li>

										 <li id="menu-academico" ><a href="#"><i class="fa fa-table"></i> <span>Recursos Humanos</span> <span class="fa fa-angle-right" style="float: right"></span></a>
										   <ul id="menu-academico-sub" >
											<li id="menu-academico-avaliacoes" ><a href="colaborador.html"> Mantencion Personal</a></li>
											<li id="menu-academico-boletim" ><a href="#">Contratos</a>
												<ul id="menu-mensagens-sub" >
													<li id="menu-mensagens-enviadas" style="width:200px" ><a href="facturacion.html">Individuales</a></li>
													<li id="menu-mensagens-recebidas"  style="width:200px"><a href="#">Masivos</a></li>
														
                                                </ul>
											</li>
											<li id="menu-academico-avaliacoes" ><a href="#">Finiquitos</a>
												<ul id="menu-mensagens-sub" >
													<li id="menu-mensagens-enviadas" style="width:200px" ><a href="facturacion.html">Individuales</a></li>
													<li id="menu-mensagens-recebidas"  style="width:200px"><a href="#">Masivos</a></li>
														
                                                </ul>
											</li>
											<li id="menu-academico-avaliacoes" ><a href="#">Personal Termino Contrato</a></li>
											
										  </ul>
										</li>
										 <li id="menu-academico" ><a href="#"><i class="fa fa-file-text-o"></i> <span>Remuneraciones</span> <span class="fa fa-angle-right" style="float: right"></span></a>
				                 		      <ul id="menu-academico-sub" >
								               <li id="menu-academico-boletim" ><a href="#">Ingresos Masivos</a>
								                   <ul id="menu-academico-sub" >
														<li id="menu-mensagens-enviadas" style="width:210px" ><a href="#" data-toggle="modal" data-target="#myModal14">Carga Masiva Hab/Des</a></li>
														<li id="menu-mensagens-recebidas"  style="width:210px"><a href="#">Ingreso Masivo de Campos</a></li>
														<li id="menu-mensagens-recebidas"  style="width:210px"><a href="#">Ingreso Segun plantilla</a></li>
														<li id="menu-mensagens-recebidas"  style="width:210px"><a href="#">Mantencion de Plantillas</a></li>
										  			</ul>
                                                </li>
												<li id="menu-academico-boletim" ><a href="#">Control de Asistencia</a></li>
												<li id="menu-academico-boletim" ><a href="#">Horas Extraordinarias</a></li>
												<li id="menu-academico-boletim" ><a href="#">Generacion de Anticipos</a></li>

												<li id="menu-academico-boletim" ><a href="creacion_hab_des.html">Registro Hab/Desc Variables</a></li>
												<li id="menu-academico-boletim" ><a href="#">Movimiento del Personal</a></li>
                                                <li id="menu-academico-boletim" ><a href="#">Calculo de Remuneraciones</a></li>
												<li id="menu-academico-boletim" ><a href="#">Detalle de Remuneraciones</a></li>

											  </ul>
										 </li>

										 <li id="menu-comunicacao" ><a href="#"><i class="fa fa-book"></i> <span>Reportes y Consultas</span><span class="fa fa-angle-right" style="float: right"></span></a>
									  		<ul id="menu-comunicacao-sub" >
												<li id="menu-mensagens" style="width:200px" ><a href="#">Reportes Legales</a></li>
												<li id="menu-mensagens" style="width:200px" ><a href="#">Otros Reportes</a></li>
												<li id="menu-mensagens" style="width:200px" ><a href="#">Procesos Anuales</a>
													<ul id="menu-mensagens-sub" >
														<li id="menu-mensagens-enviadas" style="width:200px" ><a href="facturacion.html">Declaración Jurada 1887</a></li>
                                                    </ul>
												</li>
		
									  		</ul>
										</li>

										<li id="menu-comunicacao" ><a href="#"><i class="fa fa-list"></i> <span>Opc. de Configuracion</span><span class="fa fa-angle-right" style="float: right"></span></a>
									  		<ul id="menu-comunicacao-sub" >
												<li id="menu-mensagens" style="width:200px" ><a href="#" data-toggle="modal" data-target="#myModal13">Parámetros Mensuales</a></li>
												<li id="menu-mensagens" style="width:200px" ><a href="#">Otros Aportes</a></li>
												<li id="menu-mensagens" style="width:200px" ><a href="impuesto_unico.html">Impuesto Único</a></li>
												<li id="menu-mensagens" style="width:200px" ><a href="creacion_hab_des.html">Creación de Hab/Des</a></li>
												<li id="menu-mensagens" style="width:200px" ><a href="#">Fáctores de Actualización</a></li>
												<li id="menu-mensagens" style="width:200px" ><a href="#">Feriados</a></li>
												<li id="menu-mensagens" style="width:200px" ><a href="#">Cuentas Centralización</a></li>
									  		</ul>
										</li>

                                     <li id="menu-comunicacao" ><a href="#"><i class="fa fa-list"></i> <span>Procesos Mensuales</span><span class="fa fa-angle-right" style="float: right"></span></a>
                                        <ul>
											<li><a href="#"> Centralizacion Mensual</a></li>
						           			<li><a href="#"> Cierre Mensual</a></li>
						           			<li id="menu-academico-boletim" ><a href="#">Transferencia a Bancos y Otros</a>
                                                <ul id="menu-mensagens-sub" >
													<li id="menu-mensagens-enviadas" style="width:200px" ><a href="facturacion.html">Traspaso a Bancos</a></li>
													<li id="menu-mensagens-recebidas"  style="width:200px"><a href="#">Previred</a></li>
													<li id="menu-mensagens-recebidas"  style="width:200px"><a href="#">Emisión de Cheques</a></li>
                                                </ul> 
                                            </li>
									 	</ul>


                                     </li>
									
									 <li><a href="#"><i class="fa fa-folder" aria-hidden="true"></i><span>Aux. Remuneraciones</span><span class="fa fa-angle-right" style="float: right"></span></a>
									   	<ul>
											<li><a href="#" data-toggle="modal" data-target="#myModal5"> Control de Vacaciones</a></li>
											<li><a href="#" data-toggle="modal" data-target="#myModal10"> Licencias Medicas</a></li>
											
									  	</ul>
									</li>

									<li id="menu-academico" ><a href="#"><i class="fa fa-user"></i> <span>Seguridad</span> <span class="fa fa-angle-right" style="float: right"></span></a>
										  <ul id="menu-academico-sub" >
										    <li id="menu-academico-avaliacoes" ><a href="#">Usuarios</a></li>
										    <li id="menu-academico-boletim" ><a href="#">Roles</a></li>
										    <li id="menu-academico-boletim" ><a href="#">Bitacora</a></li>
											
										  </ul>
									 </li>	
								</ul>
								</div>
							  </div>
							  <div class="clearfix"></div>		
							</div>
							<script>
							var toggle = true;
										
							$(".sidebar-icon").click(function() {                
							  if (toggle)
							  {
								$(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
								$("#menu span").css({"position":"absolute"});
							  }
							  else
							  {
								$(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
								setTimeout(function() {
								  $("#menu span").css({"position":"relative"});
								}, 400);
							  }
											
											toggle = !toggle;
										});
							</script>
<!--js -->
<link rel="stylesheet" href="<?php echo base_url();?>css/vroom.css">
<script type="text/javascript" src="<?php echo base_url();?>js/vroom.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/TweenLite.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/CSSPlugin.min.js"></script>
<script src="<?php echo base_url();?>js/jquery.nicescroll.js"></script>
<script src="<?php echo base_url();?>js/scripts.js"></script>

<!-- Bootstrap Core JavaScript -->
   <script src="<?php echo base_url();?>js/bootstrap.min.js"></script>

<!-- /Script Modal -->
<script>
	$('#myModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget)  
})
</script>
<!-- /Script Modal -->

<!-- /Script archivo -->
<script>
	$("#archivo").fileinput();
</script>
<!-- /Script archivo -->

</body>
</html>
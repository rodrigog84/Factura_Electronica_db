									<!--sub-heard-part-->
									  <div class="sub-heard-part">
									   <ol class="breadcrumb m-b-0">
											<li><a href="inicio.html">Inicio</a></li>
											<li class="active">Env&iacute;o Respuesta Intercambio</li>
										</ol>
									   </div>
								  <!--//sub-heard-part-->


										
										<form action="<?php echo base_url();?>facturaselectronicas/put_empresa" id="basicBootstrapForm" method="POST"  enctype="multipart/form-data">	



 					<div class="panel panel-inverse">                       
                                <div class="panel-heading">
                                      <h4 class="panel-title">Informaci&oacute;n DTE</h4>
                                  </div>
                      <div class="panel-body">
                        <div class='row'>
                          <div class='col-md-6'>
                            <div class="form-group">
                                  <label for="caja">Contacto</label>    
                                  <input type="text" name="contacto" class="form-control" id="contacto" placeholder="Ingrese Contacto" value="">
                            </div>  
                          </div>

                        </div>

  						<div class='row'>
                          <div class='col-md-6'>
                            <div class="form-group">
                                  <label for="caja">Email Contacto</label>    
                                  <input type="text" name="email_contacto" class="form-control" id="email_contacto" placeholder="Ingrese Email Contacto" value="">
                            </div>  
                          </div>


                        </div>  

                        <div class='row'>                       

						<div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Recinto</label>    
                                  <input type="text" name="recinto" class="form-control" id="recinto" placeholder="Ingrese Recinto" value="">
                            </div>  
                          </div>

						
                        </div>   



                        <div class='row'>                       

						<div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Responder A</label>    
                                  <input type="text" name="responder" class="form-control" id="responder" placeholder="Ingrese Destinatario Respuesta" value="">
                            </div>  
                          </div>

						
                        </div> 


						<div class='row'>                       

						<div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Estado Env&iacute;o</label>    
                                  <select class="form-control" name="estado_envio" id="estado_envio">
                                  	<option value="0">Env&iacute;o Recibido Conforme</option>
                                  	<option value="1">Env&iacute;o Rechazado - Error de Schema</option>
                                  	<option value="2">Env&iacute;o Rechazado - Error de Firma</option>
                                  	<option value="3">Env&iacute;o Rechazado - RUT Receptor No Corresponde</option>
                                  	<option value="90">Env&iacute;o Rechazado - Archivo Repetido</option>
                                  	<option value="91">Env&iacute;o Rechazado - Archivo Ilegible</option>
                                  	<option value="99">Env&iacute;o Rechazado - Otros</option>
                                  </select>
                            </div>  
                          </div>

						
                        </div>                           

                      
						<div class='row'>                       

						<div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Acuse Recibo de Mercader&iacute;as o Servicios</label> &nbsp;&nbsp;  
                                 <input type="checkbox" name="mercaderias" id="mercaderias" class="minimal" />  
                            </div>  
                          </div>

						
                        </div>  
                        


                        </div>
                 
                  </div><br><br>


				<div class="panel panel-inverse">                       
                                <div class="panel-heading">
                                      <h4 class="panel-title">Informaci&oacute;n Documentos</h4>
                                  </div>
                      <div class="panel-body">
                        <div class='row'>
                          <div class='col-md-6'>
                            <div class="form-group">
                                  <label for="caja">Contacto</label>    
                                  <input type="text" name="contacto" class="form-control" id="contacto" placeholder="Ingrese Contacto" value="">
                            </div>  
                          </div>

                        </div>

  						<div class='row'>
                          <div class='col-md-6'>
                            <div class="form-group">
                                  <label for="caja">Email Contacto</label>    
                                  <input type="text" name="email_contacto" class="form-control" id="email_contacto" placeholder="Ingrese Email Contacto" value="">
                            </div>  
                          </div>


                        </div>  

                        <div class='row'>                       

						<div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Recinto</label>    
                                  <input type="text" name="recinto" class="form-control" id="recinto" placeholder="Ingrese Recinto" value="">
                            </div>  
                          </div>

						
                        </div>   



                        <div class='row'>                       

						<div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Responder A</label>    
                                  <input type="text" name="responder" class="form-control" id="responder" placeholder="Ingrese Destinatario Respuesta" value="">
                            </div>  
                          </div>

						
                        </div> 


						<div class='row'>                       

						<div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Estado Env&iacute;o</label>    
                                  <select class="form-control" name="estado_envio" id="estado_envio">
                                  	<option value="0">Env&iacute;o Recibido Conforme</option>
                                  	<option value="1">Env&iacute;o Rechazado - Error de Schema</option>
                                  	<option value="2">Env&iacute;o Rechazado - Error de Firma</option>
                                  	<option value="3">Env&iacute;o Rechazado - RUT Receptor No Corresponde</option>
                                  	<option value="90">Env&iacute;o Rechazado - Archivo Repetido</option>
                                  	<option value="91">Env&iacute;o Rechazado - Archivo Ilegible</option>
                                  	<option value="99">Env&iacute;o Rechazado - Otros</option>
                                  </select>
                            </div>  
                          </div>

						
                        </div>                           

                      
						<div class='row'>                       

						<div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Acuse Recibo de Mercader&iacute;as o Servicios</label> &nbsp;&nbsp;  
                                 <input type="checkbox" name="mercaderias" id="mercaderias" class="minimal" />  
                            </div>  
                          </div>

						
                        </div>  
                        


                        </div>
                 
                  </div>                  

			</form>


<script>

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });

</script>
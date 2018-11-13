<!--sub-heard-part-->
									  <div class="sub-heard-part">
									   <ol class="breadcrumb m-b-0">
											<li><a href="inicio.html">Inicio</a></li>
											<li class="active">Configuración de Email</li>
										</ol>
									   </div>
								  <!--//sub-heard-part-->
								 
									<div class="graph-visual tables-main">
										<form action="<?php echo base_url();?>facturaselectronicas/registro_email" method="POST" id="basicBootstrapForm">	

					<div class="panel panel-inverse">                       
                                <div class="panel-heading">
                                      <h4 class="panel-title">Contacto SII</h4>
                                  </div>
                      <div class="panel-body">
                        <div class='row'>

                        <div class='col-md-6'>
                         <div class="form-group">
                                <div class="form-group">
                                  <label for="exampleInputFile">Email</label>
                                  <input type="text" class="form-control input-sm" id="email_contacto" placeholder="Email" name="email_contacto" value="<?php echo $datosform['email_contacto'];?>">
                                </div>
                        </div>                
                        </div>                                  


						<div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Password</label>    
								<input type="password" class="form-control" id="pass_contacto" placeholder="Contraseña" name="pass_contacto" value="<?php echo $datosform['pass_contacto'];?>"> 
                            </div>  
                          </div>

                        </div>



                        <div class='row'>

                        <div class='col-md-6'>
                         <div class="form-group">
                                <div class="form-group">
                                  <label for="exampleInputFile">Tipo Server</label>
                                  <select name="tipoServer_contacto" id="tipoServer_contacto" class="form-control">
														<option value="">Seleccione.</option>
														<option value="smtp" <?php echo $datosform['tserver_contacto'] == 'smtp' ? 'selected' : '';?>>SMTP</option>
														<option value="imap" <?php echo $datosform['tserver_contacto'] == 'imap' ? 'selected' : '';?>>IMAP</option>
													</select>
                                </div>
                        </div>                
                        </div>                                  


						              <div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Puerto</label>    
								<input type="text" class="form-control" id="port_contacto" name="port_contacto" placeholder="999" value="<?php echo $datosform['port_contacto'];?>">
                            </div>  
                          </div>

                        </div>


                        <div class='row'>

                        <div class='col-md-6'>
                         <div class="form-group">
                                <div class="form-group">
                                  <label for="exampleInputFile">Host</label>
                                  <input type="text" class="form-control" id="host_contacto" name="host_contacto" placeholder="ssl://smtp.gmail.com" value="<?php echo $datosform['host_contacto'];?>">
                                </div>
                        </div>                
                        </div>                                  


                        </div>


                        </div>
                          
                  </div>
                  <br>

				<div class="panel panel-inverse">                       
                                <div class="panel-heading">
                                      <h4 class="panel-title">Email Intercambio</h4>
                                  </div>
                      <div class="panel-body">
                        <div class='row'>

                        <div class='col-md-6'>
                         <div class="form-group">
                                <div class="form-group">
                                  <label for="exampleInputFile">Email</label>
                                  <input type="text" class="form-control input-sm" id="email_intercambio" name="email_intercambio" placeholder="Email" value="<?php echo $datosform['email_intercambio'];?>">
                                </div>
                        </div>                
                        </div>                                  


						<div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Password</label>    
								<input type="password" class="form-control" id="pass_intercambio" name="pass_intercambio" placeholder="Contraseña" value="<?php echo $datosform['pass_intercambio'];?>"> 
                            </div>  
                          </div>

                        </div>



                        <div class='row'>

                        <div class='col-md-6'>
                         <div class="form-group">
                                <div class="form-group">
                                  <label for="exampleInputFile">Tipo Server</label>
                                  <select name="tipoServer_intercambio" id="tipoServer_intercambio" class="form-control">
														<option value="">Seleccione.</option>
														<option value="smtp" <?php echo $datosform['tserver_intercambio'] == 'smtp' ? 'selected' : '';?>>SMTP</option>
														<option value="imap" <?php echo $datosform['tserver_intercambio'] == 'imap' ? 'selected' : '';?>>IMAP</option>
													</select>
                                </div>
                        </div>                
                        </div>                                  


						              <div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Puerto</label>    
								<input type="text" class="form-control" id="port_intercambio" name="port_intercambio" placeholder="999" value="<?php echo $datosform['port_intercambio'];?>">
                            </div>  
                          </div>

                        </div>


                        <div class='row'>

                        <div class='col-md-6'>
                         <div class="form-group">
                                <div class="form-group">
                                  <label for="exampleInputFile">Host</label>
                                  <input type="text" class="form-control" id="host_intercambio" name="host_intercambio" placeholder="ssl://smtp.gmail.com" value="<?php echo $datosform['host_intercambio'];?>">
                                </div>
                        </div>                
                        </div>                                  


                        </div>


                        </div>
                           
                      <div class="panel-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>&nbsp;&nbsp;
                      </div>

                 	</form>
                  </div>                  



<script>

$(document).ready(function() {




 $('#basicBootstrapForm').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {

			email_contacto: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Email de Contacto es requerido'
                    },                  
                    emailAddress: {
                        message: 'El valor ingresado no es una direcci&oacute; de email valida'
                    }                    
                }
            },

			pass_contacto: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Password de Contacto es requerido'
                    },                  
                
                }
            },



            tipoServer_contacto: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Tipo Server de Contacto es requerido'
                    },                  
                
                }
            },

			port_contacto: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Puerto de Contacto es requerido'
                    },                  
                
                }
            },            

    		host_contacto: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Tipo Server de Contacto es requerido'
                    },                  
                
                }
            }, 

            email_intercambio: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Email de Intercambio es requerido'
                    },                  
                    emailAddress: {
                        message: 'El valor ingresado no es una direcci&oacute; de email valida'
                    }                    
                }
            },

			pass_intercambio: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Password de Intercambio es requerido'
                    },                  
                
                }
            },



            tipoServer_intercambio: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Tipo Server de Intercambio es requerido'
                    },                  
                
                }
            },

			port_intercambio: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Puerto de Intercambio es requerido'
                    },                  
                
                }
            },            

    		host_intercambio: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Tipo Server de Intercambio es requerido'
                    },                  
                
                }
            },             

        }
    });
 


})



</script>								
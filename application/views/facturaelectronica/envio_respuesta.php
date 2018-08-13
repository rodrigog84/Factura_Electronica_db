									<!--sub-heard-part-->
									  <div class="sub-heard-part">
									   <ol class="breadcrumb m-b-0">
											<li><a href="inicio.html">Inicio</a></li>
											<li class="active">Env&iacute;o Respuesta Intercambio</li>
										</ol>
									   </div>
								  <!--//sub-heard-part-->


										
										<form action="<?php echo base_url();?>facturaselectronicas/envio_acuse_recibo" id="form_envio_acuse" method="POST"  enctype="multipart/form-data">	



 					<div class="panel panel-inverse">                       
                                <div class="panel-heading">
                                      <h4 class="panel-title">Informaci&oacute;n DTE</h4>
                                  </div>
                      <div class="panel-body">
                        <!--div class='row'>
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

						
                        </div-->   



                        <div class='row'>                       

						<div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Responder A</label>    
                                  <input type="text" name="responder" class="form-control" id="responder" placeholder="Ingrese Destinatario Respuesta" value="<?php echo $datos_factura->proveemail;?>">
                            </div>  
                          </div>

						
                        </div> 


						<div class='row'>                       

						<div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Estado Env&iacute;o</label>    
                                  <select class="form-control" name="estado_envio" id="estado_envio">
                                  	<option value="0" <?php echo $resumen_dte['estado'] == 0 ? 'selected' : ''; ?>>Env&iacute;o Recibido Conforme</option>
                                  	<option value="1" <?php echo $resumen_dte['estado'] == 1 ? 'selected' : ''; ?>>Env&iacute;o Rechazado - Error de Schema</option>
                                  	<option value="2" <?php echo $resumen_dte['estado'] == 2 ? 'selected' : ''; ?>>Env&iacute;o Rechazado - Error de Firma</option>
                                  	<option value="3" <?php echo $resumen_dte['estado'] == 3 ? 'selected' : ''; ?>>Env&iacute;o Rechazado - RUT Receptor No Corresponde</option>
                                  	<option value="90" <?php echo $resumen_dte['estado'] == 90 ? 'selected' : ''; ?>>Env&iacute;o Rechazado - Archivo Repetido</option>
                                  	<option value="91" <?php echo $resumen_dte['estado'] == 91 ? 'selected' : ''; ?>>Env&iacute;o Rechazado - Archivo Ilegible</option>
                                  	<option value="99" <?php echo $resumen_dte['estado'] == 99 ? 'selected' : ''; ?>>Env&iacute;o Rechazado - Otros</option>
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

                          <div class='col-md-12'>
								<table class="table">
									<thead>
										<tr>
											<th>#</th>
											<th>Tipo DTE</th>
											<th>Folio</th>
											<th>Fecha Documento</th>
											<th>Rut Emisor</th>
											<th>Total ($)</th>
											<th>Estado</th>
										</tr>
									</thead>
									<tbody>
										<?php $i = 1; ?>
										<?php foreach ($resumen_dte['resumen_documentos'] as $documentos) { ?>
										<tr>
											<td><?php echo $i;?></td>
											<td><?php echo $documentos['TipoDTE'];?></td>
											<td><?php echo $documentos['Folio'];?></td>
											<td><?php echo $documentos['FchEmis'];?></td>
											<td><?php echo $documentos['RUTEmisor'];?></td>
											<td><?php echo number_format($documentos['MntTotal'],0,".",".");?></td>
											<td><select class="form-control" name="estado_documento-<?php echo $documentos['TipoDTE'];?>-<?php echo $documentos['Folio'];?>" id="estado_documento">
													<option value="0" <?php echo $documentos['EstadoRecepDTE'] == 0 ? 'selected' : ''; ?>>DTE Recibido OK</option>
													<option value="1" <?php echo $documentos['EstadoRecepDTE'] == 1 ? 'selected' : ''; ?>>DTE No Recibido - Error de Firma</option>
													<option value="2" <?php echo $documentos['EstadoRecepDTE'] == 2 ? 'selected' : ''; ?>>DTE No Recibido - Error en RUT Emisor</option>
													<option value="3" <?php echo $documentos['EstadoRecepDTE'] == 3 ? 'selected' : ''; ?>>DTE No Recibido - Error en RUT Receptor</option>
													<option value="4" <?php echo $documentos['EstadoRecepDTE'] == 4 ? 'selected' : ''; ?>>DTE No Recibido - DTE Repetido</option>
													<option value="99" <?php echo $documentos['EstadoRecepDTE'] == 99 ? 'selected' : ''; ?>>DTE No Recibido - Otros</option>
												</select>
											</td>
										</tr>
										<?php $i++; ?>
										<?php } ?>
									</tbody>
								</table>
							</div>

                          </div>

                        </div>
  						<div class="panel-footer">
  							<!--button class = "btn btn-info" id="comando" data-toggle="modal" data-target="#confirm-send">Generar Respuesta Intercambio	</button-->
  							<input type="hidden" name="idfactura" value="<?php echo $resumen_dte['idfactura'];?>" >
  							<a href="#"  title="Generar Respuesta Intercambio" class="btn btn-info" data-toggle="modal" data-target="#confirm-send">Generar Respuesta Intercambio</a>
  						</div>





                        </div>
                 
                  </div>                  

			</form>
    <div class="modal fade" id="confirm-send" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirmar Env&iacute;o</h4>
                </div>
            
                <div class="modal-body">
                    <p>Se Generar&aacute; y enviar&aacute; acuse de recibo.&nbsp;&nbsp;</p>
                    <p>Desea continuar?</p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-success btn-ok" id="enviar_acuse">Enviar</a>
                </div>
            </div>
        </div>
    </div>

<script>

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });

</script>

 <script>
        $('#confirm-send').on('show.bs.modal', function(e) {

            //$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            
        });

        $('#enviar_acuse').on('click',function(){

        		$('#form_envio_acuse').submit();

        })
    </script>

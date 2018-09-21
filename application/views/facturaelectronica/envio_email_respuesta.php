									<!--sub-heard-part-->
									  <div class="sub-heard-part">
									   <ol class="breadcrumb m-b-0">
											<li><a href="inicio.html">Inicio</a></li>
											<li class="active">Env&iacute;o Respuesta Intercambio</li>
										</ol>
									   </div>
								  <!--//sub-heard-part-->


										
										<form action="<?php echo base_url();?>facturaselectronicas/envio_email_acuse_recibo" id="form_envio_acuse" method="POST" >	



 					<div class="panel panel-inverse">                       
                                <div class="panel-heading">
                                      <h4 class="panel-title">Informaci&oacute;n DTE</h4>
                                  </div>
                      <div class="panel-body">
                        


                        <div class='row'>                       

						<div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Responder A</label>    
                                  <input type="text" name="responder" class="form-control" id="responder" placeholder="Ingrese Destinatario Respuesta" value="<?php echo $datos_factura->proveemail;?>">
                            </div>  
                          </div>

						
                        </div> 


					
       


                        </div>
                        <div class="panel-footer">
                <!--button class = "btn btn-info" id="comando" data-toggle="modal" data-target="#confirm-send">Generar Respuesta Intercambio  </button-->
                <input type="hidden" name="idfactura" value="<?php echo $resumen_dte['idfactura'];?>" >
                <!--a href="#"  title="Generar Email Intercambio" class="btn btn-info" data-toggle="modal" data-target="#confirm-send">Generar Email Intercambio</a-->
                <input type="submit" title="Generar Email Intercambio" class="btn btn-info" value="Generar Email Intercambio">
              </div>
                 
                  </div><br><br>


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
                    <p>Se enviar&aacute; a email de proveedor acuse de recibo.&nbsp;&nbsp;</p>
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
    /*    $('#confirm-send').on('show.bs.modal', function(e) {

            //$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            
        });

        $('#enviar_acuse').on('click',function(){
          console.log("envio");
        		$('#form_envio_acuse').submit();

        })
*/
$(document).ready(function() {

         $('#form_envio_acuse').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
      

            responder: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Email es requerido'
                    },                  
                    emailAddress: {
                        message: 'El valor ingresado no es una direcci&oacute; de email valida'
                    }                    
                }
            },       


        }
    });


});

    </script>

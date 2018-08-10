									<!--sub-heard-part-->
									  <div class="sub-heard-part">
									   <ol class="breadcrumb m-b-0">
											<li><a href="inicio.html">Inicio</a></li>
											<li class="active">Carga Certificado Digital</li>
										</ol>
									   </div>
								  <!--//sub-heard-part-->

        <?php if(isset($message)): ?>
         <div class="row">
            <div class="col-md-12">
                    <div class="alert alert-<?php echo $classmessage; ?> alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa <?php echo $icon;?>"></i> Alerta!</h4>
                      <?php echo $message;?>
                    </div>
            </div>   
          </div>
          <?php endif; ?>
										
										<form action="<?php echo base_url();?>facturaselectronicas/cargacertificado" id="basicBootstrapForm" method="POST"  enctype="multipart/form-data">	



 					<div class="panel panel-inverse">                       
                                <div class="panel-heading">
                                      <h4 class="panel-title">Carga Certificado Digital</h4>
                                  </div>
                      <div class="panel-body">
                        <div class='row'>

                        <div class='col-md-6'>
                         <div class="form-group">
                                <div class="form-group">
                                  <label for="exampleInputFile">Adjuntar Certificado Digital (.p12 o .pfx)</label>
                                  <input type="file" id="certificado" name="certificado" accept=".p12,.pfx">
                                </div>
                        </div>                
                        </div>                                  


						              <div class='col-md-6'>
                            <div class="form-group">
                            	<label for="caja">Password</label>    
                                  <input type="password" name="password" class="form-control" id="password" value="">
                            </div>  
                          </div>

                        </div>


                        </div>
                           
                      <div class="panel-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>&nbsp;&nbsp;
                      </div>

                 
                  </div>

			</form>


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

			certificado: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Certificado Digital es requerida'
                    }
                }
            },


            password: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Password es requerida'
                    },
                  
                }
            },



        }
    });
 


})



</script>
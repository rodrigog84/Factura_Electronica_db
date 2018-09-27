<form id="formotros" action="<?php echo base_url();?>rrhh/submit_mut_caja" method="post" role="form" enctype="multipart/form-data">
                            <div class="panel panel-inverse">                       
                                <div class="panel-heading">
                                      <h4 class="panel-title">Listado Documentos de Clientes</h4>
                                  </div>
                      <div class="panel-body">
                        <div class='row'>
										<table class="table"> 
																	<thead> 
																		<tr>
																			
																			<th><small>Cliente</small></th> 
																			<th><small>Rut</small></th> 
																			<th><small>Email</small></th> 
																			<th><small>Fecha Documento</small></th> 
																			<th><small>Ver Documento</small></th> 
																			<th><small>Ver XML</small></th> 
																			

																		</tr> 
																	</thead> 
																	<tbody> 
												                    <?php $i = 1; ?>
												                    <?php if(count($datos_factura) > 0){ ?>
												                    <?php foreach ($datos_factura as $facturas) { ?>	
												                    <?php $mercaderias = $facturas->envios_recibos == 1 ? 1 : 0; ?>		
												                    <?php //echo "<pre>"; print_r($facturas); exit; ?>
																		<tr >
																			<td><small><?php echo $facturas->proveenombre;?></small></td>
																			<td><small><?php echo $facturas->rutemisor;?></small></td>
																			<td><small><?php echo $facturas->proveemail;?></small></td>
																			<td><small><?php echo $facturas->fecemision;?></small></td>
																			<td><small><a href="<?php echo base_url();?>facturacion_electronica/pdf/dte_76568660-1_T33F18199954.pdf" target="_blank"><i class="fa fa-file-pdf-o fa-2x" ></i></a></small></td>
																			<td><small><a href="<?php echo base_url();?>facturacion_electronica/dte/201804/2_61_7_SII_233439.xml" target="_blank"><i class="fa fa-file-o fa-2x" ></i></a></small></td>
																			
																		</tr> 
												                      <?php $i++; ?>
												                    <?php } ?>													
												                    <?php }else{ ?>
												                    	<tr ><td colspan="9">No existen facturas de proveedor disponibles </td></tr>

												                    <?php } ?>	
																	</tbody> 
																</table>                           
                        </div>
                        
                      </div><!-- /.box-body -->

                 
                  </div> 
                  </div>
    </form>                   
                
<div class="modal fade" id="show-xml" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Descarga XML</h4>
                </div>
            
                <div class="modal-body">
                	<div class="row" >
                		<div class='col-md-4' id="div_envio_recibos">
                				<div class="form-group">
                            		<label for="caja">Env&iacute;o Recibo</label>  
                				<a href="#" id="lnk_envio_recibos" target="_blank"><img src="<?php echo base_url();?>images/29611.svg" width="40%" height="40%"></a>
                		</div>
                	</div>
                	<div class="row">
                		<div class='col-md-4'>
                				<div class="form-group">
                            		<label for="caja">Recepci&oacute;n DTE</label>  
                				<a href="#" id="lnk_recepcion_dte"  target="_blank"><img src="<?php echo base_url();?>images/29611.svg" width="40%" height="40%"></a>
                		</div>
                	</div>
                	<div class="row">
                		<div class='col-md-4'>
                				<div class="form-group">
                            		<label for="caja">Resultado DTE</label>  
                				<a href="#" id="lnk_resultado_dte" target="_blank"><img src="<?php echo base_url();?>images/29611.svg" width="40%" height="40%"></a>
                		</div>
                	</div>                	                	
       
                	</div>           	                    
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

<script>

    $(document).ready(function() {

    	$('.lnk_xml').click(function(){
    		if($(this).data('mercaderias') == '1'){
    			$('#lnk_envio_recibos').attr('disabled','disabled');
    			$('#div_envio_recibos').hide();
    		}else{
    			$('#lnk_envio_recibos').attr('disabled',false);
	    		$('#lnk_envio_recibos').attr('href','<?php echo base_url();?>facturacion_electronica/acuse_recibo/'+$(this).data('path')+'/'+$(this).data('envrec'));
	    		$('#div_envio_recibos').show();

    		}

			$('#lnk_recepcion_dte').attr('href','<?php echo base_url();?>facturacion_electronica/acuse_recibo/'+$(this).data('path')+'/'+$(this).data('recdte'));


			$('#lnk_resultado_dte').attr('href','<?php echo base_url();?>facturacion_electronica/acuse_recibo/'+$(this).data('path')+'/'+$(this).data('resdte'));
    	});


        <?php if(isset($message)){ ?>

          $.gritter.add({
            title: 'Atención',
            text: '<?php echo $message;?>',
            sticky: false,
            image: '<?php echo base_url();?>images/logos/<?php echo $classmessage == 'success' ? 'check_ok_accept_apply_1582.png' : 'alert-icon.png';?>',
            time: 5000,
            class_name: 'my-sticky-class'
        });
        /*setTimeout(redirige, 1500);
        function redirige(){
            location.href = '<?php //echo base_url();?>welcome/dashboard';
        }*/
        <?php } ?>


    });
</script>
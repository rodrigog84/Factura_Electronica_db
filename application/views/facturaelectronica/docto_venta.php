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
																			<th><small>Nro. Docto</small></th> 
                                                                            <th><small>Tipo Docto</small></th> 
                                                                            <th><small>Fecha Emisi&oacute;n</small></th> 
                                                                            <th><small>Fecha Venc</small></th> 
                                                                            <th><small>Rut</small></th> 
																			<th><small>Raz&oacute;n Social</small></th>
																			<th><small>Total</small></th> 
																			<th><small>Ver Documento</small></th> 
																			<th><small>Ver XML</small></th> 
                                                                            <th><small>Env&iacute;o DTE</small></th> 
																			

																		</tr> 
																	</thead> 
																	<tbody> 
												                    <?php $i = 1; ?>
												                    <?php if(count($datos_factura) > 0){ ?>
												                    <?php foreach ($datos_factura as $facturas) { ?>	
												                    <?php //echo "<pre>"; print_r($facturas); exit; ?>
																		<tr >
																			<td><small><?php echo $facturas->num_factura;?></small></td>
																			<td><small><?php echo $facturas->tipo_docto;?></small></td>
																			<td><small><?php echo $facturas->fecha_factura;?></small></td>
																			<td><small><?php echo $facturas->fecha_venc;?></small></td>
                                                                            <td><small><?php echo substr($facturas->rut,0,strlen($facturas->rut)-1)."-".substr($facturas->rut,-1);?></small></td>
                                                                            <td><small><?php echo $facturas->razon_social;?></small></td>
                                                                            <td><small><?php echo number_format($facturas->totalfactura,0,".",".");?></small></td>
																			<td><small><a href="<?php echo base_url();?>facturaselectronicas/exportPDF/<?php echo $facturas->id;?>" target="_blank"><i class="fa fa-file-pdf-o fa-2x" ></i></a></small></td>
																			<td><small><a href="<?php echo base_url();?>facturaselectronicas/ver_dte/<?php echo $facturas->id;?>" target="_blank"><i class="fa fa-file-o fa-2x" ></i></a></small></td>
                                                                            <td><small><a href="<?php echo base_url();?>facturaselectronicas/ver_dte/<?php echo $facturas->id;?>" target="_blank"><i class="fa fa-mail-reply-all fa-2x" ></i></a></small></td>
																			
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
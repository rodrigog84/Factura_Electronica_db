<form id="formotros" action="<?php echo base_url();?>rrhh/submit_mut_caja" method="post" role="form" enctype="multipart/form-data">
                            <div class="panel panel-inverse">                       
                                <div class="panel-heading">
                                      <h4 class="panel-title">Listado Facturas Proveedores</h4>
                                  </div>
                      <div class="panel-body">
                        <div class='row'>
										<table class="table"> 
																	<thead> 
																		<tr>
																			
																			<th><small>Proveedor</small></th> 
																			<th><small>Rut</small></th> 
																			<th><small>Email</small></th> 
																			<th><small>Fecha Documento</small></th> 
																			<th><small>Fecha Genera Acuse</small></th>
																			<th><small>Fecha Env&iacute;o</small></th> 
																			<th><small>Fecha Lectura</small></th> 
																			<th><small>Ver Documento</small></th> 
																			<th><small>Respuesta</small></th> 
																			

																		</tr> 
																	</thead> 
																	<tbody> 
												                    <?php $i = 1; ?>
												                    <?php foreach ($datos_factura as $facturas) { ?>														
												                    <?php //echo "<pre>"; print_r($facturas); exit; ?>
																		<tr >
																			<td><small><?php echo $facturas->proveenombre;?></small></td>
																			<td><small><?php echo $facturas->rutemisor;?></small></td>
																			<td><small><?php echo $facturas->proveemail;?></small></td>
																			<td><small><?php echo $facturas->fecemision;?></small></td>
																			<td><small><?php echo $facturas->fecgeneraacuse;?></small></td>
																			<td><small><?php echo $facturas->fecenvio;?></small></td>
																			<td><small><?php echo $facturas->created_at;?></small></td>
																			<td><small><a href="<?php echo base_url();?>facturaselectronicas/ver_pdf_compra/<?php echo $facturas->id;?>"><i class="fa fa-file-code-o fa-2x" ></i></a></small></td>
																			<td><small><a href="<?php echo base_url();?>facturaselectronicas/envio_respuesta/<?php echo $facturas->id;?>"><i class="fa fa-mail-reply-all fa-2x	" ></i></a></small></td>
																		</tr> 
												                      <?php $i++; ?>
												                    <?php } ?>																		
																	</tbody> 
																</table>                           
                        </div>
                        
                      </div><!-- /.box-body -->

                 
                  </div> 
                  </div>
    </form>                   
                


<script>

    $(document).ready(function() {
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
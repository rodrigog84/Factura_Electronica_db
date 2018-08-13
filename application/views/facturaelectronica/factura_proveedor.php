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
																			<td><small>&nbsp;</small></td>
																			<td><small><?php echo $facturas->created_at;?></small></td>
																			<td><small><a href="<?php echo base_url();?>facturaselectronicas/ver_documento/<?php echo $facturas->id;?>"><i class="fa fa-file-code-o fa-2x" ></i></a></small></td>
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
                


                
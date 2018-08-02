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
																			
																			<th>Raz&oacute;n Social</th> 
																			<th>Rut</th> 
																			<th>Email</th> 
																			<th>Fecha Documento</th> 
																			<th>Fecha Env&iacute;o</th> 
																			<th>Fecha Lectura</th> 
																			<th>Ver Documento</th> 
																			<th>Respuesta</th> 
																			

																		</tr> 
																	</thead> 
																	<tbody> 
												                    <?php $i = 1; ?>
												                    <?php foreach ($datos_factura as $facturas) { ?>														
												                    <?php //echo "<pre>"; print_r($facturas); exit; ?>
																		<tr >
																			<td><?php echo $facturas->razon_social;?></td>
																			<td><?php echo $facturas->rutemisor;?></td>
																			<td><?php echo $facturas->mail;?></td>
																			<td><?php echo $facturas->fecemision;?></td>
																			<td>&nbsp;</td>
																			<td><?php echo $facturas->created_at;?></td>
																			<td><i class="fa fa-pencil-square-o" aria-hidden="true" role="button"></i></td>
																			<td>&nbsp;</td>
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
                
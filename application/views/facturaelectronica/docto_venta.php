<form id="formotros" action="<?php echo base_url();?>rrhh/submit_mut_caja" method="post" role="form" enctype="multipart/form-data">
                            <div class="panel panel-inverse">                       
                                <div class="panel-heading">
                                      <h4 class="panel-title">Listado Documentos de Clientes</h4>
                                  </div>
                      <div class="panel-body">
                        <div class='row'>
										<table class="table" id="listadoFacturas"> 
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
                                                                            <td><small>
                                                                                <a href="#" class="lnk_dte"  data-toggle="modal" data-idfact="<?php echo $facturas->id;?>" data-target="#show-estado_dte" >
                                                                                <i class="fa fa-mail-reply-all fa-2x" ></i></a></small></td>
																			
																		</tr> 
												                      <?php $i++; ?>
												                    <?php } ?>													
												                    <?php }else{ ?>
												                    	<tr ><td colspan="9">No existen documentos disponibles </td></tr>

												                    <?php } ?>	
																	</tbody> 
																</table>                           
                        </div>
                        
                      </div><!-- /.box-body -->

                 
                  </div> 
                  </div>
    </form>                   
                

            
<div class="modal fade" id="show-estado_dte" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
            
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Ver Estado DTE</h4>
                </div>
            
                <div class="modal-body">
                    <div class="row">
                        <div class='col-md-4'>
                            <label><b>Tipo Documento:</b></label>
                        </div>
                        <div class='col-md-8'>
                           <span id="est_dte_tip_doc"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-md-4'>
                            <label><b>Folio:</b></label>
                        </div>
                        <div class='col-md-8'>
                            <span id="est_dte_folio"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-md-4'>
                            <label><b>Identificador de Env&iacute;o:</b></label>
                        </div>
                        <div class='col-md-8'>
                            <span id="est_dte_trackid"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-md-4'>
                            <label><b>Estado del Env&iacute;o:</b></label>
                        </div>
                        <div class='col-md-8'>
                            <span id="est_dte_est_envio"></span>
                        </div>
                    </div> 
                    <div class="row">
                        <div class='col-md-4'>
                            <label><b>Estado del DTE:</b></label>
                        </div>
                        <div class='col-md-8'>
                            <span id="est_dte_est_dte"></span>

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

    	$('.lnk_dte').click(function(){
    		$('#est_dte_tip_doc').html('Factura Electr&oacute;nica');
            $('#est_dte_folio').html('4647');
            $('#est_dte_trackid').html('3345388136');
            $('#est_dte_est_envio').html('EPR - Envio Procesado');
            $('#est_dte_est_dte').html('DTE Recibido - Documento Recibido por el SII. Datos Coinciden con los Registrados');

            console.log($(this).data('idfact'));
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

  $("table#listadoFacturas").DataTable({
                        searching: false,
                        paging:         true,
                        ordering:       false,
                        info:           true,
                        columnDefs: [
                          { targets: 'no-sort', orderable: false }
                        ],
                        //bDestroy:       true,                        
                        "oLanguage": {
                            "sLengthMenu": "_MENU_ Registros por p&aacute;gina",
                            "sZeroRecords": "No se encontraron registros",
                            "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ registros",
                            "sInfoEmpty": "Mostrando 0 de 0 registros",
                            "sInfoFiltered": "(filtrado de _MAX_ registros totales)",
                            "sSearch":        "Buscar:",
                            "sProcessing" : '<img src="<?php echo base_url(); ?>images/gif/spin2.svg" height="42" width="42" >',
                            "oPaginate": {
                                "sFirst":    "Primero",
                                "sLast":    "Último",
                                "sNext":    "Siguiente",
                                "sPrevious": "Anterior"
                            }
                        },
                        lengthMenu: [[10, 50, 100, -1], [10, 50, 100, "All"]]                              

        });       
</script>
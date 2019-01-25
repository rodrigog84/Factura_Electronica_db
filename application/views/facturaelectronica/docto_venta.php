<form id="formotros" action="<?php echo base_url();?>facturaselectronicas/docto_venta" method="post" role="form" enctype="multipart/form-data">

    <div class="panel panel-inverse">                       
        <div class="panel-heading">
              <h4 class="panel-title">Buscar Entre Documentos Emitidos</h4>
          </div>
          <div class="form-group" style="text-align:center;">                             
            <label for="">TIPOS DE DOCUMENTOS</label><br/>
            <select name="tipodoc" id="tipodoc" class="selectpicker" data-live-search="true">
            <option data-tokens="todos">TODOS LOS DOCUMENTOS</option>
            <option data-tokens="101">FACTURA ELECTRONICA</option>
            <option data-tokens="103">FACTURA EXCENTA ELECTRONICA</option>
            <option data-tokens="102">FACTURA COMPRA ELECTRONICA</option>
            <option data-tokens="104">NOTA DE CREDITO ELECTRONICA</option>
            <option data-tokens="107">NOTA DE DEBITO ELECTRONICA</option>
            <option data-tokens="105">GUIA DESPACHO ELECTRONICA</option>
            </select>                                          
           </div>                         
          <div class="panel-body">
            <div class='row'>
              <div class='col-md-6'>
                <div class="form-group">
                      <label for="caja">Rut Receptor</label>    
                      <input type="text" name="rut" class="form-control" id="rut" placeholder="Rut" value="">
                </div>  
              </div>
            <div class='col-md-6'>
                <div class="form-group">
                    <label for="caja">Folio</label>    
                      <input type="text" name="folio" class="form-control" id="folio" placeholder="Folio" value="">
                </div>  
              </div>
            </div>
            <div class='col-md-6'>
                            <div class="form-group">
                                <label for="caja">Fecha Emision Desde</label>
                              <div class="input-group">
                                  <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                  </div>
                                <input placeholder="Fecha desde" class="form-control mask_date" id="fecha_desde" name="fecha_desde"   size="30" type="text" value="" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>
                                </div>                              
                            </div>  
                          </div>
                            <div class='col-md-6'>
                            <div class="form-group">
                                <label for="caja">Fecha Emiison Hasta</label>    

                              <div class="input-group">
                                  <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                  </div>
                                <input placeholder="Fecha hasta" class="form-control mask_date" id="fecha_hasta" name="fecha_hasta"   size="30" type="text" value="" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>
                                </div> 
                              </div> 
                          </div>                           
                        </div>                                              
                        <div class="panel-footer" style="text-align:center;">
                        <button type="submit" class="btn btn-primary">Buscar</button>&nbsp;&nbsp;
                      </div>
                        </div>  
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
                                      <th><small>Estado DTE</small></th>																		</tr> 
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

            var idfactura = $(this).data('idfact');

           $.ajax({
                  type: 'GET',
                  async: false,
                  url: "<?php echo base_url();?>facturaselectronicas/datos_dte_json/"+idfactura,
                 success : function(data) {

                        
                        var obj_datos = JSON.parse(data);
                        console.log(obj_datos);
                        $('#est_dte_tip_doc').html(obj_datos.tipo_doc);
                        $('#est_dte_folio').html(obj_datos.folio);
                        $('#est_dte_trackid').html(obj_datos.trackid);
                        /*$('#est_dte_est_envio').html('EPR - Envio Procesado');
                        $('#est_dte_est_dte').html('DTE Recibido - Documento Recibido por el SII. Datos Coinciden con los Registrados');*/

                    }

            });




           $.ajax({
                  type: 'GET',
                  async: false,
                  url: "<?php echo base_url();?>facturaselectronicas/estado_envio_dte/"+idfactura,
                 success : function(data) {

                        
                        var obj_envio = JSON.parse(data);
                        console.log(obj_envio);
                        var cod_envio = obj_envio.codigo == -11 ? 'Error' : obj_envio.codigo
                        var estado_envio_dte = obj_envio.error ? obj_envio.message : cod_envio + " - " + obj_envio.glosa;                       
                        $('#est_dte_est_envio').html(estado_envio_dte);
                        /*$('#est_dte_est_dte').html('DTE Recibido - Documento Recibido por el SII. Datos Coinciden con los Registrados');*/

                    }

            });

           $.ajax({
                  type: 'GET',
                  async: false,
                  url: "<?php echo base_url();?>facturaselectronicas/estado_dte/"+idfactura,
                 success : function(data) {

                        
                        var obj_estado = JSON.parse(data);
                        var estado_dte = obj_estado.error ? obj_estado.message : obj_estado.glosa_estado + " - " + obj_estado.glosa_err; 
                        console.log(obj_estado);
                      
                        $('#est_dte_est_dte').html(estado_dte);
                        /*$('#est_dte_est_dte').html('DTE Recibido - Documento Recibido por el SII. Datos Coinciden con los Registrados');*/

                    }

            });
            //console.log($(this).data('idfact'));
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
<form id="formotros" action="<?php echo base_url();?>facturaselectronicas/factura_proveedor" method="post" role="form" enctype="multipart/form-data">

    <div class="panel panel-inverse">                       
        <div class="panel-heading">
              <h4 class="panel-title">Buscar Entre Documentos Recibidos</h4>
          </div>
          <div class="form-group" style="text-align:center;">                             
            <label for="">TIPOS DE DOCUMENTOS</label><br/>
            <select name="tipodoc" id="tipodoc" class="selectpicker" data-live-search="true">
            <option data-tokens="todos" <?php echo $tipdoc == 'TODOS LOS DOCUMENTOS' ? 'selected' : ''; ?> >TODOS LOS DOCUMENTOS</option>
            <option data-tokens="101" <?php echo $tipdoc == 'FACTURA ELECTRONICA' ? 'selected' : ''; ?> >FACTURA ELECTRONICA</option>
            <option data-tokens="103" <?php echo $tipdoc == 'FACTURA EXCENTA ELECTRONICA' ? 'selected' : ''; ?> >FACTURA EXCENTA ELECTRONICA</option>
            <option data-tokens="102" <?php echo $tipdoc == 'FACTURA COMPRA ELECTRONICA' ? 'selected' : ''; ?> >FACTURA COMPRA ELECTRONICA</option>
            <option data-tokens="104" <?php echo $tipdoc == 'NOTA DE CREDITO ELECTRONICA' ? 'selected' : ''; ?> >NOTA DE CREDITO ELECTRONICA</option>
            <option data-tokens="107" <?php echo $tipdoc == 'NOTA DE DEBITO ELECTRONICA' ? 'selected' : ''; ?> >NOTA DE DEBITO ELECTRONICA</option>
            <option data-tokens="105" <?php echo $tipdoc == 'GUIA DESPACHO ELECTRONICA' ? 'selected' : ''; ?> >GUIA DESPACHO ELECTRONICA</option>
            </select>                                          
           </div>       
          <div class="panel-body">
            <div class='row'>
              <div class='col-md-6'>
                <div class="form-group">
                      <label for="caja">Rut Emisor</label>    
                      <input type="text" name="rut" class="form-control" id="rut" placeholder="12345345-9" value="<?php echo $rut == '' ? null : $rut;?>">
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
                                <input placeholder="Fecha desde" class="form-control mask_date" id="fecha_desde" name="fecha_desde"   size="30" type="" value="<?php echo $fecha_desde == '' ? date("d-m-Y") : $fecha_desde;?>" data-inputmask="" data-mask>
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
                                <input placeholder="Fecha hasta" class="form-control mask_date" id="fecha_hasta" name="fecha_hasta"   size="30" type="text" value="<?php echo $fecha_hasta == '' ? date("d-m-Y") : $fecha_hasta;?>"" data-inputmask="'alias': 'd/m/Y'" data-mask>
                                </div>                              
                            </div>  
                          </div>
                          <div class="form-group" style="text-align:center;">                                        
                          <label for="exampleInputEmail1">Estado de Docuemntos</label><br/>
                          <select name="estado" id="estado" class="selectpicker" data-live-search="true">
                          <option data-tokens="todos" >Todos</option>
                          <option data-tokens="acuse" <?php echo $estado == 'Acuse Recibo' ? 'selected' : ''; ?> >Acuse Recibo</option>
                          <option data-tokens="pendientes"  <?php echo $estado == 'Pendientes' ? 'selected' : ''; ?>>Pendientes</option>
                          </select>                                          
                         </div> 
                        </div> 
                      
                        <div class="panel-footer" style="text-align:center;">
                        <button type="submit" class="btn btn-primary">Buscar</button>&nbsp;&nbsp;
                      </div>

                        </div>  
                            <div class="panel panel-inverse">                       
                                <div class="panel-heading">
                                      <h4 class="panel-title">Listado Facturas Proveedores
                                      <div class="pull-right box-tools">
                                        <h5><a href="<?php echo base_url(); ?>facturaselectronicas/reporte_factura_proveedor" style="color:white" data-toggle="tooltip" title="Exportar"><i class="fa fa-file-excel-o"></i></a></h5>
                                     </div>
                                     </h4>
                                  </div>
                      <div class="panel-body">
                        <div class='row'>
										<table class="table" id="listadoFacturas"> 
																	<thead> 
																		<tr>

                                                                            <th><small>Tip.Doc</small></th>	
                                                                            <th><small>Folio</small></th>																		
																			<th><small>Proveedor</small></th> 
																			<th><small>Rut</small></th>                                                                            
																			<th><small>Email</small></th> 
																			<th><small>Fecha Documento</small></th> 
																			<th><small>Fecha Genera Acuse</small></th>
																			<th><small>Fecha Env&iacute;o</small></th> 
																			<th><small>Fecha Lectura</small></th>
																			<th><small>Ver Documento</small></th> 
                                                                            <th><small>XML Proveedor</small></th> 
																			<th><small>XML Respuesta</small></th> 
																			<th><small>Respuesta</small></th> 
																			<th><small>Env&iacute;o Email</small></th>
																			

																		</tr> 
																	</thead> 
																	<tbody> 
												                    <?php $i = 1; ?>
												                    <?php if(count($datos_factura) > 0){ ?>
												                    <?php foreach ($datos_factura as $facturas) { ?>	
												                    <?php $mercaderias = $facturas->envios_recibos == 1 ? 1 : 0; ?>		
												                    <?php //echo "<pre>"; print_r($facturas); exit; ?>
																		<tr >
                                                                            <td><small><?php echo $facturas->tipo_documento;?></small></td>
                                                                            <td><small><?php echo $facturas->folio;?></small></td>
																			<td><small><?php echo $facturas->proveenombre;?></small></td>
																			<td><small><?php echo $facturas->rutemisor;?></small></td>
                                                                            <td><small><?php echo $facturas->proveemail;?></small></td>
																			<td><small><?php echo $facturas->fecemision;?></small></td>
																			<td><small><?php echo $facturas->fecgeneraacuse;?></small></td>
																			<td><small><?php echo $facturas->fecenvio;?></small></td>
																			<td><small><?php echo $facturas->created_at;?></small></td>
																			<td><small><a href="<?php echo base_url();?>facturaselectronicas/ver_pdf_compra/<?php echo $facturas->id;?>" target="_blank"><i class="fa fa-file-pdf-o fa-2x" ></i></a></small></td>
                                                                            <td><small><a href="<?php echo base_url();?>facturacion_electronica/dte_provee_tmp/<?php echo $facturas->path. $facturas->filename;?>" target="_blank"><i class="fa fa-file-text-o fa-2x" ></i></a></small></td>
																			<td><small>
                                                                            <?php if(!is_null($facturas->fecgeneraacuse)){ ?>
                                                                                <a href="#" class="lnk_xml" id="<?php echo $facturas->id;?>" data-toggle="modal" data-target="#show-xml" data-mercaderias="<?php echo $mercaderias;?>" data-envrec="<?php echo $facturas->arch_env_rec;?>" data-recdte="<?php echo $facturas->arch_rec_dte;?>" data-resdte="<?php echo $facturas->arch_res_dte;?>"  data-path="<?php echo $facturas->path;?>"><i class="fa fa-file-o fa-2x" ></i></a>
                                                                            <?php }else{ ?>

                                                                                <i class="fa fa-times fa-2x " ></i>

                                                                            <?php } ?>

                                                                            </small></td>
																			<td><small>
																			<?php if(is_null($facturas->fecgeneraacuse)){ ?>
																				<a href="<?php echo base_url();?>facturaselectronicas/envio_respuesta/<?php echo $facturas->id;?>" <?php echo $facturas->fecgeneraacuse == '' ? '' : 'disabled'; ?> ><i class="fa fa-mail-reply-all fa-2x	" ></i></a>
																			<?php }else{ ?>

																				<i class="fa fa-check fa-2x	" ></i>

																			<?php } ?>


																			</small></td>
																			<td><small>
                                                                            <?php // igual queda disponible el botón por si es necesario enviar a alguien más ?>
																			<?php if(is_null($facturas->fecgeneraacuse)){ ?>
																				
																				<i class="fa fa-times fa-2x	" ></i>

																			<?php }else{ ?>

																				<a href="<?php echo base_url();?>facturaselectronicas/envio_email_acuse/<?php echo $facturas->id;?>" <?php echo $facturas->fecgeneraacuse == '' ? '' : 'disabled'; ?> ><i class="fa fa-envelope-o fa-2x	" ></i></a>
																			<?php } ?>


																			</small></td>
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

        $('#basicBootstrapForm').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            rut: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Rut Empresa es requerido'
                    },
                    stringLength: {
                        min: 0,
                        max: 12,
                        message: 'El largo del Rut es Incorrecto'
                    },
                    validateRut: {
                      message: 'Rut Incorrecto'
                    }

                }
            },      

            /*fecha_desde: {
                row: '.form-group',
                validators: {
                    date: {
                        format: 'DD/MM/YYYY',
                        message: 'El valor no es una fecha v&aacute;lida'
                    }                   
                }
            }, */
            
            /* fecha_hasta: {
                row: '.form-group',
                validators: {
                    date: {
                        format: 'DD/MM/YYYY',
                        message: 'El valor no es una fecha v&aacute;lida'
                    }                   
                }
            },*/     

           



        }

        //$(".mask_date").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});  
    });

     
        
         
</script>


<script>
 $.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '< Ant',
 nextText: 'Sig >',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 weekHeader: 'Sm',
 dateFormat: 'dd-mm-yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);
$(function () {
$("#fecha_desde").datepicker();

});
</script>


<script>
 $.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '< Ant',
 nextText: 'Sig >',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 weekHeader: 'Sm',
 dateFormat: 'dd-mm-yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);
$(function () {
$("#fecha_hasta").datepicker();

});
</script>
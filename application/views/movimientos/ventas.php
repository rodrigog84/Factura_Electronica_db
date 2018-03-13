<!--sub-heard-part-->
								  <!--//sub-heard-part-->
								
	 <div class="panel panel-inverse">                       
                                <div class="panel-heading">
                                      <h4 class="panel-title">Documentos</h4>
                                  </div>								
													
                      <div class="panel-body">
                        <div class='row'>


																<table id="listado" class="table"> 
																	<thead> 
																		<tr>
                            												<th>Tipo Docto</th>
                            												<th>Num. Docto</th>
                            												<th>Emisor</th>
                            												<th>Fecha</th>
                                                    <th>Neto</th>
                                                    <th>IVA</th>
                                                    <th>Total</th>
                                                    <th>XML</th>
                                                    <th>PDF</th>
																		</tr> 
																	</thead> 
																	<tbody> 
	                            										<?php $i = 1; ?>
	                            										<?php foreach ($ventas as $venta) { ?>	
																		<tr >
											                              <td><small><?php echo $venta->descripcion ;?></small></td>
	                              										  <td><small><?php echo $venta->num_factura;?></small>
	                              										  <td><small><?php echo $venta->nombres;?></small></td>
	                              										  <td><small><?php echo $venta->fecha_factura;?></small></td>
                                                      <td><small><?php echo number_format($venta->neto,0,".",".");?></small></td>
                                                      <td><small><?php echo number_format($venta->iva,0,".",".");?></small></td>
                                                      <td><small><?php echo number_format($venta->totalfactura,0,".",".");?></small></td>
                                                      <td><center><a href="<?php echo base_url();?>facturaselectronicas/ver_dte/<?php echo $venta->id;?>" target="_blank"><i class="fa fa-file-code-o fa-lg"></i></a></center></td>
                                                      <td><center><a href="<?php echo base_url();?>facturaselectronicas/exportFePDF/<?php echo $venta->id;?>" target="_blank"><i class="fa fa-file-pdf-o fa-lg"></i></a><center></td>
	                              										  
																		</tr> 

											                            <?php $i++;?>
											                            	<?php } ?>

																		
																	</tbody> 
																</table> 
													</div>
										
											</div>
                      </div> 
                    </div> 


<script>


$(function () {
        $('#listado').dataTable({
          "bLengthChange": true,
          "bFilter": true,
          "bInfo": true,
          "bSort": false,
          "bAutoWidth": false,
          "aLengthMenu" : [[5,10,15,30,45,100,-1],[5,10,15,30,45,100,'Todos']],
          "iDisplayLength": 10,
          "oLanguage": {
              "sLengthMenu": "_MENU_ Registros por p&aacute;gina",
              "sZeroRecords": "No se encontraron registros",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ registros",
              "sInfoEmpty": "Mostrando 0 de 0 registros",
              "sInfoFiltered": "(filtrado de _MAX_ registros totales)",
              "sSearch":        "Buscar:",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":    "Último",
                "sNext":    "Siguiente",
                "sPrevious": "Anterior"
            }              
          }          
        });
      });


</script>											
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


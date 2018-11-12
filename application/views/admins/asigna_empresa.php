
        <!-- Main content -->

                            <div class="panel panel-inverse">                       
                                <div class="panel-heading">
                                      <h4 class="panel-title">Selecci&oacute;n de Empresa</h4>
                                  </div>
                      <div class="panel-body">
                         <div class="row">
                            <div class="span12">
                              <div class="box box-solid">
                                <div class="box-body no-padding">
                                  <ul class="nav nav-pills nav-stacked">
                                    <?php foreach($empresas as $empresa): ?>
                                      <li><a href="<?php echo base_url(); ?>main/dashboard/<?php echo $empresa->id_empresa;?>"><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;  <?php echo $empresa->nombre;?></a></li>
                                    <?php endforeach; ?>
                                  </ul>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->                  
                            </div>
                          </div>


                      </div><!-- /.box-body  PUEBA -->

                 
                  </div> 
                  </div>
             





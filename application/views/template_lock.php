<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <title>Sistema de Remuneraciones Dynamics</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    
    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/css/animate.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/css/style.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/css/style-responsive.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/css/theme/blue.css" rel="stylesheet" id="theme" />
    <!-- ================== END BASE CSS STYLE ================== -->
    
    <!-- ================== BEGIN BASE JS ================== -->

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-89102959-1', 'auto');
  ga('send', 'pageview');

</script>

    
    <script src="<?php echo base_url(); ?>assets/plugins/pace/pace.min.js"></script>
    <!-- ================== END BASE JS ================== -->


<!-- ================== BEGIN BASE JS ================== -->
    <?php if(isset($jquery2)){?>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery-2.2.3.min.js"></script>
    <?php } else {?>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery-1.12.4.min.js"></script>
    <?php } ?>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
        <script src="<?php echo base_url(); ?>assets/crossbrowserjs/html5shiv.js"></script>
        <script src="<?php echo base_url(); ?>assets/crossbrowserjs/respond.min.js"></script>
        <script src="<?php echo base_url(); ?>ssets/crossbrowserjs/excanvas.min.js"></script>
    <![endif]-->
    <script src="<?php echo base_url(); ?>assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-cookie/jquery.cookie.js"></script>
    <!-- ================== END BASE JS ================== -->
    
    <!-- ================== BEGIN PAGE LEVEL JS ================== -->

    <!-- ================== END PAGE LEVEL JS ================== -->
    <?php if(isset($bootstrap_select)){ ?>
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <?php } ?>
    

    <?php if(isset($wizard_validation)){ ?>
    <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-wizard/css/bwizard.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/plugins/parsley/src/parsley.css" rel="stylesheet" />
    <!-- ================== END PAGE LEVEL STYLE ================== -->            

    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="<?php echo base_url(); ?>assets/plugins/parsley/dist/parsley.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/parsley/src/i18n/es.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wizard/js/bwizard.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/form-wizards-validation.demo.min.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->            
    <?php } ?>

  <?php if(isset($jqueryRut)){ ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.Rut/jquery.Rut.js" charset="UTF-8"></script>
  <?php } ?>  


<?php if(isset($blockUI)){ ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.blockUI.js" charset="UTF-8"></script>
  <?php } ?>  

      <?php if(isset($datetimepicker)){ ?>
       <link href="<?php echo base_url(); ?>js/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-datetimepicker-master/js/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>
      <?php } ?>


      <?php if(isset($datetimepicker2)){ ?>
       <link href="<?php echo base_url(); ?>js/bootstrap-datetimepicker-master2/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-datetimepicker-master2/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-datetimepicker-master2/js/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>
      <?php } ?>




      <?php if(isset($datetimepicker_advance)){ ?>
        <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-combobox/css/bootstrap-combobox.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>assets/plugins/jquery-tag-it/css/jquery.tagit.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />


        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

        <script src="<?php echo base_url(); ?>assets/plugins/masked-input/masked-input.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-combobox/js/bootstrap-combobox.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput-typeahead.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/jquery-tag-it/js/tag-it.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-daterangepicker/moment.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/select2/dist/js/select2.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-eonasdan-datetimepicker/src/js/locales/bootstrap-datepicker.es.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-eonasdan-datetimepicker/src/js/bootstrap-datetimepicker.js"></script>

      <?php } ?>

    <?php if(isset($mask2)){?>
        <script src="<?php echo base_url(); ?>assets/plugins/masked-input/masked-input.min.js"></script>
    <?php } ?>


  <?php if(isset($icheck)){ ?>
    <link href="<?php echo base_url(); ?>assets/plugins/iCheck/all.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js" type="text/javascript"></script>

  <?php } ?>  


  <?php if(isset($datatable)){ ?>
    <link href="<?php echo base_url(); ?>assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/plugins/DataTables/extensions/Buttons/css/buttons.bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
<!--      <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.css"> -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/cdn/datatables/1.10.10/css/jquery.dataTables.css">
    <script src="<?php echo base_url(); ?>assets/plugins/DataTables/media/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/DataTables/extensions/Buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/DataTables/extensions/Buttons/js/buttons.flash.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/DataTables/extensions/Buttons/js/jszip.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/DataTables/extensions/Buttons/js/pdfmake.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/DataTables/extensions/Buttons/js/vfs_fonts.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/DataTables/extensions/Buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/DataTables/extensions/Buttons/js/buttons.colvis.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
  <?php } ?>

    <?php if(isset($altEditor)){?>
    <link href="<?=base_url(); ?>assets/plugins/DataTables/altEditor/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="<?=base_url(); ?>assets/plugins/DataTables/altEditor/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="<?=base_url(); ?>assets/plugins/DataTables/altEditor/select.dataTables.min.css" rel="stylesheet" />
    <link href="<?=base_url();?>assets/plugins/DataTables/altEditor/responsive.dataTables.min.css" />

    <script src="<?=base_url();?>assets/plugins/DataTables/altEditor/dataTables.select.min.js"></script>
        <script src="<?=base_url();?>assets/plugins/DataTables/altEditor/dataTables.altEditor.free.js"></script>
        <style>
            table.dataTable tbody>tr.selected,
            table.dataTable tbody>tr>.selected {
                background-color: #A2D3F6;
            }
        </style>
    <?php }?>

    <?php if(isset($fileupload)){ ?>
    <!-- ================== BEGIN PAGE LEVEL CSS STYLE ================== -->
    <link href="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" />
    <!-- ================== END PAGE LEVEL CSS STYLE ================== -->
    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/js/vendor/tmpl.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/js/vendor/load-image.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/js/vendor/canvas-to-blob.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/blueimp-gallery/jquery.blueimp-gallery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/js/jquery.iframe-transport.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/js/jquery.fileupload.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/js/jquery.fileupload-process.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/js/jquery.fileupload-image.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/js/jquery.fileupload-audio.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/js/jquery.fileupload-video.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/js/jquery.fileupload-validate.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/js/jquery.fileupload-ui.js"></script>
    <!--[if (gte IE 8)&(lt IE 10)]>
        <script src="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/js/cors/jquery.xdr-transport.js"></script>
    <![endif]-->
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/js/main.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->    



    <?php } ?>

  <?php if (isset($highchartsGraph)): ?>
  <script src="<?php echo base_url(); ?>plugins/highcharts/highcharts.js"></script>
  <script src="<?php echo base_url(); ?>plugins/highcharts/exporting.js"></script>
  <!--script src="<?php echo base_url(); ?>plugins/highcharts/style.js"></script-->
  <script src="<?php echo base_url(); ?>plugins/highcharts/highcharts-more.js"></script>
  <?php endif; ?>       


  <?php if(isset($chart)){ ?>
    <link href="<?php echo base_url(); ?>assets/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/plugins/nvd3/build/nv.d3.css" rel="stylesheet" />
    <script src="<?php echo base_url(); ?>assets/plugins/flot/jquery.flot.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/flot/jquery.flot.time.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/flot/jquery.flot.resize.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/flot/jquery.flot.pie.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/sparkline/jquery.sparkline.js"></script>
<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.2/d3.min.js"></script> -->
    <script src="<?php echo base_url(); ?>assets/cdn/ajax/libs/d3/3.5.2/d3.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/nvd3/build/nv.d3.js"></script>

  <?php } ?>



    <?php if(isset($gritter)){ ?>     
    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <link href="<?php echo base_url(); ?>assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />  
    <script src="<?php echo base_url(); ?>assets/plugins/gritter/js/jquery.gritter.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->
    <?php } ?>

    <?php if(isset($masked_input)){ ?>     
    <script src="<?php echo base_url(); ?>assets/plugins/masked-input/masked-input.min.js"></script>
    <?php } ?>

    <?php if(isset($maleta)){ ?>     
    <script src="<?php echo base_url(); ?>js/maleta.js"></script>
    <?php } ?>    

      <?php if(isset($mask)){ ?>
            <script src="<?php echo base_url(); ?>plugins/jquery.mask.min.js"></script>
      <?php } ?>    


    <?php if(isset($switchery)){ ?>  
    	<link href="<?php echo base_url(); ?>assets/plugins/switchery/switchery.min.css" rel="stylesheet" />
		<link href="<?php echo base_url(); ?>assets/plugins/powerange/powerange.min.css" rel="stylesheet" />   
	    <script src="<?php echo base_url(); ?>assets/plugins/switchery/switchery.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/powerange/powerange.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/form-slider-switcher.demo.min.js"></script>
	<?php } ?>



      <?php if(isset($formValidation)){ ?>    
            <!--link rel="stylesheet" href="<?php echo base_url(); ?>vendor/bootstrap/css/bootstrap.css"/-->
            <!--link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/formValidation.css"/-->
            <script type="text/javascript" src="<?php echo base_url(); ?>dist/js/formValidation.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>dist/js/framework/bootstrap.js"></script>
      <?php } ?>   



<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap-multiselect.css" type="text/css"/>
<!-- Latest compiled and minified JavaScript -->
<script src="<?php echo base_url();?>js/bootstrap-multiselect.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/i18n/defaults-es_CL.js"></script>
<!--<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap-select.min.css">-->




        <script src="<?php echo base_url(); ?>assets/js/apps.min.js"></script>

    <script>
        $(document).ready(function() {
            App.init();


            <?php if(isset($sidebar_minified)){ // achicar menu lateral?>
            var sidebarClass = 'page-sidebar-minified';
            var targetContainer = '#page-container';
            $('#sidebar [data-scrollbar="true"]').css('margin-top','0');
            $('#sidebar [data-scrollbar="true"]').removeAttr('data-init');
            $('#sidebar [data-scrollbar=true]').stop();
            $(targetContainer).addClass(sidebarClass);

            if(!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                if ($(targetContainer).hasClass('page-sidebar-fixed')) {
                    $('#sidebar [data-scrollbar="true"]').slimScroll({destroy: true});
                    $('#sidebar [data-scrollbar="true"]').removeAttr('style');
                }
                $('#sidebar [data-scrollbar=true]').trigger('mouseover');
            } else {
                $('#sidebar [data-scrollbar="true"]').css('margin-top','0');
                $('#sidebar [data-scrollbar="true"]').css('overflow', 'visible');
            }
            $(window).trigger('resize');
            <?php } ?>

            //FormMultipleUpload.init();
            //FormWizardValidation.init();
        });
    </script>
    
</head>
<body class="boxed-layout">
<?php //debug($this->session->userdata('menu_list'));exit;?>
    <!-- begin #page-loader -->
    <div id="page-loader" class="fade in"><span class="spinner"></span></div>
    <!-- end #page-loader -->
    
    <!-- begin #page-container -->
    <div id="page-container" class="page-container fade page-without-sidebar page-header-fixed page-with-top-menu">
        <!-- begin #header -->
        <div id="header" class="header navbar navbar-default navbar-fixed-top navbar-inverse" style="height: 50px;">
            <!-- begin container-fluid -->
            <div class="container-fluid">
                <!-- begins mobile sidebar expand / collapse button -->
                <ul class="nav navbar-nav navbar-left">
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" style="margin-top: -10px;">
                            <img src="<?php echo base_url().'images/logos/logo-ecomac-1.png';?>" alt="" style="width: 100px; "  />
                            <span class="hidden-xs"></span> 
                        </a>
                    </li>
                    <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>                    
                </ul>                

                <!-- end mobile sidebar expand / collapse button -->
                
                          
                
                <!-- begin header navigation right -->
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <form class="navbar-form full-width">
                            
                            
                            
                                <p class="text-info"><em><b><?php echo $this->session->userdata('empresanombre'); ?></em></b></p>
                        </form>
                    </li>                
                    <li class="dropdown navbar-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo base_url();?>assets/img/user-13.jpg" alt="" />
                            <span class="hidden-xs"><?php echo $this->session->userdata('name'); ?></span> <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu animated fadeInLeft">
                            <li class="arrow"></li>
                            <!--li><a href="javascript:;">Editar Perfil</a></li-->
                            <li><a href="<?=base_url();?>admins/cambio_clave"><i class="fa fa-key fa-rotate-90"></i>&nbsp;&nbsp;Cambio de Password</a></li>
                            <li><a href="<?php echo base_url();?>archivos/manualusuario.pdf" target="_blank"><i class="fa fa-wrench"></i>&nbsp;&nbsp;Ayuda</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo base_url();?>auth/logout">Salir</a></li>
                        </ul>
                    </li>
                </ul>
                <!-- end header navigation right -->
            </div>
            <!-- end container-fluid -->
        </div>
        <!-- end #header -->
        
              <!-- end #sidebar -->
        
        <!-- begin #content -->
        <div id="content" class="content">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right">
                <li><a href="j<?php echo base_url();?>main/dashboard">Inicio</a></li>
                <li class="active"><?php echo $content_menu['menu']; ?></li>
            </ol>
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header"><?php echo $content_menu['title'];?>&nbsp;<small><?php echo $content_menu['subtitle'];?></small></h1>
            <?php $this->load->view($content_view); ?>
        </div>
        <!-- end #content -->

        <!-- begin #footer -->
        <?php if(!isset($footer)){ ?>
        <div id="footer" class="footer">
            &copy; 2018 Infosys - Consisa - Todos los derechos reservados
        </div>        
        <?php } ?>

        
        <!-- begin scroll to top btn -->
        <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
        <!-- end scroll to top btn -->
    </div>
    <!-- end page container -->
    

</body>
</html>





   
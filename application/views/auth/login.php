<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
  <meta charset="utf-8" />
  <title>Sistema de Remuneraciones Dynamics | Página Acceso</title>
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
  <meta content="" name="description" />
  <meta content="" name="author" />

  <!-- ================== BEGIN BASE CSS STYLE ================== -->
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="<?=base_url();?>assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
  <link href="<?=base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="<?=base_url();?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
  <link href="<?=base_url();?>assets/css/animate.min.css" rel="stylesheet" />
  <link href="<?=base_url();?>assets/css/style.min.css" rel="stylesheet" />
  <link href="<?=base_url();?>assets/css/style-responsive.min.css" rel="stylesheet" />
  <link href="<?=base_url();?>assets/css/theme/default.css" rel="stylesheet" id="theme" />
  <!-- ================== END BASE CSS STYLE ================== -->

  <!-- ================== BEGIN BASE JS ================== -->
  <script src="<?=base_url();?>assets/plugins/pace/pace.min.js"></script>
  <!-- ================== END BASE JS ================== -->

  <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
  <link href="<?php echo base_url(); ?>assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
  <!-- ================== END PAGE LEVEL STYLE ================== -->
</head>
<body class="pace-top">
<!-- begin #page-loader -->
<div id="page-loader" class="fade in"><span class="spinner"></span></div>
<!-- end #page-loader -->

<div class="login-cover">
  <div class="login-cover-image"><img src="<?=base_url();?>assets/img/login-bg/bgf2.jpg" data-id="login-cover-image" alt="" /></div>
  <div class="login-cover-bg"></div>
</div>
<!-- begin #page-container -->
<div id="page-container" class="fade">
  <!-- begin login -->
  <div class="login login-v2" data-pageload-addclass="animated fadeIn">
    <!-- begin brand -->
    <div class="login-header">
      <div class="brand">
        <span class="logo"></span>Factura Electr&oacute;nica
        <small>Acceso a sistemas de Factura Electr&oacute;nica</small>
      </div>
      <div class="icon">
        <i class="fa fa-sign-in"></i>
      </div>
    </div>
    <!-- end brand -->
    <div class="login-content">
      <form action="<?php echo base_url();?>auth/login" method="POST" class="margin-bottom-0">
        <div class="form-group m-b-20">
          <input type="text" name="identity" id="identity" value="" class="form-control input-lg" placeholder="Nombre Usuario" />
        </div>
        <div class="form-group m-b-20">
          <input type="password" name="password" id="password" value="" class="form-control input-lg" placeholder="Contraseña" />
        </div>
        <div class="checkbox m-b-20">
          <label>
            <input type="checkbox" id="remember" /> Recordar Usuario
          </label>
        </div>
        <div class="login-buttons">
          <button type="submit" class="btn btn-success btn-block btn-lg">Acceder</button>
        </div>
        <div class="m-t-20">
          <a href="<?=base_url();?>auth/forgot_password"><?php echo lang('login_forgot_password');?></a>.
        </div>
      </form>
    </div>
  </div>
  <!-- end login -->
</div>
<!-- end page container -->

<!-- ================== BEGIN BASE JS ================== -->
<script src="<?=base_url();?>assets/plugins/jquery/jquery-1.9.1.min.js"></script>
<script src="<?=base_url();?>assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
<script src="<?=base_url();?>assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
<script src="<?=base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!--[if lt IE 9]>
<script src="<?=base_url();?>assets/crossbrowserjs/html5shiv.js"></script>
<script src="<?=base_url();?>assets/crossbrowserjs/respond.min.js"></script>
<script src="<?=base_url();?>assets/crossbrowserjs/excanvas.min.js"></script>
<![endif]-->
<script src="<?=base_url();?>assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?=base_url();?>assets/plugins/jquery-cookie/jquery.cookie.js"></script>
<!-- ================== END BASE JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="<?php echo base_url(); ?>assets/plugins/gritter/js/jquery.gritter.js"></script>
<script src="<?=base_url();?>assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>
  $(document).ready(function() {
    App.init();
    //LoginV2.init();
  });
  <?php if($message){
  $message = trim($message);
  $message = str_replace("<p>","",$message);
  $message = str_replace("</p>",",",$message);
  $array_message = explode(",",$message);
  foreach ($array_message as $mess) {
  if(!empty($mess)){
  $mess = trim($mess);  ?>


  $.gritter.add({
    title: 'Atención',
    text: '<?php echo $mess; ?>',
    class_name: 'gritter-info gritter-center'
  });
  <?php }
  }
  }
  ?>
</script>
</body>
</html>

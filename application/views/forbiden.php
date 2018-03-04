<!-- begin #page-container -->
<?php //echo "hola";exit;?>
<div class="row">
    <!-- begin error -->
    <div class="error">
        <div class="error-code m-b-10">403 <i class="fa fa-warning"></i></div>
        <div class="error-content">
            <div class="error-message">Acceso Restringido</div>
            <div class="error-desc m-b-20">
                No tiene permisos para acceder a la p&aacute;gina.
            </div>
            <div>
                <a href="<?php echo $rutaVolver;?>" class="btn btn-success">Volver a P&aacute;gina Inicial</a>
            </div>
        </div>
    </div>
    <!-- end error -->
</div>
<!-- end page container -->

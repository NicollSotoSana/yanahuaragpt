<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?php echo base_url();?>assets/nuevotemp/img/favicon.png"/>
    <title>Centro Óptico Guillén Tamayo</title>

		<link rel="stylesheet" href="<?php echo base_url();?>assets/nuevotemp/css/bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>assets/nuevotemp/css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>assets/nuevotemp/css/matrix-login.css" />
        <link href="<?php echo base_url();?>assets/nuevotemp/font-awesome/css/font-awesome.css" rel="stylesheet" />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
		<script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/jquery-1.10.2.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap-addons.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/ini.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/jquery.validator.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/jquery.form.js'); ?>"></script>
    <script>
	    function base_url(url)
	    {
			return '<?php echo base_url('index.php'); ?>' + '/' + url;
		}

		var FormatoFecha = 'dd/mm/yyyy';
	</script>
  </head>


    <body>
        <div id="loginbox">            
        	<?php echo form_open('acceso/ajax/Acceder', array('class' => 'upd form-vertical', 'id'=>'loginform')); ?>
				<div class="control-group normal_text"> <h3><img src="<?php echo base_url(); ?>/assets/nuevotemp/img/logo3.png" alt="Logo" /></h3></div>
				<div class="control-group">
					<div class="controls">
                        <div class="main_input_box">
					        <span class="add-on bg_bz"><i class="icon-map-marker"> </i></span><?php echo Select('Empresa_id', $empresas, 'Nombre', 'id'); ?>
                        </div>
					</div>
				</div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_bz"><i class="icon-user"> </i></span><input type="text" placeholder="Usuario" name="Usuario" />
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_bz"><i class="icon-lock"></i></span><input type="password" placeholder="Contraseña" name="Contrasena" />
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <!--<span class="pull-left"><a href="#" class="flip-link btn btn-info" id="to-recover">Lost password?</a></span>-->
                    <span class="pull-right"><button type="submit" class="btn btn-blue submit-ajax-button"><i class="icon-signin"> </i> Acceder</button></span>
                </div>
            <?php echo form_close(); ?>
        </div>
        
    </body>
</html>
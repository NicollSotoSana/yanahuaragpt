<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?php echo base_url();?>assets/nuevotemp/img/favicon.png"/>
    <title><?php echo $this->conf->RazonSocial; ?></title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <?php echo link_tag('assets/nuevotemp/css/bootstrap-responsive.min.css'); ?>
    <?php echo link_tag('assets/nuevotemp/css/fullcalendar.css'); ?>
    <?php echo link_tag('assets/nuevotemp/css/matrix-style.css'); ?>
    <?php echo link_tag('assets/nuevotemp/css/matrix-media.css'); ?>
    <?php echo link_tag('assets/nuevotemp/css/select2.css'); ?>
    <?php echo link_tag('assets/nuevotemp/font-awesome/css/font-awesome.css'); ?>
    <?php echo link_tag('assets/bootstrap/css/light/jqgrid/ui.jqgrid.css'); ?>
   	<?php echo link_tag('assets/bootstrap/css/light/style.css'); ?>
   	<?php echo link_tag('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css'); ?>

		<?php echo link_tag('assets/bootstrap/css/jquery.toast.min.css'); ?>
		 
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    
    <script>
	    function base_url(url)
	    {
			return '<?php echo base_url('index.php'); ?>' + '/' + url;
		}

		var FormatoFecha = 'dd/mm/yyyy';
		var moneda = '<?php echo $this->conf->Moneda_id; ?>';
		var Modulos = '<?php echo MODULES; ?>';

	</script>
<script type="text/javascript">
	function marcar(source) 
	{
		checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}
</script>
  </head>

  <body>
  	<div id="header">
		<!--<h1><a href="dashboard.html">Matrix Admin</a></h1>-->
		<img src="<?php echo base_url(); ?>/assets/nuevotemp/img/logo3.png" style="width:220px; padding:10px; margin-top:10px;">
	</div>

	<!--<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">Bienvenido <?php echo $this->user->Usuario; ?></span><b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="<?php echo base_url('acceso/logout'); ?>"><i class="icon-key"></i> Salir</a></li>
      </ul>
    </li>
    <li class=""><a title="" href="<?php echo base_url('acceso/logout'); ?>"><i class="icon icon-share-alt"></i> <span class="text">Salir</span></a></li>
  </ul>
</div>
close-top-Header-menu-->
<!--start-top-serch-->

	<!--close-top-serch-->
	<!--sidebar-menu-->
	<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Menú</a>
	  <ul>
	  	<?php foreach($this->menu as $m1): ?>
	  	    <?php 
	  	    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	    $url_parseada = basename($actual_link);
	  	    ?>
			<?php if($m1->Url != '#'){ ?>
				<li class="<?php echo $url_parseada == $m1->Class ? 'active' : ''; ?>">
					<a href="<?php echo base_url($m1->Url); ?>"><i class="icon <?php echo $m1->Css; ?>"></i> <span><?php echo $m1->Nombre; ?></span></a> 
				</li>
			<?php }else{ ?>
			    <li class="submenu <?php echo $url_parseada == $m1->Class ? 'active' : ''; ?>"> 
			    	<a href="#"><i class="icon <?php echo $m1->Css; ?>"></i> <span><?php echo $m1->Nombre; ?></span> <b class="caret"></b></a>
					      <ul>
					      	<?php foreach($m1->Hijos as $m2): ?>
				      			<?php if($m2->Separador == 1): ?>
					      			<li class="divider"></li>
				      			<?php endif; ?>
					        	<li><a href="<?php echo base_url($m2->Url);?>"><?php echo $m2->Nombre;?></a></li>
					        <?php endforeach; ?>
					      </ul>
			    </li>

			   <?php } ?>
		      <?php endforeach; ?>
	    <!--<li class="content"> <span>Monthly Bandwidth Transfer</span>
	      <div class="progress progress-mini progress-danger active progress-striped">
	        <div style="width: 77%;" class="bar"></div>
	      </div>
	      <span class="percent">77%</span>
	      <div class="stat">21419.94 / 14000 MB</div>
	    </li>
	    <li class="content"> <span>Disk Space Usage</span>
	      <div class="progress progress-mini active progress-striped">
	        <div style="width: 87%;" class="bar"></div>
	      </div>
	      <span class="percent">87%</span>
	      <div class="stat">604.44 / 4000 MB</div>
	    </li>-->
	    <li class="content"><a href="<?php echo base_url('acceso/logout'); ?>"><i class="icon-key"></i> Cerrar Sesión</a></li>
	  </ul>
	</div>
<div id="content">
	  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo base_url(); ?>" title="Ir al Inicio" class="tip-bottom" style="font-size:1em; font-weight:bold;"><i class="icon-home"></i> Bienvenido(a) <?php echo $this->user->Usuario; ?></a></div>
  </div>
  <div class="container-fluid">
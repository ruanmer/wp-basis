<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="pt-BR"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" lang="pt-BR"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9" lang="pt-BR"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="pt-BR"> <!--<![endif]-->
<head>
<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 

<title><?php wp_title(''); ?></title>

<meta name="viewport" content="width=device-width">

<link rel="icon" type="image/png" href="<?php bloginfo('template_url'); ?>/img/favicon.png">

<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>">
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'template_url' ); ?>/css/base.css">

<script src="<?php echo get_template_directory_uri(); ?>/js/libs/modernizr.js"></script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo get_template_directory_uri(); ?>/js/libs/jquery.js"><\/script>')</script>

<?php 
	wp_head();
?>

<script src="<?php echo get_template_directory_uri(); ?>/js/plugins.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/base.js"></script>
</head>
<body <?php body_class(); ?>>

<div id="container">
	<header id="header"> 
		<h1 id="brand">
			<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_html(  get_bloginfo( 'name' ) ) ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
		</h1>

		<?php 	
			/* primary-nav */	
			if( has_nav_menu('primary-nav') ) {
				wp_nav_menu( array( 'theme_location' => 'primary-nav', 'container' => 'nav', 'container_class' => '', 'container_id' => 'menu', 'menu_class' => '', 'menu_id' => 'menu-ul' ) );   
			}	
		?>
	</header><!--/#header-->
    
    <div id="main" role="main">
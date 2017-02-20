<?php
$fileName = 'logo-screen-' . get_query_var('pagename') . '.png';
$logoName = file_exists(TEMPLATEPATH . '/images/' . $fileName) ? $fileName : 'logo-screen.png';
?>

<!DOCTYPE HTML>
<?php
?>
<html>
<head>
	<title>CROATIAN BUSINESS MONITOR Daily News</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="CROATIAN BUSINESS MONITOR Daily News" />
	<meta name="keywords" content="CROATIAN BUSINESS MONITOR, News, croatia, business, finance" />
	<script>var _cbm_tpl_dir = '<?= get_template_directory_uri() ?>/';</script>
	<?php
		// wp_enqueue_style( 'fonts', get_template_directory_uri() . '/css/fonts.css', array(), '1.1', 'all');
		wp_enqueue_style( 'fonts', get_template_directory_uri() . '/css/fonts.css');

		wp_enqueue_script( 'html5shiv', get_template_directory_uri() . '/js/html5shiv.js');
		wp_script_add_data( 'html5shiv', 'conditional', 'lte IE 8' );

		wp_enqueue_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js');

		wp_enqueue_script( 'skel', get_template_directory_uri() . '/js/skel.min.js');
		wp_enqueue_script( 'skel-panels', get_template_directory_uri() . '/js/skel-panels.min.js');

		wp_enqueue_script( 'init', get_template_directory_uri() . '/js/init.js');

		wp_enqueue_style( 'v8', get_template_directory_uri() . '/css/ie/v8.css');
		wp_style_add_data( 'v8', 'conditional', 'lte IE 8' );

		wp_enqueue_style( 'v9', get_template_directory_uri() . '/css/ie/v9.css');
		wp_style_add_data( 'v9', 'conditional', 'lte IE 9' );
	?>
	<?php
	?>
	<?php wp_head();?>
	<noscript>
		<link rel="stylesheet" href="<?= get_template_directory_uri() ?>/css/skel-noscript.css" />
		<link rel="stylesheet" href="<?= get_template_directory_uri() ?>/style.css" />
		<link rel="stylesheet" href="<?= get_template_directory_uri() ?>/css/style-desktop.css" />
	</noscript>
</head>
<body class="homepage">

<!-- Header -->
	<div id="header">
		<div class="container">
				
			<!-- Logo -->
			<div id="logo">
				<h1><a href="index.php"><img src="<?= get_template_directory_uri() ?>/images/<?= $logoName ?>"  class="img-responsive" alt="CROATIAN BUSINESS MONITOR"></a></h1>
			</div>

			<div id="date"><?= CBMTheme::formatDate( time() ) ?></div>
		</div>

		<div class="container">
			<!-- Nav -->
			<nav id="nav">
				<ul>
					<li class="active"><a href="<?= home_url() ?>">Home</a></li>
					<li><a href="<?= get_url_by_slug( 'about' ) ?>">About</a></li>
					<li><a href="<?= get_url_by_slug( 'contact' ) ?>">Contact</a></li>
					<?php /* ?><li><a href="<?= home_url( 'membership.php' ) ?>">Membership</a></li><?php */ ?>
					<li><a href="<?= wp_registration_url() ?>">Membership</a></li>
					<li><a href="<?= get_url_by_slug( 'links' ) ?>">Useful links</a></li>
					<li style="float: right;">
						<?php if (is_user_logged_in()) { ?>
							<a href="<?= wp_logout_url( home_url() ) ?>">Logout</a>
						<?php } else { ?>
							<a href="<?= wp_login_url( home_url() ) ?>">Login</a>
						<?php } ?>
					</li>
				</ul>
			</nav>
		</div>
		
		<!-- Extra -->
		<div id="themes" class="container">
			<div class="row">
				<?php get_template_part( 'navigation', get_post_format() ); ?>
			</div>
		</div>
		<!-- /Extra -->
	</div>
<!-- Header -->

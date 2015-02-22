<!DOCTYPE html>
<html lang="en"><head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Homepage cover theme</title>

	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/bootstrap-select.min.css" rel="stylesheet">
	<link href="/css/main.css" rel="stylesheet">
	<link href="/css/offcanvas.css" rel="stylesheet">
	<link href="/css/cover.css" rel="stylesheet">

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<style type="text/css"></style><style id="holderjs-style" type="text/css"></style></head>

	<body>

		<div class="site-wrapper">
			<div class="site-wrapper-inner">
				<div class="cover-container">
					<div class="backgroundFull">
						<div class="masthead clearfix">
							<div class="inner innerHeader">
								<h3 class="masthead-brand"><a href="/"><img src="/img/logo.jpg" class="logo"></a></h3>
								<ul class="nav masthead-nav">
									<li <?php if(uri_string() == ''): ?> class="active"<?php endif; ?>>
										<a href="/">
											Hem
										</a>
									</li>
									<li <?php if(uri_string() == 'vimmel'): ?> class="active"<?php endif; ?>>
										<a href="#">
											Vimmel
										</a>
									</li>
									<li <?php if(uri_string() == 'gastlista'): ?> class="active"<?php endif; ?>>
										<a href="#">
											Gästlista
										</a>
									</li>
									<li <?php if(uri_string() == 'kontakt'): ?> class="active"<?php endif; ?>>
										<a href="#">
											Kontakt
										</a>
									</li>
									<li <?php if(uri_string() == 'medlemskap'): ?> class="active"<?php endif; ?>>
										<a href="#">
											Medlemskap
										</a>
									</li>
									<li <?php if(uri_string() == 'anvandare'): ?> class="active"<?php endif; ?>>
										<a href="#">
											Logga in
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
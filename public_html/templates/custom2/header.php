<!DOCTYPE html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title><?php echo $b['logoImage']; ?> - <?php echo $q['pagename']; ?></title>
	<?php echo $head; ?>
  	<meta name="keywords" content="<?php echo $b['keywords']; ?>">
  	<meta name="description" content="<?php echo $b['description']; ?>">
  	<meta name="robots" content="<?php echo $b['robots']; ?>">
  	<meta name="generator" content="WebsMaking (https://www.websmaking.com)" />
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  	<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
  	<link rel="shortcut icon" href="<?php echo $b['favicon']; ?>" type="image/x-icon" />
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
	<link rel="stylesheet" href="<?php echo ABS_PATH; ?>templates/<?php echo $themeFolder; ?>/<?php echo $metadata['data'][$b['language_code']]['css']; ?>?v=0.0.16">
	<?php echo $additional; ?>
</head>
<body>
<header class="header-fixed">
<div class="header-limiter">
<h1 id="site-logo"><?php echo $b['header']; ?></h1>
<nav>
<ul>
<li><?php for ($i=0; $i<sizeof($rows); $i++): echo (isset($menu[$i]) ? $menu[$i] : ''); endfor; ?></li>
</ul>
</nav>
</header>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>

	$(document).ready(function(){

		var showHeaderAt = 150;

		var win = $(window),
				body = $('body');

		// Show the fixed header only on larger screen devices

		if(win.width() > 400){

			// When we scroll more than 150px down, we set the
			// "fixed" class on the body element.

			win.on('scroll', function(e){

				if(win.scrollTop() > showHeaderAt) {
					body.addClass('fixed');
				}
				else {
					body.removeClass('fixed');
				}
			});

		}

	});

</script>
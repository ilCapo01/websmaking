<!DOCTYPE html>
<html>
  <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title><?php echo $q['pagename']; ?> - <?php echo $b['logoImage']; ?></title>
    <?php echo $head; ?>
    
  	<meta name="robots" content="<?php echo $b['robots']; ?>">
  	<meta name="generator" content="WebsMaking (https://www.websmaking.com)" />
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  	<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css" rel="stylesheet">
  	<link rel="stylesheet" href="<?php echo ABS_PATH; ?>templates/<?php echo $themeFolder; ?>/<?php echo $metadata['data'][$b['language_code']]['css']; ?>">
  	<link rel="shortcut icon" href="<?php echo $b['favicon']; ?>" type="image/x-icon" />
  	<?php echo $additional; ?>
  </head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="menu">
<div class="container">
<div class="navbar-header">
        <div class="header">
      <div class="inside-header">
      <?php echo $b['header']; ?>
      </div>
    </div>
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
</div>
<div class="collapse navbar-collapse">
<ul class="nav navbar-nav">
      <?php for ($i=0; $i<sizeof($rows); $i++): echo (isset($menu[$i]) ? $menu[$i] : ''); endfor; ?>
</ul>
</div>
</div>
</div>
    <!-- Bootstrap core JavaScript -->
    <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

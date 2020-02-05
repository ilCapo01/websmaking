<!DOCTYPE html>
<html>
  <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title><?php echo $q['pagename']; ?> - <?php echo $b['logoImage']; ?></title>
    <?php echo $head; ?>
    
  	<meta name="robots" content="<?php echo $b['robots']; ?>">
  	<meta name="generator" content="WebsMaking (https://www.websmaking.com)" />
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="<?php echo ABS_PATH; ?>templates/<?php echo $themeFolder; ?>/<?php echo $metadata['data'][$b['language_code']]['css']; ?>?v=0.1.3">
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  	<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
  	<link rel="shortcut icon" href="<?php echo $b['favicon']; ?>" type="image/x-icon" />
    <?php echo $additional; ?>
    </head>

  <body>
    <nav class="nav">
       <div class="container">
        <div class="logo">
      <?php echo $b['header']; ?>
      </div>
    
<div id="mainListDiv" class="main_list">
<ul class="navlinks">
<?php for ($i=0; $i<sizeof($rows); $i++): echo (isset($menu[$i]) ? $menu[$i] : ''); endfor; ?>
</ul>
 </div>
<span class="navTrigger">
<i></i>
<i></i>
<i></i>
</span>
</div>
</nav>

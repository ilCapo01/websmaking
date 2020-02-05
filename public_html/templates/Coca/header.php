<!DOCTYPE html>
<html>
  <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title><?php echo $q['pagename']; ?> - <?php echo $b['logoImage']; ?></title>
    <?php echo $head; ?>
    
  	<meta name="robots" content="<?php echo $b['robots']; ?>">
  	<meta name="generator" content="WebsMaking (https://www.websmaking.com)" />
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="<?php echo ABS_PATH; ?>templates/<?php echo $themeFolder; ?>/<?php echo $metadata['data'][$b['language_code']]['css']; ?>?v=0.1.1">
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  	<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
  	<link rel="shortcut icon" href="<?php echo $b['favicon']; ?>" type="image/x-icon" />
    <?php echo $additional; ?>
    </head>

  <body>

<div class="nav">
<input type="checkbox" id="nav-check">
<div class="nav-header">
<div class="nav-title">
      <?php echo $b['header']; ?>
</div>
</div>
<div class="nav-btn">
<label for="nav-check">
<span></span>
<span></span>
<span></span>
</label>
</div>
<div class="nav-links">
    <?php for ($i=0; $i<sizeof($rows); $i++): echo (isset($menu[$i]) ? $menu[$i] : ''); endfor; ?>
</div>
</div>
<script src="https://static.codepen.io/assets/common/stopExecutionOnTimeout-de7e2ef6bfefd24b79a3f68b414b87b8db5b08439cac3f1012092b2290c719cd.js"></script>
<script id="rendered-js">
// Resize your browser for effect
//# sourceURL=pen.js
    </script>
</body>
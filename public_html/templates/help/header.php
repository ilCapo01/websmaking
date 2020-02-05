<!DOCTYPE html>
<html>
  <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  
    <title><?php echo $q['pagename']; ?> - <?php echo $b['logoImage']; ?></title>
    <?php echo $head; ?>
    
  	<meta name="keywords" content="<?php echo $q['seo_keywords']; ?>">
  	<meta name="description" content="<?php echo $q['seo_description']; ?>">
  	<meta name="robots" content="<?php echo $b['robots']; ?>">
  	<meta name="generator" content="WebsMaking (https://www.websmaking.com)" />
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="<?php echo ABS_PATH; ?>templates/<?php echo $themeFolder; ?>/<?php echo $metadata['data'][$b['language_code']]['css']; ?>?v=0.0.2">
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  	<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
  	<link rel="shortcut icon" href="<?php echo $b['favicon']; ?>" type="image/x-icon" />
  	<?php echo $additional; ?>
  </head>

  <body>
    <header class="h">
       <a href="/help/"><i class="fa fa-question-circle"></i> <b> מרכז העזרה</b></a>
       <a class="to-site" href="/">צור אתר בחינם</a>
    </header>
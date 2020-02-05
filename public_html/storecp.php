<?php
ob_start();
require_once(dirname(__FILE__).'/includes/config.php');
global $db, $user, $lang;

if(!$user->checkAuthentication()){
  header('location: '.ABS_PATH.'index.php?do=login&redirect='.urlencode($_SERVER['REQUEST_URI']));
  die;
}

$do = (isset($_GET['do']) ? $_GET['do'] : null);

// Cookie. 
//$userloggined = (isset(explode('-', $_SESSION['AUTHSESS'])[1]) ? explode('-', $_SESSION['AUTHSESS'])[1] : null);
$userloggined = (isset(explode('-', $_COOKIE['AUTHSESS'])[1]) ? explode('-', $_COOKIE['AUTHSESS'])[1] : null);
if (is_null($userloggined) || !isset($_COOKIE['AUTHSESS'])) {
    header('location: '.ABS_PATH);
    die;
}
$usersiteid = (isset($_SESSION['siteid']) ? $_SESSION['siteid'] : null);

// User's data.
//=============
$q2 = $db->prepare("SELECT * FROM `buildp_users` WHERE id=?");
$q2->execute(array($userloggined));
$b = $q2->fetch(); 
$group = $b['group'];
$uname = $b['username'];


if ($group == 1) {
    // Main site's data.
    $q2 = $db->prepare('SELECT * FROM buildp_setting');
    $q2->execute();
    $s = $q2->fetch();
}

if (!is_null($usersiteid)) {
    // Site's data.
    //=============
    //$q2 = $db->prepare("SELECT * FROM `buildp_sites` where userID=?");
    //$q2->execute(array($userloggined));
    $q2 = $db->prepare("SELECT * FROM `buildp_sites` where id=?");
    $q2->execute(array($usersiteid)); 
    $row = $q2->fetch();
    $url = $row['siteurl'];
    $cssID = $row['themeID'];
    $premium = $row['isPremium'];
}else {
    if ($group == 2) {
        header('location: '.ABS_PATH.'?do=mysites');
        die;
    }
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
        <title>לוח בקרה לניהול חנות אונליין </title>
        <meta name="apple-mobile-web-app-title" content="CodePen">
        <link rel="shortcut icon" type="image/x-icon" href="https://static.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico" />
        <link rel="mask-icon" type="" href="https://static.codepen.io/assets/favicon/logo-pin-8f3771b1072e3c38bd662872f6b673a722f4b3ca2421637d5596661b4e2132cc.svg" color="#111" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Droid+Sans" rel="stylesheet">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'>
        <link rel="stylesheet" href="./css/store.css">
        <link rel="apple-touch-icon" type="image/png" href="https://www.websmaking.com/icon.png" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <aside class="side-nav" id="show-side-navigation1">
            <i class="fa fa-bars close-aside hidden-sm hidden-md hidden-lg" data-close="show-side-navigation1"></i>
            <div class="heading">
            </div>
            <ul class="categories">
            <div class="display"><li><i class="fa fa-home fa-fw" aria-hidden="true"></i><a href="/storecp.php"> לוח בקרה</a>
            </li></div>
            <li><i class="fa fa-cube fa-fw"></i><a href="#"> מוצרים</a>
            <ul class="side-nav-dropdown">
            <li><a href="#">קטלוג</a></li>
            <li><a href="#">קטגוריות</a></li>
            <li><a href="#">תכונות</a></li>
            <li><a href="#">ביקורות</a></li>
            <li><a href="#">אפשרויות</a></li>
            </ul>
            </li>
            <li><i class="fa fa-dollar fa-fw"></i><a href="#"> מכירות</a>
            <ul class="side-nav-dropdown">
            <li><a href="#">הזמנות</a></li>
            <li><a href="#">עסקאות</a></li>
            </ul>
            </li>
            <div class="display"><li><i class="fa fa-tags fa-fw"></i><a href="#"> קופונים</a>
            </li></div>
            <div class="display"><li><i class="fa fa-camera fa-fw"></i><a href="#"> מדיה</a></li></div>
            </ul>
        </aside>
        
        <section id="contents">
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <i class="fa fa-align-right"></i>
                </button>
                <a class="navbar-brand" href="#"><a href="https://www.websmaking.com/" class="logo"><img src="https://www.websmaking.com/images/white-logo.png" style="width: 200px; margin-top: 15px;"></a></a>
                </div>
                <div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">החשבון שלי <span class="caret"></span></a>
                <ul class="dropdown-menu">
                <li><a href="./index.php?do=settings"><i class="fa fa-user-o fw"></i> ניהול חשבון</a></li>
                <li><a href="#"><i class="fa fa-question-circle-o fw"></i> עזרה</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="./index.php?do=logout"><i class="fa fa-sign-out"></i> התנתקות</a></li>
                </ul>
                </li>
                <li><a href="#"><i data-show="show-side-navigation1" class="fa fa-bars show-side-btn"></i></a></li>
                </ul>
                </div>
                </div>
            </nav>
            <div class="welcome">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="content">
                                <h2>ברוך הבא ללוח הבקרה!</h2>
                                <p>באפשרותך לצפות בכל נתוני המכירות, הקבלות ואף לנהל את החנות במלואה.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <section class="statistics">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="box">
                                <i class="fa fa-shopping-cart pull-left fa-fw bg-primary"></i>
                                <div class="info">
                                    <h3>1,245</h3> <span>סה"כ הזמנות</span>
                                    <p>כמות ההזמנות בחנות שלך מהחודש האחרון</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box">
                                <i class="fa fa-money pull-left fa-fw danger"></i>
                                <div class="info">
                                    <h3>34</h3> <span>מכירות</span>
                                    <p>כמות המכירות בחנות שלך מהחודש האחרון</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box">
                                <i class="fa fa-cubes fa-fw success"></i>
                                <div class="info">
                                    <h3>5,245</h3> <span>מוצרים</span>
                                    <p>כמות המוצרים שהוספו לאחרונה לחנות שלך</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="charts">
                <div class="container-fluid">
                <div class="row">
                <div class="col-md-6">
                <div class="chart-container">
                <h3>Chart</h3>
                <canvas id="myChart"></canvas>
                </div>
                </div>
                <div class="col-md-6">
                <div class="chart-container">
                <h3>Chart2</h3>
                <canvas id="myChart2"></canvas>
                </div>
                </div>
                </div>
                </div>
            </section>
            <section class="admins">
                <div class="container-fluid">
                    <div class="row">
                    </div>
                </div>
            </section>
            <section class='statis text-center'>
                <div class="container-fluid">
                <div class="row">
                <div class="col-md-3">
                <div class="box bg-primary">
                <i class="fa fa-eye"></i>
                <h3>5,154</h3>
                <p class="lead">צפיות בחנות</p>
                </div>
                </div>
                <div class="col-md-3">
                <div class="box danger">
                <i class="fa fa-user-o"></i>
                <h3>245</h3>
                <p class="lead">לקוחות חדשים</p>
                </div>
                </div>
                <div class="col-md-3">
                <div class="box warning">
                <i class="fa fa-shopping-cart"></i>
                <h3>5,154</h3>
                <p class="lead">מוצרים שנמכרו</p>
                </div>
                </div>
                <div class="col-md-3">
                <div class="box success">
                <i class="fa fa-handshake-o"></i>
                <h3>5,154</h3>
                <p class="lead">עסקאות</p>
                </div>
                </div>
                </div>
                </div>
            </section>
            <section class="chrt3">
                <div class="container-fluid">
                <div class="row">
                <div class="col-md-9">
                <div class="chart-container">
                <canvas id="chart3" width="100%"></canvas>
                </div>
                </div>
                <div class="col-md-4">
                <div class="box">
                </div>
                </div>
                </div>
                </div>
            </section>
        </section>
        <script src="https://code.jquery.com/jquery-latest.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
        <script src='js/main.js'></script>
        
        <script src="https://static.codepen.io/assets/common/stopExecutionOnTimeout-de7e2ef6bfefd24b79a3f68b414b87b8db5b08439cac3f1012092b2290c719cd.js"></script>
        <script id="rendered-js">
              /*global $, console*/
        /*
                                By Mostafa Omar
                              	https://www.facebook.com/MostafaOmarIbrahiem
                              */
        $(function () {
        
          'use strict';
        
          (function () {
        
            var aside = $('.side-nav'),
        
            showAsideBtn = $('.show-side-btn'),
        
            contents = $('#contents');
        
            showAsideBtn.on("click", function () {
        
              $("#" + $(this).data('show')).toggleClass('show-side-nav');
        
              contents.toggleClass('margin');
        
            });
        
            if ($(window).width() <= 767) {
        
              aside.addClass('show-side-nav');
        
            }
            $(window).on('resize', function () {
        
              if ($(window).width() > 767) {
        
                aside.removeClass('show-side-nav');
        
              }
        
            });
        
            // dropdown menu in the side nav
            var slideNavDropdown = $('.side-nav-dropdown');
        
            $('.side-nav .categories li').on('click', function () {
        
              $(this).toggleClass('opend').siblings().removeClass('opend');
        
              if ($(this).hasClass('opend')) {
        
                $(this).find('.side-nav-dropdown').slideToggle('fast');
        
                $(this).siblings().find('.side-nav-dropdown').slideUp('fast');
        
              } else {
        
                $(this).find('.side-nav-dropdown').slideUp('fast');
        
              }
        
            });
        
            $('.side-nav .close-aside').on('click', function () {
        
              $('#' + $(this).data('close')).addClass('show-side-nav');
        
              contents.removeClass('margin');
        
            });
        
          })();
        
          // Start chart
        
          var chart = document.getElementById('myChart');
          Chart.defaults.global.animation.duration = 2000; // Animation duration
          Chart.defaults.global.title.display = false; // Remove title
          Chart.defaults.global.title.text = "Chart"; // Title
          Chart.defaults.global.title.position = 'bottom'; // Title position
          Chart.defaults.global.defaultFontColor = '#999'; // Font color
          Chart.defaults.global.defaultFontSize = 10; // Font size for every label
        
          // Chart.defaults.global.tooltips.backgroundColor = '#FFF'; // Tooltips background color
          Chart.defaults.global.tooltips.borderColor = 'white'; // Tooltips border color
          Chart.defaults.global.legend.labels.padding = 0;
          Chart.defaults.scale.ticks.beginAtZero = true;
          Chart.defaults.scale.gridLines.zeroLineColor = 'rgba(255, 255, 255, 0.1)';
          Chart.defaults.scale.gridLines.color = 'rgba(255, 255, 255, 0.02)';
        
          Chart.defaults.global.legend.display = false;
        
          var myChart = new Chart(chart, {
            type: 'bar',
            data: {
              labels: ["January", "February", "March", "April", "May", 'Jul'],
              datasets: [{
                label: "Lost",
                fill: false,
                lineTension: 0,
                data: [45, 25, 40, 20, 45, 20],
                pointBorderColor: "#4bc0c0",
                borderColor: '#4bc0c0',
                borderWidth: 2,
                showLine: true },
              {
                label: "Succes",
                fill: false,
                lineTension: 0,
                startAngle: 2,
                data: [20, 40, 20, 45, 25, 60],
                // , '#ff6384', '#4bc0c0', '#ffcd56', '#457ba1'
                backgroundColor: "transparent",
                pointBorderColor: "#ff6384",
                borderColor: '#ff6384',
                borderWidth: 2,
                showLine: true }] } });
        
        
        
          //  Chart ( 2 )
        
        
          var Chart2 = document.getElementById('myChart2').getContext('2d');
          var chart = new Chart(Chart2, {
            type: 'line',
            data: {
              labels: ["January", "February", "March", "April", 'test', 'test', 'test', 'test'],
              datasets: [{
                label: "My First dataset",
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 79, 116)',
                borderWidth: 2,
                pointBorderColor: false,
                data: [5, 10, 5, 8, 20, 30, 20, 10],
                fill: false,
                lineTension: .4 },
              {
                label: "Month",
                fill: false,
                lineTension: .4,
                startAngle: 2,
                data: [20, 14, 20, 25, 10, 15, 25, 10],
                // , '#ff6384', '#4bc0c0', '#ffcd56', '#457ba1'
                backgroundColor: "transparent",
                pointBorderColor: "#4bc0c0",
                borderColor: '#4bc0c0',
                borderWidth: 2,
                showLine: true },
              {
                label: "Month",
                fill: false,
                lineTension: .4,
                startAngle: 2,
                data: [40, 20, 5, 10, 30, 15, 15, 10],
                // , '#ff6384', '#4bc0c0', '#ffcd56', '#457ba1'
                backgroundColor: "transparent",
                pointBorderColor: "#ffcd56",
                borderColor: '#ffcd56',
                borderWidth: 2,
                showLine: true }] },
        
        
        
            // Configuration options
            options: {
              title: {
                display: false } } });
        
        
        
        
        
          console.log(Chart.defaults.global);
        
          var chart = document.getElementById('chart3');
          var myChart = new Chart(chart, {
            type: 'line',
            data: {
              labels: ["One", "Two", "Three", "Four", "Five", 'Six', "Seven", "Eight"],
              datasets: [{
                label: "Lost",
                fill: false,
                lineTension: .5,
                pointBorderColor: "transparent",
                pointColor: "white",
                borderColor: '#d9534f',
                borderWidth: 0,
                showLine: true,
                data: [0, 40, 10, 30, 10, 20, 15, 20],
                pointBackgroundColor: 'transparent' },
              {
                label: "Lost",
                fill: false,
                lineTension: .5,
                pointColor: "white",
                borderColor: '#5cb85c',
                borderWidth: 0,
                showLine: true,
                data: [40, 0, 20, 10, 25, 15, 30, 0],
                pointBackgroundColor: 'transparent' },
        
              {
                label: "Lost",
                fill: false,
                lineTension: .5,
                pointColor: "white",
                borderColor: '#f0ad4e',
                borderWidth: 0,
                showLine: true,
                data: [10, 40, 20, 5, 35, 15, 35, 0],
                pointBackgroundColor: 'transparent' },
        
              {
                label: "Lost",
                fill: false,
                lineTension: .5,
                pointColor: "white",
                borderColor: '#337ab7',
                borderWidth: 0,
                showLine: true,
                data: [0, 30, 10, 25, 10, 40, 20, 0],
                pointBackgroundColor: 'transparent' }] } });
        
        
        
        
        });
              //# sourceURL=pen.js
        </script>
    </body>
</html>

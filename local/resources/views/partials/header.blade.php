<?php use App\Config; ?>

<!-- Show Thai apha -->
<meta http-equiv="content-Type" content="text/html; charset=utf-8">

<!-- Latest compiled and minified CSS -->
<link href="{{Config::root()}}/frontend/css/bootstrap.min.css" rel="stylesheet">

<link href="{{Config::root()}}/frontend/css/main.css" rel="stylesheet">

<script type="text/javascript" src="{{Config::root()}}/frontend/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="{{Config::root()}}/frontend/js/bootstrap.min.js"></script>

<!-- angular -->
<script type="text/javascript" src="{{Config::root()}}/frontend/js/angular.min.js"></script>

<!-- app -->
<script type="text/javascript" src="{{Config::root()}}/frontend/app/mainApp.js"></script>
<script type="text/javascript" src="{{Config::root()}}/frontend/app/authApp.js"></script>
<script type="text/javascript" src="{{Config::root()}}/frontend/app/styleApp.js"></script>
<script type="text/javascript" src="{{Config::root()}}/frontend/app/userApp.js"></script>
<script type="text/javascript" src="{{Config::root()}}/frontend/app/graderApp.js"></script>
<script type="text/javascript" src="{{Config::root()}}/frontend/app/submitApp.js"></script>
<script type="text/javascript" src="{{Config::root()}}/frontend/app/taskApp.js"></script>
<script type="text/javascript" src="{{Config::root()}}/frontend/app/adminApp.js"></script>
<script type="text/javascript" src="{{Config::root()}}/frontend/app/contestApp.js"></script>
<script type="text/javascript" src="{{Config::root()}}/frontend/app/discussApp.js"></script>
<script type="text/javascript" src="{{Config::root()}}/frontend/app/messageApp.js"></script>

<!-- upload file api -->
<script src="{{Config::root()}}/frontend/js/angular-file-upload.js"></script>
<script src="{{Config::root()}}/frontend/js/UploadController.js"></script>

<!-- text editor -->
<link rel='stylesheet' href='{{Config::root()}}/frontend/textAngular/src/textAngular.css'>
<script src='{{Config::root()}}/frontend/textAngular/dist/textAngular-rangy.min.js'></script>
<script src='{{Config::root()}}/frontend/textAngular/dist/textAngular-sanitize.min.js'></script>
<script src='{{Config::root()}}/frontend/textAngular/dist/textAngular.min.js'></script>

<!-- Chart -->
<script src='{{Config::root()}}/frontend/js/Chart.min.js'></script>

<!-- Toaster -->
<link rel='stylesheet' href='{{Config::root()}}/frontend/css/toaster.min.css'>
<script type="text/javascript" src="{{Config::root()}}/frontend/js/toaster.min.js"></script>
<script type="text/javascript" src="{{Config::root()}}/frontend/js/angular-animate.min.js"></script>

 <!-- Angular Loading Bar -->
<link rel='stylesheet' href='//cdnjs.cloudflare.com/ajax/libs/angular-loading-bar/0.9.0/loading-bar.min.css' type='text/css' media='all' />
<style type="text/css">#loading-bar { display: inline; } #loading-bar .bar { background-color: #337ab7; }</style>
<script type='text/javascript' src='//cdnjs.cloudflare.com/ajax/libs/angular-loading-bar/0.9.0/loading-bar.min.js'></script>

<!-- Notification -->
<script src="{{Config::root()}}/frontend/js/socket.io.js"></script>
<script type="text/javascript" src="{{Config::root()}}/frontend/js/notular.js"></script>

<!-- font awesome -->
<link rel="stylesheet" href="{{Config::root()}}/frontend/css/font-awesome.min.css">

<!-- Allow smartphone user interface -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- favicon -->
 <link rel="shortcut icon" href="{{Config::root()}}/img/favicon.png">
 
<!-- Meta Tag (Facebook) -->
<!-- Format <meta property="og:{tagName}" content=""/> -->
<meta property="og:site_name" content="CodeCube"/>
<meta property="og:title" content="CodeCube"/>
<meta property="og:description" content="ชุมชนโปรแกรมเมอร์รุ่นใหม่ เว็บไซต์การแข่งขัน และ ฝึกเขียนโปรแกรม แก้ปัญหา" />
<meta property="og:image" content="{{ Config::root() }}/img/logo_new.png"/>
<meta property="og:type" content="Programming"/>
<meta property="og:locale" content="th_TH" />    

<meta property="fb:admins" content="100000077243173"/> <!-- PanTA -->
<meta property="fb:admins" content="100000037684756"/> <!-- SaBuZa -->
<meta property="fb:admins" content="1653498990"/> <!-- JETHO -->
<meta property="fb:admins" content="100001562738250"/> <!-- Wyte -->
<meta property="fb:admins" content="100000174725440"/> <!-- Mickey -->
<meta property="fb:admins" content="100000862045925"/> <!-- OOP -->
<!--
	PUT YOUR FACEBOOK USER ID HERE PLEASE!
<meta property="fb:admins" content=""/>
<meta property="fb:admins" content=""/> 
-->

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');

fbq('init', '545532888957360');
fbq('track', "PageView");</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=545532888957360&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<!-- custom HTML injection -->
<?= Config::custom() ?>

<title>{{Config::title()}}</title>

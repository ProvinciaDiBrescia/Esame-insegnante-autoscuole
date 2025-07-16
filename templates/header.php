<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
<?php
if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) {
	header('X-UA-Compatible: IE=edge,chrome=1');
}
?>
<title>Provincia di Brescia - Esami autoscuole</title>
<link href="css/style.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="alternate stylesheet" type="text/css" href="css/print-preview.css" media="screen" title="Print Preview" />
<link rel="stylesheet" type="text/css" href="css/print.css" media="print" />
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700,900" rel="stylesheet">
<script type="text/javascript" src="js/print.js"></script>
<style type="text/css" id="accessibar-pref-focus">
*:focus { -moz-outline: 3px solid #10bae0; -moz-outline-offset: 1px; -moz-outline-radius: 5px; }
button:focus::-moz-focus-inner { border-color: transparent; }
button::-moz-focus-inner,input[type="reset"]::-moz-focus-inner,input[type="button"]::-moz-focus-inner,input[type="submit"]::-moz-focus-inner,input[type="file"] > input[type="button"]::-moz-focus-inner { border: 1px dotted transparent;}
textarea:focus, button:focus, select:focus, input:focus { -moz-outline-offset: -1px; }
input[type="radio"]:focus {-moz-outline-radius: 12px; -moz-outline-offset: 0px; }
a:focus { -moz-outline-offset: 0px; }
</style>
<style type="text/css">
* {
	font-family: 'Montserrat', sans-serif !important;
}
html,
body {
	height: 100%
}
body {
	padding: 0 6px;
}
.wrapper {
	display: table;
	height: 100%;
	width: 100%;
}
.header {
	-moz-user-select: none;
	cursor: default;
	display: table-row;
	height: 200px;
	user-select: none;
}
#header {
	margin-top: 1px;
}
#titolo h1 {
	top: 19px;
}
.main {
	display: table-row;
	height: 100%;
}
.footer {
	display: table-row;
	height: 120px;
}
#footer {
	margin-bottom: 6px;
}
#wrapper {
	float: unset;
	height: calc(100% - 10px);
	position: unset;
}
#contenuto {
	height: calc(100% - 11px);
}
#login {
	margin: 50px auto;
}
.menu {
	text-align: left;
	padding: 0 10px;
}
ul#menu {
	margin: 0;
	width: 100%;
}
.menu ul li a.last {
	margin-right: 0;
}
#center-col {
	height: auto !important;
	padding: 10px 0;
}
button,
button:hover,
button:focus,
button:active {
	font-weight: 400 !important;
}
.paging_full_numbers a.paginate_button.current,
.paging_full_numbers a.paginate_active {
	font-weight: 700;
	color: #333 !important;
}
.ui-dialog .ui-dialog-title {
	font-size: 1.2em;
	text-transform: uppercase;
}
</style>
<?php
	$scripts = array(
		'jquery-1.12.4.min.js',
		'jquery-migrate-1.4.1.js',
		'jquery-ui-1.13.1.min.js',
		'jquery-ui-i18n.min.js',
		'jquery.dataTables.min.js',
		'jquery.dataTables.extend.js',
		'jquery.qtip.min.js',
		'jquery.validate.min.js',
		'select2.min.js',
		'ajaxfileupload.js',
		'jquery.blockUI.js',
		'sweetalert2.min.js',
	);

	foreach ($scripts AS $script) {
		echo '<script type="text/javascript" src="js/' . $script . '"></script>';
	}
?>
<!--[if (gte IE 6)&(lte IE 8)]>
<script type="text/javascript" src="js/selectivizr-min.js"></script>
<link href="css/ie7.css" rel="stylesheet" type="text/css" media="screen" />
<![endif]-->
</head>
<body>
<div id="loadpage"><span>CARICAMENTO IN CORSO</span></div>
<div class="wrapper">
	<div class="header">
		<div class="stemma"><a href="http://www.provincia.brescia.it/" title="Vai alla home page della Provincia di Brescia"><img src="img/headerprovincia.gif" alt="Stemma della Provincia di Brescia" /></a></div>
		<div id="titolo">
			<h1><a href="http://www.provincia.brescia.it/" title="Vai alla home page della Provincia di Brescia"><img src="img/provincia.gif" alt="Provincia di Brescia" /></a></h1>
			<p><span>La Provincia, il tuo Network</span></p>
		</div>
		<div id="header">
			<div id="globalNav" style="background: rgb(232, 232, 232) url(img/provincia1.gif) no-repeat scroll 16px top; height: 27px;"></div>
			<div id="testata">
				<h1><span>Provincia</span></h1>
			</div>
		</div>
		<p class="today">Brescia, <?php echo date('d/m/Y') ?> - <span class="current-hours">00</span>:<span class="current-min">00</span>:<span class="current-sec">00</span></p>
	</div>
	<div class="main">
		<div id="wrapper">
			<div id="contenuto">
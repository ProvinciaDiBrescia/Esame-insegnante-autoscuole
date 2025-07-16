<?php

date_default_timezone_set('Europe/Rome');

foreach ($_GET AS $key => $value) { ${$key} = $value; }
foreach ($_POST AS $key => $value) { ${$key} = $value; }

include_once('includes/config.php');

if (isset($_GET['logout']) && !empty($_GET['logout'])) {
	unset($_SESSION);
	session_destroy();
	header("Location: index.php");
	exit;
}

if (!empty($_REQUEST['service']) && !empty($_REQUEST['page'])) {
	$_SESSION['search'][$_REQUEST['page']] = 'search_param-0-table=s.id&search_param-0-operatore=%3D&search_param-0-valore='.$_REQUEST['service'].'&search_param-0-tipo=autocomplete&search_param-0-extra=servizi';
}

ob_start();

require_once('templates/header.php');

if (!isset($_SESSION['userinfo'])) {

	require_once('templates/login.php');

} else if (isset($_GET['page']) && !empty($_GET['page']) && strpos('://', $_GET['page']) == 0) {

	require_once('templates/' . $_GET['page'] . '.php');

} else {

	require_once('templates/questionari.php');

}

require_once('templates/footer.php');

ob_end_flush();

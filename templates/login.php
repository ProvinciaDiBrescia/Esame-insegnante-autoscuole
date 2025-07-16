<?php

require_once('includes/config.php');
require_once('includes/ldap.inc.php');

function checkUser($username, $password) {
	global $adldap;

	$ldap_suser = $username;
	$ldap_spass = $password;

	if ($adldap->authenticate($ldap_suser, $ldap_spass)) {
		return true;
	} else {
		return false;
	}
}

global $error_warning;

function checkPwd($uname, $pwd) {
	if (!checkUser($uname, $pwd)) { /* CONTROLLO LDAP */
		$error_warning = "Password di dominio inserita non valida.";

		$result = mysql_query("SELECT password FROM utenti WHERE username='".$_POST['username']."'");
		$row = mysql_fetch_array($result);
		if (!empty($row['password'])) {
			if (md5($_POST['password']) != $row['password']) {
				$error_warning = "Password inserita non valida.";
			} else {
				return 1;
			}
		}

		return $error_warning;
	} else {
		$result = mysql_query("SELECT attivo FROM utenti WHERE username='".$_POST['username']."'");
		$row = mysql_fetch_array($result);
		if (empty($row['attivo'])) {
			$error_warning = "Utente non abilitato.";

			return $error_warning;
		}
	}

	return 1;
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && empty($_POST['logout'])) {
	if (!isset($_POST['username']) || empty($_POST['username'])) {
		$error_warning = "Nome utente non inserito.";
	} else if (!isset($_POST['password']) || empty($_POST['password'])) {
		$error_warning = "Password non inserita.";
	} else {
		$check = checkPwd($_POST['username'], $_POST['password']);
		if ($check == 1) {
			$result = mysql_query("SELECT * FROM utenti WHERE username='".$_POST['username']."'");
			$row = mysql_fetch_array($result);
			unset($_SESSION['userinfo']);
			$_SESSION['userinfo'] = $row;

			mysql_free_result($result);

			ob_end_clean();

			header("Location: index.php");
			exit;
		} else {
			$error_warning = $check;
		}
	}
}
?>

<div id="login">
	<div class="div1">Inserisci i dati di login</div>
	<div class="div2">
		<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
		<?php } ?>
		<form action="index.php" id="formlogin" method="post">
		<fieldset>
		<legend style="display:none">Inserisci i dati di login</legend>
		<table>
		<tr>
		<td style="text-align: center" rowspan="3"><img src="img/login.png" alt="login" /></td>
		</tr>
		<tr>
		<td>Nome utente<br />
		<input type="text" id="username" name="username" style="margin-top: 4px;" />
		<br />
		<br />
		Password<br />
		<input type="password" id="password" name="password" style="margin-top: 4px;" /></td>
		</tr>
		<tr>
		<td style="text-align: right"><a onclick="$('#formlogin').submit();" class="button"><span class="button_left button_login"><!-- --></span><span class="button_middle">Accedi</span><span class="button_right"><!-- --></span></a></td>
		</tr>
		</table>
		</fieldset>
		</form>
	</div>
	<div class="div3"></div>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function () {
	if ($('#username').val() == '') {
		$('#username').focus();
	} else {
		$('#password').focus();
	}

	$('#formlogin input').keydown(function (e) {
		if (e.keyCode == 13) {
			$('#formlogin').submit();
		}
	});

	$('#loadpage').animate({opacity:0}, 600).hide("slow");
});
//]]>
</script>

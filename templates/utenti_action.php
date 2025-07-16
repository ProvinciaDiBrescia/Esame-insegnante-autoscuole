<?php
	include_once "../includes/config.php";
	include_once "../includes/functions.php";

	$action = $_REQUEST['action'];

	$id = (isset($_REQUEST['id'])) ? intval($_REQUEST['id']) : NULL;

	if ($action === 'insert' OR $action === 'update') {
		$query = "UPDATE utenti SET 
			profilo = " . intval($_REQUEST['profilo']) . ",
			attivo = 1
			WHERE id = " . intval($_REQUEST['id_utente']) . "
		";

		mysql_query($query);
	}

	if ($action === 'delete') {
		mysql_query("UPDATE utenti SET attivo = 0 WHERE id = " . $id);

		/* NOTA -> Si disabilita per non perdere un eventuale storico */
	}

	if ($action === 'autocomplete') {
		$result = mysql_query("
			SELECT
				id,
				CONCAT(cognome,' ',nome) AS descrizione
			FROM utenti
			WHERE UPPER(CONCAT(cognome,' ',nome)) LIKE '%" . strtoupper($_REQUEST['term']) . "%'
			ORDER BY CONCAT(cognome,' ',nome)
		");

		while($row = mysql_fetch_array($result)) {
			$rows[] = array(
				'id' => $row['id'],
				'value' => $row['descrizione'],
			);
		}

		echo json_encode($rows);
	}

	if ($action === 'check') {
		echo (mysql_num_rows(mysql_query("SELECT id FROM utenti WHERE attivo = 1 AND id = " . intval($_REQUEST['id']))) == 0) ? 'true' : 'false';
	}

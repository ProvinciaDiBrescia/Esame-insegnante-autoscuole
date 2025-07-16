<?php

	include_once '../includes/config.php';
	include_once '../includes/functions.php';

	$action = $_REQUEST['action'];

	$id = (isset($_REQUEST['id'])) ? intval($_REQUEST['id']) : null;

	$fields = array(
		'string' => array(
				'descrizione',
			),
		'serialize' => array(
				'categorie',
			),
	);

	if ($action === 'insert') {
		mysql_query('INSERT INTO argomenti SET '.generate_db_fields($fields));

		echo mysql_insert_id();
	}

	if ($action === 'update') {
		mysql_query('UPDATE argomenti SET '.generate_db_fields($fields).' WHERE id = '.$id);
	}

	if ($action === 'delete') {
		mysql_query('DELETE FROM argomenti WHERE id = '.$id);
	}

	if ($action === 'populate') {
		$result = mysql_query("SELECT id, descrizione FROM argomenti");

		while ($row = mysql_fetch_array($result)) {
			$rows[] = array(
				'id' => $row['id'],
				'descrizione' => $row['descrizione'],
			);
		}

		echo json_encode($rows);
	}

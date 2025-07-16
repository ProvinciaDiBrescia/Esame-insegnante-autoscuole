<?php

	include_once '../includes/config.php';
	include_once '../includes/functions.php';

	$action = $_REQUEST['action'];

	$table = $_REQUEST['tabella'];

	$id = (isset($_REQUEST['id'])) ? intval($_REQUEST['id']) : null;

	$fields = array(
		'string' => array(
				'descrizione',
				'note',
			),
	);

	if ($action === 'insert') {
		mysql_query('INSERT INTO tipo_'.$table.' SET '.generate_db_fields($fields));

		echo mysql_insert_id();
	}

	if ($action === 'update') {
		mysql_query('UPDATE tipo_'.$table.' SET '.generate_db_fields($fields).' WHERE id = '.$id);
	}

	if ($action === 'delete') {
		mysql_query('DELETE FROM tipo_'.$table.' WHERE id = '.$id);
	}

	if ($action === 'enable') {
		mysql_query('UPDATE tipo_'.$table.' SET attivo = 1 WHERE id = '.$id);
	}

	if ($action === 'disable') {
		mysql_query('UPDATE tipo_'.$table.' SET attivo = 0 WHERE id = '.$id);
	}

	if ($action === 'autocomplete') {
		$result = mysql_query("
			SELECT id, descrizione
			FROM tipo_".$table."
			WHERE UPPER(descrizione) LIKE '%".strtoupper($_REQUEST['term'])."%'
			AND (attivo = 1 OR id = '".$id."')
		");

		while ($row = mysql_fetch_array($result)) {
			$rows[] = array(
				'id' => $row['id'],
				'value' => $row['descrizione'],
			);
		}

		echo json_encode($rows);
	}

<?php
	include_once "../includes/config.php";
	include_once "../includes/functions.php";

	$action = $_REQUEST['action'];

	$id = (isset($_REQUEST['id'])) ? intval($_REQUEST['id']) : NULL;

	$fields = array(
		'integer' => array(
			'id_argomento',
		),
		'string' => array(
			'domanda',
		),
	);

	if ($action === 'insert') {
		mysql_query("INSERT INTO domande SET ".generate_db_fields($fields));
	}

	if ($action === 'update') {
		mysql_query("UPDATE domande SET " . generate_db_fields($fields) . " WHERE id = " . $id);
	}

	if ($action === 'delete') {
		mysql_query("DELETE FROM domande WHERE id = " . $id);
	}

	if ($action === 'enable') {
		mysql_query('UPDATE domande SET attivo = 1 WHERE id = '.$id);
	}

	if ($action === 'disable') {
		mysql_query('UPDATE domande SET attivo = 0 WHERE id = '.$id);
	}

	if ($action === 'autocomplete') {
		$result = mysql_query("
			SELECT a.id, a.descrizione AS titolo 
			FROM domande AS a
			WHERE UPPER(a.descrizione) LIKE '%" . $_REQUEST['term'] . "%'
		");
		while ($row = mysql_fetch_array($result)) {
			$rows[] = array(
				'id' => $row['id'],
				'value' => $row['titolo'],
			);
		}

		echo json_encode($rows);
	}

	if ($action === 'export') {
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment;filename=export_domande.csv');
		$fp = fopen('php://output', 'w');

		$query = '
			SELECT
				a.descrizione AS argomento,
				d.domanda
			FROM domande AS d
			INNER JOIN argomenti AS a ON d.id_argomento = a.id
			WHERE d.attivo = 1
			ORDER BY argomento, domanda
		';

		$result = mysql_query($query);

		if ($row = mysql_fetch_assoc($result)) {
			foreach (array_keys($row) AS $key) {
				$header[] = ucfirst(str_replace("_"," ",$key));
			}
			fputcsv($fp, $header, ';');
			mysql_data_seek($result, 0);
		}

		while ($row = mysql_fetch_assoc($result)) {
			fputcsv($fp, $row, ';');
		}

		fclose($fp);
	}

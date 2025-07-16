<?php
	include_once "../includes/config.php";
	include_once "../includes/functions.php";

	require_once('../includes/ForceUTF8/Encoding.php');

	$action = $_REQUEST['action'];

	$id = (isset($_REQUEST['id'])) ? intval($_REQUEST['id']) : NULL;

	$fields = array(
		'date' => array(
			'data_esame',
		),
	);

	if ($action === 'insert') {
		mysql_query('INSERT INTO questionari SET '.generate_db_fields($fields));

		$questionario_id = mysql_insert_id();

		$categorie = array();

		$query = mysql_query("SELECT * FROM tipo_categoria ORDER BY id");

		while ($row = mysql_fetch_object($query)) {
			$categorie[$row->id] = array(
				'domande' => array(),
			);
		}

		$argomenti = array();

		$query = mysql_query("SELECT * FROM argomenti");

		while ($row = mysql_fetch_object($query)) {
			$domande = array();

			$query1 = mysql_query("SELECT * FROM domande WHERE id_argomento = " . $row->id);

			while ($row1 = mysql_fetch_object($query1)) {
				$domande[$row1->id] = $row1->domanda;
			}

			$categorie_argomenti = unserialize($row->categorie);

			foreach ($categorie_argomenti AS $categoria) {
				$result = $categorie[$categoria]['domande'] + $domande;

				$categorie[$categoria]['domande'] = $result;
			}
		}

		for ($i = 1; $i <= intval($_REQUEST['schede']); $i++) {
			mysql_query('INSERT INTO schede SET id_questionario = ' . $questionario_id . ', codice = ' . $i);

			$scheda_id = mysql_insert_id();

			foreach ($categorie AS $key => $categoria) {
				$domanda = array_rand($categoria['domande'], 1);

				mysql_query("INSERT INTO schede_domande SET id_scheda = ".$scheda_id.", id_domanda = ".$domanda.", ordine = " . $key);

				unset($categorie[$key]['domande'][$domanda]);
			}
		}

		echo $questionario_id;
	}

	if ($action === 'update') {
		mysql_query('UPDATE questionari SET '.generate_db_fields($fields).' WHERE id = '.$id);
	}

	if ($action === 'delete') {
		mysql_query("DELETE FROM questionari WHERE id = " . $id);

		mysql_query("DELETE FROM schede_domande WHERE id_scheda IN (SELECT id FROM schede WHERE id_questionario = " . $id . ")");

		mysql_query("DELETE FROM schede WHERE id_questionario = " . $id);
	}

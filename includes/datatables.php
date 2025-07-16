<?php
	if (empty($_SERVER['HTTP_REFERER'])) {
		http_response_code(404);
		die();
	};

	include_once('config.php');
	include_once('functions.php');

	require_once('ForceUTF8/Encoding.php');

	foreach ($_GET AS $key => $value) {
		$_GET[$key] = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH);

		if ($key[0] == 'i') {
			$pattern = '/^([0-9 ]+)(.*)$/';

			$value = preg_replace($pattern, '$1', trim($value));

			if (strpos($value, " ")) {
				$value = substr($value, 0, strpos($value, " "));
			}

			$_GET[$key] = var_export(filter_var(trim($value), FILTER_VALIDATE_INT), true);
		}

		if ($key[0] == 'b') {
			if (strpos($value, " ")) {
				$value = substr($value, 0, strpos($value, " "));
			}

			$_GET[$key] = var_export(filter_var(trim($value), FILTER_VALIDATE_BOOLEAN), true);
		}

		if (substr($_GET[$key], 0, 8) == "sSortDir") {
			$pattern = '/^([asc|desc]+)(.*)$/';

			$_GET[$key] = preg_replace($pattern, '$1', trim($value));
		}

		$_GET[$key] = htmlentities($value);
	}

	$sDistinct = '';
	$sJoin = '';
	$sGroupBy = '';
	$sHaving = '';
	$sWhere = '';

	if ($_GET['sezione'] == 'questionari') {
		$aColumns = array(
			'q.id',
			'q.data_esame',
			'(SELECT COUNT(*) FROM schede WHERE id_questionario = q.id) AS schede',
			' '
		);
		$sIndexColumn = 'q.id';
		$sFrom = 'questionari AS q';
	} else if ($_GET['sezione'] == 'argomenti') {
		$aColumns = array(
			'a.id',
			'a.descrizione',
			'a.categorie',
			'(SELECT COUNT(*) FROM domande WHERE id_argomento = a.id AND attivo = 1) as domande',
			' '
		);
		$sIndexColumn = 'a.id';
		$sFrom = 'argomenti AS a';
	} else if ($_GET['sezione'] == 'domande') {
		$aColumns = array(
			'd.id',
			'd.domanda',
			'a.descrizione AS argomento',
			' '
		);
		$sIndexColumn = 'd.id';
		$sFrom = 'domande AS d';
		$sJoin = '
			LEFT JOIN argomenti AS a ON d.id_argomento = a.id
		';
	} else if ($_GET['sezione'] == 'tipologia') {
		$aColumns = array(
			't.id',
			't.descrizione',
			't.note',
			' '
		);
		$sIndexColumn = 't.id';
		$sFrom = 'tipo_' . $_GET['tabella'] . ' AS t';
	} else if ($_GET['sezione'] == 'utenti') {
		$aColumns = array(
			'u.id',
			'CONCAT(u.cognome," ",u.nome) AS utente',
			's1.descrizione AS area',
			's2.descrizione AS settore',
			'p.descrizione AS profilo',
			' '
		);
		$sIndexColumn = 'u.id';
		$sFrom = 'utenti AS u';
		$sJoin = '
			INNER JOIN profili AS p ON p.id = u.profilo
			LEFT JOIN struttura_attuale AS s1 ON s1.codice = u.area
			LEFT JOIN struttura_attuale AS s2 ON s2.codice = u.settore
		';
		$sWhere = 'WHERE u.attivo = 1';
	}

	/*
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
			mysql_real_escape_string( $_GET['iDisplayLength'] );
	}

	/*
	 * Ordering
	 */
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$2', $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]))."
					".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
		}

		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}

	/*
	 * Filtering
	 */

	/* FILTRO DI RICERCA */
	$a_search = array(
		'pratiche',
		'archivio',
	);
	if (in_array($_GET['sezione'], $a_search)) {
		$search_params = array();
		if (!empty($_REQUEST['ricerca']) && strpos($_REQUEST['ricerca'],'&') > 0) {
			$_REQUEST['ricerca'] = explode("&", $_REQUEST['ricerca']);

			foreach ($_REQUEST['ricerca'] AS $r) {
				$r = explode("=", $r);
				$r[0] = explode("-", $r[0]);
				if (empty($search_params[$r[0][1]][$r[0][2]])) {
					$search_params[$r[0][1]][$r[0][2]] = urldecode($r[1]);
				} else {
					if (!is_array($search_params[$r[0][1]][$r[0][2]])) {
						$search_params[$r[0][1]][$r[0][2]] = explode(" ", $search_params[$r[0][1]][$r[0][2]]);
					}

					$search_params[$r[0][1]][$r[0][2]][] = urldecode($r[1]);
				}
			}

			foreach ($search_params AS $sp) {
				if (strlen($sp['valore']) > 0 || is_array($sp['valore'])) {
					switch($sp['tipo']) {
						case 'date': $sp['valore'] = convert_date_mysql($sp['valore']); break;
						case 'scrollbox':
						case 'text': $sp['valore'] = "%" . $sp['valore'] . "%"; break;
						case 'multiselect':
							$sp['valore'] = "(" . (!is_array($sp['valore']) ? $sp['valore'] : implode(",", $sp['valore'])) . ")";
							break;
					}
					$sWhere .= ( $sWhere == "" ) ? "WHERE " : " AND ";
					if ($sp['operatore'] == 'IN' || $sp['operatore'] == 'NOT IN') {
						$sWhere .= $sp['table'] . " " . $sp['operatore'] . " " . $sp['valore'];
					} else {
						$sWhere .= $sp['table'] . " " . $sp['operatore'] . " '" . $sp['valore'] . "'";
					}
				} else {
					$sWhere .= ( $sWhere == "" ) ? "WHERE " : " AND ";
					$sWhere .= $sp['table'] . " IS NULL";
				}
			}
		}
	}

	if ( !empty($_GET['sSearch']) )
	{
		if (preg_match('/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/', $_GET['sSearch'])) {
			$_GET['sSearch'] = convert_date_mysql($_GET['sSearch']);
		}

		$sHaving .= ($sHaving == "") ? "HAVING (" : " AND (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" )
			{
				$sHaving .= "UPPER(CONVERT(" . trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$2', $aColumns[$i]))." USING utf8)) LIKE '%".strtoupper(mysql_real_escape_string( $_GET['sSearch'] ))."%' OR ";
			}
		}
		$sHaving = substr_replace( $sHaving, "", -3 );
		$sHaving .= ')';
	}

	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sHaving == "" )
			{
				$sHaving = "HAVING ";
			}
			else
			{
				$sHaving .= " AND ";
			}
			$sHaving .= "UPPER(CONVERT(" . trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$1', $aColumns[$i]))." USING utf8)) LIKE '%".strtoupper(mysql_real_escape_string($_GET['sSearch_'.$i]))."%' ";
		}
	}

	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = trim("
		SELECT " . $sDistinct . " SQL_CALC_FOUND_ROWS ".str_replace(",  ", "", implode(", ", $aColumns))."
		FROM $sFrom
		$sJoin
		$sWhere
		$sGroupBy
		$sHaving
		$sOrder
		$sLimit
	");
	$rResult = mysql_query( $sQuery ) or die(mysql_error());

	/* Data set length after filtering */
	$sQuery = "
		SELECT " . $sDistinct . " FOUND_ROWS()
	";
	$rResultFilterTotal = mysql_query( $sQuery ) or die(mysql_error());
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];

	/* Total data set length */
	$sQuery = trim("
		SELECT " . $sDistinct . " COUNT(".$sIndexColumn.")
		FROM $sFrom
	");
	$rResultTotal = mysql_query( $sQuery ) or die(mysql_error());
	$aResultTotal = mysql_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];

	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);

	foreach ($aColumns as $key => $val)
	{
		$aColumns[$key] = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$2', $val));
	}

	while ( $aRow = mysql_fetch_array( $rResult ) )
	{
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$aColumns[$i] = (strpos($aColumns[$i], ".") > 0) ? substr($aColumns[$i], strpos($aColumns[$i], ".") + 1) : $aColumns[$i];

			$row['DT_RowId'] = 'record' . $aRow['id'];

			if ( substr($aColumns[$i],0,5) == 'data_' /*in_array($aColumns[$i], $a_dataField)*/ )
			{
				$row[] = convert_date_it($aRow[ $aColumns[$i] ]);
			}
			else if ($_GET['sezione'] == 'argomenti' && ($aColumns[$i] == "categorie"))
			{
				$text = '';
				if (!empty($aRow[ $aColumns[$i] ])) {
					$text .= '<ul>';
					foreach (unserialize($aRow[ $aColumns[$i] ]) AS $categoria) {
						list($descrizione) = mysql_fetch_row(mysql_query("SELECT descrizione FROM tipo_categoria WHERE id = " . intval($categoria)));
						$text .= '<li>' . $descrizione . '</li>';
					}
					$text .= '</ul>';
				} else {
					$text .= 'Non costituisce oggetto del programma di esame';
				}

				$row[] = $text;
			}
			else if ( $aColumns[$i] != '' )
			{
				$row[] = $aRow[ $aColumns[$i] ];
			}
			else
			{
				if ($_GET['sezione'] == 'questionari') {
					$row[] = '<span class="databtn transfer" title="Stampa questionario" rel="'.$aRow[$aColumns[0]].'"></span><span class="databtn delete" title="Elimina questionario" rel="'.$aRow[$aColumns[0]].'"></span>'; 
				} else if ($_GET['sezione'] == 'anagrafica') {
					$row[] = '<span class="databtn edit" title="Modifica anagrafica ' . str_replace("_"," ",$_GET['tabella']) . '" rel="'.$aRow[$aColumns[0]].'"></span>';
				} else if ($_GET['sezione'] == 'argomenti') {
					$content = '<span class="databtn edit" title="Modifica argomento" rel="'.$aRow[$aColumns[0]].'"></span>';

					if ($aRow[$aColumns[3]] == 0) {
						$content .= '<span class="databtn delete" title="Elimina argomento" rel="'.$aRow[$aColumns[0]].'"></span>';
					} else {
						$content .= '<span class="databtn delete disabled"></span>';
					}

					$row[] = $content;
				} else if ($_GET['sezione'] == 'domande') {
					list($attivo) = mysql_fetch_row(mysql_query('SELECT attivo FROM domande WHERE id='.$aRow[$aColumns[0]]));

					if ($attivo) {
						$action = 'disable';
						$text = 'Disabilita';
						$row['DT_RowClass'] = '';
					} else {
						$action = 'enable';
						$text = 'Abilita';
						$row['DT_RowClass'] = 'disabled';
					}

					$content = '<span class="databtn '.$action.'" title="'.$text.'" rel="'.$aRow[$aColumns[0]].'"></span>';

					if ($attivo) {
						$content .= '<span class="databtn edit" title="Modifica domanda" rel="'.$aRow[$aColumns[0]].'"></span>';
					} else {
						$content .= '<span class="databtn edit disabled"></span>';
					}

					if (mysql_num_rows(mysql_query("SELECT id_scheda FROM schede_domande WHERE id_domanda = " . $aRow[$aColumns[0]])) == 0) {
						$content .= '<span class="databtn delete" title="Elimina domanda" rel="'.$aRow[$aColumns[0]].'"></span>';
					} else {
						$content .= '<span class="databtn delete disabled"></span>';
					}

					$row[] = $content;
				} else if ($_GET['sezione'] == 'tipologia') {
					list($attivo) = mysql_fetch_row(mysql_query('SELECT attivo FROM tipo_'.$_GET['tabella'].' WHERE id='.$aRow[$aColumns[0]]));

					if ($attivo) {
						$action = 'disable';
						$text = 'Disabilita';
						$row['DT_RowClass'] = '';
					} else {
						$action = 'enable';
						$text = 'Abilita';
						$row['DT_RowClass'] = 'disabled';
					}

					$content = '<span class="databtn '.$action.'" title="'.$text.'" rel="'.$aRow[$aColumns[0]].'"></span>';

					if ($attivo) {
						$content .= '<span class="databtn edit" title="Modifica '.str_replace('_', ' ', $_GET['tabella']).'" rel="'.$aRow[$aColumns[0]].'"></span>';
					} else {
						$content .= '<span class="databtn edit disabled"></span>';
					}

					switch ($_GET['tabella']) {
						case 'grado': $table = 'pratiche'; $field = 'grado'; break;
						case 'materia': $table = 'pratiche'; $field = 'materia'; break;
						case 'sede': $table = 'pratiche'; $field = 'sede'; break;
						case 'foro': $table = 'pratiche'; $field = 'foro'; break;
						case 'pratica': $table = 'pratiche'; $field = 'tipologia'; break;
					}
					if (!empty($table) && mysql_num_rows(mysql_query("SELECT id FROM " . $table . " WHERE " . $field . " = " . $aRow[$aColumns[0]])) == 0) {
						$content .= '<span class="databtn delete" title="Elimina ' . str_replace("_"," ",$_GET['tabella']) . '" rel="'.$aRow[$aColumns[0]].'"></span>';
					} else {
						$content .= '<span class="databtn delete disabled"></span>';
					}
					$row[] = $content;
				} else if ($_GET['sezione'] == 'utenti') {
					$row[] = '<span class="databtn edit" title="Modifica utente" rel="'.$aRow[$aColumns[0]].'"></span><span class="databtn delete" title="Elimina utente" rel="'.$aRow[$aColumns[0]].'"></span>';
				} else {
					$row[] = '';
				}
			}
		}
		$output['aaData'][] = $row;
	}

	echo json_encode( ForceUTF8\Encoding::toUTF8($output) );

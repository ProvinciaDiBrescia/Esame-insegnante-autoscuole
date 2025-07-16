			</div>
		</div>
	</div>
	<div class="footer">
		<div id="footer" style="background: #ecf1f5 url(img/footerprovincia.gif) no-repeat scroll 0; border: 1px solid #ddd; border-top: 0px;">
			<img style="margin-top: 29px; margin-left: 15px;" src="img/cit.gif" alt="logo cit" />
			<p class="info" style="left: 220px; padding-top: 10px;">Info: <a href="mailto:assistenza@provincia.brescia.it">assistenza@provincia.brescia.it</a><br />
			Assistenza: <a href="https://assistenza.provincia.brescia.it/">clicca qui</a><br />
			Provincia di Brescia<br />
			Via Milano, 13 | 25126 Brescia | Italia<br />
			Centralino +39.030.3748524<br />
			P. I.V.A. 03046380170<br /></p>
			<p class="css"><a href="http://validator.w3.org/check?uri=referer"><img src="img/css.gif" alt="css valid" /></a></p>
			<p class="w3c"><a href="http://validator.w3.org/check?uri=referer"><img style="height: 31px; width: 88px;" src="img/valid-xhtml10-blue.png" alt="Valid XHTML 1.0 Strict" /></a></p>
		</div>
	</div>
</div>
<div id="form_dialog"></div>
<div id="export_dialog" title="Selezionare parametri di esportazione" style="display:none"></div>
<div id="search_dialog" title="Selezionare parametri di ricerca" style="display:none"><?php
if (!empty($_SESSION['search'][$_REQUEST['page']])) {
	if (!empty($_SESSION['search'][$_REQUEST['page']]) && strpos($_SESSION['search'][$_REQUEST['page']],'&') > 0) {
		$params = explode("&", $_SESSION['search'][$_REQUEST['page']]);

		$_SEARCH = include('includes/search.php');
?>
		<script type="text/javascript">
		//<![CDATA[
		$(document).ready(function () {
			$('#addsearch').button({ icons: { primary: "fa fa-plus" } });
			$('#delsearch').button({ icons: { primary: "fa fa-minus" } });
		});
		//]]>
		</script>
		<form id="searchform">
		<ul id="searchmenu">
<?php
		foreach ($params AS $r) {
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

		$content = '';
		foreach ($search_params AS $key => $sp) {
			$content .= '<li>
					<select name="search_param-'.$key.'-table" rel="'.$key.'" class="text ui-widget-content ui-corner-all params">
					<option value="0"> - Selezionare un parametro - </option>
			';
			foreach ($_SEARCH[$_REQUEST['page']] as $k => $v) {
				if (empty($v['admin']) || $_SESSION['userinfo']['profilo'] != 2) {
					$content .= '<option value="'.$k.'" rel="'.$v['rel'].'"'.(!empty($v['class']) ? ' class="'.$v['class'].'"' : '').(($sp['table'] === $k) ? ' selected="selected"' : '').'>'.$v['text'].'</option>';
				}
			}
			$content .= '</select><span class="extra">';

			switch ($sp['tipo']) {
				case 'date':
					$content .= '<select name="search_param-'.$key.'-operatore" class="text ui-widget-content ui-corner-all"><option value="="'.(($sp['operatore'] === '=') ? ' selected="selected"' : '').'>uguale a</option><option value=">"'.(($sp['operatore'] === '>') ? ' selected="selected"' : '').'>successivo a</option><option value="<"'.(($sp['operatore'] === '<') ? ' selected="selected"' : '').'>antecedente a</option></select><input type="text" name="search_param-'.$key.'-valore" value="'.$sp['valore'].'" class="text ui-widget-content ui-corner-all calendar" />';
					$content .= '<input type="hidden" name="search_param-'.$key.'-tipo" value="'.$sp['tipo'].'" />';
					$content .= '<script>$(document).on("mousedown", ".calendar", function () {
						$(this).datepicker($.datepicker.regional[\'it\']);
					  });</script>';
				break;
				case 'number':
					$content .= '<select name="search_param-'.$key.'-operatore" class="text ui-widget-content ui-corner-all"><option value="="'.(($sp['operatore'] === '=') ? ' selected="selected"' : '').'>uguale a</option><option value=">"'.(($sp['operatore'] === '>') ? ' selected="selected"' : '').'>maggiore di</option><option value="<"'.(($sp['operatore'] === '<') ? ' selected="selected"' : '').'>minore di</option></select><input type="text" name="search_param-'.$key.'-valore" value="'.$sp['valore'].'" class="text ui-widget-content ui-corner-all money" />';
					$content .= '<input type="hidden" name="search_param-'.$key.'-tipo" value="'.$sp['tipo'].'" />';
				break;
				case 'radio':
					$content .= '<input type="hidden" name="search_param-'.$key.'-operatore" value="=" />';
					$content .= '<input type="hidden" name="search_param-'.$key.'-tipo" value="'.$sp['tipo'].'" />';
					$content .= '<input type="radio" name="search_param-'.$key.'-valore" value="1"'.(($sp['valore'] == 1) ? ' checked="checked"' : '').'>Presente</input><input type="radio" name="search_param-'.$key.'-valore" value="0"'.(($sp['valore'] == 0) ? ' checked="checked"' : '').'>Assente</input>';
				break;
				case 'select':
					$content .= '<select name="search_param-'.$key.'-operatore" class="text ui-widget-content ui-corner-all"><option value="="'.(($sp['operatore'] === '=') ? ' selected="selected"' : '').'>uguale a</option><option value="<>"'.(($sp['operatore'] === '<>') ? ' selected="selected"' : '').'>diverso da</option></select>';
					$content .= '<select name="search_param-'.$key.'-valore" class="text ui-widget-content ui-corner-all"><option value="">&nbsp;</option>';
					if ($sp['extra'] == 'servizio_convenzione')
					{
						$result = mysql_query("SELECT id, descrizione FROM convenzioni ORDER BY descrizione");
						$content .= '<optgroup label="Convenzioni">';
						while ($row = mysql_fetch_array($result)) {
							$content .= '<option value="c'.$row['id'].'"'.(($row['id'] == $sp['valore']) ? ' selected="selected"' : '').'>'.$row['descrizione'].'</option>';
						}
						$content .= '</optgroup>';
						$result = mysql_query("SELECT id, descrizione FROM servizi ORDER BY descrizione");
						$content .= '<optgroup label="Servizi">';
						while ($row = mysql_fetch_array($result)) {
							$content .= '<option value="s'.$row['id'].'"'.(($row['id'] == $sp['valore']) ? ' selected="selected"' : '').'>'.$row['descrizione'].'</option>';
						}
						$content .= '</optgroup>';
					}
					else
					{
						if (substr($sp['extra'],0,5) == 'tipo_' || $sp['extra'] == 'clienti' || $sp['extra'] == 'convenzioni' || $sp['extra'] == 'servizi') {
							$col = 'descrizione';
						} else if ($sp['extra'] == 'capitoli') {
							$col = 'CONCAT(capitolo," - ",descrizione)';
						} else if ($sp['extra'] == 'referenti') {
							$col = 'CONCAT(cognome," ",nome)';
						} else if ($sp['extra'] == 'fornitori') {
							$col = 'CONCAT(descrizione, CASE WHEN tipologia = 1 THEN if (partita_iva IS NOT NULL,CONCAT(" (",partita_iva,")"),"") WHEN tipologia = 2 THEN if (codice_fiscale IS NOT NULL,CONCAT(" (",codice_fiscale,")"),"") END)';
						}
						$result = mysql_query("SELECT id, ".$col." AS descrizione FROM " . $sp['extra'] . " ORDER BY descrizione");
						while ($row = mysql_fetch_array($result)) {
							$content .= '<option value="'.$row['id'].'"'.(($row['id'] == $sp['valore']) ? ' selected="selected"' : '').'>'.$row['descrizione'].'</option>';
						}
					}
					$content .= '</select>';
					$content .= '<input type="hidden" name="search_param-'.$key.'-tipo" value="'.$sp['tipo'].'" />';
					$content .= '<input type="hidden" name="search_param-'.$key.'-extra" value="'.$sp['extra'].'" />';
				break;
				case 'multiselect':
					$options = array();
					if (substr($sp['extra'],0,5) == 'tipo_' || $sp['extra'] == 'attori') {
						$col = 'descrizione';
					} else if ($sp['extra'] == 'capitoli') {
						$col = 'CONCAT(capitolo," - ",descrizione)';
					} else if ($sp['extra'] == 'referenti') {
						$col = 'CONCAT(cognome," ",nome)';
					} else if ($sp['extra'] == 'fornitori') {
						$col = 'CONCAT(descrizione, CASE WHEN tipologia = 1 THEN if (partita_iva IS NOT NULL,CONCAT(" (",partita_iva,")"),"") WHEN tipologia = 2 THEN if (codice_fiscale IS NOT NULL,CONCAT(" (",codice_fiscale,")"),"") END)';
					}
					$result = mysql_query("SELECT id, ".$col." AS descrizione FROM " . $sp['extra'] . " WHERE id IN (" . (!is_array($sp['valore']) ? $sp['valore'] : implode(",", $sp['valore'])) . ") ORDER BY descrizione");
					while ($row = mysql_fetch_array($result)) {
						$options[$row['id']] = $row['descrizione'];
					}
					$content .= '<select name="search_param-'.$key.'-operatore" class="text ui-widget-content ui-corner-all"><option value="IN"'.(($sp['operatore'] === 'IN') ? ' selected="selected"' : '').'>uguale a</option><option value="NOT IN"'.(($sp['operatore'] === 'NOT IN') ? ' selected="selected"' : '').'>diverso da</option></select>';
					$content .= '<select multiple="multiple" name="search_param-'.$key.'-valore" class="text ui-widget-content ui-corner-all"><option value="">&nbsp;</option>';
					$content .= '</select>';
					$content .= '<script>
						$(\'[name^="search_param-'.$key.'-valore"]\').select2({
							theme: "classic",
							language: {
								errorLoading: function() {
									return "I risultati non possono essere caricati."
								},
								inputTooLong: function (e) {
									var n=e.input.length-e.maximum,t="Per favore cancella "+n+" caratter";
									return t+=1!==n?"i":"e"
								},
								inputTooShort: function (e) {
									return "Per favore inserisci "+(e.minimum-e.input.length)+" o più caratteri"
								},
								loadingMore: function () {
									return "Caricando più risultati…"
								},
								maximumSelected: function (e) {
									var n="Puoi selezionare solo "+e.maximum+" element";
									return 1!==e.maximum?n+="i":n+="o",n
								},
								noResults: function () {
									return"Nessun risultato trovato"
								},
								searching: function () {
									return "Sto cercando…"
								},
								removeAllItems: function () {
									return "Rimuovi tutti gli oggetti"
								}
							},
							ajax: {
								delay: 250,
								url: "templates/parametri_action.php?action=autocomplete&tabella='.$sp['extra'].'",
								dataType: "json",
								processResults: function (resp) {
									var data = $.map(resp, function (obj) {
										obj.text = obj.text || obj.value;
		
										return obj;
									});
		
									return {
										results: data
									};
								}
							},
							minimumInputLength: 3
						});

						var select = $(\'[name^="search_param-'.$key.'-valore"]\');
					';

					foreach ($options AS $k => $v) {
						$content .= '
							var option = new Option("'.$v.'", '.$k.', true, true);
							select.append(option);
						';
					}

					$content .= '
						select.trigger("change");

						select.trigger({
							type: "select2:select",
							params: {
								data: data
							}
						});
					</script>';
		
					$content .= '<input type="hidden" name="search_param-'.$key.'-tipo" value="'.$sp['tipo'].'" />';
					$content .= '<input type="hidden" name="search_param-'.$key.'-extra" value="'.$sp['extra'].'" />';
				break;
				case 'autocomplete':
					list($text) = mysql_fetch_row(mysql_query('SELECT descrizione FROM '.$sp['extra'].' WHERE id = '.intval($sp['valore'])));

					$content .= '<select name="search_param-'.$key.'-operatore" class="text ui-widget-content ui-corner-all"><option value="="'.(($sp['operatore'] === '=') ? ' selected="selected"' : '').'>uguale a</option><option value="<>"'.(($sp['operatore'] === '<>') ? ' selected="selected"' : '').'>diverso da</option></select>';
					$content .= '<script>
						$(document).on("keyup.autocomplete", "#desc-'.$key.'", function() {
							$("#desc-'.$key.'").autocomplete({
								source: "templates/parametri_action.php?action=autocomplete&tabella='.$sp['extra'].'",
								minLength: 2,
								select: function (event, ui) {
									$(this).val(ui.item.value);
									$("[name=search_param-'.$key.'-valore]").val(ui.item.id);
								}
							});
							$("#desc-'.$key.'").on("keydown", function(e) {
								if (e.keyCode != 9 && e.keyCode != 13) {
									$("[name=search_param-'.$key.'-valore]").val("");
								}
							});
						});
					</script>';

					$content .= '<input type="text" id="desc-' . $key . '" value="'.$text.'" class="text ui-widget-content ui-corner-all" />';
					$content .= '<input type="hidden" name="search_param-'.$key.'-valore" value="'.$sp['valore'].'" />';
					$content .= '<input type="hidden" name="search_param-'.$key.'-tipo" value="'.$sp['tipo'].'" />';
					$content .= '<input type="hidden" name="search_param-'.$key.'-extra" value="'.$sp['extra'].'" />';
				break;
				case 'scrollbox':
					$content .= '<input type="hidden" name="search_param-'.$key.'-operatore" value="LIKE" />';
					$content .= '<span style="margin-left: 10px">=</span><select name="search_param-'.$key.'-valore" class="text ui-widget-content ui-corner-all"><option value="">&nbsp;</option>';
					if (substr($sp['extra'],0,5) == 'tipo_') {
						$col = 'descrizione';
					} else {
						$col = 'CONCAT(cognome," ",nome)';
					}
					$result = mysql_query("SELECT id, ".$col." AS descrizione FROM " . $sp['extra'] . " ORDER BY descrizione");
					while ($row = mysql_fetch_array($result)) {
						$content .= '<option value="'.$row['id'].'"'.(($row['id'] == $sp['valore']) ? ' selected="selected"' : '').'>'.$row['descrizione'].'</option>';
					}
					$content .= '</select>';
					$content .= '<input type="hidden" name="search_param-'.$key.'-tipo" value="'.$sp['tipo'].'" />';
					$content .= '<input type="hidden" name="search_param-'.$key.'-extra" value="'.$sp['extra'].'" />';
				break;
				case 'text':
					$content .= '<select name="search_param-'.$key.'-operatore" class="text ui-widget-content ui-corner-all"><option value="LIKE"'.(($sp['operatore'] === 'LIKE') ? ' selected="selected"' : '').'>contiene</option><option value="NOT LIKE"'.(($sp['operatore'] === 'NOT LIKE') ? ' selected="selected"' : '').'>non contiene</option></select><input type="text" name="search_param-'.$key.'-valore" value="'.$sp['valore'].'" class="text ui-widget-content ui-corner-all" />';
					$content .= '<input type="hidden" name="search_param-'.$key.'-tipo" value="'.$sp['tipo'].'" />';
				break;
			}

			if ($key > 0) $content .= '<span class="delparam" title="Rimuovi parametro"></span>';

			$content .= '</span></li>';
		}

		$content .= '</ul>';
		$content .= '</form>';

		echo $content;
?>
		<button id="addsearch">Aggiungi parametro</button>
		<button id="delsearch">Cancella filtri</button>
<?php
	}
}
?></div>
<div id="extra_dialog" style="display:none"></div>
<script type="text/javascript" src="js/javascript.js"></script>
</body>
</html>

<?php

ini_set('max_execution_time', '0');
ini_set('memory_limit', '-1');

include "../config.php";
include "../functions.php";

require_once('../ForceUTF8/Encoding.php');

$numero = intval($_REQUEST['numero']);

list($data_esame) = mysql_fetch_row(mysql_query("SELECT data_esame FROM questionari q WHERE q.id = " . $numero));
$data_esame = convert_date_it($data_esame);

$query = mysql_query('
  SELECT
	d.domanda,
	s.codice,
	sd.ordine
  FROM
    domande AS d
  INNER JOIN schede_domande AS sd ON sd.id_domanda = d.id
  INNER JOIN schede AS s ON sd.id_scheda = s.id
  INNER JOIN questionari AS q ON s.id_questionario = q.id
  WHERE q.id = ' . $numero . '
  ORDER BY s.id, sd.ordine ASC
');

$i = 0;

$codice = 0;

$impostacharset = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>';

$newpage = false;

while ($domanda = mysql_fetch_object($query)) {
	$i++;

	if ($newpage) {
		$html .= '<pagebreak />';

		$newpage = false;
	}

	$domanda->domanda = ForceUTF8\Encoding::toUTF8($domanda->domanda);

	if ($codice != $domanda->codice) {
		if ($i > 1) {
			$html .= '<pagebreak />';
		}

		$html .= '<html>
		<table width=196mm>
		<thead>
		<tr class="headerrow1">
		<td width=10% style=border-right-color:white;><img src="stemma-1.5.jpg" width="6%"></td>
		<td align=center width=90%; style=border-right-color:white;><strong>ESAME PER IL CONSEGUIMENTO DELL\'IDONEITÀ<br>D\'INSEGNANTE DI TEORIA DI AUTOSCUOLA<br>SESSIONE DEL '.$data_esame.'</strong><br></td>
		</tr>
		<tr>
		</tr>
		</thead>
		</table>
		<table width=196mm style=border-color:white;>
		<thead>
		<tr class="headerrow2">
		<td align=left width=100% style="border-color:white;padding-left:0"><strong>Candidato:</strong> ___________________________________________________</td>
		</tr>
		</thead>
		</table>';

		$codice = $domanda->codice;
	}
//<strong>'.$domanda->ordine.'</strong> - '.$domanda->domanda.'
	if ($i === 1) {
		$html .= '
			<table width=196mm>
			<tbody>
			<tr class="headerrow2">
			<td width=196mm style="text-align: center; border: 0"><strong>Prova Simulazione Teoria</strong><br><em>(art. 3 c. 2 lett. C del D.M. 17 26.01.2011)</em></td>
			</tr>
			<tr class="headerrow2">
			<td width=196mm style="text-align: left; border: 0; padding-left:32px">'.$domanda->ordine.') '.$domanda->domanda.'</td>
			</tr>
			<tr class="headerrow2">
			<td width=196m style="border: 0">
				<table width=196mm style="border: 0; padding: 0 8px">
					<tr>
						<td width=10mm height=8mm valign=bottom style="text-align: left; border: 0; padding: 0">Giudizio sintetico:</td>
						<td width=156mm height=8mm style="border: 0; border-bottom: 1px solid black"></td>
					</tr>
					<tr class="headerrow2">
						<td width=164mm height=8mm colspan=2 style="border: 0; border-bottom: 1px solid black"></td>
					</tr>
					<tr class="headerrow2">
						<td width=164mm height=8mm colspan=2 style="border: 0; border-bottom: 1px solid black"></td>
					</tr>
				</table>
			</td>
			</tr>
			<tr class="headerrow2">
			<td width=196mm style="border: 0; text-align: right">Valutazione complessiva simulazione di teoria ____ / 30</td>
			</tr>
			</tbody>
			</table><br>';
	} else {
		if ($i === 2) {
			$html .= '
			<table width=196mm>
			<tbody>
			<tr class="headerrow2">
			<td width=196mm style="text-align: center; border: 0"><strong>Prova Orale</strong><br><em>(art. 3 c. 2 lett. C del D.M. 17 26.01.2011)</em></td>
			</tr>';
		}
		$html .= '
		<tr class="headerrow2">
		<td width=196mm style="text-align: left; border: 0; padding-left:32px">'.($domanda->ordine-1).') '.$domanda->domanda.'</td>
		</tr>
		<tr class="headerrow2">
		<td width=196m style="border: 0">
			<table width=196mm style="border: 0; padding: 0 8px">
				<tr>
					<td width=10mm height=8mm valign=bottom style="text-align: left; border: 0; padding: 0">Giudizio sintetico:</td>
					<td width=156mm height=8mm style="border: 0; border-bottom: 1px solid black"></td>
				</tr>
				<tr class="headerrow2">
					<td width=164mm height=8mm colspan=2 style="border: 0; border-bottom: 1px solid black"></td>
				</tr>
				<tr class="headerrow2">
					<td width=164mm height=8mm colspan=2 style="border: 0; border-bottom: 1px solid black"></td>
				</tr>
			</table>
		</td>
		</tr>
		<tr class="headerrow2">
		<td width=196mm style="border: 0; text-align: right">Valutazione domanda orale n.'.($domanda->ordine-1).' ____ / 10</td>
		</tr>
		';
		if ($i === 4) {
			$html .= '
			<tr class="headerrow2">
			<td width=196mm style="border: 0; border-top: 1px solid grey; text-align: right">Valutazione finale prova orale ____ / 30</td>
			</tr>
			</tbody>
			</table><pagebreak /><p>
			<strong>FIRMA COMMISSIONE</strong><br><br>
			<strong>Il Presidente</strong><br><br>________________________________________<br><br>
			<strong>Esperto designato dalla Provincia di Brescia</strong><br><br>________________________________________<br><br>
			<strong>Rappresentante Associazione di categoria</strong><br><br>________________________________________<br><br>
			<strong>D.T.T. - Uffici Motorizzazione Civile di Brescia</strong><br><br>________________________________________
			</p>';

			$i = 0;

			$newpage = true;
		}
	}
}

include("mpdf.php");

$mpdf=new mPDF('','A4',0,0,7,7,10,10); 

$mpdf->SetDisplayMode('fullpage');

$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet

$stylesheet = file_get_contents('Motorizzazione_Esami_mpdfstyle.css');

$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($impostacharset);

$mpdf->WriteHTML($html,2);

$mpdf->Output('questionario_'.implode('_', array_reverse(explode('/', $data_esame))).'.pdf','D');

exit();

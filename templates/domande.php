<script type="text/javascript">
//<![CDATA[
var oTable = '';

$(document).ready(function () {

	$('#add').on('click', function () {
		$("#form_dialog").dialog('option', 'title', 'Inserimento domanda');

		$.post('templates/domande_form.php', function (resp) {
			$("#form_dialog").html(resp).dialog('open');
		}, 'html');
		return false;
	});

	$('#exportcsv').on('click', function () {
		location.href = 'templates/domande_action.php?action=export';
		return false;
	});

	$('#list')
		.on('click', 'span.edit:not(.disabled)', function () {
			$('#datatable').find('tr').removeClass('highlight');
			$('#record'+$(this).attr('rel')).addClass('highlight');

			$("#form_dialog").dialog('option', 'title', 'Modifica domanda');

			$.post('templates/domande_form.php', { id: $(this).attr('rel') }, function (resp) {
				$("#form_dialog").html(resp).dialog('open');
			}, 'html');
			return false;
		})
		.on('click', 'span.delete:not(.disabled)', function () {
			var id = $(this).attr('rel');

			$('#datatable').find('tr').removeClass('highlight');
			$('#record'+$(this).attr('rel')).addClass('highlight');

			confirmBox(
				"Vuoi veramente cancellare la domanda selezionata?",
				"warning",
				"templates/domande_action.php",
				{ action: 'delete', id: id },
				$('#record'+id),
				oTable,
				"L'domanda selezionato è stata eliminato."
			);
		})
		.on('click', 'span.enable:not(.disabled)', function () {
			var id = $(this).attr('rel');

			$('#datatable').find('tr').removeClass('highlight');
			$('#record'+$(this).attr('rel')).addClass('highlight');

			confirmBox(
				"Vuoi veramente abilitare la domanda selezionata?",
				"question",
				"templates/domande_action.php",
				{ action: 'enable', id: id },
				null,
				oTable,
				"La domanda selezionata è stata abilitata."
			);
		})
		.on('click', 'span.disable:not(.disabled)', function () {
			var id = $(this).attr('rel');

			$('#datatable').find('tr').removeClass('highlight');
			$('#record'+$(this).attr('rel')).addClass('highlight');

			confirmBox(
				"Vuoi veramente disabilitare la domanda selezionata?",
				"question",
				"templates/domande_action.php",
				{ action: 'disable', id: id },
				null,
				oTable,
				"La domanda selezionata è stata disabilitata."
			);
		});

});
//]]>
</script>
<!--[if lt IE 8]>
<script type="text/javascript" src="js/ie7.js"></script>
<![endif]-->
<div id="top"><?php require_once('menu.php') ?></div>
<div id="center-col">
	<button id="add">Aggiungi domanda</button>
	<button id="exportcsv">Esporta in CSV</button>
	<?php include('domande_table.php') ?>
</div>

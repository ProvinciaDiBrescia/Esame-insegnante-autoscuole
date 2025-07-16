<script type="text/javascript">
//<![CDATA[
var oTable = '';

$(document).ready(function () {

	$('#add').on('click', function () {
		$("#form_dialog").dialog('option', 'title', 'Inserimento argomento');

		$.post('templates/argomenti_form.php', function (resp) {
			$("#form_dialog").html(resp).dialog('open');
		}, 'html');
		return false;
	});

	$('#list')
		.on('click', 'span.edit:not(.disabled)', function () {
			$('#datatable').find('tr').removeClass('highlight');
			$('#record'+$(this).attr('rel')).addClass('highlight');

			$("#form_dialog").dialog('option', 'title', 'Modifica argomento');

			$.post('templates/argomenti_form.php', { id: $(this).attr('rel') }, function (resp) {
				$("#form_dialog").html(resp).dialog('open');
			}, 'html');
			return false;
		})
		.on('click', 'span.delete:not(.disabled)', function () {
			var id = $(this).attr('rel');

			$('#datatable').find('tr').removeClass('highlight');
			$('#record'+$(this).attr('rel')).addClass('highlight');

			confirmBox(
				"Vuoi veramente cancellare l'argomento selezionato?",
				"warning",
				"templates/argomenti_action.php",
				{ action: 'delete', id: id },
				$('#record'+id),
				oTable,
				"L'argomento selezionato è stata eliminato."
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
	<button id="add">Aggiungi argomento</button>
	<?php include('argomenti_table.php') ?>
</div>

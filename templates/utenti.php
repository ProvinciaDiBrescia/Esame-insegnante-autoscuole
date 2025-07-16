<?php if($_SESSION['userinfo']['profilo'] < 3) { require_once('templates/softwares.php'); } else { ?>
<script type="text/javascript">
//<![CDATA[
var oTable = '';

$(document).ready(function () {

	$('#add').on('click', function () {
		$("#form_dialog").dialog('option', 'title', 'Inserimento utente');

		$.post('templates/utenti_form.php', function (resp) {
			$("#form_dialog").html(resp).dialog('open');
		}, 'html');
		return false;
	});

	$('#list')
		.on('click', 'span.edit', function () {
			$('#datatable').find('tr').removeClass('highlight');
			$('#record'+$(this).attr('rel')).addClass('highlight');

			$("#form_dialog").dialog('option', 'title', 'Modifica utente');

			$.post('templates/utenti_form.php', { id: $(this).attr('rel') }, function (resp) {
				$("#form_dialog").html(resp).dialog('open');
			}, 'html');
			return false;
		})
		.on('click', 'span.delete', function () {
			var id = $(this).attr('rel');

			if (confirm('Vuoi veramente cancellare l\'utente selezionato?')) {
				$.post('templates/utenti_action.php', { action: 'delete', id: id }, function () {
					$('#record' + id).remove();
					oTable.fnDraw(false);
				});
			}
		});

});
//]]>
</script>
<!--[if lt IE 8]>
<script type="text/javascript" src="js/ie7.js"></script>
<![endif]-->
<div id="top"><?php require_once('menu.php') ?></div>
<div id="center-col">
	<button id="add">Aggiungi utente</button>
	<?php include('utenti_table.php') ?>
</div>
<?php } ?>
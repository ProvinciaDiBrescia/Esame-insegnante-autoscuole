<script type="text/javascript">
//<![CDATA[
var oTable = '';

$(document).ready(function () {

	$('#add').on('click', function () {
		$("#form_dialog").dialog('option', 'title', 'Inserimento <?php echo str_replace("_"," ",$_GET['section']) ?>');

		$.post('templates/tipologia_form.php', { tabella: '<?php echo $_GET['section'] ?>' }, function (resp) {
			$("#form_dialog").html(resp).dialog('open');
		}, 'html');
		return false;
	});

	$('#list')
		.on('click', 'span.edit:not(.disabled)', function () {
			$('#datatable').find('tr').removeClass('highlight');
			$('#record'+$(this).attr('rel')).addClass('highlight');

			$("#form_dialog").dialog('option', 'title', 'Modifica <?php echo str_replace("_"," ",$_GET['section']) ?>');

			$.post('templates/tipologia_form.php', { tabella: '<?php echo $_GET['section'] ?>', id: $(this).attr('rel') }, function (resp) {
				$("#form_dialog").html(resp).dialog('open');
			}, 'html');
			return false;
		})
		.on('click', 'span.delete:not(.disabled)', function () {
			var id = $(this).attr('rel');

			$('#datatable').find('tr').removeClass('highlight');
			$('#record'+$(this).attr('rel')).addClass('highlight');

			confirmBox(
				"Vuoi veramente cancellare la <?php echo str_replace("_"," ",$_GET['section']) ?> selezionata?",
				"warning",
				"templates/tipologia_action.php",
				{ action: 'delete', tabella: '<?php echo $_GET['section']; ?>', id: id },
				$('#record'+id),
				oTable,
				"La <?php echo str_replace("_"," ",$_GET['section']) ?> selezionata è stata eliminata."
			);
		})
		.on('click', 'span.enable:not(.disabled)', function () {
			var id = $(this).attr('rel');

			$('#datatable').find('tr').removeClass('highlight');
			$('#record'+$(this).attr('rel')).addClass('highlight');

			confirmBox(
				"Vuoi veramente abilitare la <?php echo str_replace("_"," ",$_GET['section']) ?> selezionata?",
				"question",
				"templates/tipologia_action.php",
				{ action: 'enable', tabella: '<?php echo $_GET['section']; ?>', id: id },
				null,
				oTable,
				"La <?php echo str_replace("_"," ",$_GET['section']) ?> selezionata è stata abilitata."
			);
		})
		.on('click', 'span.disable:not(.disabled)', function () {
			var id = $(this).attr('rel');

			$('#datatable').find('tr').removeClass('highlight');
			$('#record'+$(this).attr('rel')).addClass('highlight');

			confirmBox(
				"Vuoi veramente disabilitare la <?php echo str_replace("_"," ",$_GET['section']) ?> selezionata?",
				"question",
				"templates/tipologia_action.php",
				{ action: 'disable', tabella: '<?php echo $_GET['section']; ?>', id: id },
				null,
				oTable,
				"La <?php echo str_replace("_"," ",$_GET['section']) ?> selezionata è stata disabilitata."
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
	<button id="add">Aggiungi <?php echo str_replace("_"," ",$_GET['section']) ?></button>
	<?php include('tipologia_table.php') ?>
</div>

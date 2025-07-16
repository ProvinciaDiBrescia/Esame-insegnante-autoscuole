<script type="text/javascript">
//<![CDATA[
var oTable = '';

$(document).ready(function () {

	$('#create').on('click', function () {
		$("#form_dialog").dialog('option', 'title', 'Genera questionario');

		$.post('templates/questionari_form.php', function (resp) {
			$("#form_dialog").html(resp).dialog('open');
		}, 'html');
		return false;
	});

	$('#exportcsv').on('click', function () {
		location.href = 'templates/questionari_action.php?action=export';
		return false;
	});

	$('#list')
		.on('click', 'span.transfer:not(.disabled)', function () {
			$('#datatable').find('tr').removeClass('highlight');
			$('#record'+$(this).attr('rel')).addClass('highlight');

			Swal.fire({
				allowOutsideClick: false,
				allowEscapeKey: false,
				text: "Vuoi veramente stampare il questionario selezionato?",
				icon: "question",
				showCancelButton: true,
				confirmButtonText: 'Sì',
				cancelButtonText: 'No',
				customClass: {
					htmlContainer: 'nomargin'
				}
			}).then((result) => {
				if (result.isConfirmed) {
					location.href = "includes/mpdf/101-Quiz-Questionario.php?numero="+$(this).attr('rel');
				}
			});
		})
		.on('click', 'span.delete:not(.disabled)', function () {
			var id = $(this).attr('rel');

			$('#datatable').find('tr').removeClass('highlight');
			$('#record'+$(this).attr('rel')).addClass('highlight');

			confirmBox(
				"Vuoi veramente cancellare il questionario selezionato?",
				"warning",
				"templates/questionari_action.php",
				{ action: 'delete', id: id },
				$('#record'+id),
				oTable,
				"Il questionario selezionato è stata eliminato."
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
	<button id="create">Genera questionario</button>
	<?php include('questionari_table.php') ?>
</div>

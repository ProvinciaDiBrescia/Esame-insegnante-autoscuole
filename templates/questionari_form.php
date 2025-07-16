<?php
	include_once "../includes/config.php";
	include_once "../includes/functions.php";
	include_once "../includes/form.class.php";

	$action = (isset($_REQUEST['id'])) ? 'update' : 'insert';

	if ($action === 'insert') {
		$questionario = array(
			'data_esame'	=> NULL,
		);
	}
	if ($action === 'update') {
	}
?>
<script type="text/javascript">
//<![CDATA[
function dialogClose() {
	$("#form_dialog").dialog( "option", "buttons",
		{
			'Salva': function () {
				$('#form').submit();
				return false;
			},
			'Chiudi': function () {
				$(this).dialog('close');
			}
		}
	);
}

$(document).ready(function () {
	$("#form_dialog .calendar").datepicker($.datepicker.regional['it']);

	$("#form_dialog").dialog( "option", "buttons",
		{
			'Genera': function () {
				$('#form').submit();
				return false;
			},
			'Chiudi': function () {
				$(this).dialog('close');
			}
		}
	);

	$("#form").validate({
		ignore: [],
		errorElement: 'span',
		wrapper: '',
		meta: "validate",
		errorClass: "ui-state-error",
		rules: {
			data_esame: "required",
			schede: {
				required: true,
				number: true
			}
		},
		messages: {
			data_esame: "inserire una data",
			schede: {
				required: "inserire numero schede",
				number: "deve essere un numero"
			}
		},
		submitHandler: function () {
			$.post( "templates/questionari_action.php", $("#form").serialize(), function () {
				oTable.fnDraw(false);
				$("#form_dialog").dialog('close');
			});
			return false;
		}
	});
});
//]]>
</script>
<?php
	echo REQUIRED_MSG;

	$form = new Form;
	$form->open('', 'form');
	$form->hidden('action', $action);
	if ($action === 'update') {
		$form->hidden('id', $questionario['id']);
	}
?>
<ul>
	<li><?php $form->text('Data esame', 'data_esame', $questionario['data_esame'], 1, 'calendar') ?></li>
	<li><?php $form->text('Numero schede', 'schede', $questionario['schede'], 1) ?></li>
</ul>
<?php
	$form->close();
?>

<?php
	include_once "../includes/config.php";
	include_once "../includes/functions.php";
	include_once "../includes/form.class.php";

	$action = (isset($_REQUEST['id'])) ? 'update' : 'insert';

	if ($action === 'insert') {
		$tipologia = array(
			'descrizione'	=> NULL,
			'note'			=> NULL,
		);
	}
	if ($action === 'update') {
		$query = "SELECT * FROM tipo_" . $_REQUEST['tabella'] . " WHERE id=" . intval($_REQUEST['id']);
		$row = mysql_fetch_array(mysql_query($query));

		$tipologia = array(
			'id'			=> $row['id'],
			'descrizione'	=> $row['descrizione'],
			'note'			=> $row['note'],
		);
	}
?>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function () {
	$("#form").validate({
		errorElement: 'span',
		wrapper: '',
		meta: "validate",
		errorClass: "ui-state-error",
		rules: {
			descrizione: "required"
		},
		messages: {
			descrizione: "inserire una descrizione"
		},
		submitHandler: function () {
			$.post("templates/tipologia_action.php", $("#form").serialize(), function () {
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
	$form->hidden('tabella', $_REQUEST['tabella']);
	if ($action === 'update') {
		$form->hidden('id', $tipologia['id']);
	}
?>
<ul>
	<li><?php $form->text('Descrizione', 'descrizione', $tipologia['descrizione'], 1) ?></li>
	<li><?php $form->textarea('Note', 'note', $tipologia['note']) ?></li>
</ul>
<?php
	$form->close();
?>

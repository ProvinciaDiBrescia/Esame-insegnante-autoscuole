<?php
	include_once "../includes/config.php";
	include_once "../includes/functions.php";
	include_once "../includes/form.class.php";

	require_once('../includes/ForceUTF8/Encoding.php');

	$action = (isset($_REQUEST['id'])) ? 'update' : 'insert';

	if ($action === 'insert') {
		$argomento = array(
			'descrizione'	=> NULL,
			'categorie'		=> NULL,
		);
	}
	if ($action === 'update') {
		$query = "SELECT * FROM argomenti WHERE id=" . intval($_REQUEST['id']);
		$row = mysql_fetch_array(mysql_query($query));

		$argomento = array(
			'id'			=> $row['id'],
			'descrizione'	=> ForceUTF8\Encoding::toUTF8($row['descrizione']),
			'categorie'		=> unserialize($row['categorie']),
		);
	}
?>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function () {
	$("#form").validate({
		ignore: [],
		errorElement: 'span',
		wrapper: '',
		meta: "validate",
		errorClass: "ui-state-error",
		rules: {
			descrizione: "required",
			"categorie[]": "required"
		},
		messages: {
			descrizione: "inserire un argomento",
			"categorie[]": "selezionare una categoria"
		},
		submitHandler: function () {
			$.post("templates/argomenti_action.php", $("#form").serialize(), function () {
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
		$form->hidden('id', $argomento['id']);
	}
?>
<ul>
	<li><?php $form->textarea('Argomento', 'descrizione', $argomento['descrizione'], 1, '', '', array('style' => 'height: 100px')) ?></li>
	<li><?php
		$options = array();
		$result = mysql_query("SELECT * FROM tipo_categoria ORDER BY id");
		while ($row = mysql_fetch_array($result)) {
			$options[$row['id']] = $row['descrizione'];
		}
		$form->checkboxlist('Categorie', 'categorie', $argomento['categorie'], $options, 1, 'list');
	?></li>
</ul>
<?php
	$form->close();
?>

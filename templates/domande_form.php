<?php
	include_once "../includes/config.php";
	include_once "../includes/functions.php";
	include_once "../includes/form.class.php";

	require_once('../includes/ForceUTF8/Encoding.php');

	$action = (isset($_REQUEST['id'])) ? 'update' : 'insert';

	if ($action === 'insert') {
		$domanda = array(
			'id'				=> NULL,
			'id_argomento'		=> NULL,
			'domanda'			=> NULL,
		);
	}
	if ($action === 'update') {
		$query = "SELECT 
				d.id,
				d.id_argomento,
				d.domanda
			FROM domande AS d
			WHERE d.id=" . intval($_REQUEST['id']);
		$row = mysql_fetch_array(mysql_query($query));

		$domanda = array(
			'id'				=> $row['id'],
			'id_argomento'		=> $row['id_argomento'],
			'domanda'			=> ForceUTF8\Encoding::toUTF8($row['domanda']),
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
			id_argomento: "required",
			domanda: "required"
		},
		messages: {
			id_argomento: "",
			domanda: "inserire una domanda"
		},
		submitHandler: function () {
			$.post( "templates/domande_action.php", $("#form").serialize(), function () {
				oTable.fnDraw(false);
				$("#form_dialog").dialog('close');
			});
			return false;
		}
	});

	$('#id_argomento').on('selectmenuchange', function() {
		$(this).valid();
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
		$form->hidden('id', $domanda['id']);
	}
?>
<ul>
	<li><?php $form->textarea('Domanda', 'domanda', $domanda['domanda'], 1, '', '', array('style' => 'height: 100px')) ?></li>
	<li><?php
		$options = array();
		$result = mysql_query("SELECT * FROM argomenti WHERE categorie IS NOT NULL ORDER BY descrizione");
		while ($row = mysql_fetch_array($result)) {
			$options[] = array(
				'id'			=> $row['id'],
				'descrizione'	=> ForceUTF8\Encoding::toUTF8($row['descrizione']),
			);
		}
		$form->select('Argomento', 'Selezionare un argomento', 'id_argomento', $domanda['id_argomento'], $options, 1);
	?></li>
</ul>
<?php
	$form->close();
?>

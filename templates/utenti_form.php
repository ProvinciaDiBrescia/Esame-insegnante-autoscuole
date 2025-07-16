<?php
	include_once "../includes/config.php";
	include_once "../includes/functions.php";
	include_once "../includes/form.class.php";

	$action = (isset($_REQUEST['id'])) ? 'update' : 'insert';

	if ($action === 'insert') {
		$utente = array(
			'id_utente'	=> NULL,
			'utente'	=> NULL,
			'profilo'	=> NULL,
		);
	}
	if ($action === 'update') {
		$query = "
			SELECT
				CONCAT(u.cognome,' ',u.nome) AS utente,
				u.profilo
			FROM utenti AS u
			WHERE u.id=" . intval($_REQUEST['id'])
		;
		$row = mysql_fetch_array(mysql_query($query));

		$utente = array(
			'id_utente'	=> intval($_REQUEST['id']),
			'utente'	=> $row['utente'],
			'profilo'	=> $row['profilo'],
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
			utente: {
				depends: $("#id_utente"),
				remote: {
					param: {
						url: "templates/utenti_action.php?action=check",
						type: "post",
						data: {
							id: function () {
								return $("#id_utente").val();
							}
						}
					},
					depends: function () {
						return $("#action").val() !== "update";
					}
				}
			},
			profilo: "required"
		},
		messages: {
			utente: {
				depends: "selezionare un utente valido",
				remote: "utente gi&agrave; presente"
			},
			profilo: ''
		},
		submitHandler: function () {
			$.post("templates/utenti_action.php", $("#form").serialize(), function () {
				oTable.fnDraw(false);
				$("#form_dialog").dialog('close');
			});
			return false;
		}
	});

	$("#utente:not([readonly])")
		.autocomplete({
			source: "templates/utenti_action.php?action=autocomplete",
			minLength: 2,
			select: function ( event, ui ) {
				$(this).val(ui.item.value);
				$('#id_utente').val(ui.item.id);
				$('#utente').valid();
			}
		})
		.on("keydown", function (e) {
			if (e.keyCode != 9 && e.keyCode != 13) {
				$('#id_utente').val('');
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
?>
<ul>
	<li>
	<?php if ($action === 'insert') { ?>
		<?php $form->text('Utente', 'utente', $utente['utente'], 1) ?>
	<?php } else if ($action === 'update') { ?>
		<?php $form->static_text('Utente', 'utente', $utente['utente']) ?>
	<?php } ?>
		<?php $form->hidden('id_utente', $utente['id_utente']) ?>
	</li>
	<li><?php
		$options = array();
		$result = mysql_query("SELECT * FROM profili ORDER BY descrizione");
		while($row = mysql_fetch_array($result)) {
			$options[] = array(
				'id'			=> $row['id'],
				'descrizione'	=> $row['descrizione'],
			);
		}
		$form->select('Profilo', '- Selezionare un profilo -', 'profilo', $utente['profilo'], $options, 1);
	?></li>
</ul>
<?php
	$form->close();
?>

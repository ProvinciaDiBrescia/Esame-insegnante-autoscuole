<?php
	include_once 'includes/functions.php';

	$columns = array(
		'id'			=> 0,
		'Descrizione'	=> 40,
		'Note'			=> 60,
	);

	echo generate_list($columns);
?>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function () {

	oTable = $('#list').dataTable({
		"fnServerParams": function ( aoData ) {
			aoData.push( { "name": "sezione", "value": "tipologia" } );
			aoData.push( { "name": "tabella", "value": "<?php echo $_GET['section'] ?>" } );
		},
		"aoColumns": [
			{ "bVisible": 0 },
			{ "sClass": "center" },
			{ "sClass": "center" },
			{ "bSortable": false, "bSearchable": false, "sClass": "nowrap right" }
		],
		"aaSorting": [[ 1, "asc" ]]
	});

});
//]]>
</script>

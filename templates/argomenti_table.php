<?php
	include_once 'includes/functions.php';

	$columns = array(
		'id'					=> 0,
		'Argomento'				=> 40,
		'Categorie'				=> 50,
		'Domande disponibili'	=> 10,
	);

	echo generate_list($columns);
?>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function () {

	oTable = $('#list').dataTable({
		"fnServerParams": function ( aoData ) {
			aoData.push( { "name": "sezione", "value": "argomenti" } );
		},
		"aoColumns": [
			{ "bVisible": 0 },
			null,
			null,
			{ "sClass": "center" },
			{ "bSortable": false, "bSearchable": false, "sClass": "nowrap right" }
		],
		"aaSorting": [[ 1, "asc" ]]
	});

});
//]]>
</script>

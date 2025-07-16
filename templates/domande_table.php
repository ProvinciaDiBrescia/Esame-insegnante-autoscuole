<?php
	include_once "includes/functions.php";

	$columns = array(
		'id'				=> 0,
		'Domanda'			=> 70,
		'Argomento'			=> 30,
	);

	echo generate_list($columns);
?>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function () {

	oTable = $('#list').dataTable({
		"fnServerParams": function ( aoData ) {
			aoData.push( { "name": "sezione", "value": "domande" } );
		},
		"aoColumns": [
			{ "bVisible": 0 },
			null,
			null,
			{ "bSortable": false, "bSearchable": false, "sClass": "nowrap right" }
		],
		"aaSorting": [[ 1, "asc" ], [ 2, "asc" ]]
	});

});
//]]>
</script>

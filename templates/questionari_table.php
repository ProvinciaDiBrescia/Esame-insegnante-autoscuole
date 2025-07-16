<?php
	include_once "includes/functions.php";

	$columns = array(
		'id'				=> 0,
		'Data esame'		=> 50,
		'Numero schede'		=> 50,
	);

	echo generate_list($columns);
?>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function () {

	oTable = $('#list').dataTable({
		"fnServerParams": function ( aoData ) {
			aoData.push( { "name": "sezione", "value": "questionari" } );
		},
		"aoColumns": [
			{ "bVisible": 0 },
			{ "sClass": "center" },
			{ "sClass": "center" },
			{ "bSortable": false, "bSearchable": false, "sClass": "nowrap right" }
		],
		"aaSorting": [[ 0, "desc" ]]
	});

});
//]]>
</script>

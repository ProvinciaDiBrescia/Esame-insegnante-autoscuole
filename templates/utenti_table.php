<?php
	include_once "includes/functions.php";

	$columns = array(
		'id'		=> 0,
		'Utente'	=> 20,
		'Area'		=> 35,
		'Settore'	=> 35,
		'Profilo'	=> 10,
	);

	echo generate_list($columns);
?>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function () {

	oTable = $('#list').dataTable({
		"fnServerParams": function ( aoData ) {
			aoData.push( { "name": "sezione", "value": "utenti" } );
		},
		"aoColumns": [
			{ "bVisible": 0 },
			null,
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

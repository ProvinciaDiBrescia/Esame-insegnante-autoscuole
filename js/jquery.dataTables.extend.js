$(document).ready(function () {

	'use strict';

	$.extend(true, $.fn.dataTable.defaults, {
		"bAutoWidth" : false,
		"bInfo": true,
		"bLengthChange": true,
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "includes/datatables.php",
		"sPaginationType": "full_numbers",
		"oLanguage": {
			"sUrl": 'includes/it_IT.txt'
		},
		"fnDrawCallback": function () {
			/*$('#loader').hide();*/
			$('#loadpage').animate({opacity:0}, 600).hide("slow");
		}
	});

	$.fn.dataTable.defaults.aLengthMenu = [[10, 25, 50, 100, 9223372036854775807], [10, 25, 50, 100, 'Tutti']];

});
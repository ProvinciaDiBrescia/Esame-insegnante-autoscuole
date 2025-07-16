function ajaxFileUpload() {
	$.ajaxFileUpload ({
		url: 'includes/upload.php',
		secureuri: false,
		fileElementId: 'fileToUpload',
		data: { 'fileType': $('#fileType').val() },
		dataType: 'json',
		success: function (data) {
			if (typeof(data.error) !== 'undefined') {
				if (data.error !== '') {
					alert(data.error);
				} else {
					if($('#extra_dialog #form').html() != undefined) {
						$('#extra_dialog #form').append('<input type="hidden" rel="'+data.filename+'" name="files[]" value="'+data.name+'" \/>');
					} else {
						$('#form').append('<input type="hidden" rel="'+data.filename+'" name="files[]" value="'+data.name+'" \/>'); 
					}
					$('#flist').append('<li id="'+data.filename+'">'+data.name+'<span class="delete"><\/span><\/li>');
				}
			}
		},
		error: function (data, status, e) {
			alert(e);
		}
	});

	return false;
}

function getParameterByName(name) {
	name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
	var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		results = regex.exec(location.search);
	return results === null ? "pratiche" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function update_time() {
	var seconds = new Date().getSeconds();
	var minutes = new Date().getMinutes();
	var hours = new Date().getHours();

	$(".current-hours").html(( hours < 10 ? "0" : "" ) + hours);
	$(".current-min").html(( minutes < 10 ? "0" : "" ) + minutes);
	$(".current-sec").html(( seconds < 10 ? "0" : "" ) + seconds);
}

function confirmBox(text, icon, link, params, el, table, success_text) {
	Swal.fire({
		allowOutsideClick: false,
		allowEscapeKey: false,
		text: text,
		icon: icon,
		showCancelButton: true,
		confirmButtonText: 'Sì',
		cancelButtonText: 'No',
		customClass: {
			htmlContainer: 'nomargin'
		}
	}).then((result) => {
		if (result.isConfirmed) {
			$.post(link, params, function () {
				if (!!el) {
					el.remove();
				}

				if (!!table) {
					table.fnDraw(false);
				}

				Swal.fire(
					'Operazione eseguita!',
					success_text,
					'success'
				);
			});
		}
	});
}

$(document).ready(function () {
	update_time();

	setInterval(function () {
		update_time();
	}, 1000);

	setInterval(function () {
		if (!$('#formlogin').length) {
			$.ajax({
				url: "includes/session.php"
			})
			.done(function (data) {
				if (data === 'false') {
					location.href = 'index.php?logout=1';
				}
			});
		}
	}, 30000);

	$("#form_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 'auto',
		width: 'auto',
		modal: true,
		resizable: false,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},
		open: function () {
			counter = 0;

			$('body').css('overflow-y', 'hidden');

			$('#form_dialog select:not(".multiselect")').selectmenu({
				position: { my: "left top", at: "left bottom", collision: "flipfit" }
			});

			$('#form_dialog form :input:visible:enabled:first').select();

			if (typeof dialogOpen == 'function') {
				dialogOpen();
			}

			$('#form_dialog').dialog("option", "position", { my: "center", at: "center", of: window });
		},
		beforeClose: function () {
			$('body').css('overflow-y', '');
		},
		close: function () {
			if (typeof dialogClose == 'function') {
				dialogClose();
			}
			$(this).html('');
		},
		buttons: {
			'Salva': function () {
				$('#form_dialog #form').submit();
				return false;
			},
			'Chiudi': function () {
				$(this).dialog('close');
			}
		}
	});

	$("#extra_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 'auto',
		width: 'auto',
		modal: true,
		resizable: false,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},
		open: function () {
			counter = 0;

			$('#extra_dialog select:not(".multiselect, .custom")').selectmenu({
				position: { my: "left top", at: "left bottom", collision: "flipfit" }
			});

			$('#extra_dialog form :input:visible:enabled:first').select();

			$('#extra_dialog').dialog("option", "position", { my: "center", at: "center", of: window });
		},
		close: function () {
			if (typeof dialogClose == 'function') {
				dialogClose();
			}
			$(this).html('');
		},
		buttons: {
			'Salva': function () {
				$('#extra_dialog #form').submit();
				return false;
			},
			'Chiudi': function () {
				$(this).dialog('close');
			}
		}
	});

	$("#search_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 'auto',
		width: 'auto',
		modal: true,
		resizable: false,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},
		open: function () {
			$('.ui-dialog-buttonpane').find('button:contains("Ricerca")').button({
				icons: {
					primary: 'fa fa-search'
				}
			});

			$('[aria-describedby="search_dialog"]').css('position', 'fixed');

			$('#search_dialog').dialog("option", "position", { my: "center", at: "center", of: window });
		},
		beforeClose: function () {
			$('[aria-describedby="search_dialog"]').css('position', '');
		},
		close: function () {
		},
		buttons: {
			'Ricerca': function () {
				$.post('templates/search_action.php', {
					action: 'save',
					page: getParameterByName('page'),
					form: $("#searchform").serialize(),
				}, function (resp) {
					$('#remove').addClass('active');
					oTable.fnDraw();
				});
				$(this).dialog('close');
			},
			'Chiudi': function () {
				$(this).dialog('close');
			}
		}
	});

	$('body').on('keydown', '#form_dialog input', function (e) {
		if (e.keyCode == 13) {
			$('#form_dialog #form').submit();
			return false;
		}
	});

	$('#list, .ui-dialog-content').delegate('span.databtn', 'mouseover', function (event) {
		$(this).qtip({
			overwrite: false,
			content: {
				text: $(this).attr('title')
			},
			position: {
				my: "bottom center",
				at: "top center",
				viewport: $('#list'),
				adjust: {
					method: 'shift none'
				}
			},
			show: {
				event: event.type,
				ready: true
			},
			hide: {
				fixed: true
			},
			style: {
				classes: 'ui-tooltip-rounded ui-tooltip-blue ui-tooltip-shadow'
			}
		}, event);
	});

	$('#list').delegate('th:not(".sorting_disabled")', 'mouseover', function (event) {
		$(this).qtip({
			content: {
				text: 'Ordina per '+$(this).attr('abbr').replace(/[_]/g, " ")
			},
			position: {
				my: "bottom center",
				at: "top center",
				viewport: $('#list'),
				adjust: {
					method: 'shift none'
				}
			},
			show: {
				event: event.type,
				ready: true
			},
			style: {
				classes: 'ui-tooltip-rounded ui-tooltip-blue ui-tooltip-shadow'
			}
		}, event);
	});

	$('#form_dialog, #extra_dialog, #history_dialog').delegate('a, span.btn, div.download', 'mouseover', function (event) {
		$(this).qtip({
			overwrite: true,
			content: {
				text: $(this).attr('title')
			},
			position: {
				my: "bottom center",
				at: "top center",
				viewport: $('#form_dialog'),
				adjust: {
					method: 'shift none'
				}
			},
			show: {
				event: event.type,
				ready: true
			},
			hide: {
				fixed: true
			},
			style: {
				classes: 'ui-tooltip-rounded ui-tooltip-blue ui-tooltip-shadow'
			}
		}, event);
	});

	$('#search_dialog').delegate('span', 'mouseover', function (event) {
		$(this).qtip({
			overwrite: false,
			content: {
				text: $(this).attr('title')
			},
			position: {
				my: "bottom center",
				at: "top center",
				viewport: $('#search_dialog'),
				adjust: {
					method: 'shift none'
				}
			},
			show: {
				event: event.type,
				ready: true
			},
			hide: {
				fixed: true
			},
			style: {
				classes: 'ui-tooltip-rounded ui-tooltip-blue ui-tooltip-shadow'
			}
		}, event);
	});

	$('body').on('focus', '#form_dialog input, #extra_dialog input', function () {
		$(this).select();
	});

	$('body').on('keypress', '.money', function (event) {
		if (event.which == 46) {
			$(this).val($(this).val() + ',');
			return false;
		}
	});

	$('body').on('click', 'span.download', function () {
		$(this).qtip('destroy');
		$(location).attr('href', 'docs/' + $(this).attr('rel'));
	});

	$('body').on('click', 'div.download', function () {
		$(this).qtip('destroy');
		$(location).attr('href', $(this).data('href'));
	});

	$('#upload').button({
		icons: {
			primary: "fa fa-upload"
		}
	});

	$('#remove').button({
		icons: {
			primary: "fa fa-times"
		}
	});

	$('body').on('click', '#upload', function () {
		$('#fileToUpload').trigger('click');
	});

	if ($.browser.msie) {
		$('body').on('click', '#fileToUpload', function () {
			setTimeout(function () {
				if ($("#fileToUpload").val().length > 0) {
					return ajaxFileUpload();
				}
			}, 0);
		});
	} else {
		$('body').on('change', '#fileToUpload', function () {
			return ajaxFileUpload();
		});
	}

	$('#add').button({
		icons: {
			primary: "fa fa-plus"
		}
	});

	$('#search').button({
		icons: {
			primary: "fa fa-search"
		}
	});

	$('#create, #exportpdf').button({
		icons: {
			primary: "fa fa-file-text"
		}
	});

	$('#export, #exportcsv').button({
		icons: {
			primary: "fa fa-download"
		}
	});

	$('#highlight').button({
		icons: {
			primary: "ui-icon-alert"
		}
	}).addClass('ui-state-highlight');

	$('body').on('click', '#extra_dialog span.delete:not(.disabled)', function () {
		var file = $(this).parent().attr('id');
		var parent = $(this).parent();

		$.post('includes/upload.php', { action: 'delete', name: file }, function (resp) {
			if (resp == '1') {
				parent.remove();
				$('input[rel="' + file + '"]').remove();
			}
			return false;
		});
	});

	$('body').on('mouseover', 'span.submenu', function () {
		$('span.submenu').removeClass('active');
		$(this).addClass('active');
	});

	$('body').on('mouseover', '#menu .clear', function () {
		$('span.submenu').removeClass('active');
	});

	$('body').on('click', 'div.odd, div.even', function (event) {
		if ($(':checkbox', this).length > 0) {
			if (event.target.type !== 'checkbox') {
				$(':checkbox', this).trigger('click');
				$(':checkbox', this).valid();
			}
		}
	});

	$('.scrollbox input:checked').each(function () {
		if ($.browser.msie) { $(this).next().addClass("checked"); }
	});

	$('body').on('click', '.scrollbox input', function () {
		if ($.browser.msie) {
			var c = $(this).is(':checked');
			if (c) {
				$(this).next().removeClass("checked");
			} else {
				$(this).next().addClass("checked");
			}
		}
	});

	$('body').on('change', 'select.params', function () {
		var element = $(this);
		$.post('templates/search_action.php', { rel: element.attr('rel'), type: $("option:selected", this).attr('rel'), extra: $("option:selected", this).attr('class') }, function (resp) {
			element.next().html('');
			element.next().html(resp);
		});
		return false;
	});

	$('body').on('click', '#addsearch', function () {
		var rel = parseInt($('#searchmenu > li:last > select').attr('rel')) + 1;
		var clone = $('#searchmenu').find('li:first').clone(true);
		clone.find('.extra').html('');
		clone.appendTo($('#searchmenu'));
		$('#searchmenu > li:last > select').val(0).attr('name', 'search_param-' + rel + '-table').attr('rel', rel);
		$("#search_dialog").dialog("option", "position", { my: "center", at: "center", of: window });
		return false;
	});

	$('body').on('click', '#delsearch', function () {
		$.post('templates/search.php', { page: getParameterByName('page') }, function (resp) {
			$("#search_dialog").html('')
			$("#search_dialog").html(resp);
			$("#search_dialog").dialog("option", "position", { my: "center", at: "center", of: window });
			$.post('templates/search_action.php', {
				action: 'delete',
				page: getParameterByName('page'),
			}, function (resp) {
				oTable.fnDraw();
				$('#remove').removeClass('active');
			});
		}, 'html');
		return false;
	});

	$('body').on('click', '#remove', function () {
		$.post('templates/search.php', { page: getParameterByName('page') }, function (resp) {
			$("#search_dialog").html('')
			$("#search_dialog").html(resp);
			$.post('templates/search_action.php', {
				action: 'delete',
				page: getParameterByName('page'),
			}, function (resp) {
				oTable.fnDraw();
				$('#remove').removeClass('active');
			});
		}, 'html');
		return false;
	});

	$('body').on('click', '#sendsearch', function () {
		oTable.fnDraw();
		$('#remove').addClass('active');
		return false;
	});

	$('body').on('click', '#search_dialog span.delparam', function () {
		$(this).parent().parent().remove();
		$("#search_dialog").dialog("option", "position", { my: "center", at: "center", of: window });
		return false;
	});

	$('body').on('click', '#search', function () {
		if ($("#search_dialog").html() === '') {
			$.post('templates/search.php', { page: getParameterByName('page') }, function (resp) {
				$("#search_dialog").html(resp).dialog('open');
			}, 'html');
		} else {
			$("#search_dialog").dialog('open');
		}
		return false;
	});

	$('.scrollbox input').click(function () {
		if ($.browser.msie) {
			var c = $(this).is(':checked');
			if(c) {
				$(this).next().removeClass("checked");
			} else {
				$(this).next().addClass("checked");
			}
		}
	});

	$('body').on('click', '.calendar + span', function () {
		$(this).prev('input').val('');
	});

	$('body').on('focus', '[readonly="readonly"]', function () {
		$(this).blur();
	});
});

$.validator.addMethod('depends', function (value, el, param) {
	return param.val() !== '';
});

$.validator.addMethod('dependsIfNotNull', function (value, el, param) {
	if (value.length > 0) {
		return param.val().length !== 0;
	} else {
		return true;
	}
});

$.validator.addMethod('minStrict', function (value, el, param) {
	value = value.replace(/\./g, '');
	value = value.replace(',', '.');

	return (value >= param && $.isNumeric(value));
});

Number.prototype.formatMoney = function (c, d, t) {
	var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

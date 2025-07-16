<?php

define ('REQUIRED_MSG', '<p id="validateTips">I campi con<span></span>sono obbligatori.</p>');

function convert_date_it($date) {
	return implode('/', array_reverse(explode('-', $date)));
}

function convert_date_mysql($date) {
	return implode('-', array_reverse(explode('/', $date)));
}

function removefile($file, $ext) {
	$path = realpath('..') . DIRECTORY_SEPARATOR . "docs" . DIRECTORY_SEPARATOR;
	$filename = md5($file).'.'.$ext;

	if (file_exists($path . $filename)) {
		unlink($path . $filename);
	}
}

function ae_detect_ie() { 
	if (isset($_SERVER['HTTP_USER_AGENT']) && 
	(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
		return true;
	else
		return false;
}

function scrollbox($fieldname, $fieldval, $txt, $array = array(), $mini = false) {
	$content = ($mini === true) ? '<div class="scrollbox mini">' : '<div class="scrollbox">';
	$class = 'odd';
	if (count($array) > 0) {
		foreach ($array AS $key => $val) { 
			if (!empty($key)) {
				$class = ($class == 'even' ? 'odd' : 'even');
				$content .= '<div class="'.$class.'">';
				$checked = (in_array($key, $fieldval)) ? 'checked="checked"' : '';
				$content .= '<input type="checkbox" name="'.$fieldname.'[]" value="'.$key.'"'.$checked.' />';
				$content .= '<span>'.$val.'</span></div>';
			}
		}
	} else {
		$content .= '<div class="'.$class.' none">'.$txt.'</div>';
	}
	return $content .= '</div>';
}

function generate_db_fields($fields) {
	foreach($fields AS $key => $value) {
		foreach($value AS $v) {
			if (is_array($v)) {
				$k = key($v);

				switch($key) {
					case 'boolean':		$return .= $k . ' = ' . ((!isset($_REQUEST[$v[$k]]) OR empty($_REQUEST[$v[$k]])) ? 0 : 1) . ','; break;
					case 'integer':		$return .= $k . ' = ' . ((!empty($_REQUEST[$v[$k]])) ? intval($_REQUEST[$v[$k]]) : 'NULL') . ','; break;
					case 'money':		$return .= $k . ' = ' . ((!empty($_REQUEST[$v[$k]])) ? "'" . convert_money_mysql($_REQUEST[$v[$k]]) . "'" : 'NULL') . ','; break;
					case 'string':		$return .= $k . ' = ' . ((!empty($_REQUEST[$v[$k]])) ? "'" . addslashes(trim($_REQUEST[$v[$k]])) . "'" : 'NULL') . ','; break;
					case 'date':		$return .= $k . ' = ' . ((!empty($_REQUEST[$v[$k]])) ? "'" . convert_date_mysql($_REQUEST[$v[$k]]) . "'" : 'NULL') . ','; break;
					case 'serialize':	$return .= $k . ' = ' . ((!empty($_REQUEST[$v[$k]])) ? "'" . serialize($_REQUEST[$v[$k]]) . "'" : 'NULL') . ','; break;
				}
			} else {
				switch($key) {
					case 'boolean':		$return .= $v . ' = ' . ((!isset($_REQUEST[$v]) OR empty($_REQUEST[$v])) ? 0 : 1) . ','; break;
					case 'integer':		$return .= $v . ' = ' . ((!empty($_REQUEST[$v])) ? intval($_REQUEST[$v]) : 'NULL') . ','; break;
					case 'money':		$return .= $v . ' = ' . ((!empty($_REQUEST[$v])) ? "'" . convert_money_mysql($_REQUEST[$v]) . "'" : 'NULL') . ','; break;
					case 'string':		$return .= $v . ' = ' . ((!empty($_REQUEST[$v])) ? "'" . addslashes(trim($_REQUEST[$v])) . "'" : 'NULL') . ','; break;
					case 'date':		$return .= $v . ' = ' . ((!empty($_REQUEST[$v])) ? "'" . convert_date_mysql($_REQUEST[$v]) . "'" : 'NULL') . ','; break;
					case 'serialize':	$return .= $v . ' = ' . ((!empty($_REQUEST[$v])) ? "'" . serialize($_REQUEST[$v]) . "'" : 'NULL') . ','; break;
				}
			}
		}
	}

	return substr($return, 0, -1);
}

function generate_list($columns) {
	$return = '
		<div id="datatable">
			<table id="list">
				<thead>
					<tr>
	';
	foreach($columns AS $column => $width) {
		$return .= '<th abbr="' . strtolower(str_replace(" ","_",$column)) . '" style="width:' . $width . '%">' . $column . '</th>';
	}
	$return .= '
						<th class="action"> </th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="' . (count($columns) + 1) . '" class="dataTables_empty">Caricamento dati in corso...</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="loader"></div>
	';

	return $return;
}
function formatBytes($size, $precision = 2) {
	$base = log($size) / log(1024);
	$suffixes = array('', 'kB', 'MB', 'GB', 'TB');

	return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}

function fileIcon($ext) {
	if ($ext == "txt" || $ext == "html" || $ext == "xml") {
		$img = "txt-icon.png";
	} else if ($ext == "rar") {
		$img = "rar-icon.png";
	} else if ($ext == "zip") {
		$img = "zip-icon.png";
	} else if ($ext == "jpg") {
		$img = "jpg-icon.png";
	} else if ($ext == "png") {
		$img = "png-icon.png";
	} else if ($ext == "doc" || $ext == "docx") {
		$img = "word-doc-icon.png";
	} else if ($ext == "xls" || $ext == "xlsx") {
		$img = "excel-xls-icon.png";
	} else if ($ext == "ppt") {
		$img = "ppt-icon.png";
	} else if ($ext == "pdf") {
		$img = "pdf-icon.png";
	} else if ($ext == "p7m") {
		$img = "p7m-icon.png";
	} else {
		$img = "file.gif";
	}

	return $img;
}
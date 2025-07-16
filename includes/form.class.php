<?php

class Form {
	public function open($action, $name, $method = 'POST') {
		echo '<form action="' . $action . '" name="' . $name . '" id="' . $name . '" method="' . $method . '"><fieldset>';
	}
	public function close() {
		echo '</fieldset></form>';
	}
	public function hidden($name, $value) {
		echo '<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />';
	}
	public function static_text($label, $name, $value, $class = '', $info = '') {
		echo '
			<label for="' . $name . '">' . $label . '</label>
			<span' . ((!empty($class)) ? ' class="' . $class . '"' : '') . '>' . htmlspecialchars($value) . '</span>
		';
	}
	public function text($label, $name, $value, $required = 0, $class = '', $info = '', $extra = array()) {
		$extratag = '';
		foreach ($extra as $k => $v) {
			$extratag .= ' ' . $k . '="' . $v . '"';
		}

		echo '
			<label for="' . $name . '">' . $label . ((!empty($required)) ? '<span></span>' : '') . ((!empty($info)) ? ' ' . $info : '') .'</label>
			<input type="text" name="' . $name . '" id="' . $name . '" value="' . htmlspecialchars($value) . '" class="text ui-widget-content ui-corner-all' . ((!empty($class)) ? ' ' . $class : '') . '"' . $extratag . ' />
		';
	}
	public function textarea($label, $name, $value, $required = 0, $class = '', $info = '', $extra = array()) {
		$extratag = '';
		foreach ($extra as $k => $v) {
			$extratag .= ' ' . $k . '="' . $v . '"';
		}

		echo '
			<label for="' . $name . '">' . $label . ((!empty($required)) ? '<span></span>' : '') . ((!empty($info)) ? ' ' . $info : '') .'</label>
			<textarea name="' . $name . '" id="' . $name . '" class="text ui-widget-content ui-corner-all' . ((!empty($class)) ? ' ' . $class : '') . '"' . $extratag . '>' . $value . '</textarea>
		';
	}
	public function select($label, $empty, $name, $value, $options, $required = 0, $multiple = 0, $class = '', $info = '') {
		echo '
			<label for="' . $name . '">' . $label . ((!empty($required)) ? '<span></span>' : '') . ((!empty($info)) ? ' ' . $info : '') .'</label>
			<select name="' . $name . ((!empty($multiple)) ? '[]' : '') . '" id="' . $name . '" class="text ui-widget-content ui-corner-all' . ((!empty($class)) ? ' ' . $class : '') . '"' . ((!empty($multiple)) ? ' multiple="multiple"' : '') . '>
		';
		if (!empty($empty)) {
			echo '<option value="">' . $empty . '</option>';
		}
		if (count($options) > 0) {
			foreach ($options AS $option) {
				if (is_array($value)) {
					$selected = (in_array($option['id'], $value));
				} else {
					$selected = ($value == $option['id']);
				}
				echo '<option value="'. $option['id'] .'"' . ($selected ? ' selected="selected"' : '') . '>' . $option['descrizione'] . '</option>';
			}
		}
		echo '</select>';
	}
	public function checkbox($label, $name, $value, $required = 0, $class = '', $info = '') {
		echo '
			<label for="' . $name . '">' . $label . ((!empty($required)) ? '<span></span>' : '') . ((!empty($info)) ? ' ' . $info : '') .'</label>
			<input type="checkbox" name="' . $name . '" id="' . $name . '"' . ((!empty($value)) ? ' checked="checked"' : '') . '>
		';
	}
	public function checkboxlist($label, $name, $value, $options, $required = 0, $class = '', $info = '') {
		echo '
			<label for="' . $name . '">' . $label . ((!empty($required)) ? '<span></span>' : '') . ((!empty($info)) ? ' ' . $info : '') .'</label>
		';
		echo '<div class="checkboxlist">';
		foreach ($options AS $val => $text) {
			echo '<label class="inside '.$class.'"><input type="checkbox" name="' . $name . '[]" id="' . $name . '_' . $val . '" value="' . $val . '" ' . (in_array($val, $value) ? ' checked="checked"' : '') . '>' . $text . '</label>';
		}
		echo '</div>';
	}
	public function static_checkbox($label, $name, $value) {
		echo '
			<label for="' . $name . '">' . $label . '</label>
			<img src="img/' . ((!empty($value)) ? 'yes' : 'cross') . '.png" alt="' . $name . '" />
		';
	}
	public function radio($label, $name, $value, $options, $required = 0, $class = '', $info = '') {
		echo '
			<label for="' . $name . '">' . $label . ((!empty($required)) ? '<span></span>' : '') . ((!empty($info)) ? ' ' . $info : '') .'</label>
		';
		foreach($options AS $val => $text) {
			echo '<label class="inside"><input type="radio" name="' . $name . '" value="' . $val . '"' . (($val == $value) ? ' checked="checked"' : '') . '>' . $text . '</label>';
		}
	}
	public function fileupload($label, $name, $value, $required = 0, $class = '') {
		echo $this->text($label, $name, $value[0], $required, $class);
		if (empty($value[1])) {
			echo '<a title="Aggiungi ' . strtolower($label) . '" class="add_attachment"></a>';
		} else {
			echo '<a title="Elimina ' . strtolower($label) . '" class="del_attachment"></a>';
		}
		echo $this->hidden('id_' . $name, $value[1]);
	}
	public function static_list($label, $name, $values, $class = '', $info = '') {
		echo '<label for="' . $name . '">' . $label . '</label>';
		echo '<ul>';
		foreach ($values AS $value) {
			echo '<li>' . $value . '</li>';
		}
		echo '</ul>';
	}
}

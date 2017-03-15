<?php
class Form {

	protected $html = '';
	protected $module_html = '';

	protected $prefix = '';
	protected $form_id = 'form';
	protected $button_id = 'button';
	protected $input_id = 'input';
	protected $class_name = 'input_class';

	function __construct() {
		$this->form_id = $this->prefix.$this->form_id ;
		$this->button_id = $this->prefix.$this->button_id ;
		$this->input_id = $this->prefix.$this->input_id ;
		$this->class_name = $this->prefix.$this->class_name ;
	}

	public function rendered_form($action) {
		$this->html  = '<fieldset>';
		$this->html .= '<legend>'.$this->title.':</legend>';
		$this->html .= '<form id="'.$this->form_id.'" action="'.$action.'" method="post">';
		$this->html .= '<table>'.$this->module_html ;
		// $this->html .= '<tr><td style="text-align: center"><input id="'.$this->button_id.'" type="button" value="'.$this->button_name.'"></td></tr></table>';
		$this->html .= '<tr><td style="text-align: center"><button type="button" id="'.$this->button_id.'">'.$this->button_name.'</button></td></tr></table>';
		$this->html .= '</form>';
		$this->html .= '</fieldset>';
		return $this->html;
	}

	public function text_input($name) {
		$this->module_html .= '<tr>';
		$this->module_html .= '<td style="text-align: center; height:10px">';
		$this->module_html .= '<input id="'.$this->input_id.'_'.$name.'" class="'.$this->class_name.'" type="text" name="'.$name.'" value="'.String::ucfirst_rmunderscores($name).'"></td></tr>';
	}

	public function double_text_input($requested_fields) {
		$w = '90%';
		$chunked_arrays = array_chunk($requested_fields, 2);
		foreach ($chunked_arrays as $value) {
			$this->module_html .= '<tr>';
			$this->module_html .= '<td style="text-align: left; width:50%">';
			$this->module_html .= '<input id="'.$this->input_id.'_'.$value["0"].'" style="width:'.$w.'" class="'.$this->class_name.'" type="text" name="'.$value["0"].'" value="'.String::ucfirst_rmunderscores($value["0"]).'"> </td>';
			if (isset($value["1"])) {
				$this->module_html .= '<td style="text-align: right">';
				$this->module_html .= '<input id="'.$this->input_id.'_'.$value["1"].'" style="width:'.$w.'" class="'.$this->class_name.'" type="text" name="'.$value["1"].'" value="'.String::ucfirst_rmunderscores($value["1"]).'"> </td>';
			}
			$this->module_html .= '</tr>';
		}
	}
}

class Block extends Form {
	protected $button_name = 'Blocca';
	protected $title = 'Blocca una Pratica';
	protected $prefix = 'block_';
}

class Search extends Form {
	protected $button_name = 'Cerca';
	protected $title = 'Filtri di Ricerca';
	protected $prefix = 'search_';
}

?>

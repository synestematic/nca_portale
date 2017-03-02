<?php
class Search {

	private $html = '';
	private $module_html = '';

	public function render_form($action) {
		$this->html  = '<fieldset>';
		$this->html .= '<legend>Ricerca:</legend>';
		$this->html .= '<form id="search_form" action="'.$action.'" method="post">';
		$this->html .= $this->module_html ;
		$this->html .= '<table><tr><td style="text-align: center; height:10px"><input id="search_button" type="button" name="cerca" value="Cerca"></td></tr></table>';
		$this->html .= '</form>';
		$this->html .= '</fieldset>';
		return $this->html;
	}

	public function text_input($name, $value) {
		$this->module_html .= '<table><tr>';
		$this->module_html .= '<td style="text-align: center; height:10px">';
		$this->module_html .= String::ucfirst_rmunderscores($name).':<input id="'.$name.'" type="text" name="'.$name.'" value="'.$value.'"> </td></tr>';
		$this->module_html .= '<tr><td>';
		$this->module_html .= '</td></tr>';
		$this->module_html .= '</table>';
	}

}


?>

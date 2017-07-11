<?php
class CellStats {

	private $path = '../private/csvs/' ;
	private $file_name;
	private $file_handle;
	private $file_contents;

	public $first_name;
	public $last_name;
	public $number;
	public $calls = array() ;
	public $calls_off = array() ;

	function __construct($csv_filename) {
		$this->file_name = $csv_filename;
		$this->first_name = str_replace( '.csv', '', explode('_', $this->file_name)[1]);
		$this->last_name = explode('_', $this->file_name)[0];

		$this->file_handle = fopen($this->path . $this->file_name, "r");
		$this->file_contents = fread($this->file_handle,filesize($this->path . $this->file_name));

		$this->set_number();
		$this->set_calls();

		fclose($this->file_handle);
    }

	private function set_number() {
		if ( preg_match("/^Dettaglio spese relative al Numero di telefono: [0-9]{10}/", $this->file_contents , $match_array) ) {
		    foreach ($match_array as $match) {
		        $this->number = explode(' ', $match)[7];
		    }
		}
	}

	private function set_calls() {
		// Chiamate voce e video,Milano,390289415***,05/06/2017 11:30:41,00:00:18,0.00000,MinInNaz,Traffico originato,,,Italy - I Omnitel,-,,A
		if ( preg_match_all("/[0-9]+\*\*\*,[0-9]{2}\/[0-9]{2}\/[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2}/", $this->file_contents , $match_array) ) {
			foreach ($match_array as $matches) {
				foreach ($matches as $match) {
					$this->calls[] = $match;
					$this->set_times($match) ;
				}
		    }
		}
	}

	private function set_times($call) {
		$call_datetime = explode(',', $call)[1] ;
		$date = explode(' ', $call_datetime)[0] ;
		$time = explode(' ', $call_datetime)[1] ;
		$ora_inizio = 09 ;
		$ora_fine = 18 ;
		if ($this->last_name === 'Ruffoni' ||
			$this->last_name === 'Saibene' ||
			$this->last_name === 'Alimena' ||
			($this->first_name === 'Niccolo' && $this->last_name === 'Cavallaro' )) {
			$ora_inizio = 10 ;
			$ora_fine = 19 ;
		}
		if ((date('H', strtotime($time))) >= $ora_fine || (date('H', strtotime($time))) < $ora_inizio) {
			// $called_number = explode(',', $call)[0] ;
			// $this->calls_off[] = $called_number;
			// if (!in_array ( $called_number , $this->calls_off )){
			// 	$this->calls_off[] = $called_number;
			// }
			$this->calls_off[] = $call;
		}
	}

}
?>

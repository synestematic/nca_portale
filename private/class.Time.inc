<?php
class Time {

	public static function inverse_date($date) {
			$date_sections = explode("-", $date);
			$inversed_date = $date_sections[2]."/".$date_sections[1]."/".$date_sections[0];
			return $inversed_date;
	}

	public static function secs_to_his($secs) {
			$H = floor($secs / 3600) ;
		  $i = ($secs / 60) % 60 ;
		  $s = $secs % 60 ;
			$output = ($H != 0) ? sprintf("%02d:%02d:%02d", $H, $i, $s) : sprintf("%02d:%02d", $i, $s) ;
			echo $output;
			// return $output;
	}

	public static function check_for_date($value) {
			$year = substr($value, '0', '4');
			$month = substr($value, '5', '2');
			$day = substr($value, '8', '2');
			if (checkdate((int)$month, (int)$day, (int)$year)) {
					$value = $day . '/' . $month . '/' . $year ;
			}
			return $value;
	}

}
?>

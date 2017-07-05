<?php
class Time {

	public $sec;
	public $min;
	public $hour;
	public $day;
	public $month;
	public $year;

	public static function inverse_date($date) {
			$inversed_date = date("d/m/Y", strtotime($date));
			return $inversed_date;
	}

	public static function secs_to_his($secs) {
			$H = floor($secs / 3600) ;
		  $i = ($secs / 60) % 60 ;
		  $s = $secs % 60 ;
			$output = ($H != 0) ? sprintf("%02d:%02d:%02d", $H, $i, $s) : sprintf("%02d:%02d", $i, $s) ;
			echo $output;
	}

	public static function check_for_date($value) {
			// 0123456789
			// 2017-11-31
			$year = substr($value, '0', '4');
			$month = substr($value, '5', '2');
			$day = substr($value, '8', '2');
			$space = substr($value, '10', '1');
			$d1 = substr($value, '4', '1');
			$d2 = substr($value, '7', '1');

			if ( preg_match("/[0-9]{4}([-\/][0-9]{2}){2} /", $value ) && checkdate((int)$month, (int)$day, (int)$year) ) {
				if (preg_match("/[0-9]{4}([-\/][0-9]{2}){2} 00:00:00/", $value))  {
					$value = $day . '/' . $month . '/' . $year ;
				} else {
					$value = $day . '/' . $month . '/' . $year . substr($value,10,6);
				}
			}
			return $value;
	}

	public static function check_for_sunday($weekday_no) {
		if ($weekday_no === '7') {
			return true;
		} else {
			return false;
		}
	}

	public static function if_sunday_go_to($url) {
		$its_sunday = self::check_for_sunday(strftime("%u")); // 7 is sunday
		if ($its_sunday) {
		  $_SESSION["message"] = "La piattaforma non è disponibile di domenica.";
		  redirect($url);
		}
	}

}
?>

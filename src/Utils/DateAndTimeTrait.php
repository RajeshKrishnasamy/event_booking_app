<?php

namespace App\Utils;

trait DateAndTimeTrait {

	public static function durationDisplay(\DateInterval $interval) {
		$result = "";
		if ($interval->h) { $result .= $interval->format("%h hours "); }
		if ($interval->i) { $result .= $interval->format("%i minutes "); }
		return $result;
	}

	public static function durationCalculater(\DateTime $startTimeObj, \DateTime $endTimeObj){
		$diff = $startTimeObj->diff($endTimeObj);
		return DateAndTimeTrait::durationDisplay($diff);
    }

}

?>
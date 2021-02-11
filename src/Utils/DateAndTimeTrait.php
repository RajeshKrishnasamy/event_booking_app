<?php

namespace App\Utils;

/**
 * Trait - Date and time related Calculation
 */
trait DateAndTimeTrait {

	/**
	 * returns duration
	 */
	public static function durationDisplay(\DateInterval $interval) {
		$result = "";
		if ($interval->h) { $result .= $interval->format("%h hours "); }
		if ($interval->i) { $result .= $interval->format("%i minutes "); }
		return $result;
	}

	/**
	 * Clculates and returns duration based on Start and stop
	 */
	public static function durationCalculater(\DateTime $startTimeObj, \DateTime $endTimeObj){
		$diff = $startTimeObj->diff($endTimeObj);
		return DateAndTimeTrait::durationDisplay($diff);
    }

}

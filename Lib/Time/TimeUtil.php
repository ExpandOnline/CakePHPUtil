<?php

namespace CakePHPUtil\Lib\Time;

/**
 * Class TimeHelper
 * Put time manipulation methods here.
 */

class TimeUtil {

	public function hoursToDecimal($hours) {
		$hours = trim($hours);
		if (is_null($hours)) {
			return '';
		}
		$sign = 1;
		if (substr($hours,0, 1) === '-') {
			$sign = -1;
			$hours = substr($hours, 1);
		}
		list($hours, $minutes) = explode(':', $hours);
		return $sign * ($hours + ($minutes / 60));
	}

}
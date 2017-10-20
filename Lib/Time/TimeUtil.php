<?php

namespace CakePHPUtil\Lib\Time;

/**
 * Class TimeHelper
 * Put time manipulation methods here.
 */
class TimeUtil {

	/**
	 * @param $hours string in the form [-]hh[:mm[:ss]]
	 *
	 * @return string
	 */
	public function hoursToDecimal($hours) {
		$hours = trim($hours);
		if (is_null($hours)) {
			return '';
		}
		$sign = 1;
		if (substr($hours, 0, 1) === '-') {
			$sign = -1;
			$hours = substr($hours, 1);
		}
		$parts = explode(':', $hours);
		foreach ([
			'hours',
			'minutes',
			'seconds'
		] as $index => $name) {
			${$name} = $parts[$index] ?? 0;
		}

		return $sign * ($hours + ($minutes / 60) + (($seconds ?? 0) / 3600));
	}

}
<?php

namespace CakePHPUtil\Lib\Date;

/**
 * Class DateHelper
 */
class DateHelper {

	/**
	 * @param integer $secondsInput
	 *
	 * @return string
	 */
	public static function secondsToTime($secondsInput) {
		$zero = new \DateTime("@0");
		$offset = new \DateTime("@$secondsInput");
		$diff = $zero->diff($offset);
		$time = sprintf('%02d:%02d:%02d', $diff->days * 24 + $diff->h, $diff->i, $diff->s);

		return $secondsInput < 0 ? '-' . $time : '' . $time;
	}
}

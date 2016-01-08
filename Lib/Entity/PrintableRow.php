<?php
/**
 * Created by PhpStorm.
 * User: switteveen
 * Date: 7-1-2016
 * Time: 11:34
 */

interface PrintableRow {

	/**
	 * Indicates whether the row to be printed is of lower priority.
	 * A printer would typically print such a row greyed out or italic.
	 *
	 * @return boolean
	 */
	public function isLowPriority ();
}
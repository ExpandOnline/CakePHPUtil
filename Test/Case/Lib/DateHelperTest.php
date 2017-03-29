<?php

use Lib\Date\DateHelper;

class DateHelperTest extends CakeTestCase
{
	/**
	 * @param string  $expected
	 * @param string  $date
	 *
	 * @dataProvider provideTestTimes
	 */
	public function testDateWithSeconds($expected, $date)
	{
		$this->assertSame($expected, DateHelper::secondsToTime($date));
	}

	/**
	 * @return array
	 */
	public function provideTestTimes()
	{
		return array(
			array('00:10:00', 600),
			array('00:00:01', 1),
			array('00:01:00', 60),
			array('01:00:00', 3600),
			array('00:00:00', 0),
			array('-01:00:00', -3600),
			array('-00:01:00', -60),
			array('00:01:00', 60),
			array('-00:15:00', -900),
			array('00:15:00', 900),
			array('00:30:00', 1800),
			array('05:00:00', 18000),
			array('25:00:00', 25 * 3600),
			array('30:00:00', 30 * 3600),
			array('100:00:00', 100 * 3600),
			array('-100:00:00', -360000),
			array('100:00:00', 360000),
			array('450:00:00', 3600 * 450),
			array('01:06:40', 4000),
			array('-01:06:40', -4000),
		);
	}
}

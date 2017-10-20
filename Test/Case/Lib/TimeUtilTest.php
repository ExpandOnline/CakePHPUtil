<?php

use CakePHPUtil\Lib\Time\TimeUtil;

class TimeUtilTest extends CakeTestCase {

	/**
	 * @var TimeUtil
	 */
	public $timeUtil;

	public function setUp() {
		parent::setUp();
		$this->timeUtil = new TimeUtil();
	}


	public function testHoursToDecimal() {
		$cases = [
			[
				'hours' => '10:15',
				'decimals' => 2,
				'expected' => '10.25',
			],
			[
				'hours' => '10:20',
				'decimals' => 3,
				'expected' => '10.333',
			],
			[
				'hours' => '-10:15',
				'expected' => '-10.25',
				'decimals' => 2,
			],
			[
				'hours' => '-10:00',
				'expected' => '-10.00',
				'decimals' => 2
			],
			[
				'hours' => '-0:30',
				'expected' => '-0.5',
				'decimals' => 1
			],
			[
				'hours' => '-0:30:30',
				'expected' => '-0.5083',
				'decimals' => 4
			],
		];
		foreach ($cases as $case) {
			$this->assertEquals(
				$case['expected'],
				number_format($this->timeUtil->hoursToDecimal($case['hours']), $case['decimals'])
			);
		}
	}
}
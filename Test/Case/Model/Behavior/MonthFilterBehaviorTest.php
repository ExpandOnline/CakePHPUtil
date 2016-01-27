<?php
App::uses('MonthFilterBehavior', 'CakePHPUtil.Model/Behavior');

class MonthFilterBehaviorTest extends CakeTestCase{

	/**
	 * @var MonthFilterBehavior
	 */
	public $MonthFilter;

	public function setUp() {
		parent::setUp();
		$this->MonthFilter = new MonthFilterBehavior();
	}


	public function testSetDefaultFilterMonths() {
		$myModel = new Model();
		$this->MonthFilter->setDefaultFilterMonths($myModel);
		$this->assertEquals($myModel->filterArgs['year_start']['defaultValue'], date('Y'));
		$this->assertEquals($myModel->filterArgs['month_start']['defaultValue'], 1);
		$this->assertEquals($myModel->filterArgs['year_end']['defaultValue'], date('Y'));
		$this->assertEquals($myModel->filterArgs['month_end']['defaultValue'], 12);

		$this->MonthFilter->setDefaultFilterMonths($myModel, new DateTime('2012-03-01'), new DateTime('2013-04-01'));
		$this->assertEquals($myModel->filterArgs['year_start']['defaultValue'], 2012);
		$this->assertEquals($myModel->filterArgs['month_start']['defaultValue'], 3);
		$this->assertEquals($myModel->filterArgs['year_end']['defaultValue'], 2013);
		$this->assertEquals($myModel->filterArgs['month_end']['defaultValue'], 4);
	}

	public function testGetDateStart() {
		$cases = [
			[
				'args' => [
					'year_start' => '2015',
					'month_start' => '11',
					'test' => 'xyz'
				],
				'expect' => '2015-11-01'
			],
			[
				'args' => [
					'year_start' => '2015',
					'month_start' => '11',
					'test' => 'xyz'
				],
				'expect' => '2015-11-01'
			],
			[
				'args' => [
					'year_start' => '2015',
					'test' => 'xyz'
				],
				'expect' => null
			],
			[
				'args' => [
					'year_start' => null,
					'month_start' => '11',
					'test' => 'xyz'
				],
				'expect' => null
			]
		];

		foreach ($cases as $case) {
			$month = $this->MonthFilter->getDateStart($case['args']);
			if (!is_null($month)) $month = $month->format('Y-m-d');
			$this->assertEquals($month, $case['expect']);
		}

	}

	public function testGetDateEnd() {
		$cases = [
			[
				'args' => [
					'year_end' => '2015',
					'month_end' => '11',
					'test' => 'xyz'
				],
				'expect' => '2015-11-01'
			],
			[
				'args' => [
					'year_end' => '2015',
					'month_end' => '11',
					'test' => 'xyz'
				],
				'expect' => '2015-11-01'
			],
			[
				'args' => [
					'year_end' => '2015',
					'test' => 'xyz'
				],
				'expect' => null
			],
			[
				'args' => [
					'year_end' => null,
					'month_end' => '11',
					'test' => 'xyz'
				],
				'expect' => null
			]
		];

		foreach ($cases as $case) {
			$month = $this->MonthFilter->getDateEnd($case['args']);
			if (!is_null($month)) $month = $month->format('Y-m-d');
			$this->assertEquals($month, $case['expect']);
		}
	}

}
<?php
use CakePHPUtil\Lib\Iterator\CsvFileIterator;

/**
 * Class CsvFileIterator
 */
class CsvFileIteratorTest extends CakeTestCase {

	/**
	 *
	 */
	public function testIterator() {
		$cases = [
			[
				'file' => 'test.csv',
				'delimiter' => ','
			],
			[
				'file' => 'test.tsv',
				'delimiter' => "\t"
			]
		];
		$expected = [
			[
				'my' => 1,
				'csv' => 2,
				'file' => 3,
			],
			[
				'my' => 4,
				'csv' => 5,
				'file' => 6,
			],
			[
				'my' => 7,
				'csv' => 8,
				'file' => 9,
			]
		];
		foreach ($cases as $case) {
			$iterator = new CsvFileIterator(__DIR__ . '/' . $case['file'], $case['delimiter']);
			foreach ($iterator as $index => $row) {
				$this->assertEquals($expected[$index - 1], $row);
			}
		}
	}

}
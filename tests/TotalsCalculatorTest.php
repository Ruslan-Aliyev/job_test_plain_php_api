<?php

use BearClaw\Warehousing\TotalsCalculator;
use PHPUnit\Framework\TestCase;

class TotalsCalculatorTest extends TestCase
{
	public function testGenerateReport()
	{
		$calculator = new TotalsCalculator;
		$response   = $calculator->generateReport([2344, 2345, 2346]);
		$result     = array_column($response['result'], null, 'product_type_id');
		$expected1  = 41.5;
		$expected2  = 13.8;
		$expected3  = 25.0;

		$this->assertEquals($expected1, $result[1]['total']);
		$this->assertEquals($expected2, $result[2]['total']);
		$this->assertEquals($expected3, $result[3]['total']);
	}
}

<?php

namespace BearClaw\Warehousing;

use BearClaw\Warehousing\Services\PurchaseOrderService;

class TotalsCalculator
{
	public function generateReport(array $ids)
	{
		$service = new PurchaseOrderService;
		$tally   = $service->calculateTotals($ids);
		$result  = ['result' => []];

		foreach($tally as $key => $value)
		{
			$result['result'][] = [
				'product_type_id' => $key,
				'total'           => (float) $value,
			];
		}

		return $result;
	}
}

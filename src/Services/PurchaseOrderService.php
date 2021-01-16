<?php

namespace BearClaw\Warehousing\Services;

use Api\Core\Config;

class PurchaseOrderService
{
	private $calcBy = [
		'1' => 'weight',
		'2' => 'volume',
		'3' => 'weight',
	];

	public function calculateTotals($ids)
	{
		$tally = [];

		foreach ($ids as $id)
		{
			$response = json_decode($this->callApi($id));

			foreach ($response->data->PurchaseOrderProduct as $orderedProduct) 
			{
				$typeId       = (int) $orderedProduct->product_type_id;
				$initQuantity = (float) $orderedProduct->unit_quantity_initial;
				$product      = $orderedProduct->Product;

				if (isset($tally[$typeId]))
				{
					$tally[$typeId] += $initQuantity * (float) $product->{$this->calcBy[$typeId]};
				}
				else
				{
					$tally[$typeId] = $initQuantity * (float) $product->{$this->calcBy[$typeId]};
				}
			}
		}

		return $tally;
	}

	private function callApi($orderId)
	{
		$settings = Config::$settings['api'];
		$username = $settings['username'];
		$password = $settings['password'];
		$url      = $settings['domain'] . $settings['path'] . $orderId . $settings['params'];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}
}

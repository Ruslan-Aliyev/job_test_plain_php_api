<?php

namespace Api\Core\Traits;

use Api\Core\Config;

trait Auth 
{
	public function checkBasicAuth()
	{
		header('Cache-Control: no-cache, must-revalidate, max-age=0');

		$has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));

		$is_not_authenticated = (
			!$has_supplied_credentials ||
			$_SERVER['PHP_AUTH_USER'] != Config::$settings['test']['username'] ||
			$_SERVER['PHP_AUTH_PW']   != Config::$settings['test']['password']
		);

		if ($is_not_authenticated) 
		{
			header('HTTP/1.1 401 Authorization Required');
			header('WWW-Authenticate: Basic realm="Access denied"');
	        header("Content-Type: application/json; charset=UTF-8");

	        echo json_encode(["info" => "Unauthorized", "result" => []]);
			exit;
		}
	}
}
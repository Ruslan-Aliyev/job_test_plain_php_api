<?php 

namespace Api\Controllers;

use Api\Core\Controller;
use Api\Core\Traits\Auth;
use BearClaw\Warehousing\TotalsCalculator;

class Home extends Controller 
{
	use Auth;

    public function __construct() 
    {
        parent::__construct();
    }

    public function index($args) 
    {
        $calculator = new TotalsCalculator;
        $data       = $calculator->generateReport($args["purchase_order_ids"]);

        parent::respond($data);
    }
}

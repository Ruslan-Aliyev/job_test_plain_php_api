<?php 

namespace Api\Core;

class Controller 
{
    public function __construct() 
    {

    }

    protected function respond($data)
    {
        header("Content-Type: application/json; charset=UTF-8");

        echo json_encode($data);
    }
}

?>

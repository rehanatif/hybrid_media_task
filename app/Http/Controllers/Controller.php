<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    private $call_back;

    protected function setResponse($status,$message,$data = [])
    {
        $this->call_back['status']  = $status;

        $this->call_back['message'] = $message;

        $this->call_back['data'] = $data;
    }

    protected function getResponse()
    {
        return $this->call_back;
    }
}

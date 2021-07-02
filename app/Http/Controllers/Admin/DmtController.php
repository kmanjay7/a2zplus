<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\A2zSuvidhaa;
use App\Http\Controllers\Controller;

class DmtController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function getIp()
    {
        $response = A2zSuvidhaa::getResponse('v3/get-ip', []);

        return $response;
    }

    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function bankList()
    {
        $response = A2zSuvidhaa::getResponse('v3/get-bank-list', []);

        return $response;
    }
}

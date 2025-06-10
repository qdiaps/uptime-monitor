<?php

namespace App\Http\Controllers;

use App\Kafka\Producers\MonitorCreateProducer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class MonitorCreateController extends Controller
{
    public function create(MonitorCreateProducer $producer): RedirectResponse
    {
        $producer->produce();
        return Redirect::to('/');
    }
}

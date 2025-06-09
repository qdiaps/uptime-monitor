<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Kafka\Producers\FooProducer;

class FooController extends Controller
{
    public function index(FooProducer $producer)
    {
        $producer->foo();
    }
}

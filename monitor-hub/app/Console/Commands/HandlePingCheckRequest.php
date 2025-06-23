<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Kafka\Producers\PingCheckRequestProducer;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandlePingCheckRequest extends Command implements ShouldQueue
{
    protected $signature = 'monitor:handle-ping-check-request {data : Ping check request data}';

    protected $description = 'Handles incoming ping check requests for network monitoring by forwarding them to the request processor';

    public function handle(PingCheckRequestProducer $producer): void
    {
        $data = $this->argument('data');
        $producer->request($data);
    }
}

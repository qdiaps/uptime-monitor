<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Kafka\Producers\PingCheckReplyProducer;
use Illuminate\Console\Command;
use JJG\Ping;

class PingCheck extends Command
{
    protected $signature = 'ping:check {data : Ping check data}  {correlation-id : Correlation ID}';

    protected $description = 'Ping check';

    public function handle(PingCheckReplyProducer $producer): void
    {
        $data = $this->argument('data')['data'];
        $correlationId = $this->argument('correlation-id');

        $ping = (new Ping($data['host']))->ping();
        $result = [
            'success' => true,
            'ms' => $ping,
        ];
        if ($ping === false)
            $result = ['success' => false];

        $producer->produce($result, $correlationId);
    }
}

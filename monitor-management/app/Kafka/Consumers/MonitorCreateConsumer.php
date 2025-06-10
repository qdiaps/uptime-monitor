<?php declare(strict_types=1);

namespace App\Kafka\Consumers;

use Illuminate\Support\Facades\Artisan;
use Junges\Kafka\Contracts\Consumer;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Contracts\MessageConsumer;

class MonitorCreateConsumer extends Consumer
{
    public function handle(ConsumerMessage $message, MessageConsumer $consumer): void
    {
        Artisan::queue('monitor:handle-ping-check-request', [
            'data' => $message->getBody(),
        ]);
    }
}

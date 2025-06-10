<?php declare(strict_types=1);

namespace App\Kafka\Consumers;

use Illuminate\Support\Facades\Artisan;
use Junges\Kafka\Contracts\Consumer;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Contracts\MessageConsumer;

class PingCheckRequestConsumer extends Consumer
{

    public function handle(ConsumerMessage $message, MessageConsumer $consumer): void
    {
        Artisan::queue('ping:check', [
            'data' => $message->getBody(),
            'correlation-id' => $message->getHeaders()['correlation_id'],
        ]);
    }
}

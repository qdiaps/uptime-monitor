<?php declare(strict_types=1);

namespace App\Kafka\Consumers;

use Illuminate\Support\Facades\Log;
use Junges\Kafka\Contracts\Consumer;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Contracts\MessageConsumer;

class FooConsumer extends Consumer
{
    public function handle(ConsumerMessage $message, MessageConsumer $consumer): void
    {
        Log::debug($message->getBody());
    }
}

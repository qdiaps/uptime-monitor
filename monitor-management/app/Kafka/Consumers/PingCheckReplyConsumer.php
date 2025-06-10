<?php declare(strict_types=1);

namespace App\Kafka\Consumers;

use Illuminate\Support\Facades\Cache;
use Junges\Kafka\Contracts\Consumer;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Contracts\MessageConsumer;

class PingCheckReplyConsumer extends Consumer
{
    public function handle(ConsumerMessage $message, MessageConsumer $consumer): void
    {
        $headers = $message->getHeaders();
        $message = $message->getBody();

        $correlationId = $headers['correlation_id'];
        Cache::put("kafka_reply_$correlationId", $message, now()->addMinutes(5));
    }
}

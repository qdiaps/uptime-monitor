<?php declare(strict_types=1);

namespace App\Kafka\Producers;

use Illuminate\Support\Facades\Log;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class FooProducer
{
    public function foo(): void
    {
        $message = new Message(
            headers: ['header_key' => 'header_value'],
            body: ['body_key' => 'body_value'],
            key: 'key',
        );
        try {
            Kafka::publish('kafka')
                ->onTopic('foo')
                ->withMessage($message)
                ->send();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}

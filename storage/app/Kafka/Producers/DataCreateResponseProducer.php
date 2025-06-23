<?php

namespace App\Kafka\Producers;

use Exception;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class DataCreateResponseProducer
{
    public function produce(array $result, string $correlation_id): void
    {
        $message = new Message(
            headers: [
                'correlation_id' => $correlation_id,
            ],
            body: $result,
        );
        try {
            Kafka::asyncPublish('kafka')
                ->onTopic('storage.data.create.response')
                ->withMessage($message)
                ->send();
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}

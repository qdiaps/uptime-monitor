<?php declare(strict_types=1);

namespace App\Kafka\Producers;

use Illuminate\Support\Facades\Log;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class BaseResponseProducer
{
    protected string $topic = '';

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
                ->onTopic($this->topic)
                ->withMessage($message)
                ->send();
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}

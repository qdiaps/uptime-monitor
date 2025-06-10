<?php declare(strict_types=1);

namespace App\Kafka\Producers;

use Exception;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class PingCheckReplyProducer
{
    public function produce(array $result, string $correlationId): void
    {
        $message = new Message(
            headers: [
                'correlation_id' => $correlationId,
            ],
            body: [
                'result' => $result,
            ],
        );
        try {
            Kafka::asyncPublish('kafka')
                ->onTopic('checker.ping.check.reply')
                ->withMessage($message)
                ->send();
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}

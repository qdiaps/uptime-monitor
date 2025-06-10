<?php declare(strict_types=1);

namespace App\Kafka\Producers;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class PingCheckRequestProducer
{
    public function request(array $data): void
    {
        $correlationId = Str::uuid()->toString();
        $message = new Message(
            headers: [
                'correlation_id' => $correlationId,
            ],
            body: [
                'data' => $data,
            ],
        );
        try {
            Kafka::asyncPublish('kafka')
                ->onTopic('checker.ping.check.request')
                ->withMessage($message)
                ->send();
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        $response = $this->waitForReply($correlationId);
        if ($response === null)
            Log::error('PingCheckRequestProducer::request response fail');
        else
            Log::info('PingCheckRequestProducer::request response: ' . json_encode($response));
    }

    private function waitForReply(string $correlationId): mixed
    {
        $startTime = time();

        while (time() - $startTime < 30) {
            if ($response = Cache::get("kafka_reply_$correlationId")) {
                Cache::forget("kafka_reply_$correlationId");
                return $response;
            }
            usleep(250);
        }

        return null;
    }
}

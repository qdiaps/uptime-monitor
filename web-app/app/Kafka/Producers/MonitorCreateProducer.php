<?php declare(strict_types=1);

namespace App\Kafka\Producers;

use Exception;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class MonitorCreateProducer
{
    public function produce(): void
    {
        $message = new Message(
            body: [
                'host' => 'google.com',
                'count_replies' => 3,
            ],
        );
        try {
            Kafka::asyncPublish('kafka')
                ->onTopic('monitor_management.monitor.create')
                ->withMessage($message)
                ->send();
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}

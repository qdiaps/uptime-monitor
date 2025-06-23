<?php declare(strict_types=1);

namespace App\Kafka\Consumers;

use App\Kafka\Producers\DataReadResponseProducer;
use Junges\Kafka\Contracts\Consumer;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Contracts\MessageConsumer;

class DataReadRequestConsumer extends Consumer
{
    public function handle(ConsumerMessage $message, MessageConsumer $consumer): void
    {
        $msg = $message->getBody();

        $model = 'App\\Models\\' . $msg['table'];
        $result = null;
        if ($msg['type'] === 'id') {
            $result = $model::find($msg['id']);
        } else if ($msg['type'] === 'where') {
            $result = $model::where($msg['where'])->get();
        }

        $correlation_id = $message->getHeaders()['correlation_id'];
        (new DataReadResponseProducer())->produce([
            'success' => true,
            'result' => $result,
        ], $correlation_id);
    }
}

<?php declare(strict_types = 1);

namespace App\Kafka\Consumers;

use App\Kafka\Producers\DataCreateResponseProducer;
use Junges\Kafka\Contracts\Consumer;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Contracts\MessageConsumer;

class DataCreateRequestConsumer extends Consumer
{
    public function handle(ConsumerMessage $message, MessageConsumer $consumer): void
    {
        $msg = $message->getBody();
        $correlation_id = $message->getHeaders()['correlation_id'];

        $request = 'App\\Http\\Requests\\' . $msg['table'] . 'Request';
        $validated = validator($msg['data'], (new $request())->rules())->validate();

        $model = 'App\\Models\\' . $msg['table'];
        $result = $model::create($validated);

        (new DataCreateResponseProducer())->produce([
            'success' => true,
            'result' => $result,
        ], $correlation_id);
    }
}

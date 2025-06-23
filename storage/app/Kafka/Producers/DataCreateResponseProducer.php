<?php declare(strict_types=1);

namespace App\Kafka\Producers;

class DataCreateResponseProducer extends BaseResponseProducer
{
    protected string $topic = 'storage.data.create.response';
}

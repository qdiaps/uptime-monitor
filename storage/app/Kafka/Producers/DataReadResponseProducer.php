<?php declare(strict_types=1);

namespace App\Kafka\Producers;

class DataReadResponseProducer extends BaseResponseProducer
{
    protected string $topic = 'storage.data.read.response';
}

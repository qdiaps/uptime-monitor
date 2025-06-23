<?php

namespace App\Kafka\Producers;

class DataReadResponseProducer extends BaseResponseProducer
{
    protected string $topic = 'storage.data.read.response';
}

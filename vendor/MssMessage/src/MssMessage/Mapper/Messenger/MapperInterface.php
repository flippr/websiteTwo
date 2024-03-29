<?php

namespace MssMessage\Mapper\Messenger;

interface MapperInterface
{
    public function commit();

    public function abort();

    public function saveBatch(array $messages);

    public function queueBatch(array $messages);

    public function findRecipientByAddress($address);

    public function createRecipient($address);
}
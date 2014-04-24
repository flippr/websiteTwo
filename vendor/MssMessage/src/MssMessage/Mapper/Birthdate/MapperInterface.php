<?php

namespace MssMessage\Mapper\Birthdate;

use MssMessage\Message;

interface MapperInterface
{
    public function saveMessageBirthdate(Message $message);
}
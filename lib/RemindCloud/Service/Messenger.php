<?php

namespace RemindCloud\Service;

use MssMessage\Mapper\Messenger\DoctrineDbal as DoctrineDbalMapper;
use MssMessage\Service\Messenger as MssMessenger;
use Spiffy\Doctrine\Container as DoctrineContainer;

class Messenger
{
    /**
     * @param Spiffy\Doctrine\Container $doctrine
     */
    public static function get(DoctrineContainer $doctrine)
    {
        $em = $doctrine->getEntityManager();
        $conn = $em->getConnection();

        $mapper = new DoctrineDbalMapper($conn);
        $service = new MssMessenger($mapper);

        return $service;
    }
}
<?php

namespace RemindCloud\Service;

use MssMessage\Mapper\Mergeword\DoctrineDbal as DoctrineDbalMapper;
use MssMessage\Service\Mergeword as MssMergeword;
use Spiffy\Doctrine\Container as DoctrineContainer;

class Mergeword
{
    /**
     * @param Spiffy\Doctrine\Container $doctrine
     */
    public static function get(DoctrineContainer $doctrine)
    {
        $em = $doctrine->getEntityManager();
        $conn = $em->getConnection();

        $mapper = new DoctrineDbalMapper($conn);
        $service = new MssMergeword($mapper);

        return $service;
    }
}
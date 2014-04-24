<?php

namespace RemindCloud\Entity;

use Doctrine\ORM\Mapping as ORM;
use RemindCloud\Entity;

/**
 * @Entity
 * @Table(name="pims_query_result_type")
 */
class PimsQueryResultType
{
    const CLIENT = 0;
    const PATIENT = 1;

    /**
     * @Id
     * @Column(type="integer")
     */
    protected $id;

    /**
     * @Column(type="string")
     */
    protected $name;
}

<?php

namespace RemindCloud\Entity;

use Doctrine\ORM\Mapping as ORM;
use RemindCloud\Entity;

/**
 * @Entity
 * @Table(name="pims_query_result_code")
 */
class PimsQueryResultCode
{
    const NOT_RUN = 0;
    const RUN_OK = 1;
    const NO_SUCH_QUERY = 2;
    const RESULT_NO_SUPPORT = 3;
    const INVALID_QUERY = 4;
    const PIMS_QUERY_FAIL = 5;
    const OTHER = 6;

    /**
     * @Id
     * @Column(type="integer")
     */
    protected $id;

    /**
     * @Column(type="string")
     */
    protected $description;
}

<?php

namespace RemindCloud\Entity;

use Doctrine\ORM\Mapping as ORM;
use RemindCloud\Entity;

/**
 * @Entity
 * @Table(name="pims_query_result_data")
 */
class PimsQueryResultData
{
    /**
     * @Id
     * @Column(type="integer")
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="RemindCloud\Entity\PimsQueryQuerySchedule")
     * @JoinColumn(name="pimsQueryScheduleId")
     */
    protected $querySchedule;
}

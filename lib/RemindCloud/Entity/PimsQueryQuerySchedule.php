<?php

namespace RemindCloud\Entity;

use Doctrine\ORM\Mapping as ORM;
use RemindCloud\Entity;

/**
 * @Entity
 * @Table(name="pims_query_schedule")
 */
class PimsQueryQuerySchedule
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;


    /**
     * @Column(type="integer")
     */
    protected $queryId;

    /**
     * @Column(type="integer")
     */
    protected $resultTypeId;

    /**
     * @Column(type="datetime")
     */
    protected $startAt;

    /**
     * @Column(type="datetime")
     */
    protected $startedAt;

    /**
     * @Column(type="datetime")
     */
    protected $finishedAt;

    /**
     * @ManyToOne(targetEntity="RemindCloud\Entity\PimsQueryQuery")
     * @JoinColumn(name="queryId",referencedColumnName="id")
     */
    protected $query;

    /**
     * @ManyToOne(targetEntity="RemindCloud\Entity\PimsQueryResultType")
     * @JoinColumn(name="resultTypeId")
     */
    protected $resultType;

    /**
     * @ManyToOne(targetEntity="RemindCloud\Entity\PimsQueryResultCode")
     * @JoinColumn(name="resultCodeId")
     */
    protected $resultCode;

    /**
     * @OneToMany(targetEntity="RemindCloud\Entity\PimsQueryResultData", mappedBy="querySchedule", indexBy="id")
     */
    protected $resultData;

    public function getFinishedName()
    {
        if ($this->finishedAt)
        {
            return $this->finishedAt->format('m-d-Y h:i') . ': ' . $this->query->name;
        }
        return '';
    }
}
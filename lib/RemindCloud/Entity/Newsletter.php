<?php
namespace RemindCloud\Entity;

use Doctrine\ORM\Mapping as ORM;
use RemindCloud\Entity;

/**
 * @Entity(repositoryClass="RemindCloud\Repository\Newsletter")
 * @Table(name="newsletter")
 */
class Newsletter
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @Column(type="smallint") */
    protected $companyId;

    /** @Column(type="string",length=100) */
    protected $subject;

    /** @Column(type="text") */
    protected $body;

    /** @Column(type="date") */
    protected $sendDate;

    /** @Column(type="boolean") */
    protected $sent;

    /** @Column(type="boolean") */
    protected $published;

    /**
     * @ManyToOne(targetEntity="RemindCloud\Entity\PimsQueryQuerySchedule")
     * @JoinColumn(name="pimsQueryScheduleId")
     */
    protected $querySchedule;

    /**
     * @ManyToOne(targetEntity="RemindCloud\Entity\Company")
     * @JoinColumn(name="companyId")
     */
    protected $company;

    /**
     * @ManyToOne(targetEntity="RemindCloud\Entity\NewsletterList")
     * @JoinColumn(name="listId")
     */
    protected $list;

    public function init()
    {
        $this->sent = false;
        $this->published = false;
    }
}
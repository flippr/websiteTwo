<?php

namespace RemindCloud\Entity;

use Doctrine\ORM\Mapping as ORM;
use RemindCloud\Entity;

/**
 * @Entity
 * @Table(name="newsletter_list")
 */
class NewsletterList
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(type="string")
     */
    protected $name;

    /**
     * @Column(type="string")
     */
    protected $description;

    /**
     * @ManyToOne(targetEntity="RemindCloud\Entity\Company")
     * @JoinColumn(name="companyId")
     */
    protected $company;

    /**
     * @ManyToMany(targetEntity="RemindCloud\Entity\MessageRecipient")
     * @JoinTable(name="newsletter_list_recipient",
     *      joinColumns={@JoinColumn(name="listId", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="recipientId", referencedColumnName="id")}
     * )
     */
    protected $recipients;

    public function __construct()
    {
        $this->recipients = new \Doctrine\Common\Collections\ArrayCollection;
    }

    public function __toString()
    {
        return $this->name;
    }
}

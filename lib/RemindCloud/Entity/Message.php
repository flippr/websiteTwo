<?php
namespace RemindCloud\Entity;

use Doctrine\ORM\Mapping as ORM;
use RemindCloud\Entity;

/**
 * @Entity(repositoryClass="RemindCloud\Repository\Message")
 * @Table(name="message")
 */
class Message
{
    const CONTACT_TYPE_EMAIL = 1;
    const CONTACT_TYPE_SMS = 2;
    const CONTACT_TYPE_PHONE = 3;

    const MESSAGE_TYPE_GENERIC = 1;
    const MESSAGE_TYPE_NEWSLETTER = 2;
    const MESSAGE_TYPE_ADMIN_ACCOUNT_CREATED = 3;

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     ****/
    protected $id;

    /**
     * @Column(type="string",length=100, nullable=false)
     ****/
    protected $subject;

    /**
     * @Column(type="string",length=100, nullable=false)
     ****/
    protected $sender;

    /**
     * @Column(type="string",length=50, nullable=false)
     ****/
    protected $senderName;

    /**
     * @Column(type="text", nullable=false)
     ****/
    protected $body;

    /**
     * @Column(type="datetime",nullable=true)
     ****/
    protected $queuedAt;

    /**
     * @Column(type="datetime",nullable=true)
     ****/
    protected $sentAt;

    /**
     * @Column(type="text", nullable=true)
     ****/
    protected $result;

    /**
     * @Column(type="integer")
     ****/
    protected $priority;

    /**
     * @Column(columnDefinition="TINYINT(4)")
     */
    protected $transportId;

    /**
     * @Column(columnDefinition="TINYINT(4)")
     ****/
    protected $messageTypeId;


    /**
     * @Column(columnDefinition="TINYINT(4)")
     ****/
    protected $contactTypeId;

    /**
     * @Column(type="integer")
     ****/
    protected $clientId;

    /**
     * @Column(type="integer", length=11)
     ****/
    protected $recipientId;


    /**
     * @Column(type="text")
     ****/
    protected $recipients;

    /**
     * @ManyToOne(targetEntity="RemindCloud\Entity\ContactType")
     * @JoinColumn(name="contactTypeId")
     */
    protected $contactType;

    /**
     * @ManyToOne(targetEntity="RemindCloud\Entity\MessageType")
     * @JoinColumn(name="messageTypeId")
     */
    protected $messageType;

    /**
     * @ManyToOne(targetEntity="RemindCloud\Entity\MessageTransport")
     * @JoinColumn(name="transportId")
     */
    protected $transport;


    /**
     * @ManyToOne(targetEntity="RemindCloud\Entity\Client")
     * @JoinColumn(name="clientRid",referencedColumnName="id")
     */
    protected $client;

    /**
     * @OneToOne(targetEntity="RemindCloud\Entity\MessageRecipient")
     * @JoinColumn(name="id",referencedColumnName="id")
     */
    protected $recipient;

    /**
     * (non-PHPdoc)
     * @see RemindCloud.Entity::init()
     */
    public function init()
    {
        $this->priority = 100;
    }
}

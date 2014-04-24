<?php
namespace RemindCloud\Entity;

use Doctrine\ORM\Mapping as ORM;
use RemindCloud\Entity;

/**
 * @Entity(repositoryClass="RemindCloud\Repository\Client")
 * @Table(name="client")
 */
class Client
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(type="smallint")
     */
    protected $companyId;

    /**
     * @Column(type="integer",nullable=true)
     */
    protected $addressId;

    /**
     * @Column(columnDefinition="TINYINT(4)")
     */
    protected $preferredContactTypeId;

    /**
     * @Column(type="string",nullable=true,length=30)
     */
    protected $firstName;

    /**
     * @Column(type="string",length=50)
     */
    protected $lastName;

    /**
     * @Column(type="string",length=100,nullable=true)
     */
    protected $email;

    /**
     * @Column(type="string",length=15,nullable=true)
     */
    protected $homePhone;

    /**
     * @Column(type="string",length=15,nullable=true)
     */
    protected $mobilePhone;

    /**
     * @Column(type="string",length=15,nullable=true)
     */
    protected $workPhone;

    /**
     * @Column(type="boolean")
     */
    protected $validEmail;

    /**
     * @Column(type="boolean")
     */
    protected $active;

    /**
     * @Column(type="string",length=10,nullable=true)
     */
    protected $createdDate;

    /**
     * @Column(type="boolean")
     */
    protected $suspendReminders;

    /**
     * @Column(type="boolean")
     */
    protected $optOut;

    /**
     * @ManyToOne(targetEntity="RemindCloud\Entity\MessageType")
     * @JoinColumn(name="preferredContactTypeId")
     */
    protected $preferredMessageType;


    /**
     * @ManyToOne(targetEntity="RemindCloud\Entity\Address")
     * @JoinColumn(name="addressId")
     */
    protected $address;


    /**
     * @ManyToOne(targetEntity="RemindCloud\Entity\Company")
     * @JoinColumn(name="companyId",referencedColumnName="id")
     */
    protected $company;

    /*/**
     * @OneToMany(targetEntity="RemindCloud\Entity\Patient", mappedBy="client")
     * @OrderBy({"name"="ASC"})
     */
    //protected $patients;

    /*
    /**
     * @ManyToMany(targetEntity="RemindCloud\Entity\User", indexBy="id")
     * @JoinTable(name="user_client",
     *      joinColumns={
     * 			@JoinColumn(name="dsid", referencedColumnName="dsid"),
     *          @JoinColumn(name="clientRid", referencedColumnName="rid")
     *        },
     *      inverseJoinColumns={
     *          @JoinColumn(name="userId", referencedColumnName="id")
     *      }
     * )
     */
    //protected $users;

    /**
     * Gets merge words for this client.
     *
     * @return array
     */
    public function getMergeWords()
    {
        return array(
            'clientEmail' => $this->email,
            'clientFirstName' => $this->firstName,
            'clientLastName' => $this->lastName,
            'clientFullName' => $this->getFullName(),
            'clientPhone' => $this->homePhone,
            'clientBusiness' => $this->workPhone,
            'clientMobile' => $this->mobilePhone
        );
    }

    /**
     * Getter for full name.
     *
     * @return string
     */
    public function getFullName()
    {
        if ($this->lastName && $this->firstName)
        {
            return $this->firstName . ' ' . $this->lastName;
        } elseif ($this->firstName)
        {
            return $this->firstName;
        } elseif ($this->lastName)
        {
            return $this->lastName;
        } else
        {
            return '';
        }
    }
}

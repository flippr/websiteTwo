<?php

namespace RemindCloud\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
//use Gedmo\Mapping\Annotation as Gedmo;
use RemindCloud\Entity;

/**
 * Company
 *
 * @author McAllister Software Systems
 * @package Entity
 * @subpackage Company
 *
 * @Entity(repositoryClass="RemindCloud\ORM\EntityRepository")
 * @Table(name="company")
 */
class Company
{
    /**
     * @Id
     * @Column(type="smallint")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @Column(type="integer",nullable=true) */
    protected $representativeId;

    /** @Column(type="string",length=150) */
    protected $name;

    /** @Column(type="string",length=15) */
    protected $phoneNumber;

    /** @Column(type="integer",nullable=true) */
    protected $addressId;

    /** @Column(type="string",length=100) */
    protected $email;

    /**
     * @Gedmo\Timestampable(on="create")
     * @Column(type="datetime")
     */
    protected $created;

    /**
     * @ManyToOne(targetEntity="RemindCloud\Entity\Address")
     * @JoinColumn(name="addressId")
     */
    protected $address;


    /**
     * Render as string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * (non-PHPdoc)
     * @see RemindCloud.Entity::init()
     */
    public function init()
    {
    }

    /**
     * Gets merge words for this company.
     *
     * @return array
     */
    public function getMergeWords()
    {
        $words = array(
            'companyName' => $this->name,
            'companyPhone' => $this->phoneNumber,
            'companyEmail' => $this->email
        );

        if ($this->address)
        {
            $words = array_merge($words, array(
                'companyAddressLineOne' => $this->address->addressLineOne,
                'companyAddressLineTwo' => $this->address->addressLineTwo,
                'companyCity' => $this->address->city,
                'companyState' => $this->address->state,
                'companyCountry' => $this->address->country,
                'companyZip' => $this->address->zip
            ));
        }

        return $words;
    }
}

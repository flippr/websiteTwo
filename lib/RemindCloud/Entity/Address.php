<?php
namespace RemindCloud\Entity;

use Doctrine\ORM\Mapping as ORM;
use RemindCloud\Entity;

/**
 * @Entity(repositoryClass="RemindCloud\Repository\Address")
 * @Table(name="address")
 */
class Address
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @Column(type="string") */
    protected $addressLineOne;

    /** @Column(type="string",nullable=true) */
    protected $addressLineTwo;

    /** @Column(type="string",length=100) */
    protected $city;

    /** @Column(type="string",length=5) */
    protected $state;

    /** @Column(type="string",length=25) */
    protected $country;

    /** @Column(type="string",length=15) */
    protected $zip;

    /**
     * Getter for full address.
     *
     * @return string
     */
    public function getFullAddress($separator = '<br/>')
    {
        $address = array();
        $address[] = $this->addressLineOne;
        if ($this->addressLineTwo)
        {
            $address[] = $this->addressLineTwo;
        }
        $address[] = sprintf("%s, %s %s", $this->city, $this->state, $this->zip);
        return implode($separator, $address);
    }
}

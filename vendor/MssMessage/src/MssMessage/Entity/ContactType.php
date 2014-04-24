<?php

namespace MssMessage\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperClass
 */
class ContactType
{
    const EMAIL = 1;
    const SMS = 2;
    const PHONE = 3;


    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string",length=100)
     */
    protected $name;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
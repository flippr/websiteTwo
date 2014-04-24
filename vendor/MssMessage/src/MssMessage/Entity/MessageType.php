<?php

namespace MssMessage\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperClass
 */
class MessageType
{
    const GENERIC = 1;
    const NEWSLETTER = 2;
    const ACCOUNT_CREATED = 3;

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
<?php
namespace RemindCloud\Entity;

use Doctrine\ORM\Mapping as ORM;
use RemindCloud\Entity;

/**
 * @Entity(repositoryClass="RemindCloud\Repository\Message\Transport")
 * @Table(name="message_transport")
 */
class MessageTransport
{
    /**
     * @Id
     * @Column(columnDefinition="TINYINT(4)")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(type="string")
     */
    protected $name;

    public function __toString()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}

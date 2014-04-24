<?php
namespace RemindCloud\Entity;

use Doctrine\ORM\Mapping as ORM;
use RemindCloud\Entity;

/**
 * Type
 *
 * @author McAllister Software Systems
 * @package Entity\Contact
 * @subpackage Type
 *
 * @Entity(repositoryClass="RemindCloud\ORM\EntityRepository")
 * @Table(name="contact_type")
 */
class ContactType
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

    public function setId($id)
    {
        $this->id = $id;
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

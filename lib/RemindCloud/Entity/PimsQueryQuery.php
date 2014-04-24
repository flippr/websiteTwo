<?php

namespace RemindCloud\Entity;

use Doctrine\ORM\Mapping as ORM;
use RemindCloud\Entity;

/**
 * @Entity
 * @Table(name="pims_query")
 */
class PimsQueryQuery
{
    /**
     * @Id
     * @Column(type="integer")
     */
    protected $id;

    /**
     * @Column(type="string")
     */
    protected $name;

    /**
     * @ManyToOne(targetEntity="RemindCloud\Entity\PimsQueryQueryCategory")
     * @JoinColumn(name="categoryRid",referencedColumnName="id")
     */
    protected $category;
}

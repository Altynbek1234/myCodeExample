<?php

namespace Site\ClientsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Admin\ReferenceBundle\Entity\ReferenceChannel;
use phpDocumentor\Reflection\Types\String_;

/**
 * ClientsUpdatedAt
 *
 * @ORM\Table(name="clients_updated_date")
 * @ORM\Entity
 */

class ClientsUpdatedAt {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_at", type="date", nullable=true)
     */
    private $updated_at;

    /**
     * Get updated_at
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set updated_at
     *
     * @param DateTime $updated_at
     * @return ClientsUpdatedAt
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }
}
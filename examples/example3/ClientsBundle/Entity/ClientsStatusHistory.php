<?php

namespace Site\ClientsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientsStatusHistory
 *
 * @ORM\Table(name="clientsstatushistory")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class ClientsStatusHistory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Site\ClientsBundle\Entity\Clients", inversedBy="historystatus")
     * @ORM\JoinColumn(name="Clients_id", referencedColumnName="id")
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="Admin\ReferenceBundle\Entity\ReferenceClientStatus", inversedBy="historyclientstatuses")
     * @ORM\JoinColumn(name="ReferenceClientStatus_id", referencedColumnName="id")
     */
    private $referenceClientStatus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime")
     */
    private $date_add;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text")
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity="Admin\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * Подставляем timestamp в поле date_add
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps()
    {
        if($this->getDateAdd() == null)
        {
            $this->setDateAdd(new \DateTime(date('Y-m-d H:i:s')));
        }
    }
    
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
     * Set date_add
     *
     * @param \DateTime $dateAdd
     * @return ClientsStatusHistory
     */
    public function setDateAdd($dateAdd)
    {
        $this->date_add = $dateAdd;
    
        return $this;
    }

    /**
     * Get date_add
     *
     * @return \DateTime 
     */
    public function getDateAdd()
    {
        return $this->date_add;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return ClientsStatusHistory
     */
    public function setNote($note)
    {
        $this->note = $note;
    
        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set client
     *
     * @param \Site\ClientsBundle\Entity\Clients $client
     * @return ClientsStatusHistory
     */
    public function setClient(\Site\ClientsBundle\Entity\Clients $client = null)
    {
        $this->client = $client;
    
        return $this;
    }

    /**
     * Get client
     *
     * @return \Site\ClientsBundle\Entity\Clients 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set referenceClientStatus
     *
     * @param \Admin\ReferenceBundle\Entity\ReferenceClientStatus $referenceClientStatus
     * @return ClientsStatusHistory
     */
    public function setReferenceClientStatus(\Admin\ReferenceBundle\Entity\ReferenceClientStatus $referenceClientStatus = null)
    {
        $this->referenceClientStatus = $referenceClientStatus;
    
        return $this;
    }

    /**
     * Get referenceClientStatus
     *
     * @return \Admin\ReferenceBundle\Entity\ReferenceClientStatus 
     */
    public function getReferenceClientStatus()
    {
        return $this->referenceClientStatus;
    }

    /**
     * Set user
     *
     * @param \Admin\UserBundle\Entity\User $user
     * @return ClientsStatusHistory
     */
    public function setUser(\Admin\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Admin\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
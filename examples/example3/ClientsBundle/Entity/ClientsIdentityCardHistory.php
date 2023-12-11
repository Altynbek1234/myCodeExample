<?php

namespace Site\ClientsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientsIdentityCardHistory
 *
 * @ORM\Table(name="clientsidentitycardhistory")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class ClientsIdentityCardHistory
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
     * @ORM\ManyToOne(targetEntity="Admin\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(name="data", type="array")
     */
    private $data;

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
     * @return ClientsIdentityCardHistory
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
     * @return ClientsIdentityCardHistory
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
     * Set user
     *
     * @param \Admin\UserBundle\Entity\User $user
     * @return ClientsIdentityCardHistory
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

    /**
     * Set data
     *
     * @param array $data
     * @return ClientsIdentityCardHistory
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}

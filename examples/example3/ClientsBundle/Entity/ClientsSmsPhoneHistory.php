<?php

namespace Site\ClientsBundle\Entity;

use Admin\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ClientsSmsPhoneHistory
 *
 * @ORM\Table(name="clientssmsphonehistory")
 * @ORM\Entity
 */
class ClientsSmsPhoneHistory
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
     * @ORM\ManyToOne(targetEntity="Site\ClientsBundle\Entity\Clients", inversedBy="smsPhoneHistory")
     * @ORM\JoinColumn(name="clients_id", referencedColumnName="id")
     */
    protected $client;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime")
     */
    private $date_add;

    /**
     * @ORM\ManyToOne(targetEntity="Admin\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=12, nullable=true)
     * @Assert\Regex(pattern="'/^996\d{9}$/'", message="Номер для смс не соответствует формату")
     */
    private $phone;

    public function __construct()
    {
        $this->date_add = new \DateTime('now');
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
     * @return ClientsSmsPhoneHistory
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
     * Set client
     *
     * @param Clients $client
     * @return ClientsSmsPhoneHistory
     */
    public function setClient(Clients $client)
    {
        $this->client = $client;
    
        return $this;
    }

    /**
     * Get client
     *
     * @return Clients
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return ClientsSmsPhoneHistory
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }
}
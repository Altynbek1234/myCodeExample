<?php

namespace Site\ClientsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Admin\ReferenceBundle\Entity\ReferenceChannel;

/**
 * Clients
 *
 * @ORM\Table(name="clients")
 * @ORM\Entity(repositoryClass="\Site\ClientsBundle\Repository\ClientsRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Clients
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
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="secondname", type="string", length=255, nullable=true)
     */
    private $secondname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\ManyToOne(targetEntity="Admin\ReferenceBundle\Entity\ReferenceClientType", inversedBy="clients")
     * @ORM\JoinColumn(name="ReferenceClientType_id", referencedColumnName="id")
     */
    protected $referenceClientType;

    /**
     * @ORM\ManyToOne(targetEntity="Admin\ReferenceBundle\Entity\ReferenceGender", inversedBy="clients")
     * @ORM\JoinColumn(name="ReferenceGender_id", referencedColumnName="id")
     */
    protected $referenceGender;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="date", nullable=true)
     */
    private $birthdate;

    /**
     * @var string
     *
     * @ORM\Column(name="inn", type="string", length=14, nullable=true)
     */
    private $inn;

    /**
     * @ORM\ManyToOne(targetEntity="Admin\ReferenceBundle\Entity\ReferenceFamily", inversedBy="clients")
     * @ORM\JoinColumn(name="ReferenceFamily_id", referencedColumnName="id")
     */
    protected $referenceFamily;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="cell_phone", type="string", length=255, nullable=true)
     */
    private $cell_phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity="Admin\ReferenceBundle\Entity\ReferenceLegalForm", inversedBy="clients")
     * @ORM\JoinColumn(name="ReferenceLegalForm_id", referencedColumnName="id")
     */
    protected $referenceLegalForm;

    /**
     * @var string
     *
     * @ORM\Column(name="legal_name", type="text", nullable=true)
     */
    private $legal_name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="legal_inn", type="string", length=14, nullable=true)
     */
    private $legal_inn;

    /**
     * @var string
     *
     * @ORM\Column(name="okpo", type="string", length=255, nullable=true)
     */
    private $okpo;

    /**
     * @ORM\ManyToOne(targetEntity="Admin\ReferenceBundle\Entity\ReferenceCountry", inversedBy="clients")
     * @ORM\JoinColumn(name="ReferenceCountry_id", referencedColumnName="id")
     */
    protected $referenceCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="bank_account", type="text", nullable=true)
     */
    private $bank_account;

    /**
     * @var string
     *
     * @ORM\Column(name="bussiness", type="text", nullable=true)
     */
    private $bussiness;

    /**
     * @var string
     *
     * @ORM\Column(name="tax", type="string", length=255, nullable=true)
     */
    private $tax;
    
    /**
     * @ORM\ManyToOne(targetEntity="Admin\ReferenceBundle\Entity\ReferenceClientStatus", inversedBy="clients")
     * @ORM\JoinColumn(name="ReferenceClientStatus_id", referencedColumnName="id")
     */
    protected $referenceClientStatus;
    
    /**
     * @ORM\OneToMany(targetEntity="ClientsStatusHistory", mappedBy="client")
     *
     */
    protected $historyStatus;
    
    /**
     * @ORM\OneToOne(targetEntity="ClientsIdentityCard", mappedBy="client", cascade={"all"})
     */
    protected $identityCard;

	/**
     * @ORM\OneToMany(targetEntity="Site\ClientsBundle\Entity\ClientsChannel", mappedBy="client")
     */
	protected $clientsChannels;


    /**
     * @ORM\OneToMany(targetEntity="Site\BlanksBundle\Entity\NewBlanks\BlanksPledge", mappedBy="client")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $blanksPladges;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var boolean
     *
     * @ORM\Column(name="passive", type="boolean", nullable=true)
     */
    private $passive;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=12, nullable=true)
     * @Assert\Regex(pattern="/^996\d{9}$/", message="Номер для смс не соответствует формату")
     */
    private $smsPhone;

    /**
     * @ORM\OneToMany(targetEntity="ClientsSmsPhoneHistory", mappedBy="client")
     */
    private $smsPhoneHistory;

	/**
	 * /**
     * @ORM\ManyToOne(targetEntity="Admin\LombardBundle\Entity\Lombard", inversedBy="clients")
     * @ORM\JoinColumn(name="lombard_id", referencedColumnName="id")
     */
	protected $lombard;

	 	/**
	 * @var \DateTime
	 * 
	 * @ORM\Column(name="created_at", type="datetime", nullable="true")
	 */
	private $created_at;

	/**
	 * @var \DateTime
	 * 
	 * @ORM\Column(name="updated_at", type="datetime", nullable="true")
	 */
	private $updated_at;

    /**
     * @ORM\OneToOne(targetEntity="Site\ClientsBundle\Entity\ClientsData", mappedBy="client")
     */
    private $clientData;

	 /**
	 * Подставляем timestamp в поле created_at
	 *
	 * @ORM\PrePersist
	 */
	public function addCreatedAt()
	{
		if ($this->getCreatedAt() == null) {
			$this->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
		}
	}

	/**
	 * Подставляем timestamp в поле updated_at
	 *
	 * @ORM\PrePersist
	 * @ORM\PreUpdate
	 */
	public function addUpdatedAt()
	{
		if ($this->getUpdatedAt() == null) {
			$this->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
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
     * Set lombard
     *
     * @param string $firstname
     * @return Clients
     */
    public function setLombard($lombard)
    {
        $this->lombard = $lombard;
    
        return $this;
    }

    /**
     * Get lombard
     *
     * @return string 
     */
    public function getLombard()
    {
        return $this->lombard;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return Clients
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set secondname
     *
     * @param string $secondname
     * @return Clients
     */
    public function setSecondname($secondname)
    {
        $this->secondname = $secondname;
    
        return $this;
    }

    /**
     * Get secondname
     *
     * @return string 
     */
    public function getSecondname()
    {
        return $this->secondname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Clients
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    
    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     * @return Clients
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    
        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime 
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set inn
     *
     * @param string $inn
     * @return Clients
     */
    public function setInn($inn)
    {
        $this->inn = $inn;
    
        return $this;
    }

    /**
     * Get inn
     *
     * @return string 
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Clients
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Clients
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set cell_phone
     *
     * @param string $cellPhone
     * @return Clients
     */
    public function setCellPhone($cellPhone)
    {
        $this->cell_phone = $cellPhone;
    
        return $this;
    }

    /**
     * Get cell_phone
     *
     * @return string 
     */
    public function getCellPhone()
    {
        return $this->cell_phone;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Clients
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return Clients
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
     * Set legal_name
     *
     * @param string $legalName
     * @return Clients
     */
    public function setLegalName($legalName)
    {
        $this->legal_name = $legalName;
    
        return $this;
    }

    /**
     * Get legal_name
     *
     * @return string 
     */
    public function getLegalName()
    {
        return $this->legal_name;
    }

    /**
     * Set okpo
     *
     * @param string $okpo
     * @return Clients
     */
    public function setOkpo($okpo)
    {
        $this->okpo = $okpo;
    
        return $this;
    }

    /**
     * Get okpo
     *
     * @return string 
     */
    public function getOkpo()
    {
        return $this->okpo;
    }

    
    /**
     * Set bank_account
     *
     * @param string $bankAccount
     * @return Clients
     */
    public function setBankAccount($bankAccount)
    {
        $this->bank_account = $bankAccount;
    
        return $this;
    }

    /**
     * Get bank_account
     *
     * @return string 
     */
    public function getBankAccount()
    {
        return $this->bank_account;
    }

    /**
     * Set bussiness
     *
     * @param string $bussiness
     * @return Clients
     */
    public function setBussiness($bussiness)
    {
        $this->bussiness = $bussiness;
    
        return $this;
    }

    /**
     * Get bussiness
     *
     * @return string 
     */
    public function getBussiness()
    {
        return $this->bussiness;
    }

    /**
     * Set tax
     *
     * @param string $tax
     * @return Clients
     */
    public function setTax($tax)
    {
        $this->tax = $tax;
    
        return $this;
    }

	  /**
     * Get clientschannels
     *
     */
    public function getClientsChannels()
    {
        return $this->clientsChannels;
    }


    /**
     * Get tax
     *
     * @return string 
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Set referenceClientType
     *
     * @param \Admin\ReferenceBundle\Entity\ReferenceClientType $referenceClientType
     * @return Clients
     */
    public function setReferenceClientType(\Admin\ReferenceBundle\Entity\ReferenceClientType $referenceClientType = null)
    {
        $this->referenceClientType = $referenceClientType;
    
        return $this;
    }

    /**
     * Get referenceClientType
     *
     * @return \Admin\ReferenceBundle\Entity\ReferenceClientType 
     */
    public function getReferenceClientType()
    {
        return $this->referenceClientType;
    }

    /**
     * Set referenceGender
     *
     * @param \Admin\ReferenceBundle\Entity\ReferenceGender $referenceGender
     * @return Clients
     */
    public function setReferenceGender(\Admin\ReferenceBundle\Entity\ReferenceGender $referenceGender = null)
    {
        $this->referenceGender = $referenceGender;
    
        return $this;
    }

    /**
     * Get referenceGender
     *
     * @return \Admin\ReferenceBundle\Entity\ReferenceGender 
     */
    public function getReferenceGender()
    {
        return $this->referenceGender;
    }

    /**
     * Set referenceFamily
     *
     * @param \Admin\ReferenceBundle\Entity\ReferenceFamily $referenceFamily
     * @return Clients
     */
    public function setReferenceFamily(\Admin\ReferenceBundle\Entity\ReferenceFamily $referenceFamily = null)
    {
        $this->referenceFamily = $referenceFamily;
    
        return $this;
    }

    /**
     * Get referenceFamily
     *
     * @return \Admin\ReferenceBundle\Entity\ReferenceFamily 
     */
    public function getReferenceFamily()
    {
        return $this->referenceFamily;
    }

    /**
     * Set referenceLegalForm
     *
     * @param \Admin\ReferenceBundle\Entity\ReferenceLegalForm $referenceLegalForm
     * @return Clients
     */
    public function setReferenceLegalForm(\Admin\ReferenceBundle\Entity\ReferenceLegalForm $referenceLegalForm = null)
    {
        $this->referenceLegalForm = $referenceLegalForm;
    
        return $this;
    }

    /**
     * Get referenceLegalForm
     *
     * @return \Admin\ReferenceBundle\Entity\ReferenceLegalForm 
     */
    public function getReferenceLegalForm()
    {
        return $this->referenceLegalForm;
    }

    /**
     * Set referenceCountry
     *
     * @param \Admin\ReferenceBundle\Entity\ReferenceCountry $referenceCountry
     * @return Clients
     */
    public function setReferenceCountry(\Admin\ReferenceBundle\Entity\ReferenceCountry $referenceCountry = null)
    {
        $this->referenceCountry = $referenceCountry;
    
        return $this;
    }

    /**
     * Get referenceCountry
     *
     * @return \Admin\ReferenceBundle\Entity\ReferenceCountry 
     */
    public function getReferenceCountry()
    {
        return $this->referenceCountry;
    }

    /**
     * Set referenceClientStatus
     *
     * @param \Admin\ReferenceBundle\Entity\ReferenceClientStatus $referenceClientStatus
     * @return Clients
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
     * Constructor
     */
    public function __construct()
    {
        $this->historyStatus = new ArrayCollection();
        $this->smsPhoneHistory = new ArrayCollection();
        $this->clientsChannels = new ArrayCollection();
    }

    /**
     * Add historyStatus
     *
     * @param \Site\ClientsBundle\Entity\ClientsStatusHistory $historyStatus
     * @return Clients
     */
    public function addHistoryStatus(\Site\ClientsBundle\Entity\ClientsStatusHistory $historyStatus)
    {
        $this->historyStatus[] = $historyStatus;
    
        return $this;
    }

    /**
     * Remove historyStatus
     *
     * @param \Site\ClientsBundle\Entity\ClientsStatusHistory $historyStatus
     */
    public function removeHistoryStatus(\Site\ClientsBundle\Entity\ClientsStatusHistory $historyStatus)
    {
        $this->historyStatus->removeElement($historyStatus);
    }

    /**
     * Get historyStatus
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHistoryStatus()
    {
        return $this->historyStatus;
    }

    /**
     * Get blanksPladges
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBlanksPladges()
    {
        return $this->blanksPladges;
    }
    
    public function __toString()
    {
        if($this->getReferenceClientType()->getId() == 1){
            return $this->getLastname() . ' ' . $this->getFirstname() . ' ' .$this->getSecondname();
        }
        if($this->getReferenceLegalForm()){
            return $this->getReferenceLegalForm()->getShortName().' '.$this->getLegalName().' в лице '. $this->getLastname() . ' ' . $this->getFirstname() . ' ' .$this->getSecondname();
        }
        return $this->getLegalName().' в лице '. $this->getLastname() . ' ' . $this->getFirstname() . ' ' .$this->getSecondname();

    }

    public function getFio()
    {
        return $this->getLastname() . ' ' . $this->getFirstname() . ' ' .$this->getSecondname();
    }
    
    /**
     * Set identityCard
     *
     * @param \Site\ClientsBundle\Entity\ClientsIdentityCard $identityCard
     * @return Clients
     */
    public function setIdentityCard(\Site\ClientsBundle\Entity\ClientsIdentityCard $identityCard = null)
    {
        $this->identityCard = $identityCard;
    
        return $this;
    }

    /**
     * Get identityCard
     *
     * @return \Site\ClientsBundle\Entity\ClientsIdentityCard 
     */
    public function getIdentityCard()
    {
        return $this->identityCard;
    }

	/**
	 * Add channel
	 *
	 * @param \Admin\ReferenceBundle\Entity\ReferenceChannel $channel
	 * @return Clients
	 */

	public function addChannel(ReferenceChannel $channel): self
    {
        if (!$this->channels->contains($channel)) {
            $this->channels[] = $channel;
            $channel->setClient($this);
        }
        return $this;
    }


	/**
	 * Remove channel
	 *
	 * @param \Admin\ReferenceBundle\Entity\ReferenceChannel $channel
	 * @return Clients
	 */
	 public function removeChannel(ReferenceChannel $channel): self
    {
        if ($this->channels->contains($channel)) {
            $this->channels->removeElement($channel);
            // set the owning side to null (unless already changed)
            $channel->setClient(null);
        }
        return $this;
    }

	public function getChannels()
	{
		return $this->channels;
	}

    /**
     * Set legal_inn
     *
     * @param string $legalInn
     * @return Clients
     */
    public function setLegalInn($legalInn)
    {
        $this->legal_inn = $legalInn;
    
        return $this;
    }

    /**
     * Get legal_inn
     *
     * @return string 
     */
    public function getLegalInn()
    {
        return $this->legal_inn;
    }

    /**
     * Add historyStatus
     *
     * @param \Site\ClientsBundle\Entity\ClientsStatusHistory $historyStatus
     * @return Clients
     */
    public function addHistoryStatu(\Site\ClientsBundle\Entity\ClientsStatusHistory $historyStatus)
    {
        $this->historyStatus[] = $historyStatus;
    
        return $this;
    }

    /**
     * Remove historyStatus
     *
     * @param \Site\ClientsBundle\Entity\ClientsStatusHistory $historyStatus
     */
    public function removeHistoryStatu(\Site\ClientsBundle\Entity\ClientsStatusHistory $historyStatus)
    {
        $this->historyStatus->removeElement($historyStatus);
    }

    /**
     * Set enabled
     *
     * @param integer $enabled
     * @return Clients
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return integer 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Add blanksPladges
     *
     * @param \Site\BlanksBundle\Entity\BlanksPledge $blanksPladges
     * @return Clients
     */
    public function addBlanksPladge(\Site\BlanksBundle\Entity\BlanksPledge $blanksPladges)
    {
        $this->blanksPladges[] = $blanksPladges;

        return $this;
    }

    /**
     * Remove blanksPladges
     *
     * @param \Site\BlanksBundle\Entity\BlanksPledge $blanksPladges
     */
    public function removeBlanksPladge(\Site\BlanksBundle\Entity\BlanksPledge $blanksPladges)
    {
        $this->blanksPladges->removeElement($blanksPladges);
    }

    /**
     * Set passive
     *
     * @param integer $passive
     * @return Clients
     */
    public function setPassive($passive)
    {
        $this->passive = $passive;

        return $this;
    }

    /**
     * Get passive
     *
     * @return integer
     */
    public function getPassive()
    {
        return $this->passive;
    }

    /**
     * @return string|null
     */
    public function getSmsPhone(): ?string
    {
        return $this->smsPhone;
    }

    /**
     * @param string|null $smsPhone
     */
    public function setSmsPhone(?string $smsPhone): void
    {
        $this->smsPhone = $smsPhone;
    }

    /**
     * Add smsPhoneHistory
     *
     * @param ClientsSmsPhoneHistory $smsPhoneHistory
     * @return Clients
     */
    public function addSmsPhoneHistory(ClientsSmsPhoneHistory $smsPhoneHistory)
    {
        $this->smsPhoneHistory[] = $smsPhoneHistory;

        return $this;
    }

    /**
     * Remove smsPhoneHistory
     *
     * @param ClientsSmsPhoneHistory $smsPhoneHistory
     */
    public function removeSmsPhoneHistory(ClientsSmsPhoneHistory $smsPhoneHistory)
    {
        $this->smsPhoneHistory->removeElement($smsPhoneHistory);
    }

    /**
     * @return ArrayCollection
     */
    public function getSmsPhoneHistory()
    {
        return $this->smsPhoneHistory;
    }

	 
	/**
	 * Set created_at
	 *
	 * @param \DateTime $created_at
	 * @return ClientsChannel
	 */
	public function setCreatedAt($created_at)
	{
		$this->created_at = $created_at;

		return $this;
	}

	/**
	 * Get created_at
	 *
	 * @return \DateTime 
	 */
	public function getCreatedAt()
	{
		return $this->created_at;
	}

	/**
	 * Set updated_at
	 *
	 * @param \DateTime $updated_at
	 * @return ClientsChannel
	 */
	public function setUpdatedAt($updated_at)
	{
		$this->updated_at = $updated_at;

		return $this;
	}

	/**
	 * Get updated_at
	 *
	 * @return \DateTime 
	 */
	public function getUpdatedAt()
	{
		return $this->updated_at;
	}

	public function getClientsChannel()
	{
		return $this->clientsChannel;
	}

	/**
	 * Remove channel
	 *
	 * @param \Admin\ReferenceBundle\Entity\ReferenceChannel $channel
	 * @return Clients
	 */
	public function removeClientsChannel(ReferenceChannel $channel): self
	{
		if ($this->clientsChannels->contains($channel)) {
			$this->clientsChannels->removeElement($channel);
			// set the owning side to null (unless already changed)
			$channel->setClient(null);
		}
		return $this;
	}
}

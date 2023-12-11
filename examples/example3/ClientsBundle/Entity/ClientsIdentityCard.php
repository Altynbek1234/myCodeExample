<?php

namespace Site\ClientsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientsIdentityCard
 *
 * @ORM\Table(name="clientsidentitycard")
 * @ORM\Entity
 */
class ClientsIdentityCard
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
     * @ORM\OneToOne(targetEntity="Site\ClientsBundle\Entity\Clients", inversedBy="identityCard")
     * @ORM\JoinColumn(name="Clients_id", referencedColumnName="id")
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="Admin\ReferenceBundle\Entity\ReferenceDocumentType", inversedBy="identitycard")
     * @ORM\JoinColumn(name="ReferenceDocumentType_id", referencedColumnName="id")
     */
    private $referenceDocumentType;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=255, nullable=true)
     */
    private $number;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_issue", type="date")
     */
    private $date_issue;

    /**
     * @var string
     *
     * @ORM\Column(name="department", type="string", length=255, nullable=true)
     */
    private $department;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="validity", type="date", nullable=true)
     */
    private $validity;

    /**
     * @ORM\ManyToOne(targetEntity="Admin\ReferenceBundle\Entity\ReferenceCountry", inversedBy="identitycard")
     * @ORM\JoinColumn(name="ReferenceCountry_id", referencedColumnName="id", nullable=true)
     */
    private $referenceCountry;

    /**
     * @ORM\ManyToOne(targetEntity="Admin\ReferenceBundle\Entity\ReferencePasportStatus", inversedBy="identitycard")
     * @ORM\JoinColumn(name="ReferencePasportStatus_id", referencedColumnName="id", nullable=true)
     */
    private $referencePasportStatus;


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
     * Set number
     *
     * @param string $number
     * @return ClientsIdentityCard
     */
    public function setNumber($number)
    {
        $this->number = $number;
    
        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set date_issue
     *
     * @param \DateTime $dateIssue
     * @return ClientsIdentityCard
     */
    public function setDateIssue($dateIssue)
    {
        $this->date_issue = $dateIssue;
    
        return $this;
    }

    /**
     * Get date_issue
     *
     * @return \DateTime 
     */
    public function getDateIssue()
    {
        return $this->date_issue;
    }

    /**
     * Set department
     *
     * @param string $department
     * @return ClientsIdentityCard
     */
    public function setDepartment($department)
    {
        $this->department = $department;
    
        return $this;
    }

    /**
     * Get department
     *
     * @return string 
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set validity
     *
     * @param \DateTime $validity
     * @return ClientsIdentityCard
     */
    public function setValidity($validity)
    {
        $this->validity = $validity;
    
        return $this;
    }

    /**
     * Get validity
     *
     * @return \DateTime 
     */
    public function getValidity()
    {
        return $this->validity;
    }
    

    /**
     * Set clients
     *
     * @param \Site\ClientsBundle\Entity\Clients $client
     * @return ClientsIdentityCard
     */
    public function setClient(\Site\ClientsBundle\Entity\Clients $client = null)
    {
        $this->client = $client;
    
        return $this;
    }

    /**
     * Get clients
     *
     * @return \Site\ClientsBundle\Entity\Clients 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set referenceDocumentType
     *
     * @param \Admin\ReferenceBundle\Entity\ReferenceDocumentType $referenceDocumentType
     * @return ClientsIdentityCard
     */
    public function setReferenceDocumentType(\Admin\ReferenceBundle\Entity\ReferenceDocumentType $referenceDocumentType = null)
    {
        $this->referenceDocumentType = $referenceDocumentType;
    
        return $this;
    }

    /**
     * Get referenceDocumentType
     *
     * @return \Admin\ReferenceBundle\Entity\ReferenceDocumentType 
     */
    public function getReferenceDocumentType()
    {
        return $this->referenceDocumentType;
    }

    /**
     * Set referenceCountry
     *
     * @param \Admin\ReferenceBundle\Entity\ReferenceCountry $referenceCountry
     * @return ClientsIdentityCard
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
     * Set referencePasportStatus
     *
     * @param \Admin\ReferenceBundle\Entity\ReferencePasportStatus $referencePasportStatus
     * @return ClientsIdentityCard
     */
    public function setReferencePasportStatus(\Admin\ReferenceBundle\Entity\ReferencePasportStatus $referencePasportStatus = null)
    {
        $this->referencePasportStatus = $referencePasportStatus;
    
        return $this;
    }

    /**
     * Get referencePasportStatus
     *
     * @return \Admin\ReferenceBundle\Entity\ReferencePasportStatus 
     */
    public function getReferencePasportStatus()
    {
        return $this->referencePasportStatus;
    }
    
    public function __toString()
    {
        return $this->getReferenceDocumentType() . ' ' . $this->getNumber();
    }
}
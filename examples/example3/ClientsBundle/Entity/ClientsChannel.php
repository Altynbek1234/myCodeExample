<?php

namespace Site\ClientsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientsChannel
 *
 * @ORM\Table(name="clients_channel")
 * @ORM\Entity(repositoryClass="\Site\ClientsBundle\Repository\ClientsChannelRepository")
 */
class ClientsChannel
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
    * @ORM\ManyToOne(targetEntity="Site\ClientsBundle\Entity\Clients", inversedBy="clients_channel")
    * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
   */
	 protected $client;

	/**
    * @ORM\ManyToOne(targetEntity="Admin\ReferenceBundle\Entity\ReferenceChannel", inversedBy="clients_channel")
    * @ORM\JoinColumn(name="channel_id", referencedColumnName="id")
   */
	 protected $channel;

	 /**
     * @var string
     *
     * @ORM\Column(name="other", type="string", length=255, nullable=true)
     */

	 protected $other;

	  /**
     * Get clientschannel
     *
     * @return string 
     */
    public function getOther()
    {
        return $this->other;
    }

	 public function setOther(string $other): self
    {
        $this->other = $other;

        return $this;
    }

    public function getClient()
    {
        return $this->client;
    }
	 
	  /**
     * Set client
     *
     * @param \Site\ClientsBundle\Entity\Clients $client
     * @return ClientsChannel
     */
    public function setClient(\Site\ClientsBundle\Entity\Clients $client = null)
    {
        $this->client = $client;

        return $this;
    }

	  /**
     * Set channel
     *
     * @param \Admin\ReferenceBundle\Entity\ReferenceChannel $channel
     * @return ClientsChannel
     */
    public function setChannel(\Admin\ReferenceBundle\Entity\ReferenceChannel $channel = null)
    {
        $this->channel = $channel;

        return $this;
    }

    public function getChannel()
    {
        return $this->channel;
    }
}

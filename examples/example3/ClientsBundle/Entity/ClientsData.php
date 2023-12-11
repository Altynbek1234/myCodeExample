<?php

namespace Site\ClientsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Admin\ReferenceBundle\Entity\ReferenceChannel;
use phpDocumentor\Reflection\Types\String_;

/**
 * ClientsData
 *
 * @ORM\Table(name="clients_data")
 * @ORM\Entity
 */

class ClientsData
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
     * @ORM\Column(name="sex", type="string", length=1, nullable=true)
     */
    private $sex;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date_last_time_created_bp", type="date", nullable=true)
     */
    private $date_last_time_created_bp;

    /**
     * @ORM\OneToOne(targetEntity="Site\ClientsBundle\Entity\Clients", inversedBy="clientData")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    protected $client;

    /**
     * @var integer
     *
     * @ORM\Column(name="active_blanks_pledge", type="integer", nullable=true)
     */
    private $active_blanks_pledge;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_sum_bp_all", type="integer", nullable=true)
     */
    private $max_sum_bp_all;

    /**
     * @var integer
     *
     * @ORM\Column(name="averege_sum_bp_all", type="integer", nullable=true)
     */
    private $averege_sum_bp_all;

    /**
     * @var integer
     *
     * @ORM\Column(name="sold", type="integer", nullable=true)
     */
    private $sold;

    /**
     * @var integer
     *
     * @ORM\Column(name="average_interest_fine", type="integer", nullable=true)
     */
    private $average_interest_fine;

    /**
     * @var float
     *
     * @ORM\Column(name="average_time_bp_lombard", type="float", nullable=true)
     */
    private $average_time_bp_lombard;

    /**
     * @var integer
     *
     * @ORM\Column(name="totall_sum_returned_interest", type="integer", nullable=true)
     */
    private $totall_sum_returned_interest;

    /**
     * @var integer
     *
     * @ORM\Column(name="totall_sum_returned_fine", type="integer", nullable=true)
     */
    private $totall_sum_returned_fine;

    /**
     * @var integer
     *
     * @ORM\Column(name="totall_sum_returned_interest_fine", type="integer", nullable=true)
     */
    private $totall_sum_returned_interest_fine;

    /**
     * @var integer
     *
     * @ORM\Column(name="totall_sum_returned_interest_year", type="integer", nullable=true)
     */
    private $totall_sum_returned_interest_year;

    /**
     * @var integer
     *
     * @ORM\Column(name="totall_sum_returned_fine_year", type="integer", nullable=true)
     */
    private $totall_sum_returned_fine_year;

    /**
     * @var integer
     *
     * @ORM\Column(name="totall_sum_returned_interest_fine_year", type="integer", nullable=true)
     */
    private $totall_sum_returned_interest_fine_year;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="last_extension_time", type="date", nullable=true)
     */
    private $last_extension_time;

    /**
     * @var integer
     *
     * @ORM\Column(name="count_bp_year", type="integer", nullable=true)
     */
    private $count_bp_year;

    /**
     * @var integer
     *
     * @ORM\Column(name="count_bp_all", type="integer", nullable=true)
     */
    private $count_bp_all;

    /**
     * @var integer
     *
     * @ORM\Column(name="extensions_count_year", type="integer", nullable=true)
     */
    private $extensions_count_year;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_extensions_count", type="integer", nullable=true)
     */
    private $total_extensions_count;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lombard_name", type="text", nullable=true)
     */
    private $lombard_name;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    public function setDateLastTimeCreatedBp($date_last_time_created_bp)
    {
        $this->date_last_time_created_bp = $date_last_time_created_bp;
    }

    public function setClient($client)
    {
        $this->client = $client;
    }

    public function setActiveBlanksPledge($active_blanks_pledge)
    {
        $this->active_blanks_pledge = $active_blanks_pledge;
    }

    public function setMaxSumBpAll($max_sum_bp_all)
    {
        $this->max_sum_bp_all = $max_sum_bp_all;
    }

    public function setAveregeSumBpAll($averege_sum_bp_all)
    {
        $this->averege_sum_bp_all = $averege_sum_bp_all;
    }

    public function setSold($sold)
    {
        $this->sold = $sold;
    }

    public function setAverageInterestFine($average_interest_fine)
    {
        $this->average_interest_fine = $average_interest_fine;
    }

    public function setAverageTimeBpLombard($average_time_bp_lombard)
    {
        $this->average_time_bp_lombard = $average_time_bp_lombard;
    }

    public function setTotallSumReturnedInterest($totall_sum_returned_interest)
    {
        $this->totall_sum_returned_interest = $totall_sum_returned_interest;
    }

    public function setTotallSumReturnedFine($totall_sum_returned_fine)
    {
        $this->totall_sum_returned_fine = $totall_sum_returned_fine;
    }

    public function setTotallSumReturnedInterestFine($totall_sum_returned_interest_fine)
    {
        $this->totall_sum_returned_interest_fine = $totall_sum_returned_interest_fine;
    }

    public function setTotallSumReturnedInterestYear($totall_sum_returned_interest_year)
    {
        $this->totall_sum_returned_interest_year = $totall_sum_returned_interest_year;
    }

    public function setTotallSumReturnedFineYear($totall_sum_returned_fine_year)
    {
        $this->totall_sum_returned_fine_year = $totall_sum_returned_fine_year;
    }

    public function setTotallSumReturnedInterestFineYear($totall_sum_returned_interest_fine_year)
    {
        $this->totall_sum_returned_interest_fine_year = $totall_sum_returned_interest_fine_year;
    }

    public function setLastExtensionTime($last_extension_time)
    {
        $this->last_extension_time = $last_extension_time;
    }

    public function setCountBpYear($count_bp_year)
    {
        $this->count_bp_year = $count_bp_year;
    }

    public function setCountBpAll($count_bp_all)
    {
        $this->count_bp_all = $count_bp_all;
    }

    public function setExtensionsCountYear($extensions_count_year)
    {
        $this->extensions_count_year = $extensions_count_year;
    }

    public function setTotalExtensionsCount($total_extensions_count)
    {
        $this->total_extensions_count = $total_extensions_count;
    }

    public function setLombardName($lombard_name)
    {
        $this->lombard_name = $lombard_name;
    }

}

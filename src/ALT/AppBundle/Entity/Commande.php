<?php

namespace ALT\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="ALT\AppBundle\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @ORM\ManyToOne(targetEntity="ALT\AppBundle\Entity\Client",inversedBy="commande")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $client;
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCommande", type="datetime")
     */
    private $dateCommande;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateVisite", type="datetime")
     */
    private $dateVisite;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;
    
    /**
     * @var float
     *
     * @ORM\Column(name="tarif", type="float")
     */
    private $tarif;

    /**
     * @var string
     *
     * @ORM\Column(name="codeReservation", type="string", length=255)
     */
    private $codeReservation;

    /**
     * @var int
     *
     * @ORM\Column(name="statut", type="integer")
     */
    private $statut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFacturation", type="datetime")
     */
    private $dateFacturation;

    /**
     * @var string
     *
     * @ORM\Column(name="numeroTransaction", type="string", length=255)
     */
    private $numeroTransaction;
    

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="string", length=255)
     */
    private $data;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateCommande
     *
     * @param \DateTime $dateCommande
     *
     * @return Commande
     */
    public function setDateCommande($dateCommande)
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    /**
     * Get dateCommande
     *
     * @return \DateTime
     */
    public function getDateCommande()
    {
        return $this->dateCommande;
    }

    /**
     * Set dateVisite
     *
     * @param \DateTime $dateVisite
     *
     * @return Commande
     */
    public function setDateVisite($dateVisite)
    {
        $this->dateVisite = $dateVisite;

        return $this;
    }

    /**
     * Get dateVisite
     *
     * @return \DateTime
     */
    public function getDateVisite()
    {
        return $this->dateVisite;
    }

    /**
     * Set tarif
     *
     * @param float $tarif
     *
     * @return Commande
     */
    public function setTarif($tarif)
    {
        $this->tarif = $tarif;

        return $this;
    }

    /**
     * Get tarif
     *
     * @return float
     */
    public function getTarif()
    {
        return $this->tarif;
    }

    /**
     * Set codeReservation
     *
     * @param string $codeReservation
     *
     * @return Commande
     */
    public function setCodeReservation($codeReservation)
    {
        $this->codeReservation = $codeReservation;

        return $this;
    }

    /**
     * Get codeReservation
     *
     * @return string
     */
    public function getCodeReservation()
    {
        return $this->codeReservation;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Commande
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set dateFacturation
     *
     * @param \DateTime $dateFacturation
     *
     * @return Commande
     */
    public function setDateFacturation($dateFacturation)
    {
        $this->dateFacturation = $dateFacturation;

        return $this;
    }

    /**
     * Get dateFacturation
     *
     * @return \DateTime
     */
    public function getDateFacturation()
    {
        return $this->dateFacturation;
    }

    /**
     * Set numeroTransaction
     *
     * @param string $numeroTransaction
     *
     * @return Commande
     */
    public function setNumeroTransaction($numeroTransaction)
    {
        $this->numeroTransaction = $numeroTransaction;

        return $this;
    }

    /**
     * Get numeroTransaction
     *
     * @return string
     */
    public function getNumeroTransaction()
    {
        return $this->numeroTransaction;
    }

    /**
     * Set typeTransaction
     *
     * @param string $typeTransaction
     *
     * @return Commande
     */
    public function setTypeTransaction($typeTransaction)
    {
        $this->typeTransaction = $typeTransaction;

        return $this;
    }

    /**
     * Get typeTransaction
     *
     * @return string
     */
    public function getTypeTransaction()
    {
        return $this->typeTransaction;
    }

    /**
     * Set data
     *
     * @param string $data
     *
     * @return Commande
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set client
     *
     * @param \ALT\AppBundle\Entity\Client $client
     *
     * @return Commande
     */
    public function setClient(\ALT\AppBundle\Entity\Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \ALT\AppBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }
}
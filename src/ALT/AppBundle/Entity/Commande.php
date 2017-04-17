<?php

namespace ALT\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\JoinColumn(nullable=true)
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
     * @ORM\Column(name="dateVisite", type="date")
     */
    private $dateVisite;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
    
    /**
     * @var float
     *
     * @ORM\Column(name="tarif", type="float", nullable=true)
     */
    private $tarif;

    /**
     * @var string
     *
     * @ORM\Column(name="codeReservation", type="string", length=255, nullable=true)
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
     * @ORM\Column(name="dateFacturation", type="datetime", nullable=true)
     */
    private $dateFacturation;

    /**
     * @var string
     *
     * @ORM\Column(name="numeroTransaction", type="string", length=255, nullable=true)
     */
    private $numeroTransaction;
    

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="string", length=255, nullable=true)
     */
    private $data;

    /**
     * @var int
     *
     * @ORM\Column(name="nbBillets", type="integer")
     */
    private $nbBillets;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="ALT\AppBundle\Entity\Billet", mappedBy="commande", fetch="EXTRA_LAZY", cascade={"remove"})
     */
    private $billets;

    /**
     * Commande constructor.
     */
    public function __construct()
    {
        // Par défaut, la date de la commande est la date d'aujourd'hui
        $this->dateCommande = new \Datetime();
        $this->billets = new ArrayCollection();
        $this->statut = 1;
    }

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

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Commande
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    public function getNbBillets()
    {
        return $this->nbBillets;
    }

    public function setNbBillets($nbBillets)
    {
        $this->nbBillets = $nbBillets;

        return $this;
    }

    /**
     * Add billet
     *
     * @param \ALT\AppBundle\Entity\Billet $billet
     *
     * @return Commande
     */
    public function addBillet(\ALT\AppBundle\Entity\Billet $billet)
    {
        $this->billets[] = $billet;

        return $this;
    }

    /**
     * Remove billet
     *
     * @param \ALT\AppBundle\Entity\Billet $billet
     */
    public function removeBillet(\ALT\AppBundle\Entity\Billet $billet)
    {
        $this->billets->removeElement($billet);
    }

    /**
     * Get billets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBillets()
    {
        return $this->billets;
    }
}

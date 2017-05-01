<?php

namespace ALT\AppBundle\Entity;

use ALT\AppBundle\Validator\NonReservationDates;
use ALT\AppBundle\Validator\NonReservationType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ALT\AppBundle\Validator\MaxBillet;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="ALT\AppBundle\Repository\CommandeRepository")
 * @NonReservationType()
 */
class Commande
{

    const DEMI_JOURNEE = 'Demi-Journée';
    const JOURNEE = 'Journée';
    
    
    
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
     * @Assert\DateTime(groups={"commande"})
     */
    private $dateCommande;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateVisite", type="date")
     * @Assert\DateTime(groups={"commande"})
     * @NonReservationDates(groups={"commande"})
     */
    private $dateVisite;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     * @Assert\NotBlank(groups={"commande"})
     *
     */
    private $type;
    
    /**
     * @var float
     *
     * @ORM\Column(name="tarif", type="float", nullable=true)
     *
     */
    private $tarif;

    /**
     * @var string
     *
     * @ORM\Column(name="codeReservation", type="string", length=255, nullable=true)
     */
    private $codeReservation;

  

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFacturation", type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $dateFacturation;

    /**
     * @var string
     *
     * @ORM\Column(name="numeroTransaction", type="string", length=255, nullable=true)
     */
    private $numeroTransaction;
    

    /**
     * @var int
     *
     * @ORM\Column(name="nbBillets", type="integer")
     * @Assert\NotBlank(groups={"commande"})
     * @Assert\Range(
     *      min = 1,
     *      max = 20,
     *      minMessage = "Vous ne pouvez pas commander moins de {{ limit }} billet",
     *      maxMessage = "Vous ne pouvez pas commander plus de {{ limit }} billets",
     *      groups={"commande"}
     * )
     * @MaxBillet(groups={"commande"})
     */
    private $nbBillets;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="ALT\AppBundle\Entity\Billet", mappedBy="commande", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $billets;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank(groups={"commande"})
     * @Assert\Email(groups={"commande"})
     */
    private $email;

    /**
     * Commande constructor.
     */
    public function __construct()
    {
        // Par défaut, la date de la commande est la date d'aujourd'hui
        $this->dateCommande = new \Datetime();
        $this->billets = new ArrayCollection();
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
        $billet->setCommande($this);

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
     * @return Billet[]
     */
    public function getBillets()
    {
        return $this->billets;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Commande
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
    
    public function estGratuit(){
        return $this->tarif == 0;
    }
}

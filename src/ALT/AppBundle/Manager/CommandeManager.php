<?php
/**
 * Created by PhpStorm.
 * User: Anne-Laure
 * Date: 19/04/2017
 * Time: 15:43
 */

namespace ALT\AppBundle\Manager;


use ALT\AppBundle\Entity\Billet;
use ALT\AppBundle\Entity\Commande;
use ALT\AppBundle\Exception\CommandeNotFoundException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class CommandeManager
{
    const TARIF_NORMAL = 16.00;
    const TARIF_ENFANT = 8.00;
    const TARIF_SENIOR = 12.00;
    const TARIF_REDUIT = 10.00;
    const TARIF_GRATUIT = 0.00;

    /** @var EntityManager */
    private $manager;

    /** @var Session */
    private $session;

    public function __construct(EntityManager $manager, Session $session, $stripeApiKey)
    {
        $this->manager = $manager;
        $this->session = $session;
        \Stripe\Stripe::setApiKey($stripeApiKey);
    }

    public function preparerBillets(Commande $commande)
    {
        $billets = $commande->getBillets();
        if (count($billets) > $commande->getNbBillets()) {
            $billetsASupprimer = $billets->slice($commande->getNbBillets());

            // On les supprime l'un après l'autre de la commande
            foreach ($billetsASupprimer as $billetASupprimer) {
                $commande->removeBillet($billetASupprimer);
            }
        }

        if (count($billets) < $commande->getNbBillets()) {
            for ($i = count($billets); $i < $commande->getNbBillets(); $i++) {
                $commande->addBillet(new Billet());
            }
        }

        return $commande;
    }


    /**
     * Calcul le tarif d'une commande ainsi que de ces billets
     * 
     * @param Commande $commande
     * @return Commande
     */
    public function calculerTarif(Commande $commande)
    {

        foreach ($commande->getBillets() as $billet) {
            $this->calculerTarifBillet($billet);
        }

        $this->recalculerCommande($commande);

        return $commande;
    }

    /**
     * Calcul le tarif d'un seul billet.
     *
     * @param Billet $billet
     */
    private function calculerTarifBillet(Billet $billet)
    {
        $now = new \DateTime();
        $diff = $now->diff($billet->getDateNaissance());

        $age = $diff->days / 365;

        $tarif = self::TARIF_NORMAL;
        switch (true) {
            case $age < 4:
                $tarif = self::TARIF_GRATUIT;
                break;
            case $age <= 12:
                $tarif = self::TARIF_ENFANT;
                break;
            case $age > 60:
                $tarif = self::TARIF_SENIOR;
                break;
        }

        if ($tarif > self::TARIF_REDUIT) {
            if ($billet->getTarifReduit()) {
                $tarif = self::TARIF_REDUIT;
            }
        }
        
        if ($billet->getCommande()->getType() == Commande::DEMI_JOURNEE) {
            $tarif = $tarif / 2;
        }

        $billet->setTarif($tarif);
    }

    /**
     * Recalcule le montant total de la commande.
     *
     * @param Commande $commande
     */
    private function recalculerCommande(Commande $commande)
    {
        $total = 0.00;
        foreach ($commande->getBillets() as $billet) {
            $total += $billet->getTarif();
        }

        $commande->setTarif($total);
    }


    public function fairePayer(Commande $commande, $token)
    {
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => $commande->getTarif() * 100, // montant en centimes !
                "currency" => "eur",
                "description" => "De l'argent qui rentre chez Anne Laure ;)",
                "source" => $token,
            ));
        } catch (\Exception $e) {
            return false;
        }

        $this->manager->persist($commande);
        $this->manager->flush();
              
        return true;
    }

    public function faireGratuit(Commande $commande)
    {
        $this->manager->persist($commande);
        $this->manager->flush();
    }

    public function getCommande()
    {
        $commande = $this->session->get('commande');
        if ($commande == null) { // Si l'objet n'existe pas, on retourne sur la page d'accueil !!
            throw new CommandeNotFoundException();
        }

        return $commande;
    }

    public function getCommandeOuCreerUneNouvelle()
    {
        try {
            $commande = $this->getCommande();
        } catch (CommandeNotFoundException $e) {
            $commande = new Commande(); // Si la commande n'est pas en session, on en créer une nouvelle
        }

        return $commande;
    }

    public function stockEnSession(Commande $commande)
    {
        $this->session->set('commande', $commande);
    }

    public function retireDeLaSession()
    {
        $this->session->set('commande', null);
    }
}
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

    /**
     * Prépare le nombre exact d'entité Billet dans l'entité Commande.
     *
     * @param Commande $commande
     * @return Commande
     */
    public function preparerBillets(Commande $commande)
    {
        $billets = $commande->getBillets();

        // si le nombre de billets demandé à la première page est inférieur aux nombres d'entités Billet dans l'entité Commande
        // Alors, on supprime le surplus d'entités Billet dans l'entité Commande.
        if (count($billets) > $commande->getNbBillets()) {
            $billetsASupprimer = $billets->slice($commande->getNbBillets());

            // On les supprime l'un après l'autre de la commande
            foreach ($billetsASupprimer as $billetASupprimer) {
                $commande->removeBillet($billetASupprimer);
            }
        }

        $nbBillet=count($billets);
        if ($nbBillet < $commande->getNbBillets()) {
            for ($i = $nbBillet; $i < $commande->getNbBillets(); $i++) {
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

        $totalCommande = 0.00;

        foreach ($commande->getBillets() as $billet) {
            $this->calculerTarifBillet($billet);
            $totalCommande += $billet->getTarif();
        }

        $commande->setTarif($totalCommande);

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
            // Dans l'idéal, on pourrait "logger" l'erreur mais on va pas le faire ici pour l'exemple ;)
            return false;
        }

        $this->creerCodeReservation($commande);
        $commande->setDateFacturation(new \DateTime());
        $commande->setNumeroTransaction($charge['balance_transaction']);

        $this->manager->persist($commande);
        $this->manager->flush();
              
        return true;
    }

    public function faireGratuit(Commande $commande)
    {
        $this->creerCodeReservation($commande);
        $commande->setDateFacturation(new \DateTime());

        $this->manager->persist($commande);
        $this->manager->flush();
    }

    private function creerCodeReservation(Commande $commande)
    {
        do {
            //Génération code unique
            $code = uniqid('abcdefghijklmnopqrstuvwxyz'); // prends l'alphabet + nombre aléatoire
            $code = str_shuffle($code); // on rends aléatoire le résultat obtenu par uniqid()
            $code = substr($code, 0, 7); // on prends les 7 premiers caractères

            // Vérification si ce code n'existe pas en base de données
            $qbu = $this->manager->getRepository('ALTAppBundle:Commande')->createQueryBuilder('c');
            $qbu
                ->select('COUNT(c.codeReservation)')
                ->where('c.codeReservation = :code')
                ->setParameter('code', $code);

            $result = $qbu->getQuery()->getSingleScalarResult();

        } while ($result != 0); // Si ce code est déjà existant, il faut en regénérer un nouveau et recommencer ce qu'il y a plus haut.

        $commande->setCodeReservation($code);
    }

    /**
     * Récupère la commande depuis la session
     *
     * @return Commande
     * @throws CommandeNotFoundException
     */
    public function getCommande()
    {
        $commande = $this->session->get('commande');
        if ($commande === null) { // Si l'objet n'existe pas, on retourne sur la page d'accueil !!
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
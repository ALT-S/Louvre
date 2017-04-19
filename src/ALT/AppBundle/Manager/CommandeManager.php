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

class CommandeManager
{
    const TARIF_NORMAL = 16.00;
    const TARIF_ENFANT = 8.00;
    const TARIF_SENIOR = 12.00;
    const TARIF_REDUIT = 10.00;
    const TARIF_GRATUIT = 0.00;

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
}
<?php

namespace ALT\AppBundle\Manager;

use ALT\AppBundle\Entity\Billet;
use ALT\AppBundle\Entity\Commande;

class BilletManager
{

    const TARIF_NORMAL = 16.00;
    const TARIF_ENFANT = 8.00;
    const TARIF_SENIOR = 12.00;
    const TARIF_REDUIT = 10.00;
    const TARIF_GRATUIT = 0.00;

    public function calculerTarif(Billet $billet)
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

        $this->recalculerCommande($billet->getCommande());

        return $billet;
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
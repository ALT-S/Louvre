<?php

namespace ALT\AppBundle\Validator;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class NonReservationDatesValidator extends ConstraintValidator
{

    /**
     * Retourne la liste des jours/mois qui sont fermés.
     * 
     * @return array
     */
    static public function getJoursFermes()
    {
        return ['25-12', '01-05', '01-11', '01-01', '01-05', '08-05', '14-07', '15-08', '11-11'];
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {

        $jourmois = $value->format('d-m');
        if (in_array($jourmois, self::getJoursFermes())) {
            $this->context->addViolation('Le Musée du Louvre est fermé les jours fériés ');
        }

        // vérification si on est sur un jour férié pour Paques
        $datePaques = easter_date($value->format('Y'));
        $date = new \DateTime();
        $date->setTimestamp($datePaques);
        $date->modify('+1 day');
        if ($value->format('d-m') == $date->format('d-m')) {
            $this->context->addViolation('Le Musée du Louvre est fermé pour Paques ');
        }

        $date->modify('+38 day'); // Ascension
        if ($value->format('d-m') == $date->format('d-m')) {
            $this->context->addViolation('Le Musée du Louvre est fermé pour l\'Ascension ');
        }

        $date->modify('+11 day'); // Pentecote
        if ($value->format('d-m') == $date->format('d-m')) {
            $this->context->addViolation('Le Musée du Louvre est fermé pour la Pentecôte ');
        }


        // vérification dimanche & mardi
        // savoir si le numéro du jour dans la semaine vaut (7 = dimanche, 2 mardi)
        $jourDeLaSemaine = $value->format('N');
        if ($jourDeLaSemaine == 7 || $jourDeLaSemaine == 2) {
            $this->context->addViolation('Le Musée du Louvre est fermé les mardi et dimanche');
        }

        // si la journée est passée
        // Si la date est < now()
        $now = new \DateTime();
        $now->setTime(0,0,0);

        if ($value < $now) {
            $this->context->addViolation("Il n'est pas possible de commander pour une date antérieur à celle du jour");
        }


    }
}




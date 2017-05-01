<?php

namespace ALT\AppBundle\Validator;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class NonReservationDatesValidator extends ConstraintValidator
{

    static public function getJoursFermes()
    {
        return ['25-12', '01-05', '01-11'];

    }
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {

        $jourmois = $value->format('d-m');
        if (in_array($jourmois, self::getJoursFermes())) {
            $this->context->addViolation('Le Musée du Louvre est fermé les 1er mai, les 11 novembre et les 25 decembre, ');
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




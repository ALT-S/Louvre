<?php

namespace ALT\AppBundle\Validator;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class NonReservationDatesValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {

        $jourmois = $value->format('d-m');
        switch ($jourmois) {
            case '25-12':
            case '01-05':
            case '01-11':
                // ajout violation de contrainte
                $this->context->addViolation($constraint->message);

                break;
        }

        // vérification dimanche & mardi
        // savoir si le numéro du jour dans la semaine vaut (7 = dimanche, 2 mardi)
        $jourDeLaSemaine = $value->format('N');
        if ($jourDeLaSemaine == 7 || $jourDeLaSemaine == 2) {
            $this->context->addViolation($constraint->message);
        }

        // si la journée est passée
        // Si la date est < now()
        $now = new \DateTime();
        $now->setTime(0,0,0);

        if ($value < $now) {
            var_dump('lol');
            $this->context->addViolation($constraint->message);
        }


    }
}




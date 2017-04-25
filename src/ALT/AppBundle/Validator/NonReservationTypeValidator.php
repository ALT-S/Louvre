<?php
/**
 * Created by PhpStorm.
 * User: Anne-Laure
 * Date: 25/04/2017
 * Time: 17:30
 */

namespace ALT\AppBundle\Validator;

use ALT\AppBundle\Entity\Commande;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NonReservationTypeValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {

        // si la c'est le jour même et supérieur à 14h
        // Si la date est = now()
        $now = new \DateTime();

        if ($value->getDateVisite()->format('Y-m-d') != $now->format('Y-m-d') ) {
            return;
        }

        if ($now->format('H') <= 14) {
            return;
        }

        if ($value->getType() == Commande::DEMI_JOURNEE) {
            return;
        }

        $this->context->addViolation($constraint->message);
    }
}



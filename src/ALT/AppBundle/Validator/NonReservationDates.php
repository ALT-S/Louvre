<?php

namespace ALT\AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NonReservationDates extends  Constraint
{
    public $message = "Date indisponible !";

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'alt_app_nonReservationDates'; // Ici, on fait appel à l'alias du service
    }
}


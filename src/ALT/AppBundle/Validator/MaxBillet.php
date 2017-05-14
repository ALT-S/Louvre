<?php

namespace ALT\AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MaxBillet extends  Constraint
{
    public $message = "Désolé, Nous avons atteint le nombre de billets vendus dans la journée ! Il ne reste plus que {{nb}} billets disponible";

    public function validatedBy()
    {
        return 'alt_app_maxBillet'; // Ici, on fait appel à l'alias du service
    }

}

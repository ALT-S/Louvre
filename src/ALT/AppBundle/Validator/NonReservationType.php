<?php
/**
 * Created by PhpStorm.
 * User: Anne-Laure
 * Date: 25/04/2017
 * Time: 17:29
 */

namespace ALT\AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NonReservationType extends  Constraint
{
    public $message = "A partir de 14h, vous ne pouvez commander que des billets demi-journée !";

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'alt_app_nonReservationType'; // Ici, on fait appel à l'alias du service
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}



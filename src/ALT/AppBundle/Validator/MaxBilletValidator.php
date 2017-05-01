<?php
/**
 * Created by PhpStorm.
 * User: Anne-Laure
 * Date: 25/04/2017
 * Time: 15:33
 */

namespace ALT\AppBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MaxBilletValidator extends ConstraintValidator
{
    /** @var EntityManagerInterface */
    private $em;
    private $maxLimit;

    public function __construct(EntityManagerInterface $em, $maxLimit)
    {
        $this->maxLimit = $maxLimit;
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        $now = new \DateTime();

        $nbBillets = $this->em->getRepository("ALTAppBundle:Commande")->getNbBillets($now);

        if ($nbBillets + $value >= $this->maxLimit) {

            // C'est cette ligne qui déclenche l'erreur pour le formulaire, avec en argument le message de la contrainte
            $this->context->addViolation($constraint->message, array("{{nb}}" => $this->maxLimit - $nbBillets));
        }
    }
}



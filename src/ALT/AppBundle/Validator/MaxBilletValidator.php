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
    private $em;

    // Les arguments déclarés dans la définition du service arrivent au constructeur
    // On doit les enregistrer dans l'objet pour pouvoir s'en resservir dans la méthode validate()
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        $now = new \DateTime();

        $nbBillets = $this->em->getRepository("ALTAppBundle:Commande")->getNbBillets($now);
        
        if ($nbBillets > 1000) {

            $resultat = $nbBillets - $value;

            // C'est cette ligne qui déclenche l'erreur pour le formulaire, avec en argument le message de la contrainte
            $this->context->addViolation($constraint->message);
        }
    }
}



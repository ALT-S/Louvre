<?php

namespace ALT\AppBundle\Form;

use ALT\AppBundle\Entity\Commande;
use ALT\AppBundle\Validator\NonReservationDatesValidator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CommandeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $joursFermes = $this->getJoursFermes();


        $builder
            ->add('dateVisite', DateType::class, array(
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker',
                    'data-date-format' => 'dd-mm-yyyy',
                    'data-date-days-of-week-disabled' => '02', // 0 = dimanche / 2 = mardi
                    'data-date-dates-disabled' => '['.implode(', ', $joursFermes).']'
                ],
                'html5' => false,
                'format' => 'dd-MM-yyyy',
            ))
            ->add('type', ChoiceType::class, array(
                'choices'  => array(
                    'Demi-Journée' => Commande::DEMI_JOURNEE,
                    'Journée' => Commande::JOURNEE,
                ),
                'expanded' => true
            ))
            ->add('nbBillets', IntegerType::class)
            ->add('email', EmailType::CLASS)
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ALT\AppBundle\Entity\Commande',
            'validation_groups' => array('commande'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'alt_appbundle_commande';
    }

    /**
     * Récupère la liste des jours fermés.
     *
     * @return array
     */
    private function getJoursFermes()
    {
        $joursFermes = NonReservationDatesValidator::getJoursFermes();

        $year = date('Y');
        $jours = [];
        for ($i = 0; $i < 10; $i++) { // sur 10 ans
            foreach ($joursFermes as $j) {
                $jours[] = '"'.$j.'-'.($year + $i).'"';
            }
        }

        return $jours;
    }

}

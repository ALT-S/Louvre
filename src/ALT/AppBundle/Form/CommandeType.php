<?php

namespace ALT\AppBundle\Form;

use ALT\AppBundle\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateVisite', DateType::class, array(
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker',
                    'data-date-format' => 'dd-mm-yyyy'],
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
            ->add('nbBillets')
            ->add('email')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ALT\AppBundle\Entity\Commande'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'alt_appbundle_commande';
    }


}

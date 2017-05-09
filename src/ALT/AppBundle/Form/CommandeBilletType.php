<?php
/**
 * Created by PhpStorm.
 * User: Anne-Laure
 * Date: 19/04/2017
 * Time: 15:30
 */

namespace ALT\AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeBilletType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('billets', CollectionType::class, array(
                'entry_type'   => BilletType::class,
                'allow_add' => true,
                'allow_delete' => true
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ALT\AppBundle\Entity\Commande',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'alt_appbundle_commandebillet';
    }


}

<?php

namespace AppBundle\Form\Crep\CrepAc;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\ObjectifFuturParentType;

class ObjectifFuturIndividuelType extends ObjectifFuturParentType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('indicateurs', null, [
            'attr' => ['class' => 'fieldCollection',
                'maxlength' => '4096', ], ])
                ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Crep\CrepAc\ObjectifFuturIndividuel',
        ));
    }
}

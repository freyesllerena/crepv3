<?php

namespace AppBundle\Form\Crep\CrepMj01\Competences;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CrepMj01CompetenceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('niveau', ChoiceType::class, [
                                        'choices' => [5, 4, 3, 2, 1, 0],
                                        'expanded' => true,
                                        'multiple' => false,
                                    ])
                ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Crep\CrepMj01\CrepMj01Competence',
        ));
    }
}

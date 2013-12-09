<?php

namespace Michael\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AuthorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', 'text');
        $builder->add('lastName', 'text');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'Michael\\AppBundle\\Entity\\Author',
            'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'author';
    }
}

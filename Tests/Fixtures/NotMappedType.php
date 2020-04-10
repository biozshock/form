<?php


namespace Symfony\Component\Form\Tests\Fixtures;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotMappedType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('mapped', false);
    }
}
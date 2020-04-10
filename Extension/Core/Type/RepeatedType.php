<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Extension\Core\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\Extension\Core\DataTransformer\ValueToDuplicatesTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepeatedType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Overwrite required option for child fields
        $options['first_options']['required'] = $options['required'];
        $options['second_options']['required'] = $options['required'];

        if (!isset($options['options']['error_bubbling'])) {
            $options['options']['error_bubbling'] = $options['error_bubbling'];
        }

        $builder
            ->addViewTransformer(new ValueToDuplicatesTransformer([
                $options['first_name'],
                $options['second_name'],
            ]))
            ->add($options['first_name'], $options['type'], $this->mergeOptions($options['options'], $options['first_options']))
            ->add($options['second_name'], $options['type'], $this->mergeOptions($options['options'], $options['second_options']))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'type' => TextType::class,
            'options' => [],
            'first_options' => [],
            'second_options' => [],
            'first_name' => 'first',
            'second_name' => 'second',
            'error_bubbling' => false,
        ]);

        $resolver->setAllowedTypes('options', 'array');
        $resolver->setAllowedTypes('first_options', 'array');
        $resolver->setAllowedTypes('second_options', 'array');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'repeated';
    }

    private function mergeOptions(array $fieldOptions, array $innerOptions)
    {
        $mergedOptions = array_merge($fieldOptions, $innerOptions);

        if (array_key_exists('mapped', $mergedOptions)) {
            if ($mergedOptions['mapped'] === false) {
                throw new InvalidConfigurationException('Inner types must be mapped');
            }
        } else {
            $mergedOptions['mapped'] = true;
        }

        return $mergedOptions;
    }
}

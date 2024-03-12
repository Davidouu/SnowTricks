<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class VideoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', null, [
                'label' => 'URL',
                'label_attr' => [
                    'class' => 'lock text-sm text-gray-500 dark:text-gray-300',
                ],
                'mapped' => true,
                'required' => false,
                'attr' => [
                    'class' => 'input-primary',
                    'placeholder' => 'https://www.youtube.com/embed/...',
                    'maxlength' => 10000,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}

<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ImageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'label' => 'Image',
                'label_attr' => [
                    'class' => 'lock text-sm text-gray-500 dark:text-gray-300',
                ],
                'mapped' => true,
                'required' => false,
                'multiple' => false,
                'attr' => [
                    'accept' => 'image/*',
                    'class' => 'file-primary',
                ],
                'constraints' => [
                    new Assert\Image([
                        'maxSize' => '5M',
                        'maxSizeMessage' => 'Le fichier est trop lourd ({{ size }} {{ suffix }}). La taille maximum autorisée est de {{ limit }} {{ suffix }}.',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Le fichier n\'est pas une image valide.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}

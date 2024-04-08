<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\CallbackTransformer;

class CommentsFormType extends AbstractType
{
    public function __construct(
        private readonly RouterInterface $router, 
        private readonly Security $security, 
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setAction($this->generateUrl($options['action'], $options['id'] ?? []))
            ->setMethod('POST')
            ->add('message', TextareaType::class, [
                'label' => 'Comment',
                'attr' => ['rows' => 5],
            ])
            ->add('trick', HiddenType::class, [
                'data' => $options['trickId'],
            ])
            ->add('user', HiddenType::class, [
                'data' => $this->security->getUser()?->getId() ?? null,
            ]);
            $builder->get('user')->addModelTransformer(new CallbackTransformer(
                function ($user): int|null {
                    return $user;
                },
                function ($userId): User {
                    return $this->entityManager->getReference(User::class, $userId);
                }
            ));
            $builder->get('trick')->addModelTransformer(new CallbackTransformer(
                function ($trick) {
                    return $trick;
                },
                function ($trickId): Trick {
                    return $this->entityManager->getReference(Trick::class, $trickId);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'trickId' => null,
            'id' => null,
        ]);
    }

    private function generateUrl(string $route, array $parameters = []): string
    {
        return $this->router->generate($route, $parameters);
    }
}

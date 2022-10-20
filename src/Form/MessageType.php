<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title_message', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Titre du message',
                ],
            ])
            ->add('content_message', TextareaType::class, [
                'label' => 'Message'
            ])
            ->add('recipient', EntityType::class, [
                "class" => User::class,
                "choice_label" => "pseudo",
                'label' => 'A qui ?',
                'attr' => [
                    'placeholder' => 'Titre de l\'évènement',
                ],
            ])
            ->add('envoyer', SubmitType::class, [
                "attr" => [
                    "class" => "btn btn-secondary"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}

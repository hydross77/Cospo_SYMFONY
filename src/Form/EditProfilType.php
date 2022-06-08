<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Sport;
use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('bio')
            ->add('picture_profil', FileType::class, [
                'label' => 'Photo de profil',
                'mapped' => false,
                'required' => false,
                // 'constraints' => [
                //     new File([
                //         'maxSize' => '1024k',
                //         'mimeTypes' => [
                //             'application/jpg',
                //             'application/x-jpg',
                //         ],

                //     ])
                // ],
            ])

            ->add('sport', EntityType::class, [
                'class' => Sport::class,
                'choice_label' => 'title_sport',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('sex', ChoiceType::class, [
                'required' => true,
                'label' => 'Etes-vous un homme ou une femme?',
                'choices' => [
                    'Femme' => 'Femme',
                    'Homme' => 'Homme',
                    'Non defini' => 'Non defini',
                    'Non binaire' => 'Non binaire',
                    'Binaire' => 'Binaire',
                ],
            ])
            ->add('pseudo', TextType::class, [
                'required' => false,
                'label' => 'Changer votre pseudo',

            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

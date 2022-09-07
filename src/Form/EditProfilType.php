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

            ->add('bio', TextType::class, [
                'required' => false,
                'label' => 'Changer la biographie',
            ])
            ->add('picture_profil', FileType::class, [
                'label' => 'Changer la photo profil',
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
                'label' => 'Vous pouvez cocher de nouveaux sports ou en retirer!',
                'choice_label' => 'title_sport',
                'multiple' => true,
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

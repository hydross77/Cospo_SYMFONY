<?php

namespace App\Form;

use App\Entity\Level;
use App\Entity\Sport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

// l'extension permet de faire savoir a Symfony que la class est un formulaire
class SearchForm extends AbstractType
{

    //construction du formulaire
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Pseudo...'
                ]
            ])
            ->add('ville', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ville...'
                ]
            ])
            ->add('sport', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Sport::class,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'title_sport',
                'choice_value' => 'id',
            ])
            ->add('level', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Level::class,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'title_level',
                'choice_value' => 'id',
            ])
            ->add('date', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'required' => false,
            ]);
    }

    //configuration du formulaire
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}

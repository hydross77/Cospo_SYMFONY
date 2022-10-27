<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Level;
use App\Entity\Sport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title_event', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Titre de l\'évènement',
                ],
            ])
            ->add('nb_places', NumberType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Veuillez entrez le nombre de place en chiffre..',
                ],
            ])
            ->add('content_event', TextareaType::class, [
                'required' => true,
                'label' => 'Description de l\'évènement',
                'attr' => [
                    'placeholder' => 'Exemple : Pour un cours de Yoga en plein air, n\'oubliez pas de ramener vos tapis ainsi qu\'une bouteille d\'eau',
                ],
            ])
            ->add('date_event', DateTimeType::class, [
                'label' => 'Date et Heure de l\'évènement',
                "widget" => "single_text"
            ])
            ->add('cp', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Code postal..',
                ],
            ])
            ->add('ville', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ville de l\'évènement',
                ],
            ])
            ->add('adresse', TextType::class, [
                'required' => false,
                'label' => 'Adresse',
                'help' => 'Vous pouvez renseigner l\'adresse via la messagerie',
                'attr' => [
                    'placeholder' => 'L\'adresse n\'est pas obligatoire',
                ],
            ])
            ->add('level', EntityType::class, [
                'class' => Level::class,
                'choice_label' => 'title_level',
                'label' => 'Veuillez indiquez le niveau requis',
            ])
            ->add('sport', EntityType::class, [
                'class' => Sport::class,
                'choice_label' => 'title_sport',
                'label' => 'Veuillez indiquez le sport de cet évènement',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}

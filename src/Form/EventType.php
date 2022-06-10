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

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title_event', TextType::class)
            ->add('nb_places', NumberType::class)
            ->add('content_event', TextType::class)
            ->add('date_event', DateTimeType::class, [
                "widget" => "single_text"
            ])
            ->add('cp', TextType::class)
            ->add('ville', TextType::class)
            ->add('adresse', TextType::class)
            ->add('level', EntityType::class, [
                'class' => Level::class,
                'choice_label' => 'title_level'
            ])
            ->add('sport', EntityType::class, [
                'class' => Sport::class,
                'choice_label' => 'title_sport'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}

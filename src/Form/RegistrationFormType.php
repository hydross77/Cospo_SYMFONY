<?php

namespace App\Form;

use App\Entity\Sport;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

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
                    'Autre' => 'Autre',
                ],
            ])
            ->add('birthday', BirthdayType::class, [
                'required' => true,
                'label' => 'Quel âge avez-vous ?',
            ])
            ->add('pseudo', TextType::class, [
                'required' => true,
                'label' => 'Crée un nom d\'utilisateur',
                'help' => 'C\'est le nom que les autres voient. Cela peut simplement être votre prénom.',
                'attr' => [
                    'placeholder' => 'Entrez votre nom ici',
                ],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le pseudo doit contenir entre 6 et 30 caractères',
                        'max' => 30,
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Votre adresse email',
                'help' => 'Votre adresse email reste invisible aux autres utilisateurs.',
                'attr' => [
                    'placeholder' => 'Entrez votre adresse email ici',
                ],
            ])

            ->add('agreeTerms', CheckboxType::class, [
                //'label' => 'Accepter les <a href="{{ path(\'app_cgu\') }}" class="cguRegister">Condition générale d\'utilisation </a>',
                'label' => 'Accepter les <a href="' . $options['cgu'] . '" style="color:black;" class="cguRegister">Condition générale d\'utilisation </a>',
                'label_html' => true,
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => true,
                'first_options' => ['label' => 'Mot de passe : ', 'attr' => ['class' => 'input-full'], 'constraints' => [
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                        'match' => true,
                        'message' => 'Le mot de passe doit contenir : minimum 8 caractère, un nombre, une minuscule, une majuscule et un caractère spécial',
                    ]),
                ]],

                'second_options' => ['label' => 'Répéter le mot de passe : ', 'attr' => ['class' => 'input-full'], 'constraints' => [
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                        'match' => true,
                        'message' => 'Le mot de passe doit contenir : minimum 8 caractère, un nombre, une minuscule, une majuscule et un caractère spécial',
                    ]),
                ]],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'cgu' => null,
        ]);
    }
}

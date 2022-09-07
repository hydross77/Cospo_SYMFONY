<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class EditRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => false,
                'label' => 'Veuillez renseigner votre nouvelle adresse e-mail',
                'attr' => [
                    'placeholder' => 'cospo@cospo.com',
                ],
            ])

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => ['label' => 'Nouveau mot de passe : ', 'attr' => ['class' => 'input-full'], 'constraints' => [
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                        'match' => true,
                        'message' => 'Le mot de passe doit contenir : minimum 8 caractère, un nombre, une minuscule, une majuscule et un caractère spécial',
                    ]),
                ]],

                'second_options' => ['label' => 'Répéter le nouveau mot de passe : ', 'attr' => ['class' => 'input-full'], 'constraints' => [
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
        ]);
    }
}

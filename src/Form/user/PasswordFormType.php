<?php

namespace App\Form\user;

use App\Domain\Auth\User;
use App\Domain\profile\Dto\ProfileUpdateRequestData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints as Assert;

class PasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword',PasswordType::class,[
                'label' => "Entrer votre ancienne mot de passe",
            ])
            ->add('password',RepeatedType::class,[
                "type" =>PasswordType::class,
                "invalid_message" => 'Le mot de passe doit correspondre',
                'required' => true,
                'first_options'  => ['label' => 'Entrer votre nouveau mot de passe'],
                'second_options' => ['label' => 'Confimer votre nouveau mot de passe']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProfileUpdateRequestData::class,
        ]);
    }
}
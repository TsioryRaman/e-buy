<?php

namespace App\Form\user;

use App\Domain\Auth\User;
use App\Domain\profile\Dto\ProfileUpdateRequestData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvatarFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('avatar',FileType::class,[
                'label' => '',
                'label_attr' => [
                    'style' => 'display:none'
                ],
                'attr' => [
                    'class' => 'hidden'
                ]
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

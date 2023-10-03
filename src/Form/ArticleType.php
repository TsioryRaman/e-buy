<?php

namespace App\Form;

use App\Domain\Article\Article;
use App\Domain\Fournisseur\Entity\Fournisseur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('address')
            ->add('postalCode')
            ->add('sold')
            ->add('quantity')
            ->add('imageFile',FileType::class,[
                "required" => false
                ])
            ->add("fournisseur",EntityType::class,[
                    'class' => Fournisseur::class,
                    'choice_label' => 'name',
                    'multiple' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'translation_domain' => 'forms',
        ]); 
    }
}

<?php

namespace App\Form;

use App\Domain\Article\Article;
use App\Domain\Category;
use App\Domain\Fournisseur\Entity\Fournisseur;
use App\Form\Type\SwitchType;
use App\Http\Admin\Data\ArticleCrudData;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description',TextareaType::class)
            ->add('price')
            ->add('address')
            ->add('postalCode')
            ->add('sold',SwitchType::class)
            ->add('quantity')
            ->add('brand',null,[
                'label' => 'Marque'
            ])
            ->add('category',EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => false
            ])

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
            'data_class' => ArticleCrudData::class,
            'translation_domain' => 'forms',
        ]); 
    }
}

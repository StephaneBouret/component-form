<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la catégorie',
                'attr' => [
                    'placeholder' => 'tapez le nom de la catégorie'
                ]
            ])
            // ->add('products', EntityType::class, [
            //     'class' => Product::class,
            //     'choice_label' => 'name',
            //     'multiple' => true,
            //     'expanded' => true, // Permet d'afficher les cases à cocher
            //     'by_reference' => false, // Assure que les changements sont bien pris en compte
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}

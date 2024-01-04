<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $types = ['Article', 'Accessoire'];
        $builder
            // ->add('name', TextType::class, [
            //     'label' => 'Nom du produit',
            //     'attr' => [
            //         'placeholder' => 'Tapez le nom du produit'
            //     ],
            // ])
            ->add('name', NameType::class, [
                'data_class' => Product::class,
                'label' => false
            ])
            ->add('type', ChoiceType::class, [
                'choices' => array_combine($types, $types)
            ])
            // ->add('shortDescription', TextareaType::class, [
            //     'label' => 'Description courte',
            //     'attr' => [
            //         'placeholder' => 'Tapez une description assez courte mais parlante pour le visiteur'
            //     ]
            // ])
            ->add('shortDescription', null, [
                'rows' => 5
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix du produit',
                'attr' => [
                    'placeholder' => 'Tapez le prix du produit en euros'
                ],
                // 'divide' => false
                'divisor' => 100,
            ])
            ->add('mainPicture', UrlType::class, [
                'label' => 'Image du produit',
                'attr' => [
                    'placeholder' => 'Tapez une URL d\'image'
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'placeholder' => '-- Choisir une catégorie --',
                'class' => Category::class,
                'choice_label' => function (Category $category) {
                    return $category->getName();
                }
            ]);

        // $builder->get('price')->addModelTransformer(new CentimesTransformer);

        // $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
        //     /** @var Product */
        //     $product = $event->getData();

        //     if ($product->getPrice() !== null) {
        //         $product->setPrice($product->getPrice() * 100);
        //     }
        // });

        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
        //     $form = $event->getForm();
        //     /** @var Product */
        //     $product = $event->getData();

        //     if ($product->getPrice() !== null) {
        //         $product->setPrice($product->getPrice() / 100);
        //     }
        //     // dd($product);
        // });

        // Écouteur d'événements pour conditionner l'affichage du champ 'state'
        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        //     $form = $event->getForm();
        //     /** @var Product */
        //     $product = $event->getData();
        //     $state = ['Neuf', 'Très bon état', 'Occasion'];

        //     // Si le produit est nouvellement créé, on utilise le type par défaut
        //     if (!$product || null === $product->getId()) {
        //         $type = 'Article';
        //     } else {
        //         // Sinon, on utilise le type du produit existant
        //         $type = $product->getType();
        //     }

        //     // En fonction du type et du statut de création, on décide quels champs afficher
        //     if ($type === 'Article' && $form->has('state')) {
        //         $form->remove('state');
        //     } elseif ($type === 'Accessoire' && !$form->has('state')) {
        //         $form->add('state', ChoiceType::class, [
        //             'label' => 'Etat',
        //             'attr' => ['class' => 'form-control'],
        //             'placeholder' => '--Choisir un état--',
        //             'required' => false,
        //             'choices' => array_combine($state, $state)
        //         ]);
        //     }
        // });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

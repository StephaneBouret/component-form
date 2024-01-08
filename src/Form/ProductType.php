<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => [
                    'placeholder' => 'Tapez le nom du produit'
                ],
            ])
            // ->add('name', NameType::class, [
            //     'data_class' => Product::class,
            //     'label' => false
            // ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'Description courte',
                'attr' => [
                    'placeholder' => 'Tapez une description assez courte mais parlante pour le visiteur'
                ]
            ])
            // ->add('shortDescription', null, [
            //     'rows' => 5
            // ])
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
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Article' => 'Article',
                    'Accessoire' => 'Accessoire'
                ],
                'required' => false,
                'label' => 'Type',
                'placeholder' => '--Choisir un type--',
            ]);

            $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                /** @var Product|null $data */
                $data = $event->getData();
                if (!$data) {
                    return;
                }

                $this->setupSpecificStatusNameField(
                    $event->getForm(),
                    $data->getType()
                );
            });

        // if ($type) {
        //     $builder->add('statut', ChoiceType::class, [
        //         'choices' => $this->getStatusNameChoices($type),
        //         'required' => false,
        //         'label' => 'Statut',
        //         'placeholder' => '--Choisir un statut--',
        //     ]);
        // }

        $builder->get('type')->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) {
            $form = $event->getForm();
            $this->setupSpecificStatusNameField(
                $form->getParent(),
                $form->getData()
            );
        });
    }

    private function setupSpecificStatusNameField(FormInterface $form, ?string $type)
    {
        if (null === $type) {
            $form->remove('statut');
            return;
        }

        $choices = $this->getStatusNameChoices($type);

        if (null === $choices) {
            $form->remove('statut');
            return;
        }

        $form->add('statut', ChoiceType::class, [
            'label' => 'Statut',
            'placeholder' => '--Choisir un statut--',
            'choices' => $choices,
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }

    private function getStatusNameChoices(string $type)
    {
        $status = [
            'Neuf',
            'Bon état',
            'Occasion',
            'Abimé',
        ];

        $typeNameChoices = [
            'Accessoire' => array_combine($status, $status),
            'Article' => null
        ];

        return $typeNameChoices[$type] ?? null;
    }
}

<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom'
            ])
         //   ->add('slug')
            ->add('description', null, [
                'label' => 'Description'
         ])
            ->add('price', null, [
                'label' => 'Prix'
            ])
           //->add('creation_date')
            ->add('crush', null, [
                'label' => 'Coup de cœur'
           ])
            ->add('color_list', null, [
                'label' => 'Couleur(s) disponible(s)'
            ])
            ->add('color_list', ChoiceType::class, [
                'choices' => [
                    'Jaune' => 'Jaune',
                    'Bleu' => 'Bleu',
                    'Rouge' => 'Rouge',
                    'Orange' => 'Orange',
                    'Vert' => 'Vert',
                ],
                'label' =>'Couleur(s) disponible(s)',
                 'expanded'  => true,
                'multiple'  => true,
            ])

            ->add('url_image', null, [
                'label' => 'URL de l\'image du produit'
            ])
            ->add('special_offer', null, [
                'label' => 'Promotion (valeur du prix en pourcentage du prix total)'
            ])
            ->add('category', null, [
                'label' => 'Catégorie',
                'choice_label' => 'name',
                'expanded' => true, // on crée des input radio
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

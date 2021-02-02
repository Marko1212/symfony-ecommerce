<?php

namespace App\Form;

use App\Entity\Product;
use Faker\Provider\ar_SA\Color;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
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
                'choices' => array_map(function ($color) {
                    return [$color => $color];
                }, [
                    'AliceBlue', 'AntiqueWhite', 'Aqua', 'Aquamarine',
                    'Azure', 'Beige', 'Bisque', 'Black', 'BlanchedAlmond',
                    'Blue', 'BlueViolet', 'Brown', 'BurlyWood', 'CadetBlue',
                    'Chartreuse', 'Chocolate', 'Coral', 'CornflowerBlue',
                    'Cornsilk', 'Crimson', 'Cyan', 'DarkBlue', 'DarkCyan',
                    'DarkGoldenRod', 'DarkGray', 'DarkGreen', 'DarkKhaki',
                    'DarkMagenta', 'DarkOliveGreen', 'Darkorange', 'DarkOrchid',
                    'DarkRed', 'DarkSalmon', 'DarkSeaGreen', 'DarkSlateBlue',
                    'DarkSlateGray', 'DarkTurquoise', 'DarkViolet', 'DeepPink',
                    'DeepSkyBlue', 'DimGray', 'DimGrey', 'DodgerBlue', 'FireBrick',
                    'FloralWhite', 'ForestGreen', 'Fuchsia', 'Gainsboro', 'GhostWhite',
                    'Gold', 'GoldenRod', 'Gray', 'Green', 'GreenYellow', 'HoneyDew',
                    'HotPink', 'IndianRed', 'Indigo', 'Ivory', 'Khaki', 'Lavender',
                    'LavenderBlush', 'LawnGreen', 'LemonChiffon', 'LightBlue', 'LightCoral',
                    'LightCyan', 'LightGoldenRodYellow', 'LightGray', 'LightGreen', 'LightPink',
                    'LightSalmon', 'LightSeaGreen', 'LightSkyBlue', 'LightSlateGray', 'LightSteelBlue',
                    'LightYellow', 'Lime', 'LimeGreen', 'Linen', 'Magenta', 'Maroon', 'MediumAquaMarine',
                    'MediumBlue', 'MediumOrchid', 'MediumPurple', 'MediumSeaGreen', 'MediumSlateBlue',
                    'MediumSpringGreen', 'MediumTurquoise', 'MediumVioletRed', 'MidnightBlue',
                    'MintCream', 'MistyRose', 'Moccasin', 'NavajoWhite', 'Navy', 'OldLace', 'Olive',
                    'OliveDrab', 'Orange', 'OrangeRed', 'Orchid', 'PaleGoldenRod', 'PaleGreen',
                    'PaleTurquoise', 'PaleVioletRed', 'PapayaWhip', 'PeachPuff', 'Peru', 'Pink', 'Plum',
                    'PowderBlue', 'Purple', 'Red', 'RosyBrown', 'RoyalBlue', 'SaddleBrown', 'Salmon',
                    'SandyBrown', 'SeaGreen', 'SeaShell', 'Sienna', 'Silver', 'SkyBlue', 'SlateBlue',
                    'SlateGray', 'Snow', 'SpringGreen', 'SteelBlue', 'Tan', 'Teal', 'Thistle', 'Tomato',
                    'Turquoise', 'Violet', 'Wheat', 'White', 'WhiteSmoke', 'Yellow', 'YellowGreen',
                ]),
                'label' =>'Couleur(s) disponible(s)',
                 'expanded'  => true,
                'multiple'  => true,
            ])

            ->add('url_image', null, [
                'label' => 'URL de l\'image du produit'
            ])
            ->add('special_offer', null, [
                'label' => 'Promotion (pourcentage)'
            ])
            ->add('category', null, [
                'label' => 'Catégorie',
                'choice_label' => 'name',
                'expanded' => true, // on crée des input radio
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

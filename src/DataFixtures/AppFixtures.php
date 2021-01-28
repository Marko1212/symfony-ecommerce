<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager)
    {
        // On crée une instance de Faker pour générer la donnée aléatoire
        $faker = Factory::create('fr_FR');
        $categoryNames = ['multimédia', 'jouets pour animaux', 'bricolage', 'jardinage', 'papeterie'];
        foreach($categoryNames as $index => $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $this->addReference('cat-'.$index, $category);
            $manager->persist($category);
        }

        /* #DEBUT [GENERATION DES FIXTURES] */
            foreach($i = 1; $i <= 150; $i++){
                $product = new Product();

                /* #DEBUT [GENERATION DES DONNEES FAKE] */
                    $category = $this->getReference('cat-'.rand(0, count($categoryNames) - 1));
                    $name = $faker->sentence($nbWors = 4, true);
                    $description = $faker->paragraph($nb = 3, false);
                    $price = $faker->numberBetween($min = 5, $max = 2000);
                    $date = 
                /* #FIN [GENERATION DES DONNEES FAKE] */

                /* #DEBUT [SETTING DES DONNEES FAKE] */
                    $category->setSlug($this->slugger->slug($name));
                /* #FIN [SETTING DES DONNEES FAKE] */


            }
         /* #FIN [GENERATION DES FIXTURES] */



        $manager->flush();
    }
}

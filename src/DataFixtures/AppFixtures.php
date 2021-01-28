<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Review;
use DateTime;
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

       /* #DEBUT [GENERATION DES FIXTURES PRODUCT] */
            for($i = 1; $i <= 150; $i++){
                $product = new Product();


                /* #DEBUT [GENERATION DES DONNEES PRODUCTS] */
                    $category = $this->getReference('cat-'.rand(0, count($categoryNames) - 1));
                    $name = $faker->sentence($nbWords = 4, true);
                    $description = $faker->paragraph($nb = 3, false);
                    $price = $faker->numberBetween($min = 5, $max = 2000);
                    $dateProduct = $faker->unixTime($max ='now');
                    $crush = $faker->boolean(10); 
                    for($i = 1; $i <= $faker->randomDigitNotNull(); $i++){
                        $colors[] = $faker->safeColorName();
                    }
                    $image = $faker->randomElement([
                        'default.jfif', 'fixtures/animalerie.jpg', 'fixtures/bricolage.jfif', 'fixtures/jardinage.jfif','fixtures/multimedia.jfif', 'fixtures/papeterie.jfif'
                    ]);
                    if($faker->boolean(15)){
                        $specialOffer = $faker->numberBetween(10, 80);
                    }

                    /* #DEBUT [GENERATION DES FIXTURES REVIEWS] */
                    for($i = 0; $i <= $faker->randomDigit(); $i++) {
                        $review = new Review();

                        /* #DEBUT [GENERATION DES DONNEES REVIEWS] */
                        $username = $faker->firstName();
                        $dateReview = $faker->unixTime();
                        $mark = $faker->numberBetween(1, 5);
                        $comment = $faker->text(150);

                        /* #FIN [GENERATION DES DONNEES REVIEWS] */

                        /* #DEBUT [SETTING DES REVIEWS] */
                         $review->setProduct($product);
                         $review->setUsername($username);
                         $review->setCreationReview($dateReview);
                         $review->setMark($mark);
                         $review->setComment($comment);
                         $manager->persist($review);
                        /* #FIN [SETTING DES REVIEWS] */

                    }
                    /* #FIN [GENERATION DES FIXTURES REVIEWS] */
                    
                /* #FIN [GENERATION DES DONNEES PRODUCTS] */

                /* #DEBUT [SETTING DES DONNEES PRODUCTS] */
                    $product->setSlug($this->slugger->slug($name));
                    $product->setName($name);
                    $product->setDescription($description);
                    $product->setPrice($price);
                    $product->setCreationDate($dateProduct);
                    $product->setCrush($crush);
                    $product->setColorList($colors);
                    $product->setUrlImage($image);
                    $product->setSpecialOffer($specialOffer);
                    $product->setCategory($category);
                    $manager->persist($product);
                /* #FIN [SETTING DES DONNEES PRODUCTS] */


            }
         /* #FIN [GENERATION DES FIXTURES PRODUCT] */

        $manager->flush();
    }
}

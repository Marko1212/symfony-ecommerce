<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
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

        $manager->flush();
    }
}

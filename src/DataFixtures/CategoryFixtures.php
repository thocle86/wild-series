<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        "Action",
        "Animation",
        "Aventure",
        "Catastrophe",
        "Documentaire",
        "Drame",
        "Erotique",
        "Espionnage",
        "Fantastique",
        "Fantasy",
        "Guerre",
        "Historique",
        "Horreur",
        "Humoristique",
        "Médicale",
        "Musicale",
        "Policière",
        "Politique",
        "Romantique",
        "Science-fiction",
        "Super-héros",
        "Western"
    ];

    public function load(ObjectManager $manager)
    {
        foreach(self::CATEGORIES as $key => $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            $this->addReference('category_' . $key, $category);
        }
        $manager->flush();
    }
}

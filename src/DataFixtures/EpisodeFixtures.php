<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 6; $i++) {
            for ($y = 0; $y < 3; $y++) {
                for ($z = 0; $z < 10; $z++) {
                    $episode = new Episode();
                    $episode->setSeason($this->getReference('season_'.$i.'_'.$y));
                    $episode->setTitle($faker->sentence($nbWords = 2, $variableNbWords = true));
                    $episode->setNumber($z+1);
                    $episode->setSynopsis($faker->paragraph($nbSentences = 20, $variableNbSentences = true));
                    $manager->persist($episode);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}

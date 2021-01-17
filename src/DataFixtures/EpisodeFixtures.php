<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use App\Service\Slugify;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    private $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

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
                    $episode->setSlug($this->slugify->generate($episode->getTitle()));
                    $manager->persist($episode);
                    $this->addReference('episode_'.$i.'_'.$y.'_'.$z, $episode);
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

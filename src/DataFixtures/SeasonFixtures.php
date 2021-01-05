<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 6; $i++) {
            for ($y = 0; $y < 3; $y++) {
                $season = new Season();
                $season->setProgram($this->getReference('program_'.$i));
                $season->setYear($y+2015);
                $season->setNumber($y+1);
                $season->setDescription($faker->paragraph($nbSentences = 20, $variableNbSentences = true));
                $manager->persist($season);
                $this->addReference('season_'.$i.'_'.$y, $season);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 6; $i++) {
            for ($y = 0; $y < 3; $y++) {
                for ($z = 0; $z < 10; $z++) {
                    for ($com = 0; $com < 3; $com++) {
                        $comment = new Comment();
                        $comment->setAuthor($this->getReference('machin'));
                        $comment->setEpisode($this->getReference('episode_'.$i.'_'.$y.'_'.$z));
                        $comment->setComment($faker->sentence($nbWords = 6, $variableNbWords = true));
                        $comment->setRate($faker->randomDigit);
                        $manager->persist($comment);
                    }
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [EpisodeFixtures::class];
    }
}

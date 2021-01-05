<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS = [
        'actor1' => [
            'firstname' => 'Andrew',
            'lastname' => 'Lincoln',
            'birth_date' => '1973-09-14',
            'programs' => 'program_0',
        ],
        'actor2' => [
            'firstname' => 'Norman',
            'lastname' => 'Reedus',
            'birth_date' => '1969-01-06',
            'programs' => 'program_0',
        ],
        'actor3' => [
            'firstname' => 'Melissa',
            'lastname' => 'McBride',
            'birth_date' => '1965-05-23',
            'programs' => 'program_0',
        ],
        'actor4' => [
            'firstname' => 'Danai',
            'lastname' => 'Gurira',
            'birth_date' => '1978-02-14',
            'programs' => 'program_0',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        $i = 0;
        foreach(self::ACTORS as $title => $data) {
            $actor = new Actor();
            $actor->setFirstname($data['firstname']);
            $actor->setLastname($data['lastname']);
            $actor->setBirthDate(\DateTime::createFromFormat('Y-m-d', $data['birth_date']));
            $actor->addProgram($this->getReference($data['programs']));
            $manager->persist($actor);
            $this->addReference('actor_' . $i, $actor);
            $i++;
        }

        $faker = Faker\Factory::create('fr_FR');
        for ($i = 4; $i < 50; $i++) {
            $actor = new Actor();
            $actor->setFirstname($faker->firstName());
            $actor->setLastname($faker->lastname());
            $actor->setBirthDate(\DateTime::createFromFormat('Y-m-d', $faker->date($format = 'Y-m-d', $max = '2010-12-31')));
            $actor->addProgram($this->getReference('program_'.random_int(0, 5)));
            $manager->persist($actor);
            $this->addReference('actor_' . $i, $actor);
        }

        $manager->flush();
    }

    public function getDependencies()  
    {
        return [ProgramFixtures::class];  
    }
}

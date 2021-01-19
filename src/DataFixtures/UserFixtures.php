<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        //création d'un premier contributeur
        $contributor = new User();
        $contributor->setEmail('machin@gmail.com');
        $contributor->setRoles(['ROLE_CONTRIBUTOR']);
        $contributor->setPassword($this->passwordEncoder->encodePassword(
            $contributor,
            'machinpassword'
        ));

        $manager->persist($contributor);
        $this->addReference('machin', $contributor);

        //création d'un deuxième contributeur
        $contributor2 = new User();
        $contributor2->setEmail('bidule@hotmail.com');
        $contributor2->setRoles(['ROLE_CONTRIBUTOR']);
        $contributor2->setPassword($this->passwordEncoder->encodePassword(
            $contributor2,
            'bidulepassword'
        ));

        $manager->persist($contributor2);
        $this->addReference('bidule', $contributor2);

        // Création d’un utilisateur de type “administrateur”
        $admin = new User();
        $admin->setEmail('admin@monsite.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'adminpassword'
        ));

        $manager->persist($admin);
        $this->addReference('admin', $admin);

        // Sauvegarde des 2 nouveaux utilisateurs :
        $manager->flush();
    }

    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Reparation;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;

    }
    public function load(ObjectManager $manager): void
    {
        //$faker = Faker\Factory::create('ES_es');
        $faker = Faker\Factory::create();

        $array = [
            ["user", "Usuari de prova", "user"],
            ["admin", "Admnistrador", "admin"],
            ["nando", "nando", "nando"]
        ];

        $status = ['reparacio', 'llest', 'diagnostic', 'entregat'];



        $totalUsers = count($array);
        $users = [];


        for ($i=0; $i < $totalUsers; $i++) {
            $user = new User();
            $user->setUsername($array[$i][0]);
            $user->setName($array[$i][1]);
            $user->setProfile('default');

            $hashedPassword = $this->passwordHasher->hashPassword($user, $array[$i][2]);
            $user->setPassword($hashedPassword);

            $user->setLastNames('a');
            $user->setEmail('prova'.$i.'@email.com');
            $user->setRole('ROLE_USER');
            $manager->persist($user);
            $users[] = $user;
        }

        for ($i=0; $i<30; $i++) {
            $reparation = new Reparation();
            $reparation->setOwner($users[rand(0, $totalUsers-1)]);
            $reparation->setDescription($faker->text(250));
            $reparation->setName($faker->text(40));
            $reparation->setDate($faker->dateTimeInInterval('-1 year'));
            $reparation->setStatus($status[rand(0,count($status)-1)]);
            $manager->persist($reparation);
        }

        $manager->flush();
    }
}

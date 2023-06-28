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
            ["user01", "user01", "user01"],
            ["user02", "user02", "user02"],
            ["treballador1", "treballador1", "treballador1"],
            ["treballador2", "treballador2", "treballador2"],
            ["administrador", "administrador", "administrador"]
        ];

        $normalUsers = [
            ["user01", "user01", "user01"],
            ["user02", "user02", "user02"]
        ];

        $workers = [
            ["treballador1", "treballador1", "treballador1"],
            ["treballador2", "treballador2", "treballador2"]
        ];

        $status = ['EN REPARACIO', 'LLEST PER A ARREPLEGAR', 'EN DIAGNOSTIC', 'ENTREGAT'];



        $totalUsers = count($array);
        $totalNormal = count($normalUsers);
        $totalWorker = count($workers);
        $users = [];


        for ($i=0; $i < $totalNormal; $i++) {
            $user = new User();
            $user->setUsername($normalUsers[$i][0]);
            $user->setName($normalUsers[$i][1]);
            $user->setProfile('default');

            $hashedPassword = $this->passwordHasher->hashPassword($user, $array[$i][2]);
            $user->setPassword($hashedPassword);

            $user->setLastNames('a');
            $user->setEmail('prova'.$i.'@email.com');
            $user->setRole('ROLE_USER');
            $manager->persist($user);
            $userN[] = $user;
        }

        for ($i=0; $i < $totalWorker; $i++) {
            $user = new User();
            $user->setUsername($workers[$i][0]);
            $user->setName($workers[$i][1]);
            $user->setProfile('default');

            $hashedPassword = $this->passwordHasher->hashPassword($user, $array[$i][2]);
            $user->setPassword($hashedPassword);

            $user->setLastNames('a');
            $user->setEmail('prova1'.$i.'@email.com');
            $user->setRole('ROLE_WORKER');
            $manager->persist($user);
            $userW[] = $user;
        }

        $user = new User();
        $user->setUsername('administrador');
        $user->setName('administrador');
        $user->setProfile('default');

        $hashedPassword = $this->passwordHasher->hashPassword($user, 'administrador');
        $user->setPassword($hashedPassword);

        $user->setLastNames('a');
        $user->setEmail('prova1'.$i.'@email.com');
        $user->setRole('ROLE_ADMIN');
        $manager->persist($user);

        for ($i=0; $i<30; $i++) {
            $reparation = new Reparation();
            $reparation->setOwner($userN[rand(0, $totalNormal-1)]);
            $reparation->setReparator($userW[rand(0, $totalWorker-1)]);
            $reparation->setDescription($faker->text(250));
            $reparation->setName($faker->text(45));
            $reparation->setDate($faker->dateTimeInInterval('-1 year'));
            $reparation->setStatus($status[rand(0,count($status)-1)]);
            $manager->persist($reparation);
        }

        $manager->flush();
    }
}

<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class DefaultApiFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $group1 = new Group('group_1');
        $group1->setId(1);
        $manager->persist($group1);

        $user = new User();
        $user->setId(1);
        $user->setName('user_1');
        $user->setEmail('user_1@test.com');
        $user->addGroups([$group1]);
        $manager->persist($user);

        $group2 = new Group('group_2');
        $group2->setId(2);
        $manager->persist($group2);

        $user = new User();
        $user->setId(2);
        $user->setName('user_2');
        $user->setEmail('user_2@test.com');
        $user->addGroups([$group2]);
        $manager->persist($user);

        $group3 = new Group('group_3');
        $group3->setId(3);
        $manager->persist($group3);

        $user = new User();
        $user->setId(3);
        $user->setName('user_3');
        $user->setEmail('user_3@test.com');
        $user->addGroups([$group3, $group2]);
        $manager->persist($user);

        $group4 = new Group('group_4');
        $group4->setId(4);
        $manager->persist($group4);

        $user = new User();
        $user->setId(4);
        $user->setName('user_4');
        $user->setEmail('user_4@test.com');
        $user->addGroups([$group1]);
        $manager->persist($user);

        $group5 = new Group('group_5');
        $group5->setId(5);
        $manager->persist($group5);

        $user = new User();
        $user->setId(5);
        $user->setName('user_5');
        $user->setEmail('user_5@test.com');
        $manager->persist($user);
        $user->addGroups([$group5, $group3, $group2]);
        $manager->persist($user);

        $group6 = new Group('group_6');
        $group6->setId(6);
        $manager->persist($group6);

        $user = new User();
        $user->setId(6);
        $user->setName('user_6');
        $user->setEmail('user_6@test.com');
        $user->addGroups([$group6, $group5]);
        $manager->persist($user);

        $manager->flush();
    }
}

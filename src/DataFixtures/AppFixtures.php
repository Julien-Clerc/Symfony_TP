<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Author;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;



class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $author = (new Author())
            ->setFirstname('Julien')
            ->setLastname('Clerc');

        $manager->persist($author);

        $manager->flush();
    }
}

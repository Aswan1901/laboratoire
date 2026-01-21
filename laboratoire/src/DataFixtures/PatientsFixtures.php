<?php

namespace App\DataFixtures;

use App\Entity\Patient;
use App\Entity\Sample;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PatientsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $patient = new Patient();
            $patient->setName($faker->firstName());

            $manager->persist($patient);


            $this->addReference('patient_' . $i, $patient);
        }

        $manager->flush();
    }
}


<?php

namespace App\DataFixtures;

use App\Entity\Technician;
use App\Enum\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TechniciansFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $arrayTypes = [Type::TISSUE, Type::URINE, Type::BLOOD, Type::GENERAL];

        $randTime = new \DateTimeImmutable();

        for ($i = 0; $i < 10; $i++) {
            $technician = new Technician();
            $technician->setName($faker->firstName());
            $technician->setSpeciality($arrayTypes[array_rand($arrayTypes)]);
            $technician->setStartTime($randTime->setTime(mt_rand(0,12),mt_rand(0,59)));
            $technician->setEndTime($randTime->setTime(mt_rand(13,23),mt_rand(0,59)));
            $manager->persist($technician);
        }
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Patient;
use App\Entity\Sample;
use App\Enum\Priority;
use App\Enum\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SamplesFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $typeArray = [Type::BLOOD, Type::TISSUE, Type::URINE];
        $priorityArray = [Priority::STAT, Priority::ROUTINE, Priority::URGENT];
        $patients  = $manager->getRepository(Patient::class)->findAll();
        $randTime = new \DateTimeImmutable();


        for ($i = 0; $i < 10; $i++) {
            $sample = new Sample();

            $sample->setName('Sample ' . $i);
            $sample->setType($typeArray[array_rand($typeArray)]);
            $sample->setPatient($patients[array_rand($patients)]);
            $sample->setPriority($priorityArray[array_rand($priorityArray)]);
            $sample->setArrivalTime($randTime->setTime(mt_rand(8,17),mt_rand(0,59)));

            $manager->persist($sample);
        }

        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            PatientsFixtures::class,
        ];

    }
}


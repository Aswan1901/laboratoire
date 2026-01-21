<?php

namespace App\DataFixtures;

use App\Entity\Patient;
use App\Entity\Sample;
use App\Enum\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class SamplesFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $typeArray = [Type::BLOOD, Type::TISSUE, Type::URINE];

        $patients = $manager->getRepository(Patient::class)->findAll();

        if (empty($patients)) {
            return;
        }

        for ($i = 0; $i < 10; $i++) {
            $sample = new Sample();

            // random type
            $sample->setType($typeArray[array_rand($typeArray)]);

            // random patient
            $sample->setPatient($patients[array_rand($patients)]);

            // random times (arrival < analysis)
            $baseDate = new \DateTimeImmutable();

            $arrivalMinutes  = random_int(0, 12 * 60);
            $analysisMinutes = random_int($arrivalMinutes + 1, 23 * 60 + 59);

            $arrivalTime = $baseDate->setTime(
                intdiv($arrivalMinutes, 60),
                $arrivalMinutes % 60
            );

            $analysisTime = $baseDate->setTime(
                intdiv($analysisMinutes, 60),
                $analysisMinutes % 60
            );

            $sample->setArrivalTime($arrivalTime);
            $sample->setAnalysisTime($analysisTime);

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


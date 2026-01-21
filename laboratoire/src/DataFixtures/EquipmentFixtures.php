<?php

namespace App\DataFixtures;

use App\Entity\Equipment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Enum\Type;

class EquipmentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // récupérer les types
        $typeArray = [Type::BLOOD, Type::TISSUE, Type::URINE];

        // 10 equipments
        for ($i = 0; $i < 10; $i++) {
            $equipment = new Equipment();
            $equipment->setName('Equipment ' . $i);
            $equipment->setType($typeArray[array_rand($typeArray)]);
            $manager->persist($equipment);
        }

        $manager->flush();
    }
}

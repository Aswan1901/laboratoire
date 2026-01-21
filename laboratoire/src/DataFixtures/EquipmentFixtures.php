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
        $typeArray = [Type::BLOOD, Type::TISSUE, Type::URINE];


        // 10 equipments
        for ($i = 0; $i < 10; $i++) {
            $equipment = new Equipment();
            $equipment->setType($typeArray[array_rand($typeArray)]);
            $equipment->setAvailable((bool) rand(0, 1));
            $manager->persist($equipment);
        }

        $manager->flush();
    }
}

<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Entity\Sample;
use App\Entity\Technician;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class SampleController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager)
    {
    }

    #[Route('/sample', name: 'app_sample')]
    public function AddTechnician(): JsonResponse
    {
        // Récupération des données
        $technicians = $this->manager->getRepository(Technician::class)->findAll();
        $samples     = $this->manager->getRepository(Sample::class)->findAll();
        $equipments  = $this->manager->getRepository(Equipment::class)->findAll();
        $result = [];

        /*
         * Règles métier :
         * - Priorité : STAT -> URGENT -> ROUTINE
         * - Même spécialité/type
         * - Technicien disponible à l’heure d’arrivée
         * - Spécialistes avant GENERAL
         * - Un technicien général peut utiliser n'importe quel équipement disponible
         */
        foreach (['STAT', 'URGENT', 'ROUTINE'] as $priority) {

            foreach ($samples as $sample) {
                if ($sample->getPriority() !== $priority) {
                    continue;
                }

                $arrival  = $sample->getArrivalTime();
                $assigned = false;

                //Trouvé avec un technicien SPÉCIALISÉ

                foreach ($technicians as $technician) {

                    // La spécialité doit correspondre au type du sample
                    if ($technician->getSpeciality() !== $sample->getType()) {
                        continue;
                    }

                    // Vérification de la disponibilité du technicien
                    if (
                        $arrival < $technician->getStartTime()
                        || $arrival > $technician->getEndTime()
                    ) {
                        continue;
                    }

                    // Assignation du sample au technicien
                    $technician->setSampleToAnalyse($sample->getName());

                    // Assignation d’UN équipement compatible et disponible (disponibilité "true" par défaut)
                    $assignedEquipment = null;
                    foreach ($equipments as $equipment) {
                        if ($equipment->getType() === $technician->getSpeciality()  && $equipment->isAvailable())
                        {
                            $technician->addEquipment($equipment->getName());
                            $equipment->setAvailable(false);
                            $assignedEquipment = $equipment->getName();
                            break;
                        }
                    }

                    // Sauvegarde pour le retour JSON
                    $result[] = [
                        'technician_type' => 'SPECIALIST',
                        'sample'     => $sample->getName(),
                        'priority'   => $sample->getPriority(),
                        'name' => $technician->getName(),
                        'equipment'  => $assignedEquipment,
                    ];

                    $assigned = true;
                    break;
                }

                /*
                 * technicien GENERAL
                 * seulement si aucun spécialiste n’a été trouvé
                 */
                if (!$assigned) {
                    foreach ($technicians as $technician) {

                        if ($technician->getSpeciality() !== 'GENERAL') {
                            continue;
                        }

                        if (
                            $arrival < $technician->getStartTime()
                            || $arrival > $technician->getEndTime()
                        ) {
                            continue;
                        }

                        $technician->setSampleToAnalyse($sample->getName());

                        $assignedEquipment = null;
                        foreach ($equipments as $equipment) {
                            if ($equipment->isAvailable()) {
                                $technician->addEquipment($equipment->getName());
                                $equipment->setAvailable(false);
                                $assignedEquipment = $equipment->getName();
                                break;
                            }
                        }

                        $result[] = [
                            'technician_type' => 'GENERALIST',
                            'sample'     => $sample->getName(),
                            'priority'   => $sample->getPriority(),
                            'name' => $technician->getName(),
                            'equipment'  => $assignedEquipment,
                        ];
                        break;
                    }
                }
            }
        }
        return new JsonResponse([
            'status' => 'OK',
            'assigned-samples' => $result,
        ]);
    }
}

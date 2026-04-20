<?php

namespace App\Controller;

use App\Service\JsonDataLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\SchedulerService;
use Symfony\Component\Serializer\SerializerInterface;


final class SampleController extends AbstractController
{
    public function __construct(
        private SchedulerService $schedulerService,
        private JsonDataLoader $loader,
        private SerializerInterface $serializer,
    ) {}

    #[Route('/data', name: 'app_samples_data', methods: ['GET'])]
    public function getData(): JsonResponse
    {
        $data = [
            'samples' => $this->loader->loadSamples(),
            'technicians' => $this->loader->loadTechnicians(),
            'equipments' => $this->loader->loadEquipments(),
        ];

        $result = $this->schedulerService->planifyLab($data);
        $JsonData = $this->serializer->serialize($result, 'json');
        return JsonResponse::fromJsonString($JsonData);
    }
}

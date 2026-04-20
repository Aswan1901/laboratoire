<?php
namespace App\Service;

final class SchedulerService
{
    public function planifyLab(array $data): array
    {
        $equipments = $data['equipments'];
        $technicians = $data['technicians'];
        $samples = $data['samples'];

        $result = [];

        $techBusyUntil = [];
        $equipBusyUntil = [];

        // metrics
        $totalAnalysisTime = 0;
        $globalStart = null;
        $globalEnd = null;

        foreach (['STAT', 'URGENT', 'ROUTINE'] as $priority) {

            foreach ($samples as $sample) {

                if ($sample['priority'] !== $priority) {
                    continue;
                }

                $arrival = new \DateTimeImmutable($sample['arrivalTime']);
                $duration = $sample['analysisTime'];

                $startTime = $arrival;
                $endTime = $startTime->modify("+{$duration} minutes");

                $assigned = false;

                /*
                 * SPECIALIST
                 */
                foreach ($technicians as $tech) {

                    if (($tech['speciality'] ?? null) !== $sample['type']) {
                        continue;
                    }

                    $shiftStart = new \DateTimeImmutable($tech['startTime']);
                    $shiftEnd = new \DateTimeImmutable($tech['endTime']);

                    if ($startTime < $shiftStart || $endTime > $shiftEnd) {
                        continue;
                    }

                    if (($techBusyUntil[$tech['id']] ?? null) > $startTime) {
                        continue;
                    }

                    $assignedEquipment = null;

                    foreach ($equipments as $key => $eq) {

                        if ($eq['type'] !== $sample['type']) {
                            continue;
                        }

                        if (($equipBusyUntil[$eq['id']] ?? null) > $startTime) {
                            continue;
                        }

                        if ($eq['available'] === true) {
                            $assignedEquipment = $eq;
                            $equipments[$key]['available'] = false;
                            break;
                        }
                    }

                    if (!$assignedEquipment) {
                        continue;
                    }

                    // reserve
                    $techBusyUntil[$tech['id']] = $endTime;
                    $equipBusyUntil[$assignedEquipment['id']] = $endTime;

                    // metrics update
                    $totalAnalysisTime += $duration;

                    if ($globalStart === null || $startTime < $globalStart) {
                        $globalStart = $startTime;
                    }

                    if ($globalEnd === null || $endTime > $globalEnd) {
                        $globalEnd = $endTime;
                    }

                    $result[] = [
                        "schedule" => [
                            'technician_type' => 'SPECIALIST',
                            'sampleId' => $sample['id'],
                            'technicianId' => $tech['id'],
                            'equipmentId' => $assignedEquipment['id'],
                            'startTime' => $startTime,
                            'endTime' => $endTime,
                            'priority' => $sample['priority'],
                        ]
                    ];

                    $assigned = true;
                    break;
                }

                /*
                 * GENERAL
                 */
                if (!$assigned) {

                    foreach ($technicians as $tech) {

                        if (($tech['speciality'] ?? null) !== 'GENERAL') {
                            continue;
                        }

                        $shiftStart = new \DateTimeImmutable($tech['startTime']);
                        $shiftEnd = new \DateTimeImmutable($tech['endTime']);

                        if ($startTime < $shiftStart || $endTime > $shiftEnd) {
                            continue;
                        }

                        if (($techBusyUntil[$tech['id']] ?? null) > $startTime) {
                            continue;
                        }

                        $assignedEquipment = null;

                        foreach ($equipments as $key => $eq) {

                            if (($equipBusyUntil[$eq['id']] ?? null) > $startTime) {
                                continue;
                            }

                            if ($eq['available'] === true) {
                                $assignedEquipment = $eq;
                                $equipments[$key]['available'] = false;
                                break;
                            }
                        }

                        if (!$assignedEquipment) {
                            continue;
                        }

                        // reserve
                        $techBusyUntil[$tech['id']] = $endTime;
                        $equipBusyUntil[$assignedEquipment['id']] = $endTime;

                        // metrics update
                        $totalAnalysisTime += $duration;

                        if ($globalStart === null || $startTime < $globalStart) {
                            $globalStart = $startTime;
                        }

                        if ($globalEnd === null || $endTime > $globalEnd) {
                            $globalEnd = $endTime;
                        }

                        $result[] = [
                            "schedule" => [
                                'technician_type' => 'GENERAL',
                                'sampleId' => $sample['id'],
                                'technicianId' => $tech['id'],
                                'equipmentId' => $assignedEquipment['id'],
                                'startTime' => $startTime,
                                'endTime' => $endTime,
                                'priority' => $sample['priority'],
                            ]
                        ];

                        break;
                    }
                }
            }
        }

        // final metrics
        $makespan = ($globalStart && $globalEnd) ? ($globalEnd->getTimestamp() - $globalStart->getTimestamp()) / 60 : 0;

        $efficiency = $makespan > 0 ? $totalAnalysisTime / $makespan : 0;

        return [
            'schedule' => $result,
            'metrics' => [
                'totalAnalysisTime' => $totalAnalysisTime,
                'makespan' => $makespan,
                'efficiency' => $efficiency,
            ]
        ];
    }
}

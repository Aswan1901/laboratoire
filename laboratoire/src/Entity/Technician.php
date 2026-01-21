<?php

namespace App\Entity;

use App\Enum\Type;
use App\Repository\TechnicianRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TechnicianRepository::class)]
class Technician
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(enumType: Type::class)]
    private ?Type $speciality = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startTime = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $endTime = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sampleToAnalyse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $equipmentUsed = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSpeciality(): ?Type
    {
        return $this->speciality;
    }

    public function setSpeciality(Type $speciality): static
    {
        $this->speciality = $speciality;

        return $this;
    }

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeImmutable $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeImmutable
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeImmutable $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getSampleToAnalyse(): ?string
    {
        return $this->sampleToAnalyse;
    }

    public function setSampleToAnalyse(?string $sampleToAnalyse): static
    {
        $this->sampleToAnalyse = $sampleToAnalyse;

        return $this;
    }

    public function getEquipmentUsed(): ?string
    {
        return $this->equipmentUsed;
    }

    public function setEquipmentUsed(?string $equipmentUsed): static
    {
        $this->equipmentUsed = $equipmentUsed;

        return $this;
    }
}

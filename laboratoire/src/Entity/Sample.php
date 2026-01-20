<?php

namespace App\Entity;

use App\Enum\Type;
use App\Repository\SampleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SampleRepository::class)]
class Sample
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: Type::class)]
    private ?Type $type = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $analysisTime = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $arrivalTime = null;

    #[ORM\ManyToOne(inversedBy: 'sampleId')]
    private ?Patient $patient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(Type $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAnalysisTime(): ?\DateTimeImmutable
    {
        return $this->analysisTime;
    }

    public function setAnalysisTime(?\DateTimeImmutable $analysisTime): static
    {
        $this->analysisTime = $analysisTime;

        return $this;
    }

    public function getArrivalTime(): ?\DateTimeImmutable
    {
        return $this->arrivalTime;
    }

    public function setArrivalTime(\DateTimeImmutable $arrivalTime): static
    {
        $this->arrivalTime = $arrivalTime;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }
}

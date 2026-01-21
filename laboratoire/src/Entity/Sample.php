<?php

namespace App\Entity;

use App\Enum\Priority;
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
    private ?\DateInterval $analysisDuration = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $arrivalTime = null;

    #[ORM\ManyToOne(inversedBy: 'sampleId')]
    private ?Patient $patient = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(enumType: Priority::class)]
    private ?Priority $priority = null;

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

    public function getAnalysisDuration(): ?\DateInterval
    {
        return $this->analysisDuration;
    }

    public function setAnalysisDuration(?\DateInterval $analysisDuration): self
    {
        if ($analysisDuration !== null && $this->getPriority() === 'STAT') {

            $minutes = ($analysisDuration->h * 60) + $analysisDuration->i;
            if ($minutes < 59) {
                throw new \InvalidArgumentException(
                    'Un échantillon STAT ne peut pas dépasser 59 minutes.'
                );
            }
    }

        $this->analysisDuration = $analysisDuration;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPriority(): ?Priority
    {
        return $this->priority;
    }

    public function setPriority(Priority $priority): static
    {
        $this->priority = $priority;

        return $this;
    }
}

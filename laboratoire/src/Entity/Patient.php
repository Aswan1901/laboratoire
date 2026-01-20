<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Sample>
     */
    #[ORM\OneToMany(targetEntity: Sample::class, mappedBy: 'patient')]
    private Collection $sampleId;

    public function __construct()
    {
        $this->sampleId = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Sample>
     */
    public function getSampleId(): Collection
    {
        return $this->sampleId;
    }

    public function addSampleId(Sample $sampleId): static
    {
        if (!$this->sampleId->contains($sampleId)) {
            $this->sampleId->add($sampleId);
            $sampleId->setPatient($this);
        }

        return $this;
    }

    public function removeSampleId(Sample $sampleId): static
    {
        if ($this->sampleId->removeElement($sampleId)) {
            // set the owning side to null (unless already changed)
            if ($sampleId->getPatient() === $this) {
                $sampleId->setPatient(null);
            }
        }

        return $this;
    }
}

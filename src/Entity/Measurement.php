<?php

namespace App\Entity;

use App\Repository\MeasurementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeasurementRepository::class)]
#[ORM\Table(name: 'measurement')]
class Measurement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    private ?int $id = null;

    #[ORM\Column(nullable: false)]
    private ?int $year = null;

    #[ORM\Column(length: 30, nullable: false)]
    private ?string $color = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: false)]
    private ?float $graduation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: false)]
    private ?float $temperature = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: false)]
    private ?float $ph = null;

    #[ORM\ManyToOne(inversedBy: 'measurements', targetEntity: Sensor::class)]
    #[ORM\JoinColumn(nullable: false, name: 'sensor_id', referencedColumnName: 'id')]
    private ?Sensor $sensor = null;

    #[ORM\ManyToOne(inversedBy: 'measurements', targetEntity: Wine::class)]
    #[ORM\JoinColumn(nullable: false, name: 'wine_id', referencedColumnName: 'id')]
    private ?Wine $wine = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getGraduation(): ?float
    {
        return $this->graduation;
    }

    public function setGraduation(float $graduation): static
    {
        $this->graduation = $graduation;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getPh(): ?float
    {
        return $this->ph;
    }

    public function setPh(float $ph): static
    {
        $this->ph = $ph;

        return $this;
    }

    public function getSensor(): ?Sensor
    {
        return $this->sensor;
    }

    public function setSensor(?Sensor $sensor): static
    {
        $this->sensor = $sensor;

        return $this;
    }

    public function getWine(): ?Wine
    {
        return $this->wine;
    }

    public function setWine(?Wine $wine): static
    {
        $this->wine = $wine;

        return $this;
    }
}

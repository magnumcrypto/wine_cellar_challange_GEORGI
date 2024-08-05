<?php

namespace App\Repository;

use App\Entity\Measurement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Measurement>
 */
class MeasurementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Measurement::class);
    }

    public function getMeasurementsByWineId(int $idWine): array
    {
        $measurements = $this->findBy(['wine' => $idWine]);
        if (empty($measurements)) {
            return [];
        }
        $allMeasurements = [];
        foreach ($measurements as $measurement) {
            $allMeasurements[] = [
                'year' => $measurement->getYear(),
                'color' => $measurement->getColor(),
                'graduation' => $measurement->getGraduation(),
                'temperature' => $measurement->getTemperature(),
                'ph' => $measurement->getPh()
            ];
        }
        return $allMeasurements;
    }
}

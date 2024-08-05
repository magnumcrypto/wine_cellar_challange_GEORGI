<?php

namespace App\Repository;

use App\Entity\Wine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wine>
 */
class WineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wine::class);
    }

    public function getAllWines(MeasurementRepository $measurementRepository): array
    {
        $wines = $this->findAll();
        $allWines = ['wines' => []];
        foreach ($wines as $wine) {
            $allWines['wines'][] = [
                'name' => $wine->getName(),
                'year' => $wine->getYear(),
                'measurements' => $measurementRepository->getMeasurementsByWineId($wine->getId())
            ];
        }
        return $allWines;
    }
}

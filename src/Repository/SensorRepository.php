<?php

namespace App\Repository;

use App\Entity\Sensor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sensor>
 */
class SensorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sensor::class);
    }

    public function getSensorsOrderedByName(): array
    {
        $sensors = $this->findBy([], ['name' => 'ASC']);
        $orderedSensors = ['sensors' => []];
        //return sensors oredered by name
        foreach ($sensors as $sensor) {
            $orderedSensors['sensors'][] = ['name' => $sensor->getName()];
        }
        return $orderedSensors;
    }
}

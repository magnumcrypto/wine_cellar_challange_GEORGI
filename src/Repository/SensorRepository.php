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

    public function save(Sensor $sensor, bool $flush = false): void
    {
        $this->getEntityManager()->persist($sensor);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
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

    public function insertSensor(object $dataSensor): ?int
    {
        if (!isset($dataSensor->name) || empty($dataSensor->name)) {
            return null;
        }
        try {
            $sensor = new Sensor();
            $sensor->setName($dataSensor->name);
            $this->save($sensor, true);
            return $sensor->getId();
        } catch (\Exception $e) {
            echo 'Error inserting sensor: ' . $e->getMessage();
            return null;
        }
    }
}

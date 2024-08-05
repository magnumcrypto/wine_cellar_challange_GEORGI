<?php

namespace App\Repository;

use App\Entity\Measurement;
use App\Entity\Sensor;
use App\Entity\Wine;
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

    public function save(Measurement $measurement, bool $flush = false): void
    {
        $this->getEntityManager()->persist($measurement);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
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

    public function insertMeasurement(object $data, Wine $wine, Sensor $sensor): ?int
    {
        if (!isset($data) || empty($data)) {
            return null;
        }
        try {
            $measurement = new Measurement();
            $measurement
                ->setYear((int)$data->year)
                ->setColor($data->color)
                ->setGraduation((float)$data->graduation)
                ->setTemperature((float)$data->temperature)
                ->setPh((float)$data->ph)
                ->setSensor($sensor)
                ->setWine($wine);
            $this->save($measurement, true);
            return $measurement->getId();
        } catch (\Exception $e) {
            echo 'Error inserting measurement: ' . $e->getMessage();
            return null;
        }
    }
}

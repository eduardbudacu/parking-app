<?php

namespace Temperworks\Codechallenge\Domain;

use Exception;

class Garage
{
    public const GROUND_FLOOR = 0;
    public const FIRST_FLOOR = 1;
    public const SECOND_FLOOR = 2;

    public const CAR = 'c';
    public const MOTORCYCLE = 'm';
    public const VAN = 'v';

    protected array $sizes = [
        self::CAR => 1,
        self::MOTORCYCLE => 0.5,
        self::VAN => 1.5,
    ];

    protected array $floorsCapacities = [
        self::GROUND_FLOOR => 0,
        self::FIRST_FLOOR => 0,
        self::SECOND_FLOOR => 0,
    ];

    protected array $occupiedSpaces = [
        self::GROUND_FLOOR => 0,
        self::FIRST_FLOOR => 0,
        self::SECOND_FLOOR => 0,
    ];

    protected const ALL_FLOORS = [self::GROUND_FLOOR, self::FIRST_FLOOR, self::SECOND_FLOOR];

    protected array $parkingRules = [
        self::CAR => self::ALL_FLOORS,
        self::MOTORCYCLE => self::ALL_FLOORS,
        self::VAN => [self::GROUND_FLOOR],
    ];

    public function intakeVehicle(string $vehicle): bool
    {
        if (!in_array($vehicle, array_keys($this->sizes))) {
            throw new Exception('Invalid vehicle type');
        }

        foreach ($this->parkingRules[$vehicle] as $level) {
            if ($this->hasSpace($level, $vehicle)) {
                $this->occupySpace($level, $vehicle);

                return true;
            }
        }

        return false;
    }

    public function setCapacity(int $floor, int $capacity)
    {
        $this->checkFloorType($floor);
        $this->floorsCapacities[$floor] = $capacity;
    }

    public function getOccupiedSpace(int $floor): float
    {
        $this->checkFloorType($floor);

        return $this->occupiedSpaces[$floor];
    }

    protected function checkFloorType(int $floor): void
    {
        if (!in_array($floor, array_keys($this->floorsCapacities))) {
            throw new Exception('Invalid floor type');
        }
    }

    protected function hasSpace(int $floor, string $vehicle): bool
    {
        return ($this->occupiedSpaces[$floor] + $this->sizes[$vehicle]) <= $this->floorsCapacities[$floor];
    }

    protected function occupySpace(int $floor, string $vehicle): void
    {
        $this->occupiedSpaces[$floor] += $this->sizes[$vehicle];
    }
}

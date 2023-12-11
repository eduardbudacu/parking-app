<?php

namespace Domain;

use PHPUnit\Framework\TestCase;
use Temperworks\Codechallenge\Domain\Garage;

final class GarageTest extends TestCase
{
    public function testSpaceAllocation()
    {
        $sequence = ['c', 'c', 'm', 'v', 'c', 'm'];
        $results = [true, true, true, false, true, true];
        $garage = new Garage();
        $garage->setCapacity(Garage::GROUND_FLOOR, 3);
        $garage->setCapacity(Garage::FIRST_FLOOR, 3);
        $garage->setCapacity(Garage::SECOND_FLOOR, 3);
        foreach ($sequence as $key => $value) {
            $this->assertEquals($results[$key], $garage->intakeVehicle($value));
        }
        $this->assertEquals(3, $garage->getOccupiedSpace(Garage::GROUND_FLOOR));
        $this->assertEquals(1, $garage->getOccupiedSpace(Garage::FIRST_FLOOR));
        $this->assertEquals(0, $garage->getOccupiedSpace(Garage::SECOND_FLOOR));
    }
}

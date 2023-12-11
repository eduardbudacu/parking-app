<?php

namespace Presentation;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Temperworks\Codechallenge\Presentation\Cli;
use Temperworks\Codechallenge\Domain\Garage;

final class CliTest extends TestCase
{
    public function testCarIsAllowedToPark()
    {
        $garage = $this->createMock(Garage::class);
        $garage->expects($this->exactly(3))
            ->method('setCapacity');
        $garage->expects($this->once())
            ->method('intakeVehicle')
            ->with(Garage::CAR)
            ->willReturn(true);
        $application = new Application();
        $application->add(new Cli($garage));

        $command = $application->find('app:parking');

        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['1', '1', '1', 'c', 'q']);

        $commandTester->execute([]);
        $this->assertStringContainsString('Welcome, please go in', $commandTester->getDisplay());
    }

    public function testCarIsNotAllowedToPark()
    {
        $garage = $this->createMock(Garage::class);
        $garage->expects($this->exactly(3))
            ->method('setCapacity');
        $garage->expects($this->once())
            ->method('intakeVehicle')
            ->with(Garage::CAR)
            ->willReturn(false);
        $application = new Application();
        $application->add(new Cli($garage));

        $command = $application->find('app:parking');

        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['0', '0', '0', 'c', 'q']);

        $commandTester->execute([]);
        $this->assertStringContainsString('Sorry, no spaces left', $commandTester->getDisplay());
    }

    public function testCapacityIsValidated()
    {
        $garage = $this->createMock(Garage::class);
        $garage->expects($this->exactly(3))
            ->method('setCapacity');
        $garage->expects($this->once())
            ->method('intakeVehicle')
            ->with(Garage::CAR)
            ->willReturn(false);
        $application = new Application();
        $application->add(new Cli($garage));

        $command = $application->find('app:parking');

        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['abc', '0', '0', '0', 'c', 'q']);

        $commandTester->execute([]);
        $this->assertStringContainsString(' The answer must be a number.', $commandTester->getDisplay());
    }

    public function testVehicleIsValidated()
    {
        $garage = $this->createMock(Garage::class);
        $garage->expects($this->exactly(3))
            ->method('setCapacity');
        $application = new Application();
        $application->add(new Cli($garage));

        $command = $application->find('app:parking');

        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['0', '0', '0', 'd', 'q']);

        $commandTester->execute([]);
        $this->assertStringContainsString('Vehicle d is invalid.', $commandTester->getDisplay());
    }
}

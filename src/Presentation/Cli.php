<?php

namespace Temperworks\Codechallenge\Presentation;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

use Symfony\Component\Console\Question\Question;
use Temperworks\Codechallenge\Domain\Garage;

#[AsCommand(
    name: 'app:parking',
    description: 'Parking management system',
)]
class Cli extends Command
{
    public function __construct(protected Garage $garage)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->setup($input, $output);
        $this->parkVehicles($input, $output);

        return Command::SUCCESS;
    }

    protected function parkVehicles(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        do {
            $question = new ChoiceQuestion(
                'Please select your vehicle type',
                [Garage::CAR => 'Car', Garage::MOTORCYCLE => 'Motorcycle', Garage::VAN => 'Van', 'q' => 'Quit'],
                Garage::CAR
            );
            $question->setErrorMessage('Vehicle %s is invalid.');
            $choice = $helper->ask($input, $output, $question);
            if ($choice !== 'q') {
                if ($this->garage->intakeVehicle($choice)) {
                    $output->writeln('Welcome, please go in');
                } else {
                    $output->writeln('Sorry, no spaces left');
                }
            }
        } while ($choice != 'q');
    }

    protected function setup(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelper('question');
        $floorMap = [
            Garage::GROUND_FLOOR => 'ground floor',
            Garage::FIRST_FLOOR => 'first floor',
            Garage::SECOND_FLOOR => 'second floor',
        ];
        foreach ($floorMap as $floor => $floorName) {
            $question = new Question('Please define capacity for '.$floorName.': ', 0);
            $question->setValidator(function ($answer) {
                if (!is_numeric($answer)) {
                    throw new \RuntimeException(
                        'The answer must be a number.'
                    );
                }

                return $answer;
            });
            $capacity = $helper->ask($input, $output, $question);
            $this->garage->setCapacity($floor, $capacity);
        }
    }
}

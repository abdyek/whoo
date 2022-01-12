<?php

namespace Abdyek\Whoo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Init extends Command
{
    protected static $defaultName = 'init';

    protected function configure(): void
    {
        $this->setDescription('Creates all necessary files of whoo');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $command = $this->getApplication()->find('propel:create');

        $inputArray = new ArrayInput([]);
        $returnCode = $command->run($inputArray, $output);

        if($returnCode !== Command::SUCCESS) {
            return $returnCode;
        }

        return Command::SUCCESS;
    }
}

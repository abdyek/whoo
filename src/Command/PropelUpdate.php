<?php

namespace Abdyek\Whoo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PropelUpdate extends Command
{
    protected static $defaultName = 'propel:update';

    protected function configure(): void
    {
        $this->setDescription('Updates config and model files of Propel');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command::SUCCESS;
    }
}



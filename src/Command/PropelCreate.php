<?php

namespace Abdyek\Whoo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PropelCreate extends Command
{
    protected static $defaultName = 'propel:create';

    protected function configure(): void
    {
        $this->setDescription('Creates config and model files of Propel');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        echo 'here will be creator codes';
        return Command::SUCCESS;
    }
}

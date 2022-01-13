<?php

namespace Abdyek\Whoo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// TODO: This commands don't have any test. This commands must be refactored and have test codes.

class Init extends Command
{
    protected static $defaultName = 'init';

    protected function configure(): void
    {
        $this->setDescription('Creates all necessary files of whoo');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if(APP === 'prod') {
            copy('vendor/abdyek/whoo/whoo-config.php', 'whoo-config.php');
        }

        if(file_exists('whoo-config.php')) {
            echo 'There is whoo-config.php file already' . PHP_EOL;
            exit();
        }
        echo 'Created empty whoo-config.php' . PHP_EOL;
        return Command::SUCCESS;
    }
}

<?php

namespace Abdyek\Whoo\Command;

use Abdyek\Whoo\Tool\Propel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PropelCreate extends Command
{
    private string $outputDir = 'whoo';
    private string $defaultConfig = 'vendor/abdyek/whoo/whoo-config.php';

    private array $dbConfig = [];

    protected static $defaultName = 'propel:create';

    protected function configure(): void
    {
        $this->setDescription('Creates config and model files of Propel');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if(!file_exists($this->outputDir)) {
            mkdir($this->outputDir);
        }
        if(!file_exists($this->outputDir . '/propel')) {
            mkdir($this->outputDir . '/propel');
        }
        $this->fetchDatabaseConfig();
        $this->generateSchema();
        $this->generatePropelConfig();
        $this->runPropelCommands();

        return Command::SUCCESS;
    }

    private function generateSchema(): void
    {
        file_put_contents($this->outputDir . '/propel/schema.xml', Propel::generateSchema($this->dbConfig['dbname']));
    }

    private function fetchDatabaseConfig(): void
    {
        $config = (file_exists('whoo-config.php')? require 'whoo-config.php': require $this->defaultConfig());
        if(!isset($config['database'])) {
            echo 'Database config not found in whoo-config.php' . PHP_EOL;
            exit();
        }
        $this->dbConfig = $config['database'];
    }

    private function generatePropelConfig(): void
    {
        $content = [
            'propel' => [
                'database' => [
                    'connections' => [
                        $this->dbConfig['dbname']=> [
                            'adapter'    => $this->dbConfig['adapter'],
                            'classname'  => 'Propel\Runtime\Connection\ConnectionWrapper',
                            'dsn'        => $this->dbConfig['dsn'],
                            'user'       => $this->dbConfig['user'],
                            'password'   => $this->dbConfig['password'],
                            'attributes' => []
                        ]
                    ]
                ],
                'runtime' => [
                    'defaultConnection' => $this->dbConfig['dbname'],
                    'connections' => [$this->dbConfig['dbname']]
                ],
                'generator' => [
                    'defaultConnection' => $this->dbConfig['dbname'],
                    'connections' => [$this->dbConfig['dbname']]
                ]
            ]
        ];

        if($this->dbConfig['utf-8']) {
            $settings = [];
            if($this->dbConfig['adapter'] === 'mysql') {
                $settings = [
                    'charset'=> 'utf8mb4',
                    'queries'=> [
                        'utf8' => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci, COLLATION_CONNECTION = utf8mb4_unicode_ci, COLLATION_DATABASE = utf8mb4_unicode_ci, COLLATION_SERVER = utf8mb4_unicode_ci'
                    ]
                ];
            } elseif($this->dbConfig['adapter'] === 'pgsql') {
                $settings = [
                    'charset'=>'utf8',
                    'queries'=> [
                        'utf8'=> "SET NAMES 'UTF8'"
                    ]
                ];
            } elseif($this->dbConfig['adapter'] === 'sqlite') {
                $settings = [
                    'charset'=>'utf8'
                ];
            } elseif($this->dbConfig['adapter'] === 'mssql') {
                $settings = [
                    'charset'=>'UTF-8'
                ];
            } elseif($this->dbConfig['adapter'] === 'oracle') {
                $settings = [
                    'charset'=>'UTF8'
                ];
            };
            $content['propel']['database']['connections'][$this->dbConfig['dbname']]['settings'] = $settings;
        }
        file_put_contents($this->outputDir . '/propel.json', json_encode($content));
    }
    
    private function runPropelCommands(): void
    {
        shell_exec('vendor/bin/propel sql:build --config-dir="' . $this->outputDir . '/propel.json" --schema-dir="' . $this->outputDir . '/propel'.'" --output-dir="' . $this->outputDir . '/propel"');
        // model build
        shell_exec('vendor/bin/propel model:build --config-dir="' . $this->outputDir . '/propel.json" --schema-dir="' . $this->outputDir . '/propel" --output-dir="' . $this->outputDir . '/propel/model"');
        // config convert
        shell_exec('vendor/bin/propel config:convert --config-dir="' . $this->outputDir . '/propel.json" --output-dir="' . $this->outputDir . '/propel"');
    }
}

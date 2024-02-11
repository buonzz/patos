<?php

namespace Buonzz\Patos\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Style\SymfonyStyle;



class MigrateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('migrate')
            ->setDescription('create tables needed to store the parsed objects from the source code');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sql = [];


        $sql[] = <<<'SQL'
            CREATE TABLE `tbl_repository` (
            `url` varchar(255) DEFAULT NULL,
            `local_path` varchar(255) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

            CREATE TABLE `tbl_branch` (
            `name` varchar(255) DEFAULT NULL,
            `repository_id` int unsigned DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

            CREATE TABLE `tbl_revision` (
            `hash` varchar(255) DEFAULT NULL,
            `message` varchar(255) DEFAULT NULL,
            `branch_id` int unsigned DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

            CREATE TABLE `tbl_file` (
            `filename` varchar(255) DEFAULT NULL,
            `path` varchar(255) DEFAULT NULL,
            `language` varchar(255) DEFAULT NULL,
            `language_version` varchar(255) DEFAULT NULL,
            `revision_id` int unsigned DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

            CREATE TABLE `tbl_class` (
            `name` varchar(255) DEFAULT NULL,
            `extends` varchar(255) DEFAULT NULL,
            `start_line` int unsigned DEFAULT NULL,
            `end_line` int unsigned DEFAULT NULL,
            `file` varchar(255) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

            CREATE TABLE `tbl_method` (
            `name` varchar(255) DEFAULT NULL,
            `class` varchar(255) DEFAULT NULL,
            `start_line` int unsigned DEFAULT NULL,
            `end_line` int unsigned DEFAULT NULL,
            `file` varchar(255) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

            CREATE TABLE `tbl_functions` (
            `name` varchar(255) DEFAULT NULL,
            `start_line` int unsigned DEFAULT NULL,
            `end_line` int unsigned DEFAULT NULL,
            `file` varchar(255) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;


            CREATE TABLE `tbl_constant` (
            `name` varchar(255) DEFAULT NULL,
            `line` int unsigned DEFAULT NULL,
            `file` varchar(255) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

            CREATE TABLE `tbl_key_values` (
            `array_key` varchar(255) DEFAULT NULL,
            `array_value` TEXT,
            `line` int unsigned DEFAULT NULL,
            `file` varchar(255) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;
        SQL;

        foreach($sql as $item){
            echo $item . "\n\n";
        }

    }

}
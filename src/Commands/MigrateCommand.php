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
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `url` varchar(255) DEFAULT NULL,
            `local_path` varchar(255) DEFAULT NULL,
            `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `date_added` (`date_added`),
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

            CREATE TABLE `tbl_branch` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) DEFAULT NULL,
            `repository_id` int(10) unsigned DEFAULT NULL,
            `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `date_added` (`date_added`),
            CONSTRAINT `fk_tbl_branch_repository_id` FOREIGN KEY (`repository_id`) REFERENCES `tbl_repository` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

            CREATE TABLE `tbl_revision` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `hash` varchar(255) DEFAULT NULL,
            `message` varchar(255) DEFAULT NULL,
            `branch_id` int(10) unsigned DEFAULT NULL,
            `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `date_added` (`date_added`),
            CONSTRAINT `fk_tbl_revision_branch_id` FOREIGN KEY (`branch_id`) REFERENCES `tbl_branch` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

            CREATE TABLE `tbl_file` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `filename` varchar(255) DEFAULT NULL,
            `path` varchar(255) DEFAULT NULL,
            `language` varchar(255) DEFAULT NULL,
            `language_version` varchar(255) DEFAULT NULL,
            `revision_id` int(10) unsigned DEFAULT NULL,
            `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `date_added` (`date_added`),
            CONSTRAINT `fk_tbl_revision_id` FOREIGN KEY (`revision_id`) REFERENCES `tbl_revision` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

            CREATE TABLE `tbl_class` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) DEFAULT NULL,
            `file_id` int(10) unsigned DEFAULT NULL,
            `parent_id` int(10) unsigned DEFAULT NULL,
            `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `date_added` (`date_added`),
            CONSTRAINT `fk_tbl_class_id` FOREIGN KEY (`file_id`) REFERENCES `tbl_file` (`id`),
            CONSTRAINT `fk_tbl_class_parent_id` FOREIGN KEY (`parent_id`) REFERENCES `tbl_class` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

            CREATE TABLE `tbl_method` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `access_modifier` varchar(255) DEFAULT NULL,
            `name` varchar(255) DEFAULT NULL,
            `return_type` varchar(255) DEFAULT NULL,
            `class_id` int(10) unsigned DEFAULT NULL,
            `line_id` int(10) unsigned DEFAULT NULL,
            `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `date_added` (`date_added`),
            CONSTRAINT `fk_tbl_method_class_id` FOREIGN KEY (`class_id`) REFERENCES `tbl_file` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


            CREATE TABLE `tbl_constant` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `node_type` varchar(255) DEFAULT NULL,
            `name` varchar(255) DEFAULT NULL,
            `return_type` varchar(255) DEFAULT NULL,
            `file_id` int(10) unsigned DEFAULT NULL,
            `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `date_added` (`date_added`),
            CONSTRAINT `fk_tbl_constant_id` FOREIGN KEY (`file_id`) REFERENCES `tbl_file` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        SQL;

        foreach($sql as $item){
            echo $item . "\n\n";
        }

    }

}
<?php

namespace Buonzz\Patos\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Style\SymfonyStyle;

use Buonzz\Patos\FileParser;
use Buonzz\Patos\PhpFilesList;
use Buonzz\Patos\NodeTypeIdentifier;
use PhpParser\NodeDumper;


class ParseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('parse')
            ->setDescription('parse PHP codes given a directory')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to folder where you want to parse PHP files?');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');

        if(!file_exists($path))
        {
            echo "folder is not valid\n";
            return;
        }

        $files = PhpFilesList::get($path);

        foreach($files as $file){

            $stmts = FileParser::parse($file);

            foreach($stmts as $node){

                $nodeType = NodeTypeIdentifier::identify($node);

                if($nodeType == false)
                {
                    print_r($node);
                    echo "Could not identify node\n";
                    continue;
                }

                $key = $nodeType->getKey($node);
                $value = $nodeType->getValue($node);

                echo "$key=" . json_encode($value) . "\n";
            }

        }
        return;
    }
}
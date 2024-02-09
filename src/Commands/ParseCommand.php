<?php

namespace Buonzz\Patos\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Style\SymfonyStyle;

use PhpParser\NodeDumper;
use PhpParser\{Node, NodeTraverser, NodeVisitorAbstract};

use Buonzz\Patos\FileParser;
use Buonzz\Patos\PhpFilesList;
use Buonzz\Patos\NodeTypeIdentifier;
use Buonzz\Patos\NodeVisitors\ConstantNodeVisitor;
use Buonzz\Patos\NodeVisitors\FunctionNodeVisitor;

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

        if(count($files) <=0){
            echo "No php files found inside $path\n";
            return;
        }

        foreach($files as $file){

            $stmts = FileParser::parse($file);

            // 1. File
            $path_parts = pathinfo($file);
            echo "INSERT INTO tbl_file(filename,path) VALUES('".$path_parts['filename'] . "','". $file . "');\n";


             //2. process statements
            foreach($stmts as $node){

                $nodeType = NodeTypeIdentifier::identify($node);

                if($nodeType == false)
                {
                    //echo "Could not identify node\n";
                    continue;
                }

                // 2. Get SQL of Node
                $sql = $nodeType->getSql($node, $file);
                echo $sql  . "\n";

            }

            // 3. traverse all constants
            $constant_traverser = new NodeTraverser;
            $constant_node_visitor = new ConstantNodeVisitor();
            $constant_node_visitor->set_file($file);
            $constant_traverser->addVisitor($constant_node_visitor);
            $constant_traverser->traverse($stmts);
            echo $constant_node_visitor->getSQL();

            // 4. traverse all functions
            $function_traverser = new NodeTraverser;
            $function_node_visitor = new FunctionNodeVisitor();
            $function_node_visitor->set_file($file);
            $function_traverser->addVisitor($function_node_visitor);
            $function_traverser->traverse($stmts);
            echo $function_node_visitor->getSQL();
        }
        return;
    }
}
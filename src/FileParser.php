<?php namespace Buonzz\Patos;

use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;
use PhpParser\PrettyPrinter\Standard;
use PhpParser\NodeDumper;

class FileParser {

	public static function parse($file){
        
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $prettyPrinter = new Standard;
        $traverser = new \PhpParser\NodeTraverser;
        $nodeDumper = new NodeDumper;
  
        $source = file_get_contents($file);

        try {
            $stmts = $parser->parse($source);
            $stmts = $traverser->traverse($stmts);
            return $stmts;
        } catch (Error $error) {
            return null;
        }
    }

    public static function parseCode($source){
        
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $prettyPrinter = new Standard;
        $traverser = new \PhpParser\NodeTraverser;
        $nodeDumper = new NodeDumper;
        try {
            $stmts = $parser->parse($source);
            $stmts = $traverser->traverse($stmts);
            return $stmts;
        } catch (Error $error) {
            return null;
        }
    }
}
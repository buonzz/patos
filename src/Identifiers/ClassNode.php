<?php namespace Buonzz\Patos\Identifiers;

class ClassNode {

    public function identify($node){
        // make sure this is a statement
        if(!$node instanceof \PhpParser\Node\Stmt\Class_)
            return false;
        return true;
	}

    public function getInfo($node){
        return [
            'name' => $node->name->name,
            'line' => $node->getStartLine(),
            'extends' => $node->extends->parts[0]
        ];
    }

    public function getSql($node){

        $info = $this->getInfo($node);
        return "INSERT INTO tbl_class(`name`, `line`) VALUES('". $info['name'] . "','" .  $info['line'] . ")";
    }
}
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

    public function getSql($node, $file){

        $output = '';
        $info = $this->getInfo($node);
        $output .= "INSERT INTO tbl_class(`name`, `extends`, `line`, `path`) VALUES('". $info['name'] . "','" . $info['extends'] ."','" .  $info['line'] . "', '".  $file . "');\n";

        foreach($node->stmts as $statement){
            if(get_class($statement) == 'PhpParser\Node\Stmt\ClassMethod'){
                $output .= "INSERT INTO tbl_method(`name`, `class`, `line`, `path`) VALUES('". $statement->name->name . "','" .  $info['name'] ."','" .  $statement->getStartLine() . "', '".  $file . "');\n";
            }
        }

        return $output;
    }
}
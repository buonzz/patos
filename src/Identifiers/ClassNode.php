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
            'start_line' => $node->getStartLine(),
            'end_line' => $node->getEndLine(),
            'extends' => isset($node->extends->parts[0]) ? $node->extends->parts[0] : ''
        ];
    }

    public function getSql($node, $file){

        $output = '';
        $info = $this->getInfo($node);
        $output .= "INSERT INTO tbl_class(`name`, `extends`, `start_line`, `end_line`, `file`) VALUES('". $info['name'] . "','" . $info['extends'] ."','" .  $info['start_line'] . "', '".   $info['end_line'] . "','". $file . "');\n";

        foreach($node->stmts as $statement){

            // method name
            if(get_class($statement) == 'PhpParser\Node\Stmt\ClassMethod'){
                $output .= "INSERT INTO tbl_method(`name`, `class`, `start_line`, `end_line`, `file`) VALUES('". $statement->name->name . "','" .  $info['name'] ."','" .  $statement->getStartLine() . "', '".   $statement->getEndLine() . "','" .  $file . "');\n";
            }

            // use of constant
        }

        return $output;
    }
}